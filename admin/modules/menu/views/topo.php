<div id="menu"> 
	<ul class="menu">
	<?php 
		$submenus = $menus;
		foreach($menus as $menu){ 
			if($menu->getParentId() == 0){		
	?>
		<li class="menu">
		    <?php if($menu->getURL() != '#'){ ?>
	            <a href="<?php echo $GLOBALS['BASE_URL'].$menu->getURL(); ?>" target="<?php echo $menu->getTarget(); ?>"><?php echo $menu->getLabel(); ?></a>
	        <?php }else{ ?>
	            <a href="<?php echo $menu->getURL(); ?>" target="<?php echo $menu->getTarget(); ?>"><?php echo $menu->getLabel(); ?></a>
	        <?php } ?>
	        <ul class="submenu">
	<?php 
			foreach($submenus as $submenu){
				if($submenu->getParentId() == $menu->getId()){
	?>
	        	<li class="submenu"><a href="<?php echo $GLOBALS['BASE_URL'].$submenu->getURL(); ?>" target="<?php echo $submenu->getTarget(); ?>"><?php echo $submenu->getLabel(); ?></a></li>
	<?php }} ?>
		</ul>
	    </li>
	<?php }} ?>
	</ul>
</div>