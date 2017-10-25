<?php

class BalanceteDespesaBO{

	public function create(BalanceteDespesa $balanceteDespesa, $conn=null){
    	BalanceteDespesaBO::validate($balanceteDespesa);
    	$balanceteDespesa->save($conn);
		$balanceteDespesa->free();

    	return $balanceteDespesa;
	}

	public function get($balanceteDespesaId){
        return Doctrine::getTable('BalanceteDespesa')->find($balanceteDespesaId);
	}
	
	public function getBalanceteDespesaOrgao($exercicio=null, $codEntidade=null){
		$exercicio = ($exercicio == null) ? ImportacaoBO::getUltimo()->getExercicio() : $exercicio;
		
        $query = new Doctrine_Query();
        $query->select("o.cod_orgao")
			  ->addSelect("o.cod_orgao as cod")
			  ->addSelect("o.nome_orgao as descricao")
			  ->addSelect("SUM(bd.valor_pago) as valor_pago")
			  ->addSelect("SUM(bd.valor_empenhado) as valor_empenhado")
			  ->addSelect("SUM(bd.valor_liquidado) as valor_liquidado")
 		      ->addSelect("(SELECT SUM(bd3.dotacao_inicial) FROM BalanceteDespesa bd3 WHERE bd3.cod_orgao = o.cod_orgao AND CAST(bd3.cod_elemento AS VARCHAR) = SUBSTR(CAST(bd3.cod_elemento AS VARCHAR), 1, 6)||'00000000' AND bd3.importacao_id = (SELECT i3.id from Importacao i3 WHERE i3.exercicio = ".$exercicio." ORDER BY i3.timestamp DESC LIMIT 1) ) as dotacao_inicial")
              ->from('Orgao o')
              ->innerJoin('o.Importacao i')
			  ->innerJoin('o.BalanceteDespesa bd WITH bd.importacao_id = i.id')
			  ->innerJoin('bd.ConfiguracaoEntidade c')
			  ->where('o.exercicio = ?', $exercicio)
			  ->AndWhere('o.importacao_id = (SELECT i2.id from Importacao i2 WHERE i2.exercicio = ? ORDER BY i2.timestamp DESC LIMIT 1)', $exercicio)
			  ->groupBy('o.id')
			  ->addGroupBy('o.cod_orgao')
			  ->addGroupBy('o.nome_orgao')
			  ->orderBy('o.nome_orgao');
			  
		if(!is_null($codEntidade)) {
			$query->andWhere('bd.cod_entidade = ?', $codEntidade);
		}

        return $query;
	}

	public function getBalanceteDespesaFuncao($exercicio=null, $codEntidade=null){
		$exercicio = ($exercicio == null) ? ImportacaoBO::getUltimo()->getExercicio() : $exercicio;
		
        $query = new Doctrine_Query();
        $query->select("f.cod_funcao")
		      ->addSelect("f.cod_funcao as cod")
		      ->addSelect("f.nome_funcao as descricao")
		      ->addSelect("SUM(bd.valor_pago) as valor_pago")
		      ->addSelect("SUM(bd.valor_empenhado) as valor_empenhado")
		      ->addSelect("SUM(bd.valor_liquidado) as valor_liquidado")
 		      ->addSelect("(SELECT SUM(bd3.dotacao_inicial) FROM BalanceteDespesa bd3 WHERE bd3.cod_funcao = f.cod_funcao AND CAST(bd3.cod_elemento AS VARCHAR) = SUBSTR(CAST(bd3.cod_elemento AS VARCHAR), 1, 6)||'00000000' AND bd3.importacao_id = (SELECT i3.id from Importacao i3 WHERE i3.exercicio = ".$exercicio." ORDER BY i3.timestamp DESC LIMIT 1) ) as dotacao_inicial")
              ->from('Funcao f')
			  ->innerJoin('f.Importacao i')
			  ->innerJoin('f.BalanceteDespesa bd WITH bd.importacao_id = i.id')
			  ->innerJoin('bd.ConfiguracaoEntidade c')
			  ->where('f.exercicio = ?', $exercicio)
			  ->AndWhere('f.importacao_id = (SELECT i2.id from Importacao i2 WHERE i2.exercicio = ? ORDER BY i2.timestamp DESC LIMIT 1)', $exercicio)
			  ->groupBy('f.id')
			  ->addGroupBy('f.cod_funcao')
			  ->addGroupBy('f.nome_funcao')
			  ->orderBy('f.nome_funcao');
			  
		if(!is_null($codEntidade)) {
			$query->andWhere('bd.cod_entidade = ?', $codEntidade);
		}
			  
        return $query;
	}

