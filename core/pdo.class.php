<?php

class PDOConnectionFactory{
		
	private static $instance;

	public static function getInstance() {
		if (!isset(self::$instance)){
			$pdo = new PDOConnectionFactory();
			self::$instance = $pdo->getConnect();
		}
		return self::$instance;
	}

	private function getConnect(){
		try {
			$objPDO = new PDO("pgsql:dbname=".DB.";user=".USUARIO.";password=".SENHA.";host=".HOST);
			$objPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo $e->getMessage();
		}	
		return $objPDO;
	}
}

?>