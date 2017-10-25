<div class="messages">
	<ul class="messages">
		<?php Message::getInstance()->showMessages(); ?>
	</ul>
</div>

<script type="application/javascript">
$(document).ready(function(){
	if($("ul.messages").text() != ""){
		$("div.messages").fadeIn('slow');
	}
});	
</script>