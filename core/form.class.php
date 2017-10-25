<?php

class Form{

        protected $data;
        protected $files;
        protected $valid;
        protected $fields;
        protected $name;
        protected $errors;
       
        public function getData(){
            return $this->data;
        }

        public function setData($data){
            $this->data = $data;
        }

        public function getFiles(){
            return $this->files;
        }

        public function setFiles($files){
            $this->files = $files;
        }
       
        public function getValid(){
            return $this->valid;
        }

        public function setValid($valid){
            $this->valid = $valid;
        }

        public function getFields($field) {
            if(!isset($this->fields[$field])) {
                return null;
            } elseif ($field != '') {
                return $this->fields[$field];
            }

            return $this->fields;
        }

        public function setFields($fields){
            $this->fields = $fields;
        }

        public function getName(){
            return $this->name;
        }

        public function setName($name){
            $this->name = $name;
        }      

        public function getErrors(){
            return $this->errors;
        }

        public function setErrors($errors){
            $this->errors[] = $errors;
        }              
       
/*
 * Métodos de manipulação do form
 */    

        /*
         * Pega dados do "request" e atribui aos respectivos atributos
         */
        public function __construct($data=null, $files=null, $args=array()){
                $this->valid = true;
                $this->data  = $data;
                $this->files = $files;
                $this->name  = strtolower(get_class($this));
               
                /*
                 * Percorre atributos
                 */
                foreach($this->fields as $field__name => $field__obj){
					/**
					 * Bloqueia campos que estão desabilitados
					 */
                	if(key_exists('disabled', $args)){
                		if(in_array($field__name, $args['disabled'])){
                			break;
                		}
                	}
		            
                	$field__obj->setName($field__obj->getName());
		                       
		            if(($this->data != null)||($this->files != null)){
	                    /**
	                     * Verica e insere apenas os atributos da classe filha como campos do form
	                     */
	                    if(isset($this->data[$field__obj->getName()])){
	                     	if(get_class($field__obj) == 'Checkbox'){
			            	  	$field__obj->setChecked(true);
							} else {
			                   	$field__obj->setValue($this->data[$field__obj->getName()]);
							}
			           	}
			                               
						if(isset($this->files[$field__obj->getName()])){
	                       	$field__obj->setValue($this->files[$field__obj->getName()]);
						}
		                               
                        try{
                        	$field__obj->validate();
						} catch(Exception $e){
        	              	if($e->getMessage() != ''){
		                      	$this->errors[] = $e->getMessage();
							}
							$this->valid = false;
						}
                   		$this->fields[$field__name] = $field__obj;
                       		
	        		} else {
						$this->fields[$field__obj->getName()] = $field__obj;
                   	}
                }
               
                /*
                 * Laço se repete para que ao executar o "executeClean", todos os campos do form jÃ¡ estejam preenchidos
                 * e os seus "values" possam ser acessados dentro do clean
                 */
               
                foreach($this->fields as $field__name => $field__obj){
                        $this->executeClean($field__name);
                }
               
                /*
                 * Seta variáveis utilizadas no método CANCEL, salva a última URL da página de pesquisa
                 * A URL contém a consulta enviada via GET;
                 */
                if($_REQUEST['action'] == 'index'){
                        Search::store();
                }              
        }
       
        public function isValid(){
                if($this->valid){
                        return true;
                } else {
                        return false;
                }
        }
       
        public function clean(){
                foreach($this->fields as $name => $obj){
                        $obj->setValue('');
                        $this->fields[$name] = $obj;
                }
        }
               
        public function executeClean($field__name){
        /*
         * Executa clean apenas quando existir algum valor no data (valor passado por parÃ¢metro ao form)
         */
	        if($this->data == ''){
	            return;
	        }

                $field__obj = $this->fields[$field__name];
                /*
                 * Monta nome do método clean a ser chamado
                 */
                $method__name = 'clean'.ucfirst($field__name);
                /*
                 * Verifica se método existe
                 */
                if(is_callable(array($this, $method__name))){
                        /*
                         * Executa mÃ©todo clean do objeto
                         */
                        if(!call_user_func(array($this, $method__name), $field__obj)){
                                $this->valid = false;
                        }
                }      
        }
       
        public function cancel(){
                return Search::restore();
        }

}


