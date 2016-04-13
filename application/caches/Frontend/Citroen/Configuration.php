<?php
/**
 * Fichier de Pelican_Cache : Configuration
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Configuration extends Pelican_Cache
{

    var $duration = DAY;
    
    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $sVersion = ($this->params[2]) ? $this->params[2] : "CURRENT";
        $aBind[':ZONE_ID'] = Pelican::$config['ZONE']['CONFIGURATION'];
        $sSQL = "
            select pz.*
            from psa_page p
            inner join psa_page_version pv
                on (p.PAGE_ID = pv.PAGE_ID
                    and p.LANGUE_ID = pv.LANGUE_ID
                    and p.PAGE_" . $sVersion . "_VERSION = pv.PAGE_VERSION)
            inner join psa_zone_template zt
                on (zt.TEMPLATE_PAGE_ID = pv.TEMPLATE_PAGE_ID)
            inner join psa_page_zone pz
                on (pz.PAGE_ID = pv.PAGE_ID
                    and pz.LANGUE_ID = pv.LANGUE_ID
                    and pz.PAGE_VERSION = pv.PAGE_VERSION
                    and pz.ZONE_TEMPLATE_ID = zt.ZONE_TEMPLATE_ID)
            where zt.ZONE_ID = :ZONE_ID
            and p.SITE_ID = :SITE_ID
            and p.PAGE_STATUS = 1
            and pv.STATE_ID = 4
            and p.LANGUE_ID = :LANGUE_ID";
        $aResults = $oConnection->queryRow($sSQL, $aBind);
        $aValues = array();
        if ($aResults) {
            $aValues['EMISSION'] = $aResults['ZONE_TITRE'];
            $aValues['DEVISE_PAYS'] = $aResults['ZONE_TITRE2'];
            $aValues['CONSOMMATION'] = $aResults['ZONE_TITRE3'];
            $aValues['TAILLE'] = $aResults['ZONE_TITRE4'];
            $aValues['ACTIVATION_PRIX_CREDITS'] = $aResults['ZONE_PARAMETERS'];
            $aValues['URL_CONFIGURATEUR'] = $aResults['ZONE_TITRE5'];
            $aValues['URL_CONFIGURATEUR_MOBILE'] = $aResults['ZONE_TITRE6'];
            $aValues['URL_DEMANDE_ESSAI'] = $aResults['ZONE_TITRE7'];
            $aValues['URL_DEMANDE_ESSAI_MOBILE'] = $aResults['ZONE_TITRE8'];
            $aValues['URL_DEMANDE_OFFRE_COMMERCIALE'] = $aResults['ZONE_TITRE9'];
            $aValues['URL_DEMANDE_OFFRE_COMMERCIALE_MOBILE'] = $aResults['ZONE_TITRE10'];
            $aValues['URL_DEMANDE_BROCHURE'] = $aResults['ZONE_TITRE11'];
            $aValues['URL_DEMANDE_BROCHURE_MOBILE'] = $aResults['ZONE_URL'];
            $aValues['URL_BOUTIQUE_ACCESSOIRE'] = $aResults['ZONE_URL2'];
            $aValues['URL_BOUTIQUE_ACCESSOIRE_MOBILE'] = $aResults['ZONE_LABEL2'];
            $aValues['FIL_DARIANE_HOME'] = $aResults['ZONE_TITRE12'];
            $aValues['ZOOM_GMAP'] = $aResults['ZONE_ATTRIBUT'];
            $aValues['LATITUDE_GMAP'] = $aResults['ZONE_MAP_LATITUDE'];
            $aValues['LONGITUDE_GMAP'] = $aResults['ZONE_MAP_LONGITUDE'];
            $aValues['SHOW_SCROLL_TOP'] = $aResults['ZONE_TITRE16'];
            $aValues['URL_REBOND_CFG_PRO'] = $aResults['ZONE_TITRE19'];
            $aValues['URL_CARSTORE'] = $aResults['ZONE_TITRE14'];
            $aValues['URL_CARSTORE_PRO'] = $aResults['ZONE_TITRE20'];
            $aValues['URL_REBOND_CARSTORE'] = $aResults['ZONE_TITRE21'];
            
        }
        $this->value = $aValues;
    }
}