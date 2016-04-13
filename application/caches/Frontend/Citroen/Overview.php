<?php
/**
 * Fichier de Pelican_Cache : renvois les rubriques filles
 * @package Cache
 * @param 0 SITE_ID         id du site
 * @param 1 LANGUE_ID       langue du site
 * @param 2 PAGE_ID         pid de la page 
 * @param 3 CURRENT         par default
 * @subpackage Pelican
 */
class Frontend_Citroen_Overview extends Pelican_Cache {

    var $duration = DAY;

    /*
     * Valeur ou objet a mettre en Pelican_Cache
     */
    function getValue()
    {
        // Collecte paramètres
        $siteId   = $this->params[0];
        $langueId = $this->params[1];
        $pageId   = $this->params[2];
        $sVersion = ($this->params[3])?$this->params[3]:'CURRENT';
        $isMobile = isset($this->params[4]) ? $this->params[4] : null;
        
        // Récupération données
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID']   = $siteId;
        $aBind[':LANGUE_ID'] = $langueId;
        $aBind[':PAGE_ID']   = $pageId;
        $sSQL = "
            select
                * 
            from #pref#_page p
            inner join #pref#_page_version pv
                on (p.PAGE_ID = pv.PAGE_ID
                    and p.PAGE_".$sVersion."_VERSION = pv.PAGE_VERSION
                    and p.LANGUE_ID = pv.LANGUE_ID)
            where p.SITE_ID=:SITE_ID
            and p.LANGUE_ID=:LANGUE_ID
            and p.PAGE_PARENT_ID =:PAGE_ID
            and p.PAGE_STATUS = 1
            and pv.PAGE_DISPLAY = 1
            and pv.STATE_ID = 4
            order by p.PAGE_ORDER asc
            limit 0, 8;";
        $result = $oConnection->queryTab($sSQL, $aBind);
        
        // Filtrage des pages web/mobile
        if (is_array($result)) {
            foreach ($result as $key => $aPage) {
                $return = Pelican_Cache::fetch("Frontend/Page/Zone", array(
                    $aPage['PAGE_ID'], 
                    $_SESSION[APP]['LANGUE_ID'], 
                    Pelican::getPreviewVersion(), 
                    'desktop'
                ));
                if (is_array($return['zones'][Pelican::$config['AREA']['DYNAMIQUE']])) {
                    foreach ($return['zones'][Pelican::$config['AREA']['DYNAMIQUE']] as $tranche) {
                        if ($tranche[0]['ZONE_ID'] == Pelican::$config['ZONE']['FORMULAIRE']) {
                            // Tranche "Uniquement Mobile" + accès non mobile => élément exclu
                            if ($tranche[0]['ZONE_ATTRIBUT'] == '0' || $tranche[0]['ZONE_ATTRIBUT'] == '2' && !$isMobile) {
                                unset($result[$key]);
                            }
                            // Tranche "Uniquement Web" + accès mobile => élément exclu
                            if ($tranche[0]['ZONE_ATTRIBUT'] == '0' || $tranche[0]['ZONE_ATTRIBUT'] == '1' && $isMobile) {
                                unset($result[$key]);
                            }
                        }
                    }
                }
            }
        }
        
        $this->value = $result;
    }
}
?>