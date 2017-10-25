<?php 

class Plugin{

	public static function load($plugin, $args=null){
		switch($plugin){
			case 'jmask':
//				echo '<script type="application/javascript" src="'.$GLOBALS['BASE_URL'].'plugins/js/jmask/jmask.js"></script>';
				echo '<script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>';
			break;	

			case 'jquery':
//				echo '<script type="application/javascript" src="'.$GLOBALS['BASE_URL'].'plugins/js/jquery/jquery.js"></script>';
				echo '<script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>';
			break;	
			
			case 'jhistory':
				echo '<script type="application/javascript" src="'.$GLOBALS['BASE_URL'].'plugins/js/jhistory/jhistory.js"></script>';
			break;		
			
			case 'jselectboxes':
				echo '<script type="application/javascript" src="'.$GLOBALS['BASE_URL'].'plugins/js/jselectboxes/jselectboxes.js"></script>';
			break;	

			case 'jtooltip':
				echo '<script type="application/javascript" src="'.$GLOBALS['BASE_URL'].'plugins/js/jtooltip/jtooltip.js"></script>';
				echo '<link type="text/css" rel="stylesheet" href="'.$GLOBALS['BASE_URL'].'plugins/js/jtooltip/jtooltip.css" />';
			break;			
			
			case 'jcrop':
				echo '<link type="text/css" rel="stylesheet" href="'.$GLOBALS['BASE_URL'].'plugins/js/jcrop/css/jcrop.css" media="screen"/>';
				echo '<script type="application/javascript" src="'.$GLOBALS['BASE_URL'].'plugins/js/jcrop/js/jcrop.min.js"></script>';
			break;			

			case 'jprettyPhoto':
				echo '<link rel="stylesheet" href="'.$GLOBALS['BASE_URL'].'plugins/js/prettyphoto/css/prettyPhoto.css" type="text/css" media="screen"/>';
				echo '<script src="'.$GLOBALS['BASE_URL'].'plugins/js/prettyphoto/js/jquery.prettyPhoto.js" type="application/javascript"></script>';
			break;
			
			case 'fontCandara':
				if(getSO() == 'linux'){
					echo '<link rel="stylesheet" href="'.$GLOBALS['BASE_SITE_URL'].'templates/default/css/_candara.css" type="text/css" media="screen"/>';
				}
			break;

			case 'cssChrome':
				if(getBrowser() == 'chrome' || getBrowser() == 'safari'){
					echo '<link rel="stylesheet" href="'.$GLOBALS['BASE_SITE_URL'].'templates/default/css/_chrome.css" type="text/css" media="screen"/>';
				}
			break;		
			
			case 'shadowbox':
				echo '<link rel="stylesheet" href="'.$GLOBALS['BASE_URL'].'plugins/js/shadowbox/shadowbox.css" type="text/css" media="screen"/>';
				echo '<script src="'.$GLOBALS['BASE_URL'].'plugins/js/shadowbox/shadowbox.js" type="application/javascript"></script>';
			break;			
			
			case 'autocomplete':
				echo '<script type="application/javascript" src="'.$GLOBALS['BASE_URL'].'plugins/js/autocomplete/jquery.autocomplete.min.js"></script>';
				echo '<link rel="stylesheet" type="text/css" href="'.$GLOBALS['BASE_URL'].'plugins/js/autocomplete/jquery.autocomplete.css" />'; 
			break;
			
			case 'tinymce':
				echo '<script type="application/javascript" src="'.$GLOBALS['BASE_URL'].'plugins/js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>';
				echo '<script type="application/javascript">
						$(document).ready(function(){
							$("'.$args.'").tinymce({
								// Location of TinyMCE script
								script_url : "'.$GLOBALS['BASE_URL_JS_PLUGIN'].'tinymce/jscripts/tiny_mce/tiny_mce.js",
						
								// General options
								theme : "simple",
								//plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
						
								// Theme options
								//theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
								//theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
								theme_advanced_buttons3 : "tablecontrols,|,hr,|,fullscreen",
								//theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,pagebreak",
								theme_advanced_toolbar_location : "top",
								theme_advanced_toolbar_align : "left",
								theme_advanced_statusbar_location : "bottom",
								theme_advanced_resizing : true,
						
						        // Skin options
						        //skin : "o2k7",
						        //skin_variant : "silver",
						
								// Example content CSS (should be your site CSS)
								content_css : "css/content.css",
						
								// Drop lists for link/image/media/template dialogs
								template_external_list_url : "lists/template_list.js",
								external_link_list_url : "lists/link_list.js",
								external_image_list_url : "lists/image_list.js",
								media_external_list_url : "lists/media_list.js",
						
							});
						});
					</script>';
			break;	

			case 'jcycle':
				echo '<script src="'.$GLOBALS['BASE_URL'].'plugins/js/jcycle/jquery.cycle.min.js" type="application/javascript"></script>';
			break;
			
			case 'jdream':
				echo '<link type="text/css" rel="stylesheet" href="'.$GLOBALS['BASE_URL'].'plugins/js/jdream/style.css"/>';
				echo '<script type="application/javascript" src="'.$GLOBALS['BASE_URL'].'plugins/js/jdream/scripts.js"></script>';
			break;
			
            case 'jcarousel':
                echo '<link type="text/css" rel="stylesheet" href="'.$GLOBALS['BASE_URL'].'plugins/js/jcarousel/skin.css" />';
                echo '<script type="application/javascript" src="'.$GLOBALS['BASE_URL'].'plugins/js/jcarousel/jquery.jcarousel.min.js"></script>';
            break;

			case 'jquery-ui':
				echo '<link type="text/css" rel="stylesheet" href="'.$GLOBALS['BASE_URL'].'plugins/js/ui/jquery-ui.css"/>';
				echo '<script type="application/javascript" src="'.$GLOBALS['BASE_URL'].'plugins/js/ui/jquery-ui.js"></script>';
			break;			
			
			default:
				echo 'Plugin '.$plugin.' não encontrado';
			break;
		}
	}
}

?>
