<?php
/**
 * Fichier de Pelican_Cache : retourne une page ayant un code de type de page donné.
 *
 * @author Fatma Arkam <fatma.arkam@businessdecision.com>
 *
 * @since 04/08/2011
 */
class Frontend_Page_TypeCode extends Pelican_Cache
{
    public $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":SITE_ID"] = $this->params[0];
        $aBind[":LANGUE_ID"] = $this->params[1];
        if ($this->params[2]) {
            $type_version = $this->params[2];
        } else {
            $type_version = "CURRENT";
        }
        $aBind[":PAGE_TYPE_CODE"] = $oConnection->strToBind($this->params[3]);

        $sSql = "
			select
				*
			from
				#pref#_page p
				inner join #pref#_page_version pv on (pv.PAGE_ID = p.PAGE_ID and pv.LANGUE_ID = p.LANGUE_ID and pv.PAGE_VERSION = p.PAGE_".$type_version."_VERSION)
				inner join #pref#_template_page tp on (tp.TEMPLATE_PAGE_ID = pv.TEMPLATE_PAGE_ID)
				inner join #pref#_page_type pt on (pt.PAGE_TYPE_ID = tp.PAGE_TYPE_ID)
			where
				p.SITE_ID = :SITE_ID
				and p.LANGUE_ID = :LANGUE_ID
				and pt.PAGE_TYPE_CODE = :PAGE_TYPE_CODE
				and p.PAGE_STATUS = 1
			";

        $aResult = $oConnection->queryRow($sSql, $aBind);

        $this->value = $aResult;
    }
}
