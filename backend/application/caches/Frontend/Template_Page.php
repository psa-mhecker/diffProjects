<?php
/**
 * Fichier de Pelican_Cache : Tableau de mise en page des zones.
 *
 * retour : id, lib
 *
 * @author Raphaël Carles <rcarles@businessdecision.fr>
 *
 * @since 28/02/2006
 */
class Frontend_Template_Page extends Pelican_Cache
{
    public $duration = WEEK;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":TEMPLATE_PAGE_ID"] = $this->params[0];

        /*
         * Paramètre permettant de s'adapter à des browsers de types différents en masquant ou non certaines zones
         * Valeurs possibles : '' ou 'web', 'mobile', 'text', 'bot', 'probe'
         **/
        $sZoneCondition = '';
        $sZoneOrder = 'TEMPLATE_PAGE_AREA_ORDER, ZONE_TEMPLATE_ORDER';

        // Pour des types de browsers particuliers, on prend en compte un champs d'ordre différent
        if (isset($this->params[1])) {
            if ($this->params[1] == 'desktop') {
                unset($this->params[1]);
            } else {
                $fieldOrder = strtoUpper($this->params[1]);
                $sZoneOrder = 'ZONE_TEMPLATE_'.$fieldOrder.'_ORDER';
                $sZoneCondition = 'AND ZONE_TEMPLATE_'.$fieldOrder.'_ORDER IS NOT NULL AND ZONE_TEMPLATE_'.$fieldOrder.'_ORDER<>0';
            }
        }

        $sSQL = "SELECT
				tpa.*,
				zt.*,
				z.*
				FROM
				#pref#_template_page_area tpa
				INNER JOIN #pref#_zone_template zt on (tpa.TEMPLATE_PAGE_ID=zt.TEMPLATE_PAGE_ID AND tpa.AREA_ID=zt.AREA_ID)
				INNER JOIN #pref#_zone z on (z.ZONE_ID = zt.ZONE_ID)
				WHERE
				tpa.TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID ".$sZoneCondition." ORDER BY ".$sZoneOrder;
        $tabZones = $oConnection->queryTab($sSQL, $aBind);

        if ($this->params[1] == 'mobile') {
            $tabAreas = array();
            $tabAreas[] = array(
                "AREA_ID" => 1,
            );
        } else {
            $sSQL = "SELECT distinct a.*, tpa.*
				FROM #pref#_template_page_area tpa
				INNER JOIN #pref#_area a on (tpa.AREA_ID=a.AREA_ID)
				WHERE tpa.TEMPLATE_PAGE_ID=:TEMPLATE_PAGE_ID
				order by tpa.TEMPLATE_PAGE_AREA_ORDER";
            $tabAreas = $oConnection->querytab($sSQL, $aBind);
        }

        if ($tabZones) {
            foreach ($tabZones as $data) {
                if ($this->params[1] == 'mobile') {
                    $data["AREA_ID"] = "1";
                }
                if ($data["AREA_ID"]) {
                    $data["ZONE_FO_PATH"] =  $data["ZONE_FO_PATH"];
                    $return[$data["AREA_ID"]][] = $data;
                }
            }
        }

        $this->value = array(
            "areas" => $tabAreas,
            "zones" => $return,
        );
    }
}
