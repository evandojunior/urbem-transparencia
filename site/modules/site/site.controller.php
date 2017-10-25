<?php
Load::_require('core/conn.class.php');
Load::_require('core/dto.class.php');
Load::_require('core/search.class.php');
Load::_require('core/email.class.php');
Load::_require('core/customPager.class.php');

Load::_require('modules/configuracao/configuracao.base.php');
Load::_require('modules/configuracao/configuracao.class.php');
Load::_require('modules/configuracao/configuracao.bo.php');

Load::_require('modules/categoria/categoria.base.php');
Load::_require('modules/categoria/categoria.class.php');
Load::_require('modules/categoria/categoria.bo.php');

Load::_require('modules/endereco/endereco.base.php');
Load::_require('modules/endereco/endereco.class.php');
Load::_require('modules/endereco/endereco.bo.php');

Load::_require('modules/pessoa/pessoa.base.php');
Load::_require('modules/pessoa/pessoa.class.php');
Load::_require('modules/pessoa/pessoa.bo.php');

Load::_require('modules/usuario/usuario.base.php');
Load::_require('modules/usuario/usuario.class.php');
Load::_require('modules/usuario/usuario.bo.php');

Load::_require('modules/grupo/grupo.base.php');
Load::_require('modules/grupo/grupo.class.php');

Load::_require('modules/grupo/acao.base.php');
Load::_require('modules/grupo/acao.class.php');

Load::_require('modules/contato/contato.base.php');
Load::_require('modules/contato/contato.class.php');
Load::_require('modules/contato/contato.bo.php');
Load::_require('modules/contato/contato.form.php');

Load::_require('modules/importacao/importacao.base.php');
Load::_require('modules/importacao/importacao.class.php');
Load::_require('modules/importacao/importacao.bo.php');

Load::_require('modules/publicacao/secao.base.php');
Load::_require('modules/publicacao/secao.class.php');
Load::_require('modules/publicacao/secao.bo.php');

Load::_require('modules/publicacao/publicacao.base.php');
Load::_require('modules/publicacao/publicacao.class.php');
Load::_require('modules/publicacao/publicacao.bo.php');

Load::_require('modules/configuracaoEntidade/configuracaoEntidade.base.php');
Load::_require('modules/configuracaoEntidade/configuracaoEntidade.class.php');
Load::_require('modules/configuracaoEntidade/configuracaoEntidade.bo.php');

Load::_require('modules/transparencia/entidade.base.php');
Load::_require('modules/transparencia/entidade.class.php');
Load::_require('modules/transparencia/entidade.bo.php');

Load::_require('modules/transparencia/empenho.base.php');
Load::_require('modules/transparencia/empenho.class.php');
Load::_require('modules/transparencia/empenho.bo.php');

Load::_require('modules/transparencia/item.base.php');
Load::_require('modules/transparencia/item.class.php');
Load::_require('modules/transparencia/item.bo.php');

Load::_require('modules/transparencia/credor.base.php');
Load::_require('modules/transparencia/credor.class.php');
Load::_require('modules/transparencia/credor.bo.php');

Load::_require('modules/transparencia/pagamento.base.php');
Load::_require('modules/transparencia/pagamento.class.php');
Load::_require('modules/transparencia/pagamento.bo.php');

Load::_require('modules/transparencia/liquidacao.base.php');
Load::_require('modules/transparencia/liquidacao.class.php');
Load::_require('modules/transparencia/liquidacao.bo.php');

Load::_require('modules/transparencia/acao.base.php');
Load::_require('modules/transparencia/acao.class.php');
Load::_require('modules/transparencia/acao.bo.php');

Load::_require('modules/transparencia/cedidoadido.base.php');
Load::_require('modules/transparencia/cedidoadido.class.php');
Load::_require('modules/transparencia/cedidoadido.bo.php');

Load::_require('modules/transparencia/orgao.base.php');
Load::_require('modules/transparencia/orgao.class.php');
Load::_require('modules/transparencia/orgao.bo.php');

