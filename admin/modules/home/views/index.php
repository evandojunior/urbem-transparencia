<?php Load::snippet('header', $snippet); ?>

<div>
    Em caso de dúvidas consulte o manual do sistema, o arquivo está disponível através do link  
    <a href="<?php echo $manualArquivo ?>" title="Manual de utilização" target="_blank" style="color: #000;"><b>Download do Manual</b></a>.
</div>

<div class="lista">
    <ul class="ultimos">
        <?php foreach($publicacoes as $publicacao){ ?>
        <li><a href="<?php echo url('publicacao/'.$publicacao->getId()) ?>"><em><?php echo $publicacao->getCreated(); ?></em> - <b><?php echo $publicacao->getSecao()->getSecao(); ?></b> / <?php echo $publicacao->getDescricao(); ?></a></li>
        <?php } ?>
    </ul>
</div>