	public function getBalanceteDespesaPrograma($exercicio=null, $codEntidade=null){
		$exercicio = ($exercicio == null) ? ImportacaoBO::getUltimo()->getExercicio() : $exercicio;
		
        $query = new Doctrine_Query();
        $query->select("p.cod_programa")
			  ->addSelect("p.cod_programa as cod")
			  ->addSelect("p.nome_programa as descricao")
			  ->addSelect("SUM(bd.valor_pago) as valor_pago")
			  ->addSelect("SUM(bd.valor_empenhado) as valor_empenhado")
			  ->addSelect("SUM(bd.valor_liquidado) as valor_liquidado")
 		      ->addSelect("(SELECT SUM(bd3.dotacao_inicial) FROM BalanceteDespesa bd3 WHERE bd3.cod_programa = p.cod_programa AND CAST(bd3.cod_elemento AS VARCHAR) = SUBSTR(CAST(bd3.cod_elemento AS VARCHAR), 1, 6)||'00000000' AND bd3.importacao_id = (SELECT i3.id from Importacao i3 WHERE i3.exercicio = ".$exercicio." ORDER BY i3.timestamp DESC LIMIT 1) ) as dotacao_inicial")
              ->from('Programa p')
			  ->innerJoin('p.Importacao i')
			  ->innerJoin('p.BalanceteDespesa bd WITH bd.importacao_id = i.id')
			  ->innerJoin('bd.ConfiguracaoEntidade c')
			  ->where('p.exercicio = ?', $exercicio)
			  ->AndWhere('p.importacao_id = (SELECT i2.id from Importacao i2 WHERE i2.exercicio = ? ORDER BY i2.timestamp DESC LIMIT 1)', $exercicio)
			  ->groupBy('p.id')
			  ->addGroupBy('p.cod_programa')
			  ->addGroupBy('p.nome_programa')
			  ->orderBy('p.nome_programa');
			  
		if(!is_null($codEntidade)) {
			$query->andWhere('bd.cod_entidade = ?', $codEntidade);
		}

        return $query;
	}

	public function getBalanceteDespesaProjeto($exercicio=null, $codEntidade=null){
		$exercicio = ($exercicio == null) ? ImportacaoBO::getUltimo()->getExercicio() : $exercicio;
		
        $query = new Doctrine_Query();
        $query->select("a.cod_projeto")
			  ->addSelect("a.cod_projeto as cod")
			  ->addSelect("a.nome_projeto as descricao")
			  ->addSelect("SUM(bd.valor_pago) as valor_pago")
			  ->addSelect("SUM(bd.valor_empenhado) as valor_empenhado")
			  ->addSelect("SUM(bd.valor_liquidado) as valor_liquidado")
 		      ->addSelect("(SELECT SUM(bd3.dotacao_inicial) FROM BalanceteDespesa bd3 INNER JOIN bd3.ConfiguracaoEntidade c2 WHERE bd3.cod_projeto = a.cod_projeto AND CAST(bd3.cod_elemento AS VARCHAR) = SUBSTR(CAST(bd3.cod_elemento AS VARCHAR), 1, 6)||'00000000' AND bd3.importacao_id = (SELECT i3.id from Importacao i3 WHERE i3.exercicio = ".$exercicio." ORDER BY i3.timestamp DESC LIMIT 1) ) as dotacao_inicial")
              ->from('Acao a')
			  ->innerJoin('a.Importacao i')
			  ->innerJoin('a.BalanceteDespesa bd WITH bd.importacao_id = i.id')
			  ->innerJoin('bd.ConfiguracaoEntidade c')
			  ->where('a.exercicio = ?', $exercicio)
			  ->AndWhere('a.importacao_id = (SELECT i2.id from Importacao i2 WHERE i2.exercicio = ? ORDER BY i2.timestamp DESC LIMIT 1)', $exercicio)
			  ->groupBy('a.id')
			  ->addGroupBy('a.cod_projeto')
			  ->addGroupBy('a.nome_projeto')
			  ->orderBy('a.nome_projeto');
			  
		if(!is_null($codEntidade)) {
			$query->andWhere('bd.cod_entidade = ?', $codEntidade);
		}

        return $query;
	}

