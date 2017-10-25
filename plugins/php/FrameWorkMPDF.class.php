<?php
/*
    **********************************************************************************
    *                                                                                *
    * @package URBEM CNM - SoluÃ§Ãµes em GestÃ£o PÃºblica                                *
    * @copyright (c) 2013 ConfederaÃ§Ã£o Nacional de MunicÃ­pos                         *
    * @author ConfederaÃ§Ã£o Nacional de MunicÃ­pios                                    *
    *                                                                                *
    * O URBEM CNM Ã© um software livre; vocÃª pode redistribuÃ­-lo e/ou modificÃ¡-lo sob *
    * os  termos  da LicenÃ§a PÃºblica Geral GNU conforme  publicada  pela FundaÃ§Ã£o do *
    * Software Livre (FSF - Free Software Foundation); na versÃ£o 2 da LicenÃ§a.       *
    *                                                                                *
    * Este  programa  Ã©  distribuÃ­do  na  expectativa  de  que  seja  Ãºtil,   porÃ©m, *
    * SEM NENHUMA GARANTIA; nem mesmo a garantia implÃ­cita  de  COMERCIABILIDADE  OU *
    * ADEQUAÃÃO A UMA FINALIDADE ESPECÃFICA. Consulte a LicenÃ§a PÃºblica Geral do GNU *
    * para mais detalhes.                                                            *
    *                                                                                *
    * VocÃª deve ter recebido uma cÃ³pia da LicenÃ§a PÃºblica Geral do GNU "LICENCA.txt" *
    * com  este  programa; se nÃ£o, escreva para  a  Free  Software Foundation  Inc., *
    * no endereÃ§o 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.       *
    *                                                                                *
    **********************************************************************************
*/
?>
<?php
/**
  * PÃ¡gina Oculta para gerar o arquivo Demostrativo RCL
  * Data de CriaÃ§Ã£o: 24/07/2014

  * @author Analista:      Eduardo Paculski Schitz
  * @author Desenvolvedor: Franver Sarmento de Moraes
  *
  * @ignore
  * $Id: FrameWorkMPDF.class.php 65828 2016-06-21 17:12:19Z carlos.silva $
  * $Date: 2016-04-07 15:35:09 -0300 (Qui, 07 Abr 2016) $
  * $Author: michel $
  * $Rev: 64854 $
  *
*/

include 'MPDF57/mpdf.php';
include 'ViewLoader.class.php';

class FrameWorkMPDF
{
    /**
    * 
    * @var string
    *    
    */
    private $stPrincipalHTML;
    
    /**
    * 
    * @var string
    *    
    */
    private $stDiretorioArquivo;
    
    /**
    * 
    * @var string
    *    
    */
    private $stCabecalhoHTML;
    
    /**
    * 
    * @var string
    *    
    */
    private $stRodapeHTML;
    
    /**
    * 
    * @var string
    *    
    */
    private $stFolhaCSS;
    
    /**
    * 
    * @var string
    *    
    */
    private $stNomeRelatorio;
    
    /**
    * 
    * @var string
    *    
    */
    private $stCodEntidades;
    
    /**
    * 
    * @var string
    *    
    */
    private $stData;
    
    /**
    * 
    * @var string
    *    
    */
    private $stTipoSaida = 'I';
    
    /**
    * 
    * @var string
    *    
    */
    private $stFormatoFolha = 'A4';
    
    /**
    * 
    * @var string
    *    
    */
    private $stNomeArquivo;
    
    /**
    * 
    * @var string
    *    
    */
    private $stTemplate;
    
    /**
    * 
    * @var integer
    *    
    */
    private $inCodGestao;
    
    /**
    * 
    * @var integer
    *    
    */
    private $inCodModulo;
    
    /**
    * 
    * @var integer
    *    
    */
    private $inCodRelatorio;
    
    /**
    * 
    * @var Array()
    *    
    */
    private $arConteudo;
    
    /**
    * 
    * @var boolean
    *    
    */
    private $boCabecalho;
    
    /**
    * 
    * @var Objeto
    *    
    */
    private $obMPDF;
    
    /**
    * 
    * @var Objeto
    *    
    */
    private $obViewLoader;
    
    public function getPrincipalHTML(){ return $this->stPrincipalHTML; }
    public function setPrincipalHTML( $stPrincipalHTML ) { $this->stPrincipalHTML = $stPrincipalHTML; }
    
    public function getCabecalhoHTML() { return $this->stCabecalhoHTML; }
    public function setCabecalhoHTML( $stCabecalhoHTML ) { $this->stCabecalhoHTML = $stCabecalhoHTML; }
    
    public function getRodapeHTML() { return $this->stRodapeHTML; }
    public function setRodapeHTML( $stRodapeHTML ) { $this->stRodapeHTML = $stRodapeHTML; }
    
    public function getCodGestao(){ return $this->inCodGestao; }
    public function setCodGestao( $inCodGestao ) { $this->inCodGestao = $inCodGestao; }
    
    public function getCodModulo(){ return $this->inCodModulo; }
    public function setCodModulo( $inCodModulo ) { $this->inCodModulo = $inCodModulo; }

    public function getCodRelatorio(){ return $this->inCodRelatorio; }
    public function setCodRelatorio( $inCodRelatorio ) { $this->inCodRelatorio = $inCodRelatorio; }
    
    public function getConteudo() { return $this->arConteudo; }
    public function setConteudo( $arConteudo ) { $this->arConteudo = $arConteudo; }
    
    public function getDiretorioArquivo() { return $this->stDiretorioArquivo; }
    public function setDiretorioArquivo( $stDiretorioArquivo ) { $this->stDiretorioArquivo = $stDiretorioArquivo; }
    