Load::_require('modules/transparencia/recurso.base.php');
Load::_require('modules/transparencia/recurso.class.php');
Load::_require('modules/transparencia/recurso.bo.php');

Load::_require('modules/transparencia/remuneracao.base.php');
Load::_require('modules/transparencia/remuneracao.class.php');
Load::_require('modules/transparencia/remuneracao.bo.php');

Load::_require('modules/transparencia/rubrica.base.php');
Load::_require('modules/transparencia/rubrica.class.php');
Load::_require('modules/transparencia/rubrica.bo.php');

Load::_require('modules/transparencia/funcao.base.php');
Load::_require('modules/transparencia/funcao.class.php');
Load::_require('modules/transparencia/funcao.bo.php');

Load::_require('modules/transparencia/programa.base.php');
Load::_require('modules/transparencia/programa.class.php');
Load::_require('modules/transparencia/programa.bo.php');

Load::_require('modules/transparencia/balancetedespesa.base.php');
Load::_require('modules/transparencia/balancetedespesa.class.php');
Load::_require('modules/transparencia/balancetedespesa.bo.php');

Load::_require('modules/transparencia/balancetereceita.base.php');
Load::_require('modules/transparencia/balancetereceita.class.php');
Load::_require('modules/transparencia/balancetereceita.bo.php');

Load::_require('modules/transparencia/cargo.base.php');
Load::_require('modules/transparencia/cargo.class.php');
Load::_require('modules/transparencia/cargo.bo.php');

Load::_require('modules/transparencia/compra.base.php');
Load::_require('modules/transparencia/compra.class.php');
Load::_require('modules/transparencia/compra.bo.php');

Load::_require('modules/transparencia/estagiario.base.php');
Load::_require('modules/transparencia/estagiario.class.php');
Load::_require('modules/transparencia/estagiario.bo.php');

Load::_require('modules/transparencia/servidor.base.php');
Load::_require('modules/transparencia/servidor.class.php');
Load::_require('modules/transparencia/servidor.bo.php');

Load::_require('modules/transparencia/licitacao.base.php');
Load::_require('modules/transparencia/licitacao.class.php');
Load::_require('modules/transparencia/licitacao.bo.php');

Load::_require('modules/transparencia/unidade.base.php');
Load::_require('modules/transparencia/unidade.class.php');
Load::_require('modules/transparencia/unidade.bo.php');

Load::_require('modules/transparencia/subfuncao.base.php');
Load::_require('modules/transparencia/subfuncao.class.php');
Load::_require('modules/transparencia/subfuncao.bo.php');

Load::_require('modules/site/site.bo.php');
Load::_require('modules/site/site.form.php');


class SiteController extends Controller{

	public static function index(){
//		$municipio = MunicipioBO::getByAlias($GLOBALS['alias']);
//		$uf        = UFBO::getBySigla($GLOBALS['sigla']);

//		Sessao::set('municipio_id', 1);
		Sessao::set('municipio_db', $GLOBALS['municipio_db']);
//		Sessao::set('municipio_alias', $municipio->getAlias());
//		Sessao::set('municipio_nome', $municipio->getNome());
		Sessao::set('municipio_nome', $GLOBALS['municipio_nome']);
//		Sessao::set('municipio_hash', $municipio->getHash());
//		Sessao::set('uf_sigla', $uf->getSigla());
		Sessao::set('uf_sigla', $GLOBALS['sigla']);

		$context = array(
			'snippet' => array(),
            'hasEntidades' => EntidadeBO::entidadeExists()
		);

        EntidadeBO::configuracaoEntidadeExists();

		Load::view('site/views/index2.php', $context);	
	}
	
	public static function alterarMunicipio(){
		Sessao::clean();
		
		redirect();
	}
	
	public static function comoFunciona(){
		$snippet = array('title' => 'Como funciona');
		
		$context = array(
			'snippet' => $snippet,
		);

		Load::view('site/views/comoFunciona.php', $context);	
	}
	
