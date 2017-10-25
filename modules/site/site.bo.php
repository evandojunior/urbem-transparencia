<?php

class SiteBO{
	public static function storeBairros($bairros){
		$_SESSION['bairrosSelecionados'] = $bairros;
	}
	
	public static function restoreBairros(){
		if(isset($_SESSION['bairrosSelecionados'])){
			return $_SESSION['bairrosSelecionados'];	
		} else {
			return array();
		} 
	}
}