<?php

/**
 * Fichier de Pelican_Cache : Caractéristiques
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Finitions_Caracteristiques extends Pelican_Cache
{

	var $duration = DAY;

	/*
	 * Valeur ou objet à mettre en Pelican_Cache
	 */

	function getValue()
	{
		$oConnection = Pelican_Db::getInstance();
		$aBind[':ENGINE_CODE'] = $oConnection->strToBind($this->params[0])/* $this->params[0] */;
		$aBind[':LCDV6'] = $oConnection->strToBind($this->params[1])/* $this->params[1] */;
		$aBind[':GAMME'] = $oConnection->strToBind($this->params[2]);
		$aBind[':SITE_ID'] = $this->params[3];
		$aBind[':LANGUE_ID'] = $this->params[4];
		if (isset($this->params[5]) && !empty($this->params[5])) {
			$aBind[':FINITION_CODE'] = $oConnection->strToBind($this->params[5]);
		}

		$sSQL = "
			SELECT
				wcm.*,
				wct.CATEGORY_NAME, wct.NAME, wct.VALUE
            FROM #pref#_ws_caracteristique_moteur wcm
            INNER JOIN #pref#_ws_caracteristique_technique wct
            ON (wct.LCDV_CODE = wcm.REFERENCE_LCDV
              and wct.GAMME = wcm.GAMME
              and wct.CULTURE = wcm.CULTURE)
            INNER JOIN #pref#_ws_prix_finition_version pfv
            ON (pfv.LCDV_CODE = wct.LCDV_CODE
              and pfv.GAMME = wct.GAMME
              and pfv.CULTURE = wct.CULTURE
            )
            WHERE wcm.SITE_ID = :SITE_ID
			AND wcm.LANGUE_ID = :LANGUE_ID
			AND wcm.LCDV6 = :LCDV6
			AND pfv.GR_COMMERCIAL_NAME_CODE = :FINITION_CODE
			AND pfv.ENGINE_CODE = :ENGINE_CODE
			and pfv.GAMME = :GAMME
			AND pfv.LCDV6 = wcm.LCDV6
			and wcm.LCDV6 = wct.LCDV6
			ORDER by wct.RANK";


        $aResult = $oConnection->queryTab($sSQL, $aBind);
        if (is_array($aResult) && count($aResult) === 0)
        { //  CPW-1896
            $aResult = $oConnection->queryTab(
                "
                SELECT
                cdm.CARACT_KEY as CATEGORY_NAME, cdm.LABEL as NAME, cdm.VALUE as VALUE
                from
                #pref#_ws_prix_finition_version pfv
                inner join #pref#_ws_caracteristique_detail_moteur cdm
                on (cdm.LCDV6 = pfv.LCDV6 and cdm.REFERENCE_LCDV=pfv.LCDV_CODE and cdm.CULTURE = pfv.CULTURE and cdm.GAMME = pfv.GAMME)
                where pfv.SITE_ID = :SITE_ID and pfv.LANGUE_ID = :LANGUE_ID
                and pfv.GAMME = :GAMME and pfv.LCDV6 = :LCDV6
                and pfv.GR_COMMERCIAL_NAME_CODE = :FINITION_CODE
                and pfv.ENGINE_CODE = :ENGINE_CODE
                order by cdm.CARACT_KEY, cdm.LABEL
                ", $aBind);
        }
		$aCaracteristiques = array();
		if (is_array($aResult) && count($aResult) > 0) {
			foreach ($aResult as $key => $res) {
				$aCaracteristiques[$res['CATEGORY_NAME']]['LABEL'] = $res['CATEGORY_NAME'];
				$aCaracteristiques[$res['CATEGORY_NAME']]['CARACTERISTIQUES'][] = array(
					'NAME' => $res['NAME'], 'VALUE' => $res['VALUE']);
			}
		}
		$this->value = $aCaracteristiques;
	}

}
