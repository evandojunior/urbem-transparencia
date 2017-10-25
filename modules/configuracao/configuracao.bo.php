<?php

class ConfiguracaoBO{

	public function create(Configuracao $configuracao, $conn=null){
		try{
			$configuracao->setCreated(date('Y-m-d H:i:s'));
			ConfiguracaoBO::validate($configuracao);
			$configuracao->save($conn);
			
			return $configuracao;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function update(Configuracao $configuracao, $conn=null){
		try{
			$configuracao->setUpdated(date('Y-m-d H:i:s'));
			ConfiguracaoBO::validate($configuracao);
			$configuracao->save($conn);

			return $configuracao;

		} catch(Exception $e){
			throw $e;
		}
	}

	public function delete($configuracaoId, $conn=null){
		return $configuracao = Doctrine::getTable('Configuracao')->find($configuracaoId)->delete();
	}
	
	public function get($configuracaoId){
        return Doctrine::getTable('Configuracao')->find($configuracaoId);
	}
	
	public function getByAlias($alias){
        return Doctrine::getTable('Configuracao')->findOneBy('alias', $alias);
	}	
	
    public function filter(Search $search){
        $fields = array(
            'id'        => 'c.id',
        	'modulo'    => 'm.modulo',
			'municipio' => 'm2.nome',
            'alias'     => 'c.alias',
            'parametro' => 'c.parametro',
        	'valor'     => 'c.valor',
        	'created'   => 'c.created',
        	'updated'   => 'c.updated',
        );
        
        $query = new Doctrine_Query();
        $query->select('c.*, m.modulo')
              ->from('Configuracao c')
              ->innerJoin('c.Modulo m')
			  ->leftJoin('c.Municipio m2');

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
        $configuracao = $pager->execute();
        
        $search->setPager($pager);
        
        $configuracaoDTO = new DTO();
        $configuracaoDTO->setObj($configuracao);
        $configuracaoDTO->setSearch($search);

        return $configuracaoDTO;
    }
    	

	public function validate(Configuracao $configuracao){
		ConfiguracaoBO::validateAlias($configuracao);
		ConfiguracaoBO::validateParametro($configuracao);
	}

	public function validateParametro(Configuracao $configuracao){
		if($configuracao->getParametro() != ''){
			if(Validator::validateUnique('Configuracao', 'alias', $configuracao->getValor(), $configuracao->getId())){
				return true;
			} else {
				throw new Exception('Já existe um paramtro cadastrado com este valor');
			}
		} else {
			throw new Exception('O parametro deve ser preenchido');
		}
	}
	
	public function validateAlias(Configuracao $configuracao){
		if($configuracao->getAlias() != ''){
			return true;
		} else {
			throw new Exception('O alias deve ser preenchido');
		}
	}	
}
