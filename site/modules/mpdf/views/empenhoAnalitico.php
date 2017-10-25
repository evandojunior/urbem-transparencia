<div class="relatorio">

<div class="titulo">Detalhes do empenho</div>
<table cellspacing="1" width="100%" style="font-size:8pt;">
	<tr class="destaque"><td width="130px">Empenho:</td><td><?php echo substr($empenho['numero_empenho'], 6, 7) ?></td></tr>
	<tr><td width="130px">Data do Empenho:</td><td><?php echo formatDateToPHP($empenho['data_empenho']); ?></td></tr>
	<tr><td width="130px">Credor:</td><td><?php echo utf8_encode($empenho['Credor']['nome_credor']); ?></td></tr>
	<tr><td width="130px">Empenhado:</td><td>R$<?php echo number_format($empenho['valor_empenho'], 2, ',', '.'); ?></td></tr>
	<tr><td width="130px">Estornado:</td><td>R$<?php echo number_format($empenho['estornado'], 2, ',', '.'); ?></td></tr>
	<tr><td width="130px">Liquidado:</td><td>R$<?php echo number_format($empenho['liquidado'], 2, ',', '.'); ?></td></tr>
	<tr><td width="130px">Pago:</td><td>R$<?php echo number_format($empenho['pago'], 2, ',', '.'); ?></td></tr>
	<tr><td width="130px">Saldo a Pagar:</td><td>R$<?php echo number_format(($empenho['valor_empenho'] - $empenho['estornado'] - $empenho['pago']), 2, ',', '.'); ?></td></tr>
	
	<tr><td width="130px">Entidade:</td><td><?php echo utf8_encode($empenho['Entidade']['nome_entidade']);?></td></tr>
	<tr><td width="130px">Orgão:</td><td><?php echo utf8_encode($empenho['Orgao']['nome_orgao']);?></td></tr>
	<tr><td width="130px">Unidade:</td><td><?php echo utf8_encode($empenho['Unidade']['nome_unidade']);?></td></tr>
	<tr><td width="130px">Função:</td><td><?php echo utf8_encode($empenho['Funcao']['nome_funcao']);?></td></tr>
	<tr><td width="130px">Subfunção:</td><td><?php echo utf8_encode($empenho['Subfuncao']['nome_subfuncao']);?></td></tr>
	<tr><td width="130px">Programa:</td><td><?php echo utf8_encode($empenho['Programa']['nome_programa']);?></td></tr>
	<tr><td width="130px">Proj/Ativ/Op:</b></td><td><?php echo utf8_encode($empenho['Acao']['nome_projeto']);?></td></tr>
	<tr><td width="130px">Categoria:</td><td><?php echo utf8_encode($empenho['categoria']);?></td></tr>
	<tr><td width="130px">Natureza:</td><td><?php echo utf8_encode($empenho['natureza']);?></td></tr>
	<tr><td width="130px">Rubrica:</td><td><?php echo utf8_encode($empenho['Rubrica']['especificacao_rubrica_despesa']);?></td></tr>
	<tr><td width="130px">Recurso:</td><td><?php echo utf8_encode($empenho['Recurso']['nome_recurso']);?></td></tr>
	<tr><td width="130px">Contrapartida:</td><td><?php echo utf8_encode($empenho['contrapartida_recurso']);?></td></tr>
	<tr><td width="130px">Credor:</td><td><?php echo utf8_encode($empenho['Credor']['nome_credor']);?></td></tr>
	<tr><td width="130px">HistÃ³rico:</td><td><?php echo utf8_encode($empenho['historico_empenho']);?></td></tr>
	<tr><td width="130px">Modalidade:</td><td><?php echo utf8_encode($empenho['modalidade_licitacao']);?></td></tr>
	<?php if(trim($empenho['numero_licitacao']) != ''){ ?>                                
		<tr align="right" class="destaque"><td width="130px">Nro licitação/Compra:</td><td><?php echo utf8_encode($empenho['numero_licitacao']);?></td></tr>
		<tr align="right" class="destaque"><td width="130px">Ano licitação/Compra:</td><td><?php echo utf8_encode($empenho['ano_licitacao']);?></td></tr>
	<?php } ?>
</table>
</div>

<div class="titulo">Itens do empenho</div>
<table width="100%" cellspacing="1" style="font-size:10pt;">
    <thead>
        <tr class="destaque">
            <td width="110px" align="center">Nro. Item</td>
            <td width="560px" align="left">Descrição</td>
            <td width="110px" align="right">Unidade</td>
            <td width="110px" align="right">Quantidade</td>
            <td width="110px" align="right">Valor Total (R$)</td>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($itens as $item) { ?>
        <tr>
            <td align="center"><?php echo  $item->numero_item ?></td>
            <td align="left"><?php echo utf8_encode($item->descricao); ?></td>
            <td align="right"><?php echo utf8_encode($item->unidade); ?></td>
            <td align="right"><?php echo $item->quantidade; ?></td>
            <td align="right"><?php echo number_format($item->valor, 2, ',', '.'); ?></td>
        </tr>
    <?php }  ?>
    </tbody>	
</table>

<div class="titulo">HistÃ³rico do empenho</div>
<table width="100%" cellspacing="1" style="font-size:10pt;">
    <thead>
        <tr class="destaque">
            <td width="110px" align="left">Tipo</td>
            <td width="90px" align="center">Data</td>
            <td width="690px" align="left">HistÃ³rico</td>
            <td width="110px" align="right">Valor (R$)</td>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach ($historicos as $historico) {
            if($historico['sinal_valor'] == '-'){
                switch($historico['tipo']){
                    case 'Empenho':
                        $historico['tipo'] = utf8_decode('Anulação de Empenho');
                    break;    

                    case 'Liquidação':
                        $historico['tipo'] = utf8_decode('Estorno de Liquidação');
                    break;
                
                    case 'Pagamento':
                        $historico['tipo'] = utf8_decode('Estorno de Pagamento');
                    break;
                }
			} 
    ?>
	<tr>
		<td align="left"><?php echo  utf8_encode($historico['tipo']); ?></td>
		<td align="center"><?php echo formatDateToPHP($historico['data']); ?></td>
		<td align="left"><?php echo utf8_encode($historico['historico']); ?></td>
		<td align="right"><?php echo $historico['sinal_valor'] == '+' ? '':'-'; ?><?php echo number_format($historico['valor'], 2, ',', '.'); ?></td>
	</tr>
    <?php }  ?>
    </tbody>	
</table>
</div>