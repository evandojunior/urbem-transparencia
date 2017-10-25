<?php

class HistoricoBO{

	public function create(Historico $historico, $conn=null){
		try{
			$historico->setCreated(date('Y-m-d H:i:s'));
			HistoricoBO::validate($historico);
			$historico->save($conn);
			
			return $historico;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function update(Historico $historico, $conn=null){
		try{			
			$historico->setUpdated(date('Y-m-d H:i:s'));
			HistoricoBO::validate($historico);
			$historico->save($conn);

			return $historico;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function delete($historicoId, $conn=null){
        return Doctrine::getTable('Historico')->find($historicoId)->delete();
	}
	
	public function get($historicoId){
        return Doctrine::getTable('Historico')->find($historicoId);
	}
	
	public function filter(Search $search){
		$fields = array(
			'id'   	   	   => 'h.id',
            'descricao'    => 'h.descricao',
			'pessoa_nome'  => 'p.nome',
			'modulo_nome'  => 'm.modulo',
			'created'      => 'h.created',
		);
		
		$query = new Doctrine_Query();
        $query->select('h.*')
              ->from('Historico h')
              ->innerJoin('h.Modulo m')
              ->innerJoin('h.Pessoa p');

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
		$historico = $pager->execute();
		
		$search->setPager($pager);
		
		$historicoDTO = new DTO();
		$historicoDTO->setObj($historico);
		$historicoDTO->setSearch($search);

		return $historicoDTO;
	}
	
	public function getHistoricoByEntidade($module, $entidadeId){
		try{
			$query = new Doctrine_Query();
	        $query->select('h.*')
	              ->from('Historico h')
	              ->innerJoin('h.Modulo m')
	              ->innerJoin('h.Pessoa p')
	              ->where('m.alias LIKE ?', $module)
	              ->andWhere('h.entidade_id = ?', $entidadeId);
	             
	        return $query->execute();
	        
		} catch(Exception $e){
			throw $e;
		}        
	}
		
	public function validate(Historico $historico){
		HistoricoBO::validateDescricao($historico);
	}

	public function validateDescricao(Historico $historico){
		if($historico->getDescricao() == ''){
			throw new Exception('A descrição deve ser preenchida');
		} 

        return true;
	}
}
