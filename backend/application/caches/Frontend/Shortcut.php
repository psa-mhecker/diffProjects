<?php
/**
 * Fichier de Pelican_Cache : Shortcut de pages types.
 *
 * @author Raphael Carles <rcarles@businessdecision.com>
 *
 * @since 14/06/2005
 */
class Frontend_Shortcut extends Pelican_Cache
{
    public $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[":SHORTCUT"] = $oConnection->strToBind(str_replace("//", "/", "/".strToLower($this->params[0])));
        $aBind[":SITE_ID"] = $this->params[1];
        $aBind[":LANGUE_ID"] = ($this->params[2] ? $this->params[2] : 1);

        $sSql = "select p.PAGE_ID, PAGE_CLEAR_URL from #pref#_template_page tp
		inner join #pref#_page_type pt on (tp.PAGE_TYPE_ID=pt.PAGE_TYPE_ID)
		inner join #pref#_page_version pv on (tp.TEMPLATE_PAGE_ID=pv.TEMPLATE_PAGE_ID)
		inner join #pref#_page p on (p.PAGE_ID=pv.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID AND pv.PAGE_VERSION=p.PAGE_CURRENT_VERSION)
		WHERE p.SITE_ID=:SITE_ID
		AND p.LANGUE_ID=:LANGUE_ID
		AND PAGE_TYPE_SHORTCUT = :SHORTCUT";
        $result = $oConnection->queryRow($sSql, $aBind);

        $this->value = $result;
    }
}
