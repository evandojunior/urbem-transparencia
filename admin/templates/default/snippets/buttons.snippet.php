<?php if($acao == 'new'){ ?>
<div class="buttons">
	<!-- <label class="button"><input type="submit" name="saveContinue" id="submit_new" value="Salvar e cadastrar novo" /></label>  --> 
	<label class="button"><input type="submit" name="save" id="submit" value="Salvar" class="save" /></label> 
	<label class="button"><input type="button" name="cancel" id="cancel" value="Cancelar" class="cancel" /></label>
</div>

<?php }elseif($acao == 'edit'){ ?>
<div class="buttons">
	<!-- <label class="button"><input type="submit" name="saveContinue" id="submit_new" value="Salvar e continuar editado" /></label>  -->
	<label class="button"><input type="submit" name="save" id="submit" value="Salvar" class="save" /></label> 
	<label class="button"><input type="button" name="cancel" id="cancel" value="Cancelar" class="cancel" /></label>
</div>

<?php }else{ ?>
<div class="buttons">
	<label class="button"><input type="submit" name="save" id="submit" value="Salvar" class="save"/></label> 
</div>
<?php } ?>