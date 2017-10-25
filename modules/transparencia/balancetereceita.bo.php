<?php

class BalanceteReceitaBO{

	public function create(BalanceteReceita $balanceteReceita, $conn=null){
    	BalanceteReceitaBO::validate($balanceteReceita);
    	$balanceteReceita->save($conn);
		$balanceteReceita->free();

    	return $balanceteReceita;
	}

	public function get($balanceteReceitaId){
        return Doctrine::getTable('BalanceteReceita')->find($balanceteReceitaId);
	}
	
	/*public function getBalanceteReceitaConta(){
        $query = new Doctrine_Query();
        $query->select("(CASE WHEN LENGTH(CAST(br.cod_conta AS VARCHAR)) = 14 THEN REPLACE(TO_CHAR(br.cod_conta, '9:9:9:9:99:99:99:99:99'), ':', '.') ELSE REPLACE(TO_CHAR(br.cod_conta, '9:9:9:9:9:99:99:99:99:99'), ':', '.') END) as cod_conta")
			  ->addSelect("SUM(receita_janeiro+receita_fevereiro+receita_marco+receita_abril+receita_maio+receita_junho+receita_julho+receita_agosto+receita_setembro+receita_outubro+receita_novembro+receita_dezembro) as receita_realizado")
			  ->addSelect("SUM(br.receita_orcada) as receita_orcada")
			  ->addSelect("SUM(receita_janeiro+receita_fevereiro+receita_marco+receita_abril+receita_maio+receita_junho+receita_julho+receita_agosto+receita_setembro+receita_outubro+receita_novembro+receita_dezembro) as receita_realizado")
			  ->addSelect("SUM(receita_janeiro)   as receita_janeiro")
			  ->addSelect("SUM(receita_fevereiro) as receita_fevereiro")
			  ->addSelect("SUM(receita_marco)     as receita_marco")
			  ->addSelect("SUM(receita_abril)     as receita_abril")
			  ->addSelect("SUM(receita_maio)      as receita_maio")
			  ->addSelect("SUM(receita_junho)     as receita_junho")
			  ->addSelect("SUM(receita_julho)     as receita_julho")
			  ->addSelect("SUM(receita_agosto)    as receita_agosto")
			  ->addSelect("SUM(receita_setembro)  as receita_setembro")
			  ->addSelect("SUM(receita_outubro)   as receita_outubro")
			  ->addSelect("SUM(receita_novembro)  as receita_novembro")
			  ->addSelect("SUM(receita_dezembro)  as receita_dezembro")
			  ->addSelect("numero_nivel")
			  ->addSelect("tipo_nivel")
			  ->addSelect("especificacao_conta")
              ->from('BalanceteReceita br')
			  ->innerJoin('br.ConfiguracaoEntidade c')
              ->innerJoin('br.Importacao i')
			  ->groupBy('br.cod_conta, br.especificacao_conta, br.numero_nivel, br.tipo_nivel')
			  ->orderBy('br.cod_conta');

        return $query;
	}*/

