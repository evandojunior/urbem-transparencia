<?php

Load::_require('modules/endereco/endereco.base.php');
Load::_require('modules/endereco/endereco.class.php');
Load::_require('modules/endereco/endereco.form.php');
Load::_require('modules/endereco/endereco.bo.php');


class EnderecoController extends Controller{

	public static function AJAXgetMunicipios(){
		$municipioBO = new MunicipioBO();
		try{
			$municipios = $municipioBO->findMunicipioByUF($_REQUEST['uf']);

			foreach($municipios as $municipio){
				$jsonMunicipio['id']   = $municipio->getId();
				$jsonMunicipio['nome'] = utf8_encode($municipio->getNome());
				$jsonMunicipios[] 		= $jsonMunicipio;
			}

			Load::json($jsonMunicipios);

		} catch (Exception $e){
			Message::getInstance()->error($e->getMessage());
		}
	}
}
