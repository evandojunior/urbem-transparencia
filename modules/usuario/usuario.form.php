<?php

class FormUsuario extends Form{

    public function __construct($data=null, $files=null, $args=array()){

        $id = new Hidden();
        $id->setName('id');
        $id->setRequired(false);
        $this->fields['id'] = $id;
        
        $pessoa_id = new Hidden();
        $pessoa_id->setName('pessoa_id');
        $pessoa_id->setRequired(false);
        $this->fields['pessoa_id'] = $pessoa_id;
        
		/********** Consulta **********/
		$query = new Doctrine_Query();
        $query->select("g.*")
              ->from("Grupo g");
		$lista = $query->execute();
		/**********/
		
        $grupo_id = new Select();
        $grupo_id->setName('grupo_id');
        $grupo_id->setLabel('Grupo');
        $grupo_id->setQueryOptions('id', 'grupo', $lista, 'Selecione');
		if(!isset($args['grupo_id_required'])){
			$grupo_id->setRequired(true);
		}
        $this->fields['grupo_id'] = $grupo_id;

		/********** Consulta **********/
		$query = new Doctrine_Query();
        $query->select("m.*, CONCAT(m.nome, '/' ,u.sigla) as nome")
              ->from("Municipio m")
			  ->innerJoin("m.UF u")
			  ->orderBy("u.sigla, m.nome");
		$lista = $query->execute();
		/**********/
		
        $municipio_id = new Select();
        $municipio_id->setName('municipio_id');
        $municipio_id->setLabel('Município');
        $municipio_id->setQueryOptions('id', 'nome', $lista, 'Não informado');
		if(!isset($args['municipio_id_required'])){
			$municipio_id->setRequired(true);
		}
        $this->fields['municipio_id'] = $municipio_id;
		
        $nome = new Text();
        $nome->setName('nome');
        $nome->setLabel('Nome');
		if(isset($args['nome_readonly'])){
			$nome->setReadOnly(true);
		}				
        $this->fields['nome'] = $nome;

        $email = new Text();
        $email->setFormat('email');
        $email->setName('email');
        $email->setLabel('E-mail');
		if(isset($args['email_readonly'])){
			$email->setReadOnly(true);
		}		
        $this->fields['email'] = $email;
        
        $status = new Text();
        $status->setName('status');
        $status->setLabel('Status');
        $status->setStyle(array('background'=>'#F7F7F7'));
        $status->setReadOnly(true);
        $status->setRequired(false);
        $this->fields['status'] = $status;        

        parent::__construct($data, $files, $args);
    }

    public function cleanEmail(){
    	if($this->fields['id']->getValue() != ''){
	    	$usuario = UsuarioBO::get($this->fields['id']->getValue());
	    	
	        if(Validator::validateUnique('Pessoa', 'email', $this->fields['email']->getValue(), $usuario->getPessoa()->getId())){
	            return true;
	        } else {
	            $this->fields['email']->setError('Já existe um usuário cadastrado com este e-mail!');
	            return false;
	        }
    	} else {
    		return true;
    	}
    }
}

class FormSenha extends Form{
        
    public function __construct($data=null, $files=null, $args=null){
        $senha = new Password();
        $senha->setName('senha');
        $senha->setLabel('Senha');
        $senha->setMin(6);
        $this->fields['senha'] = $senha;        

        $_senha = new Password();
        $_senha->setName('_senha');
        $_senha->setLabel('Repita a senha');
        $_senha->setMin(6);
        $this->fields['_senha'] = $_senha;

        parent::__construct($data, $files);
    } 
    
    public function clean_senha(){
        if($this->fields['senha']->getValue() != $this->fields['_senha']->getValue()){
            $this->fields['senha']->setError('As senhas não são idênticas!');
            return false;
        } else {
            return true;
        }
    }
}

class FormUsuarioFilter extends Form{
    public function __construct($data=null, $files=null){
        $q = new Text();
        $q->setName('q', true);
        $q->setLabel('Digite o que deseja pesquisar');
        $q->setStyle(array('width' => '500px'));
        $this->fields['q'] = $q;

        $filter = new Select();
        $filter->setName('filter', true);
        $filter->setLabel('Selecione o tipo de filtro');
        $filter->setStyle(array('width' => '365px'));
        $filter->setOptions(array('nome' => 'Nome'));

        $this->fields['filter'] = $filter;

        parent::__construct($data, $files);
    }
}

class FormLogin extends Form{
        
    public function __construct($data=null, $files=null){
        $email = new Text();
        $email->setName('email');
        $email->setFormat('email');
        $email->setLabel('E-mail');
        $email->setRequired(true);
        $email->setStyle(array('width' => '400px'));
        $this->fields['email'] = $email;
        
        $senha = new Password();
        $senha->setName('senha');
        $senha->setLabel('Senha');
        $senha->setMin(6);
        $senha->setRequired(true);
        $senha->setStyle(array('width' => '400px'));
        $this->fields['senha'] = $senha;        

        parent::__construct($data, $files);
    }       
}
