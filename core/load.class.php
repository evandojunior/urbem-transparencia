<?php

class Load{
	
	public static function module($module, $action, $args=null){
		if($args != null){
			foreach($args as $key => $value){
					$$key = $args[$key];
			}
		}

		try{
			if(file_exists('modules/'.$module.'/'.$module.'.controller.php')){
				require_once 'modules/'.$module.'/'.$module.'.controller.php';
				#verifica se existe a action solicitada
				if(!is_callable(ucfirst($module."Controller")."::".$action)){
					throw new Exception('AÃ§Ã£o nÃ£o definida no controller: '.ucfirst($module).'Controller');
				}
				
				#executa a action solicitada
				call_user_func(ucfirst($module."Controller")."::".$action, $args);
	
			} else {
				throw new Exception('AÃ§Ã£o '.$action.' nÃ£o encontrada');
			}
		} catch(Exception $e){
			echo '<pre>';
			echo 'Exception: '.$e->getMessage().'\n';
			echo '</pre>';
		}	
	}
	
	public static function view($view, $args=null){
		if($args != null){
			foreach($args as $key => $value){
				if(in_array($key, $GLOBALS['RESERVED'])){
				    $GLOBALS[$key] = $args[$key];
	            }				
				$$key = $args[$key];
			}	
		}
		
		require 'modules/'.$view;
	}
	
	public static function snippet($snippet, $args=null){
		if($args != null){
			foreach($args as $key => $value){
					$$key = $args[$key];
			}
		}
				
		require 'templates/default/snippets/'.$snippet.'.snippet.php';
	}
	
	public static function main(){
		$module = $_REQUEST['module'];
		$action = $_REQUEST['action'];
		
		foreach($GLOBALS['MODULES'] as $module_){
			$controllers[$module_] = 'modules/'.$module_.'/'.$module_.'.controller.php';
		}
		
		try{
			#verifica se o controller existe no array
			if(!isset($controllers[$_REQUEST['module']])){
				throw new Exception('Controller nÃ£o definido na action');
			}	
			
			#verifica se existe arquivo do controller
			if(!file_exists($controllers[$module])){
				throw new Exception('Arquivo '.$module.'Controller nÃ£o definido na action');
			}	
			
			#carrega arquivo controller
			require_once $controllers[$module];

			#retira caracteres especiais para que possa encontrar o nome correto do controller antes Venda_imovelController
			$module = str_replace(array('_', '-'), '', $module);
			
			#verifica se existe a action solicitada
			if(!is_callable(ucfirst($module."Controller")."::".$action)){
				throw new Exception('Action nÃ£o definido no controller: '.ucfirst($module).'Controller');
			}

			#executa a action solicitada
			call_user_func(ucfirst($module."Controller")."::".$action);
			
		} catch(Exception $e){
			if($_REQUEST['module'] == 'mpdf') {
//				echo url();
				die;
			}
			
			echo '<pre>';
			var_dump($e);
			echo '</pre>';
		}
	}
	
	public function json($array){
		echo json_encode($array);
		
		die;
	}
	
	public static function _require($path){
		require_once $GLOBALS['BASE_DIR'].$path;
	}
}
