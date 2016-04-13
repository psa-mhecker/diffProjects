<?php
/**
	* @package Cache
	* @subpackage General
	*/

/**
	* Fichier de Pelican_Cache : Description des champs d'une table
	*
	* retour : *
	*
	* @package Cache
	* @subpackage General
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 23/09/2004
	*/
class Database_Describe_Table extends Pelican_Cache {

	/** Valeur ou objet à mettre en Pelican_Cache */
	public function getValue() {

		$oConnection = Pelican_Db::getInstance();
		$return = $oConnection->getDbInfo($this->params[0], $this->params[1]);

		//debug($return);
		if ($return && $this->params[0] == 'fields') {
			foreach ($return as $key=>$value) {
				$return[$key]['primaryType'] = $oConnection->primaryType($value['type']);
				
				if(!array_key_exists('sequence_name',$value))
					$value['sequence_name'] = false;
					
				if ($value['key'] && ($value['increment'] || $value['sequence_name'])) {
					$return[$key]['primaryType'] = Pelican_Db::IDENTITY;
					//$return[$key]['extra'] = 'auto_increment';
				}
				if (!$return[$key]['primaryType']) {
					debug("pb d'identification de type ".$value['type']);
					//debug($return);
				}
				if ($return[$key]['primaryType'] == Pelican_Db::INTEGER) {
					$return[$key]['size'] = null;
				}
				if (!$value['increment']) {
					$return[$key]['increment'] = false;
				}
				if (!$value['sequence']) {
					$return[$key]['sequence'] = false;
				}
				if (!array_key_exists('fkey',$value)){//!$value['fkey']) {
				//if (!$value['fkey']) {
					$return[$key]['fkey'] = false;
				}
				if (!$value['null']) {
					$return[$key]['null'] = false;
				}

				if ($return[$key]['default'] == '0000-00-00 00:00:00') {
					$return[$key]['default'] = null;
				}
				if ($return[$key]['default'] == '0000-00-00 00:00') {
					$return[$key]['default'] = null;
				}
				if ($return[$key]['default'] == '0000-00-00') {
					$return[$key]['default'] = null;
				}
			}
		}
		/*debug($return);
		die();*/
		$this->value = $return;
	}
}
?>