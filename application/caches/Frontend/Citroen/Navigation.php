<?php
pelican_import('Hierarchy');

class Frontend_Citroen_Navigation extends Citroen_Cache
{

    var $duration = DAY;
	
	public $isPersistent = true;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $sType = $this->params[2]; // PAGE_DISPLAY_NAV: Navigation, PAGE_DISPLAY: Plan du site

        if($this->params[3] && $this->params[3] != "PLAN_SITE")
        {
            $limit = $this->params[3];
        }
        elseif( $this->params[3] == "PLAN_SITE" )
        {
            $plan_site = 1;
        }
        $isMobile = isset($this->params[4]) ? $this->params[4] : Pelican_Controller::isMobile(); 

        $sSQL = "
            select
                p.*,
                pv.*,
                p.PAGE_ID as \"id\",
                p.PAGE_PARENT_ID as \"pid\",
                pv.PAGE_VERSION as \"pv\",
                pv.PAGE_TITLE_BO as \"lib\",
                pv.PAGE_URL_EXTERNE,
                pv.PAGE_OUVERTURE_DIRECT,
                pv.PAGE_URL_EXTERNE_MODE_OUVERTURE,
                p.PAGE_ORDER as \"order\",
                m.MEDIA_PATH as \"img\",
                m.MEDIA_ALT as \"imgAlt\",
                pv.MEDIA_ID2 as \"media_expand\"
            from #pref#_page p
            inner join #pref#_page_version pv
            on (p.PAGE_ID = pv.PAGE_ID
                and p.PAGE_CURRENT_VERSION = pv.PAGE_VERSION
                and p.LANGUE_ID = pv.LANGUE_ID
            )
            left join #pref#_media m
            on (pv.MEDIA_ID2 = m.MEDIA_ID)
            where p.SITE_ID = :SITE_ID
            and p.LANGUE_ID = :LANGUE_ID";
        if(!$isMobile){
            $sSQL .= "
                and pv." . $sType . " = 1";
        }
        $sSQL .= "
            and pv.STATE_ID = 4
            and p.PAGE_STATUS = 1
            order by p.PAGE_ORDER asc
        ";

        $MENU = $oConnection->queryTab($sSQL, $aBind);

