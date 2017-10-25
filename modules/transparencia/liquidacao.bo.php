<?php

class LiquidacaoBO{

	public function create(Liquidacao $liquidacao, $conn=null){
    	LiquidacaoBO::validate($liquidacao);
    	$liquidacao->save($conn);
		$liquidacao->free();

    	return $liquidacao;
	}

	public function get($liquidacaoId){
        return Doctrine::getTable('Liquidacao')->find($liquidacaoId);
	}
	
	public function filter(Search $search){
		$fields = array(
			'id' => 'l.id',
		);
		
		$query = new Doctrine_Query();
        $query->select('l.*')
              ->from('Liquidacao l');

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
		$liquidacao = $pager->execute();
		
		$search->setPager($pager);
		
		$liquidacaoDTO = new DTO();
		$liquidacaoDTO->setObj($liquidacao);
		$liquidacaoDTO->setSearch($search);

		return $liquidacaoDTO;
	}
    
    
	public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');
        
        while(!feof($data)) { 
        	$line = fgets($data,4096);
			/*
			 * Lembrar de calcular (-1) no 1° parâmetro do mb_substr, pois a documentação começa a contar os
			 * caracteres do 1 e o mb_substr começa a contagem pelo 0
			 */  
            $liquidacao = new Liquidacao();
            
            $liquidacao->setImportacaoId($importacaoId);
            $liquidacao->setCodEmpenho(mb_substr($line,0,13));
            $liquidacao->setCodEntidade(mb_substr($line,13,2));
            $liquidacao->setCodLiquidacao(mb_substr($line,15,20));
            $date = mb_substr($line,35,8);
            $liquidacao->setDataLiquidacao((mb_substr($date, 4, 4).'-'.mb_substr($date, 2, 2).'-'.mb_substr($date, 0, 2)));
            $liquidacao->setValorLiquidacao(formatNumberPgSQL(mb_substr($line,43,13)));
            $liquidacao->setSinalValor(mb_substr($line,56,1));
            $liquidacao->setHistoricoLiquidacao(mb_substr($line,57,165));
            
		    LiquidacaoBO::create($liquidacao, $conn);
		}
        
        fclose($data);
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Liquidacao l');
        $query->where('l.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }       
		
	public function validate(Liquidacao $liquidacao){}
}