<?php

class CompraBO{

	public function create(Compra $compra, $conn=null){
    	CompraBO::validate($compra);
    	$compra->save($conn);
		$compra->free();

    	return $compra;
	}

	public function get($compraId){
        return Doctrine::getTable('Compra')->find($compraId);
	}
	
	public function getCompra($sql=''){
        $query = "SELECT c.cod_compra_direta as cod, c.cod_entidade, c.modalidade, c.descricao_objeto, c.exercicio_entidade 
                   FROM transparencia.compra c 
			 INNER JOIN transparencia.configuracao_entidade c2 ON c2.entidade_id = c.cod_entidade
			 INNER JOIN transparencia.importacao i ON i.id = c.importacao_id
		          WHERE  1=1
				         ".$sql."
				  GROUP BY c.cod_compra_direta, c.cod_entidade, c.modalidade, c.descricao_objeto, c.exercicio_entidade
				  ORDER BY c.cod_compra_direta DESC";
		
        return $query;
	}
	
	public function customFilter(Search $search, $method = '', $request=array()){

		$exercicio = is_null($request['exercicio']) ? ImportacaoBO::getUltimo()->getExercicio() : $request['exercicio'];

        $whereExercicio = " AND i.exercicio = " . $exercicio;

        $query = isset($query) ? $query . $whereExercicio : $whereExercicio;
        $query .= isset($request['cod_entidade']) ? " AND c.cod_entidade = " . $request['cod_entidade'] : '';
		
		$query = CompraBO::$method($query);
		
		/* Calcula o número de registros */
		$statement = Doctrine_Manager::getInstance()->connection();
		$results = $statement->execute($query);
		$rowCount = $results->rowCount();
		
		/* Efetua paginação */
		$query.= " LIMIT ".$search->getMax()." OFFSET ".(($search->getPage()-1)  * $search->getMax());
		$statement = Doctrine_Manager::getInstance()->connection();
		$results = $statement->execute($query);
		
		/* Classe customizada de paginação - utilizada em casos de chaves compostas */
		$customPager = new CustomPager;
		$customPager->setNumPages(ceil($rowCount / $search->getMax()));
		$customPager->setMaxPerPage($search->getMax());
		$customPager->setNumResults($rowCount);
		$search->setPager($customPager);
		
		$compraDTO = new DTO();
		$compraDTO->setObj($results->fetchAll());
		$compraDTO->setSearch($search);

		return $compraDTO;
	}	

	public function geraRelatorio($request, $method){
		$exercicio = ($request['exercicio'] == null) ? ImportacaoBO::getUltimo()->getExercicio() : $request['exercicio'];
        $query.= " AND i.exercicio = ".$exercicio;
        
        if(isset($request['cod_entidade'])){
            $query.= " AND c.cod_entidade = ".$request['cod_entidade'];
		}
		
		$query = CompraBO::$method($query);
        
		$statement = Doctrine_Manager::getInstance()->connection();
		$results = $statement->execute($query);
		
		return $results;
	}	
    
	public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');
        
        while(!feof($data)) { 
        	$line = fgets($data,4096);
			/*
			 * Lembrar de calcular (-1) no 1° parâmetro do mb_substr, pois a documentação começa a contar os
			 * caracteres do 1 e o mb_substr começa a contagem pelo 0
			 */  
            $compra = new Compra();
            $compra->setImportacaoId($importacaoId);
            $compra->setExercicioEntidade(mb_substr($line,0,4));
            $compra->setCodEntidade(mb_substr($line,4,2));
            $compra->setCodCompraDireta(mb_substr($line,6,8));
            $compra->setModalidade(mb_substr($line,14,50));
            $compra->setExercicioEmpenho(mb_substr($line,64,4));
            $compra->setCodEmpenho(mb_substr($line,68,8));
            $compra->setDescricaoTipoLicitacao(mb_substr($line,76,15));
            $compra->setDescricaoTipoObjeto(mb_substr($line,91,50));
            $compra->setDescricaoObjeto(mb_substr($line,141,500));
            
		    CompraBO::create($compra, $conn);
		}
		
		fclose($data);
    }
	
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Compra c');
        $query->where('c.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    } 	
		
	public function validate(Compra $compra){}
}