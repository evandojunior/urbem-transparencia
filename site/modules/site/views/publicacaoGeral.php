<style>
#paginacao {
    float: left;
    width: 100% !important;
}
</style>

<script type="application/javascript">
$(document).ready(function(){
	$("form#pesquisa").submit(function(){
		var secao     = $("select#id_secao").val();
		var exercicio = $("select#id_exercicio").val();
		mensagem 	  = "";
		
		url = "?exercicio="+exercicio;
		
		if (secao != undefined){
			url+="&secao="+secao;
		}		
		
		window.location  = url;
		return false;
	});
});	
</script>

<div class="publicacao">
    <h3 class="publicacao_municipio">Município</h3>
    <h2 class="publicacao_municipio_nome"><?php echo Sessao::get('municipio_nome'); ?> / <?php echo Sessao::get('uf_sigla'); ?></h2>
    
	<form name="pesquisa" id="pesquisa" method="get" action="">
		<div class="form-element-pesquisa">
		<div class="form-error"><?php echo $formPesquisa->getFields('secao')->getError(); ?></div>
		<div class="form-input-text"><?php echo $formPesquisa->getFields('secao')->render(); ?></div>
		</div>		
		
		<div class="form-element-pesquisa">
		<div class="form-error"><?php echo $formPesquisa->getFields('exercicio')->getError(); ?></div>
		<div class="form-input-text"><?php echo $formPesquisa->getFields('exercicio')->render(); ?></div>
		</div>
		
		<div class="form-element-pesquisa">
		<div class="submitFormPesquisa">
			<label class="button"><input type="submit" name="pesquisa" id="pesquisa" value="pesquisa"/></label>
		</div>
		</div>
	</form>	
	
    <div class="lista_publicacao" style="margin-top: 5px;">
        <table width="100%" cellspacing="1">
            <thead>
                <tr>
                    <td colspan="4">Publicações Gerais</td>
                </tr>
                <tr>
                    <td width="15%">Tipo de Publicação</td>
                    <td width="60%">Descrição</td>
                    <td width="15%">Data de Publicação</td>
                    <td width="10%" align="center">Download</td>
                </tr>                
            </thead>
            <tbody>
                <?php
					if(count($publicacoes) > 0){
						foreach($publicacoes as $publicacao){
				?>
                <tr>
                    <td><?php echo $publicacao->getSecao()->getSecao(); ?></td>
                    <td>
                        <a href="#" class="more" title="more"><img src="<?php echo url('templates/default/img/read_more.png?20170801') ?>" width="15"/>
                        <?php echo $publicacao->getDescricao(); ?></a>
                        <div class="hide"><?php echo $publicacao->getDetalhamento() ?></div>
                    </td>
                    <td><?php echo $publicacao->getCreated(); ?></td>
                    <td align="center"><a href="<?php echo $GLOBALS['BASE_URL'].'uploads/'.Sessao::get('municipio_hash').'/publicacao/'.$publicacao->getArquivo() ?>" class="download" target="_blank">&nbsp;</a></td>
                </tr>
                <?php
						} # Fim do foreach
					} else {
						echo '<tr><td colspan="4">A consulta não retornou nenhum item.</td></tr>';
					} # Fim do else
				?>
            </tbody>	
		</table>
	</div>
</div>

<?php Load::snippet('pager', $snippet); ?>