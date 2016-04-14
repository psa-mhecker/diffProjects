<?php

/**
 */

/**
 * Fichier de Pelican_Cache : Résultat de requête sur site.
 *
 * retour : id, lib
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 20/06/2004
 */
class Frontend_Site extends Pelican_Cache
{
    public $duration = UNLIMITED;

    public $isPersistent = true;

    /**
     * Valeur ou objet à mettre en Pelican_Cache.
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $query = "
				SELECT s.SITE_ID as \"id\", s.SITE_LABEL as \"lib\", s.*, mp.*
				FROM #pref#_site as s
				left join #pref#_map_provider as mp on (s.MAP_PROVIDER_ID=mp.MAP_PROVIDER_ID)";
        if ($this->params) {
            $query .= " WHERE SITE_ID = '".$this->params[0]."' ";
        }
        $query .= " ORDER BY SITE_LABEL";

        if ($this->params) {
            $this->value = $oConnection->queryRow($query);
            $parameters = $oConnection->queryTab("select * from #pref#_site_parameter_dns where SITE_ID = '".$this->params[0]."' ");
            foreach ($parameters as $val) {
                $this->value['DNS'][$val['SITE_DNS']][$val['SITE_PARAMETER_ID']] = $val['SITE_PARAMETER_VALUE'];
                if ($val['SITE_PARAMETER_ID'] == 'map_google') {
                    $this->value['DNS'][$val['SITE_DNS']][$val['SITE_PARAMETER_ID'].'_key'] = $val['SITE_PARAMETER_PARAM'];
                }
            }
            $langs = $oConnection->queryTab("select * from #pref#_site_language where SITE_ID = '".$this->params[0]."' ");
            foreach ($langs as $val) {
                $this->value['LANG'][] = $val['LANGUE_ID'];
            }
        } else {
            $this->value = $oConnection->queryTab($query);
        }
    }
}
