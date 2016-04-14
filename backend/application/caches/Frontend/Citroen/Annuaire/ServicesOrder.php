<?php
/**
 * Fichier de Pelican_Cache : Retour WS AnnuPDV.
 */
class Frontend_Citroen_Annuaire_ServicesOrder extends Pelican_Cache
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
        $aBind[':ACTIF'] = $this->params[2];

        $sSQL = "
            SELECT
			    CODE_SERVICE as code,
                LABEL_SERVICE as label,
                TYPE_SERVICE,
                ORDER_SERVICE,
                ACTIF_SERVICE,
                CODE_ID
			FROM
				#pref#_ws_services_pdv
			WHERE
				SITE_ID = :SITE_ID
			AND LANGUE_ID = :LANGUE_ID ";
        if ($aBind[':ACTIF'] == 1) {
            $sSQL .=  "AND ACTIF_SERVICE = :ACTIF";
        }
        $sSQL .= " ORDER BY ORDER_SERVICE ASC
        ";
        $aRes = $oConnection->queryTab($sSQL, $aBind);
        foreach ($aRes as $keyBu => $valueBu) {
            $aRes[$keyBu]['Picto'] =  '<img src="'.Pelican::$config['MEDIA_HTTP']."/design/frontend/images/picto/services/".$valueBu['code'].".png".'" />';
            $aRes[$keyBu]['img'] =  "/design/frontend/images/picto/services/".$valueBu['code'].".png";
            $aRes[$keyBu]['big'] =  "/design/frontend/images/picto/services/".$valueBu['code']."_big.png";
            $aRes[$keyBu]['mobile'] =  "/design/frontend/images/mobile/picto/services/".$valueBu['code']."_big.png";
            $aRes[$keyBu]['index'] = $keyBu;
        }
        $this->value = $aRes;
    }
}
