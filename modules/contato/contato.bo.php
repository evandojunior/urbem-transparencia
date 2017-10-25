<?php

class ContatoBO{

	public function create(Contato $contato, $conn=null){
		try{
			$contato->setStatus('nl');
			$contato->setCreated(date('Y-m-d H:i:s'));
			ContatoBO::validate($contato);
			
			$mensagem = "Contato - Transparência<br /><br />";
			$mensagem.= "<b>Nome</b><br />";
			$mensagem.= $contato->getNome()."<br />";
			$mensagem.= "<b>Telefone</b><br />";
			$mensagem.= $contato->getDDD().$contato->getTelefone()."<br />";
			$mensagem.= "<b>E-mail</b><br />";
			$mensagem.= $contato->getEmail()."<br />";
			$mensagem.= "<b>Mensagem</b><br />";
			$mensagem.= $contato->getMensagem()."<br />";

//			$configuracao = ConfiguracaoBO::get($contato->getConfiguracaoId());
		
			$email = new Email();
//			$email->setDestinatario($configuracao->getValor());
			$email->setDestinatario(MAIL_ADMINISTRATOR);
			$email->setAssunto("Contato - Transparência");
			$email->setMensagem($mensagem);
			$email->enviar($contato);
			
//			$contato->save($conn);
			
			return $contato;

		} catch(Exception $e){
			throw $e;
		}
	}
	
	public function update(Contato $contato, $conn=null){
		try{
			$contato->setUpdated(date('Y-m-d H:i:s'));
			$this->validate($contato);
			$contato->save($conn);

			return $contato;

		} catch(Exception $e){
			throw $e;
		}
	}	
	
	public function get($contatoId){
        return Doctrine::getTable('Contato')->find($contatoId);
	}
	
	public function filter(Search $search){
		$fields = array(
			'id'   	       => 'c.id',
			'assunto'      => 'c.assunto',
			'nome'         => 'c.nome',
			'email'		   => 'c.email',
			'created'      => 'c.created',
			'updated'      => 'c.updated',
		);
		
		$query = new Doctrine_Query();
        $query->select('c.*')
              ->from('Contato c');

        if($search->getFilter() != null){
			$query->andWhere($fields[$search->getFilter()].' LIKE ?', '%'.$search->getQ().'%');
		}
		
		if($search->getOrder() != null){
			$order = $fields[$search->getOrder()];
				
			if($search->getDirection() != null){
                $order.= ' '.$search->getDirection();
			}

            $query->orderBy($order);
		}

		$pager = new Doctrine_Pager($query, $search->getPage(), $search->getMax());
		$contato = $pager->execute();
		
		$search->setPager($pager);
		
		$contatoDTO = new DTO();
		$contatoDTO->setObj($contato);
		$contatoDTO->setSearch($search);

		return $contatoDTO;
	}

	public function validate(Contato $contato){}
}
