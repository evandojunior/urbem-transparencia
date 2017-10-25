<?php

class SecaoBO{

	public function create(Secao $secao, $conn=null){
		try{
            $secao->setCreated(date('Y-m-d H:i:s'));
		    SecaoBO::validate($secao);
			$secao->save($conn);
			
			return $secao;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function update(Secao $secao, $conn=null){
		try{
            $secao->setUpdated(date('Y-m-d H:i:s'));
		    SecaoBO::validate($secao);
			$secao->save($conn);

			return $secao;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function delete($secaoId, $conn=null){
        return Doctrine::getTable('Secao')->find($secaoId)->delete();
	}
	
	public function get($secaoId){
        return Doctrine::getTable('Secao')->find($secaoId);
	}
	
	public function getByType($type){
        return Doctrine::getTable('Secao')->findBy('type', $type);
	}
	
	public function getByAlias($alias){
        return Doctrine::getTable('Secao')->findOneBy('alias', $alias);
	}
	
	public function getAll(){
		
		$query = new Doctrine_Query();
        $query->select("s.*")
              ->from("Secao s")
			  ->orderBy("s.alias ASC");
			  
		return $query->execute();
	}	
	
	public function filter(Search $search){
		$fields = array(
			'id'   	    => 's.id',
			'secao'     => 's.secao',
	        'type'      => 's.type',
			'alias'     => 's.alias',
			'parent_id' => 's.parent_id',
			'created'   => 's.created',
			'updated'   => 's.updated',
		);
		
		$query = new Doctrine_Query();
        $query->select('s.*')
              ->from('Secao s');

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
		$secao = $pager->execute();
		
		$search->setPager($pager);
		
		$secaoDTO = new DTO();
		$secaoDTO->setObj($secao);
		$secaoDTO->setSearch($search);

		return $secaoDTO;
	}
	
	public function validate(Secao $secao){}
}