	public function getBalanceteReceitaConta($exercicio=null, $codEntidade=null){

		$exercicio = ($exercicio == null) ? ImportacaoBO::getUltimo()->getExercicio() : $exercicio;
	
		$sql = "
			SELECT
				   (CASE WHEN LENGTH(CAST(br.cod_conta AS VARCHAR)) = 14 THEN REPLACE(TO_CHAR(br.cod_conta, '9:9:9:9:99:99:99:99:99'), ':', '.') ELSE REPLACE(TO_CHAR(br.cod_conta, '9:9:9:9:9:99:99:99:99:99'), ':', '.') END) as cod_conta
				 , br.especificacao_conta
				 , SUM(br.receita_orcada) as receita_orcada
				 , SUM(receita_janeiro+receita_fevereiro+receita_marco+receita_abril+receita_maio+receita_junho+receita_julho+receita_agosto+receita_setembro+receita_outubro+receita_novembro+receita_dezembro) as receita_realizado
				 , SUM(receita_janeiro)   as receita_janeiro
				 , SUM(receita_fevereiro) as receita_fevereiro
				 , SUM(receita_marco)     as receita_marco
				 , SUM(receita_abril)     as receita_abril
				 , SUM(receita_maio)      as receita_maio
				 , SUM(receita_junho)     as receita_junho
				 , SUM(receita_julho)     as receita_julho
				 , SUM(receita_agosto)    as receita_agosto
				 , SUM(receita_setembro)  as receita_setembro
				 , SUM(receita_outubro)   as receita_outubro
				 , SUM(receita_novembro)  as receita_novembro
				 , SUM(receita_dezembro)  as receita_dezembro
				 , numero_nivel
				 , tipo_nivel
				 
			  FROM transparencia.balancete_receita br

		INNER JOIN transparencia.importacao i
				ON br.importacao_id = i.id
				
		INNER JOIN transparencia.configuracao_entidade c
				ON br.cod_entidade = c.entidade_id
		
			 WHERE i.exercicio = ".$exercicio;
					
		if($codEntidade != null) {
		   $sql.= " AND br.cod_entidade = ".$codEntidade;
		 }
		  
		$sql.= " GROUP BY br.cod_conta, br.especificacao_conta, br.numero_nivel, br.tipo_nivel
				 ORDER BY br.cod_conta ASC ";

        return $sql;
	}

	public function getBalanceteReceitaContaTotalByExercicio($exercicio=null, $codEntidade=null, $mes=null){
		
		$exercicio = ($exercicio == null) ? ImportacaoBO::getUltimo()->getExercicio() : $exercicio;
        
		$query = "
 			SELECT
			       (COALESCE((SUM(CASE WHEN LENGTH(CAST(br.cod_conta AS VARCHAR)) = 14 THEN br.receita_orcada END)),0) - COALESCE((SUM(CASE WHEN LENGTH(CAST(br.cod_conta AS VARCHAR)) = 15 THEN br.receita_orcada END)),0)) AS receita_orcada
		 	     , (COALESCE((SUM(CASE WHEN LENGTH(CAST(br.cod_conta AS VARCHAR)) = 14 THEN (receita_janeiro+receita_fevereiro+receita_marco+receita_abril+receita_maio+receita_junho+receita_julho+receita_agosto+receita_setembro+receita_outubro+receita_novembro+receita_dezembro) END)),0) - COALESCE((SUM(CASE WHEN LENGTH(CAST(br.cod_conta AS VARCHAR)) = 15 THEN (receita_janeiro+receita_fevereiro+receita_marco+receita_abril+receita_maio+receita_junho+receita_julho+receita_agosto+receita_setembro+receita_outubro+receita_novembro+receita_dezembro) END)),0)) AS receita_realizado ";
              
		# Usado para listagem de Receita por Mês.
		if ($mes != null) {
			$query .= ", (SELECT COALESCE((SUM(CASE WHEN LENGTH(CAST(br2.cod_conta AS VARCHAR)) = 14 THEN br2.receita_$mes END)),0) - COALESCE((SUM(CASE WHEN LENGTH(CAST(br2.cod_conta AS VARCHAR)) = 15 THEN br2.receita_$mes END)),0)
							FROM transparencia.balancete_receita br2
     				  
					  INNER JOIN transparencia.configuracao_entidade c
     						  ON br2.cod_entidade = c.entidade_id								
						
						   WHERE i.exercicio = ".$exercicio."
						     AND br2.numero_nivel = '1' ";

			if ($codEntidade != null) {
				$query .= "  AND br2.cod_entidade = ".$codEntidade;
			}

			$query .= " ) AS receita_realizado_mes ";
		}

		$query .= "
			  FROM transparencia.balancete_receita br

		INNER JOIN transparencia.importacao i 
				ON br.importacao_id = i.id
				
		INNER JOIN transparencia.configuracao_entidade c
				ON br.cod_entidade = c.entidade_id				
		
			 WHERE i.exercicio = ".$exercicio."
               AND br.numero_nivel = '1' ";

		if ($codEntidade != null) {
			$query .= " AND br.cod_entidade = ".$codEntidade;
		}
		
		$query .= " GROUP BY i.exercicio";

		$statement = Doctrine_Manager::getInstance()->connection();
		$results = $statement->execute($query);

        return $results;
	}

