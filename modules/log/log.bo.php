<?php

class LogBO{

	public function create(Log $log){
		try{
			$log->setCreated(date('Y-m-d H:i:s'));
			LogBO::validate($log);
			$log->save($conn);
		    
		    return $log;
		    
		} catch(Exception $e){
			throw $e;
		}
	}

	public function get($logId){
        return Doctrine::getTable('Log')->find($logId);
	}
	
	public function filter(Search $search){
		$fields = array(
			'id'   	  => 'l.id',
		);
		
		$query = new Doctrine_Query();
        $query->select('l.*, m.id, m.nome')
              ->from('Log l')
              ->innerJoin('l.Municipio m');

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
		$log = $pager->execute();
		
		$search->setPager($pager);
		
		$logDTO = new DTO();
		$logDTO->setObj($log);
		$logDTO->setSearch($search);

		return $logDTO;
	}
		
	public function validate(Log $log){}
}