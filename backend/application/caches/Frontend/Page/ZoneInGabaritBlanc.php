<?php

/**
 * Fichier de Pelican_Cache : récupération des données d'une zone étant donné
 * le gabarit, l'area, et l'id de la zone.
 *
 * retour : id, lib
 *
 * @author Raphaël Carles <rcarles@businessdecision.fr>
 *
 * @since 25/02/2006
 */
class Frontend_Page_ZoneInGabaritBlanc extends Pelican_Cache
{
    public $duration = DAY;

    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':PAGE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $aBind[':AREA_ID'] = $this->params[2];
        $aBind[':ZONE_ID'] = $this->params[3];
        $aBind[':PAGE_VERSION'] = $this->params[4];

        $sSQL = "SELECT pmz.*,
                    z.ZONE_BO_PATH,
                    z.ZONE_LABEL as LABEL_ZONE,
                    z.ZONE_TYPE_ID
                FROM #pref#_page_multi_zone pmz
                    INNER JOIN #pref#_zone z
                        ON (pmz.ZONE_ID = z.ZONE_ID)
                WHERE PAGE_ID = :PAGE_ID
                    AND PAGE_VERSION = :PAGE_VERSION
                    AND LANGUE_ID = :LANGUE_ID
                    AND AREA_ID = :AREA_ID
                    AND pmz.ZONE_ID = :ZONE_ID
                ORDER BY ZONE_ORDER ASC
        ";

        $this->value = $oConnection->queryTab($sSQL, $aBind);
    }
}
