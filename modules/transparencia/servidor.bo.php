<?php

class ServidorBO{

	public function create(Servidor $servidor, $conn=null){
    	ServidorBO::validate($servidor);
    	$servidor->save($conn);
		$servidor->free();
        
    	return $servidor;
	}

	public function get($servidorId){
        return Doctrine::getTable('Servidor')->find($servidorId);
	}

	public function getServidor(){
        $query = new Doctrine_Query();
        $query->select("s.*, TO_CHAR(s.dt_rescisao, 'dd/mm/yyyy') as dt_rescisao")
              ->from('Servidor s');

        return $query;
	}
	
	public function filter(Search $search, $method = '', $request=array()){
		$query = ServidorBO::$method();
		
		if(!isset($request['nome']) && !empty($request['nome'])){
			$query->andWhere(" s.nome ILIKE '%".$request['nome']."%'");
		}
		
		if(!isset($request['situacao']) && !empty($request['situacao'])){
			$query->andWhere(" TRIM(s.situacao) ILIKE '".$request['situacao']."'");
		}
		
		if(!isset($request['competencia']) && !empty($request['situacao'])){
			$query->andWhere(" s.mes_ano ILIKE '".$request['competencia']."'");
		}
		
		$query->orderBy(" s.mes_ano DESC, s.nome ASC ");

		$pager = new Doctrine_Pager($query, $search->getPage(), $search->getMax());
		$servidor = $pager->execute();
		
		$search->setPager($pager);
		
		$servidorDTO = new DTO();
		$servidorDTO->setObj($servidor);
		$servidorDTO->setSearch($search);

		return $servidorDTO;
	}
	
	public function geraRelatorio($request, $method){
		$query = ServidorBO::$method();
		
		if(isset($request['competencia'])){
			$query->where("s.mes_ano like '".$request['competencia']."'");
		}
		
		return $query;
	}

	public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');
        
        while(!feof($data)) { 
        	$line = fgets($data,4096);

            $servidor = new Servidor();
            $servidor->setImportacaoId($importacaoId);
			$servidor->setCodEntidade(mb_substr($line, 0, 2));
			$servidor->setMesAno(mb_substr($line, 2, 7));
			$servidor->setMatricula(mb_substr($line, 9, 8));
			$servidor->setNome(mb_substr($line, 17, 60));
			$servidor->setSituacao(mb_substr($line, 77, 40));
            $date = mb_substr($line,117,8);
            $servidor->setDtAdmissao((mb_substr($date, 4, 4).'-'.mb_substr($date, 2, 2).'-'.mb_substr($date, 0, 2)));
			$servidor->setAtoNomeacao(mb_substr($line, 125, 10));
            
            $date = trim(mb_substr($line,135,8));
            if($date != ''){
                $servidor->setDtRescisao((mb_substr($date, 4, 4).'-'.mb_substr($date, 2, 2).'-'.mb_substr($date, 0, 2)));
            }
            
			$servidor->setDescricaoCausaRescisao(mb_substr($line, 143, 60));
			$servidor->setDescricaoRegimeFuncao(mb_substr($line, 203, 3));
			$servidor->setDescricaoRegimeSubdivisaoFuncao(mb_substr($line, 206, 40));
			$servidor->setDescricaoFuncao(mb_substr($line, 246, 60));
			$servidor->setDescricaoEspecialidadeFuncao(mb_substr($line, 306, 60));
			$servidor->setDescricaoPadrao(mb_substr($line, 366, 60));
			$servidor->setHorasMensais(mb_substr($line, 426, 12));
			$servidor->setLotacao(mb_substr($line, 438, 20));
			$servidor->setDescricaoLotacao(mb_substr($line, 458, 60));
			$servidor->setDescricaoLocal(mb_substr($line, 518, 60));

            ServidorBO::create($servidor, $conn);
        }
        
        fclose($data);
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Servidor s');
        $query->where('s.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }     

	public function validate(Servidor $servidor){}
}