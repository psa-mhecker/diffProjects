<?php
/**
 * Fichier de Pelican_Cache : renvois les rubriques filles.
 *
 * @param 0 SITE_ID         id du site
 * @param 1 LANGUE_ID       langue du site
 * @param 2 PAGE_ID         pid de la page
 * @param 3 CURRENT         par default
 */
class Frontend_Citroen_GalerieNiveau2 extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet a mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $aBind[':PAGE_ID'] = $this->params[2];
        $aBind[':STATE_ID'] = 4;
        $aBind[':PAGE_STATUS'] = 1;
        $aBind[':PAGE_DISPLAY'] = 1;
        $sVersion = ($this->params[3]) ? $this->params[3] : 'CURRENT';

        $sSQL = "
            select
                PAGE_TITLE_BO,
                PAGE_TEXT,
                MEDIA_ID2,
                PAGE_CLEAR_URL,
                PAGE_URL_EXTERNE_MODE_OUVERTURE
            from #pref#_page p
            inner join #pref#_page_version pv
                on (p.PAGE_ID = pv.PAGE_ID
                    and p.PAGE_".$sVersion."_VERSION = pv.PAGE_VERSION
                    and p.LANGUE_ID = pv.LANGUE_ID)
            where p.SITE_ID=:SITE_ID
            and p.LANGUE_ID=:LANGUE_ID
            and p.PAGE_PARENT_ID =:PAGE_ID
            and p.PAGE_STATUS = :PAGE_STATUS
            and pv.PAGE_DISPLAY = :PAGE_DISPLAY
            and pv.STATE_ID = :STATE_ID
            order by p.PAGE_ORDER asc
            limit 0,10";

        $this->value = $oConnection->queryTab($sSQL, $aBind);
    }
}
