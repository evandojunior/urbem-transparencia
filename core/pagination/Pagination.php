<?php

class Pagination
{
   
	/*
	 * Commentary in Brazilian Portuguese
	 * -----------------------------------------
	 * valor padrÃ£o para a pÃ¡gina atual
	 *
	 * protected - somente poderÃ¡ ser acessado dentro da prÃ³pria classe em que foram declarados e a 
	 * partir de classes descendentes, mas nÃ£o poderÃ£o ser acessados a partir do programa que faz uso dessa classe 
	 *
	 * static -  atributos dinÃ¢micos como as propriedades de um objeto, mas estÃ£o relacionados Ã  classe, sÃ£o compartilhadas
	 * entre todos os objetos de uma mesma classe
	 *
	 * @ var int
	 */
     protected $_page = 1;
	 
	 /*
	  * Commentary in Brazilian Portuguese
	  * -----------------------------------------
	  * valor padrÃ£o para o total de registros por pÃ¡gina
	  *
	  * @ var int
	  */
	 protected $_recordsPage = 10;
	 
	 /*
	  * Commentary in Brazilian Portuguese
	  * -----------------------------------------
	  * valor padrÃ£o para o retorno do inÃ­cio para clÃ¡usulas de banco de dados
	  * essa classe trabalha com arrays e inteiros, o valor retornado em questÃ£o serve apenas para organizaÃ§Ã£o
	  * e prevenÃ§Ã£o de erros, ou seja dentro de um mÃ©todo Ã© feita a conta do inÃ­cio para clÃ¡usulas sql, evitando
	  * que os resultados da sua paginaÃ§Ã£o nÃ£o sejam iguais aos resultados retornados pelo banco.
	  * A paginaÃ§Ã£o e o banco trabalharÃ£o com o mesmo valor inicial evitando erros.
	  * VocÃª consegue montar a paginaÃ§Ã£o sem ele, esse valor Ã© apenas de retorno
	  *
	  * @ var int
	  */
	 protected $_start = 0;
	 
	 /*
	  * Commentary in Brazilian Portuguese
	  * -----------------------------------------
	  * total de registros retornados pelo banco ou array de resultados
	  *
	  * @ var int
	  */
	 protected $_totalRecords = null;
	 
	 /*
	  * Commentary in Brazilian Portuguese
	  * -----------------------------------------
	  * valor padrÃ£o para os compomentes prÃ³ximos e anteriores
	  * valor opcional vocÃª pode fazer a paginaÃ§Ã£o sem ele
	  *
	  * @ var int
	  */
	 protected $_nextPreviousValue = 15;
	 
	 /*
	  * Commentary in Brazilian Portuguese
	  * -----------------------------------------
	  * retorna um array com os tipos de paginadores (opcional) diz o tipo de paginador que serÃ¡ criado
	  * vocÃª pode fazer uso ou nÃ£o do paginador
	  *
	  * @ var array
	  */	  
	 protected $_pager = array("yahoo","google","jumping","simple");
	 
	 /*
	  * Commentary in Brazilian Portuguese
	  * -----------------------------------------
	  * retorna os Ã­ndices com as pÃ¡ginas geradas pelo total de registros, ou tamanho do array
	  * Ã© um valor de retorno (array) e tambÃ©m Ã© opcional vocÃª pode trabalhar sem ele
	  *
	  * @ var array
	  */	  
	 protected $_indexes = array();
	 
	 /*
	  * Commentary in Brazilian Portuguese
	  * -----------------------------------------
	  * define o total de Ã­ndices das pÃ¡ginas que serÃ£o mostrados, valor padrÃ£o de 1 Ã  10.
	  *
	  * @ var int
	  */
	 protected $_perPage = 10;
	 
	 /*
	  * Commentary in Brazilian Portuguese
	  * -----------------------------------------
	  * define as configuraÃ§Ãµes extras para paginaÃ§Ãµes do tipo Delicious ou Google que usam respactivamente 
	  * esse parÃ¢metro como Ã­ndices extras, e total de Ã­ndices inicial
	  *
	  * @ var int
	  */
	 protected $_extraSettings = 4;
	 
	 /*
	  * Commentary in Brazilian Portuguese
	  * -----------------------------------------
	  * retorna o total de pÃ¡ginas
	  *
	  * @ var int
	  */
	 protected $_totalPages = null;
	 
	 /*
	  * Commentary in Brazilian Portuguese
	  * -----------------------------------------
	  * retorna a prÃ³xima pÃ¡gina
	  *
	  * @ var int
	  */
	 protected $_nextPage = 1;
	 
