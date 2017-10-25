<?php

class JSON{
	
	public static function encodeJSON($var){
	    if(is_array($var)){
	    	foreach ($var as $obj){
	    		$arrayObj[] = $obj->toArray();
	    	}
	    	return json_encode($arrayObj);
	    }
	    return false;
	} 
}