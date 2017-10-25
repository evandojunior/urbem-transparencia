<div class="paginacao">
	Total de <?php echo $pager->getNumPages(); ?> páginas | <b>Ir para </b>
	<select name="selecionarPagina" id="selecionarPagina">
	<?php 
		for($i=1; $i<=$pager->getNumPages(); $i++){
			if($_REQUEST['page']==$i){
				echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
			}else{
				echo '<option value="'.$i.'">'.$i.'</option>';
			}
		}
	?>
</select>
</div>