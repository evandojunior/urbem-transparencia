<?php Plugin::load('jmask'); ?>

<script type="application/javascript">
$(document).ready(function(){
	$("a#show_remember_pass").click(function(){
		$("div#remember_pass").toggle("fast");
	});
});
</script>

<div class="auth">

<?php Load::snippet('header', $snippet); ?>
<form name="formusuario" id="formusuario" method="POST" action="<?php echo url('login'); ?>">

<div class="form-element"><label class="form-label"><?php echo $form->getFields('email')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('email')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('email')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('senha')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('senha')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('senha')->render(); ?></div>
</div>

<div class="auth-buttons">
	<label class="button"><input type="submit" name="send" id="send" value="Entrar" style="width: 180px;"/></label>
	<div class="esqueci-senha"><a href="#" id="show_remember_pass"/>Esqueceu a senha</a></div>
</div>
</form>

<div id="remember_pass">
Entre em contato com o administrador através do e-mail <b><?php echo MAIL_ADMINISTRATOR ?></b>
</div>

</div>


