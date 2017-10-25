<?php

class MenuBO{

	public function getAll(){
	    $query = new Doctrine_RawSql();
	    $query->select('{menu.*}')
	          ->from('menu')
	          ->addFrom('INNER JOIN grupo_acao ON menu.acao_id = grupo_acao.acao_id')
	          ->addFrom('INNER JOIN grupo 	  ON grupo.id	  = grupo_acao.grupo_id')
              ->addFrom('INNER JOIN acao 	  ON acao.id	  = grupo_acao.acao_id')
	          ->addComponent('menu', 'Menu menu')
	          ->where('grupo_acao.grupo_id = ?', Sessao::get('usuario_grupo_id'))
	          ->orderby('menu.posicao ASC');
		
		return $query->execute();
	}
}
