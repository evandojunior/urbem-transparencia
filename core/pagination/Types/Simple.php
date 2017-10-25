<?php

class Simple
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
        

        public function ReturnIndexes($page, $totalPages, $indexesPerPage, $arrayPages)
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
                 * Pegando respectivamente o Ã­ndice atual e o local da pausa no marcador
                 *
                 * Commentary in English
                 * Getting the current index and the pause of indexes
                 */
                 $_currentIndex = $page - 1;

                 $_pause = ($totalPages - $indexesPerPage);
                  
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