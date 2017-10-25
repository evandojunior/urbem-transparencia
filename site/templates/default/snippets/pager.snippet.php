<!-- <div class="paginacao">
	Total de <?php echo $pager->getNumPages(); ?> páginas | <b>Ir para </b>
	<select name="selecionarPagina" id="selecionarPagina">
	<?php 
		/*for($i=1; $i<=$pager->getNumPages(); $i++){
			if($_REQUEST['page']==$i){
				echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
			}else{
				echo '<option value="'.$i.'">'.$i.'</option>';
			}
		} */
	?>
</select>
</div> -->

<?php

//if(($pager instanceof Doctrine_Pager)){

    Load::_require("core/pagination/Pagination.php");
    
    //current page
    $current_page = ((isset($_GET['page']))?$_GET['page']:1);
    
    //records per page
    $records = $pager->getMaxPerPage();
    
    //results total
    $total = $pager->getNumResults();
    
    $pagination = new Pagination($current_page,$records);
    
    //total, paging type, total markers
    //type markers ("google","yahoo","jumping","simple")
    $pages = $pagination->CreatePages($total,"google",5);
    
    //item used only in consultation with the bank, it is not mandatory to use it
    $start = $pagination->_start;
    
    //all pages return array
    $all= $pagination->_arrayPages;
    //markers
    $markers = $pagination->_indexes;
    $last = $pagination->_totalPages;
    $prev = $pagination->_previousPage;
    $next = $pagination->_nextPage;
    
    $go = $pagination->Go(20);
    $back = $pagination->Back(20);
    ?>
    
    <div id="paginacao">
        <a href="#" title="1">Início</a>
        <a href="#" title="<?php echo $prev; ?>">Anterior</a>
        <!-- <a href="#" title="<?php echo $back; ?>">Retornar 20</a> -->
        
        <?php foreach($markers as $num) { ?>
            <a href="#" title="<?php echo $num; ?>"><?php echo $num; ?></a>
        <?php } ?>
        
        <a href="#" title="<?php echo $next; ?>">Próximo</a>
        <!-- <a href="#" title="<?php echo $go; ?>">Avançar 20</a> -->
        <a href="#" title="<?php echo $last; ?>">Último</a>
    </div>
    
<?php
  //  }
?>