<?php

class Google
{
        /*
         * Commentary in Brazilian Portuguese
         * valor da pÃ¡gina atual no indice do array com todas as pÃ¡ginas e valor da pÃ¡gina atual
         *
         * Commentary in English
         * value of the current page index in the array with all pages and value of the current page
         *
         * @ var int
         */
        static protected $_currentIndex;

        /*
         * Commentary in Brazilian Portuguese
         * array com os indices que serÃ£o retornados no final
         *
         * Commentary in English
         * array with the indices that will be returned at the end
         *
         * @ var array
         */
        static protected $_indexes = array();

        /*
         * Commentary in Brazilian Portuguese
         * onde o marcador dever parar de fazer o slice (corte)
         *
         * Commentary in English
         * where the marker should stop making the slice (cut)
         *
         * @ var int
         */
        static protected $_pause;

        /*
         * Commentary in Brazilian Portuguese
         * valor da propriedade que centraliza a pÃ¡gina atual dentro do array
         *
         * Commentary in English
         * property value that centers the current page within the array
         *
         * @ var int
         */
        static protected $_centeringIndex;

        /*
         * Commentary in Brazilian Portuguese
         * valor dos indices atuais
         * 
         * Commentary in English
         * value of the current indexes
         *
         * @ var int
         */
        static protected $_currentIndexes;

        /*
         * Commentary in Brazilian Portuguese
         * valor da pÃ¡gina atual - 1
         *
         * Commentary in English
         * value of current page -1
         *
         * @ var int
         */
        static protected $_add;
        

        public function ReturnIndexes($page, $totalPages, $indexesPerPage, $extraSettings, $arrayPages)
	{
            /*
             * Commentary in Brazilian Portuguese
             * Se o total de pÃ¡ginas for maior que o total de Ã­ndices
             * 
             * Commentary in English
             * If the total of pages is greater than the total index
             */
            if($totalPages > $indexesPerPage)
            {

                 /*
                  * Commentary in Brazilian Portuguese
                  * Se o total de indices inicial for maior que o total de indices finais
                  * EntÃ£o o total de indices iniciais Ã© igual ao total de indices finais
                  * 
                  */
                  if($extraSettings > $indexesPerPage)
                  $extraSettings = $indexesPerPage;

                  /*
                   * Commentary in Brazilian Portuguese
                   * verifico se a pÃ¡gina atual Ã© a primeira pois o Google deixa sempre uma
                   * quantidade menor de nÃºmeros de pÃ¡ginas quando Ã© a primeira pÃ¡gina
                   * Se for a primera pÃ¡gina eu coloco apenas o valor informado em $extrasSettings
                   * Exemplo: pÃ¡gina 1 marcadores do 1 ao 10
                   * a partir da pÃ¡gina 1 comeÃ§a a soma dos marcadores
                   * Exemplo: pÃ¡gina 2 marcadores do 1 ao 11
                   *
                   */
                  if($page == 1)
                  {
                     $_indexes['index'] = array_slice($arrayPages, 0, $extraSettings);
                  }

                  else
                  {
                       /*
                        * Commentary in Brazilian Portuguese
                        * criando o acrescimo de indices
                        * PÃ¡gina atual - 1  Exemplo: pÃ¡gina 2, acrescimo de 1 indice, pÃ¡gina 3, acrescimo de 2 indices
                        *
                        */
                        $_add = $page - 1;

                       /*
                        * Commentary in Brazilian Portuguese
                        * Acrescentando os indices ao total de indices iniciais
                        */
                        $_currentIndexes = $_add + $extraSettings;

                        /*
                         * Commentary in Brazilian Portuguese
                         * Se o total de indices finais for maior que o total de indices atuais
                         * EntÃ£o o total de indices recebe o valor do indice atual
                         * 
                         */
                        if($indexesPerPage > $_currentIndexes)
                        $indexesPerPage = $_currentIndexes;

                        /*
                         * Commentary in Brazilian Portuguese
                         * Pegando a metade do total de marcadores definidos
                         *
                         * Commentary in English
                         * Taking half the total set of markers
                         */
                         $_half = ceil($indexesPerPage/2);

                         /*
                          * Commentary in Brazilian Portuguese
                          * Verificando se o total de marcadores Ã© um nÃºmero par, para definir onde serÃ¡ o comeÃ§o
                          * (centraliza os indices com a pÃ¡gina atual no meio)
                          *
                          * Commentary in English
                          * Checking if the total of markers is an even number, where it will be to define the centeringIndex
                          */
                          if($indexesPerPage % 2 == 0)
                          $_centeringIndex = $_half;

                          else
                          $_centeringIndex = ($indexesPerPage - $_half);


                          /*
                           * Commentary in Brazilian Portuguese
                           * Pegando respectivamente o Ã­ndice atual e o local da pausa no marcador
                           *
                           * Commentary in English
                           * Getting the current index and the pause of indexes
                           */
                           $_currentIndex = $page - 1;

                           $_pause = ($totalPages - $indexesPerPage);

                           /*
                            * Commentary in Brazilian Portuguese
                            * Verificando se o Ã­ndice atual Ã© maior que o comeÃ§o
                            * Em caso afirmativo o Ã­ndice atual Ã© igual ao Ã­ndice atual - o comeÃ§o (centralizador de indice)
                            *
                            * Commentary in English
                            * Checking if the current index is greater than the beginning
                            * If so the current index equals the current index - the centering
                            */
                            if($_currentIndex > $_centeringIndex)
                            {
                               $_currentIndex = ($_currentIndex - $_centeringIndex);
                            }

                            if($page > $_pause)
                            {
                               $_currentIndex = $_pause;
                            }

                            /*
                             * Commentary in Brazilian Portuguese
                             * Retorna um array com os Ã­ndices das pÃ¡ginas e com a pÃ¡gina atual no centro
                             *
                             * Commentary in English
                             * Returns an array with the contents of pages and the current page in the center
                             */
                             $_indexes['index'] = array_slice($arrayPages,$_currentIndex,$indexesPerPage);
                     
                  }

            }
            /*
             * Commentary in Brazilian Portuguese
             * Caso contrÃ¡rio o array de Ã­ndices retornado Ã© igual ao array com todas as pÃ¡ginas
             *
             * Commentary in English
             * Otherwise, the returned array of indices is equal to the array of all pages
             */
            else
            {
                $_indexes['index'] = $arrayPages;
            }

            return $_indexes;


       }
}
?>