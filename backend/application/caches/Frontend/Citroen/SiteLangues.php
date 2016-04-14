<?php
/**
 * Fichier de Pelican_Cache : Langues du site.
 */
class Frontend_Citroen_SiteLangues extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $sSQL = "
            select
                l.LANGUE_ID,
                l.LANGUE_CODE,
                l.LANGUE_TRANSLATE
            from #pref#_site_language sl
            inner join #pref#_language l
                on (sl.LANGUE_ID = l.LANGUE_ID)
            where sl.SITE_ID = :SITE_ID
            order by LANGUE_ID asc
        ";
        $aResultListLang    =   $oConnection->queryTab($sSQL, $aBind);

        // Suppression de la langue italienne pour le site suisse suite au ticket CPW-3152
        foreach ($aResultListLang as $key => $lang) {
            if ($lang['LANGUE_CODE'] == 'it' && $_SESSION[APP]['SITE_ID'] == Pelican::$config['SITE']['SUISSE']) {
                unset($aResultListLang[$key]);
            }
        }
        $this->value        =   $aResultListLang;
    }
}