	 /*
	  * Commentary in Brazilian Portuguese
	  * -----------------------------------------
	  * retorna a pÃ¡gina anterior
	  *
	  * @ var int
	  */
	 protected $_previousPage = 1;
	 
	 /*
	  * Commentary in Brazilian Portuguese
	  * -----------------------------------------
	  * retorna um array com todos os Ã­ndices das pÃ¡ginas existentes
	  *
	  * @ var array
	  */
	 protected $_arrayPages = array();
	 
	 
	 /*
	  * Commentary in Brazilian Portuguese
	  * Construtor da classe, nele vocÃª informa a pÃ¡gina atual e o total de registros por pÃ¡gina
	  * Ele retorna para vocÃª as informaÃ§Ãµes de inÃ­cio em clÃ¡usulas sql
	  *
	  * Commentary in English
	  * Class constructor, it tells you the current page and total number of records per page
	  * It returns you to the information in clauses beginning sql
	  *
	  */	 
	 public function __construct($page,$recordsPage)
	 {
		 
		  /* 
		   * Commentary in Brazilian Portuguese
		   * se a pÃ¡gina atual nÃ£o for um valor numÃ©rico ou for igual a zero
		   * entÃ£o a pÃ¡gina atual recebe o valor definido em $_page
		   * 
		   * Commentary in English
		   * if the current page is not a numeric value or  is zero
		   * then the current page receives the value determined in $_page
		   *
		   */		  
		   if(!is_numeric($page) || $page <= 0)
		   $this->_page = $this->_page;
		   else
		   $this->_page = $page;
		 
		  /*
		   * Commentary in Brazilian Portuguese
		   * se o valor de resultados por pÃ¡gina nÃ£o for numÃ©rico
		   * entÃ£o o valor de resultados por pÃ¡gina recebe o valor definido em $_recordsPage
		   *
		   * Commentary in English
		   * if the value of results per page is not a numeric
		   * then the value of results per page receives the value determined in $_recordsPage
		   *
		   */
		   if(!is_numeric($recordsPage))
		   $this->_recordsPage = $this->_recordsPage;
		   else
		   $this->_recordsPage = $recordsPage;
		 
          /*
		   * Commentary in Brazilian Portuguese
		   * criando o valor de inÃ­cio para clÃ¡usulas sql
		   * esse valor Ã© apenas um valor de retorno, vocÃª nÃ£o Ã© obrigado a trabalhar com ele
		   * a funÃ§Ã£o desse valor Ã© garantir que a sua clÃ¡usula limit tenha um valor de inÃ­cio igual
		   * ao valor calculado pela paginaÃ§Ã£o
		   *
		   * Commentary in English
		   * creating the start value for sql clauses
		   * this value is only a return value, you are not obligated to work with this
		   * the function of this value is to ensure that its limit clause has a start value equal
		   * the value calculated by the paging
		   *
		   */
		   $this->_start =  ($this->_page - 1) * $this->_recordsPage;
	 }
	 
	 
	/*
	 * Commentary in Brazilian Portuguese
	 * Usando o mÃ©todo mÃ¡gico __get para retornar os valores das propriedades e mÃ©todos dessa classe
	 *
	 * Commentary in English
	 * Using __get magic method to return the values of the properties and methods of this class
	 *
	 */
	 public function __get($property)
	 {
	     return $this->$property;
	 }
	 
