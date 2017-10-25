<div class="lista_item">
<table width="100%" cellspacing="1">
    <thead>
        <tr style="background: #E4E4E4;">
            <td width="130px" align="left">Tipo</td>
            <td width="90px" align="center">Data</td>
            <td width="610px" align="left">Hist&oacute;rico</td>
            <td width="130px" align="right">Valor (R$)</td>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach ($historicos as $historico) {
            
            if($historico['sinal_valor'] == '-'){
                switch($historico['tipo']){
                    case 'Empenho':
                        $historico['tipo'] = 'Anulação de Empenho';
                    break;    

                    case 'Liquidação':
                        $historico['tipo'] = 'Estorno de Liquidação';
                    break;
                
                    case 'Pagamento':
                        $historico['tipo'] = 'Estorno de Pagamento';
                    break;
                }
            }
    ?>
        <tr style="background: #F4F4F4;">
            <td align="left"><?php echo  $historico['tipo']; ?></td>
            <td align="center"><?php echo formatDateToPHP($historico['data']); ?></td>
            <td align="left"><?php echo $historico['historico']; ?></td>
            <td align="right"><?php echo $historico['sinal_valor'] == '+' ? '':'-'; ?><?php echo number_format($historico['valor'], 2, ',', '.'); ?></td>
        </tr>
    <?php }  ?>
    </tbody>	
</table>
</div>