	public function getBalanceteDespesaCategoriaEconomica($args){
		$query = "SELECT * FROM
							  (SELECT  (SELECT '3 - '||especificacao_rubrica_despesa FROM transparencia.rubrica r WHERE CAST(r.cod_rubrica_despesa AS VARCHAR) LIKE '3000000000000%' AND r.exercicio = ".$args['exercicio'].") AS descricao
							, (SELECT SUM(dotacao_inicial) FROM transparencia.balancete_despesa bd2
													 INNER JOIN transparencia.configuracao_entidade c ON c.entidade_id = bd2.cod_entidade
														  WHERE CAST(bd2.cod_elemento AS VARCHAR) LIKE '3%'
														    AND CAST(bd2.cod_elemento AS VARCHAR) = SUBSTR(CAST(bd2.cod_elemento AS VARCHAR), 1, 6)||'00000000'
															AND bd2.importacao_id = (SELECT i3.id from transparencia.importacao i3 WHERE i3.exercicio = ".$args['exercicio']." ORDER BY i3.timestamp DESC LIMIT 1)
							     ".($args['cod_entidade'] != null ? "AND bd2.cod_entidade = ".$args['cod_entidade'] : "" )."
								 

							) AS dotacao_inicial
							, SUM(bd.valor_empenhado) as valor_empenhado
							, SUM(bd.valor_liquidado) as valor_liquidado
							, SUM(bd.valor_pago) as valor_pago
							, '3' AS cod_categoria
					  FROM transparencia.balancete_despesa bd
				INNER JOIN transparencia.configuracao_entidade c ON c.entidade_id = bd.cod_entidade
				INNER JOIN transparencia.importacao i ON i.id = bd.importacao_id
					 WHERE ".$args['filtro']."
					   AND CAST(bd.cod_elemento AS VARCHAR) LIKE '3%'
					 
					UNION
					
					SELECT    (SELECT '4 - '||especificacao_rubrica_despesa FROM transparencia.rubrica r WHERE CAST(r.cod_rubrica_despesa AS VARCHAR) LIKE '4000000000000%' AND r.exercicio = ".$args['exercicio'].") AS descricao
						    , (SELECT SUM(dotacao_inicial) FROM transparencia.balancete_despesa bd2
													 INNER JOIN transparencia.configuracao_entidade c ON c.entidade_id = bd2.cod_entidade
					                                      WHERE CAST(bd2.cod_elemento AS VARCHAR) LIKE '4%'
														    AND CAST(bd2.cod_elemento AS VARCHAR) = SUBSTR(CAST(bd2.cod_elemento AS VARCHAR), 1, 6)||'00000000'
															AND bd2.importacao_id = (SELECT i3.id from transparencia.importacao i3 WHERE i3.exercicio = ".$args['exercicio']." ORDER BY i3.timestamp DESC LIMIT 1)
						    	 ".($args['cod_entidade'] != null ? "AND bd2.cod_entidade = ".$args['cod_entidade'] : "" )."
						     ) AS dotacao_inicial
						    , SUM(bd.valor_empenhado) as valor_empenhado
						    , SUM(bd.valor_liquidado) as valor_liquidado
						    , SUM(bd.valor_pago) as valor_pago
						    , '4' AS cod_categoria
					  FROM transparencia.balancete_despesa bd
				INNER JOIN transparencia.configuracao_entidade c ON c.entidade_id = bd.cod_entidade
				INNER JOIN transparencia.importacao i ON i.id = bd.importacao_id
					 WHERE ".$args['filtro']."
					   AND CAST(bd.cod_elemento AS VARCHAR) LIKE '4%'
					
					UNION
					
					SELECT    (SELECT '9 - '||especificacao_rubrica_despesa FROM transparencia.rubrica r WHERE CAST(r.cod_rubrica_despesa AS VARCHAR) LIKE '9000000000000%' AND r.exercicio = ".$args['exercicio'].") AS descricao
						    , (SELECT SUM(dotacao_inicial) FROM transparencia.balancete_despesa bd2
													 INNER JOIN transparencia.configuracao_entidade c ON c.entidade_id = bd2.cod_entidade
					                                      WHERE CAST(bd2.cod_elemento AS VARCHAR) LIKE '9%'
															AND CAST(bd2.cod_elemento AS VARCHAR) = SUBSTR(CAST(bd2.cod_elemento AS VARCHAR), 1, 6)||'00000000'
															AND bd2.importacao_id = (SELECT i3.id from transparencia.importacao i3 WHERE i3.exercicio = ".$args['exercicio']." ORDER BY i3.timestamp DESC LIMIT 1)
						    	 ".($args['cod_entidade'] != null ? "AND bd2.cod_entidade = ".$args['cod_entidade'] : "" )."
						    ) AS dotacao_inicial
						    , SUM(bd.valor_empenhado) as valor_empenhado
						    , SUM(bd.valor_liquidado) as valor_liquidado
						    , SUM(bd.valor_pago) as valor_pago
						    , '9' AS cod_categoria
					  FROM transparencia.balancete_despesa bd
				INNER JOIN transparencia.configuracao_entidade c ON c.entidade_id = bd.cod_entidade					  
				INNER JOIN transparencia.importacao i ON i.id = bd.importacao_id
					 WHERE ".$args['filtro']."
				       AND CAST(bd.cod_elemento AS VARCHAR) LIKE '9%'
		) as tbl ORDER BY descricao ";
		
        return $query;
	}

