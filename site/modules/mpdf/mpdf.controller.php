<?php
ini_set('memory_limit', '-1');

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
\
Load::_require('modules/transparencia/licitacao.base.php');
Load::_require('modules/transparencia/licitacao.class.php');
Load::_require('modules/transparencia/licitacao.bo.php');

Load::_require('modules/transparencia/unidade.base.php');
Load::_require('modules/transparencia/unidade.class.php');
Load::_require('modules/transparencia/unidade.bo.php');

Load::_require('modules/transparencia/subfuncao.base.php');
Load::_require('modules/transparencia/subfuncao.class.php');
Load::_require('modules/transparencia/subfuncao.bo.php');

Load::_require('plugins/php/FrameWorkMPDF.class.php');


class MpdfController extends Controller{

    # Despesa
	public static function listaBalanceteDespesaOrgao(){
		Conn::openConnection(Sessao::get('municipio_db'));

		$balanceteDespesa = BalanceteDespesaBO::getBalanceteDespesaOrgao($_REQUEST['exercicio']);
		$totais 		  = BalanceteDespesaBO::getBalanceteDespesaTotalByExercicio($_REQUEST['exercicio'], $_REQUEST['cod_entidade']);
		
        $context = array(
			'despesas' => $balanceteDespesa->execute(),
			'totais'   => $totais
		);
		
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de Despesas por Órgão");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_despesas_orgao");
        $obMPDF->setTemplate("despesa.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();

        echo url($file);
	}
    
    public static function listaBalanceteDespesaFuncao(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		$balanceteDespesa = BalanceteDespesaBO::getBalanceteDespesaFuncao($_REQUEST['exercicio']);
		$totais 		  = BalanceteDespesaBO::getBalanceteDespesaTotalByExercicio($_REQUEST['exercicio'], $_REQUEST['cod_entidade']);
		
        $context = array(
			'despesas' => $balanceteDespesa->execute(),
			'totais'   => $totais
		);
		
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de Despesas por Função");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_despesas_funcao");
        $obMPDF->setTemplate("despesa.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();
        
        echo url($file);
	}

    public static function listaBalanceteDespesaPrograma(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		$balanceteDespesa = BalanceteDespesaBO::getBalanceteDespesaPrograma($_REQUEST['exercicio']);
		$totais 		  = BalanceteDespesaBO::getBalanceteDespesaTotalByExercicio($_REQUEST['exercicio'], $_REQUEST['cod_entidade']);
		
        $context = array(
			'despesas' => $balanceteDespesa->execute(),
			'totais'   => $totais
		);
		
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de Despesas por Programa");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_despesas_programa");
        $obMPDF->setTemplate("despesa.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();
        
        echo url($file);
	}
    
    public static function listaBalanceteDespesaProjeto(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		$balanceteDespesa    = BalanceteDespesaBO::getBalanceteDespesaProjeto($_REQUEST['exercicio']);
		$totais 			 = BalanceteDespesaBO::getBalanceteDespesaTotalByExercicio($_REQUEST['exercicio'], $_REQUEST['cod_entidade']);
		
        $context = array(
			'despesas' => $balanceteDespesa->execute(),
			'totais'   => $totais
		);
		
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de Despesas por Projeto");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_despesas_projeto");
        $obMPDF->setTemplate("despesa.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();
        
        echo url($file);
	}
    
    public static function listaBalanceteDespesaRecurso(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		$balanceteDespesa = BalanceteDespesaBO::getBalanceteDespesaRecurso($_REQUEST['exercicio']);
		$totais 		  = BalanceteDespesaBO::getBalanceteDespesaTotalByExercicio($_REQUEST['exercicio'], $_REQUEST['cod_entidade']);
		
        $context = array(
			'despesas' => $balanceteDespesa->execute(),
			'totais'   => $totais
		);
		
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de Despesas por Recurso");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_despesas_recurso");
        $obMPDF->setTemplate("despesa.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();
        
        echo url($file);
	}
    
