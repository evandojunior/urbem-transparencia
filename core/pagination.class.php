<?php
 
class Pagination{

	function __construct($page, $numLimit, $numRecords, $numLinks, $separator){
		
		$this->url        = $_SERVER['QUERY_STRING'];
		$this->page       = (empty($page))? $page = 1 : $page=$page;
		$this->init       = ceil($page*$numLimit);
		$this->numLimit   = $numLimit;
		$this->numRecords = $numRecords;
		$this->numPages   = ceil($numRecords/$numLimit);
		$this->numLinks   = $numLinks;
		$this->numLinks2  = floor($numLinks/2);
		$this->separator  = $separator;
	}

	function previous(){
		if(($this->page) > 1){
			$pageAnterior = ceil($this->page-1);
		} else {
			$pageAnterior = null;
		}
		
		return $pageAnterior;
	}

	function next(){
		if(($this->page) < $this->numPages){
			$pageProximo = ceil($this->page+1);
		} else {
			$pageProximo = null;
		}
		return $pageProximo;		
	}

	function links(){
		//NÚMERO DE LINKS PRONTOS
		$numLinksEsquerda = 0;
		$numLinksDireita = 0;

		//LINKS DIMINUINDO
		for($i = $this->page-$this->numLinks2; $i < $this->page; $i++){
			if($i   >=1     &&      $numLinksEsquerda <= $this->numLinks2){
				$page[] = $i;
				$numLinksEsquerda++;
			}
		}

		//LINK ATUAL
		$page[] = $this->page;

		//LINKS AUMENTANDO
		for($i=$this->page+1; $i<=$this->numPages; $i++){
			if($numLinksDireita <= ($this->numLinks-$numLinksEsquerda)-2){
				$page[] = $i;
				$numLinksDireita++;
			}
		}
		
		//$pageList = $page;
		return $page;
	}
}