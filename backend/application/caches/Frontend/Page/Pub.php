<?php
/**
 * Fichier de Pelican_Cache : TAG de publicité associé à une page.
 *
 * @author Fairouz Bihler <fbihler@businessdecision.com>
 *
 * @since 23/03/2005
 */
class Frontend_Page_Pub extends Pelican_Cache
{
    public $duration = WEEK;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind = array();
        $aBind[":PAGE_ID"] = $this->params[0];
        $aBind[":SITE_ID"] = $this->params[1];
        if ($this->params[2]) {
            $type_version = $this->params[2];
        } else {
            $type_version = "CURRENT";
        }

        $sSql = "select t.*
				FROM #pref#_pub t, #pref#_page p, #pref#_page_version pv
				where p.PAGE_ID=:PAGE_ID
				and p.SITE_ID=:SITE_ID
				and p.PAGE_ID = pv.PAGE_ID
				and p.PAGE_".$type_version."_VERSION = pv.PAGE_VERSION
				and pv.PUB_ID = t.PUB_ID
				and t.SITE_ID = :SITE_ID";
        $aResult = $oConnection->queryRow($sSql, $aBind);
        $this->value = $aResult;
    }
}
