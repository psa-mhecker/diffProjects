<?php
/**
 */

/**
 * Fichier de Pelican_Cache : Résultat de requête sur PAGE_ZONE_MULTI.
 *
 * retour : id, lib
 *
 * @author Kristopher Perin <kristopher.perin@businessdecision.com>
 *
 * @since 09/07/2013
 */
class Frontend_Citroen_ZoneTemplate extends Citroen_Cache
{
    public $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind = array();
        $aBind[":PAGE_ID"] = $this->params[0];
        $aBind[":LANGUE_ID"] = $this->params[1];

        if (is_numeric($this->params[2])) {
            $this->params[2] = 'CURRENT';
        }
        //$aBind[":PAGE_VERSION"] = $this->params[2];
        $type_version = ($this->params[2]) ? $this->params[2] : 'CURRENT';

        $aBind[":ZONE_TEMPLATE_ID"] = $this->params[3];
        // Pour Modification de la requette
        $aBind[":AREA_ID"] = $this->params[4];
        $aBind[":ZONE_ORDER"] = $this->params[5];
        $aBind[":ZONE_ID"] = $this->params[6];

        $table = '#pref#_page_zone pzm';
        $where = ' and ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID';
        if ($aBind[":ZONE_ORDER"] != '') {
            $table = '#pref#_page_multi_zone pzm';
            $where = ' and AREA_ID = :AREA_ID
                    and ZONE_ORDER = :ZONE_ORDER';
        }
        if ($aBind[":ZONE_ID"] != '' && $aBind[":ZONE_ORDER"] != '') {
            $where .= ' and ZONE_ID = :ZONE_ID';
        }

        $query = "
	        select *
	        from
	            ".$table."
                INNER JOIN #pref#_page p
                    ON (p.PAGE_ID = pzm.PAGE_ID
                        AND p.PAGE_".$type_version."_VERSION = pzm.PAGE_VERSION
						AND p.LANGUE_ID = pzm.LANGUE_ID
                    )
	        where
	            pzm.PAGE_ID = :PAGE_ID
                    and p.PAGE_STATUS = 1
	            and pzm.LANGUE_ID = :LANGUE_ID
                ".$where."
	        ";
        $this->value = $oConnection->queryRow($query, $aBind);
    }
}
