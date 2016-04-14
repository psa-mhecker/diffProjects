<?php
/**
 * Fichier de Pelican_Cache : Finitions.
 */
class Frontend_Citroen_VersionsComparateur extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet ? mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[':LCDV6'] = $oConnection->strToBind($this->params[0]);
        $aBind[':GAMME'] = $oConnection->strToBind($this->params[2]);
        $aBind[':FINITION_CODE'] = $oConnection->strToBind($this->params[1]);
        $aBind[':VERSION'] = $oConnection->strToBind($this->params[3]);
        $aBind[':SITE_ID'] = $this->params[4];
        $aBind[':LANGUE_ID'] = $this->params[5];
        if (empty($this->params[2])) {
            $sSQL = "
				SELECT
					wpfv.*,
					v.VEHICULE_CASH_PRICE_TYPE
				FROM #pref#_vehicule v
				INNER JOIN #pref#_ws_prix_finition_version wpfv
					ON (v.VEHICULE_LCDV6_CONFIG = wpfv.LCDV6
						AND v.VEHICULE_GAMME_CONFIG = wpfv.GAMME
						AND v.SITE_ID = wpfv.SITE_ID
						AND v.LANGUE_ID = wpfv.LANGUE_ID)
				INNER JOIN #pref#_ws_energie_moteur emb
					ON (wpfv.CULTURE = emb.CULTURE
						AND wpfv.LCDV6 = emb.LCDV6
						AND wpfv.ENGINE_CODE = emb.ENGINE_CODE
						AND wpfv.TRANSMISSION_CODE = emb.TRANSMISSION_CODE
					)
				WHERE
					wpfv.SITE_ID = :SITE_ID
				AND wpfv.LANGUE_ID = :LANGUE_ID
				AND wpfv.LCDV6 = :LCDV6
				AND wpfv.GR_COMMERCIAL_NAME_CODE = :FINITION_CODE
				AND emb.ENGINE_CODE = :VERSION
				ORDER BY wpfv.PRICE_DISPLAY ASC
			";
        } else {
            $sSQL = "
				SELECT
					wpfv.*,
					v.VEHICULE_CASH_PRICE_TYPE
				FROM #pref#_vehicule v
				INNER JOIN #pref#_ws_prix_finition_version wpfv
					ON (v.VEHICULE_LCDV6_CONFIG = wpfv.LCDV6
						AND v.VEHICULE_GAMME_CONFIG = wpfv.GAMME
						AND v.SITE_ID = wpfv.SITE_ID
						AND v.LANGUE_ID = wpfv.LANGUE_ID)
				INNER JOIN #pref#_ws_energie_moteur emb
					ON (wpfv.CULTURE = emb.CULTURE
						AND wpfv.LCDV6 = emb.LCDV6
						AND wpfv.GAMME = emb.GAMME
						AND wpfv.ENGINE_CODE = emb.ENGINE_CODE
						AND wpfv.TRANSMISSION_CODE = emb.TRANSMISSION_CODE
					)
				WHERE
					wpfv.SITE_ID = :SITE_ID
				AND wpfv.LANGUE_ID = :LANGUE_ID
				AND wpfv.LCDV6 = :LCDV6
				AND wpfv.GR_COMMERCIAL_NAME_CODE = :FINITION_CODE
				AND wpfv.GAMME = :GAMME
				AND emb.ENGINE_CODE = :VERSION
				ORDER BY wpfv.PRICE_DISPLAY ASC
			";
        }
        $aVersions = $oConnection->queryRow($sSQL, $aBind);
        if ($aVersions['LCDV_CODE'] != '') {
            $aVersions['IMAGE'] = Pelican::$config["VISUEL_3D_PATH"]."?ratio=".Pelican::$config['VISUEL_3D_PARAM']['RATIO']."&version=".$aVersions['LCDV_CODE']."&quality=".Pelican::$config['VISUEL_3D_PARAM']['QUALITY']."&width=239&format=png&height=134&view=".Pelican::$config['VISUEL_3D_PARAM']['VIEW']."&client=".Pelican::$config['VISUEL_3D_PARAM']['CLIENT'];
            $aVersions['IMAGE_MOBILE'] = Pelican::$config["VISUEL_3D_PATH"]."?ratio=".Pelican::$config['VISUEL_3D_PARAM']['RATIO']."&version=".$aVersions['LCDV_CODE']."&quality=".Pelican::$config['VISUEL_3D_PARAM']['QUALITY']."&width=93&format=png&height=93&view=".Pelican::$config['VISUEL_3D_PARAM']['VIEW']."&client=".Pelican::$config['VISUEL_3D_PARAM']['CLIENT'];
        }
        $this->value = $aVersions;
    }
}
