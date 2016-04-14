<?php
/**
 * Fichier de Pelican_Cache : EngineList.
 */
class Frontend_Citroen_Finitions_EngineList extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':GR_COMMERCIAL_NAME_CODE'] = $oConnection->strToBind($this->params[0]);
        $aBind[':LCDV6'] = $oConnection->strToBind($this->params[1]);
        $aBind[':SITE_ID'] = $this->params[2];
        $aBind[':LANGUE_ID'] = $this->params[3];
        $sSQL = "
			SELECT
				emb.LABEL as ENGINE_LABEL,
				emb.LABEL,
				pfv.LCDV_CODE,
				pfv.GR_COMMERCIAL_NAME_CODE,
				emb.ENERGY_CATEGORY,
				pfv.ENGINE_CODE,
				pfv.TRANSMISSION_CODE
			FROM #pref#_ws_prix_finition_version AS pfv
			INNER JOIN #pref#_ws_energie_moteur emb
				ON (pfv.CULTURE = emb.CULTURE
					AND pfv.LCDV6 = emb.LCDV6
					AND pfv.GAMME = emb.GAMME
					AND pfv.ENGINE_CODE = emb.ENGINE_CODE
					AND pfv.TRANSMISSION_CODE = emb.TRANSMISSION_CODE
				)
			WHERE pfv.SITE_ID = :SITE_ID
			AND pfv.LANGUE_ID = :LANGUE_ID
			AND pfv.LCDV6 = :LCDV6
			AND pfv.GR_COMMERCIAL_NAME_CODE = :GR_COMMERCIAL_NAME_CODE
			GROUP BY emb.ENGINE_CODE
			ORDER BY pfv.PRICE_NUMERIC
        ";
        $aResult = $oConnection->queryTab($sSQL, $aBind);
        $this->value = $aResult;
    }
}
