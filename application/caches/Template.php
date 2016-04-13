<?php

/**
 * @package Cache
 * @subpackage General
 */

/**
 * Fichier de Pelican_Cache : Résultat de requête sur template
 *
 * retour : *, id, lib
 *
 * @package Cache
 * @subpackage General
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 20/06/2004
 */
class Template extends Pelican_Cache
{

    public static $storage = 'file';

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue()
    {
        
        $oConnection = Pelican_Db::getInstance();
        
        $aBind[":TEMPLATE_ID"] = (! empty($this->params[0]) ? $this->params[0] : '');
        $aBind[":TEMPLATE_GROUP_ID"] = (! empty($this->params[1]) ? $this->params[1] : '');
        $aBind[":SITE_ID"] = (! empty($this->params[2]) ? $this->params[2] : '');
        $aBind[":TEMPLATE_TYPE_ID"] = (! empty($this->params[3]) ? $this->params[3] : '');
        $where = array();
        
        $query = "SELECT
				t.*,
				tg.TEMPLATE_GROUP_ID,
				TEMPLATE_ID as\"id\",
				TEMPLATE_LABEL as\"lib\",
				TEMPLATE_GROUP_LABEL as\"optgroup\"
				FROM
				#pref#_template t
				left join #pref#_template_group tg on
				(t.TEMPLATE_GROUP_ID = tg.TEMPLATE_GROUP_ID) ";
        if (! empty($this->params[0])) {
            $where[] = " t.TEMPLATE_ID = :TEMPLATE_ID ";
        }
        if (! empty($this->params[1])) {
            $where[] = " tg.TEMPLATE_GROUP_ID = :TEMPLATE_GROUP_ID ";
        }
        if (! empty($this->params[3])) {
            $where[] = " TEMPLATE_TYPE_ID = :TEMPLATE_TYPE_ID ";
        }
        if ($where) {
            $query .= " WHERE " . implode(" AND ", $where);
        }
        $query .= " ORDER BY
				TEMPLATE_LABEL";
        $result = $oConnection->queryTab($query, $aBind);
        $this->value = $result;
    }
}
?>