	public function filter(Search $search, $method = '', $request=array()){
		$query = BalanceteReceitaBO::$method($request['exercicio'], $request['cod_entidade']);
		
		/* Calcula o número de registros */
		$statement = Doctrine_Manager::getInstance()->connection();
		$results = $statement->execute($query);
		$rowCount = $results->rowCount();
		
		/* Efetua paginação */
		$query .= " LIMIT ".$search->getMax()." OFFSET ".(($search->getPage()-1) * $search->getMax());
		$statement = Doctrine_Manager::getInstance()->connection();
		$results = $statement->execute($query);
		
		/* Classe customizada de paginação - utilizada em casos de chaves compostas */
		$customPager = new CustomPager;
		$customPager->setNumPages(ceil($rowCount / $search->getMax()));
		$customPager->setMaxPerPage($search->getMax());
		$customPager->setNumResults($rowCount);
		$search->setPager($customPager);
		
		$balanceteReceitaDTO = new DTO();
		$balanceteReceitaDTO->setObj($results);
		$balanceteReceitaDTO->setSearch($search);

		return $balanceteReceitaDTO;
	}
	
	public function geraRelatorio($request, $method){
		$query = BalanceteReceitaBO::$method($request['exercicio'], $request['cod_entidade']);
		
		/* Calcula o número de registros */
		$statement = Doctrine_Manager::getInstance()->connection();
		$results = $statement->execute($query);
		$rowCount = $results->rowCount();
		
		/* Efetua paginação */
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
            $balanceteReceita = new BalanceteReceita();
            $balanceteReceita->setImportacaoId($importacaoId);
			$balanceteReceita->setCodEntidade(mb_substr($line,0,2));
			$balanceteReceita->setCodConta(mb_substr($line,2,20));
			$balanceteReceita->setCodOrgaoUnidade(mb_substr($line,22,10));
			$balanceteReceita->setReceitaOrcada(formatNumberPgSQL(str_replace('-', '', mb_substr($line,32,13))));
			$balanceteReceita->setReceitaJaneiro(formatNumberPgSQL(str_replace('-', '', mb_substr($line,45,13))));
			$balanceteReceita->setReceitaFevereiro(formatNumberPgSQL(str_replace('-', '', mb_substr($line,58,13))));
			$balanceteReceita->setReceitaMarco(formatNumberPgSQL(str_replace('-', '', mb_substr($line,71,13))));
			$balanceteReceita->setReceitaAbril(formatNumberPgSQL(str_replace('-', '', mb_substr($line,84, 13))));
			$balanceteReceita->setReceitaMaio(formatNumberPgSQL(str_replace('-', '', mb_substr($line,97,13))));
			$balanceteReceita->setReceitaJunho(formatNumberPgSQL(str_replace('-', '', mb_substr($line,110,13))));
			$balanceteReceita->setReceitaJulho(formatNumberPgSQL(str_replace('-', '', mb_substr($line,123,13))));
			$balanceteReceita->setReceitaAgosto(formatNumberPgSQL(str_replace('-', '', mb_substr($line,136,13))));
			$balanceteReceita->setReceitaSetembro(formatNumberPgSQL(str_replace('-', '', mb_substr($line,149,13))));
			$balanceteReceita->setReceitaOutubro(formatNumberPgSQL(str_replace('-', '', mb_substr($line,162,13))));
			$balanceteReceita->setReceitaNovembro(formatNumberPgSQL(str_replace('-', '', mb_substr($line,175,13))));
			$balanceteReceita->setReceitaDezembro(formatNumberPgSQL(str_replace('-', '', mb_substr($line,188,13))));
			$balanceteReceita->setEspecificacaoConta(mb_substr($line,201,170));
			$balanceteReceita->setTipoNivel(mb_substr($line,371,1));
			$balanceteReceita->setNumeroNivel(trim(mb_substr($line,372,2)));
			$balanceteReceita->setCodRecurso(mb_substr($line,374,2));

            BalanceteReceitaBO::create($balanceteReceita, $conn);
        }
        
        fclose($data);
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('BalanceteReceita b');
        $query->where('b.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }    
		
	public function validate(BalanceteReceita $balanceteReceita){}
}
