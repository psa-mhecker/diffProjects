<?php
/**
 * Fichier de Pelican_Cache : Themes technologie et le detail.
 */
class Frontend_Citroen_Technologie_Gallerie extends Pelican_Cache
{
    public $duration = HOUR;
    /*
     * Valeur ou objet a mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $sVersion = ($this->params[2]) ? $this->params[2] : 'CURRENT';
        $aBind[':PAGE_PARENT_ID'] = $this->params[3];

        $aBind[':TEMPLATE_PAGE_ID'] = Pelican::$config['TEMPLATE_PAGE']['TECHNOLOGIE_DETAIL'];
        $aBind[':ZONE_ID'] = Pelican::$config['ZONE']['VISUEL_TECHNOLOGIE'];

        $aTabResult = array();

        $sSQL_themes = "
            select THEME_TECHNOLOGIE_GALLERIE_ID
            from #pref#_theme_technogie_gallerie
            where LANGUE_ID=:LANGUE_ID
            and SITE_ID=:SITE_ID
			ORDER BY THEME_TECHNOLOGIE_GALLERIE_ORDER
            ";
        $aResultsThemes = $oConnection->queryTab($sSQL_themes, $aBind);

        if (!empty($aResultsThemes) && count($aResultsThemes)>0) {
            foreach ($aResultsThemes as $aThemes) {
                $sSQL_detail = "
                    select
                        pv.PAGE_ID,
                        pv.PAGE_TITLE_BO,
                        pv.PAGE_CLEAR_URL,
                        m.MEDIA_PATH,
                        m.MEDIA_ALT,
                        pz.ZONE_TEXTE,
                        ttg.THEME_TECHNOLOGIE_GALLERIE_ID,
                        ttg.THEME_TECHNOLOGIE_GALLERIE_LABEL
                    from #pref#_page p
                    inner join #pref#_page_version pv
                        on (p.PAGE_ID = pv.PAGE_ID
                            and p.PAGE_".$sVersion."_VERSION = pv.PAGE_VERSION
                            and p.LANGUE_ID = pv.LANGUE_ID)
                    inner join #pref#_page_zone pz
                        on (pz.PAGE_ID = pv.PAGE_ID
                            and pz.PAGE_VERSION = pv.PAGE_VERSION
                            and pz.LANGUE_ID = pv.LANGUE_ID)
                    inner join #pref#_theme_technogie_gallerie ttg
                        on (pz.ZONE_TOOL = ttg.THEME_TECHNOLOGIE_GALLERIE_ID)
                    inner join #pref#_zone_template zt
                        on (zt.ZONE_TEMPLATE_ID = pz.ZONE_TEMPLATE_ID)
                    inner join #pref#_media m
                        on (m.MEDIA_ID = pz.MEDIA_ID)
                    where pv.TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID
                    and p.PAGE_PARENT_ID = :PAGE_PARENT_ID
                    and zt.ZONE_ID = :ZONE_ID
                    and pv.STATE_ID = 4
                    and p.PAGE_STATUS = 1
                    and ttg.THEME_TECHNOLOGIE_GALLERIE_ID = ".$aThemes['THEME_TECHNOLOGIE_GALLERIE_ID']."
                    order by p.PAGE_ORDER asc";

                $aDetailThemes = $oConnection->queryTab($sSQL_detail, $aBind);

                if (!empty($aDetailThemes) && count($aDetailThemes)>0) {
                    foreach ($aDetailThemes as $aDetail) {
                        $aTabResult[$aDetail['THEME_TECHNOLOGIE_GALLERIE_LABEL']][] = array(
                            'PAGE_ID' => $aDetail['PAGE_ID'],
                            'PAGE_TITLE_BO' => $aDetail['PAGE_TITLE_BO'],
                            'PAGE_CLEAR_URL' => $aDetail['PAGE_CLEAR_URL'],
                            'MEDIA_PATH' => $aDetail['MEDIA_PATH'],
                            'MEDIA_ALT' => $aDetail['MEDIA_ALT'],
                            'ZONE_TEXTE' => $aDetail['ZONE_TEXTE'],
                        );
                    }
                }
            }
        }

        $this->value = $aTabResult;
    }
}
