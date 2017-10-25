<!DOCTYPE html>
<html>
<head>
	<title>Urbem - Transparência</title>
	<meta name="title" content="Urbem - Transparência"/>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />

	<?php Plugin::load('jquery'); ?>	

	<link href="<?php echo url('templates/default/css/template.css') ?>" rel="stylesheet" type="text/css" />
	
	<!-- Carrega plugins utilizados em todo o site -->
	<?php Plugin::load('jquery'); ?>
	<?php Plugin::load('shadowbox'); ?>
	
	<!-- Carrega arquivos JS do template -->	
	<script src="<?php echo url('templates/default/js/template.js') ?>" type="application/javascript"></script>
</head>
<body id="blank">
	<?php echo $main ?>

	<script type="application/javascript">
	
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-40713250-1']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
		var ga = document.createElement('script'); ga.type = 'application/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>
</body>
</html>