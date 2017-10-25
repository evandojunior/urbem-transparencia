<?php

class EnderecoBO{

	public function create(Endereco $endereco, $conn=null){
		try{
		    EnderecoBO::validate($endereco);
			$endereco->save($conn);
			
			return $endereco;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function update(Endereco $endereco, $conn=null){
		try{
		    EnderecoBO::validate($endereco);
			$endereco->save($conn);

			return $endereco;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function delete($enderecoId, $conn=null){
        return Doctrine::getTable('Endereco')->find($enderecoId)->delete($conn);
	}
	
	public function get($enderecoId){
        return Doctrine::getTable('Endereco')->find($enderecoId);
	}

	public function getEnderecoToString(Endereco $endereco){
		$varEndereco = $endereco->getCEP()->getLogradouro().', '.$endereco->getNumero().' - ';
		$varEndereco.= $endereco->getCEP()->getBairro().' - ';
		$varEndereco.= $endereco->getCEP()->getNumeroCEP().' - ';
		$varEndereco.= $endereco->getCEP()->getMunicipio()->getNome().'/';
		$varEndereco.= $endereco->getCEP()->getMunicipio()->getUF()->getSigla();	

		return $varEndereco;
	}
	
	public function validate(Endereco $endereco){}
}

class CEPBO{

	public function create(CEP $cep, $conn=null){
		try{
			$cep->save($conn);
			
			return $cep;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function update(CEP $cep, $conn=null){
		try{
			$cep->save($conn);

			return $cep;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function delete($cepId, $conn=null){
        return Doctrine::getTable('CEP')->find($cepId)->delete($conn);
	}
	
	public function get($cepId){
        return Doctrine::getTable('CEP')->find($cepId);
	}
}

class MunicipioBO{
	
	public function findMunicipioByUF($uf){
		$query = new Doctrine_Query();
		$query->select('m.id, m.nome')
			  ->from('Municipio m')
			  ->where('m.uf_id = ?', $uf)
			  ->orderby('m.nome');
			    
		return $query->execute();
	}
	
	public function getByHash($hash){
		$query = new Doctrine_Query();
		$query ->select("m.*")
			   ->from("Municipio m")
			   ->where("m.hash = '".$hash."'");
			    
		return $query->fetchOne();
	}
	
	public function getByAlias($alias){
		$query = new Doctrine_Query();
		$query ->select("m.*")
			   ->from("Municipio m")
			   ->where("m.alias = '".$alias."'");
			    
		return $query->fetchOne();
	}	
	
	public function getByUF($ufId){
		$query = new Doctrine_Query();
		$query->select("m.*")
		      ->from("Municipio m")
			  ->where("m.uf_id = ?", $ufId)
			  ->andwhere("m.disponivel = 1");
		
		return $query->execute();
	}	
}

class UFBO{
	
	public function getAll(){
		$query = new Doctrine_Query();
		$query->select('u.id, u.sigla, count(m.uf_id)')
			  ->from('UF u')
			  ->leftjoin('u.Municipio m')
			  ->groupby('u.id, u.sigla, m.uf_id')
			  ->orderby('u.sigla');
			  
		return $query->execute();
	}
	
	public function getBySigla($sigla){
		$query = new Doctrine_Query();
		$query->select("u.*")
			  ->from("UF u")
			  ->where("sigla ILIKE '".$sigla."'");
			  
		return $query->fetchOne();
	}
}