<?php
class Layout_Citroen_Global_Footer_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        /*=  Pelican_Cache::fetch("Frontend/Citroen/ZoneMulti", array(
            $aData["pid"],
            $aData['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            $aData['ZONE_TEMPLATE_ID'],
            'MANAGER',
            $aData['AREA_ID'],
            $aData['ZONE_ORDER']
        ));*/
        if (!$_GET['popin']) {
            $aParams = $this->getParams();
            $this->assign("aParams", $aParams);
            $this->assign("session", $_SESSION[APP]);
            // Fermeture du div body dans le footer pour les pages utilisants les gabarits suivant
            $this->assign("bTplHome", (in_array($aParams['TEMPLATE_PAGE_ID'], array(Pelican::$config['TEMPLATE_PAGE']['HOME'], Pelican::$config['TEMPLATE_PAGE']['CAR_SELECTOR'], Pelican::$config['TEMPLATE_PAGE']['404'])))?1:0);
            $this->assign("footerHomeMobile", (in_array($aParams['TEMPLATE_PAGE_ID'], array(Pelican::$config['TEMPLATE_PAGE']['HOME'], Pelican::$config['TEMPLATE_PAGE']['ACTU_GALERIE'], Pelican::$config['TEMPLATE_PAGE']['ACTU_DETAIL'])))?1:0);

            if(isset($aParams['PAGE_VEHICULE'])){
                $vehicule = Pelican_Cache::fetch("Frontend/Citroen/VehiculeById", array(
                    $aParams['PAGE_VEHICULE'],
                    $_SESSION[APP]['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID']
                ));
                $vehicule_label = $vehicule['VEHICULE_LABEL'];
            }
            $this->assign("vehicule_label", $vehicule_label);
            
            $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                '',
                Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
            ));

            /*
             *  Besoin d'aide ? / Assistance téléphonique
             */
            $assistances = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                $pageGlobal['PAGE_ID'],
                Pelican::$config['ZONE_TEMPLATE_ID']['AIDES'],
                $pageGlobal['PAGE_VERSION'],
                $_SESSION[APP]['LANGUE_ID']
            ));
            
            if (!empty($assistances["MEDIA_ID"])) {
                $mediaDetail = Pelican_Cache::fetch("Media/Detail", array(
                            $assistances["MEDIA_ID"]
                ));
                $assistances["MEDIA_PATH"] = Pelican::$config['MEDIA_HTTP'] . Citroen_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($assistances['MEDIA_ID']), Pelican::$config['MEDIA_FORMAT_ID']['PETIT_CARRE']);
                $assistances["MEDIA_ALT"] = $mediaDetail["MEDIA_TITLE"];
            }

            $this->assign("assistances", $assistances);
            $zoneNavigationAssistances = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateId", array(
                $pageGlobal['PAGE_ID'],
                Pelican::$config['ZONE_TEMPLATE_ID']['AIDES'],
                Pelican::getPreviewVersion(),
                $_SESSION[APP]['LANGUE_ID']
            ));
            $navigationAssistances = Citroen_Cache::fetchProfiling($zoneNavigationAssistances['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
                $pageGlobal['PAGE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                Pelican::getPreviewVersion(),
                Pelican::$config['ZONE_TEMPLATE_ID']['AIDES'],
                'CTA',
                $aParams['AREA_ID'],
                $aParams['ZONE_ORDER']
            ));
            $this->assign("navigationAssistances", $navigationAssistances);

            /*
             *  Autres sites
             */
            $autresSites = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                $pageGlobal['PAGE_ID'],
                Pelican::$config['ZONE_TEMPLATE_ID']['AUTRES_SITES'],
                $pageGlobal['PAGE_VERSION'],
                $_SESSION[APP]['LANGUE_ID']
            ));
            $this->assign("autresSites", $autresSites);
            $zoneNavigationAutresSites = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateId", array(
                $pageGlobal['PAGE_ID'],
                Pelican::$config['ZONE_TEMPLATE_ID']['AUTRES_SITES'],
                Pelican::getPreviewVersion(),
                $_SESSION[APP]['LANGUE_ID']
            ));
            $navigationAutresSites = Citroen_Cache::fetchProfiling($zoneNavigationAutresSites['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
                $pageGlobal['PAGE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                Pelican::getPreviewVersion(),
                Pelican::$config['ZONE_TEMPLATE_ID']['AUTRES_SITES'],
                'CTA',
                $aParams['AREA_ID'],
                $aParams['ZONE_ORDER']
            ));
            $this->assign("navigationAutresSites", $navigationAutresSites);

            /*
             * Nous suivre / Abonnement newsletter
             */
            $abonnements = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                $pageGlobal['PAGE_ID'],
                Pelican::$config['ZONE_TEMPLATE_ID']['ABONNEMENTS'],
                $pageGlobal['PAGE_VERSION'],
                $_SESSION[APP]['LANGUE_ID']
            ));
            $this->assign("abonnements", $abonnements);

            $reseauxSociauxSelected = explode('|', $abonnements['ZONE_PARAMETERS']);
            $this->assign("reseauxSociauxSelected", $reseauxSociauxSelected);
            $reseauxSociaux = Pelican_Cache::fetch("Frontend/Citroen/ReseauxSociaux", array(
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID']
            ));
            $this->assign("reseauxSociaux", $reseauxSociaux);

            /*
             * Plan du site
             */
            $planDuSite = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                $pageGlobal['PAGE_ID'],
                Pelican::$config['ZONE_TEMPLATE_ID']['PLAN_DU_SITE'],
                $pageGlobal['PAGE_VERSION'],
                $_SESSION[APP]['LANGUE_ID']
            ));
            $this->assign("planDuSite", $planDuSite);
            $navigationPlanDuSite = Pelican_Cache::fetch("Frontend/Citroen/Navigation", array(
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                'PAGE_DISPLAY',
                 "PLAN_SITE"
            ));

            // Découpage des pages en groupe de 5 (1 ligne = 5 colonnes)
            if(is_array($navigationPlanDuSite)&&!empty($navigationPlanDuSite)){
                $navigationPlanDuSite = array_chunk($navigationPlanDuSite, 10);
            }

            $this->assign("navigationPlanDuSite", $navigationPlanDuSite);

            /*
             * Élements légaux
             */
            $elementsLegaux = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                $pageGlobal['PAGE_ID'],
                Pelican::$config['ZONE_TEMPLATE_ID']['ELEMENTS_LEGAUX'],
                $pageGlobal['PAGE_VERSION'],
                $_SESSION[APP]['LANGUE_ID']
            ));
            $this->assign("elementsLegaux", $elementsLegaux);
            
            //Type de cookie
            $aCookies = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                $pageGlobal['PAGE_ID'],
                Pelican::$config['ZONE_TEMPLATE_ID']['COOKIE'],
                $pageGlobal['PAGE_VERSION'],
                $_SESSION[APP]['LANGUE_ID']
            ));
            if($aCookies['ZONE_TITRE4'] === NULL){
                $aCookies['ZONE_TITRE4'] = 1;
            }
            $this->assign("cookieType", $aCookies['ZONE_TITRE4']);
            
            $zoneNavigationElementsLegaux = Pelican_Cache::fetch("Frontend/Page/ZoneTemplateId", array(
                $pageGlobal['PAGE_ID'],
                Pelican::$config['ZONE_TEMPLATE_ID']['ELEMENTS_LEGAUX'],
                Pelican::getPreviewVersion(),
                $_SESSION[APP]['LANGUE_ID']
            ));
            $navigationElementsLegaux = Citroen_Cache::fetchProfiling($zoneNavigationElementsLegaux['ZONE_PERSO'],"Frontend/Citroen/ZoneMulti", array(
                $pageGlobal['PAGE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                Pelican::getPreviewVersion(),
                Pelican::$config['ZONE_TEMPLATE_ID']['ELEMENTS_LEGAUX'],
                'CTA',
                $aParams['AREA_ID'],
                $aParams['ZONE_ORDER']
            ));
            $this->assign("navigationElementsLegaux", $navigationElementsLegaux);
           

            /*
             * CTA Mobile
             */
            $ctaMobile = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                $pageGlobal['PAGE_ID'],
                Pelican::$config['ZONE_TEMPLATE_ID']['CTA_MOBILE'],
                $pageGlobal['PAGE_VERSION'],
                $_SESSION[APP]['LANGUE_ID']
            ));
            $this->assign("ctaMobile", $ctaMobile);
            $this->fetch();
        }
    }

	/**
	 * Affichage du site en version Mobile
	 */
	public function versionMobileAction()
    {
		if ($this->isMobile()) {
			unset($_SESSION['HTTP_USER_AGENT']);
		}
		else {
			$_SESSION['HTTP_USER_AGENT'] = Pelican::$config['USER_AGENT_LIST']['Iphone'];
		}
		$_SESSION['HTTP_USER_AGENT_FORCE'] = 1;
		$this->getRequest()->addResponseCommand('reload');
	}

	/**
	 * Affichage du site en version Desktop
	 */
	public function versionDesktopAction()
    {
		if ($this->isMobile()) {
			$_SESSION['HTTP_USER_AGENT'] = Pelican::$config['USER_AGENT_LIST']['Desktop'];
		}
		else {
			unset($_SESSION['HTTP_USER_AGENT']);
		}
		$_SESSION['HTTP_USER_AGENT_FORCE'] = 1;
		$this->getRequest()->addResponseCommand('reload');
	}

}
