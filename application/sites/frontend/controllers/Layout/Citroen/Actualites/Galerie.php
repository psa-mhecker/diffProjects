<?php

class Layout_Citroen_Actualites_Galerie_Controller extends Pelican_Controller_Front {

    public function indexAction() {
        $aData = $this->getParams();
        $iThemeId = $this->getParam('themeId');

        /**
         * Filtres
         */
        $aFiltres = array();
        if ($aData['ZONE_TITRE'] != "") {
            $aFiltres = explode('##', $aData['ZONE_TITRE']);
        }
        $aThemes = Pelican_Cache::fetch("Frontend/Citroen/Actualites/Themes", array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID']
        ));

        $this->assign("aFiltres", $aFiltres);
        $this->assign("aThemes", $aThemes);

        /**
         * Actualités
         */
        $iMin = 1;
        $iCount = $iMin + 10;

        $aActualites = self::_getActualites($aData['pid'], $iThemeId, $iMin);


        // $nbActus = count($aActualites); //self::_countActualites();
        $nbActus = self::_countActualites($aData['pid']);
        $aClearUrls = Pelican_Cache::fetch("Frontend/Citroen/Actualites/PageClearUrlByActu", array(
                    $aData['pid'],
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID']
        ));
        $this->assign("aActualites", $aActualites);
        $this->assign("aClearUrls", $aClearUrls);
        $this->assign("nbActus", $nbActus);
        $this->assign("iCount", $iCount);
        /**
         * Colonne de droite Réseaux Sociaux + Newsletter + RSS
         */
        $sUrlRSS = $aData['ZONE_URL'];
        $aDisplayBox = array();
        if ($aData['ZONE_TITRE5'] != "") {
            $aDisplayBox = explode('##', $aData['ZONE_TITRE5']);
        }
        $aReseauxSociaux = Pelican_Cache::fetch("Frontend/Citroen/BoxSociales", array(
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID']
        ));
        $this->assign("aReseauxSociaux", $aReseauxSociaux);
        $iFacebook = $aData['ZONE_TITRE2'];
        $iYoutube = $aData['ZONE_TITRE3'];
        $iTwitter = $aData['ZONE_TITRE4'];
        $reseauxSociauxSelected = Pelican_Cache::fetch("Frontend/Citroen/GroupeReseauxSociaux", array(
                    $aData['ZONE_TITRE6'],
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID']
        ));

