<?php

class Menu extends BaseMenu {
	
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}
	
	public function getAcaoId(){
		return $this->acao_id;
	}

	public function setAcaoId($acao_id){
		$this->acao_id = $acao_id;
	}	

	public function getParentId(){
		return $this->parent_id;
	}

	public function setParentId($parent_id){
		$this->parent_id = $parent_id;
	}	
	
	public function getURL(){
		return $this->url;
	}

	public function setURL($url){
		$this->url = $url;
	}

	public function getLabel(){
		return $this->label;
	}

	public function setLabel($label){
		$this->label = $label;
	}

	public function getTarget(){
		return $this->target;
	}

	public function setTarget($target){
		$this->target = $target;
	}
	
	public function getPosicao(){
		return $this->posicao;
	}

	public function setPosicao($posicao){
		$this->posicao = $posicao;
	}	

	/*
	 * Relacionamentos
	 */
	
	public function getAcao(){
		return $this->_Acao;
	}

	public function setAcao(_Acao $acao){
		$this->_Acao = $acao;
	}	

	public function getMenu(){
		return $this->Menu;
	}

	public function setMenu(Menu $menu){
		$this->Menu = $menu;
	}	
}
