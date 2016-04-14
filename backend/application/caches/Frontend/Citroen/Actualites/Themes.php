<?php
/**
 * Fichier de Pelican_Cache : Thèmes d'actualité.
 */
class Frontend_Citroen_Actualites_Themes extends Pelican_Cache
{
    public $duration = HOUR;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $sSQL = "
            SELECT
               THEME_ACTUALITES_ID,
			   SITE_ID,
			   THEME_ACTUALITES_LABEL
            FROM
				#pref#_theme_actualites
			WHERE
				SITE_ID = :SITE_ID
			AND LANGUE_ID = :LANGUE_ID
			ORDER BY THEME_ACTUALITES_ORDER
           ";
        $aResults = $oConnection->queryTab($sSQL, $aBind);
        $aThemes = array();
        if (is_array($aResults) && count($aResults)>0) {
            foreach ($aResults as $res) {
                $aThemes[$res['THEME_ACTUALITES_ID']] = $res['THEME_ACTUALITES_LABEL'];
            }
        }
        $this->value = $aThemes;
    }
}
