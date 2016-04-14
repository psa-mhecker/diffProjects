<?php

/**
 * Fichier de Pelican_Cache : Liste des véhicules par page promotion.
 */
class Frontend_Citroen_OtherPromotions extends Pelican_Cache
{
    public $duration = WEEK;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[":SITE_ID"] = $this->params[0];
        $aBind[":LANGUE_ID"] = $this->params[1];
        $aBind[":CLE_PROMOTION"] = $this->params[2];
        $aBind[":TEMPLATE_PAGE_ID"] = Pelican::$config['TEMPLATE_PAGE']['DETAIL_PROMOTION'];
        $aBind[':ZONE_TEMPLATE_ID'] = Pelican::$config['ZONE_TEMPLATE_ID']['PROMOTIONS'];
        $aBind[':PAGE_ZONE_MULTI_TYPE'] = $oConnection->strToBind("Promotion");
        if ($this->params [3]) {
            $type_version = $this->params [3];
        } else {
            $type_version = "CURRENT";
        }
        $aBind[":VEHICULE_ID"] = $this->params[4];
        if (!empty($aBind[":CLE_PROMOTION"]) && !empty($this->params[4])) {
            //Explode puis implode pour pouvoir placer les quotes, sinon MySQL s'emboruille avec le concat
            $aBind[":CLE_PROMOTION"] = explode(",", $aBind[":CLE_PROMOTION"]);
            if (is_array($aBind[":CLE_PROMOTION"]) && !empty($aBind[":CLE_PROMOTION"])) {
                for ($i = 0; $i < count($aBind[":CLE_PROMOTION"]); $i++) {
                    $aBind[":CLE_PROMOTION"][$i] = $oConnection->strToBind($aBind[":CLE_PROMOTION"][$i]);
                    $aCase[$aBind[":CLE_PROMOTION"][$i]] = $i;
                }
                $aBind[":CLE_PROMOTION"] = implode(',', $aBind[":CLE_PROMOTION"]);
                $sSql = "
					SELECT
						pv.PAGE_CLEAR_URL,
						pzm.*,
						v.VEHICULE_ID,
						v.VEHICULE_LABEL
					FROM #pref#_page p
					INNER JOIN #pref#_page_version pv
						ON (pv.PAGE_ID = p.PAGE_ID
							AND pv.PAGE_VERSION = p.PAGE_".$type_version."_VERSION
							AND pv.LANGUE_ID = p.LANGUE_ID)
					INNER JOIN #pref#_page_zone_multi pzm
						ON (pzm.PAGE_ID = pv.PAGE_ID
							AND pzm.PAGE_VERSION = pv.PAGE_VERSION
							AND pzm.LANGUE_ID = pv.LANGUE_ID)
					LEFT JOIN #pref#_vehicule v
						on (v.VEHICULE_ID = pzm.PAGE_ZONE_MULTI_LABEL5)
					WHERE p.SITE_ID = :SITE_ID
					AND p.LANGUE_ID = :LANGUE_ID
                                        AND p.PAGE_STATUS = 1
                                        AND pv.STATE_ID = 4
					AND pv.TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID
					AND pzm.ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
					AND pzm.PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE
					AND CONCAT(pzm.PAGE_ID,'||',pzm.LANGUE_ID,'||',pzm.ZONE_TEMPLATE_ID,'||',pzm.PAGE_ZONE_MULTI_ID) IN(:CLE_PROMOTION)
					AND VEHICULE_ID = :VEHICULE_ID
					ORDER BY ".$oConnection->getCaseClause("CONCAT(pzm.PAGE_ID,'||',pzm.LANGUE_ID,'||',pzm.ZONE_TEMPLATE_ID,'||',pzm.PAGE_ZONE_MULTI_ID)", $aCase, 1);
                $this->value = $oConnection->queryTab($sSql, $aBind);
            }
        }

        return $this->value;
    }
}