	public static function legislacao(){
		
		if(Sessao::get('municipio_nome')){
			Conn::openConnection(Sessao::get('municipio_db'));
		    
			$search = new Search();
		    $search->setPage($_REQUEST['page']);			
		    $publicacoesDTO = PublicacaoBO::filter($search, 'getBySecaoAlias', array('categoria' => 'legislacao'));
	
			$snippet = array('title' => 'Legislação');
			$context = array(
				'snippet'     => $snippet,
				'publicacoes' => $publicacoesDTO->getObj(),
			);
		} else {
			$snippet = array('title' => 'Legislação');
			$context = array(
				'snippet'     => $snippet,
				'publicacoes' => array(),
			);
		}
		
		Load::view('site/views/legislacao.php', $context);	
	}
	
	public static function contato(){
		if(!Sessao::get('municipio_db')){
			redirect();
		}		
		
		$snippet = array('title' => 'Fale Conosco / Informações Adicionais');
		
		$context = array(
			'snippet' => $snippet,
			'form'    => new FormContato(),
		);

		Load::view('site/views/contato.php', $context);
	}
	
	
	public static function createContato(){
		postRequired('contato');
		
		$form = new FormContato($_POST);
		
		if($form->isValid()){
			try{
				$contato = new Contato();
				$contato->setConfiguracaoId($form->getFields('configuracao_id')->getValue());
				$contato->setAssunto($form->getFields('assunto')->getValue());
				$contato->setNome($form->getFields('nome')->getValue());
				$contato->setDDD($form->getFields('ddd')->getValue());
				$contato->setTelefone($form->getFields('telefone')->getValue());
				$contato->setEmail($form->getFields('email')->getValue());
				$contato->setMensagem($form->getFields('mensagem')->getValue());
                              
				$contato = ContatoBO::create($contato);

				Message::getInstance()->success('Mensagem enviada com sucesso');
				
				$form->clean();

			} catch(Exception $e){
				Message::getInstance()->error($e->getMessage());
			}
		}

		$snippet = array('title' => 'Fale Conosco / Informações Adicionais');
		
		$context = array(
			'snippet' => $snippet,
			'form'    => $form,
		);

		Load::view('site/views/contato.php', $context);	
	}
	
	public static function buscaMunicipio(){
		$municipios = MunicipioBO::getByUF($_REQUEST['uf_id']);
		$i = 0;
		
		foreach ($municipios as $municipio) {
			$array[$i]['id']       = $municipio->getId();
			$array[$i]['nome']     = utf8_encode($municipio->getNome());
			$array[$i]['alias']    = $municipio->getAlias();
			$array[$i]['uf_sigla'] = $municipio->getUF()->getSigla();
			$i++;
		}
		
        $context = array(
        	'municipios' => $array,
        );
        
		Load::json($context);
	}
	
	
	# Despesa
	public static function listaBalanceteDespesaOrgao(){
		Conn::openConnection(Sessao::get('municipio_db'));

		# Grava variável de pesquisa para que sejam eventualmente utilizadas na tela de empenhos
		Sessao::set('cod_entidade', $_REQUEST['cod_entidade']);
		Sessao::set('exercicio'   , $_REQUEST['exercicio']);

		$search = new Search();
		$search->setPage($_REQUEST['page']);

		$balanceteDespesaDTO = BalanceteDespesaBO::filter($search, 'getBalanceteDespesaOrgao', $_REQUEST);
		$totais 			 = BalanceteDespesaBO::getBalanceteDespesaTotalByExercicio($_REQUEST['exercicio'], $_REQUEST['cod_entidade']);
		$formPesquisa 		 = new FormPesquisa($_REQUEST);
		
		$snippet = array('pager' => $balanceteDespesaDTO->getSearch()->getPager(), 'formPesquisa' => $formPesquisa);
        $context = array(
			'despesas' => $balanceteDespesaDTO->getObj(),
			'snippet'  => $snippet,
			'totais'   => $totais
		);
		
		Load::view('site/views/despesa.php', $context);
	}