        if(is_array($MENU)){
            foreach($MENU as $key => $aPage){
                $return = Pelican_Cache::fetch("Frontend/Page/Zone", array(
                    $aPage['PAGE_ID'], 
                    $_SESSION[APP]['LANGUE_ID'], 
                    Pelican::getPreviewVersion(), 
                    'desktop'
                ));
                if(is_array($return['zones'][Pelican::$config['AREA']['DYNAMIQUE']])){
                    foreach( $return['zones'][Pelican::$config['AREA']['DYNAMIQUE']] as $tranche){
                        if( $tranche[0]['ZONE_ID'] == Pelican::$config['ZONE']['FORMULAIRE']){
                            // Pour la version WEB
                            if( $tranche[0]['ZONE_ATTRIBUT'] == '0' || $tranche[0]['ZONE_ATTRIBUT'] == '2' && !$isMobile){
                                unset($MENU[$key]);
                            }
                            
                            // Pour la version Mobile
                            if( $tranche[0]['ZONE_ATTRIBUT'] == '0' || $tranche[0]['ZONE_ATTRIBUT'] == '1' && $isMobile){
                                unset($MENU[$key]);
                            }
                        }
                    }
                }
            }
        }
        $oTree = Pelican_Factory::getInstance('Hierarchy', "menu", "id", "pid");
        $oTree->addTabNode($MENU);
        $oTree->setOrder("order", "ASC");
        $i = - 1;
        if ($oTree->aNodes) {
            foreach ($oTree->aNodes as $menu) {
                
                    if ($menu->level == 3) {
                        $i++;
    					$j = -1;
                        if($plan_site != 1 || ($plan_site == 1 && $menu->PAGE_DISPLAY_PLAN == 1))
                    {
                        $aMenu[$i]["n1"] = $this->getTreeParams($menu);
                    }
                    }
                    elseif ($menu->level == 4) {
                        if ($menu->id) {
    						$j++;
    						$k = -1;
                            if(count($aMenu[$i]["n1"]) > 0)
                            {
                                if($plan_site != 1 || ($plan_site == 1 && $menu->PAGE_DISPLAY_PLAN == 1))
                                {
                                    $aMenu[$i]["n2"][$j] = $this->getTreeParams($menu);
                                }
                            }   
                        }
                    }
                 
                    if(count($aMenu[$i]["n2"][$j]) > 0)
                    {
        				if ($sType == 'PAGE_DISPLAY' && $menu->level == 5) {
            					if ($menu->id) 
                                {
            						$k++;
                             if($plan_site != 1 || ($plan_site == 1 && $menu->PAGE_DISPLAY_PLAN == 1))
                                {
            						$aMenu[$i]['n2'][$j]['n3'][$k] = $this->getTreeParams($menu);
                                }
            				}
        				}
                    }
                    if(count($aMenu[$i]['n2'][$j]['n3'][$k]) > 0)
                    {
    				if ($sType == 'PAGE_DISPLAY' && $menu->level == 6) {
    					if ($menu->id) {
                            if($plan_site != 1 || ($plan_site == 1 && $menu->PAGE_DISPLAY_PLAN == 1))
                             {
        						$aMenu[$i]['n2'][$j]['n3'][$k]['n4'][] = $this->getTreeParams($menu);
                            }
    					}
    				}
                }
                
            }
        }
        if ($sType == 'PAGE_DISPLAY_NAV') {
            if ($aMenu) {
                if($limit != 0){
                    $aMenu2 = array_chunk($aMenu, $limit);
                    $aMenu = $aMenu2[0];
                }

                foreach ($aMenu as $i => $n1) {

                    if ($n1['n1']['expand']=='1') {
                        if ($n1['n2']) {
                            foreach ($n1['n2'] as $k => $n2) {
                                $aBind[':PAGE_PARENT_ID'] = $n2['id'];
                                $aBind[':ZONE_ID'] = Pelican::$config['ZONE']['SELECTEUR_DE_TEINTE'];
                                  $sSQL = "
                                    select
                                       DISTINCT(v.VEHICULE_CATEG_LABEL)
                                    from #pref#_page p
                                    inner join #pref#_page_version pv
                                        on (p.PAGE_ID = pv.PAGE_ID
                                            and p.PAGE_CURRENT_VERSION = pv.PAGE_VERSION
                                            and p.LANGUE_ID = pv.LANGUE_ID
                                        )
                                    inner join #pref#_zone_template zt
                                        on (zt.TEMPLATE_PAGE_ID = pv.TEMPLATE_PAGE_ID)
                                    inner join #pref#_page_zone pz
                                        on (pz.PAGE_ID = pv.PAGE_ID
                                            and pz.LANGUE_ID = pv.LANGUE_ID
                                            and pz.PAGE_VERSION = pv.PAGE_VERSION
                                            and pz.ZONE_TEMPLATE_ID = zt.ZONE_TEMPLATE_ID)
                                    inner join #pref#_vehicule v
                                        on (v.VEHICULE_ID = pz.ZONE_ATTRIBUT)
                                    left join #pref#_categ_vehicule cv
                                        on (
                                        v.VEHICULE_CATEG_LABEL = cv.CATEG_VEHICULE_LABEL

                                        AND cv.SITE_ID = v.SITE_ID
                                        AND cv.LANGUE_ID = v.LANGUE_ID
                                        )
                                    left join #pref#_media m
                                        on (pv.MEDIA_ID2 = m.MEDIA_ID)
									left join #pref#_media m2
                                        on (v.VEHICULE_MEDIA_ID_THUMBNAIL = m2.MEDIA_ID)
                                    where p.PAGE_PARENT_ID = :PAGE_PARENT_ID
                                    and p.LANGUE_ID = :LANGUE_ID
                                    and pv.STATE_ID = 4
                                    and p.PAGE_STATUS = 1
                                    and v.SITE_ID = :SITE_ID
                                    and v.LANGUE_ID = :LANGUE_ID
                                    and zt.ZONE_ID = :ZONE_ID
                                    order by cv.CATEG_VEHICULE_ID,(CASE WHEN  v.VEHICULE_CATEG_LABEL = '' THEN 'zzzz' ELSE v.VEHICULE_CATEG_LABEL END), v.VEHICULE_CATEG_LABEL ASC
                                ";
                                $sSQL2 = "
                                    select
                                        p.*,
                                        pv.*,
                                        m.*,
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
                                        v.VEHICULE_ID,
                                        v.DISPLAY_CTA_DISCOVER,
                                        v.VEHICULE_CASH_PRICE_TYPE,
                                        v.VEHICULE_LABEL,
                                        v.VEHICULE_CATEG_LABEL,
                                        v.VEHICULE_LCDV6_CONFIG,
										v.VEHICULE_LCDV6_MTCFG,
                                        v.VEHICULE_LCDV6_MANUAL,
                                        v.VEHICULE_DISPLAY_CASH_PRICE,
                                        v.MODE_OUVERTURE_SHOWROOM,
                                        m2.MEDIA_PATH as VEHICULE_PATH
                                    from #pref#_page p
                                    inner join #pref#_page_version pv
                                        on (p.PAGE_ID = pv.PAGE_ID
                                            and p.PAGE_CURRENT_VERSION = pv.PAGE_VERSION
                                            and p.LANGUE_ID = pv.LANGUE_ID
                                        )
                                    inner join #pref#_zone_template zt
                                        on (zt.TEMPLATE_PAGE_ID = pv.TEMPLATE_PAGE_ID)
                                    inner join #pref#_page_zone pz
                                        on (pz.PAGE_ID = pv.PAGE_ID
                                            and pz.LANGUE_ID = pv.LANGUE_ID
                                            and pz.PAGE_VERSION = pv.PAGE_VERSION
                                            and pz.ZONE_TEMPLATE_ID = zt.ZONE_TEMPLATE_ID)
                                    inner join #pref#_vehicule v
                                        on (v.VEHICULE_ID = pz.ZONE_ATTRIBUT)
                                    left join #pref#_categ_vehicule cv
                                        on (v.VEHICULE_CATEG_LABEL = cv.CATEG_VEHICULE_LABEL
                                        AND cv.SITE_ID = v.SITE_ID
                                        AND cv.LANGUE_ID = v.LANGUE_ID
                                        )
                                    left join #pref#_media m
                                        on (pv.MEDIA_ID2 = m.MEDIA_ID)
                                    left join #pref#_media m2
                                        on (v.VEHICULE_MEDIA_ID_THUMBNAIL = m2.MEDIA_ID)
                                    where p.PAGE_PARENT_ID = :PAGE_PARENT_ID
                                    and p.LANGUE_ID = :LANGUE_ID
                                    and pv.STATE_ID = 4
                                    and p.PAGE_STATUS = 1
                                    and v.SITE_ID = :SITE_ID
                                    and v.LANGUE_ID = :LANGUE_ID
                                    and zt.ZONE_ID = :ZONE_ID
                                    order by p.PAGE_ORDER, v.VEHICULE_CATEG_LABEL asc
                                ";
                                $aMenu[$i]['n2'][$k]['categ'] = $oConnection->queryTab($sSQL, $aBind);
                                $aMenu[$i]['n2'][$k]['n3'] = $oConnection->queryTab($sSQL2, $aBind);
                                if ($aMenu[$i]['n2'][$k]['n3']) {
                                    foreach($aMenu[$i]['n2'][$k]['n3'] as $key => $value) {
                                        $aMenu[$i]['n2'][$k]['n3'][$key]['EXPAND_CTA'] =  Pelican_Cache::fetch("Frontend/Citroen/VehiculeExpandCTA", array(
                                            $value['VEHICULE_ID'],
                                           $aBind[':SITE_ID'],
                                            $_SESSION[APP]['LANGUE_ID']
                                        ));
                                        if ($aMenu[$i]['n2'][$k]['n3'][$key]['MEDIA_PATH']) {
                                            if ($n2['itemParLigne']=='2') {
                                                $aMenu[$i]['n2'][$k]['n3'][$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($aMenu[$i]['n2'][$k]['n3'][$key]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['EXPAND_VEHICULE_X4']);
                                            }
                                            else {
                                                $aMenu[$i]['n2'][$k]['n3'][$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($aMenu[$i]['n2'][$k]['n3'][$key]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['EXPAND_VEHICULE_X3']);
                                            }
                                        }
                                        if ($aMenu[$i]['n2'][$k]['n3'][$key]['VEHICULE_PATH']) {
                                            if ($n2['itemParLigne']=='2') {
                                                $aMenu[$i]['n2'][$k]['n3'][$key]['VEHICULE_PATH_FORMAT'] = Pelican_Media::getFileNameMediaFormat($aMenu[$i]['n2'][$k]['n3'][$key]['VEHICULE_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['EXPAND_VEHICULE_X4']);
                                            }
                                            else {
                                                $aMenu[$i]['n2'][$k]['n3'][$key]['VEHICULE_PATH_FORMAT'] = Pelican_Media::getFileNameMediaFormat($aMenu[$i]['n2'][$k]['n3'][$key]['VEHICULE_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['EXPAND_VEHICULE_X3']);
                                            }
                                        }
																				if ($aMenu[$i]['n2'][$k]['n3'][$key]['VEHICULE_DISPLAY_CASH_PRICE'] == 0) {
																					$aMenu[$i]['n2'][$k]['n3'][$key]['PRIX'] = 0;
																				}
                                        // Définition du code LCDV6 (config si rempli sinon manual)
                                        $aMenu[$i]['n2'][$k]['n3'][$key]['LCDV6'] = !empty($value['VEHICULE_LCDV6_CONFIG']) ? $value['VEHICULE_LCDV6_CONFIG'] : $value['VEHICULE_LCDV6_MANUAL'];
                                    }
                                }
                                $aBind[':PAGE_ID'] = $n2['id'];
                                $aBind[':PAGE_VERSION'] = $n2['pv'];
                                $sSQL = "
                                    select
                                        *
                                    from #pref#_perso_profile_page
                                    where PAGE_ID = :PAGE_ID
                                    order by ORDRE_PROFILE asc";
                                $aResults = $oConnection->queryTab($sSQL, $aBind);
                                if(is_array($aResults) && count($aResults)){
                                    foreach($aResults as $result){
                                        $aMenu[$i]['n2'][$k]['PROFILES'][] = ($result['INDICATEUR_ID'] != 0 && $result['PRODUCT_ID']) ? $result['PROFILE_ID'].'_'.$result['INDICATEUR_ID'].'_'.$result['PRODUCT_ID'] : $result['PROFILE_ID'];
                                    }
                                }
                                /*$aMultiGeneral = array('PUSH_OUTILS_MAJEUR', 'PUSH_OUTILS_MINEUR', 'PUSH_CONTENU_ANNEXE');
                                foreach($aMultiGeneral as $multiGeneral) {
                                    $aBind[':PAGE_MULTI_TYPE'] = "'" . $multiGeneral . "'";
                                    $sSQL = "
                                        select
                                            pm.*,
                                            m.MEDIA_PATH,
                                            m.MEDIA_ALT
                                        from #pref#_page_multi pm
                                        left join #pref#_media m
                                            on (pm.MEDIA_ID = m.MEDIA_ID)
                                        where pm.PAGE_ID = :PAGE_ID
                                        and pm.PAGE_VERSION = :PAGE_VERSION
                                        and pm.LANGUE_ID = :LANGUE_ID
                                        and pm.PAGE_MULTI_TYPE = :PAGE_MULTI_TYPE
                                        order by PAGE_MULTI_ID asc";
                                    $aMenu[$i]['n2'][$k][$multiGeneral] = $oConnection->queryTab($sSQL, $aBind);
                                    if ($multiGeneral == 'PUSH_CONTENU_ANNEXE' && $aMenu[$i]['n2'][$k][$multiGeneral]) {
                                        foreach($aMenu[$i]['n2'][$k][$multiGeneral] as $key => $value) {
                                            if ($aMenu[$i]['n2'][$k][$multiGeneral][$key]['MEDIA_PATH']) {
                                                $aMenu[$i]['n2'][$k][$multiGeneral][$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($aMenu[$i]['n2'][$k][$multiGeneral][$key]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['EXPAND_PUSH']);
                                            }
                                        }
                                    }
                                }*/
                            }
                        }
                    }
                    else {
                        //limite du nombre pour le gabarit Masterpage N1 du nb de niveaux 2
                        if(is_array($aMenu[$i]['n2'])){
                            $after = array_slice($aMenu[$i]['n2'], 0, 12);
                            $aMenu[$i]['n2'] = $after;
                        }

                        if ($aMenu[$i]['n2']) {
                            foreach($aMenu[$i]['n2'] as $key => $value) {
                                if ($aMenu[$i]['n2'][$key]['img']) {
                                    $aMenu[$i]['n2'][$key]['img'] = Pelican_Media::getFileNameMediaFormat($aMenu[$i]['n2'][$key]['img'], Pelican::$config['MEDIA_FORMAT_ID']['EXPAND_PAGE']);
                                }
                            }
                        }
                        $aBind[':PAGE_ID'] = $n1['n1']['id'];
                        $aBind[':PAGE_VERSION'] = $n1['n1']['pv'];
                        $sSQL = "
                            select
                                *
                            from #pref#_perso_profile_page
                            where PAGE_ID = :PAGE_ID
                            order by ORDRE_PROFILE asc";
                        $aResults = $oConnection->queryTab($sSQL, $aBind);
                        if(is_array($aResults) && count($aResults)){
                            foreach($aResults as $result){
                                $aMenu[$i]['n1']['PROFILES'][] = ($result['INDICATEUR_ID'] != 0 && $result['PRODUCT_ID']) ? $result['PROFILE_ID'].'_'.$result['INDICATEUR_ID'].'_'.$result['PRODUCT_ID'] : $result['PROFILE_ID'];
                            }
                        }
                        /*$aBind[':PAGE_MULTI_TYPE'] = "'PUSH'";
                        $sSQL = "
                            select
                                pm.*,
                                m.MEDIA_PATH,
                                m.MEDIA_ALT
                            from #pref#_page_multi pm
                            left join #pref#_media m
                                on (pm.MEDIA_ID = m.MEDIA_ID)
                            where pm.PAGE_ID = :PAGE_ID
                            and pm.PAGE_VERSION = :PAGE_VERSION
                            and pm.LANGUE_ID = :LANGUE_ID
                            and pm.PAGE_MULTI_TYPE = :PAGE_MULTI_TYPE
                            order by PAGE_MULTI_ID asc";
                        $aMenu[$i]['n1']['PUSH'] = $oConnection->queryTab($sSQL, $aBind);
                        if ($aMenu[$i]['n1']['PUSH']) {
                            foreach($aMenu[$i]['n1']['PUSH'] as $key => $value) {
                                if ($aMenu[$i]['n1']['PUSH'][$key]['MEDIA_PATH']) {
                                    $aMenu[$i]['n1']['PUSH'][$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($aMenu[$i]['n1']['PUSH'][$key]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['EXPAND_PUSH']);
                                }
                            }
                        }*/
                    }
                }
            }
            $this->value = $aMenu;
        }
        else {
            $this->value = $aMenu;
        }
    }

    function getTreeParams($tree)
    {
        $return["id"] = $tree->id;
        $return["pv"] = $tree->pv;
        $return["urlext"] = $tree->urlext;
        $return["pid"] = $tree->pid;
        $return["lib"] = $tree->lib;
        $return["url"] = $tree->PAGE_CLEAR_URL;
        $return["urlExterne"] = $tree->PAGE_URL_EXTERNE;        
        $return["ouvertureDirect"] = $tree->PAGE_OUVERTURE_DIRECT;
        $return["urlExterneTarget"] = $tree->PAGE_URL_EXTERNE_MODE_OUVERTURE;
        $return["img"] = $tree->img;
        $return["imgAlt"] = $tree->imgAlt;
        $return["expand"] = $tree->PAGE_TYPE_EXPAND;
        $return["itemParLigne"] = $tree->PAGE_NB_ITEM_PAR_LIGNE;
        $return["modeAffichage"] = $tree->PAGE_MODE_AFFICHAGE;
        $return["n3Actif"] = $tree->PAGE_OUVRIR_NIVEAU_3;
        $return["mentionsLegales"] = $tree->PAGE_MENTIONS_LEGALES;
        $return["perso"] = $tree->PAGE_PERSO;
        $return["vehiculeGamme"] = $tree->PAGE_GAMME_VEHICULE;
        $return["media_expand"] = $tree->media_expand;
        $return["affichageMobile"] = $tree->PAGE_DISPLAY_NAV_MOBILE;
        return $return;
    }
}