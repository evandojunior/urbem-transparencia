<?php

class ModuloBO{

	public function create(Modulo $modulo, $conn=null){
		try{
		    ModuloBO::validate($modulo);
			$modulo->save($conn);
			
			return $modulo;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function update(Modulo $modulo, $conn=null){
		try{
		    ModuloBO::validate($modulo);
			$modulo->save($conn);
			
			return $modulo;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function delete($moduloId, $conn=null){
        return Doctrine::getTable('Modulo')->find($moduloId)->delete();
	}
	
	public function get($moduloId){
        return Doctrine::getTable('Modulo')->find($moduloId);
	}
	
	public function getByAlias($alias){
        return Doctrine::getTable('Modulo')->findOneBy('alias', $alias);
	}	
	
    public function validate(Modulo $modulo){}
}
