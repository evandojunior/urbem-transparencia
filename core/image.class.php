<?php

class Image{

	public static function crop($path, $dest, $width, $height, $x, $y, $w, $h){
		$image_p = imagecreatetruecolor($width, $height);
		$image = imagecreatefromjpeg($path);

		imagecopyresampled($image_p, $image, 0, 0, $x, $y, $width, $height, $w, $h);

		$filename = basename($dest);
		$dirname  = dirname($dest);
		
		/*
		 * Se já existir thumbs, deleta antigo
		 */
		if(file_exists($dirname.'/thumbs/'.$filename)){
			unlink($dirname.'/thumbs/'.$filename);
		}

		/*
		 * Sempre que quiser se gerar thumbnails deve-se criar o diretório thumbs dentro do destino da foto
		 */
		imagejpeg($image_p, $dirname.'/thumbs/'.$filename, 85);
	}

	public static function thumbnail($path, $dest, $new_width=180, $new_height=135){
		/*
		 * Pegando as dimensoes reais da imagem, largura e altura
		 */
		list($width, $height) = getimagesize($path);

		/*
		 * Gerando a a miniatura da imagem
		 */
		$image_p = imagecreatetruecolor($new_width, $new_height);
		$image = imagecreatefromjpeg($path);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

		$filename = basename($dest);
		$dirname = dirname($dest);

		/*
		 * Sempre que quiser se gerar thumbnails deve-se criar o diretório thumbs dentro do destino da foto
		 */
		imagejpeg($image_p, $dirname.'/thumbs/'.$filename, 85);
		imagedestroy($image_p);
	}

	public static function resize($path, $dest, $new_width=640, $new_height=640){
		/*
		 * Pegando as dimensões reais da imagem, largura e altura
		 */
		list($width, $height) = getimagesize($path);
		
		$ratio = Image::ratio($path);
		
		/*
		 * Caso as imagens a serem redimensionadas sejam menores que os novos tamanhos, manten-se tamanho original
		 */
		if($width > $height){
			if($width < $new_width){
				$new_width = $width;
			}
			$new_height = floor($new_width/$ratio);
		} else {
			if($height < $new_height){
				$new_height = $height;
			}
			$new_width = floor($new_height*$ratio);						
		}

		$image_p = imagecreatetruecolor($new_width, $new_height);
		$image = imagecreatefromjpeg($path);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

		$filename = basename($dest);
		$dirname = dirname($dest);
		
		imagejpeg($image_p, $dest, 85);
		imagedestroy($image_p);
	}

	public static function ratio($path){
		list($width, $height, $type, $attr) = getimagesize($path);
		$ratio = $width/$height;
		$ratio = number_format($ratio, 3, '.', '');
		return $ratio;
	}
	
	public static function copy($path, $dest){
		copy($path, $dest);
	}
}

?>