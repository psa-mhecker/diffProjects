<?php
/**
 * Fichier de Pelican_Cache : Tableau des zones libres.
 *
 * retour : id, lib
 *
 * @author Raphaël Carles <rcarles@businessdecision.fr>
 *
 * @since 02/03/2006
 */
class Frontend_Page_Heritable extends Pelican_Cache
{
    public $duration = DAY;
    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[":PAGE_ID"] = $this->params[1];
        $aBind[":ZONE_TEMPLATE_ID"] = $this->params[2];
        $aBind[":ZONE_ID"] = $oConnection->queryItem("select ZONE_ID from #pref#_zone_template where ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID", $aBind);
        if ($this->params[3]) {
            $type_version = $this->params[3];
        } else {
            $type_version = "CURRENT";
        }
        $aBind[":LANGUE_ID"] = $this->params[4];

        $this->value = getZoneParent($aBind, $type_version, true);
    }
}

function getZoneParent($aBind, $type_version, $init = false)
{
    $oConnection = Pelican_Db::getInstance();

    if (!$init) {
        $sqlParent = "SELECT #pref#_page.PAGE_PARENT_ID FROM #pref#_page WHERE PAGE_ID=:PAGE_ID";
        $pageParent = $oConnection->queryItem($sqlParent, $aBind);
        /* on prend les versions publiées des parents, même si on  est en prévisu */
        $type_version = "CURRENT";
    } else {
        $pageParent = $aBind[":PAGE_ID"];
    }

    if ($pageParent) {
        $aBind[":PAGE_ID"] = $pageParent;

// controler que c'est utile ici
/*
 * 	p.*,
 *	pv.*,
 */
        $sSQL = "
				SELECT
					pz.*,
					z.*
				FROM
				#pref#_page p
				INNER JOIN #pref#_page_version pv on (p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID = pv.LANGUE_ID AND pv.PAGE_VERSION = p.PAGE_".$type_version."_VERSION)
				INNER JOIN #pref#_page_zone pz on (pz.PAGE_ID = pv.PAGE_ID AND pz.LANGUE_ID = pv.LANGUE_ID AND pv.PAGE_VERSION = pz.PAGE_VERSION)
				INNER JOIN #pref#_template_page_area tpa on (tpa.TEMPLATE_PAGE_ID=pv.TEMPLATE_PAGE_ID)
				INNER JOIN #pref#_zone_template zt on (zt.ZONE_TEMPLATE_ID=pz.ZONE_TEMPLATE_ID AND tpa.TEMPLATE_PAGE_ID=zt.TEMPLATE_PAGE_ID AND tpa.AREA_ID=zt.AREA_ID)
				INNER JOIN #pref#_zone z on (z.ZONE_ID = zt.ZONE_ID)
				WHERE
				pz.PAGE_ID = :PAGE_ID
				AND zt.ZONE_ID = :ZONE_ID
				AND pz.LANGUE_ID = :LANGUE_ID";
        $zone = $oConnection->queryRow($sSQL, $aBind);

        $allowedTags = '<img><embed><object>';

        $text = trim(strip_tags($zone["ZONE_TEXTE"].$zone["ZONE_TITRE"], $allowedTags));

        if ($zone) {
            if (($init && $zone["ZONE_EMPTY"]) || ($text)) {
                $result = $zone;
            } else {
                $result = getZoneParent($aBind, $type_version);
            }
        } else {
            $result = getZoneParent($aBind, $type_version);
        }
    }

    return $result;
}
