<?php 

class PessoaBO{

    public function create(Pessoa $pessoa, $conn=null){
        try{
            $pessoa->setCreated(date('Y-m-d H:i:s'));
            PessoaBO::validate($pessoa);
            $pessoa->save($conn);
            
            return $pessoa;

        } catch(Exception $e){
            throw $e;
        }
    }

    public function update(Pessoa $pessoa, $conn=null){
        try{
            $pessoa->setUpdated(date('Y-m-d H:i:s'));
            PessoaBO::validate($pessoa);
            $pessoa->save($conn);

            return $pessoa;

        } catch(Exception $e){
            throw $e;
        }
    }

    public function delete($pessoaId){
        try{
            Doctrine::getTable('Pessoa')->find($pessoaId)->delete();
        
            return true;
            
        } catch(Exception $e){
            throw $e;
        }
    }
    
    public function get($pessoaId){
        $query = new Doctrine_Query();
        $query->select('u.*, p.id, p.nome, p.email')
              ->from('Pessoa p')
              ->leftJoin('p.Usuario u')
              ->where('p.id = ?', $pessoaId);
        
        return $query->fetchOne();        
    }

    public function getByEmail(){
        return Doctrine::getTable('Pessoa')->findOneBy('email', $email);
    }
    
    public function validate(Pessoa $pessoa){}
}