	public static function listaBalanceteDespesaFuncao(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		# Grava variável de pesquisa para que sejam eventualmente utilizadas na tela de empenhos
		Sessao::set('cod_entidade', $_REQUEST['cod_entidade']);
		Sessao::set('exercicio'   , $_REQUEST['exercicio']);
		
		$search = new Search();
		$search->setPage($_REQUEST['page']);

		$balanceteDespesaDTO = BalanceteDespesaBO::filter($search, 'getBalanceteDespesaFuncao', $_REQUEST);
		$totais 			 = BalanceteDespesaBO::getBalanceteDespesaTotalByExercicio($_REQUEST['exercicio'], $_REQUEST['cod_entidade']);
		$formPesquisa 		 = new FormPesquisa($_REQUEST);
		
		$snippet = array('pager' => $balanceteDespesaDTO->getSearch()->getPager(), 'formPesquisa' => $formPesquisa);
        $context = array(
			'despesas' => $balanceteDespesaDTO->getObj(),
			'snippet'  => $snippet,
			'totais'   => $totais
		);
		
		Load::view('site/views/despesa.php', $context);
	}

	public static function listaBalanceteDespesaPrograma(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		# Grava variável de pesquisa para que sejam eventualmente utilizadas na tela de empenhos
		Sessao::set('cod_entidade', $_REQUEST['cod_entidade']);
		Sessao::set('exercicio'   , $_REQUEST['exercicio']);

		$search = new Search();
		$search->setPage($_REQUEST['page']);

		$balanceteDespesaDTO = BalanceteDespesaBO::filter($search, 'getBalanceteDespesaPrograma', $_REQUEST);
		$totais 			 = BalanceteDespesaBO::getBalanceteDespesaTotalByExercicio($_REQUEST['exercicio'], $_REQUEST['cod_entidade']);
		$formPesquisa 		 = new FormPesquisa($_REQUEST);
		
		$snippet = array('pager' => $balanceteDespesaDTO->getSearch()->getPager(), 'formPesquisa' => $formPesquisa);
        $context = array(
			'despesas' => $balanceteDespesaDTO->getObj(),
			'snippet'  => $snippet,
			'totais'   => $totais
		);

		Load::view('site/views/despesa.php', $context);
	}

	public static function listaBalanceteDespesaProjeto(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		# Grava variável de pesquisa para que sejam eventualmente utilizadas na tela de empenhos
		Sessao::set('cod_entidade', $_REQUEST['cod_entidade']);
		Sessao::set('exercicio'   , $_REQUEST['exercicio']);		

		$search = new Search();
		$search->setPage($_REQUEST['page']);

		$balanceteDespesaDTO = BalanceteDespesaBO::filter($search, 'getBalanceteDespesaProjeto', $_REQUEST);
		$totais 			 = BalanceteDespesaBO::getBalanceteDespesaTotalByExercicio($_REQUEST['exercicio'], $_REQUEST['cod_entidade']);
		$formPesquisa 	 	 = new FormPesquisa($_REQUEST);
		
		$snippet = array('pager' => $balanceteDespesaDTO->getSearch()->getPager(), 'formPesquisa' => $formPesquisa);
        $context = array(
			'despesas' => $balanceteDespesaDTO->getObj(),
			'snippet'  => $snippet,
			'totais'   => $totais
		);

		Load::view('site/views/despesa.php', $context);
	}

	public static function listaBalanceteDespesaCategoria(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		# Grava variável de pesquisa para que sejam eventualmente utilizadas na tela de empenhos
		Sessao::set('cod_entidade', $_REQUEST['cod_entidade']);
		Sessao::set('exercicio'   , $_REQUEST['exercicio']);		

		$search = new Search();
		$search->setPage($_REQUEST['page']);

		$balanceteDespesaDTO = BalanceteDespesaBO::customFilter($search, 'getBalanceteDespesaCategoriaEconomica', $_REQUEST);
		
		$args = Sessao::get('args');
		$args['nivel'] = $_REQUEST['nivel'];
		
		$totais 			 = BalanceteDespesaBO::getBalanceteDespesaCategoriaTotal($args); # A sessão args é setada dentro do customFilter
		$formPesquisa        = new FormPesquisa($_REQUEST);
		
		$snippet = array('pager' => $balanceteDespesaDTO->getSearch()->getPager(), 'formPesquisa' => $formPesquisa);
        $context = array(
			'despesas' => $balanceteDespesaDTO->getObj(),
			'snippet'  => $snippet,
			'total'    => $totais[0]
		);

		Load::view('site/views/categoria.php', $context);
	}
	
