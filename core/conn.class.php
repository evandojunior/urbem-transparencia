<?php

class Conn{
    
    public static function openConnection($db){
        # Conecta com o banco de dados
        $manager = Doctrine_Manager::connection('pgsql://'.USUARIO.':'.SENHA.'@'.HOST.':'.PORT.'/'.$db);
        $manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_CONSERVATIVE); 
        $manager->setAttribute(Doctrine_Core::ATTR_EXPORT, Doctrine_Core::EXPORT_ALL);
        
        $profiler = new Doctrine_Connection_Profiler();
        $manager->setListener($profiler);
        $manager->setCharset('utf-8');
        
        return $manager;
    }
    
    public static function closeConnection($conn){
        #Fecha conexÃ£o com o banco dados
        $manager = Doctrine_Manager::closeConnection($conn);
        
        return true;
    }
}

?>