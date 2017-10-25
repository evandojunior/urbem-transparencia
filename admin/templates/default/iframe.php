<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<?php 
	Plugin::load('jquery');
	Plugin::load('jprettyPhoto');
?>	
	<link href="<?php echo url('templates/default/css/template.css') ?>" rel="stylesheet" type="text/css" />
	<script src="<?php echo url('templates/default/js/template.js') ?>" language="javascript" type="application/javascript"></script>
</head>
<body>
	<div class="messages-iframe">
		<ul>
		<?php 
			$messages = Message::getInstance()->getSuccessMessages();
			if(count($messages)>0){
				foreach($messages as $message){ echo '<li class="success-message">'.$message.'</li>'; }
			}
		
			$messages = Message::getInstance()->getErrorMessages();
			if(count($messages)>0){
				foreach($messages as $message){ echo '<li class="error-message">'.$message.'</li>'; }
			}			
		?>
		</ul>
	</div>
	<div class="conteudo-iframe"><?php echo $main ?></div>
	
	<input type="hidden" id="module" value="<?php echo $_REQUEST['module'] ?>"/>
	<input type="hidden" id="action" value="<?php echo $_REQUEST['action'] ?>"/>
	<input type="hidden" id="id" value="<?php echo @$_REQUEST['id'] ?>"/>
</body>


</html>