	public static function listaBalanceteDespesaCategoriaNatureza(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		# Grava variável de pesquisa para que sejam eventualmente utilizadas na tela de empenhos
		Sessao::set('cod_entidade', $_REQUEST['cod_entidade']);
		Sessao::set('exercicio'   , $_REQUEST['exercicio']);

		$search = new Search();
		$search->setPage($_REQUEST['page']);

		$balanceteDespesaDTO = BalanceteDespesaBO::customFilter($search, 'getBalanceteDespesaCategoriaNatureza', $_REQUEST);
		$rubricaCategoria    = BalanceteDespesaBO::getCategoria($_REQUEST);
		
		$args = Sessao::get('args');
		$args['nivel'] = 'natureza';
		
		$totais 	  = BalanceteDespesaBO::getBalanceteDespesaCategoriaTotal($args); # A sessão args é setada dentro do customFilter
		$formPesquisa = new FormPesquisa($_REQUEST);
		
		$snippet = array('pager' => $balanceteDespesaDTO->getSearch()->getPager(), 'formPesquisa' => $formPesquisa);
        $context = array(
			'despesas'         => $balanceteDespesaDTO->getObj(),
			'snippet'          => $snippet,
			'total'            => $totais[0],
			'rubricaCategoria' => $rubricaCategoria,
		);

		Load::view('site/views/categoria.php', $context);
	}
	
	public static function listaBalanceteDespesaCategoriaElemento(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		# Grava variável de pesquisa para que sejam eventualmente utilizadas na tela de empenhos
		Sessao::set('cod_entidade', $_REQUEST['cod_entidade']);
		Sessao::set('exercicio'   , $_REQUEST['exercicio']);		

		$search = new Search();
		$search->setPage($_REQUEST['page']);

		$balanceteDespesaDTO = BalanceteDespesaBO::customFilter($search, 'getBalanceteDespesaCategoriaElemento', $_REQUEST);
		$rubricaCategoria    = BalanceteDespesaBO::getCategoria($_REQUEST);
		$rubricaNatureza     = BalanceteDespesaBO::getNatureza($_REQUEST);
		
		$args = Sessao::get('args');
		$args['nivel'] = 'elemento';
		
		$totais 			 = BalanceteDespesaBO::getBalanceteDespesaCategoriaTotal($args); # A sessão args é setada dentro do customFilter
		$formPesquisa        = new FormPesquisa($_REQUEST);
		
		$snippet = array('pager' => $balanceteDespesaDTO->getSearch()->getPager(), 'formPesquisa' => $formPesquisa);
        $context = array(
			'despesas'         => $balanceteDespesaDTO->getObj(),
			'snippet'          => $snippet,
			'total'            => $totais[0],
			'rubricaCategoria' => $rubricaCategoria,
			'rubricaNatureza'  => $rubricaNatureza,
		);

		Load::view('site/views/categoria.php', $context);
	}	
	
	public static function listaBalanceteDespesaRecurso(){
		Conn::openConnection(Sessao::get('municipio_db'));

		# Grava variável de pesquisa para que sejam eventualmente utilizadas na tela de empenhos
		Sessao::set('cod_entidade', $_REQUEST['cod_entidade']);
		Sessao::set('exercicio'   , $_REQUEST['exercicio']);		
		
		$search = new Search();
		$search->setPage($_REQUEST['page']);

		$balanceteDespesaDTO = BalanceteDespesaBO::filter($search, 'getBalanceteDespesaRecurso', $_REQUEST);
		$totais              = BalanceteDespesaBO::getBalanceteDespesaTotalByExercicio($_REQUEST['exercicio'], $_REQUEST['cod_entidade']);
		$formPesquisa        = new FormPesquisa($_REQUEST);
		
		$snippet = array('pager' => $balanceteDespesaDTO->getSearch()->getPager(), 'formPesquisa' => $formPesquisa);
        $context = array(
			'despesas' => $balanceteDespesaDTO->getObj(),
			'snippet'  => $snippet,
			'totais'   => $totais
		);

		Load::view('site/views/despesa.php', $context);
	}
	
