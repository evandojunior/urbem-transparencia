<?php

include_once $GLOBALS['BASE_DIR'] . 'core/form.class.php';

class FormContato extends Form{
	
	public function __construct($data=null, $files=null, $args=null){
		$id = new Hidden();
		$id->setName('id');
		$id->setRequired(false);
		$this->fields['id'] = $id;
		
		/********** Conexão **********/
        $query = new Doctrine_Query();
        $query->select("e.*")
            ->from("Entidade e")
            ->innerJoin("e.ConfiguracaoEntidade e2");
        $lista = $query->execute();
		/**********/
		
        $configuracao_id = new Select();
        $configuracao_id->setName('configuracao_id');
        $configuracao_id->setLabel('Destinatário');
        $configuracao_id->setQueryOptions('cod_entidade', 'nome_entidade', $lista, 'Selecione');
        $configuracao_id->setRequired(true);
        $this->fields['configuracao_id'] = $configuracao_id;

		$assunto = new Text();
		$assunto->setName('assunto');
		$assunto->setLabel('Assunto');
		$assunto->setRequired(true);
		$this->fields['assunto'] = $assunto;		
		
		$nome = new Text();
		$nome->setName('nome');
		$nome->setLabel('Nome');
		$nome->setRequired(true);
		$this->fields['nome'] = $nome;
		
		$ddd = new Text();
		$ddd->setName('ddd');
		$ddd->setLabel('DDD');
		$ddd->setStyle(array('width' => '62px', 'font-size' => '12px'));
		$ddd->setMask('99');
		$this->fields['ddd'] = $ddd;

		$telefone = new Text();
		$telefone->setName('telefone');
		$telefone->setLabel('Telefone');
		$telefone->setMask('9999-9999');
		$telefone->setStyle(array('width' => '310px', 'font-size' => '12px'));
		$this->fields['telefone'] = $telefone;			

		$email = new Text();
		$email->setName('email');
		$email->setFormat('email');
		$email->setLabel('E-mail');
		$this->fields['email'] = $email;
		
		$mensagem = new Textarea();
		$mensagem->setName('mensagem');
		$mensagem->setLabel('Mensagem');
		$mensagem->setRequired(true);
		$this->fields['mensagem'] = $mensagem;	
		
		$captcha = new Captcha();
		$captcha->setName('captcha');
		$captcha->setLabel('Digite no campo abaixo o código que visualiza na imagem');
		$captcha->setRequired(true);
		$this->fields['captcha'] = $captcha;	
				
		parent::__construct($data, $files);
	}
	
    public function cleanTelefone(){
    	if(($this->fields['telefone']->getValue() == '') && ($this->fields['email']->getValue() == '')){
			$this->fields['ddd']->setError('Campo obrigatório.');
			$this->fields['telefone']->setError('Você deve obrigatoriamente preencher o telefone ou o e-mail.');
			$this->fields['email']->setError('Você deve obrigatoriamente preencher o telefone ou o e-mail.');
			
			return false;
		
    	} else {
    		
			return true;
    	}
    }
	
	public function cleanEmail(){
    	if(($this->fields['telefone']->getValue() == '') && ($this->fields['email']->getValue() == '')){
			$this->fields['ddd']->setError('Campo obrigatório.');
			$this->fields['telefone']->setError('Você deve obrigatoriamente preencher o telefone ou o e-mail.');
			$this->fields['email']->setError('Você deve obrigatoriamente preencher o telefone ou o e-mail.');
			
			return false;
		
    	} else {
			
    		return true;
    	}
    }
}

class FormContatoFilter extends Form{
	
	public function __construct($data=null, $files=null){
		$q = new Text();
		$q->setName('q', true);
		$q->setLabel('Digite o que deseja pesquisar');
		$q->setStyle(array('width' => '500px'));
		$this->fields['q'] = $q;

		$filter = new Select();
		$filter->setName('filter', true);
		$filter->setLabel('Selecione o tipo de filtro');
		$filter->setOptions(array('assunto' => 'Assunto', 'nome' => 'Nome', 'email' => 'E-mail'));
		$this->fields['filter'] = $filter;

		parent::__construct($data, $files);
	}
}