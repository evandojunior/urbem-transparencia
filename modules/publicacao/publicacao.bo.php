<?php

class PublicacaoBO{

	public function create(Publicacao $publicacao){
		try{
			$publicacao->setCreated(date('Y-m-d H:i:s'));
			PublicacaoBO::validate($publicacao);
			$publicacao->save($conn);

		    return $publicacao;
		    
		} catch(Exception $e){
			throw $e;
		}
	}

	public function update(Publicacao $publicacao, $conn=null){
		try{
			$publicacao->setUpdated(date('Y-m-d H:i:s'));
			PublicacaoBO::validate($publicacao);
			$publicacao->save($conn);

			return $publicacao;

		} catch(Exception $e){
			throw $e;
		}
	}
	
	public function delete($publicacaoId, $conn=null){
		$publicacao = Doctrine::getTable('Publicacao')->find($publicacaoId);
		# Deleta a publicação
		unlink($GLOBALS['BASE_DIR'].'media/img/publicacao/'.$publicacao->getArquivo());
		
		$publicacao->delete();
		
        return true;
	}	
	
	public function get($publicacaoId){
		
		$query = new Doctrine_Query();
        $query->select('p.*, s.*')
              ->from('Publicacao p')
			  ->innerJoin('p.Secao s')
			  ->where('p.id = ?', $publicacaoId);
			  
		return $query->fetchOne();
	}
	
	public function getUltimos($limit){
		
		$query = new Doctrine_Query();
        $query->select('p.*, s.*')
              ->from('Publicacao p')
			  ->innerJoin('p.Secao s')
			  ->orderBy('p.created')
			  ->limit($limit);
			  
		return $query->execute();
	}	
	
	public function getBySecaoAlias(){
		$query = new Doctrine_Query();
        $query->select("p.*, s.*")
              ->from("Publicacao p")
			  ->innerJoin("p.Secao s")
			 // ->where("s.alias = '".$alias."'")
			  ->andWhere("p.status = 'p'")
			  ->orderBy("p.created DESC");
			  
		return $query;
	}

	public function getAll(){
		$query = new Doctrine_Query();
        $query->select("p.*, s.*")
              ->from("Publicacao p")
			  ->innerJoin("p.Secao s")
			  ->where("p.status = 'p'")
			  ->orderBy("s.alias ASC, p.created DESC");
			  
		return $query;
	}
	
	public function getPublicacaoGeral(){
		$query = new Doctrine_Query();
        $query->select('p.*, s.*')
              ->from('Publicacao p')
			  ->innerJoin('p.Secao s')
			  ->where("p.status = 'p'")
			  ->orderBy("s.alias ASC, p.created DESC");
			  
		return $query;
	}

	public function filter(Search $search, $method, $request){
		$query = PublicacaoBO::$method();
		
        if(isset($request['exercicio'])){
            $query->andWhere("EXTRACT(YEAR FROM p.created) = '".$request['exercicio']."'");
        } else {
			$query->andWhere("EXTRACT(YEAR FROM p.created) = '".date('Y')."'");
        }
        
       if(isset($request['categoria'])){
			if($request['categoria'] != ''){
				$query->andWhere("s.alias = '".$request['categoria']."'");
			}
        }		
		
        if(isset($request['secao'])){
			if($request['secao'] != ''){
				$query->andWhere("p.secao_id = ?", $request['secao']);
			}
        }
		
		/* CONSULTA DINÂMICA */
		$fields = array(
			'id'   	    => 'p.id',
			'descricao' => 'p.descricao',
			'created'   => 'p.created',
			'updated'   => 'p.updated',
		);

        if($search->getFilter() != null){
			$query->andWhere($fields[$search->getFilter()]." LIKE '%".$search->getQ()."%'");
		}
		
		if($search->getOrder() != null){
			$order = $fields[$search->getOrder()];
				
			if($search->getDirection() != null){
                $order.= ' '.$search->getDirection();
			}
        
            $query->orderBy($order);
		}		
		
		$pager = new Doctrine_Pager($query, $search->getPage(), $search->getMax());
		$publicacao = $pager->execute();

		$search->setPager($pager);

		$publicacaoDTO = new DTO();
		$publicacaoDTO->setObj($publicacao);
		$publicacaoDTO->setSearch($search);

		return $publicacaoDTO;
	}

	public function validate(Publicacao $publicacao){}
}