    public static function listaBalanceteDespesaCredor(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		$balanceteDespesa    = BalanceteDespesaBO::getBalanceteDespesaCredor($_REQUEST['exercicio'], $_REQUEST['cod_entidade']);
		$totais 			 = BalanceteDespesaBO::getBalanceteDespesaTotalByExercicio($_REQUEST['exercicio'], $_REQUEST['cod_entidade']);
		
        $context = array(
			'despesas' => $balanceteDespesa->execute(),
			'totais'   => $totais
		);
        
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de Despesas por Credor");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_despesas_credor");
        $obMPDF->setTemplate("credor.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();
        
        echo url($file);
	}
    
    
	public static function listaBalanceteDespesaCategoria(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		$args = Sessao::get('args');
		$args['nivel'] = $_REQUEST['nivel'];
        
        $balanceteDespesa = BalanceteDespesaBO::geraRelatorio($_REQUEST, 'getBalanceteDespesaCategoriaEconomica');
		$totais           = BalanceteDespesaBO::getBalanceteDespesaCategoriaTotal($args); # A sessão args Ã© setada dentro do customFilter
        
        $context = array(
			'despesas'  => $balanceteDespesa->fetchAll(),
			'total' => $totais[0]
		);
        
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de Despesas por Categoria");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_despesas_categoria");
        $obMPDF->setTemplate("categoria.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();
        
        echo url($file);
	}
	
	public static function listaBalanceteDespesaCategoriaNatureza(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		$args = Sessao::get('args');
		$args['nivel'] = $_REQUEST['nivel'];
        
        $balanceteDespesa = BalanceteDespesaBO::geraRelatorio($_REQUEST, 'getBalanceteDespesaCategoriaNatureza');
		$totais           = BalanceteDespesaBO::getBalanceteDespesaCategoriaTotal($args); # A sessão args Ã© setada dentro do customFilter
        
        $context = array(
			'despesas' => $balanceteDespesa->fetchAll(),
			'total' => $totais[0]
		);
        
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de Despesas por Categoria|Natureza");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_despesas_categoria_natureza");
        $obMPDF->setTemplate("categoria.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();
        
        echo url($file);
	}
	
	public static function listaBalanceteDespesaCategoriaElemento(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		$args = Sessao::get('args');
		$args['nivel'] = $_REQUEST['nivel'];
        
        $balanceteDespesa = BalanceteDespesaBO::geraRelatorio($_REQUEST, 'getBalanceteDespesaCategoriaElemento');
		$totais           = BalanceteDespesaBO::getBalanceteDespesaCategoriaTotal($args); # A sessão args Ã© setada dentro do customFilter
        
        $context = array(
			'despesas' => $balanceteDespesa->fetchAll(),
			'total' => $totais[0]
		);
        
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de Despesas por Categoria|Elemento");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_despesas_categoria_elemento");
        $obMPDF->setTemplate("categoria.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();
        
        echo url($file);
	}
     
	# Receita
	public static function listaBalanceteReceitaConta(){	
		Conn::openConnection(Sessao::get('municipio_db'));
		
        $balanceteReceita = BalanceteReceitaBO::geraRelatorio($_REQUEST, 'getBalanceteReceitaConta');
		$totais           = BalanceteReceitaBO::getBalanceteReceitaContaTotalByExercicio($_REQUEST['exercicio'], $_REQUEST['cod_entidade']); # A sessão args Ã© setada dentro do customFilter
        
        $context = array(
			'receitas' => $balanceteReceita->fetchAll(),
			'total'    => $totais->fetch()
		);
        
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de Receitas");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_receitas");
        $obMPDF->setTemplate("receita.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();

        echo url($file);
	}
    
	public static function listaBalanceteReceitaMes(){	
		Conn::openConnection(Sessao::get('municipio_db'));
		
		$ultimoMesImportacao = getMesData((ImportacaoBO::getUltimo()->data_limite_dado), 'US');
		$mes = isset($_REQUEST['mes']) ? $_REQUEST['mes'] : getMesExtenso($ultimoMesImportacao);
        
        $balanceteReceita = BalanceteReceitaBO::geraRelatorio($_REQUEST, 'getBalanceteReceitaConta');
		$totais           = BalanceteReceitaBO::getBalanceteReceitaContaTotalByExercicio($_REQUEST['exercicio'], $_REQUEST['cod_entidade']); # A sessão args Ã© setada dentro do customFilter
        
        $context = array(
			'receitas' => $balanceteReceita->fetchAll(),
			'total'    => $totais->fetch(),
            'mes'      => $mes
		);
        
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de Receitas por MÃªs");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_receitas_mensais");
        $obMPDF->setTemplate("receitaMes.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();
        
        echo url($file);
	}
	
	# Compra
	public static function listaCompra(){
		Conn::openConnection(Sessao::get('municipio_db'));

		$compras = CompraBO::geraRelatorio($_REQUEST, 'getCompra');

        $context = array(
			'registros' => $compras->fetchAll(),
            'tipo'      => 'Compra Direta'
		);
        
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de Compra Direta");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_compras_diretas");
        $obMPDF->setTemplate("compra.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();
        
        echo url($file);
	}
    
