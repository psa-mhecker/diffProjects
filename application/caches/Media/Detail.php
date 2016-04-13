<?php

/**
 * @package Cache
 * @subpackage Media
 */

/**
 * Fichier de Pelican_Cache : Infos liées à un media
 *
 * retour : *
 *
 * @package Cache
 * @subpackage Media
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 20/05/2006
 */
class Media_Detail extends Pelican_Cache
{
    
    var $duration = DAY;
	
	public $isPersistent = true;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue()
    {
        
        $oConnection = Pelican_Db::getInstance();
        
        $file = '';
        if (is_numeric($this->params[0])) {
        $aBind[":ID"] = $this->params[0];
        $file = $oConnection->queryRow("select * from " . Pelican::$config["FW_MEDIA_TABLE_NAME"] . " where " . Pelican::$config["FW_MEDIA_FIELD_ID"] . "=:ID", $aBind);
        }
        $this->value = $file;
    }
}
?>