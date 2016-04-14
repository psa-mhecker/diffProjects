<?php
/**
 * Fichier de Pelican_Cache : récupération des valeurs d'une Pelican_Index_Frontoffice_Zone spécifique dans un gabarit sans page_id si
 * utilisé q'une seule fois.
 *
 * retour : id, lib
 *
 * @author Sebastien Maillot <sebastien.maillot@businessdecision.fr>
 *
 * @since 15/01/2006
 */
class Frontend_Page_ZoneTemplateIdPage extends Pelican_Cache
{
    public $duration = WEEK;
    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind [":ZONE_TEMPLATE_ID"] = $this->params [3];
        $aBind [":SITE_ID"] = $this->params [0];
        if ($this->params [2]) {
            $type_version = $this->params [2];
        } else {
            $type_version = "CURRENT";
        }
        $aBind [":LANGUE_ID"] = $this->params [1];

        $sSQL = "
				SELECT
				pz.*, p.PAGE_PARENT_ID
				FROM
				#pref#_page_zone pz
				inner join #pref#_page p on (pz.PAGE_ID = p.PAGE_ID AND pz.PAGE_VERSION = p.PAGE_CURRENT_VERSION AND pz.LANGUE_ID = p.LANGUE_ID)
				WHERE
				p.SITE_ID = :SITE_ID
				AND pz.ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
				AND p.LANGUE_ID = :LANGUE_ID
                AND ZONE_TITRE != 0";

        $this->value = $oConnection->queryRow($sSQL, $aBind);
    }
}
