<?php

class EmpenhoBO{

	public function create(Empenho $empenho, $conn=null){
    	EmpenhoBO::validate($empenho);
    	$empenho->save($conn);
		$empenho->free();

    	return $empenho;
	}
    
	public function getHistorico($codEmpenho){
        $sql = "SELECT 'Empenho' as tipo,
                        e.valor_empenho as valor,
                        e.data_empenho as data,
                        e.historico_empenho as historico,
                        e.sinal_valor
                  FROM Empenho e
                 WHERE e.numero_empenho = ".$codEmpenho."
              ORDER BY e.data_empenho ASC, e.sinal_valor DESC";
                
        $sql1 = "SELECT 'Liquidação' as tipo,
                        l.valor_liquidacao as valor,
                        l.data_liquidacao as data,
                        l.historico_liquidacao as historico,
                        l.sinal_valor
                  FROM Liquidacao l
                 WHERE l.cod_empenho = ".$codEmpenho."
              ORDER BY l.data_liquidacao ASC, l.sinal_valor DESC";
        
                
        $sql2 = "SELECT 'Pagamento' as tipo,
                        p.valor_pagamento as valor,
                        p.data_pagamento as data,
                        p.historico_pagamento as historico,
                        p.sinal_valor
                  FROM Pagamento p
                 WHERE p.cod_empenho = ".$codEmpenho."
              ORDER BY p.data_pagamento ASC, p.sinal_valor DESC";

        $resultArray[] = Doctrine_Query::create()->query($sql);
        $resultArray[] = Doctrine_Query::create()->query($sql1);
        $resultArray[] = Doctrine_Query::create()->query($sql2);
        
        foreach($resultArray as $result) {
            foreach($result as $registro) {    
                $resultFormatado[] = $registro;
            }
        }
        
        return $resultFormatado;
    }

