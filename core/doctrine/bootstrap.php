<?php

require 'lib/Doctrine.php';

spl_autoload_register(array('Doctrine', 'autoload'));
spl_autoload_register(array('Doctrine_Core', 'modelsAutoload'));
 
try { 
  $manager = Doctrine_Manager::connection('pgsql://'.USUARIO.':'.SENHA.'@'.HOST.':'.PORT.'/'.DB);
  
  $manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_CONSERVATIVE); 
  $manager->setAttribute(Doctrine_Core::ATTR_EXPORT, Doctrine_Core::EXPORT_ALL);
 
  $profiler = new Doctrine_Connection_Profiler();
  $manager->setListener($profiler);
  $manager->setCharset('latin1');
  //$manager->setCollate('latin1_swedish_ci');
 
} catch (Doctrine_Manager_Exception $e) {
  print $e->getMessage();
}