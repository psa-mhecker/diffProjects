<?php
/**
 * Fichier de Pelican_Caches_Citroen : Gamme
 *
 * Cache remontant les informations des véhicules. Seules les informations
 * concernant vehicule_gamme sont remontées
 *
 * @package Cache
 * @subpackage Pelican
 * @author  Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since   17/07/2013
 * @param 0 SITE_ID                 Identifiant du site
 * @param 1 LANGUE_ID               Identifiant de la langue
 * @param 2 VEHICULE_ID             Identifiant du véhicule
 * @param 3 VERSION                 Version de la page du véhicule à remonter CURRENT ou DRAFT
 *
 */
class Frontend_Citroen_VehiculeShowroomById extends Pelican_Cache {

    public $duration = DAY;


    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue() {
        $oConnection = Pelican_Db::getInstance();

        /* Mise en Bind des paramètres */
        if ( !is_null($this->params[0]) ){
            $aBind[':SITE_ID'] = (int)$this->params[0];
        }
        if ( !is_null($this->params[1]) ){
            $aBind[':LANGUE_ID'] = (int)$this->params[1];
        }
        if ( !is_null($this->params[2]) ){
            $aBind[':VEHICULE_ID'] = (int)$this->params[2];
        }
        /* Si la version n'est pas indiquée dans les paramètres, on prend la
         * version courante
         */
        $sVersion = ($this->params[3]) ? $this->params[3] : "CURRENT";

        if ( !is_null($this->params[4]) ){
            $aBind[':PAGE_CONF_ID'] = (int)$this->params[4];
        }
        
        $minPrice = "";
        if ( !is_null($this->params[5]) ){
            $aBind[':MIN_PRICE'] = (int)$this->params[5];
            $minPrice = " AND wpfv.PRICE_NUMERIC >= :MIN_PRICE";
        }
        
        // conditionner sur le PAGE_STATUS
        // sans contrôler si en mode prévisu
        if($sVersion == 'CURRENT'){ // mode normal
            $cond_status = "p.PAGE_STATUS = 1";
        }else{ // mode prévisu
            $cond_status = "1 = 1";
        }

        /* Récupération du template_page_id du gabarit SHOWROOM_ACCUEIL et
         * le ZONE_TEMPLATE_ID du bloc SHOWROOM_ACCUEIL_SELECT_TEINTE pour
         * récupérer le véhicule associé à la page
         */
        $aBind[':TEMPLATE_PAGE_ID'] = Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'];
        $aBind[':ZONE_ID_SELECTEUR_TEINTES'] = Pelican::$config['ZONE']['SELECTEUR_DE_TEINTE'];
        $aBind[':ZONE_ID_POINTS_FORTS'] = Pelican::$config['ZONE']['POINTS_FORTS'];
        $aBind[':STATE_ID'] = 4;

        /* Récupération du paramétrage Hors Taxe, Toutes taxes */
        $aBind[':CASHPRICEHT'] = $oConnection->strToBind(Pelican::$config['CASH_PRICE_TAXE']['CASH_PRICE_HT']);
        $aBind[':CASHPRICETTC'] = $oConnection->strToBind(Pelican::$config['CASH_PRICE_TAXE']['CASH_PRICE_TTC']);
        /* Création de la requête principale ramenant les informations sur le véhicule et
         * de la page associée ainsi que la récupération des informations nécessaires
         * présentes dans le configuration (WebService)
         */
        $sSqlQuery = <<<SQL
                SELECT
                    v.VEHICULE_ID,
                    v.VEHICULE_LABEL,
                    v.VEHICULE_GAMME_CONFIG,
                    v.VEHICULE_LCDV6_CONFIG,
					v.VEHICULE_LCDV6_MTCFG,
                    IF  (v.VEHICULE_LCDV6_CONFIG,
                            (
                            SELECT PRICE_DISPLAY
                            FROM #pref#_ws_prix_finition_version wpfv
                            WHERE wpfv.SITE_ID = v.SITE_ID
                                AND concat('',wpfv.LANGUE_ID,'') = concat('',v.LANGUE_ID,'')
                                AND wpfv.LCDV6 = v.VEHICULE_LCDV6_CONFIG
                                AND wpfv.GAMME = v.VEHICULE_GAMME_CONFIG
                                {$minPrice}
                                ORDER BY PRICE_NUMERIC asc
                            LIMIT 0,1
                            ),
                            v.VEHICULE_CASH_PRICE
                        ) as CASH_PRICE,
                    IFNULL(v.VEHICULE_LCDV6_CONFIG, v.VEHICULE_LCDV6_MANUAL) as LCDV6,
                    IFNULL(v.VEHICULE_GAMME_CONFIG, v.VEHICULE_GAMME_MANUAL) as GAMME,
                    IF (
                        STRCMP(v.VEHICULE_CASH_PRICE_TYPE,'CASH_PRICE_HT'),
                        :CASHPRICETTC,
                        :CASHPRICEHT
                    ) as CASH_PRICE_TYPE,
                    v.VEHICULE_DISPLAY_CASH_PRICE,
                    v.VEHICULE_CASH_PRICE_TYPE,
                    v.VEHICULE_CASH_PRICE_LEGAL_MENTION,
                    v.VEHICULE_DISPLAY_CREDIT_PRICE,
                    v.VEHICULE_USE_FINANCIAL_SIMULATOR,
                    v.VEHICULE_CREDIT_PRICE_NEXT_RENT,
                    v.VEHICULE_CREDIT_PRICE_NEXT_RENT_LEGAL_MENTION,
                    v.VEHICULE_CREDIT_PRICE_FIRST_RENT,
                    v.VEHICULE_CREDIT_PRICE_FIRST_RENT_LEGAL_MENTION,
                    vg.MODEL_LABEL as WS_MODEL_LABEL,
                    vg.MODEL_BODY_LABEL as WS_MODEL_BODY_LABEL,
                    m1.MEDIA_PATH as THUMBNAIL_PATH,
                    m1.MEDIA_ALT as THUMBNAIL_ALT,
                    m2.MEDIA_PATH as BCKGRD1_PATH,
                    m2.MEDIA_ALT as BCKGRD1_ALT,
                    m3.MEDIA_PATH as BCKGRD2_PATH,
                    m3.MEDIA_ALT as BCKGRD2_ALT,
                    m4.MEDIA_PATH as BCKGRD3_PATH,
                    m4.MEDIA_ALT as BCKGRD3_ALT,
                    m5.MEDIA_PATH as BCKGRDMOB_PATH,
                    m5.MEDIA_ALT as BCKGRDMOB_ALT,
                    pv.PAGE_TITLE,
                    pv.PAGE_TITLE_BO,
                    pv.PAGE_CLEAR_URL,
                    p.PAGE_ORDER
                FROM
                    #pref#_page p
                        INNER JOIN #pref#_page_version pv
                            ON (pv.PAGE_ID = p.PAGE_ID
                                AND pv.LANGUE_ID = p.LANGUE_ID
                                AND pv.PAGE_VERSION = p.PAGE_{$sVersion}_VERSION)
                        INNER JOIN #pref#_zone_template zt
                            ON (zt.TEMPLATE_PAGE_ID = pv.TEMPLATE_PAGE_ID)
                        INNER JOIN #pref#_page_zone pz
                            ON (pz.PAGE_ID = pv.PAGE_ID
                                AND pz.LANGUE_ID = pv.LANGUE_ID
                                AND pz.PAGE_VERSION = pv.PAGE_VERSION
                                AND pz.ZONE_TEMPLATE_ID = zt.ZONE_TEMPLATE_ID)

                        INNER JOIN #pref#_vehicule v
                            ON (v.VEHICULE_ID = pz.ZONE_ATTRIBUT)
                        LEFT JOIN #pref#_ws_vehicule_gamme vg
                            ON (vg.LCDV6 = LCDV6
                                AND vg.GAMME = GAMME
                                AND vg.SITE_ID = :SITE_ID
                                AND vg.LANGUE_ID = :LANGUE_ID  )
                        INNER JOIN #pref#_media m1 ON (m1.MEDIA_ID = v.VEHICULE_MEDIA_ID_THUMBNAIL)
                        INNER JOIN #pref#_media m2 ON (m2.MEDIA_ID = v.VEHICULE_MEDIA_ID_WEB1)
                        LEFT JOIN #pref#_media m3 ON (m3.MEDIA_ID = v.VEHICULE_MEDIA_ID_WEB2)
                        LEFT JOIN #pref#_media m4 ON (m4.MEDIA_ID = v.VEHICULE_MEDIA_ID_WEB3)
                        LEFT JOIN #pref#_media m5 ON (m5.MEDIA_ID = v.VEHICULE_MEDIA_ID_MOB)

                WHERE
                    v.VEHICULE_ID = :VEHICULE_ID
                    AND zt.ZONE_ID = :ZONE_ID_SELECTEUR_TEINTES
                    AND v.SITE_ID = :SITE_ID
                    AND v.LANGUE_ID = :LANGUE_ID
                    AND $cond_status
                    AND pv.STATE_ID = :STATE_ID
                ORDER BY
                    p.PAGE_ORDER
SQL;

        $aResults['VEHICULE'] = $oConnection->queryRow($sSqlQuery,$aBind);

        /* Récupération des teintes */
        $sSqlQuery = <<<SQL
                SELECT
                    vc.VEHICULE_ID,
                    vc.VEHICULE_COULEUR_LABEL,
                    vc.PAGE_ZONE_MULTI_ORDER,
                    m1.MEDIA_PATH as PICTO_PATH,
                    m1.MEDIA_ALT as PICTO_ALT,
                    m2.MEDIA_PATH as CARWEB1_PATH,
                    m2.MEDIA_ALT as CARWEB1_ALT,
                    m3.MEDIA_PATH as CARWEB2_PATH,
                    m3.MEDIA_ALT as CARWEB2_ALT,
                    m4.MEDIA_PATH as CARWEB3_PATH,
                    m4.MEDIA_ALT as CARWEB3_ALT,
                    m5.MEDIA_PATH as CARMOB1_PATH,
                    m5.MEDIA_ALT as CARMOB1_ALT
                FROM
                    #pref#_vehicule_couleur vc
                        INNER JOIN #pref#_media m1 ON (m1.MEDIA_ID = vc.VEHICULE_COULEUR_MEDIA_ID_PICTO)
                        INNER JOIN #pref#_media m2 ON (m2.MEDIA_ID = vc.VEHICULE_COULEUR_MEDIA_ID_CAR_WEB1)
                        LEFT JOIN #pref#_media m3 ON (m3.MEDIA_ID = vc.VEHICULE_COULEUR_MEDIA_ID_CAR_WEB2)
                        LEFT JOIN #pref#_media m4 ON (m4.MEDIA_ID = vc.VEHICULE_COULEUR_MEDIA_ID_CAR_WEB3)
                        INNER JOIN #pref#_media m5 ON (m5.MEDIA_ID = vc.VEHICULE_COULEUR_MEDIA_ID_CAR_MOB1)
                WHERE
                    vc.VEHICULE_ID = :VEHICULE_ID
                    AND vc.SITE_ID = :SITE_ID
                    AND vc.LANGUE_ID = :LANGUE_ID

                ORDER BY
                    vc.VEHICULE_ID, vc.VEHICULE_COULEUR_ORDER
SQL;

        /* Ajout des teintes au tableau de sortie */
        $aResults['COLORS'] = $oConnection->queryTab($sSqlQuery,$aBind);
        
        /* Récupération des teintes */
        $sSqlQuery = <<<SQL
                SELECT
                    vcs.*
                FROM
                    #pref#_vehicule_cta_showroom vcs
                WHERE
                    vcs.VEHICULE_ID = :VEHICULE_ID
                    AND vcs.SITE_ID = :SITE_ID
                    AND vcs.LANGUE_ID = :LANGUE_ID
                ORDER BY
                    vcs.VEHICULE_ID, vcs.PAGE_ZONE_MULTI_ORDER
SQL;

        /* Ajout des CTA Showroom au tableau de sortie */
        $aResults['CTA'] = $oConnection->queryTab($sSqlQuery,$aBind);
        if(sizeof($aResults['CTA']) > 0){
            $temp = '';
            $temps = array();
            
            $aPageZone = Pelican_Cache::fetch('Frontend/Page/Zone',array(
                $this->params[6],
                $aBind[':LANGUE_ID'],
                "CURRENT"
            ));

            $aPageVehicule = Pelican_Cache::fetch('Frontend/Citroen/VehiculeById',array(
                $aPageZone["areas"][0]["PAGE_VEHICULE"],
                $aPageZone["areas"][0]["SITE_ID"],
                $aPageZone["areas"][0]["LANGUE_ID"]
                 ));

            $aConfiguration = Pelican_Cache::fetch("Frontend/Citroen/Configuration", array(
                    $aPageZone["areas"][0]["SITE_ID"],
                    $aPageZone["areas"][0]["LANGUE_ID"],
                    Pelican::getPreviewVersion()
            ));

            $lcdvGamme = Citroen\GammeFinition\VehiculeGamme::getLCDV6Gamme(  
                    $aPageZone["areas"][0]["PAGE_VEHICULE"],
                    $aPageZone["areas"][0]["SITE_ID"],
                    $aPageZone["areas"][0]["LANGUE_ID"]
                    );
            if($aPageVehicule){
                $aPageVehicule = array_merge($aPageVehicule,$lcdvGamme);
            }
            
            foreach($aResults['CTA'] as $key=>$value){
                if(!empty($value['VEHICULE_CTA_SHOWROOM_OUTIL'])){
                    $sql='SELECT *
                        FROM
                            #pref#_barre_outils
                        WHERE
                            BARRE_OUTILS_ID = '.$value['VEHICULE_CTA_SHOWROOM_OUTIL'];

                    $temp = $oConnection->queryTab($sql, $aBind);
                    $aResults['CTA'][$key] = $temp[0];
                }else{
                    $aResults['CTA'][$key]['VEHICULE_CTA_SHOWROOM_URL'] = $this->replaceTags($value['VEHICULE_CTA_SHOWROOM_URL'], $aPageVehicule, $aConfiguration);
                }
            }
        }
        $this->value = $aResults;
    }
    
    function replaceTags($url , $aVehicule, $aConfiguration){
        //Presence d'un tags à remplacer dans l'url
        if(preg_match('/##([0-9]|[a-zA-Z_-])*##/', $url)){
            
             if(preg_match('~(##URL_CONFIGURATEUR##|##URL_CONFIGURATEUR_PRO##)~i', $url)){
                //url pour le configurateur
               $url =  \Citroen\Configurateur::getConfigurateurUrl($aVehicule,$aConfiguration,true);
            }else{
                //url standard
                $tags = array(
                        '#LCVD#'=> $aVehicule['LCDV6'],
                        '##LCDV_CURRENT##' => $aVehicule['LCDV6']
                    );
                $url= \Citroen\Html\Util::replaceTagsInUrl($url ,$tags);
            }
                
        }
        return $url;
    }
}