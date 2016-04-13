<?php
/**
 * Fichier de Pelican_Cache : Th�mes d'actualite
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Technologie_Theme extends Pelican_Cache {

    var $duration = HOUR;

    /*
     * Valeur ou objet a mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance ();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $sSQL = "
            SELECT
               THEME_TECHNOLOGIE_GALLERIE_ID,
               THEME_TECHNOLOGIE_GALLERIE_LABEL
            FROM 
                #pref#_theme_technogie_gallerie
            WHERE 
                SITE_ID = :SITE_ID
            AND LANGUE_ID = :LANGUE_ID
			ORDER BY THEME_TECHNOLOGIE_GALLERIE_ORDER
           ";
        $aResults = $oConnection->queryTab($sSQL, $aBind);
        $aThemes = array();
        if(is_array($aResults) && count($aResults)>0){
            foreach($aResults as $res){
                $aThemes[$res['THEME_TECHNOLOGIE_GALLERIE_ID']] = $res['THEME_TECHNOLOGIE_GALLERIE_LABEL'];
            }
        } 
        $this->value = $aThemes;    
    }
}
?>