	public function getBalanceteDespesaCategoriaNatureza($args){
		$query = "SELECT cod_rubrica_despesa
					     , SUBSTR(CAST(r.cod_rubrica_despesa AS VARCHAR), 1, 2)||' - '||especificacao_rubrica_despesa AS descricao
					     
					     , (SELECT SUM(dotacao_inicial) FROM transparencia.balancete_despesa bd
												  INNER JOIN transparencia.configuracao_entidade c ON c.entidade_id = bd.cod_entidade
					     	    				  INNER JOIN transparencia.importacao i ON i.id = bd.importacao_id
					     						  	   WHERE ".$args['filtro']."
					     						  	     AND CAST(bd.cod_elemento AS VARCHAR) LIKE SUBSTR(CAST(r.cod_rubrica_despesa AS VARCHAR), 1, 2)||'%'
					     						  		 AND CAST(bd.cod_elemento AS VARCHAR) = SUBSTR(CAST(bd.cod_elemento AS VARCHAR), 1, 6)||'00000000'
														 AND bd.importacao_id = (SELECT i3.id from transparencia.importacao i3 WHERE i3.exercicio = ".$args['exercicio']." ORDER BY i3.timestamp DESC LIMIT 1)
					     ) as dotacao_inicial
					     
					     , (SELECT SUM(bd.valor_empenhado) FROM transparencia.balancete_despesa bd INNER JOIN transparencia.importacao i ON i.id = bd.importacao_id WHERE ".$args['filtro']." AND CAST(bd.cod_elemento AS VARCHAR) LIKE SUBSTRING(CAST(r.cod_rubrica_despesa AS VARCHAR),1,2)||'%') as valor_empenhado
					     , (SELECT SUM(bd.valor_liquidado) FROM transparencia.balancete_despesa bd INNER JOIN transparencia.importacao i ON i.id = bd.importacao_id WHERE ".$args['filtro']." AND CAST(bd.cod_elemento AS VARCHAR) LIKE SUBSTRING(CAST(r.cod_rubrica_despesa AS VARCHAR),1,2)||'%') as valor_liquidado
					     , (SELECT SUM(bd.valor_pago)      FROM transparencia.balancete_despesa bd INNER JOIN transparencia.importacao i ON i.id = bd.importacao_id WHERE ".$args['filtro']." AND CAST(bd.cod_elemento AS VARCHAR) LIKE SUBSTRING(CAST(r.cod_rubrica_despesa AS VARCHAR),1,2)||'%') as valor_pago
						 , SUBSTR(CAST(r.cod_rubrica_despesa AS VARCHAR), 1, 1) AS cod_categoria
						 , SUBSTR(CAST(r.cod_rubrica_despesa AS VARCHAR), 2, 1) AS cod_natureza
                    FROM transparencia.rubrica r
                   WHERE substring(CAST(r.cod_rubrica_despesa AS VARCHAR), '([".$args['cod_categoria']."][1-9])')||'000000000000' = CAST(r.cod_rubrica_despesa AS VARCHAR)
                GROUP BY cod_rubrica_despesa, especificacao_rubrica_despesa
                ORDER BY cod_rubrica_despesa";
			
		return $query;
	}
	