	public static function listaBalanceteDespesaCredor(){
		Conn::openConnection(Sessao::get('municipio_db'));

		# Grava variável de pesquisa para que sejam eventualmente utilizadas na tela de empenhos
		Sessao::set('cod_entidade', $_REQUEST['cod_entidade']);
		Sessao::set('exercicio'   , $_REQUEST['exercicio']);		
		
		$search = new Search();
		$search->setPage($_REQUEST['page']);
		
		$balanceteDespesaDTO = BalanceteDespesaBO::filter($search, 'getBalanceteDespesaCredor', $_REQUEST);
		$totais              = BalanceteDespesaBO::getBalanceteDespesaTotalByExercicio($_REQUEST['exercicio'], $_REQUEST['cod_entidade']);
		$formPesquisa        = new FormPesquisa($_REQUEST);
		
		$snippet = array('pager' => $balanceteDespesaDTO->getSearch()->getPager(), 'formPesquisa' => $formPesquisa);
        $context = array(
			'despesas' => $balanceteDespesaDTO->getObj(),
			'snippet'  => $snippet,
			'totais'   => $totais
		);

		Load::view('site/views/credor.php', $context);
	}	

	# Receita
	public static function listaBalanceteReceitaConta(){
		Conn::openConnection(Sessao::get('municipio_db'));

		$search = new Search();
		$search->setPage($_REQUEST['page']);

        $balanceteReceitaDTO = BalanceteReceitaBO::filter($search, 'getBalanceteReceitaConta', $_REQUEST);
		$totais = BalanceteReceitaBO::getBalanceteReceitaContaTotalByExercicio($_REQUEST['exercicio'], $_REQUEST['cod_entidade']);
		
        $formPesquisa = new FormPesquisa($_REQUEST);
	
		$snippet = array('pager' => $balanceteReceitaDTO->getSearch()->getPager(), 'formPesquisa' => $formPesquisa);
        $context = array(
			'receitas' => $balanceteReceitaDTO->getObj(),
			'snippet'  => $snippet,
			'total'    => $totais->fetch()
		);

		Load::view('site/views/receita.php', $context);
	}
	
	public static function listaBalanceteReceitaMes(){
		Conn::openConnection(Sessao::get('municipio_db'));

		$search = new Search();
		$search->setPage($_REQUEST['page']);

		$ultimoMesImportacao = getMesData((ImportacaoBO::getUltimo()->data_limite_dado), 'US');
		$mes = isset($_REQUEST['mes']) ? $_REQUEST['mes'] : getMesExtenso($ultimoMesImportacao);

		$balanceteReceitaDTO = BalanceteReceitaBO::filter($search, 'getBalanceteReceitaConta', $_REQUEST);
		$totais = BalanceteReceitaBO::getBalanceteReceitaContaTotalByExercicio($_REQUEST['exercicio'], $_REQUEST['cod_entidade'], $mes);
		
		$formPesquisa = new FormPesquisaMes($_REQUEST);

		$snippet = array('pager' => $balanceteReceitaDTO->getSearch()->getPager(), 'formPesquisa' => $formPesquisa);
        $context = array(
			'receitas' => $balanceteReceitaDTO->getObj(),
			'snippet'  => $snippet,
			'total'    => $totais->fetch(),
			'mes'      => $mes
		);

		Load::view('site/views/receitaMes.php', $context);
	}

