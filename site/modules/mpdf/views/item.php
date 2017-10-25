<div class="lista_item">
<table width="100%" cellspacing="1">
    <thead>
        <tr style="background: #E4E4E4;">
            <td width="70px" align="center">Nro. Item</td>
            <td width="560px" align="left">Descri&ccedil;&atilde;o</td>
            <td width="100px" align="right">Unidade</td>
            <td width="100px" align="right">Quantidade</td>
            <td width="130px" align="right">Valor Total (R$)</td>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($itens as $item) { ?>
        <tr style="background: #F4F4F4;">
            <td align="center"><?php echo  $item->numero_item ?></td>
            <td align="left"><?php echo $item->descricao; ?></td>
            <td align="right"><?php echo $item->unidade; ?></td>
            <td align="right"><?php echo $item->quantidade; ?></td>
            <td align="right"><?php echo number_format($item->valor, 2, ',', '.'); ?></td>
        </tr>
    <?php }  ?>
    </tbody>	
</table>
</div>
