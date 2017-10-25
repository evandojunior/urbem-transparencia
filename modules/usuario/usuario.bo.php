<?php

class UsuarioBO{

    public function create(Usuario $usuario){
        try{
            $usuario->setStatus(1);
            $usuario->setCreated(date('Y-m-d H:i:s'));
            UsuarioBO::validate($usuario);
            $usuario->save();
            
            return $usuario;

        } catch(Exception $e){
            throw $e;
        }
    }

    public function update(Usuario $usuario){
        try{                    
            $usuario->setUpdated(date('Y-m-d H:i:s'));
            UsuarioBO::validate($usuario);
            $usuario->save();

            return $usuario;

        } catch(Exception $e){
            throw $e;
        }
    }

    public function delete($usuarioId, $conn){
        try{
			$usuario = Doctrine::getTable('Usuario')->find($usuarioId, $conn);
			$pessoa = $usuario->getPessoa();
			
			$usuario->delete($conn);
			$pessoa->delete($conn);

            return true;

        } catch(Exception $e){
            throw $e;
        }
    }
    
    public function get($usuarioId){
        $query = new Doctrine_Query();
        $query->select('u.*, p.id, p.nome, p.email, g.grupo, m.nome')
              ->from('Usuario u')
              ->innerJoin('u.Grupo g')
              ->innerJoin('u.Pessoa p')
			  ->leftJoin('u.Municipio m')
              ->where('u.id = ?', $usuarioId);
        
        return $query->fetchOne();
    }
    
    public function getByPessoa($pessoaId){
    	$query = new Doctrine_Query();
    	$query->select('u.*, p.nome, p.email, g.grupo')
    	->from('Usuario u')
    	->innerJoin('u.Grupo g')
    	->innerJoin('u.Pessoa p')
    	->where('u.pessoa_id = ?', $pessoaId);
    
    	return $query->fetchOne();
    }

    public function getByEmail($email){
    	$query = new Doctrine_Query();
    	$query->select('u.*, p.nome, p.email, g.grupo')
    	->from('Usuario u')
    	->innerJoin('u.Grupo g')
    	->innerJoin('u.Pessoa p')
    	->where('p.email = ?', $email);
    
    	return $query->fetchOne();
    }
    
    public function filter(Search $search){
        $fields = array(
            'id'    => 'u.pessoa_id',
            'nome'  => 'p.nome',
            'email' => 'p.email',
        );
        
        $query = new Doctrine_Query();
        $query->select('u.*, p.id, p.nome, p.email, g.grupo')
              ->from('Usuario u')
              ->innerJoin('u.Grupo g')
              ->innerJoin('u.Pessoa p');

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
        $usuarios = $pager->execute();
        
        $search->setPager($pager);
        
        $usuarioDTO = new DTO();
        $usuarioDTO->setObj($usuarios);
        $usuarioDTO->setSearch($search);

        return $usuarioDTO;
    }
    
    public function auth($email, $senha){
        $query = new Doctrine_Query();
        $query->select('u.*, p.id, p.nome, p.email')
              ->from('Usuario u')
              ->innerJoin('u.Grupo g')
              ->innerJoin('u.Pessoa p')
              ->where('p.email = ?', $email)
              ->andWhere('u.senha = ?', md5($senha));

        $usuario = $query->fetchOne();

        if(!$usuario instanceof Usuario){
            throw new Exception('O e-mail ou senha digitados estão incorretos');
        }
    
        if(!$usuario->getStatus()){
            throw new Exception('Este usuário está desativado');
        }
    
        return $usuario;
    }
    
