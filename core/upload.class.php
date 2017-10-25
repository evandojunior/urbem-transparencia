<?php

/*********************************************************************
 * Criada por Vinícius
 * op.vini@gmail.com ou www.isecretaria.net/vinicius
 **********************************************************************/

class Upload{

	public $maxSize; // 8 MB, tamanho máximo permitido
	public $overwrite; // 0 NÃO, 1 SIM, sobrescreve o arquivo se ele existir
	public $allowBlank; // 0 NÃO, 1 SIM, deixar campo em branco
	public $blockExt; // 0 NÃO, 1 SIM, bloquear extensões de arrExtBlocks
	public $liberaExt; // 0 NÃO, 1 SIM, liberar apenas extensões de extensions

	public $extensions;
	public $arrExtBlocks = array(); // array com extensões que não são permitidas blockExt deve ser 1

	public $file;
	public $filename;
	public $fileTmp;
	public $fileSize;
	protected $dir;

	public $error;

	protected $cfg;
	protected $ext;
	protected $arq;
	protected $i;

	public function __construct(){
		#Parâmetros "default" para upload
		$this->dir = $GLOBALS['TMP_DIR'];
		$this->maxSize = 8000;
		$this->overwrite = 0;
		$this->allowBlank = 1;
		$this->blockExt = 0;
		$this->liberaExt = 1;
		$this->extensions = array('mp3', 'pdf', 'doc', 'jpg', 
								'png', 'gif', 'mov', 'avi', 
								'mpg', 'xls', 'txt', 'pps', 
								'ppt','odt');
	}
	
	public function getFile(){
	    return $this->file;
	}

	public function setFile($file){
	    $this->file = $file;
	}
	
	public function getFilename(){
	    return $this->filename;
	}	

	public function getFileTmp(){
	    return $this->fileTmp;
	}

	public function setFileTmp($fileTmp){
	    $this->fileTmp = $fileTmp;
	}

	public function getMaxSize(){
	    return $this->maxSize;
	}

	public function setMaxSize($maxSize){
		if(($maxSize != '')&&($maxSize != null)){
	    	$this->maxSize = $maxSize;
		}
	}

	public function getDir(){
	    return $this->dir;
	}

	public function setDir($dir){
		if(($dir != '')&&($dir != null)){
	    	$this->dir = $dir;
		}
	}

	public function getExtensions(){
	    return $this->extensions;
	}

	public function setExtensions($extensions){
	    $this->extensions = $extensions;
	}

	public function send($criptFilename=true){
		#teste se existe o arquivo
		if($this->file['name'] == ''){
			return;
		}
		
		$this->fileSize = $this->file['size'];
		$this->fileTmp  = $this->file['tmp_name'];
		if($criptFilename){
			$this->filename = $this->criptFilename($this->file);
		} else{
			$this->filename = $this->file['name'];			
		}
		
		if($this->filename != ""){
			$this->cfg = explode(".", $this->filename);
			$this->ext = $this->cfg[ count($this->cfg)-1 ];
			$this->arq = str_replace(".".$this->ext, "", $this->filename);
		} else {
			return false;
		}

		if(!$this->allowBlank && $this->filename == ""){
			$this->error .= "Upload Error 1: não foi passado nenhum arquivo.";
		}

		if(($this->fileSize / 1024) > $this->maxSize ){
			$kb = ($this->maxSize);
			$kb2 = number_format(($this->fileSize / 1024), '0', '.', '');
			$this->error .= "Upload Error 2: O arquivo é maior que ".$kb."KB que é o máximo permitido => (".$kb2."KB)";
		}

		if( $this->blockExt && in_array(strtolower($this->ext),$this->arrExtBlocks) && $this->filename != "" ){
			$this->error .= "Upload Error 5: A extensão '".$this->ext."' não é permitida.";
		}

		if( $this->liberaExt && !in_array(strtolower($this->ext),$this->extensions) && $this->filename != "" ){
			$this->error .= "Upload Error 6: A extensão '".$this->ext."' não é permitida.";
		}

		if($this->filename != "" && $this->dir != "" && !$this->overwrite){
			if(file_exists( $this->dir.$this->filename )){
				$this->i = 2;
				while( file_exists($this->dir.$this->arq."(".$this->i.").".$this->ext)){
					$this->i = $this->i + 1;
				}
				$this->filename = $this->arq."(".$this->i.").".$this->ext;
			}
		}

		else if( $this->dir == "" ){
			$this->error .= "Upload Error 3: Não foi encontrado um dir.";
		}

		if(isset($this->error)){
			throw new Exception($this->error);
		} else if($this->filename == "" && $this->allowBlank){
			return true;
		} else {
			if(!move_uploaded_file( $this->fileTmp, $this->dir.$this->filename )){
				$this->error .= "Upload Error 4: Houve um erro no upload.".$this->fileTmp;
				throw new Exception($this->error);
			} else {
				return true;
			}
		}
	}

	public function criptFilename($file){
		if(is_array($file)){
			if($file['name'] != ''){
				$extensao = substr($file['name'], -4);//PEGA O PONTO E A EXTENSÃO DO ARQUIVO
				$name = md5($file['name'].session_id().rand(0,1000000).date('ymdhis')).$extensao;
			}
		}else{
			if($file != ''){
				$extensao = substr($file, -4);//PEGA O PONTO E A EXTENSÃO DO ARQUIVO
				$name = md5($file.session_id().rand(0,1000000).date('ymdhis')).$extensao;
			}
		}
		
		return $name;
	}
}

?>
