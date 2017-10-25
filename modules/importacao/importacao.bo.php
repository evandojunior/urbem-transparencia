<?php

class ImportacaoBO{

	public function create(Importacao $importacao, $conn=null){
		$importacao->setTimestamp(date('Y-m-d H:i:s'));
    	ImportacaoBO::validate($importacao);
    	$importacao->save($conn);

    	return $importacao;
	}

	public function get($importacaoId){
        return Doctrine::getTable('Importacao')->find($importacaoId);
	}
    
	public function getUltimo(){
        $query = new Doctrine_Query();
        $query->select('i.*')
              ->from('importacao i')
              ->orderBy('i.timestamp DESC')
              ->limit(1);
              
        return $query->fetchOne();
	}   
	
	public function filter(Search $search){
		$fields = array(
			'id' => 'i.id',
		);
		
		$query = new Doctrine_Query();
        $query->select('i.*')
              ->from('importacao i');

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
		$importacao = $pager->execute();
		
		$search->setPager($pager);
		
		$importacaoDTO = new DTO();
		$importacaoDTO->setObj($importacao);
		$importacaoDTO->setSearch($search);

		return $importacaoDTO;
	}
		
	public function validate(Importacao $importacao){
        if($importacao->getExercicio() == '') {
            throw new Exception('É obrigatório o preenchimento do campo exercicio no arquivo config.xml');
        }
        
        if($importacao->getTimestampGeracao() == '') {
            throw new Exception('É obrigatório o preenchimento do campo timestamp_geracao no arquivo config.xml');
        }
        
        if($importacao->getUsuario() == '') {
            throw new Exception('É obrigatório o preenchimento do campo usuario no arquivo config.xml');
        }
        
        return true;
    }
}