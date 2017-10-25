<?php

class CargoBO{

	public function create(Cargo $cargo, $conn=null){
    	CargoBO::validate($cargo);
    	$cargo->save($conn);
		$cargo->free();

    	return $cargo;
	}

	public function get($cargoId){
        return Doctrine::getTable('Cargo')->find($cargoId);
	}
	
	public function getCargo(){
        $query = new Doctrine_Query();
        $query->select("c.*, TO_CHAR(vigencia,'DD/MM/YYYY') as vigencia")
              ->from('Cargo c');

        return $query;
	}

	public function filter(Search $search, $method = '', $request=array()) {
		$query = CargoBO::$method();
		
		if(isset($request['competencia'])){
			$query->where("c.mes_ano like '".$request['competencia']."'");
		}
		
		$query->orderBy("c.importacao_id DESC, c.mes_ano DESC, c.codigo ASC");

		$pager = new Doctrine_Pager($query, $search->getPage(), $search->getMax());
		$cargo = $pager->execute();
		
		$search->setPager($pager);
		
		$cargoDTO = new DTO();
		$cargoDTO->setObj($cargo);
		$cargoDTO->setSearch($search);

		return $cargoDTO;
	}

	public function geraRelatorio($request, $method){
		$query = CargoBO::$method();
		
		if(isset($request['competencia'])){
			$query->where("c.mes_ano like '".$request['competencia']."'");
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
            $cargo = new Cargo();
            $cargo->setImportacaoId($importacaoId);
			$cargo->setCodEntidade(mb_substr($line,0,2));
		    $cargo->setMesAno(mb_substr($line,2,7));
		    $cargo->setCodigo(mb_substr($line,9,10));
		    $cargo->setDescricaoCargo(mb_substr($line,19,60));
		    $cargo->setTipoCargo(mb_substr($line,79,20));
		    $cargo->setLei(mb_substr($line,99,10));
		    $cargo->setDescricaoPadrao(mb_substr($line,109,60));
		    $cargo->setCargaHorariaMensal(formatNumberPgSQL(mb_substr($line,169,7)));
		    $cargo->setCargaHorariaSemanal(formatNumberPgSQL(mb_substr($line,176,7)));
		    $cargo->setValor(formatNumberPgSQL(mb_substr($line,183,13)));
            
            $date = trim(mb_substr($line,196,8));
            if($date != ''){
                $cargo->setVigencia((mb_substr($date, 4, 4).'-'.mb_substr($date, 2, 2).'-'.mb_substr($date, 0, 2)));
            }
            
		    $cargo->setRegimeSubdivisao(mb_substr($line,204,80));
		    $cargo->setVagasCriadas(mb_substr($line,284,6));
		    $cargo->setVagasOcupadas(mb_substr($line,290,6));
		    $cargo->setVagasDisponiveis(mb_substr($line,296,6));
			
		    CargoBO::create($cargo, $conn);
		}
        
        fclose($data);
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Cargo c');
        $query->where('c.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }    
		
	public function validate(Cargo $cargo){}
}