<?php
/**
 * Fichier de Pelican_Cache : Reseaux Sociaux.
 */
class Frontend_Citroen_BoxSociales extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $sSQL = "
            select
                rs.*
            from #pref#_reseau_social rs
            where SITE_ID = :SITE_ID
            and LANGUE_ID = :LANGUE_ID
            order by RESEAU_SOCIAL_ORDER asc
        ";
        $aTemp = $oConnection->queryTab($sSQL, $aBind);
        $aResults = array();
        if ($aTemp) {
            foreach ($aTemp as $key => $temp) {
                $aResults[$temp['RESEAU_SOCIAL_ID']] = $temp;
            }
        }
        $this->value = $aResults;
    }
}