	public function getBalanceteDespesaCategoriaElemento($args){
		$query = "SELECT cod_rubrica_despesa
					     , SUBSTR(CAST(r.cod_rubrica_despesa AS VARCHAR), 1, 6)||' - '||especificacao_rubrica_despesa AS descricao
					     
					     , (SELECT SUM(dotacao_inicial) FROM transparencia.balancete_despesa bd
												  INNER JOIN transparencia.configuracao_entidade c ON c.entidade_id = bd.cod_entidade
					     	    				  INNER JOIN transparencia.importacao i ON i.id = bd.importacao_id
					     							   WHERE ".$args['filtro']."
					     							     AND CAST(bd.cod_elemento AS VARCHAR) LIKE SUBSTR(CAST(r.cod_rubrica_despesa AS VARCHAR), 1, 6)||'%'
					     								 AND CAST(bd.cod_elemento AS VARCHAR) = SUBSTR(CAST(bd.cod_elemento AS VARCHAR), 1, 6)||'00000000'
														 AND bd.importacao_id = (SELECT i3.id from transparencia.importacao i3 WHERE i3.exercicio = ".$args['exercicio']." ORDER BY i3.timestamp DESC LIMIT 1)
					     ) as dotacao_inicial
					     
					     , (SELECT SUM(bd.valor_empenhado) FROM transparencia.balancete_despesa bd INNER JOIN transparencia.importacao i ON i.id = bd.importacao_id WHERE ".$args['filtro']." AND CAST(bd.cod_elemento AS VARCHAR) LIKE SUBSTRING(CAST(r.cod_rubrica_despesa AS VARCHAR),1,6)||'%') as valor_empenhado
					     , (SELECT SUM(bd.valor_liquidado) FROM transparencia.balancete_despesa bd INNER JOIN transparencia.importacao i ON i.id = bd.importacao_id WHERE ".$args['filtro']." AND CAST(bd.cod_elemento AS VARCHAR) LIKE SUBSTRING(CAST(r.cod_rubrica_despesa AS VARCHAR),1,6)||'%') as valor_liquidado
					     , (SELECT SUM(bd.valor_pago)      FROM transparencia.balancete_despesa bd INNER JOIN transparencia.importacao i ON i.id = bd.importacao_id WHERE ".$args['filtro']." AND CAST(bd.cod_elemento AS VARCHAR) LIKE SUBSTRING(CAST(r.cod_rubrica_despesa AS VARCHAR),1,6)||'%') as valor_pago
						 , SUBSTR(CAST(r.cod_rubrica_despesa AS VARCHAR), 1, 1) AS cod_categoria
						 , SUBSTR(CAST(r.cod_rubrica_despesa AS VARCHAR), 2, 1) AS cod_natureza
						 , SUBSTR(CAST(r.cod_rubrica_despesa AS VARCHAR), 3, 4) AS cod_elemento
                    FROM transparencia.rubrica r
                   WHERE substring(CAST(r.cod_rubrica_despesa AS VARCHAR), '([".$args['cod_categoria']."][".$args['cod_natureza']."][1-9][0-9][0-9][0-9])')||'00000000' = CAST(r.cod_rubrica_despesa AS VARCHAR)
                GROUP BY cod_rubrica_despesa, especificacao_rubrica_despesa
                ORDER BY cod_rubrica_despesa";
			
		return $query;
	}
	
	public function getCategoria($args){
		$query = new Doctrine_Query();
		
		$query->select("r.especificacao_rubrica_despesa")
              ->from("transparencia.rubrica r")
              ->where("CAST(r.cod_rubrica_despesa AS VARCHAR) LIKE '".$args['cod_categoria']."00000000000%'")
			  ->groupBy("r.especificacao_rubrica_despesa");
		
		$statement = Doctrine_Manager::getInstance()->connection();
		$results = $statement->execute($query);
		
		return $results->fetch();
	}
	
	public function getNatureza($args){
		$query = new Doctrine_Query();
		
		$query->select("r.especificacao_rubrica_despesa")
              ->from("transparencia.rubrica r")
              ->where("CAST(r.cod_rubrica_despesa AS VARCHAR) LIKE '".$args['cod_categoria'].$args['cod_natureza']."0000000000%'")
			  ->groupBy("r.especificacao_rubrica_despesa");
		
		$statement = Doctrine_Manager::getInstance()->connection();
		$results = $statement->execute($query);
		
		return $results->fetch();
	}	

	public function getBalanceteDespesaCategoriaTotal($args){
		switch($_REQUEST['nivel']){
			case 'categoria':
				$tbl = BalanceteDespesaBO::getBalanceteDespesaCategoriaEconomica($args);
			break;

			case 'natureza':
				$tbl = BalanceteDespesaBO::getBalanceteDespesaCategoriaNatureza($args);
			break;
			
			case 'elemento':
				$tbl = BalanceteDespesaBO::getBalanceteDespesaCategoriaElemento($args);
			break;			
		}
		
		$query = "SELECT 
					SUM(dotacao_inicial) as dotacao_inicial, 
					SUM(valor_empenhado) as valor_empenhado, 
					SUM(valor_liquidado) as valor_liquidado, 
					SUM(valor_pago) as valor_pago
	    		  FROM  (".$tbl.") as tbl ";
		
		$statement = Doctrine_Manager::getInstance()->connection();
		$results = $statement->execute($query);
		
		return $results->fetchAll();
	}		
	
