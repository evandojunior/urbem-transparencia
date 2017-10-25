<?php

class AcaoBO{

	public function create(Acao $acao, $conn=null){
    	AcaoBO::validate($acao);
    	$acao->save($conn);
		$acao->free();

    	return $acao;
	}

	public function get($acaoId){
        return Doctrine::getTable('Acao')->find($acaoId);
	}
	
	public function filter(Search $search){
		$fields = array(
			'id' => 'a.id',
		);
		
		$query = new Doctrine_Query();
        $query->select('a.*')
              ->from('Acao a');

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
		$acao = $pager->execute();
		
		$search->setPager($pager);
		
		$acaoDTO = new DTO();
		$acaoDTO->setObj($acao);
		$acaoDTO->setSearch($search);

		return $acaoDTO;
	}

    public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');
        
        while(!feof($data)) { 
        	$line = fgets($data,4096);
            
            $acao = new Acao();
            $acao->setImportacaoId($importacaoId);
            $acao->setExercicio(mb_substr($line,0,4));
            $acao->setCodProjeto(mb_substr($line,4,5));
            $acao->setNomeProjeto(mb_substr($line,9,80));

            AcaoBO::create($acao, $conn);
        }
        
        fclose($data);
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Acao a');
        $query->where('a.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }
		
	public function validate(Acao $acao){}
}