        //Newsletters
        $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            '',
            Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
        ));
        $abonnements = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
            $pageGlobal['PAGE_ID'],
            Pelican::$config['ZONE_TEMPLATE_ID']['ABONNEMENTS'],
            $pageGlobal['PAGE_VERSION'],
            $_SESSION[APP]['LANGUE_ID']
        ));
        $this->assign("abonnements", $abonnements);

        //Sharer
        $sSharer = Backoffice_Share_Helper::getSharer($aParams['ZONE_LABEL2'], $aParams['SITE_ID'], $aParams['LANGUE_ID'], Pelican::$config['MODE_SHARER'][4], array('getParams' => $aParams));
        $this->assign("sSharerBar", $sSharer);
        //Sharer
        $sSharer = Backoffice_Share_Helper::getSharer($aParams['ZONE_LABEL2'], $aParams['SITE_ID'], $aParams['LANGUE_ID'], Pelican::$config['MODE_SHARER'][2], array('getParams' => $aParams));
        $this->assign("sSharerButton", $sSharer);

        $this->assign("reseauxSociauxSelected", $reseauxSociauxSelected);
        $this->assign("aDisplayBox", $aDisplayBox);
        $this->assign("sUrlRSS", $sUrlRSS);
        $this->assign("iFacebook", $iFacebook);
        $this->assign("iYoutube", $iYoutube);
        $this->assign("iTwitter", $iTwitter);
        $this->assign("iThemeId", $iThemeId);
        $this->assign("aData", $aData);
        $this->assign('sIncludeTplPath',Pelican::$config['APPLICATION_VIEWS'].'/Layout/Citroen/Actualites/Galerie');
        $this->fetch();
    }

    /**
     * Action permettant d'afficher plus ou moins d'actualité (ajax)
     */
    public function moreNewsAction() {
        include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Share.php');

        $aData = $this->getParams();
        $sTypeAff = $aData['typeAff'];
        $iPid = !empty($aData['iPid']) ? $aData['iPid'] : $_GET['values']['iPid'];
        $iMin = ($sTypeAff == 'less') ? 1 : $aData['iMin'];

        $aActualites = self::_getActualites($iPid, $iThemeId, $iMin, $sTypeAff);
        $nbActus = self::_countActualites($iPid);

        $aClearUrls = Pelican_Cache::fetch("Frontend/Citroen/Actualites/PageClearUrlByActu", array($iPid, $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']));
        $this->assign("aClearUrls", $aClearUrls);

        //Sharer
        $sSharer = Backoffice_Share_Helper::getSharer($aParams['ZONE_LABEL2'], $aParams['SITE_ID'], $aParams['LANGUE_ID'], Pelican::$config['MODE_SHARER'][4], array('getParams' => $aParams));
        $this->assign("sSharerBar", $sSharer);

        $this->assign("aActualites", $aActualites);
        $this->fetch();
        $sTypeAdd = ($sTypeAff == 'less') ? 'assign' : 'append';
        $this->getRequest()->addResponseCommand($sTypeAdd, array(
            'id' => 'allActu',
            'attr' => 'innerHTML',
            'value' => $this->getResponse()
        ));
        $iCount = $iMin + 10;
        if ($sTypeAff == 'less') {

            $this->getRequest()->addResponseCommand('script', array(
                'value' => "
                    $('#iCount').val(" . $iCount . ");
                    $('#seeMoreNews a').html('" . str_replace("'", "\'", t('VOIR_PLUS_ACTU')) . "');
                    $('#seeMoreNews a').unbind('click');
                    $('#seeMoreNews a').bind('click',function(e){
                        e.preventDefault();
                        displayMoreNews('more');
                    });
"
            ));
        } else {
            if (count($aActualites) < 10 || $nbActus < $iCount) {
                $this->getRequest()->addResponseCommand('script', array(
                    'value' => "
                        $('#iCount').val(" . $iCount . ");
                        $('#seeMoreNews a').html('" . str_replace("'", "\'", t('VOIR_MOINS_ACTU')) . "');
                        $('#seeMoreNews a').unbind('click');
                        $('#seeMoreNews a').bind('click',function(e){
                            e.preventDefault();
                            displayMoreNews('less');
                        });"
                ));
            } else {
                $this->getRequest()->addResponseCommand('script', array(
                    'value' => "$('#iCount').val(" . $iCount . ");"
                ));
            }
        }
    }

    /**
     * Action permettant de rafrechir les actualité en fonction du theme (ajax)
     */
    public function filterNewsAction() {
        include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Share.php');
        $aData = $this->getParams();

        $iPid = $aData['iPid'];
        $iMin = $aData['iMin'];
        $iTheme = ($aData['iTheme'] == 0) ? null : $aData['iTheme'];

        $aActualites = self::_getActualites($iPid, $iTheme, $iMin);
        $aClearUrls = Pelican_Cache::fetch("Frontend/Citroen/Actualites/PageClearUrlByActu", array(
                    $aData['iPid'],
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID']
        ));
        $this->assign("aActualites", $aActualites);
        $this->assign("aClearUrls", $aClearUrls);
        
        //Sharer
        $sSharer = Backoffice_Share_Helper::getSharer($aParams['ZONE_LABEL2'], $aParams['SITE_ID'], $aParams['LANGUE_ID'], Pelican::$config['MODE_SHARER'][4], array('getParams' => $aParams));
        $this->assign("sSharerBar", $sSharer);

        $this->assign('sIncludeTplPath',Pelican::$config['APPLICATION_VIEWS'].'/Layout/Citroen/Actualites/Galerie');

        $this->fetch();

        $this->getRequest()->addResponseCommand('assign', array(
            'id' => 'allActu',
            'attr' => 'innerHTML',
            'value' => $this->getResponse()
        ));
        $this->getRequest()->addResponseCommand('script', array(
            'value' => "ReinitializeAddThis(); lazy.set($('#allActu img.lazy'));"
        ));
    }

    /**
     * Fonction récupérant les actualités et de les trié dans l'ordre chronologique
     * @param $iPid int : pid de la page courante
     * @param $iTheme int : identifiant du thème sélectionné
     * @param $iMin int : limite minimum de récupération des actualités
     * @return $aResults array : Tableau d'actus
     */
    static private function _getActualites($iPid, $iTheme = 0, $iMin = 0, $sTypeAff = "") {
        require_once('Pelican/Media.php');
        include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Date.php');
        $aResults = Pelican_Cache::fetch("Frontend/Citroen/Actualites/Liste", array(
                    $iPid,
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    false,
                    $iTheme,
                    $iMin,
                    "CURRENT",
                    "1"
        ));

        if (is_array($aResults) && count($aResults) > 0) {
            foreach ($aResults as $key => $actu) {
                if($actu['MEDIA_PATH']){
                    $aResults[$key]['MEDIA_PATH'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat($actu['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['ACTUALITES_GALERIE']);
                }else{
                    $aResults[$key]['MEDIA_PATH'] = '';
                }
                $aResults[$key]['MEDIA_PATH_MOBILE'] = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat($actu['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['ACTUALITES_GALERIE_MOBILE']);
                
                 if($actu["CONTENT_CODE2"] == 1)
                    {
                    $aResults[$key]['DATE_FORMATEE'] = Frontoffice_Date_Helper::formatDate(2, $actu['DATE_LETTER']);
                    }
                    else
                    {
                     $aResults[$key]['DATE_FORMATEE']= Frontoffice_Date_Helper::formatDate(1, $actu['DATE_LETTER']);   
                    }
            }
        }


        $aTemp = array();
        $n = 0;
        // test des dates de publications et fin
        if (is_array($aResults)) {
        foreach ($aResults as $aOneActualites) {

            if ($aOneActualites['CONTENT_START_DATE']) {
                //si date de debut
                if ($aOneActualites['CONTENT_END_DATE']) {
                    //si date debut + fin

                    if ($aOneActualites['CONTENT_END_DATE'] >= date("Y-m-d G:i:s") && date("Y-m-d G:i:s") >= $aOneActualites['CONTENT_START_DATE']) {
                        $aTemp[$n] = $aOneActualites;
                    }
                } else {
                    //juste debut
                    if (date("Y-m-d G:i:s") >= $aOneActualites['CONTENT_START_DATE']) {
                        $aTemp[$n] = $aOneActualites;
                    }
                }
            } else {
                if ($aOneActualites['CONTENT_END_DATE']) {
                    //si date de fin sans debut
                    if ($aOneActualites['CONTENT_END_DATE'] >= date("Y-m-d G:i:s")) {
                        $aTemp[$n] = $aOneActualites;
                    }
                } else {
                    //si ni fin ni debut
                    $aTemp[$n] = $aOneActualites;
                }
            }

            $n++;
        }
        }
                
        // tri par ordre chronologique
        for($it=0; $it < count($aTemp); $it++)
        {
            for ($yt=0; $yt < count($aTemp); $yt++) 
            { 
                if(strtotime($aTemp[$it]["DATE_TIME_START"]) > strtotime($aTemp[$yt]["DATE_TIME_START"]))
                {
                    $aTempo = $aTemp[$it];
                    $aTemp[$it] = $aTemp[$yt];
                    $aTemp[$yt] = $aTempo;
                }
            }
        }
    
        return $aTemp;
    }


    /**
     * Fonction récupérant le nombre d'actualité actualités
     */
    static private function _countActualites($iPid) {
        $aResults = Pelican_Cache::fetch("Frontend/Citroen/Actualites/Liste", array(
            $iPid,
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            true
        ));
        return $aResults;
    }

}