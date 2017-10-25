<?php

class GrupoBO{

	public function create(Grupo $grupo, $conn=null){
		try{
			$grupo->setCreated(date('Y-m-d H:i:s'));
			GrupoBO::validate($grupo);
			$grupo->save($conn);
			
			return $grupo;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function update(Grupo $grupo, $conn=null){
		try{
		    $grupo->setUpdated(date('Y-m-d H:i:s'));
			GrupoBO::validate($grupo);
			$grupo->save($conn);

			return $grupo;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function delete($grupoId, $conn=null){
        return Doctrine::getTable('Grupo')->find($grupoId)->delete();
	}        
	
	public function get($grupoId){
        return Doctrine::getTable('Grupo')->find($grupoId);
	}
		public function filter(Search $search){
		$fields = array(
			'id'   	  => 'g.id',
			'grupo'   => 'g.grupo',
			'alias'   => 'g.alias',
			'created' => 'g.created',
			'updated' => 'g.updated',
		);
		
		$query = new Doctrine_Query();
        $query->select('g.*')
              ->from('Grupo g');

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
		$grupo = $pager->execute();
		
		$search->setPager($pager);
		
		$grupoDTO = new DTO();
		$grupoDTO->setObj($grupo);
		$grupoDTO->setSearch($search);

		return $grupoDTO;
	}
		
	public function validate(Grupo $grupo){
		GrupoBO::validateAlias($grupo);
	}

	public function validateAlias(Grupo $grupo){
		if($grupo->getAlias() != ''){
			#verifica se já existe um grupo cadastrado com o e-mail preenchido
			if(Validator::validateUnique('Grupo', 'alias', $grupo->getAlias(), $grupo->getId())){
				return true;
			} else {
				throw new Exception('Já existe um grupo cadastrado com este alias');
			}
			#se o e-mail não estiver preenchido
		} else {
			throw new Exception('O Alias deve ser preenchido');
		}
	}
}

class GrupoAcaoBO{

	public function create(GrupoAcao $grupoAcao, $conn=null){
		try{
			$grupoAcao->save($conn);
			
			return $grupoAcao;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function update(GrupoAcao $grupoAcao, $conn=null){
		try{
			$grupoAcao->save($conn);

			return $grupoAcao;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function delete($grupoAcaoId, $conn=null){
        return Doctrine::getTable('GrupoAcao')->find($grupoAcaoId)->delete($conn);
	}
	
	public function deleteByGrupo($grupoId, $conn=null){
        return Doctrine::getTable('GrupoAcao')->findBy('grupo_id', $grupoId)->delete($conn);
	}	
	
	public function get($grupoAcaoId){
        return Doctrine::getTable('GrupoAcao')->find($grupoAcaoId);
	}
	
	public function getByGrupo($grupoId, $conn=null){
        return Doctrine::getTable('GrupoAcao')->findBy('grupo_id', $grupoId);
	}	
	
	public function getArrayGrupoAcaoId($grupoId){
	    $grupoAcao = Doctrine::getTable('GrupoAcao')->findBy('grupo_id', $grupoId);
	    
		if(count($grupoAcao) > 0){
			unset($_grupoAcao);
			foreach($grupoAcao as $_grupoAcao){
				$grupoAcaoArray[] = $_grupoAcao->getAcaoId();
			}
		}

		return $grupoAcaoArray;
	}	
}