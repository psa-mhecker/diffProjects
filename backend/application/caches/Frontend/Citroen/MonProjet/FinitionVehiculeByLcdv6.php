<?php

/**
 * Fichier de Pelican_Cache : Mes Selections Mon projet.
 */
class Frontend_Citroen_MonProjet_FinitionVehiculeByLcdv6 extends Pelican_Cache
{
    public $duration = DAY;

    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':LCDV6'] =  $oConnection->strToBind($this->params[0]);
        $aBind[':FINITION_CODE'] =  $oConnection->strToBind($this->params[1]);
        $aBind[':GAMME'] = $oConnection->strToBind($this->params[2]);
        $aBind[':SITE_ID'] = $this->params[3];
        $aBind[':LANGUE_ID'] = $this->params[4];
        $sSQL = 'SELECT wsf.V3D_LCDV,
						wsf.LCDV6,
						v.VEHICULE_ID,
						v.VEHICULE_LABEL as LABEL,
						wsf.FINITION_LABEL,
						wsf.PRIMARY_DISPLAY_PRICE as PRICE_DISPLAY,
						wsf.FINITION_CODE
					FROM  #pref#_ws_finitions as wsf
					LEFT JOIN
						#pref#_vehicule v
                             ON (v.VEHICULE_LCDV6_CONFIG = wsf.LCDV6
                             AND v.SITE_ID = wsf.SITE_ID
                             AND v.LANGUE_ID = wsf.LANGUE_ID
                             AND v.VEHICULE_GAMME_CONFIG = wsf.GAMME)
					WHERE wsf.LCDV6 =:LCDV6
					AND wsf.FINITION_CODE =:FINITION_CODE
					AND wsf.SITE_ID =:SITE_ID
					AND wsf.LANGUE_ID =:LANGUE_ID';

        $aData = $oConnection->queryRow($sSQL, $aBind);
        $this->value = $aData;
    }
}
