<?php
/**
 */

/**
 * Fichier de Pelican_Cache : retourne une page ayant un template de page donné.
 *
 * @author Gilles Lenormand <glenormand@businessdecision.com>
 *
 * @since 23/05/2006
 */
class PageType_Template extends Pelican_Cache
{
    public $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":TEMPLATE_PAGE_ID"] = $this->params[0];

        $sSQL = "
				SELECT
				*
				FROM #pref#_template_page tp
				INNER JOIN #pref#_page_type pt on (tp.PAGE_TYPE_ID=pt.PAGE_TYPE_ID)
				AND tp.TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID";

        $return = $oConnection->queryRow($sSQL, $aBind);

        $this->value = $return;
    }
}
