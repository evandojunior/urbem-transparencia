
<div class="header_municipio_selecionado" style="margin-top: -20px">
    <div class="header_municipio_titulo">Município</div>
    <div class="header_municipio_nome"><?php echo Sessao::get('municipio_nome') ?> / <?php echo Sessao::get('uf_sigla') ?></div>
</div>


<?php Load::snippet('header_principal', $snippet); ?>

<section id="legislacao" class="row success text-center">
    <div class="row">
        <div class="col-lg-12 text-left">
            <p class="text-justify">Publicação das informações contábeis e orçamentárias, atendendo as disposições legais atribuidas pela Lei Federal n° 9.755/1998 e Instrução Normativa do TCU - Tribunal de contas da União n° 28/1999 e, pelas Leis Complementares n° 101/2000 e 131/2009, pelo Decreto n° 7.185/2010 e pela Lei de Acesso à Informação 12.527/2011.</p>
            <br>
            <div class="colunas-2 text-left">

                <ul>
                    <li><a href="http://www.planalto.gov.br/ccivil_03/leis/L9755.htm" target="_blank">Lei nº 9.755, de 16 de Dezembro de 1998.</a></li>
                    <li><a href="http://www.contaspublicas.gov.br/TCU_InstrNorm28-99.htm" target="_blank">Instrução Normativa nº 28, de 5 de Maio de 1999</a></li>
                    <li><a href="http://www.planalto.gov.br/ccivil_03/leis/lcp/lcp101.htm" target="_blank">Lei complementar nº 101, de 4 de Maio de 2000.</a></li><br>
                    <li><a href="http://www.planalto.gov.br/ccivil_03/leis/lcp/lcp131.htm" target="_blank">Lei complementar nº 131, de 27 de Maio de 2009</a></li>
                    <li><a href="http://www.planalto.gov.br/ccivil_03/_ato2007-2010/2010/decreto/d7185.htm" target="_blank">Decreto nº 7.185, de 27 de Maio de 2010.</a></li>
                    <li><a href="http://www.planalto.gov.br/ccivil_03/_ato2011-2014/2011/lei/l12527.htm" target="_blank">Lei nº 12.527, de 18 de Novembro de 2011.</a></li>
                </ul>

            </div>
        </div>
    </div>
</section>

<?php if (count($publicacoes) > 0){ ?>
<div class="lista_publicacao" style="margin: 20px 0px 0px 0px;">
    <div class="header_principal_nome" style="margin: 0px 0px 20px 0px;">Legislação Municipal da Transparência</div>
    
    <table width="100%" cellspacing="1">
        <thead>
            <tr>
                <td width="60%">Descrição</td>
                <td width="10%" align="center">Data de Publicação</td>
                <td width="5%" align="center">Download</td>
            </tr>                
        </thead>

        <tbody>
            <?php foreach($publicacoes as $publicacao){ ?>
            <tr>
                <td>
                    <a href="#" class="more" title="more"><img src="<?php echo url('templates/default/img/read_more.png?20170801') ?>" width="15"/>
                    <?php echo $publicacao->getDescricao(); ?></a>
                    <div class="hide"><?php echo $publicacao->getDetalhamento() ?></div>
                </td>
                <td align="center"><?php echo $publicacao->getCreated(); ?></td>
                <td align="center"><a href="<?php echo $GLOBALS['BASE_URL'].'uploads/'.Sessao::get('municipio_hash').'/publicacao/'.$publicacao->getArquivo() ?>" class="download" target="_blank">&nbsp;</a></td>
            </tr>
            <?php } ?>
        </tbody>	
	</table>
</div>
<?php } ?>