	public function getBalanceteDespesaRecurso($exercicio=null, $codEntidade=null){
		
		$exercicio = ($exercicio == null) ? ImportacaoBO::getUltimo()->getExercicio() : $exercicio;
		
        $query = new Doctrine_Query();
        $query->select("r.cod_recurso")
			  ->addSelect("r.cod_recurso as cod")
			  ->addSelect("r.nome_recurso as descricao")
			  ->addSelect("SUM(bd.valor_pago) as valor_pago")
			  ->addSelect("SUM(bd.valor_empenhado) as valor_empenhado")
			  ->addSelect("SUM(bd.valor_liquidado) as valor_liquidado")
 		      ->addSelect("(SELECT SUM(bd3.dotacao_inicial) FROM BalanceteDespesa bd3 INNER JOIN bd3.ConfiguracaoEntidade c3 WHERE bd3.cod_recurso = r.cod_recurso AND CAST(bd3.cod_elemento AS VARCHAR) = SUBSTR(CAST(bd3.cod_elemento AS VARCHAR), 1, 6)||'00000000' AND bd3.importacao_id = (SELECT i3.id from Importacao i3 WHERE i3.exercicio = ".$exercicio." ORDER BY i3.timestamp DESC LIMIT 1) ) as dotacao_inicial")
              
			  ->from('Recurso r')
			  ->innerJoin('r.Importacao i')
			  ->innerJoin('r.BalanceteDespesa bd WITH bd.importacao_id = i.id')
			  ->innerJoin('bd.ConfiguracaoEntidade c')
			  ->where('r.importacao_id = (SELECT i2.id from Importacao i2 WHERE i2.exercicio = ? ORDER BY i2.timestamp DESC LIMIT 1)', $exercicio)
			  ->groupBy('r.id')
			  ->addGroupBy('r.cod_recurso')
			  ->addGroupBy('r.nome_recurso')
			  ->orderBy('r.nome_recurso');

        return $query;
	}
    
