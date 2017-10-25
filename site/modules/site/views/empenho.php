<?php
	Load::snippet('header_reduzido_municipio');
    Load::snippet('pesquisaLabel', $snippet);
	//Plugin::load('jquery-ui');
?>


<script type="application/javascript">
$(document).ready(function(){
	$( ".tabs" ).tabs({
		beforeLoad: function( event, ui ) {
			ui.jqXHR.error(function() {
			ui.panel.html("Couldn't load this tab. We'll try to fix this as soon as possible. " +"If this wouldn't be a demo." );
		});
		}
	});

    $(".empenhoAnalitico").click(function(e){
        e.preventDefault();

		var elem = $(this);
        $.ajax({
            type: "GET",
            url: $(this).attr("href"),
            beforeSend:function(){
                $(".se-pre-con").fadeIn("fast");
            },
            
            success: function(data) {
                window.location = $("a#inicio").attr("href") + data.replace("/", "");
            },
            
            complete:function(){
                $(".se-pre-con").fadeOut("fast");
            },
        });
    });
});

function abreConteudoAba(url, dataTo)
{
    if ($("."+dataTo).children().length > 0) {
        return;
    }

    $.get(url, function(result){
        $("."+dataTo).html(result);
    });
}
</script>

<div class="categoria">
<?php
	if(isset($_REQUEST['natureza'])) {
		echo '<style> .pesquisa_label { padding-bottom: 10px !important; } </style>';
		echo '<div class="categoria_1">Categoria:</div> <div class="categoria_2">'.$rubricaCategoria['especificacao_rubrica_despesa'].'</div>';
		echo '<div class="categoria_1">Natureza:</div>  <div class="categoria_2">'.$rubricaNatureza['especificacao_rubrica_despesa'].'</div>';
	}
?>	
</div>

