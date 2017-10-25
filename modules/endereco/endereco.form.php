<?php

class FormEndereco extends Form{

	public function __construct($data=null, $files=null){

		$id = new Hidden();
		$id->setName('id');
		$id->setRequired(false);
		$this->fields['id'] = $id;

        $uf = new Select();
        $uf->setName('uf');
        $uf->setLabel('UF');
		$consulta = 'SELECT * FROM uf WHERE disponivel = 1';
        $uf->setQueryOptions('id', 'nome', $consulta, 'Selecione');        
        $uf->setRequired(false);
        $this->fields['uf'] = $uf;

        $municipio = new Select();
        $municipio->setName('municipio');
        $municipio->setLabel('Municipio');
        $municipio->setRequired(true);
        $this->fields['municipio'] = $municipio;
        
        $bairro = new Text();
        $bairro->setName('bairro');
        $bairro->setLabel('Bairro');
        $bairro->setRequired(true);
        $this->fields['bairro'] = $bairro;

		$logradouro = new Text();
		$logradouro->setName('logradouro');
		$logradouro->setLabel('Logradouro');
		$logradouro->setStyle(array('width' => '405px'));
		$logradouro->setRequired(true);
		$this->fields['logradouro'] = $logradouro;
        
		$numero = new Text();
		$numero->setName('numero');
		$numero->setLabel('Número');
		$numero->setStyle(array('width' => '50px'));
		$numero->setRequired(true);
		$this->fields['numero'] = $numero;

		$complemento = new Text();
		$complemento->setName('complemento');
		$complemento->setLabel('Complemento');
		$this->fields['complemento'] = $complemento;
		
        $tipo = new Select();
        $tipo->setName('tipo');
        $tipo->setLabel('Tipo Endereço');
        $tipo->setOptions(array('Residencial' => 'Residencial', 'Comercial' => 'Comercial', 'Outros' => 'Outros'));
        $tipo->setRequired(true);
        $this->fields['tipo'] = $tipo;		
		
		$cep = new Text();
		$cep->setName('cep');
		$cep->setLabel('CEP');
		$cep->setMask('99.999-999');
		$this->fields['cep'] = $cep;	
		
		parent::__construct($data, $files);
		
		$this->preencheMunicipio($this->fields['uf']->getValue());
	}
	
	public function preencheMunicipio($ufId){
        $options[''] = 'Selecione';

		if($ufId != ''){
			$municipios = Doctrine::getTable('Municipio')->findBy('uf_id', $ufId);
			foreach($municipios as $municipio){
				$options[$municipio->getId()] = $municipio->getNome();
			}
		}

    	$this->getFields('municipio')->setOptions($options);
	}
}
