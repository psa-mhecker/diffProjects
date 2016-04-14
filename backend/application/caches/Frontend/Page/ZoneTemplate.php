<?php

/**
 * Fichier de Pelican_Cache : récupération des valeurs d'une Pelican_Index_Frontoffice_Zone spécifique dans un gabarit.
 *
 * retour : id, lib
 *
 * @author Raphaël Carles <rcarles@businessdecision.fr>
 *
 * @since 25/02/2006
 */
class Frontend_Page_ZoneTemplate extends Pelican_Cache
{
    public $duration = WEEK;

    public $isPersistent = true;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":PAGE_ID"] = $this->params[0];
        $aBind[":ZONE_TEMPLATE_ID"] = $this->params[1];
        $aBind[":PAGE_VERSION"] = $this->params[2];
        $aBind[":LANGUE_ID"] = $this->params[3];

        $sSQL = "
				SELECT
					*
				FROM
				#pref#_page_zone pz
				WHERE
				pz.PAGE_ID = :PAGE_ID
				AND pz.PAGE_VERSION = :PAGE_VERSION
				AND pz.ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
				AND pz.LANGUE_ID = :LANGUE_ID";
        $this->value = $oConnection->queryRow($sSQL, $aBind);
    }
}