	public function getBalanceteDespesaCredor($exercicio=null, $codEntidade=null){
		
		$exercicio = ($exercicio == null) ? ImportacaoBO::getUltimo()->getExercicio() : $exercicio;
		
        $query = new Doctrine_Query();
        $query->select("c.id,
                        c.cod_credor,
                        c.cod_credor as cod,
                        c.nome_credor as descricao,
                        c.cnpj_cpf_credor,
                       (SELECT COALESCE(SUM(e1.valor_empenho),0)      FROM Empenho e1 INNER JOIN e1.ConfiguracaoEntidade c1 WHERE e1.cod_credor = c.cod_credor AND e1.sinal_valor = '+' AND EXTRACT(YEAR FROM e1.data_empenho) = '".$exercicio."' ".($codEntidade != null ? " AND e1.cod_entidade = $codEntidade":"")." ) AS valor_empenhado,
                       (SELECT COALESCE(SUM(e2.valor_empenho),0)      FROM Empenho e2 INNER JOIN e2.ConfiguracaoEntidade c2 WHERE e2.cod_credor = c.cod_credor AND e2.sinal_valor = '-' AND EXTRACT(YEAR FROM e2.data_empenho) = '".$exercicio."' ".($codEntidade != null ? " AND e2.cod_entidade = $codEntidade":"")." ) AS estornado,
                       ((SELECT COALESCE(SUM(l1.valor_liquidacao), 0) FROM Empenho e3 INNER JOIN e3.ConfiguracaoEntidade c3 INNER JOIN e3.Liquidacao l1 ON l1.cod_empenho = e3.numero_empenho WHERE e3.cod_credor = c.cod_credor AND l1.sinal_valor = '+' AND EXTRACT(YEAR FROM e3.data_empenho) = '".$exercicio."' ".($codEntidade != null ? " AND e3.cod_entidade = $codEntidade":"").") -
                        (SELECT COALESCE(SUM(l2.valor_liquidacao), 0) FROM Empenho e4 INNER JOIN e4.ConfiguracaoEntidade c4 INNER JOIN e4.Liquidacao l2 ON l2.cod_empenho = e4.numero_empenho WHERE e4.cod_credor = c.cod_credor AND l2.sinal_valor = '-' AND EXTRACT(YEAR FROM e4.data_empenho) = '".$exercicio."' ".($codEntidade != null ? " AND e4.cod_entidade = $codEntidade":"").")) AS valor_liquidado,
                       ((SELECT COALESCE(SUM(p1.valor_pagamento), 0)  FROM Empenho e5 INNER JOIN e5.ConfiguracaoEntidade c5 INNER JOIN e5.Pagamento p1 ON p1.cod_empenho  = e5.numero_empenho WHERE e5.cod_credor = c.cod_credor AND p1.sinal_valor = '+' AND EXTRACT(YEAR FROM e5.data_empenho) = '".$exercicio."' ".($codEntidade != null ? " AND e5.cod_entidade = $codEntidade":"").") -
                        (SELECT COALESCE(SUM(p2.valor_pagamento), 0)  FROM Empenho e6 INNER JOIN e6.ConfiguracaoEntidade c6 INNER JOIN e6.Pagamento p2 ON p2.cod_empenho  = e6.numero_empenho WHERE e6.cod_credor = c.cod_credor AND p2.sinal_valor = '-' AND EXTRACT(YEAR FROM e6.data_empenho) = '".$exercicio."' ".($codEntidade != null ? " AND e6.cod_entidade = $codEntidade":"").")) AS valor_pago")
              ->from('Credor c')
              ->innerJoin('c.Importacao i')
			  ->where('c.importacao_id = (SELECT i2.id from Importacao i2 WHERE i2.exercicio = ? ORDER BY i2.timestamp DESC LIMIT 1)', $exercicio)
			  ->groupBy("c.id, c.cod_credor, c.nome_credor, c.cnpj_cpf_credor")
              ->having("(SELECT COALESCE(SUM(e.valor_empenho), 0)
						   FROM Empenho e
					 INNER JOIN e.ConfiguracaoEntidade c7
					      WHERE (e.cod_credor = c.cod_credor AND e.sinal_valor = '+' AND EXTRACT(YEAR FROM e.data_empenho) = '".$exercicio."' ".($codEntidade != null ? " AND e.cod_entidade = $codEntidade":"").")) > 0")
			  ->orderBy('c.nome_credor');

        return $query;
	}

	public function getBalanceteDespesaTotalByExercicio($exercicio=null, $codEntidade=null){

		$exercicio = ($exercicio == null) ? ImportacaoBO::getUltimo()->getExercicio() : $exercicio;

		$filtro = " AND i2.exercicio = $exercicio ";
		if ($codEntidade != null){
			$filtro .= " AND bd2.cod_entidade = $codEntidade ";
		}
		
        $query = new Doctrine_Query();
        $query->select("SUM(bd.valor_pago) as valor_pago")
			  ->addSelect("SUM(bd.valor_empenhado) as valor_empenhado")
			  ->addSelect("SUM(bd.valor_liquidado) as valor_liquidado")
			  ->addSelect("(SELECT SUM(bd2.dotacao_inicial) FROM BalanceteDespesa bd2 INNER JOIN bd2.ConfiguracaoEntidade c2 INNER JOIN bd2.Importacao i2 WHERE CAST(bd2.cod_elemento AS VARCHAR) = SUBSTR(CAST(bd2.cod_elemento AS VARCHAR), 1, 6)||'00000000' $filtro) AS dotacao_inicial ")
			  ->from('BalanceteDespesa bd')
			  ->innerJoin('bd.ConfiguracaoEntidade c')
			  ->innerJoin('bd.Importacao i')
			  ->where('i.exercicio = ?', $exercicio);

		if ($codEntidade != null){
            $query->andWhere('bd.cod_entidade = ?', $codEntidade);
        }
		
        return $query->execute();
	}
	
	public function customFilter(Search $search, $method = '', $request=array()){
		$args = array();

		$exercicio = null;
	    if(isset($request['exercicio'])){
			$args['exercicio'] = $request['exercicio'];
            $args['filtro'][]  = "i.exercicio = ".$request['exercicio'];
        } else {
			//Seta com último exercício disponível na tabela de importações
			$exercicio = is_null($exercicio) ? ImportacaoBO::getUltimo()->getExercicio() : $exercicio;
			
			$args['exercicio'] = $exercicio;
            $args['filtro'][]  = "i.exercicio = ".$exercicio;
        }
        
        if(isset($request['cod_entidade'])){
            $args['filtro'][]     = "bd.cod_entidade = ".$request['cod_entidade'];
		    $args['cod_entidade'] = $request['cod_entidade'];
		} else {
			$args['cod_entidade'] = null;
		}
		
		if(count($args['filtro']) > 0){
			$args['filtro'] = implode(" AND ", $args['filtro']);
		}
		
		if(isset($request['cod_categoria'])){
			$args['cod_categoria'] = $request['cod_categoria'];
		}
		
		if(isset($request['cod_natureza'])){
			$args['cod_natureza'] = $request['cod_natureza'];
		}
		
		Sessao::set('args', $args);
		$query = BalanceteDespesaBO::$method($args);
		
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
		$customPager->setNumPages(ceil($rowCount / $search->getMax()));
		$customPager->setMaxPerPage($search->getMax());
		$customPager->setNumResults($rowCount);
		$search->setPager($customPager);
		
		$balanceteDespesaDTO = new DTO();
		$balanceteDespesaDTO->setObj($results->fetchAll());
		$balanceteDespesaDTO->setSearch($search);

		return $balanceteDespesaDTO;
	}		

	public function filter(Search $search, $method = '', $request=array()){
		
		$exercicio = ($request['exercicio'] == null) ? ImportacaoBO::getUltimo()->getExercicio() : $request['exercicio'];
		
        if(isset($request['cod_entidade'])){
            $codEntidade = $request['cod_entidade'];
        } else {
            $codEntidade = null;
        }        
        
        if($request['secao'] == 'credor'){
            $query = BalanceteDespesaBO::$method($exercicio, $codEntidade);
        } else {
            $query = BalanceteDespesaBO::$method($exercicio);
            
            if($codEntidade != null){
                $query->andWhere('bd.cod_entidade = ?', $request['cod_entidade']);
			}
        }        
        
        $query->andWhere('i.exercicio = ?', $exercicio);
		
		$pager = new Doctrine_Pager($query, $search->getPage(), $search->getMax());
		$balanceteDespesa = $pager->execute();
		
		$search->setPager($pager);
		
		$balanceteDespesaDTO = new DTO();
		$balanceteDespesaDTO->setObj($balanceteDespesa);
		$balanceteDespesaDTO->setSearch($search);

		return $balanceteDespesaDTO;
	}
	
	public function geraRelatorio($request, $method){
		$args = array();
		
	    if(isset($request['exercicio'])){
			$args['exercicio'] = $request['exercicio'];
            $args['filtro'][]  = "i.exercicio = ".$request['exercicio'];
        } else {
			//Seta com último exercício disponível na tabela de importações
			$exercicio = ($exercicio == null) ? ImportacaoBO::getUltimo()->getExercicio() : $exercicio;
			
			$args['exercicio'] = $exercicio;
            $args['filtro'][]  = "i.exercicio = ".$exercicio;
        }
        
        if(isset($request['cod_entidade'])){
            $args['filtro'][]     = "bd.cod_entidade = ".$request['cod_entidade'];
		    $args['cod_entidade'] = $request['cod_entidade'];
		} else {
			$args['cod_entidade'] = null;
		}
		
		if(count($args['filtro']) > 0){
			$args['filtro'] = implode(" AND ", $args['filtro']);
		}
		
		if(isset($request['cod_categoria'])){
			$args['cod_categoria'] = $request['cod_categoria'];
		}
		
		if(isset($request['cod_natureza'])){
			$args['cod_natureza'] = $request['cod_natureza'];
		}
		
		Sessao::set('args', $args);
		$query = BalanceteDespesaBO::$method($args);
		
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
            $balanceteDespesa = new BalanceteDespesa();
            $balanceteDespesa->setImportacaoId($importacaoId);
			$balanceteDespesa->setCodEntidade(mb_substr($line,0,2));
			$balanceteDespesa->setCodOrgao(mb_substr($line,2,5));
			$balanceteDespesa->setCodUnidade(mb_substr($line,7,5));
			$balanceteDespesa->setCodFuncao(mb_substr($line,12,2));
			$balanceteDespesa->setCodSubfuncao(mb_substr($line,14,3));
			$balanceteDespesa->setCodPrograma(mb_substr($line,17,4));
			$balanceteDespesa->setCodProjeto(mb_substr($line,21,5));
			$balanceteDespesa->setCodElemento(mb_substr($line,26,14));
			$balanceteDespesa->setCodRecurso(mb_substr($line,40,4));
			$balanceteDespesa->setDotacaoInicial(formatNumberPgSQL(mb_substr($line,44,13)));
			$balanceteDespesa->setAtualizacaoMonetaria(formatNumberPgSQL(mb_substr($line,57,13)));
			$balanceteDespesa->setCreditosSuplementares(formatNumberPgSQL(mb_substr($line,70,13)));
			$balanceteDespesa->setCreditosEspeciais(formatNumberPgSQL(mb_substr($line,83,13)));
			$balanceteDespesa->setCreditosExtraordinarios(formatNumberPgSQL(mb_substr($line,96,13)));
			$balanceteDespesa->setReducaoDotacoes(formatNumberPgSQL(mb_substr($line,109,13)));
			$balanceteDespesa->setSuplementacaoRecurso(formatNumberPgSQL(mb_substr($line,122,13)));
			$balanceteDespesa->setReducaoRecurso(formatNumberPgSQL(mb_substr($line,135,13)));
			$balanceteDespesa->setValorEmpenhado(formatNumberPgSQL(mb_substr($line,148,13)));
			$balanceteDespesa->setValorLiquidado(formatNumberPgSQL(mb_substr($line,161,13)));
			$balanceteDespesa->setValorPago(formatNumberPgSQL(mb_substr($line,174,13)));

            BalanceteDespesaBO::create($balanceteDespesa, $conn);
        }
        
        fclose($data);
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('BalanceteDespesa b');
        $query->where('b.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }    
		
	public function validate(BalanceteDespesa $balanceteDespesa){}
}
