<?php
/**
 * Fichier de Pelican_Cache : Url Vehicule par identifiant.
 */
class Frontend_Citroen_UrlVehiculeById extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':VEHICULE_ID'] = $this->params[0];
        $aBind[':ZTID'] = $this->params[1];
        $aBind[':TPID'] = $this->params[2];
        $aBind[':LANGUE_ID'] = $this->params[3];
        $aBind[':SITE_ID'] = $this->params[4];
        $aBind[':PAGE_STATUS'] = 1;
        $aBind[':STATE_ID'] = 4;
        if ($this->params[5]) {
            $type_version = $this->params[5];
        } else {
            $type_version = "CURRENT";
        }
        if (isset($this->params[6])) {
            $bURLOnly = $this->params[6];
        } else {
            $bURLOnly = true;
        }
        $sSQL = "
            SELECT
				".(($bURLOnly) ? "pv.PAGE_CLEAR_URL" : "pv.*")."
			FROM
				#pref#_page p
				INNER JOIN #pref#_page_version pv
					ON (p.PAGE_ID = pv.PAGE_ID
						AND p.PAGE_".$type_version."_VERSION = pv.PAGE_VERSION
						AND p.LANGUE_ID = pv.LANGUE_ID
					)
				INNER JOIN #pref#_page_zone pz
					ON (p.PAGE_ID = pz.PAGE_ID
						AND p.PAGE_".$type_version."_VERSION = pz.PAGE_VERSION
						AND p.LANGUE_ID = pz.LANGUE_ID
					)
			WHERE
				p.SITE_ID = :SITE_ID
			AND p.LANGUE_ID = :LANGUE_ID
			AND p.PAGE_STATUS = :PAGE_STATUS
			AND pv.STATE_ID = :STATE_ID
			AND pv.TEMPLATE_PAGE_ID = :TPID
			AND pz.ZONE_TEMPLATE_ID = :ZTID
			AND pz.ZONE_ATTRIBUT = :VEHICULE_ID
        ";
        if ($bURLOnly) {
            $aResult = $oConnection->queryItem($sSQL, $aBind);
        } else {
            $aResult = $oConnection->queryRow($sSQL, $aBind);
        }
        $this->value = $aResult;
    }
}
