<?php

class EntidadeBO{

	public function create(Entidade $entidade, $conn=null){
    	EntidadeBO::validate($entidade);
    	$entidade->save($conn);
		$entidade->free();

    	return $entidade;
	}

	public function get($entidadeId){
        return Doctrine::getTable('Entidade')->find($entidadeId);
	}
    
	public function getByEntidade($codEntidade){
        return Doctrine::getTable('Entidade')->findOneBy('cod_entidade', $codEntidade);
	}    

	public static function entidadeExists()
    {
        return Doctrine::getTable('Entidade')->count() > 0;
    }

    public static function configuracaoEntidadeExists()
    {
        if (self::entidadeExists() && Doctrine::getTable('ConfiguracaoEntidade')->count() == 0) {
            $q = Doctrine_Query::create()
                ->select("e.id, e.cod_entidade")
                ->from('Entidade e')
                ->groupBy("e.cod_entidade, e.id");
            $entidades = $q->fetchArray();
            $entidadesValidas = [];

            $conn = Doctrine_Manager::connection();

            foreach($entidades as $entidade) {
                if (!in_array($entidade['cod_entidade'], $entidadesValidas)) {
                    $entidadesValidas[] = $entidade['cod_entidade'];

                    $sql = sprintf("INSERT INTO transparencia.configuracao_entidade (entidade_id) VALUES (%s)", $entidade['cod_entidade']);

                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                }
            }
        }
    }

	public function filter(Search $search){
		$fields = array(
			'id' => 'e.id',
		);
		
		$query = new Doctrine_Query();
        $query->select('e.*')
              ->from('Entidade e');

        if($search->getFilter() != null){
			$query->where($fields[$search->getFilter()].' LIKE ?', '%'.$search->getQ().'%');
		}
		
		if($search->getOrder() != null){
			$order = $fields[$search->getOrder()];
				
			if($search->getDirection() != null){
                $order.= ' '.$search->getDirection();
			}

            $query->orderBy($order);
		}

		$pager = new Doctrine_Pager($query, $search->getPage(), $search->getMax());
		$entidade = $pager->execute();
		
		$search->setPager($pager);
		
		$entidadeDTO = new DTO();
		$entidadeDTO->setObj($entidade);
		$entidadeDTO->setSearch($search);

		return $entidadeDTO;
	}
    
	public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');
        
        while(!feof($data)) { 
        	$line = fgets($data,4096);
			/*
			 * Lembrar de calcular (-1) no 1° parâmetro do mb_substr, pois a documentação começa a contar os
			 * caracteres do 1 e o mb_substr começa a contagem pelo 0
			 */  
            $entidade = new Entidade();
            $entidade->setImportacaoId($importacaoId);
            $entidade->setCodEntidade(mb_substr($line,0,2));
            $entidade->setNomeEntidade(mb_substr($line,2,160));
            
		    EntidadeBO::create($entidade, $conn);
		}
        
        fclose($data);
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Entidade e');
        $query->where('e.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }     
		
	public function validate(Entidade $entidade){}
}