<?php

/**
 * Fichier de Pelican_Cache : Finitions.
 */
class Frontend_Citroen_Versions extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */

    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        //debug($this->params);
        $aBind[':LCDV6'] = $oConnection->strToBind($this->params[0]);
        $aBind[':GAMME'] = $oConnection->strToBind($this->params[1]);
        $aBind[':FINITION_CODE'] = $oConnection->strToBind($this->params[2]);
        $aBind[':VERSION'] = $oConnection->strToBind($this->params[3]);
        $aBind[':SITE_ID'] = $this->params[4];
        $aBind[':LANGUE_ID'] = $this->params[5];
        $sSQL = "
			SELECT
				wspfv.*,
				emb.*,
				wsf.FINITION_LABEL,
				emb.LABEL as ENGINE_LABEL,
				wspfv.LABEL as LABEL,
				v.VEHICULE_ID
			FROM #pref#_vehicule v
			INNER JOIN #pref#_ws_finitions wsf
				ON (v.VEHICULE_LCDV6_CONFIG = wsf.LCDV6
					AND v.VEHICULE_GAMME_CONFIG = wsf.GAMME
					AND v.SITE_ID = wsf.SITE_ID
					AND v.LANGUE_ID = wsf.LANGUE_ID)
			INNER JOIN #pref#_ws_prix_finition_version wspfv
				ON (wsf.CULTURE = wspfv.CULTURE
					AND wsf.LCDV6 = wspfv.LCDV6
					AND wsf.GAMME = wspfv.GAMME
					AND wsf.FINITION_CODE = wspfv.GR_COMMERCIAL_NAME_CODE
				)
			INNER JOIN #pref#_ws_energie_moteur emb
				ON (wspfv.CULTURE = emb.CULTURE
					AND wspfv.LCDV6 = emb.LCDV6
					AND wspfv.GAMME = emb.GAMME
					AND wspfv.ENGINE_CODE = emb.ENGINE_CODE
					AND wspfv.TRANSMISSION_CODE = emb.TRANSMISSION_CODE
				)
			WHERE v.SITE_ID = :SITE_ID
			AND v.LANGUE_ID = :LANGUE_ID
			AND wsf.LCDV6 = :LCDV6
			AND wsf.FINITION_CODE = :FINITION_CODE
			AND emb.ENGINE_CODE = :VERSION";
        $aVersions = $oConnection->queryRow($sSQL, $aBind);

        if ($aVersions['LCDV_CODE'] != '') {
            $aVersions['IMAGE'] = Pelican::$config["VISUEL_3D_PATH"]."?ratio=".Pelican::$config['VISUEL_3D_PARAM']['RATIO']."&version=".$aVersions['LCDV_CODE']."&quality=".Pelican::$config['VISUEL_3D_PARAM']['QUALITY']."&width=373&format=png&height=209&view=".Pelican::$config['VISUEL_3D_PARAM']['VIEW']."&client=".Pelican::$config['VISUEL_3D_PARAM']['CLIENT'];
            $aVersions['IMAGE_MOBILE'] = Pelican::$config["VISUEL_3D_PATH"]."?ratio=".Pelican::$config['VISUEL_3D_PARAM']['RATIO']."&version=".$aVersions['LCDV_CODE']."&quality=".Pelican::$config['VISUEL_3D_PARAM']['QUALITY']."&width=93&format=png&height=93&view=".Pelican::$config['VISUEL_3D_PARAM']['VIEW']."&client=".Pelican::$config['VISUEL_3D_PARAM']['CLIENT'];
        }
        $this->value = $aVersions;
    }
}
