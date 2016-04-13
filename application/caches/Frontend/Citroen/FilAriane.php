<?php
/**
 * Fichier de Pelican_Cache : FilAriane
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_FilAriane extends Pelican_Cache {

    var $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $filAriane = array();
        $pageId = $this->params[0];
        $langueId = $this->params[1];
        do {
            $aTmp = $this->getPageInfo($pageId, $langueId);
            $pageId = $aTmp['PAGE_PARENT_ID'];
            if($aTmp['PAGE_DISPLAY_IN_ARIANE'] == 1 || $aTmp['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['HOME']) {
                $filAriane[] = $aTmp;
            }
        }
        while ($aTmp['PAGE_PARENT_ID']);

        $this->value = array_reverse($filAriane);
    }

    function getPageInfo($pageId, $langueId) {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':PAGE_ID'] = $pageId;
        $aBind[':LANGUE_ID'] = $langueId;
        $aBind[':PAGE_STATUS'] = 1;
        $sSQL = "
            select
                p.PAGE_ID,
                p.PAGE_PARENT_ID,
                pv.PAGE_TITLE,
                pv.PAGE_TITLE_BO,
                pv.PAGE_CLEAR_URL,
                pv.PAGE_URL_EXTERNE,
                pv.PAGE_OUVERTURE_DIRECT,
                pv.PAGE_URL_EXTERNE_MODE_OUVERTURE,
                pv.PAGE_DISPLAY_IN_ARIANE,
                pv.TEMPLATE_PAGE_ID
            from #pref#_page p
            inner join #pref#_page_version pv
            on (p.PAGE_ID = pv.PAGE_ID
                and p.PAGE_CURRENT_VERSION = pv.PAGE_VERSION
                and p.LANGUE_ID = pv.LANGUE_ID
            )
            where p.PAGE_ID = :PAGE_ID
            and p.LANGUE_ID = :LANGUE_ID
        ";

        return $oConnection->queryRow($sSQL, $aBind);
    }
}