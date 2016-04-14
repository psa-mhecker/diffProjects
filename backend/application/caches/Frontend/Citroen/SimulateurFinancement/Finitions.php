<?php

/**
 * Fichier de Pelican_Cache : Finitions.
 */
class Frontend_Citroen_SimulateurFinancement_Finitions extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $sLcdv6 = $this->params[0];
        $aBind[':LCDV6'] = $oConnection->strToBind($sLcdv6);
        $aBind[':GAMME'] = $oConnection->strToBind($this->params[1]);
        $aBind[':SITE_ID'] = $this->params[2];
        $aBind[':LANGUE_ID'] = $this->params[3];
        $sSQL = "
            SELECT *
			FROM #pref#_ws_finitions
			WHERE SITE_ID = :SITE_ID
			AND LANGUE_ID = :LANGUE_ID";

        if ($sLcdv6 != '') {
            $sSQL .= ' AND LCDV6 = :LCDV6 ';
        }

        $sSQL .= " ORDER BY PRIMARY_DISPLAY_PRICE ASC";
        $aResults = $oConnection->queryTab($sSQL, $aBind);
        $aFinitions = array();
        if (count($aResults)) {
            foreach ($aResults as $aResult) {
                $aFinitions[] = array(
                    'FINITION_LABEL' => $aResult['FINITION_LABEL'],
                    'FINITION_CODE' => $aResult['FINITION_CODE'],
                    );
            }
        }
        $this->value = $aFinitions;
    }
}
