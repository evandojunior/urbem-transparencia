<?php Load::snippet('header', $snippet); ?>

<div class="show">
	<span class="campo">ID:</span> <span class="valor"><?php echo $historico->getId(); ?></span>
	<span class="campo">Usuário:</span> <span class="valor"><?php echo $historico->getPessoa()->getNome(); ?></span>
	<span class="campo">Descrição:</span><span class="valor"><?php echo $historico->getDescricao(); ?></span>
	<span class="campo">Criado em:</span><span class="valor"><?php echo $historico->getCreatedAt(); ?></span>
</div>