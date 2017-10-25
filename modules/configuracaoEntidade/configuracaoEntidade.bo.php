<?php

class ConfiguracaoEntidadeBO{

	public function create(ConfiguracaoEntidade $configuracaoEntidade, $conn=null){
		try{
			ConfiguracaoEntidadeBO::validate($configuracaoEntidade);
			$configuracaoEntidade->save($conn);
			
			return $configuracaoEntidade;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function deleteAll($conn=null){
		return Doctrine::getTable('ConfiguracaoEntidade')->findAll()->delete();
	}
	
	public function get(){
	    $query = new Doctrine_Query();
        $query->select('c.*')
              ->from('ConfiguracaoEntidade c');
			  
		return $query->fetchOne();
	}
	
	public function getArrayEntidadeId(){
	    $entidades = Doctrine::getTable('ConfiguracaoEntidade')->findAll();
	    
		if(count($entidades) > 0){
			unset($_entidades);
			foreach($entidades as $entidade){
				$entidadeArray[] = $entidade->getEntidadeId();
			}
		}

		return $entidadeArray;
	}	
	

	public function validate(ConfiguracaoEntidade $configuracaoEntidade){}
}