    public function login($usuario){
        Sessao::clean();
        
        Sessao::set('usuario_pessoa_id', $usuario->getPessoa()->getId());
        Sessao::set('usuario_id', $usuario->getId());
		Sessao::set('usuario_grupo_id', $usuario->getGrupoId());
		Sessao::set('usuario_grupo_alias', $usuario->getGrupo()->getAlias());
		Sessao::set('usuario_municipio_id', $usuario->getMunicipioId());
        Sessao::set('usuario_nome', $usuario->getPessoa()->getNome());
        Sessao::set('usuario_email', $usuario->getPessoa()->getEmail());
		Sessao::set('municipio_db', $usuario->getMunicipio()->getDB());
		Sessao::set('municipio_hash', $usuario->getMunicipio()->getHash());
		Sessao::set('municipio_nome', $usuario->getMunicipio()->getNome());
        
        $query = new Doctrine_RawSql();
        $query->select('{acao.*}')
              ->from('acao')
              ->addFrom('INNER JOIN grupo_acao ON acao.id  = grupo_acao.acao_id')
              ->addFrom('INNER JOIN grupo      ON grupo.id = grupo_acao.grupo_id')
              ->addComponent('acao', '_Acao acao')
              ->Where('grupo_acao.grupo_id = ?', Sessao::get('usuario_grupo_id'));
 
        $acoes = $query->execute();

        Sessao::setPermissoes($acoes);
 
        return true;            
    }

    public function logout(){
        Sessao::clean();
    }

    public function editSenha($senhaAntiga, $senhaNova){
		$usuario = UsuarioBO::get(Sessao::get('usuario_pessoa_id'));
    	
    	if($usuario->getSenha() != md5($senhaAntiga)){
    		throw new Exception('A senha atual não confere. Por favor, tente novamente.');
    	}
    	
    	$usuario->setSenha($senhaNova);

        UsuarioBO::update($usuario);
    	
    	return true;
    }
    
    public function editStatus($usuarioId){
        $usuario = UsuarioBO::get($usuarioId);

        if($usuario->getStatus() == 1){
            $usuario->setStatus(0);
        } else {
            $usuario->setStatus(1);
        }
        
        UsuarioBO::update($usuario);
 
        return true;
    }
    
    public function recoverSenha($email){
        $usuario = UsuarioBO::getByEmail($email);
		
		if($usuario instanceof Usuario){
			$senha = gerarSenha();
			$usuario->setSenha($senha);
			
			UsuarioBO::update($usuario);
			
	    	$params = array(
				'nome'  => $usuario->getPessoa()->getNome(),
				'senha' => $senha,
        	);
					
	        $email = new Email();
	        $email->setTemplate('senha');
		    $email->setDestinatario($usuario->getPessoa()->getEmail());
		    $email->setAssunto('Nova senha');
		    $email->send($params);
			
			return true;			
		} else {
    		throw new Exception('E-mail não encontrado.');
    	}
    }    
    
    public function isAuth($request){
        $alias = $request['module'].".".$request['action'];
        
        if(!in_array($alias, $GLOBALS['ANONYMOUS'])){
            try{
                if(!Sessao::validate()){
                    throw new Exception('Você não pode acessar o sistema sem estar autenticado');
                } else {
                    return true;
                }

            } catch(Exception $e) {
                Message::getInstance()->error($e->getMessage());
                
                redirect('setLogin');
            }
        }
    }
    
    public function hasPerm($acao, $redirect=true){
        try{
            if(!in_array($acao, Sessao::getPermissoes())){
                throw new Exception('Você não possui permissão para acessar esta área');
            }

        } catch(Exception $e){
            Message::getInstance()->error($e->getMessage());
            
            if($redirect){
                redirect('setLogin');
            } else {
                return false;
            }
        } 
    }

    public function allowShow($acao){
        if(in_array($acao, Sessao::getPermissoes())){
            return true;
        }else{
            return false;   
        } 
    }    
    
    public function validate(Usuario $usuario){
        UsuarioBO::validateSenha($usuario);
    }

    public function validateSenha(Usuario $usuario){
        if($usuario->getSenha() != ''){
            if(strlen($usuario->getSenha()) < 6){
                throw new Exception('A senha deve conter pelo menos 6 caracteres');
            }
        } else {
            throw new Exception('A senha deve ser preenchida');
        }
    }
}
