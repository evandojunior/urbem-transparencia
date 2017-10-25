<?php

class PagamentoBO{

	public function create(Pagamento $pagamento, $conn=null){
    	PagamentoBO::validate($pagamento);
    	$pagamento->save($conn);
		$pagamento->free();

    	return $pagamento;
	}

	public function get($pagamentoId){
        return Doctrine::getTable('Pagamento')->find($pagamentoId);
	}
	
	public function filter(Search $search){
		$fields = array(
			'id' => 'p.id',
		);
		
		$query = new Doctrine_Query();
        $query->select('p.*')
              ->from('Pagamento p');

        if($search->getFilter() != null){
			$query->where($fields[$search->getFilter()].' LIKE ?', '%'.$search->getQ().'%');
		}
		
		if($search->getOrder() != null){
			$order = $fields[$search->getOrder()];
				
			if($search->getDirection() != null){
                $order.= ' '.$search->getDirection();
			}

            $query->orderBy($order);
		}

		$pager = new Doctrine_Pager($query, $search->getPage(), $search->getMax());
		$pagamento = $pager->execute();
		
		$search->setPager($pager);
		
		$pagamentoDTO = new DTO();
		$pagamentoDTO->setObj($pagamento);
		$pagamentoDTO->setSearch($search);

		return $pagamentoDTO;
	}

	public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');
        
        while(!feof($data)) { 
        	$line = fgets($data,4096);

            $pagamento = new Pagamento();
            $pagamento->setImportacaoId($importacaoId);

			$pagamento->setCodEmpenho(mb_substr($line, 0, 13));
			$pagamento->setCodEntidade(mb_substr($line, 13, 2));
			$pagamento->setNumeroPagamento(mb_substr($line, 15, 20));
            $date = mb_substr($line,35,8);
            $pagamento->setDataPagamento((mb_substr($date, 4, 4).'-'.mb_substr($date, 2, 2).'-'.mb_substr($date, 0, 2)));
			$pagamento->setValorPagamento(formatNumberPgSQL(mb_substr($line, 43, 13)));
			$pagamento->setSinalValor(mb_substr($line, 56, 1));
			$pagamento->setHistoricoPagamento(mb_substr($line, 57, 165));

            PagamentoBO::create($pagamento, $conn);
        }
        
        fclose($data);
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Pagamento p');
        $query->where('p.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }       
		
	public function validate(Pagamento $pagamento){}
}