	# Compra
	public static function listaCompra(){
		Conn::openConnection(Sessao::get('municipio_db'));

		Sessao::set('cod_entidade', $_REQUEST['cod_entidade']);
		Sessao::set('exercicio'   , $_REQUEST['exercicio']);
		
		$search = new Search();
		$search->setPage($_REQUEST['page']);

		$comprasDTO   = CompraBO::customFilter($search, 'getCompra', $_REQUEST);
		$formPesquisa = new FormPesquisa($_REQUEST);

		$snippet = array('pager' => $comprasDTO->getSearch()->getPager(), 'formPesquisa' => $formPesquisa);
        $context = array(
			'registros' => $comprasDTO->getObj(),
			'snippet'   => $snippet,
			'tipo'      => 'Compra Direta'
		);

		Load::view('site/views/compra.php', $context);
	}

	# Licitação
	public static function listaLicitacao(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		Sessao::set('cod_entidade', $_REQUEST['cod_entidade']);
		Sessao::set('exercicio'   , $_REQUEST['exercicio']);		

		$search = new Search();
		$search->setPage($_REQUEST['page']);

		$licitacoesDTO = LicitacaoBO::customFilter($search, 'getLicitacao', $_REQUEST);
		$formPesquisa  = new FormPesquisa($_REQUEST);
    
		$snippet = array('pager' => $licitacoesDTO->getSearch()->getPager(), 'formPesquisa' => $formPesquisa);
        $context = array(
			'registros' => $licitacoesDTO->getObj(),
			'snippet'   => $snippet,
			'tipo'      => 'Licitação'
		);

		Load::view('site/views/compra.php', $context);
	}

	# Cargos
	public static function listaCargo(){
		Conn::openConnection(Sessao::get('municipio_db'));

		$search = new Search();
		$search->setPage($_REQUEST['page']);

		$cargosDTO = CargoBO::filter($search, 'getCargo', $_REQUEST);
		$formPesquisaCompetencia = new FormPesquisaCompetencia($_REQUEST);

		$snippet = array('pager' => $cargosDTO->getSearch()->getPager(), 'formPesquisa' => $formPesquisaCompetencia);
        $context = array(
			'cargos'  => $cargosDTO->getObj(),
			'snippet' => $snippet
		);

		Load::view('site/views/cargo.php', $context);
	}

	# Servidores
	public static function listaServidor(){
		Conn::openConnection(Sessao::get('municipio_db'));

		$search = new Search();
		$search->setPage($_REQUEST['page']);

		$servidoresDTO = ServidorBO::filter($search, 'getServidor', $_REQUEST);
		$formPesquisa  = new FormPesquisaServidor($_REQUEST);
	
		$snippet = array('pager' => $servidoresDTO->getSearch()->getPager(), 'formPesquisa' => $formPesquisa);		
        $context = array(
			'servidores' => $servidoresDTO->getObj(),
			'snippet'    => $snippet
		);

		Load::view('site/views/servidor.php', $context);
	}

	# Estagiários
	public static function listaEstagiario(){
		Conn::openConnection(Sessao::get('municipio_db'));

		$search = new Search();
		$search->setPage($_REQUEST['page']);

		$estagiariosDTO = EstagiarioBO::filter($search, 'getEstagiario', $_REQUEST);
		$formPesquisaCompetencia = new FormPesquisaCompetencia($_REQUEST);

		$snippet = array('pager' => $estagiariosDTO->getSearch()->getPager(), 'formPesquisa' => $formPesquisaCompetencia);
        $context = array(
			'estagiarios' => $estagiariosDTO->getObj(),
			'snippet'     => $snippet
		);

		Load::view('site/views/estagiario.php', $context);
	}

	# CedidosAdidos
	public static function listaCedidoAdido(){
		Conn::openConnection(Sessao::get('municipio_db'));

		$search = new Search();
		$search->setPage($_REQUEST['page']);

		$cedidosadidosDTO = CedidoAdidoBO::filter($search, 'getCedidoAdido');
        $formPesquisaCompetencia = new FormPesquisaCompetencia($_REQUEST);

		$snippet = array('pager' => $cedidosadidosDTO->getSearch()->getPager(), 'formPesquisa' => $formPesquisaCompetencia);
        $context = array(
			'cedidosadidos' => $cedidosadidosDTO->getObj(),
			'snippet'       => $snippet
		);

		Load::view('site/views/cedidoadido.php', $context);
	}

