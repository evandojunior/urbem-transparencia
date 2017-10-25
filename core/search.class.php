<?php

class Search{
	
	protected $q;
	protected $filter;
	protected $order;
	protected $direction;
	protected $page;
	protected $pager;
	protected $max;
	
	public function __construct(){
		$this->max = 20;
	}
	
	public function getQ(){
	    return $this->q;
	}

	public function setQ($q){
	    $this->q = $q;
	}

	public function getFilter(){
	    return $this->filter;
	}

	public function setFilter($filter){
	    $this->filter = $filter;
	}

	public function getOrder(){
	    return $this->order;
	}

	public function setOrder($order){
		if(isset($order)){
			$this->order = $order;
		} else {
			$this->order = 'id';
		}
	}

	public function getDirection(){
	    return $this->direction;
	}

	public function setDirection($direction){
		if(isset($direction)){
			$this->direction = $direction;
		} else {
			$this->direction = 'DESC';
		}
	}

	public function getPage(){
	    return $this->page;
	}

	public function setPage($page){
		if(isset($page)){
			$this->page = $page;
		} else {
			$this->page = 1;
		}
	}

	public function getPager(){
	    return $this->pager;
	}

	public function setPager($pager){
	    $this->pager = $pager;
	}

	public function getMax(){
	    return $this->max;
	}

	public function setMax($max){
	    $this->max = $max;
	}

	public static function store(){
		$name = 'cancel'.ucwords($_REQUEST['module']);
		$_SESSION[$name] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	} 
	
	public static function restore(){
		$name = 'cancel'.ucwords($_REQUEST['module']);
		if(isset($_SESSION[$name])){
			return $_SESSION[$name];
		} else {
			return url($_REQUEST['module']);
		}
	}

}
	
?>