	 /*
          * Commentary in Brazilian Portuguese
          * Retornando a prÃ³xima pÃ¡gina, pÃ¡gina anterior, total de pÃ¡ginas, primeira pÃ¡gina, e Ã­ndices de pÃ¡ginas
          *
          * Commentary in English
          * Returning the next page, previous page, total of pages, fisrt page, and index of pages
          */
	 public function CreatePages($totalRecords, $pager = null, $marcadores = 10, $extraSettings = null)
	 {
         
		 /*
		  * Commentary in Brazilian Portuguese
		  * Se o total de registros ou dimensÃ£o do array nÃ£o for um nÃºmero ou for menor ou igual a zero
		  * o mÃ©todo retorna falso
		  *
		  * Commentary in English
		  * If the total records or size of the array is not a number or is less than or equal to zero
		  * the method return false
		  *
		  */
		 if(!is_numeric($totalRecords) || $totalRecords <= 0)
		 {
			 return false;
		 }
		 
		 else
		 {
			/* 
			 * Commentary in Brazilian Portuguese
			 * Total de pÃ¡ginas Ã© igual ao total de registros dividido pelo total de registros por pÃ¡gina com o valor arredondado para cima
			 *
			 * Commentary in English 
			 * Total number of pages is equal to the total of records divided by the total number of records per page
			 *
			 */
		        $this->_totalPages = ceil($totalRecords/$this->_recordsPage);
			
			/*
			 * Commentary in Brazilian Portuguese
			 * definindo a primeira pÃ¡gina sempre no valor 1
			 * 
			 * Commentary in English 
			 * setting the first page always worth 1
			 */
			$this->_firstPage = 1;
			
			/*
			 * Commentary in Brazilian Portuguese
			 * Calculando a prÃ³xima pÃ¡gina
			 *
			 * Commentary in English 
			 * Calculating the next page
			 */
			       $nextPage = $this->_page + 1;
				   
				   if($nextPage >= $this->_totalPages)
				   $nextPage = $this->_totalPages;
				   
			$this->_nextPage = $nextPage;
			
			/*
			 * Commentary in Brazilian Portuguese
			 * Calculando a pÃ¡gina anterior
			 *
			 * Commentary in English 
			 * Calculating the previous page
			 */			       
				   $previousPage = $this->_page - 1;
				   if($previousPage <= 1)
				   $previousPage = 1;
				
			$this->_previousPage = $previousPage;
			
			/*
			 * Commentary in Brazilian Portuguese
			 * Retornando um array com todas as pÃ¡ginas
			 *
			 * Commentary in English
			 * Returning an array with all pages
			 */
			$this->_arrayPages = range(1,$this->_totalPages);


                        if($pager != null && in_array($pager, $this->_pager))
                        {

                            /*
                             * Commentary in Brazilian Portuguese
                             * Verificando se o total de Ã­ndices por pÃ¡gina foi informado
                             *
                             * Commentary in English
                             * Checking if the total of index by page was informed
                             */
                             if(!is_numeric($this->_perPage))
                             $this->_perPage = $this->_perPage;

                            /*
                             * Commentary in Brazilian Portuguese
                             * Verificando se as informaÃ§Ãµes extras foram informadas
                             *
                             * Commentary in English
                             * Verifying that the extra information were informed
                             */
                             if(!is_numeric($extraSettings))
                             $extraSettings = $this->_extraSettings;

                            /*
                             * Commentary in Brazilian Portuguese
                             * Chamando a classe de acordo com o tipo informado
                             *
                             * Commentary in English
                             * Calling the class according to the type reported
                             */
                             switch($pager)
                             {
                                case "yahoo":
                                   require_once 'Types/Yahoo.php';
                                   $indexes = new Yahoo;
                                   $indexes = $indexes->ReturnIndexes($this->_page, $this->_totalPages, $marcadores, $extraSettings, $this->_arrayPages);

                                   $this->_indexes = $indexes['index'];
                                   $this->_initialIndex = $indexes['initialIndex'];
                                   $this->_finalIndex = $indexes['finalIndex'];
                                   break;
                               
                                case "google":
                                   require_once 'Types/Google.php';
                                   $indexes = new Google;
                                   $indexes = $indexes->ReturnIndexes($this->_page, $this->_totalPages, $marcadores, $extraSettings, $this->_arrayPages);

                                   $this->_indexes = $indexes['index'];
                                   break;

                                case "jumping":
                                   require_once 'Types/Jumping.php';
                                   $indexes = new Jumping;
                                   $indexes = $indexes->ReturnIndexes($this->_page, $this->_totalPages, $marcadores, $this->_arrayPages);

                                   $this->_indexes = $indexes['index'];
                                   break;
                               
                                case "simple":
                                   require_once 'Types/Simple.php';
                                   $indexes = new Simple;
                                   $indexes = $indexes->ReturnIndexes($this->_page, $this->_totalPages, $marcadores, $this->_arrayPages);

                                   $this->_indexes = $indexes['index'];
                                   break;
                            }

                           
                        }
			
			
		 }



	 }

        
        /*
         * Commentary in Brazilian Portuguese
         * Retornando a pÃ¡gina atual + o nÃºmero de pÃ¡ginas passadas no parÃ¢metro do mÃ©todo
         *
         * Commentary in English
         * Returning the current page + the number of pages reported in the method
         */
         public function Go($parameter)
         {
             $go = (int) $this->_page + $parameter;
             if($go >= $this->_totalPages)
             $go = $this->_totalPages;

             return $go;
         }

         /*
          * Commentary in Brazilian Portuguese
          * Retornando a pÃ¡gina atual - o nÃºmero de pÃ¡ginas passadas no parÃ¢metro do mÃ©todo
          *
          * Commentary in English
          * Returning the current page - the number of pages reported in the method
          */
         public function Back($parameter)
         {
             $back = (int) $this->_page - $parameter;
             if($back <= 1)
             $back = 1;

             return $back;
         }

}

?>