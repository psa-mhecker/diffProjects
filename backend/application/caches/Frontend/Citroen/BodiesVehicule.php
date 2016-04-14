<?php
/**
 * Fichier de Pelican_Cache : Liste des silhouettes vehicule.
 */
class Frontend_Citroen_BodiesVehicule extends Pelican_Cache
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
        $aBind[':GAMME'] = $this->params[2];

        $sSQL = "
            SELECT
				DISTINCT CRIT_BODY_CODE,
				CRIT_BODY_LABEL
            FROM
				#pref#_ws_critere_selection
            WHERE SITE_ID = :SITE_ID
            AND LANGUE_ID = :LANGUE_ID

        ";
        if ($aBind[':GAMME']) {
            $sSQL .= "AND GAMME = ':GAMME' ";
        }

        $sSQL .= "order by GAMME ASC, CRIT_ORDER ASC ";

        $aResult = $oConnection->queryTab($sSQL, $aBind);
        $this->value = $aResult;
    }
}
