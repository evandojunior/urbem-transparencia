<style>
#paginacao {
    float: left;
    width: 100% !important;
}
</style>

<div class="publicacao">
    <h3 class="publicacao_municipio">Município</h3>
    <h2 class="publicacao_municipio_nome"><?php echo Sessao::get('municipio_nome'); ?> / <?php echo Sessao::get('uf_sigla'); ?></h2>
    
    <div class="lista_publicacao">

        <table width="100%" cellspacing="1">
            <thead>
                <tr>
                    <td colspan="4">
                        <?php if (gettype($secao) == 'object') { ?>
                            <?php echo ucfirst($secao->getSecao()) ?> / Publicações
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td width="60%">Descrição</td>
                    <td width="10%" align="center">Data de Publicação</td>
                    <td width="5%" align="center">Download</td>
                </tr>                
            </thead>

            <tbody>
                <?php
					if (count($publicacoes) > 0){
						foreach($publicacoes as $publicacao){
				?>
                <tr>

                    <td>
                        <a href="#" class="more" title="more"><img src="<?php echo url('templates/default/img/read_more.png?20170801') ?>" width="15"/>
                        <?php echo $publicacao->getDescricao(); ?></a>
                        <div class="hide"><?php echo $publicacao->getDetalhamento() ?></div>
                    </td>
                    <td align="center"><?php echo $publicacao->getCreated(); ?></td>
                    <td align="center"><a href="<?php echo $GLOBALS['BASE_URL'].'uploads/'.Sessao::get('municipio_hash').'/publicacao/'.$publicacao->getArquivo() ?>" class="download" target="_blank">&nbsp;</a></td>
                </tr>
                <?php
						} 
					} else {
						echo '<tr><td colspan="4">A consulta não retornou nenhum item.</td></tr>';
					}
				?>
            </tbody>	
		</table>
	</div>
</div>

<?php Load::snippet('pager', $snippet); ?>