	public function getEmpenho(){
        $query = new Doctrine_Query();
        $query->select("em.id,
					    em.numero_empenho,
						em.data_empenho,
						em.contrapartida_recurso,
						em.numero_empenho,
						em.data_empenho,
						em.valor_empenho,
						em.sinal_valor,
						em.cod_credor,
						em.historico_empenho,
						em.modalidade_licitacao,
						em.numero_licitacao,
						em.ano_licitacao,
                        c.nome_credor,
                        e.nome_entidade,
                        o.nome_orgao,
                        u.nome_unidade,
                        f.nome_funcao,
                        sf.nome_subfuncao,
                        p.nome_programa,
                        a.nome_projeto,
						r.especificacao_rubrica_despesa,
						(SELECT r2.especificacao_rubrica_despesa FROM Rubrica r2 WHERE CAST(r2.cod_rubrica_despesa AS VARCHAR) LIKE SUBSTR(CAST(r.cod_rubrica_despesa AS VARCHAR), 1, 1)||'000000000000%' AND r2.exercicio = i.exercicio LIMIT 1) AS categoria,
						(SELECT r3.especificacao_rubrica_despesa FROM Rubrica r3 WHERE CAST(r3.cod_rubrica_despesa AS VARCHAR) LIKE SUBSTR(CAST(r.cod_rubrica_despesa AS VARCHAR), 1, 2)||'00000000000%' AND r3.exercicio = i.exercicio LIMIT 1) AS natureza,
						r.id, r.especificacao_rubrica_despesa,
                        re.id, re.nome_recurso,
                       (SELECT SUM(valor_empenho)     FROM Empenho    em2 WHERE em2.numero_empenho = em.numero_empenho AND em2.sinal_valor = '-') AS estornado,
                      ((SELECT COALESCE(SUM(valor_liquidacao), 0) FROM Liquidacao l1  WHERE l1.cod_empenho     = em.numero_empenho AND l1.sinal_valor = '+') -
                       (SELECT COALESCE(SUM(valor_liquidacao), 0) FROM Liquidacao l2  WHERE l2.cod_empenho     = em.numero_empenho AND l2.sinal_valor = '-')) AS liquidado,
                      ((SELECT COALESCE(SUM(valor_pagamento), 0) FROM Pagamento  p1  WHERE p1.cod_empenho     = em.numero_empenho AND p1.sinal_valor = '+') -
                        (SELECT COALESCE(SUM(valor_pagamento), 0) FROM Pagamento  p2  WHERE p2.cod_empenho     = em.numero_empenho AND p2.sinal_valor = '-')) AS pago ")
              ->from('Empenho em')
              ->innerJoin('em.Importacao i')
		      ->innerJoin('em.Entidade e ON e.cod_entidade = em.cod_entidade AND e.importacao_id = i.id')
			  ->innerJoin('em.ConfiguracaoEntidade c2')
		      ->innerJoin('em.Orgao o ON o.cod_orgao = em.cod_orgao AND o.exercicio = i.exercicio AND o.importacao_id = i.id')
		      ->innerJoin('em.Unidade u ON u.cod_unidade = em.cod_unidade AND u.cod_orgao = o.cod_orgao AND u.exercicio = i.exercicio AND u.importacao_id = i.id')
		      ->innerJoin('em.Funcao f ON f.cod_funcao = em.cod_funcao AND f.exercicio = i.exercicio AND f.importacao_id = i.id')
		      ->innerJoin('em.Subfuncao sf ON sf.cod_subfuncao = em.cod_subfuncao AND sf.exercicio = i.exercicio AND sf.importacao_id = i.id')
		      ->innerJoin('em.Programa p ON p.cod_programa = em.cod_programa AND p.exercicio = i.exercicio AND p.importacao_id = i.id')
		      ->innerJoin('em.Acao a ON a.cod_projeto = em.cod_projeto AND a.exercicio = i.exercicio AND a.importacao_id = i.id')
		      ->innerJoin('em.Rubrica r ON r.cod_rubrica_despesa = em.cod_rubrica AND r.exercicio = i.exercicio AND r.importacao_id = i.id')
		      ->innerJoin('em.Recurso re ON re.cod_recurso = em.cod_recurso AND re.importacao_id = i.id')
              ->leftJoin('em.Credor c ON c.cod_credor = em.cod_credor AND c.importacao_id = i.id')
			  ->groupBy('em.id, em.numero_empenho, em.data_empenho, em.contrapartida_recurso, em.numero_empenho, em.data_empenho, em.valor_empenho, em.sinal_valor, em.cod_credor, em.historico_empenho, em.modalidade_licitacao, em.numero_licitacao, em.ano_licitacao,
                         c.id,  c.nome_credor,
                         e.id,  e.nome_entidade,
                         o.id,  o.nome_orgao,
                         u.id,  u.nome_unidade,
                         f.id,  f.nome_funcao,
                         sf.id, sf.nome_subfuncao,
                         p.id,  p.nome_programa,
                         a.id,  a.nome_projeto,
                         r.id,  r.especificacao_rubrica_despesa, r.cod_rubrica_despesa,
                         re.id, re.nome_recurso,
						 i.exercicio')
			 ->orderBy('c.nome_credor, em.data_empenho');

            return $query;
	}
	
	public function filter(Search $search, $method = '', $request){
	
		$query = EmpenhoBO::$method();

        $exercicio = null;
		if(isset($request['exercicio_entidade'])){
			$exercicio = $request['exercicio_entidade'];
		} else {
			//Seta com último exercício disponível na tabela de importações
			$exercicio = is_null($exercicio) ? ImportacaoBO::getUltimo()->getExercicio() : $exercicio;
		}
        
        switch($request['secao']){
            case 'orgao':
                $query->andWhere('em.cod_orgao = ?', $request['id']);
            break;
        
            case 'funcao':
                $query->andWhere('em.cod_funcao = ?', $request['id']);
            break;
        
            case 'programa':
                $query->andWhere('em.cod_programa = ?', $request['id']);
            break;
        
            case 'projeto':
                $query->andWhere('em.cod_projeto = ?', $request['id']);
            break;
        
            case 'categoria':
                if(!isset($request['elemento'])){
                    $query->andWhere('em.cod_rubrica = ?', $request['id']);
                } else {
                    $query->andWhere("CAST(em.cod_rubrica AS VARCHAR) LIKE '".$request['cod_elemento']."%' ");
                }
                
                if(isset($request['cod_entidade'])){
                    $query->andWhere('em.cod_entidade = ?', $request['cod_entidade']);
                }
            break;
            
            case 'recurso':
                $query->andWhere('em.cod_recurso = ?', $request['id']);
            break;
        
            case 'credor':
                $query->andWhere('em.cod_credor = ?', $request['id']);
            break;
        
            case 'compra':
				$query->innerJoin("em.Compra co ON (co.exercicio_empenho||lpad(CAST(co.cod_entidade AS VARCHAR), 2, '0')||lpad(CAST(co.cod_empenho AS VARCHAR), 7, '0') = CAST(em.numero_empenho AS VARCHAR)) AND co.importacao_id = i.id");

                $query->andWhere("co.cod_entidade = ?", $request['cod_entidade']);
                $query->andWhere("co.exercicio_entidade = ?", $exercicio);
                $query->andWhere("TRIM(co.modalidade) = '".utf8_decode($request['modalidade'])."'");
                $query->andWhere("co.cod_compra_direta = ?", $request['cod']);
            break;
        
            case 'licitacao':
				$query->innerJoin("em.Licitacao li ON (li.exercicio_empenho||lpad(CAST(li.cod_entidade AS VARCHAR), 2, '0')||lpad(CAST(li.cod_empenho AS VARCHAR), 7, '0') = CAST(em.numero_empenho AS VARCHAR)) AND li.importacao_id = i.id");
                
                $query->andWhere("li.cod_entidade = ?", $request['cod_entidade']);
                $query->andWhere("li.exercicio_entidade = ?", $exercicio);
                $query->andWhere("TRIM(li.modalidade) = '".utf8_decode($request['modalidade'])."'");
                $query->andWhere("li.cod_licitacao = ?", $request['cod']);
            break;
        }
        
        $query->andWhere("em.sinal_valor = '+'")
		      ->andWhere('EXTRACT(YEAR FROM em.data_empenho) = ?', $exercicio)
			  ->andWhere('o.importacao_id = (SELECT i2.id from Importacao i2 WHERE i2.exercicio = ? ORDER BY i2.timestamp DESC LIMIT 1)', $exercicio);
			  
		$pager = new Doctrine_Pager($query, $search->getPage(), $search->getMax());
		$empenho = $pager->execute();
		
		$search->setPager($pager);
		
		$empenhoDTO = new DTO();
		$empenhoDTO->setObj($empenho);
		$empenhoDTO->setSearch($search);

		return $empenhoDTO;
	}
	
	public function geraRelatorio($request, $method = ''){
	
		$query = EmpenhoBO::$method();
		
		if(isset($request['exercicio_entidade'])){
			$exercicio = $request['exercicio_entidade'];
		} else {
			//Seta com último exercício disponível na tabela de importações
            $exercicio = ($exercicio == null) ? ImportacaoBO::getUltimo()->getExercicio() : $exercicio;
		}
        
        switch($request['secao']){
            case 'empenho':
                $query->andWhere("em.numero_empenho = '".$request['cod_empenho']."'");
            break;
			
            case 'orgao':
                $query->andWhere('em.cod_orgao = ?', $request['id']);
            break;
        
            case 'funcao':
                $query->andWhere('em.cod_funcao = ?', $request['id']);
            break;
        
            case 'programa':
                $query->andWhere('em.cod_programa = ?', $request['id']);
            break;
        
            case 'projeto':
                $query->andWhere('em.cod_projeto = ?', $request['id']);
            break;
        
            case 'categoria':
                if(!isset($request['elemento'])){
                    $query->andWhere('em.cod_rubrica = ?', $request['id']);
                } else {
                    $query->andWhere("CAST(em.cod_rubrica AS VARCHAR) LIKE '".$request['cod_elemento']."%' ");
                }
                
                if(isset($request['cod_entidade'])){
                    $query->andWhere('em.cod_entidade = ?', $request['cod_entidade']);
                }
            break;
            
            case 'recurso':
                $query->andWhere('em.cod_recurso = ?', $request['id']);
            break;
        
            case 'credor':
                $query->andWhere('em.cod_credor = ?', $request['id']);
            break;
        
            case 'compra':
				$query->innerJoin("em.Compra co ON (co.exercicio_empenho||lpad(CAST(co.cod_entidade AS VARCHAR), 2, '0')||lpad(CAST(co.cod_empenho AS VARCHAR), 7, '0') = CAST(em.numero_empenho AS VARCHAR)) AND co.importacao_id = i.id");

                $query->andWhere("co.cod_entidade = ?", $request['cod_entidade']);
                $query->andWhere("co.exercicio_entidade = ?", $exercicio);
                $query->andWhere("TRIM(co.modalidade) = '".utf8_decode($request['modalidade'])."'");
                $query->andWhere("co.cod_compra_direta = ?", $request['cod']);
            break;
        
            case 'licitacao':
				$query->innerJoin("em.Licitacao li ON (li.exercicio_empenho||lpad(CAST(li.cod_entidade AS VARCHAR), 2, '0')||lpad(CAST(li.cod_empenho AS VARCHAR), 7, '0') = CAST(em.numero_empenho AS VARCHAR)) AND li.importacao_id = i.id");
                
                $query->andWhere("li.cod_entidade = ?", $request['cod_entidade']);
                $query->andWhere("li.exercicio_entidade = ?", $exercicio);
                $query->andWhere("TRIM(li.modalidade) = '".utf8_decode($request['modalidade'])."'");
                $query->andWhere("li.cod_licitacao = ?", $request['cod']);
            break;
        }
        
        $query->andWhere("em.sinal_valor = '+'")
		      //->andWhere('EXTRACT(YEAR FROM em.data_empenho) = ?', $exercicio)
			  ->andWhere('o.importacao_id = (SELECT i2.id from Importacao i2 WHERE i2.exercicio = ? ORDER BY i2.timestamp DESC LIMIT 1)', $exercicio);
		
		$query->orderBy('c.nome_credor, em.data_empenho');
		
		return $query;
	}
	
	public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');
        
        while(!feof($data)) { 
        	$line = fgets($data,4096);
			/*
			 * Lembrar de calcular (-1) no 1° parâmetro do mb_substr, pois a documentação começa a contar os
			 * caracteres do 1 e o mb_substr começa a contagem pelo 0
			 */  
            $empenho = new Empenho();
            $empenho->setImportacaoId($importacaoId);
            $empenho->setCodEntidade(mb_substr($line,0,2));
            $empenho->setCodOrgao(mb_substr($line,2,5));
            $empenho->setCodUnidade(mb_substr($line,7,5));
            $empenho->setCodFuncao(mb_substr($line,12,2));
            $empenho->setCodSubfuncao(mb_substr($line,14,3));
            $empenho->setCodPrograma(mb_substr($line,17,4));
            $empenho->setCodSubprograma(mb_substr($line,21,3));
            $empenho->setCodProjeto(mb_substr($line,24,5));
            $empenho->setCodRubrica(mb_substr($line,29,15));
            $empenho->setCodRecurso(mb_substr($line,44,4));
            $empenho->setContrapartidaRecurso(mb_substr($line,48,4));
            $empenho->setNumeroEmpenho(mb_substr($line,52,13));
            $date = mb_substr($line,65,8);
            $empenho->setDataEmpenho((mb_substr($date, 4, 4).'-'.mb_substr($date, 2, 2).'-'.mb_substr($date, 0, 2)));
            $empenho->setValorEmpenho(formatNumberPgSQL(mb_substr($line,73,13)));
            $empenho->setSinalValor(mb_substr($line,86,1));
            $empenho->setCodCredor(mb_substr($line,87,10));
            $empenho->setHistoricoEmpenho(mb_substr($line,97,165));
            $empenho->setModalidadeLicitacao(mb_substr($line,262,30));
            $empenho->setNumeroLicitacao(mb_substr($line,292,10));
            $empenho->setAnoLicitacao(mb_substr($line,302,4));
            
		    EmpenhoBO::create($empenho, $conn);
		}
        
        fclose($data);
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Empenho e');
        $query->where('e.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }     
		
	public function validate(Empenho $empenho){}
}