	# Remuneração
	public static function listaRemuneracao(){
		Conn::openConnection(Sessao::get('municipio_db'));

		$search = new Search();
		$search->setPage($_REQUEST['page']);

		$remuneracoesDTO         = RemuneracaoBO::filter($search, 'getRemuneracao', $_REQUEST);
		$formPesquisaCompetencia = new FormPesquisaCompetencia($_REQUEST);
		
		$snippet = array('pager' => $remuneracoesDTO->getSearch()->getPager(), 'formPesquisa' => $formPesquisaCompetencia);
        $context = array(
			'remuneracoes' => $remuneracoesDTO->getObj(),
			'snippet'      => $snippet,
		);

		Load::view('site/views/remuneracao.php', $context);
	}

	# Publicação
	public static function listaPublicacao(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		$search = new Search();
		$search->setPage($_REQUEST['page']);		
		
		$publicacoesDTO = PublicacaoBO::filter($search, 'getBySecaoAlias', $_REQUEST);
		$secao  	    = SecaoBO::getByAlias($_REQUEST['categoria']);

		$snippet = array('pager' => $publicacoesDTO->getSearch()->getPager());
        $context = array(
        	'publicacoes' => $publicacoesDTO->getObj(),
			'secao' 	  => $secao,
			'snippet'     => $snippet,
			'template'    => 'blank'
        );

		Load::view('site/views/publicacao.php', $context);
	}

	# Publicações Geral
	public static function listaPublicacaoGeral(){
		Conn::openConnection(Sessao::get('municipio_db'));

		$search = new Search();
		$search->setPage($_REQUEST['page']);
		
		$publicacoesDTO = PublicacaoBO::filter($search, 'getPublicacaoGeral', $_REQUEST);
		$formPesquisa   = new FormPesquisaPublicacao($_REQUEST);

		$snippet     = array('pager' => $publicacoesDTO->getSearch()->getPager());
        $context     = array(
			'publicacoes'  => $publicacoesDTO->getObj(),
			'formPesquisa' => $formPesquisa,
			'snippet'      => $snippet,
			'template'     => 'blank',
        );

		Load::view('site/views/publicacaoGeral.php', $context);
	}	

	# Listagem de Empenho
	public static function listaEmpenho(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		if(isset($_REQUEST['cod_entidade'])){
			Sessao::set('cod_entidade', $_REQUEST['cod_entidade']);
		}
		
		$search = new Search();
		$search->setPage($_REQUEST['page']);
        
		$empenhoDTO = EmpenhoBO::filter($search, 'getEmpenho', $_REQUEST);
		
		$snippet = array('pager' => $empenhoDTO->getSearch()->getPager());
        $context = array(
			'empenhos' => $empenhoDTO->getObj(),
			'snippet'  => $snippet
		);
		
		if(isset($_REQUEST['natureza'])){
			$rubricaCategoria    = BalanceteDespesaBO::getCategoria($_REQUEST);
			$rubricaNatureza     = BalanceteDespesaBO::getNatureza($_REQUEST);
			
			$context['rubricaCategoria'] = $rubricaCategoria;
			$context['rubricaNatureza'] = $rubricaNatureza;
		}		
		
		Load::view('site/views/empenho.php', $context);
	}

	# Listagem de Itens
	public static function listaItem(){
		Conn::openConnection(Sessao::get('municipio_db'));

		$itens = ItemBO::getItemByCodEmpenho($_REQUEST['cod_empenho']);
        $snippet = '';
        $context = array(
			'itens'    => $itens,
			'snippet'  => $snippet,
			'template' => 'blank',
		);		
		
		Load::view('site/views/item.php', $context);
	}
	
	# Histórico do empenho
	public static function listaHistorico(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		$historicos = EmpenhoBO::getHistorico($_REQUEST['cod_empenho']);
        $snippet = '';
        $context = array(
			'historicos' => $historicos,
			'snippet'    => $snippet,
			'template'   => 'blank',
		);		
		
		Load::view('site/views/historico.php', $context);
	}	
}