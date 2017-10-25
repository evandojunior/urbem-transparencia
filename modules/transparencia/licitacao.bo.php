<?php

class LicitacaoBO{

	public function create(Licitacao $licitacao, $conn=null){
    	LicitacaoBO::validate($licitacao);
    	$licitacao->save($conn);
		$licitacao->free();

    	return $licitacao;
	}

	public function get($licitacaoId){
        return Doctrine::getTable('Licitacao')->find($licitacaoId);
	}

	public function getLicitacao($sql=''){
        $query = "  SELECT l.cod_licitacao as cod, l.cod_entidade, l.modalidade, l.descricao_objeto, l.exercicio_entidade 
                      FROM transparencia.licitacao l
			    INNER JOIN transparencia.configuracao_entidade c2 ON c2.entidade_id = l.cod_entidade
			    INNER JOIN transparencia.importacao i ON i.id = l.importacao_id
		             WHERE  1=1
				           ".$sql."
				  GROUP BY l.cod_licitacao, l.cod_entidade, l.modalidade, l.descricao_objeto, l.exercicio_entidade
				  ORDER BY l.cod_licitacao DESC";
		
        return $query;
	}
	
	public function customFilter(Search $search, $method = '', $request=array()){
        $exercicio = is_null($request['exercicio']) ? ImportacaoBO::getUltimo()->getExercicio() : $request['exercicio'];

        $whereExercicio = " AND i.exercicio = " . $exercicio;

        $query = isset($query) ? $query . $whereExercicio : $whereExercicio;
        $query .= isset($request['cod_entidade']) ? " AND l.cod_entidade = " . $request['cod_entidade'] : '';
		
		$query = LicitacaoBO::$method($query);
		
		/* Calcula o número de registros */
		$statement = Doctrine_Manager::getInstance()->connection();
		$results = $statement->execute($query);
		$rowCount = $results->rowCount();
		
		/* Efetua paginação */
		$query.= " LIMIT ".$search->getMax()." OFFSET ".(($search->getPage()-1) * $search->getMax());
		$statement = Doctrine_Manager::getInstance()->connection();
		$results = $statement->execute($query);
		
		/* Classe customizada de paginação - utilizada em casos de chaves compostas */
		$customPager = new CustomPager;
		$customPager->setNumPages(round($rowCount / $search->getMax()));
		$search->setPager($customPager);
		
		$licitacaoDTO = new DTO();
		$licitacaoDTO->setObj($results->fetchAll());
		$licitacaoDTO->setSearch($search);

		return $licitacaoDTO;
	}	

	public function geraRelatorio($request, $method){
		$exercicio = ($request['exercicio'] == null) ? ImportacaoBO::getUltimo()->getExercicio() : $request['exercicio'];
        $query.= " AND i.exercicio = ".$exercicio;
        
        if(isset($request['cod_entidade'])){
            $query.= " AND l.cod_entidade = ".$request['cod_entidade'];
		}
		
		$query = LicitacaoBO::$method($query);
        
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
            $licitacao = new Licitacao();
            $licitacao->setImportacaoId($importacaoId);
            $licitacao->setExercicioEntidade(mb_substr($line,0,4));
            $licitacao->setCodEntidade(mb_substr($line,4,2));
            $licitacao->setCodLicitacao(mb_substr($line,6,8));
            $licitacao->setModalidade(mb_substr($line,14,50));
            $licitacao->setExercicioEmpenho(mb_substr($line,64,4));
            $licitacao->setCodEmpenho(mb_substr($line,68,8));
            $licitacao->setDescricaoTipoLicitacao(mb_substr($line,76,15));
            $licitacao->setDescricaoTipoObjeto(mb_substr($line,91,50));
            $licitacao->setDescricaoObjeto(mb_substr($line,141,500));
            
		    LicitacaoBO::create($licitacao, $conn);
		}
        
        fclose($data);
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Licitacao l');
        $query->where('l.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }      
    
	public function validate(Licitacao $licitacao){}
}