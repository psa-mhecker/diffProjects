<?php
/**
 * Fichier de Pelican_Caches_Citroen : Zonne.
 *
 * Cache remontant les vehicules qui sont dans "disponible sur" pour le bandeau
 *
 * @author  Kristopher Perin <kristopher.perin@businessdecision.com>
 *
 * @since   25/07/2013
 *
 * @param 0 SITE_ID                 Identifiant du site
 * @param 1 LANGUE_ID               Identifiant de la langue
 * @param 2 PAGE_ID                 Identifiant de la page
 * @param 3 PAGE_VERSION            Identifiant de la page version
 * @param 4 ZONE_TEMPLATE_ID        Identifiant de la zone template
 */
class Frontend_Citroen_VehiculeDisponibleSur extends Pelican_Cache
{
    public $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        /*$sSql = "
            SELECT
                #pref#_vehicule.VEHICULE_ID,
                VEHICULE_LABEL,
                VEHICULE_MEDIA_ID_THUMBNAIL
            FROM
                #pref#_page_zone_vehicule
            INNER JOIN
                #pref#_vehicule ON #pref#_page_zone_vehicule.VEHICULE_ID = #pref#_vehicule.VEHICULE_ID
            WHERE
                #pref#_vehicule.SITE_ID=:SITE_ID
                AND PAGE_ID=:PAGE_ID
                AND #pref#_page_zone_vehicule.LANGUE_ID=:LANGUE_ID
                AND PAGE_VERSION=:PAGE_VERSION
                AND ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID
            ORDER BY #pref#_page_zone_vehicule.ZONE_ORDER";*/
        $result = array();
        if (!empty($this->params[0])) {
            $sSql = "
                SELECT VEHICULE_ID, VEHICULE_LABEL
                FROM #pref#_vehicule
                WHERE VEHICULE_ID in (".str_replace('#', ', ', $this->params[0]).")
                order by field(VEHICULE_ID, ".str_replace('#', ', ', $this->params[0])." )
             ";

            $result = $oConnection->queryTab($sSql);
        }
        $this->value = $result;
    }
}