<div class="lista_dados">
<table width="100%" cellspacing="1">
	<thead>
		<tr id="principal">
			<td width="100px">Empenho</td>
			<td width="90px" align="center">Data</td>
			<td width="300px" align="center">Credor</td>
			<td width="125px" align="right">Empenhado (R$)</td>
			<td width="125px" align="right">Estornado (R$)</td>
			<td width="125px" align="right">Liquidado (R$)</td>
			<td width="125px" align="right">Pago (R$)</td>
			<td width="25px" align="center">Analítico</td>
		</tr>
	</thead>
	<tbody>
	<?php
		$i = 0;
		foreach ($empenhos as $empenho) {
	?>
		<tr id="principal">
			<td><a href="#" class="more" title="more" rel="row_<?php echo $i ?>" onclick="abreConteudoAba('<?php echo url('despesa/empenho/'.$empenho['numero_empenho'].'/item'); ?>', 'tabs-2-<?php echo $i ?>'); abreConteudoAba('<?php echo url('despesa/empenho/'.$empenho['numero_empenho'].'/historico'); ?>', 'tabs-3-<?php echo $i ?>');"><img src="<?php echo url("templates/default/img/read_more.png?20170801")?>" width="15"/>&nbsp;<?php echo substr($empenho['numero_empenho'], 6, 7) ?></a></td>
			<td align="center"><?php echo formatDateToPHP($empenho['data_empenho']); ?></td>
			<td align="left"><?php echo $empenho['Credor']['nome_credor']; ?></td>
			<td align="right"><?php echo number_format($empenho['valor_empenho'], 2, ',', '.'); ?></td>
			<td align="right"><?php echo number_format($empenho['estornado'], 2, ',', '.'); ?></td>
			<td align="right"><?php echo number_format($empenho['liquidado'], 2, ',', '.'); ?></td>
			<td align="right"><?php echo number_format($empenho['pago'], 2, ',', '.'); ?></td>
			<td align="center"><a href="<?php echo $GLOBALS['BASE_URL']; ?>empenho/<?php echo $empenho['numero_empenho'] ?>/pdf" class="empenhoAnalitico" id="<?php echo $empenho['numero_empenho'] ?>"><img src="<?php echo url('templates/default/img/icon-pdf.png?20170801'); ?>" width="20" /></a></td>
		</tr>
		<tr id="row_<?php echo $i ?>" name="detail" style="display:none; border: 0px; padding: 0px;">
			<td colspan="8" style="padding: 0px;">
                <div class="tabs">
                    <ul>
                        <li><a data-toggle="tab" href="#tabs-1" class="aba-dinamica-empenho" data-to="tabs-1-<?php echo $i ?>" data-href="">Dados do Empenho</a></li>
                        <li><a data-toggle="tab" href="#tabs-2" class="aba-dinamica-empenho" data-to="tabs-2-<?php echo $i ?>" data-href="<?php echo url('despesa/empenho/'.$empenho['numero_empenho'].'/item'); ?>">Itens do Empenho</a></li>
                        <li><a data-toggle="tab" href="#tabs-3" class="aba-dinamica-empenho" data-to="tabs-3-<?php echo $i ?>" data-href="<?php echo url('despesa/empenho/'.$empenho['numero_empenho'].'/historico'); ?>">Histórico</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="tabs-1" class="tab-pane tabs-1-<?php echo $i ?>">
                            <table cellspacing="1" width="100%">
                                <tr><td width="210px" class="tab-title"><b>Entidade: </b></td><td class="tab-detail"><?php echo $empenho['Entidade']['nome_entidade'];?></td></tr>
                                <tr><td width="210px" class="tab-title"><b>Orgão: </b></td><td class="tab-detail"><?php echo $empenho['Orgao']['nome_orgao'];?></td></tr>
                                <tr><td width="210px" class="tab-title"><b>Unidade: </b></td><td class="tab-detail"><?php echo $empenho['Unidade']['nome_unidade'];?></td></tr>
                                <tr><td width="210px" class="tab-title"><b>Função: </b></td><td class="tab-detail"><?php echo $empenho['Funcao']['nome_funcao'];?></td></tr>
                                <tr><td width="210px" class="tab-title"><b>Subfunção: </b></td><td class="tab-detail"><?php echo $empenho['Subfuncao']['nome_subfuncao'];?></td></tr>
                                <tr><td width="210px" class="tab-title"><b>Programa: </b></td><td class="tab-detail"><?php echo $empenho['Programa']['nome_programa'];?></td></tr>
                                <tr><td width="210px" class="tab-title"><b>Projeto / Atividade / Operação Especial:</b></td><td class="tab-detail"><?php echo $empenho['Acao']['nome_projeto'];?></td></tr>
                                <tr><td width="210px" class="tab-title"><b>Categoria: </b></td><td class="tab-detail"><?php echo $empenho['categoria'];?></td></tr>
                                <tr><td width="210px" class="tab-title"><b>Natureza: </b></td><td class="tab-detail"><?php echo $empenho['natureza'];?></td></tr>
                                <tr><td width="210px" class="tab-title"><b>Rubrica: </b></td><td class="tab-detail"><?php echo $empenho['Rubrica']['especificacao_rubrica_despesa'];?></td></tr>
                                <tr><td width="210px" class="tab-title"><b>Recurso: </b></td><td class="tab-detail"><?php echo $empenho['Recurso']['nome_recurso'];?></td></tr>
                                <tr><td width="210px" class="tab-title"><b>Contrapartida: </b></td><td class="tab-detail"><?php echo $empenho['contrapartida_recurso'];?></td></tr>
                                <tr><td width="210px" class="tab-title"><b>Empenho: </b></td><td class="tab-detail"><?php echo substr($empenho['numero_empenho'], 6, 7) ;?></td></tr>
                                <tr><td width="210px" class="tab-title"><b>Data do Empenho: </b></td><td class="tab-detail"><?php echo formatDateToPHP($empenho['data_empenho']);?></td></tr>
                                <tr><td width="210px" class="tab-title"><b>Valor Empenhado: </b></td><td class="tab-detail">R$<?php echo number_format($empenho['valor_empenho'], 2, ',', '.');?></td></tr>
                                <tr><td width="210px" class="tab-title"><b>Valor Estornado do Empenho: </b></td><td class="tab-detail">R$<?php echo number_format($empenho['estornado'], 2, ',', '.');?>    </td></tr>
                                <tr><td width="210px" class="tab-title"><b>Saldo a Pagar: </b></td><td class="tab-detail">R$<?php echo number_format(($empenho['valor_empenho'] - $empenho['estornado'] - $empenho['pago']), 2, ',', '.'); ?></td></tr>
                                <tr><td width="210px" class="tab-title"><b>Credor: </b></td><td class="tab-detail"><?php echo $empenho['Credor']['nome_credor'];?></td></tr>
                                <tr><td width="210px" class="tab-title"><b>Histórico: </b></td><td class="tab-detail"><?php echo $empenho['historico_empenho'];?></td></tr>
                                <tr><td width="210px" class="tab-title"><b>Modalidade: </b></td><td class="tab-detail"><?php echo $empenho['modalidade_licitacao'];?></td></tr>
                                <?php if(trim($empenho['numero_licitacao']) != ''){ ?>
                                    <tr align="right" class="tab-title"><td class="tab-detail"><b>Nro licitação/Compra: </b></td><td><?php echo $empenho['numero_licitacao'];?></td></tr>
                                    <tr align="right" class="tab-title"><td class="tab-detail"><b>Ano licitação/Compra: </b></td><td><?php echo $empenho['ano_licitacao'];?></td></tr>
                                <?php } ?>
                            </table>
                        </div>

                        <div id="tabs-2" class="tab-pane tabs-2-<?php echo $i ?>">

                        </div>

                        <div id="tabs-3" class="tab-pane tabs-3-<?php echo $i ?>">

                        </div>
                    </div>
                </div>
			</td>
		</tr>
	<?php $i++; } ?>
	</tbody>	
</table>
</div>

<?php Load::snippet('pager', $snippet); ?>