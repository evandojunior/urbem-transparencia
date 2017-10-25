<?php
	include 'C:/wamp/www/projetonovo/app/core/upload.class.php';
	if($_POST){
		$upload = new Upload();
		$upload->setdestination('tmp/');
		$upload->setFile($_FILES['foto']);
		$upload->send();
		$file = $upload->getFileName();
		header('Location: crop.php?foto='.$file);
	}
?>

<form name="form" action="" method="POST" enctype="multipart/form-data">
Foto: <input type="file" name="foto" /><input type="submit" name="submit" />
</form>