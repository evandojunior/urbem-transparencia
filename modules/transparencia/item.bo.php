<?php

class ItemBO{

	public function create(Item $item, $conn=null){
    	ItemBO::validate($item);
    	$item->save($conn);
		$item->free();

    	return $item;
	}

	public function get($itemId){
        return Doctrine::getTable('Item')->find($itemId);
	}
	
	public function getItemByCodEmpenho($codEmpenho){
        $query = new Doctrine_Query();
        $query->select('it.*')
              ->from('Item it')
              ->innerJoin('it.Importacao i')
			  ->where('it.numero_empenho = '.$codEmpenho)
			  ->andwhere("it.sinal_valor = '+'")
			  ->orderBy('it.numero_item ASC');
		
        return $query->execute();
	}

	public function filter(Search $search, $method = ''){
	
		$query = CompraBO::$method();
	
		$pager = new Doctrine_Pager($query, $search->getPage(), $search->getMax());
		$compra = $pager->execute();
		
		$search->setPager($pager);
		
		$compraDTO = new DTO();
		$compraDTO->setObj($compra);
		$compraDTO->setSearch($search);

		return $compraDTO;
	}
    
	public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');
        
        while(!feof($data)) { 
        	$line = fgets($data,4096);
			/*
			 * Lembrar de calcular (-1) no 1° parâmetro do mb_substr, pois a documentação começa a contar os
			 * caracteres do 1 e o mb_substr começa a contagem pelo 0
			 */  
            $item = new Item();
            $item->setImportacaoId($importacaoId);
            $item->setNumeroEmpenho(mb_substr($line,0,13));
            $item->setCodEntidade(mb_substr($line,13,2));
            $item->setExercicio(mb_substr($line,15,4));
			
            $date = trim(mb_substr($line,19,8));
            if($date != ''){			
				$item->setData((mb_substr($date, 4, 4).'-'.mb_substr($date, 2, 2).'-'.mb_substr($date, 0, 2)));
			}
			
            $item->setNumeroItem(mb_substr($line,27,8));
            $item->setDescricao(mb_substr($line,35,160));
            $item->setUnidade(mb_substr($line,195,80));
            $item->setQuantidade(mb_substr($line,275,13));
			$item->setValor(formatNumberPgSQL(mb_substr($line,288,13)));
            $item->setSinalValor(mb_substr($line,301,1));
            $item->setComplemento(mb_substr($line,302,500));

		    ItemBO::create($item, $conn);
		}
		
		fclose($data);
    }
	
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Item it');
        $query->where('it.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    } 	
		
	public function validate(Item $item){}
}