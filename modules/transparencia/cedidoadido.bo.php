<?php

class CedidoAdidoBO{

	public function create(CedidoAdido $cedidoAdido, $conn=null){
    	CedidoAdidoBO::validate($cedidoAdido);
    	$cedidoAdido->save($conn);
		$cedidoAdido->free();

    	return $cedidoAdido;
	}

	public function get($cedidoAdidoId){
        return Doctrine::getTable('CedidoAdido')->find($cedidoAdidoId);
	}

	public function getCedidoAdido(){
        $query = new Doctrine_Query();
        $query->select('ca.*')
              ->from('CedidoAdido ca');

        return $query;
	}
	
	public function filter(Search $search, $method = ''){
	
		$query = CedidoAdidoBO::$method();
		
		if(isset($request['competencia'])){
			$query->where("ca.mes_ano like '".$request['competencia']."'");
		}
		
		$query->orderBy("ca.importacao_id DESC, ca.mes_ano DESC, ca.nom_cgm ASC");
		
		$pager = new Doctrine_Pager($query, $search->getPage(), $search->getMax());
		$cedidoAdido = $pager->execute();
		
		$search->setPager($pager);
		
		$cedidoAdidoDTO = new DTO();
		$cedidoAdidoDTO->setObj($cedidoAdido);
		$cedidoAdidoDTO->setSearch($search);

		return $cedidoAdidoDTO;
	}
	
	public function geraRelatorio($request, $method){
		$query = CedidoAdidoBO::$method();
		
		if(isset($request['competencia'])){
			$query->where("ca.mes_ano like '".$request['competencia']."'");
		}
		
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
            $cedidoAdido = new CedidoAdido();
            $cedidoAdido->setImportacaoId($importacaoId);
            $cedidoAdido->setCodEntidade(mb_substr($line,0,2));
            $cedidoAdido->setMesAno(mb_substr($line,2,7));
            $cedidoAdido->setMatricula(mb_substr($line,9,8));
            $cedidoAdido->setNomCgm(mb_substr($line,17,60));
            $cedidoAdido->setSituacao(mb_substr($line,77,40));
            $cedidoAdido->setAtoCedencia(mb_substr($line,117,10));
            
            $date = trim(mb_substr($line,127,8));
            if($date != ''){
                $cedidoAdido->setDtInicial((mb_substr($date, 4, 4).'-'.mb_substr($date, 2, 2).'-'.mb_substr($date, 0, 2)));
            }
            
            $date = trim(mb_substr($line,135,8));
             if($date != ''){
                $cedidoAdido->setDtFinal((mb_substr($date, 4, 4).'-'.mb_substr($date, 2, 2).'-'.mb_substr($date, 0, 2)));
            }
            
            $cedidoAdido->setTipoCedencia(mb_substr($line,143,10));
            $cedidoAdido->setIndicativoOnus(mb_substr($line,153,20));
            $cedidoAdido->setOrgaoCedenteCessionario(mb_substr($line,173,60));
            $cedidoAdido->setNumConvenio(mb_substr($line,233,15));
            $cedidoAdido->setLocal(mb_substr($line,248,60));
			
		    CedidoAdidoBO::create($cedidoAdido, $conn);
		}
        
        fclose($data);
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('CedidoAdido c');
        $query->where('c.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }     
		
	public function validate(CedidoAdido $cedidoAdido){}
}