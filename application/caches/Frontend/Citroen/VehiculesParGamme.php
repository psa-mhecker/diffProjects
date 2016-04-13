<?php
/**
 * Fichier de Pelican_Cache : Vehicules par Gamme
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_VehiculesParGamme extends Pelican_Cache
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
        $aBind[':PAGE_PARENT_ID'] = $this->params[4];
        $aBind[':VEHICULE_GAMME_LABEL'] = $oConnection->dateStringToSql($this->params[2]);
        $sVersion = ($this->params[3]) ? $this->params[3] : "CURRENT";
        $aBind[':TEMPLATE_PAGE_ID'] = Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'];
        $aBind[':ZONE_ID'] = Pelican::$config['ZONE']['SELECTEUR_DE_TEINTE'];
        $aBind[':PAGE_STATUS'] = 1;
        $aBind[':STATE_ID'] = 4;
        $sSQL = "
            select distinct
                pv.PAGE_TITLE,
                pv.PAGE_TITLE_BO,
                pv.PAGE_CLEAR_URL,
                if (v.VEHICULE_LCDV6_CONFIG, (
                    select PRICE_DISPLAY
                    from #pref#_ws_prix_finition_version wpfv
                    where wpfv.SITE_ID = v.SITE_ID
                    and wpfv.LANGUE_ID = v.LANGUE_ID
                    and wpfv.LCDV6 = v.VEHICULE_LCDV6_CONFIG
                    and wpfv.GAMME = v.VEHICULE_GAMME_CONFIG
                    order by PRICE_NUMERIC asc
                    limit 0,1),
                v.VEHICULE_CASH_PRICE) as PRIX,
                ifnull(v.VEHICULE_LCDV6_CONFIG, v.VEHICULE_LCDV6_MANUAL) as LCDV6,
                v.VEHICULE_ID,
                v.VEHICULE_LABEL,
                v.VEHICULE_CASH_PRICE_TYPE,
                v.VEHICULE_DISPLAY_CASH_PRICE,
                v.MODE_OUVERTURE_SHOWROOM,
                IFNULL(v.VEHICULE_GAMME_CONFIG, v.VEHICULE_GAMME_MANUAL) as GAMME,
                m.MEDIA_ID,
                m.MEDIA_PATH,
                m.MEDIA_ALT
            from #pref#_page p
            inner join #pref#_page_version pv
                on (pv.PAGE_ID = p.PAGE_ID
                    and pv.LANGUE_ID = p.LANGUE_ID
                    and pv.PAGE_VERSION = p.PAGE_" . $sVersion . "_VERSION)
            inner join #pref#_zone_template zt
                on (zt.TEMPLATE_PAGE_ID = pv.TEMPLATE_PAGE_ID)
            inner join #pref#_page_zone pz
                on (pz.PAGE_ID = pv.PAGE_ID
                    and pz.LANGUE_ID = pv.LANGUE_ID
                    and pz.PAGE_VERSION = pv.PAGE_VERSION
                    and pz.ZONE_TEMPLATE_ID = zt.ZONE_TEMPLATE_ID)
            inner join #pref#_vehicule v
                on (v.VEHICULE_ID = pz.ZONE_ATTRIBUT and v.LANGUE_ID = :LANGUE_ID and v.SITE_ID = :SITE_ID)
            inner join #pref#_media m
                on (m.MEDIA_ID = v.VEHICULE_MEDIA_ID_THUMBNAIL)
            where p.PAGE_STATUS = :PAGE_STATUS
            and pv.STATE_ID = :STATE_ID
            and pv.TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID
            and zt.ZONE_ID = :ZONE_ID
            and p.SITE_ID = :SITE_ID
            and p.LANGUE_ID = :LANGUE_ID";
		if ($this->params[4]) {
            $sSQL .= "
                and p.PAGE_PARENT_ID = :PAGE_PARENT_ID ";
        }
        if ($this->params[2]) {
            $sSQL .= "
                and v.VEHICULE_GAMME_LABEL = :VEHICULE_GAMME_LABEL
                order by p.PAGE_ORDER asc";
        }
        else {
            $sSQL .= "
                order by v.VEHICULE_LABEL asc";
        }
        $this->value = $oConnection->queryTab($sSQL, $aBind);
    }

}