    public function getNomeArquivo() { return $this->stNomeArquivo; }
    public function setNomeArquivo( $stNomeArquivo ) { $this->stNomeArquivo = $stNomeArquivo; }
    
    public function getTemplate() { return $this->stTemplate;}
    public function setTemplate( $stTemplate ) { $this->stTemplate = $stTemplate; }
    
    public function getFolhaCSS() { return $this->stFolhaCSS; }
    public function setFolhaCSS( $stFolhaCSS ) { $this->stFolhaCSS = $stFolhaCSS; }
    
    public function getNomeRelatorio() { return $this->stNomeRelatorio; }
    public function setNomeRelatorio( $stNomeRelatorio ) { $this->stNomeRelatorio = $stNomeRelatorio; }
    
    public function getCodEntidades() { return $this->stCodEntidades; }
    public function setCodEntidades( $stCodEntidades ) { $this->stCodEntidades = $stCodEntidades; }
    
    public function getData() { return $this->stData; }
    public function setData( $stDataInicio ) { $this->stData = $stData; }

    /* recebera A4 para retrato ou A4-L para paisagem */
    public function getFormatoFolha() { return $this->stFormatoFolha; }
    public function setFormatoFolha( $stFormatoFolha ) { $this->stFormatoFolha = $stFormatoFolha; }
    
    public function getTipoSaida() { return $this->stTipoSaida; } 
    public function setTipoSaida( $stTipoSaida ) { $this->stTipoSaida = $stTipoSaida; }
    
    public function getMostraCabecalho() { return $this->boCabecalho; } 
    public function setMostraCabecalho( $valor ) { $this->boCabecalho = $valor; }
    

    /**
     *
     * $method contruct
     * 
     * Metodo construtor da class FrameWorkMPDF
     * 
     */
    public function FrameWorkMPDF()
    {
        $stLinkCSS = file_get_contents(getcwd().'/templates/default/css/mpdf.css');
        $this->setFolhaCSS ( $stLinkCSS );
        $this->obViewLoader = new ViewLoader(getcwd().'/modules/mpdf/views/');
        $this->setMostraCabecalho( TRUE );
    }
    
    /**
    * 
    * @method montaCabecalhoHTML
    *
    * Metodo para criar o HTML do cabeÃ§alho do relatÃ³rio
    *    
    */
    public function montaCabecalhoHTML()
    {
        $obCabecalho = new ViewLoader(getcwd().'/modules/mpdf/views/');
        
        $context     = array( "nomeRelatorio"   => $this->getNomeRelatorio() );
        
        $this->setCabecalhoHTML($obCabecalho->loadTemplate("_header.php", $context, false));
    }
    
    public function getDownloadNomeRelatorio() {
        $stNomRelatorio = $this->getNomeRelatorio();
        SistemaLegado::removeAcentosSimbolos($stNomRelatorio);
        $stNomRelatorio = ucwords( $stNomRelatorio );
        $stNomRelatorio = preg_replace("/[^a-zA-Z0-9]/","", $stNomRelatorio );

        return $stNomRelatorio."_".date("Y-m-d",time())."_".date("Hi",time()).".pdf";
    }

    /**
    * 
    * @method gerarRelatorio
    *
    * Metodo para gerar o relÃ¡torio em PDF
    *    
    */
    public function gerarRelatorio($stHtml = '', $stHtmlCabecalho = '') {
        $mgt = 27;
        if($stHtml!='' && $stHtmlCabecalho!=''){
            $inLinhasCabecalho = substr_count($stHtmlCabecalho, '<tr>');
            $mgt = $mgt + (4 * $inLinhasCabecalho);
        }

        $this->obMPDF = new mPDF($mode='',$format=$this->getFormatoFolha(),$default_font_size=8,$default_font='sans-serif',$mgl=5,$mgr=7,$mgt,$mgb=16,$mgh=9,$mgf=9, $orientation='P');

        // Converte o arquivo LHarquivo.php para cÃ³digo HTML
        if($stHtml=='')
            $this->setPrincipalHTML($this->obViewLoader->loadTemplate($this->getTemplate(), $this->getConteudo(), false));
        else
            $this->setPrincipalHTML($stHtml);

        // Monta o CabeÃ§alho e o RodapÃ© do relatÃ³rio
        if($this->getMostraCabecalho())
            $this->montaCabecalhoHTML();
        $this->setCabecalhoHTML($this->getCabecalhoHTML().$stHtmlCabecalho);
        
        //$this->montaRodapeHTML();
        //
        //// recebe o HTML que foi gerado do cabecalho e do rodape e inseri na classe mPDF
        $this->obMPDF->SetHTMLHeader($this->getCabecalhoHTML());
        $this->obMPDF->SetHTMLFooter($this->getRodapeHTML());
        
        // Adiciona a folha de estilo ao relatÃ³rio
        $this->obMPDF->WriteHTML($this->getFolhaCSS(), 1);
        
        // Adiciona o cÃ³digo HTMl do relatÃ³rio, que serÃ¡ gerado pelos arquivo LHarquivo.php
        $this->obMPDF->WriteHTML($this->getPrincipalHTML(), 2);
        
        ////Setando parametros para melhorar o desempenho da escrita PDF..
        $this->obMPDF->useSubstitutions = false; 
        $this->obMPDF->simpleTables = true;
        
        $file = 'tmp/'.$this->getNomeArquivo().'_'.date('YmdHis').'.pdf';
        
        $this->obMPDF->Output( '../'.$file , 'F');
    
        return $file;
    }
}

?>