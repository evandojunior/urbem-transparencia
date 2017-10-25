<?php

Load::_require('modules/menu/menu.base.php');
Load::_require('modules/menu/menu.class.php');
Load::_require('modules/menu/menu.bo.php');


class MenuController{

	public static function show(){
		$menus = MenuBO::getAll();
		
		$context = array(
			'menus' => $menus,
		);

		Load::view('menu/views/topo.php', $context);
	}
	
	public static function rodape(){
		$context = array();
	
		Load::view('menu/views/rodape.php', $context);
	}	
}

