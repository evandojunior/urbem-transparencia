<?php

/**
 * Jcrop image cropping plugin for jQuery
 * Example cropping script
 * @copyright 2008 Kelly Hallman
 * More info: http://deepliquid.com/content/Jcrop_Implementation_Theory.html
 */

if($_POST){
	$width = 250;
	$height = 250;
	$jpegQuality = 90;

	$src = 'tmp/'.$_REQUEST['foto'];
	$img_r = imagecreatefromjpeg($src);
	$destino = imagecreatetruecolor($width, $height);

	imagecopyresampled($destino,
						$img_r,
						0,
						0,
						$_POST['x'],
						$_POST['y'],
						$width, 
						$height, 
						$_POST['w'], 
						$_POST['h']);

	header('Content-type: image/jpeg');
	imagejpeg($destino, null,$jpegQuality);

	exit;
}

// If not a POST request, display page below:

?><html>
	<head>

		<script src="../js/jquery.min.js"></script>
		<script src="../js/jcrop.js"></script>
		<link rel="stylesheet" href="../css/jcrop.css" type="text/css" />
		<link rel="stylesheet" href="demo_files/demos.css" type="text/css" />

		<script language="Javascript">

			$(function(){
				$('#cropbox').Jcrop({
					aspectRatio: 1,
					onSelect: updateCoords
				});
			});

			function updateCoords(c){
				$('#x').val(c.x);
				$('#y').val(c.y);
				$('#w').val(c.w);
				$('#h').val(c.h);
			};

			function checkCoords(){
				if (parseInt($('#w').val())) return true;
				alert('Please select a crop region then press submit.');
				return false;
			};

		</script>

	</head>

	<body>

	<div id="outer">
	<div class="jcExample">
	<div class="article">

		<h1>Jcrop - Crop Behavior</h1>

		<!-- This is the image we're attaching Jcrop to -->
		<img src="tmp/<?php echo $_REQUEST['foto'] ?>" id="cropbox" />

		<!-- This is the form that our event handler fills -->
		<form action="crop.php?foto=<?php echo $_REQUEST['foto'] ?>" method="post" onsubmit="return checkCoords();">
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
			<input type="submit" value="Crop Image" />
		</form>

		<p>
			<b>An example server-side crop script.</b> Hidden form values
			are set when a selection is made. If you press the <i>Crop Image</i>
			button, the form will be submitted and a 150x150 thumbnail will be
			dumped to the browser. Try it!
		</p>

		<div id="dl_links">
			<a href="http://deepliquid.com/content/Jcrop.html">Jcrop Home</a> |
			<a href="http://deepliquid.com/content/Jcrop_Manual.html">Manual (Docs)</a>
		</div>


	</div>
	</div>
	</div>
	</body>

</html>