	# Licitação
	public static function listaLicitacao(){
		Conn::openConnection(Sessao::get('municipio_db'));

		$licitacoes = LicitacaoBO::geraRelatorio($_REQUEST, 'getLicitacao');

        $context = array(
			'registros' => $licitacoes->fetchAll(),
            'tipo'      => 'Licitação'
		);
        
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de LicitaçÃµes");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_licitacoes");
        $obMPDF->setTemplate("compra.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();
        
        echo url($file);
	}
    
	# Cargos
	public static function listaCargo(){
		Conn::openConnection(Sessao::get('municipio_db'));

		$cargos = CargoBO::geraRelatorio($_REQUEST, 'getCargo');

        $context = array(
			'cargos' => $cargos->execute()
		);
        
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de Cargos");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_cargos");
        $obMPDF->setTemplate("cargo.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();
        
        echo url($file);
	}

	# Servidores
	public static function listaServidor(){
		Conn::openConnection(Sessao::get('municipio_db'));

		$servidores = ServidorBO::geraRelatorio($_REQUEST, 'getServidor');
        
        $context = array(
			'servidores' => $servidores->execute()
		);
        
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de Servidores ");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_servidores");
        $obMPDF->setTemplate("servidor.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();
        
        echo url($file);
	}

	# EstagiÃ¡rios
	public static function listaEstagiario(){
		Conn::openConnection(Sessao::get('municipio_db'));

		$estagiarios = EstagiarioBO::geraRelatorio($_REQUEST, 'getEstagiario');
        
        $context = array(
			'estagiarios' => $estagiarios->execute()
		);
        
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de EstagiÃ¡rios");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_estagiarios");
        $obMPDF->setTemplate("estagiario.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();
        
        echo url($file);
	}

	# CedidosAdidos
	public static function listaCedidoAdido(){
		Conn::openConnection(Sessao::get('municipio_db'));

		$cedidosadidos = CedidoAdidoBO::geraRelatorio($_REQUEST, 'getCedidoAdido');

        $context = array(
			'cedidosadidos' => $cedidosadidos->execute()
		);
        
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de Cedidos e Adidos");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_cedidos_adidos");
        $obMPDF->setTemplate("cedidoadido.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();
        
        echo url($file);
	}

	# Remuneração
	public static function listaRemuneracao(){
		Conn::openConnection(Sessao::get('municipio_db'));

        $remuneracoes = RemuneracaoBO::geraRelatorio($_REQUEST, 'getRemuneracao');
        
        $context = array(
			'remuneracoes' => $remuneracoes->execute()
		);
        
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de RemuneraçÃµes");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_remuneracoes");
        $obMPDF->setTemplate("remuneracao.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();
        
        echo url($file);
	}

	# Listagem de Empenho
	public static function listaEmpenho(){
		Conn::openConnection(Sessao::get('municipio_db'));
		
		$empenhos = EmpenhoBO::geraRelatorio($_REQUEST, 'getEmpenho');
		
        $context = array(
			'empenhos' => $empenhos->execute(),
		);
		
		if(isset($_REQUEST['natureza'])){
			$rubricaCategoria    = BalanceteDespesaBO::getCategoria($_REQUEST);
			$rubricaNatureza     = BalanceteDespesaBO::getNatureza($_REQUEST);
			
			$context['rubricaCategoria'] = $rubricaCategoria;
			$context['rubricaNatureza'] = $rubricaNatureza;
		}
		
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de Empenhos");
        $obMPDF->setFormatoFolha("A4-L");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_empenhos");
        $obMPDF->setTemplate("empenho.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();
        
        echo url($file);
	}
    
    
    public static function detalheEmpenho(){
        Conn::openConnection(Sessao::get('municipio_db'));
        
        $empenho = EmpenhoBO::geraRelatorio($_REQUEST, 'getEmpenho');
        
        $context = array(
			'empenho' => $empenho->fetchOne(),
            'historicos' => EmpenhoBO::getHistorico($_REQUEST['cod_empenho']),
            'itens' => ItemBO::getItemByCodEmpenho($_REQUEST['cod_empenho']),
		);
        
        $obMPDF = new FrameWorkMPDF();
        $obMPDF->setData(date('d-m-Y'));
        $obMPDF->setNomeRelatorio("Relatório de AnalÃ­tico de Empenho");
        $obMPDF->setFormatoFolha("A4");
        $obMPDF->setDiretorioArquivo("/modules/mpdf/views/");
        $obMPDF->setNomeArquivo("relatorio_empenho_analitico");
        $obMPDF->setTemplate("empenhoAnalitico.php");
        
        $obMPDF->setConteudo($context);
        $file = $obMPDF->gerarRelatorio();
        
        echo url($file);
    }
}