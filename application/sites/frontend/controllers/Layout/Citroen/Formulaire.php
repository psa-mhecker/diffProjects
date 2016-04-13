<?php
use Citroen\GammeFinition\VehiculeGamme;
use Citroen\SelectionVehicule;
use Citroen\GTM;

require_once(Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Showroom.php');

class Layout_Citroen_Formulaire_Controller extends Pelican_Controller_Front
{
    public function indexAction()
    {


        $aData = $this->getParams();
        
        // Reset smarty assigned variables
        $this->getView()->clearAssign('sError');

        $this->assign("formcss", Pelican::$config["CSS_FRONT_HTTP"] . '/forms.css');
        $bVenteIframe = false;

        Pelican::$config['WINDOW_TITLE'] = (Pelican::$config['WINDOW_TITLE'] ? Pelican::$config['WINDOW_TITLE'] : $aPage['PAGE_TITLE']);
        $sPageTitle = Pelican::$config['WINDOW_TITLE'] . " - " . Pelican::$config['SITE']['INFOS']['SITE_TITLE'];


        $this->assign("aPage", $aPage);
        $this->assign("bVenteIframe", $bVenteIframe);
        if ($this->isMobile()) {
            $iPageId = $aData['ppid'];
        } else {
            $iPageId = $aData['pid'];
        }

        if (intval($iPageId) > 0) {
            $aPage = Pelican_Cache::fetch("Frontend/Page", array(
                $iPageId,
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID']
            ));
            if ($aPage['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['POINT_DE_VENTE_IFRAME']) {
                $bVenteIframe = true;
            }
        }
        $aPageShowroomColor = Frontoffice_Showroom_Helper::getShowroomColor($iPageId, $_SESSION[APP]['LANGUE_ID'], Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']);
        if (!empty($aPageShowroomColor['PAGE_PRIMARY_COLOR']) && !empty($aPageShowroomColor['PAGE_SECOND_COLOR'])) {
            $aData['PRIMARY_COLOR'] = $aPageShowroomColor['PAGE_PRIMARY_COLOR'];
            $aData['SECOND_COLOR'] = $aPageShowroomColor['PAGE_SECOND_COLOR'];
        }
        // $sCssStatics = Frontoffice_Showroom_Helper::getStaticCssFormsWeb();
        // $sCssStaticsMobile = Frontoffice_Showroom_Helper::getStaticCssFormsMobile();
        // $this->assign("sCssStatics", $sCssStatics,false);
        // $this->assign("sCssStaticsMobile", $sCssStaticsMobile,false);


        if (isset($aData['PRIMARY_COLOR']) && isset($aData['SECOND_COLOR']) && ($aPage['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'] || $aPage['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE'])) {
            $sCss = Frontoffice_Showroom_Helper::getCssWithDynamicColors($aData['PRIMARY_COLOR'], $aData['SECOND_COLOR']);
            $sCssMobile = Frontoffice_Showroom_Helper::getCssWithDynamicColorsMobile($aData['PRIMARY_COLOR'], $aData['SECOND_COLOR']);
            $this->assign("sCss", $sCss, false);
            $this->assign("sCssMobile", $sCssMobile, false);
            $bShowroom = true;
        }
        $aFormTypes = Pelican_Cache::fetch('Frontend/Citroen/FormType', array(true));
        if (!$this->isMobile() && $aData['FORM_DEPLOYE']) {
            $aPageZone = Pelican_Cache::fetch('Frontend/Page/Zone', array(
                $aData['pid'],
                $_SESSION[APP]['LANGUE_ID'],
                Pelican::getPreviewVersion()
            ));
            switch ($aPageZone['areas'][0]["TEMPLATE_PAGE_ID"]) {
                case Pelican::$config['TEMPLATE_PAGE']['GABARIT_BLANC']:
                    if ($aData['FORM_DEPLOYE']['TRANCHE_VEHICULE'] != '') {
                        $aData['FORM_DEPLOYE']['FORM_CONTEXT_CODE'] = 'CAR';
                    } elseif ($aData['FORM_DEPLOYE']['PAGE_VEHICULE'] != "") {
                        $aData['FORM_DEPLOYE']['FORM_CONTEXT_CODE'] = 'CAR';
                    } else {
                        //Gblanc PDV
                        $aData['FORM_DEPLOYE']['FORM_CONTEXT_CODE'] = 'RTO';
                    }
                    break;
                case Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']:
                    //showroom accueil
                    $aData['FORM_DEPLOYE']['FORM_CONTEXT_CODE'] = 'CAR';
                    break;
            }
        }

        // Vérification arrivée depuis un CTA perso
        if ($aData['FORM_DEPLOYE'] && isset($_GET['origin']) && $_GET['origin'] == "ctaperso") {
            GTM::$dataLayer['customDimension1'] = "Perso";
        }
        $trancheEssayer = 0;
        $formulaire = null;
        $typeDevice = ($this->isMobile() == true) ? 'MOB' : 'WEB';
        if ($aData['FORM_DEPLOYE']) {
            $sSharer = Backoffice_Share_Helper::getSharer($aData['FORM_DEPLOYE']['FORM_SHARE'], $aData['SITE_ID'], $aData['LANGUE_ID'], Pelican::$config['MODE_SHARER'][0], array('getParams' => $aData));
            $typeDevice = ($this->isMobile() == true) ? 'MOB' : 'WEB';
            $formulaire = $aData['FORM_DEPLOYE'];
            $lcdvSource = ($formulaire['TRANCHE_VEHICULE'] != '') ? $formulaire['TRANCHE_VEHICULE'] : VehiculeGamme::getLCDV6($formulaire['PAGE_VEHICULE'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
            // $lcdv6Form = ($_GET["lcdv"] != "") ? $_GET['lcdv'] : $lcdvSource ;
            // __JFO : pour gérer la contextualisation des forms en provenance du Configurateur, il faut gérer le parametre Car qui peut prendre un code LCDV.
            if ($_GET['lcdv'] == '' && $_GET['Car'] == '')
                $lcdv6Form = $lcdvSource;
            else
                $lcdv6Form = ($_GET['Car'] != '' ? $_GET['Car'] : $_GET['lcdv']);
            /*Moteur de Config*/
            if ($lcdv6Form != '') {
                $aVehicule = VehiculeGamme::getVehiculeByLCDVGamme($lcdv6Form, null, $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $formulaire['PAGE_VEHICULE']);
                if ($aVehicule['VEHICULE_LCDV6_MTCFG'] != '')
                    $lcdv6Form = $aVehicule['VEHICULE_LCDV6_MTCFG'];
            }

            $this->assign("lcdv6Form", $lcdv6Form);
            $this->assign("formName", 'FormulaireDeploy' . $aData['ZONE_ORDER']);
            $form_Type_GTM = $aFormTypes[$formulaire['ZONE_TITRE3']]['FORM_TYPE_GTM_ID'];
            $this->assign("formTypeGTM", $form_Type_GTM);
        } else {
            $bTrancheVisible = true;
            $order = $aData['ZONE_ORDER'];
            if ($aData['ZONE_TITRE13'] != "" && $aData['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION'] && $aData['ZONE_ID'] == Pelican::$config['ZONE']['ESSAYER']) {
                if (isset($_GET['ESSAYER'])) {
                    $aValues = explode('_', $aData['ZONE_TITRE13']);
                    $zone = Pelican_Cache::fetch("Frontend/Citroen/ZoneTemplate", array(
                        $aValues[0],
                        $_SESSION[APP]['LANGUE_ID'],
                        Pelican::getPreviewVersion(),
                        $aValues[2],
                        $aValues[4],
                        $aValues[5],
                        $aValues[3]
                    ));
                    $order = $aData['ZONE_TEMPLATE_ID'];
                    $aData = $zone;
                    $mobile = $this->isMobile() ? '_MOBILE' : '';
                    $aData['ZONE_SKIN'] = Pelican::$config['TRANCHE_COL']['MODE_AFF_CSS' . $mobile]['NEUTRE'];
                    if ($aData['ZONE_TITRE19'] == 'DS') {
                        $aData['ZONE_SKIN'] = Pelican::$config['TRANCHE_COL']['MODE_AFF_CSS' . $mobile]['DS'];
                    } else if ($aData['ZONE_TITRE19'] == 'C') {
                        $aData['ZONE_SKIN'] = Pelican::$config['TRANCHE_COL']['MODE_AFF_CSS' . $mobile]['C'];
                    }
                }
                $trancheEssayer = 1;
            }
            //Si on est sur mon projet on prend le premier slot
            if ($_GET['select_vehicule_lcdv6'] != '' && $trancheEssayer == 1) {
                $lcdv6Form = $_GET['select_vehicule_lcdv6'];
            } else {
                //Si on est pas sur mon projet ou que le premier slot est vide et qu'il y a un get, sinon on prend l'info en page
                // __JFO : pour gérer la contextualisation des forms en provenance du Configurateur, il faut gérer le parametre Car qui peut prendre un code LCDV.
                $lcdv6Form = ($_GET["lcdv"] != "") ? $_GET['lcdv'] : VehiculeGamme::getLCDV6($aData['PAGE_VEHICULE'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
                if ($_GET['lcdv'] == '' && $_GET['Car'] == '')
                    $lcdv6Form = VehiculeGamme::getLCDV6($aData['PAGE_VEHICULE'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
                else
                    $lcdv6Form = ($_GET['Car'] != '' ? $_GET['Car'] : $_GET['lcdv']);
            }
            $this->assign("lcdv6Form", $lcdv6Form);
            $this->assign("ppid", $_GET['ppid']);
            // Pour la page mon projet
            if ($aData['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']) {
                if (!isset($_GET['PROFITER'])) {
                    $bTrancheVisible = false;
                }
            }
            if ($bTrancheVisible) {
                $sSharer = Backoffice_Share_Helper::getSharer($aData['ZONE_LABEL2'], $aData['SITE_ID'], $aData['LANGUE_ID'], Pelican::$config['MODE_SHARER'][0], array('getParams' => $aData));
                if ($aData['ZONE_TITRE4'] != 'CHOIX') {
                    $formulaire = Pelican_Cache::fetch("Frontend/Citroen/Formulaire", array(
                        $aData["ZONE_TITRE3"],
                        $aData['ZONE_TITRE4'],
                        $typeDevice,
                        $_SESSION[APP]['SITE_ID'],
                        $_SESSION[APP]['LANGUE_ID'],
                        '',
                        '',
                        $lcdv6Form
                    ));
                }
                $this->assign("formName", 'Formulaire' . $order);
            }
            $form_Type_GTM = $aFormTypes[$aData['ZONE_TITRE3']]['FORM_TYPE_GTM_ID'];
            $this->assign("formTypeGTM", $form_Type_GTM);
        }
        $formulaire['deployed'] = 0;
        if (!$this->isMobile() && !empty($_GET['deployable_id']) && !empty($lcdv6Form) && empty($formulaire['FORM_CONTEXT_CODE'])) {
            $formulaire['FORM_CONTEXT_CODE'] = 'CAR';
        }
        if (!empty($_GET['deployable_id']) && $_GET['deployable_id'] == $aData['ZONE_ORDER']) {
            $formulaire['deployed'] = 1;
        }
        if ($aData['FORM_DEPLOYE']) {
            if (isset($formulaire['ZONE_ATTRIBUT2']) && !empty($formulaire['ZONE_ATTRIBUT2'])) {
                $aFormTypeId = $formulaire['ZONE_ATTRIBUT2'];
            } else {
                $aFormTypeId = $formulaire['ZONE_TITRE3'];
            }
        } else {
            if (isset($aData['ZONE_ATTRIBUT2']) && !empty($aData['ZONE_ATTRIBUT2'])) {
                $aFormTypeId = $aData['ZONE_ATTRIBUT2'];
            } else {
                $aFormTypeId = $aData['ZONE_TITRE3'];
            }
        }
        //forcer le mode d'affichage si  passé en parametre d'url
        if (isset($aData['page_skin'])) {
            $formulaire['FORM_MODE_AFF'] = strtoupper($aData['page_skin']);
            $aData['ZONE_SKIN'] = $aData['page_skin'];
        }
        $zonePage = Pelican_Cache::fetch("Frontend/Page", array(
            $iPageId,
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion()
        ));
        if (in_array($zonePage['TEMPLATE_PAGE_ID'], array(Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'], Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE']))) {
            GTM::$dataLayer['siteTypeLevel2'] = 'Showroom';
        }
        if ($this->isMobile()) {
            switch ($zonePage['TEMPLATE_PAGE_ID']) {
                case Pelican::$config['TEMPLATE_PAGE']['HOME']:
                    $formulaire['FORM_CONTEXT_CODE'] = '';
                    break;
                case Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']:
                    $formulaire['FORM_CONTEXT_CODE'] = 'CAR';
                    break;
                case Pelican::$config['TEMPLATE_PAGE']['GABARIT_BLANC']:
                    if ($zonePage['PAGE_VEHICULE'] != "") {
                        $formulaire['FORM_CONTEXT_CODE'] = 'CAR';
                    } else {
                        //Gblanc PDV
                        $formulaire['FORM_CONTEXT_CODE'] = 'RTO';
                    }
                    break;
            }
        }


        if ($this->isMobile() && $_GET['lcdv'] && empty(GTM::$dataLayer['vehicleModelBodystyle'])) {
            GTM::$dataLayer['vehicleModelBodystyle'] = $_GET['lcdv'];
        }
        $VehiculeInfo = VehiculeGamme::getShowRoomVehicule(
            $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $zonePage['PAGE_VEHICULE'], null, $_SESSION[APP]['PAGE_ID']
        );
        if ($this->isMobile() && $_GET['lcdv'] && empty(GTM::$dataLayer['vehicleModelBodystyleLabel'])) {
            GTM::$dataLayer['vehicleModelBodystyleLabel'] = $VehiculeInfo[0]['VEHICULE']['VEHICULE_LABEL'];
        }
        switch ($formulaire['FORM_CONTEXT_CODE']) {
            case "CAR":
                $FORM_CONTEXT_CODE = "context-car";
                break;
            case "RTO":
                $FORM_CONTEXT_CODE = "context-dealer";
                break;
            default:
                $FORM_CONTEXT_CODE = "context-none";
        }

        // récupération des données provenant du WS GDG
        // Brochure
        // Car Picker


        $aSiteWS = Pelican_Cache::fetch('Frontend/Citroen/SiteWs', array($_SESSION[APP]['SITE_ID']));
        $aWs = Pelican_Cache::fetch('Frontend/Citroen/WsConfig');
        $sLabel = '';

        if ($aSiteWS[$aWs['CITROEN_SERVICE_GDG']['id']]) {
            if (strtoupper($form_Type_GTM) == 'TESTDRIVE') {

                $sLabel = \Pelican_Cache::fetch("Citroen/GDG", array(
                    Pelican::$config['GDG']['CAR_PICKER'],
                    strtolower($_SESSION[APP]['LANGUE_CODE']),
                    $_SESSION[APP]['CODE_PAYS'],
                    'C',
                    'VP',
                    'json',
                    'TESTDRIVE',
                    $lcdv6Form
                ));

            } else {
                $sLabel = \Pelican_Cache::fetch("Citroen/GDG", array(
                    Pelican::$config['GDG']['BROCHURE'],
                    strtolower($_SESSION[APP]['LANGUE_CODE']),
                    $_SESSION[APP]['CODE_PAYS'],
                    'C',
                    'VP',
                    'json',
                    '',
                    $lcdv6Form
                ));

            }
        }
        if (empty($sLabel)) {
            $sLabel = GTM::$dataLayer['vehicleModelBodystyleLabel'];
        }


        $aDateGTM = array();
        // if(empty(GTM::$dataLayer['siteTypeLevel2'])){
        // GTM::$dataLayer['siteTypeLevel2'] ='forms';
        // }
        switch ($form_Type_GTM) {
            case "TestDrive":
                $aDateGTM['TestDrive'] = array(
                    'event' => "updatevirtualpath",
                    'brand' => "citroen",
                    'virtualPageURL' => "cpp/test-drive/part-pro/" . ($typeDevice == 'WEB' ? "desktop" : "mobile") . "/step-0",
                    'pageName' => "cpp/" . (empty(GTM::$dataLayer['siteTypeLevel2']) ? 'forms' : GTM::$dataLayer['siteTypeLevel2']) . "/central/all/new cars/G37_Forms/" . ($typeDevice == 'WEB' ? "desktop" : "mobile") . "/" . GTM::$dataLayer['vehicleModelBodystyle'] . "/" . $sPageTitle,
                    'language' => GTM::$dataLayer['language'],
                    'country' => GTM::$dataLayer['country'],
                    'siteTypeLevel1' => "cpp",
                    'siteTypeLevel2' => empty(GTM::$dataLayer['siteTypeLevel2']) ? 'forms' : GTM::$dataLayer['siteTypeLevel2'],
                    'siteOwner' => "central",
                    'siteTarget' => "all",
                    'siteFamily' => "new cars",
                    'pageCategory' => "form page",
                    'pageVariant' => $FORM_CONTEXT_CODE,
                    'formsName' => "test drive",
                    'mainStepIndicator' => "0",
                    'mainStepName' => "customer-target",
                    'vehicleModelBodystyle' => GTM::$dataLayer['vehicleModelBodystyle'],
                    'vehicleModelBodystyleLabel' => $sLabel,
                    'to_push' => TRUE
                );
                break;
            case "Brochure":
                $aDateGTM['Brochure'] = array(
                    'event' => "updatevirtualpath",
                    'brand' => "citroen",
                    'virtualPageURL' => "cpp/brochure-request/part-pro/" . ($typeDevice == 'WEB' ? "desktop" : "mobile") . "/step-0",
                    'pageName' => "cpp/" . (empty(GTM::$dataLayer['siteTypeLevel2']) ? 'forms' : GTM::$dataLayer['siteTypeLevel2']) . "/central/all/new cars/G37_Forms/" . ($typeDevice == 'WEB' ? "desktop" : "mobile") . "/" . GTM::$dataLayer['vehicleModelBodystyle'] . "/" . $sPageTitle,
                    'language' => GTM::$dataLayer['language'],
                    'country' => GTM::$dataLayer['country'],
                    'siteTypeLevel1' => "cpp",
                    'siteTypeLevel2' => empty(GTM::$dataLayer['siteTypeLevel2']) ? 'forms' : GTM::$dataLayer['siteTypeLevel2'],
                    'siteOwner' => "central",
                    'siteTarget' => "all",
                    'siteFamily' => "new cars",
                    'pageCategory' => "form page",
                    'pageVariant' => $FORM_CONTEXT_CODE,
                    'formsName' => "brochure request",
                    'mainStepIndicator' => "0",
                    'mainStepName' => "customer-target",
                    'vehicleModelBodystyle' => GTM::$dataLayer['vehicleModelBodystyle'],
                    'vehicleModelBodystyleLabel' => $sLabel,
                    'to_push' => TRUE
                );
                break;
            case "Offer":
                $aDateGTM['Offer'] = array(
                    'event' => "updatevirtualpath",
                    'brand' => "citroen",
                    'virtualPageURL' => "cpp/offer-request/part-pro/" . ($typeDevice == 'WEB' ? "desktop" : "mobile") . "/step-0",
                    'pageName' => "cpp/" . (empty(GTM::$dataLayer['siteTypeLevel2']) ? 'forms' : GTM::$dataLayer['siteTypeLevel2']) . "/central/all/new cars/G37_Forms/" . ($typeDevice == 'WEB' ? "desktop" : "mobile") . "/" . GTM::$dataLayer['vehicleModelBodystyle'] . "/" . $sPageTitle,
                    'language' => GTM::$dataLayer['language'],
                    'country' => GTM::$dataLayer['country'],
                    'siteTypeLevel1' => "cpp",
                    'siteTypeLevel2' => empty(GTM::$dataLayer['siteTypeLevel2']) ? 'forms' : GTM::$dataLayer['siteTypeLevel2'],
                    'siteOwner' => "central",
                    'siteTarget' => "all",
                    'siteFamily' => "new cars",
                    'pageCategory' => "form page",
                    'pageVariant' => $FORM_CONTEXT_CODE,
                    'formsName' => "offer request",
                    'mainStepIndicator' => "0",
                    'mainStepName' => "customer-target",
                    'vehicleModelBodystyle' => GTM::$dataLayer['vehicleModelBodystyle'],
                    'vehicleModelBodystyleLabel' => $sLabel,
                    'to_push' => TRUE
                );
                break;
            default:
                break;
        }

        $this->assign("aDataGTM", $aDateGTM);
        $this->assign("vehicleModelBodystyleLabel", $sLabel);
        $this->assign("siteTypeLevel2", empty(GTM::$dataLayer['siteTypeLevel2']) ? 'forms' : GTM::$dataLayer['siteTypeLevel2']);
        $this->assign("pageName", $sPageTitle);
        // Vérifie si le service brochure picker est disponible ou pas.
        $aSiteWS = Pelican_Cache::fetch('Frontend/Citroen/SiteWs', array($_SESSION[APP]['SITE_ID']));
        $aWs = Pelican_Cache::fetch('Frontend/Citroen/WsConfig');
        $bcheckBrochureSrv = false;
        if (isset($aSiteWS[$aWs['CITROEN_SERVICE_BROCHURE']['id']]) && $aSiteWS[$aWs['CITROEN_SERVICE_BROCHURE']['id']] == true) {
            $bcheckBrochureSrv = true;
            if (isset($aWs['CITROEN_SERVICE_BROCHURE']['url']) && !empty($aWs['CITROEN_SERVICE_BROCHURE']['url'])) {
                // Appel du WS externe pour les tests
                if ($aWs['CITROEN_SERVICE_BROCHURE']['url'] == Pelican::$config['WS']['BROCHURE_PICKER']['URL_EXT']) {
                    $sServiceAvailable = json_decode(@file_get_contents(Pelican::$config['WS']['BROCHURE_PICKER']['URL_EXT']));
                } else {// Appel du WS interne distinct pour chaque site pays
                    $sServiceAvailable = json_decode(@file_get_contents($aWs['CITROEN_SERVICE_BROCHURE']['url']));
                }
            }
        }
        // Affiche message erreur si le service n'est pas disponible uniquement pour le formulaire brochure
        if (($bcheckBrochureSrv && Pelican::$config['PERSO']['FORMTYPES'][$aFormTypeId] == 'Brochure+request')
            &&
            (
                (is_object($sServiceAvailable->response) && isset($sServiceAvailable->response->service) && $sServiceAvailable->response->service == 'KO')
                || ($sServiceAvailable->response == NULL) || ($sServiceAvailable->response->service == NULL)
            )
        ) {
            $this->assign("sError", true);
        } else {
            $this->assign("sSharer", $sSharer);
            $this->assign("formActivation", $aData['ZONE_TITRE4']);
            $this->assign("formulaire", $formulaire);
            $this->assign("formTypeFull", $aFormTypes[$aFormTypeId]);
            $this->assign("formtype", Pelican::$config['PERSO']['FORMTYPES'][$aFormTypeId]);
            $this->assign("aData", $aData);
            $this->assign("typeDevice", $typeDevice);
            $this->assign("sCodePays", strtoupper($_SESSION[APP]['CODE_PAYS']));
            $this->assign("sLanguePays", strtolower($_SESSION[APP]['LANGUE_CODE']));
            $this->assign("trancheEssayer", $trancheEssayer);
            if ($this->isMobile()) {
                if ($_GET['id'] && $_GET['type'] == "RTO") {
                    $this->assign('idPDV', $_GET['id']);
                    $this->assign('isRTO', $_GET['type']);
                }
            }
        }
        $this->fetch();
    }

    public function getContenuAction()
    {
        $aData = $this->getParams();
        $formulaire = Pelican_Cache::fetch("Frontend/Citroen/Formulaire", array(
            $aData["typeFormulaire"],
            $aData["typeClient"],
            $aData["typeDevice"],
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            '',
            '',
            $aData["contextForm"]
        ));
        $_SESSION[APP]["FORM_TYPE_CLIENT"] = $aData["typeClient"];
        $sFormtype = Pelican::$config['PERSO']['FORMTYPES'][$aData["typeFormulaire"]];
        $this->getRequest()->addResponseCommand('script', array(
            'value' => sprintf(
                "getFormId('%s','%s','%s','%s','%s','%s','%s', '%s', '%s');",
                $formulaire['FORM_INCE_CODE'],
                $formulaire['FORM_USER_TYPE_CODE'],
                $aData['idSection'],
                $aData['lcdvForm'],
                $aData['email'],
                $sFormtype,
                $aData['isDeployed'],
                $aData['contextForm'],
                $aData['form_page_id']
            )
        ));
    }

    public function reinitFormAction()
    {
        $aParams = $this->getParams();
        $typeDevice = ($this->isMobile() == true) ? 'MOB' : 'WEB';
        $aData = Pelican_Cache::fetch("Frontend/Citroen/ZoneTemplate", array(
            $aParams['idPage'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            $aParams['zoneTid'],
            $aParams['areaId'],
            $aParams['zoneOrder'],
            Pelican::$config['ZONE']['FORMULAIRE']
        ));
        if ($aData['ZONE_TITRE4'] != 'CHOIX') {
            $formulaire = Pelican_Cache::fetch("Frontend/Citroen/Formulaire", array(
                $aData["ZONE_TITRE3"],
                $aData['ZONE_TITRE4'],
                $typeDevice,
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                '',
                '',
                $aParams['contextForm']
            ));
        }
        if (isset($aData['ZONE_ATTRIBUT2']) && !empty($aData['ZONE_ATTRIBUT2'])) {
            $aFormTypeId = $aData['ZONE_ATTRIBUT2'];
        } else {
            $aFormTypeId = $aData['ZONE_TITRE3'];
        }
        $sSharer = Backoffice_Share_Helper::getSharer($aData['FORM_DEPLOYE']['FORM_SHARE'], $aData['SITE_ID'], $aData['LANGUE_ID'], Pelican::$config['MODE_SHARER'][0], array('getParams' => $aData));
        $this->assign("sSharer", $sSharer);
        $this->assign("formActivation", $aData['ZONE_TITRE4']);
        $this->assign("formulaire", $formulaire);
        $this->assign("formtype", Pelican::$config['PERSO']['FORMTYPES'][$aFormTypeId]);
        $this->assign("aData", $aData);
        $this->assign("typeDevice", $typeDevice);
        $this->assign("formName", 'FormulaireDeploy' . $aData['ZONE_ORDER']);
        $this->assign("email", $aParams['email']);
        $this->fetch();
    }

    public function getINCECodeAction()
    {
        $aData = $this->getParams();
        $aFormulaire = Pelican_Cache::fetch("Frontend/Citroen/Formulaire", array(
            $aData["TYPE_ID"],
            $aData['USER_TYPE_CODE'],
            $aData['EQUIPEMENT_CODE'],
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            '',
            '',
            $aData['CONTEXT_CODE']
        ));
        echo json_encode($aFormulaire);
    }

    public function iframeAction()
    {
        $head = $this->getView()->getHead();
        $head->setCss('/form_ds.css', 'screen', '', '', 'cssPack3');
        $aData = $this->getParams();
        $formulaire = Pelican_Cache::fetch("Frontend/Citroen/Formulaire", array(
            '',
            '',
            '',
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            '',
            $aData["idform"],
            $aData["contextForm"]
        ));
        //var_dump($formulaire);
        if (null != $formulaire) {
            $formtype = Pelican::$config['PERSO']['FORMTYPES'][$formulaire['FORM_TYPE_ID']];
        } else {
            $formtype = '';
        }
        // Récupération identifiant formulaire GTM
        $formTypeFull = \Pelican_Cache::fetch("Frontend/Citroen/FormType", array(true));
        if (isset($_GET["formClass"])) {
            switch ($_GET["formClass"]) {
                case 'c-skin':
                    // on ne touche pas au skin
                    break;
                case 'ds':
                    $this->assign("formClass", "ds");
                    break;
                default:
                    // neutre : on applique le DS si le form est en DS
                    if ($aData["styleForm"] == "ds") {
                        $this->assign("formClass", "ds");
                    }
                    break;
            }
        }
        // Google Tag Manager - initialisation variables générales
        GTM::$dataLayer['brand'] = Pelican::$config['GTM']['brand'];
        GTM::$dataLayer['virtualPageURL'] = $_SERVER['REQUEST_URI'];
        GTM::$dataLayer['pageName'] = 'Formulaire';
        GTM::$dataLayer['language'] = strtolower($_SESSION[APP]['LANGUE_CODE']);
        GTM::$dataLayer['country'] = strtolower($_SESSION[APP]['CODE_PAYS']);
        GTM::$dataLayer['siteTypeLevel1'] = Pelican::$config['GTM']['siteTypeLevel1'];
        GTM::$dataLayer['siteTypeLevel2'] = '';
        GTM::$dataLayer['scoringVisit'] = GTM::serializeScore();
        GTM::$dataLayer['profiles'] = GTM::serializeProfile();
        GTM::$dataLayer['vehicleModelBodystyle'] = '';
        GTM::$dataLayer['vehicleModelBodystyleLabel'] = '';
        GTM::$dataLayer['vehicleFinition'] = '';
        GTM::$dataLayer['vehicleFinitionLabel'] = '';
        GTM::$dataLayer['vehicleMotor'] = '';
        GTM::$dataLayer['vehicleMotorLabel'] = '';
        GTM::$dataLayer['edealerName'] = '';
        GTM::$dataLayer['edealerSiteGeo'] = '';
        GTM::$dataLayer['edealerID'] = '';
        GTM::$dataLayer['edealerCity'] = '';
        GTM::$dataLayer['edealerAddress'] = '';
        GTM::$dataLayer['edealerPostalCode'] = '';
        GTM::$dataLayer['edealerRegion'] = '';
        GTM::$dataLayer['edealerCountry'] = '';
        GTM::$dataLayer['internalSearchKeyword'] = '';
        GTM::$dataLayer['internalSearchType'] = '';
        $this->assign('gtmTag', Frontoffice_Analytics_Helper::getGtmTag(), false);
        $pays = $_SESSION[APP]['CODE_PAYS'] == 'CT' ? 'FR' : $_SESSION[APP]['CODE_PAYS'];
        $lang = strtolower($_SESSION[APP]['LANGUE_CODE']);
        $isDeployed = isset($aData['isDeployed']) ? $aData['isDeployed'] : null;
        $this->assign("formtype", $formtype);
        //Debut CPW-3350
        $aSite = Pelican_Cache::fetch("Frontend/Site", array($_SESSION[APP]['SITE_ID']));
        $frontFont = isset($aSite['SITE_CITROEN_FONT2']) ? intval($aSite['SITE_CITROEN_FONT2']) : 0;
        switch ($frontFont) {
            // Arial mini
            case 2:
                $this->assign("cssArial", "_arial");
                break;
            // Arial
            default:
                $this->assign("cssArial", "");
                break;
        }
        //Fin CPW-3350
        if ($this->isMobile()) {
            $iPageId = $aData['ppid'];
        } else {
            $iPageId = $aData['form_page_id'];
        }
        $bShowroom = false;
        if (intval($iPageId) > 0) {
            $aPageShowroomColor = Frontoffice_Showroom_Helper::getShowroomColor($iPageId, $_SESSION[APP]['LANGUE_ID'], Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']);
            if (!empty($aPageShowroomColor['PAGE_PRIMARY_COLOR']) && !empty($aPageShowroomColor['PAGE_SECOND_COLOR'])) {
                $aData['PRIMARY_COLOR'] = $aPageShowroomColor['PAGE_PRIMARY_COLOR'];
                $aData['SECOND_COLOR'] = $aPageShowroomColor['PAGE_SECOND_COLOR'];
                $bShowroom = true;
            }
        }
        if (isset($aData['iframe'])) {
            $isIframe = 1;
        } else {
            $isIframe = 0;
        }
        if (isset($aData['PRIMARY_COLOR']) && isset($aData['SECOND_COLOR'])) {
            $sCss = Frontoffice_Showroom_Helper::getCssWithDynamicColors($aData['PRIMARY_COLOR'], $aData['SECOND_COLOR'], $isIframe);
            $sCssMobile = Frontoffice_Showroom_Helper::getCssWithDynamicColorsMobile($aData['PRIMARY_COLOR'], $aData['SECOND_COLOR'], $isIframe);
            $this->assign("sCss", $sCss, false);
            $this->assign("sCssMobile", $sCssMobile, false);
            $bShowroom = true;
        }
        $this->assign("formTypeFull", $formTypeFull[$formulaire['FORM_TYPE_ID']]);
        $this->assign("formulaire", $formulaire);
        $this->assign("bShowroom", $bShowroom);
        $this->assign("aData", $aData);
        $this->assign("pays", $pays);
        $this->assign("lang", $lang);
        $this->assign("culture", $lang . '-' . $pays);
        $this->assign("email", $aData['email']);
        $this->assign("formds_css", $head->css[0]);
        $this->assign("domain", Pelican::$config["DOCUMENT_HTTP"]);
        $this->assign("isDeployed", $isDeployed);
        $this->fetch();
    }

    public function finalStepAction()
    {
        $aData = $this->getParams();
        if ($this->isMobile()) {
            $iPageId = $aData['ppid'];
        } else {
            $iPageId = $aData['form_page_pid'];
        }


        $aPage = Pelican_Cache::fetch("Frontend/Page", array(
            $iPageId,
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID']
        ));

        if (!empty($iPageId) && ($aPage['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'] || $aPage['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE'])) {
            $aPageShowroomColor = Frontoffice_Showroom_Helper::getShowroomColor((int)$iPageId, $_SESSION[APP]['LANGUE_ID'], Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']);
            if (!empty($aPageShowroomColor['PAGE_PRIMARY_COLOR']) && !empty($aPageShowroomColor['PAGE_SECOND_COLOR'])) {
                $aData['PRIMARY_COLOR'] = $aPageShowroomColor['PAGE_PRIMARY_COLOR'];
                $aData['SECOND_COLOR'] = $aPageShowroomColor['PAGE_SECOND_COLOR'];
            } else {
                $aColors = Frontoffice_Showroom_Helper::getShowroomColor((int)$iPageId, $_SESSION[APP]['LANGUE_ID'], true);
                $aData['PRIMARY_COLOR'] = $aColors['PAGE_PRIMARY_COLOR'];
                $aData['SECOND_COLOR'] = $aColors['PAGE_SECOND_COLOR'];
            }
        }
        $this->assign('aData', $aData);
        $zone = Pelican_Cache::fetch("Frontend/Citroen/ZoneTemplate", array(
            $aData["idPage"],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            $aData["zoneTid"],
            $aData["areaId"],
            $aData["zoneOrder"]
        ));
        $formulaire = Pelican_Cache::fetch("Frontend/Citroen/Formulaire", array(
            '',
            '',
            '',
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            '',
            $aData["idForm"]
        ));
        $formType = \Pelican_Cache::fetch(
            "Frontend/Citroen/FormType"
        );
        $this->assign('titleCTA', t('TITRE_GENERIQUE_' . $formType[$formulaire['FORM_TYPE_ID']]));
        $this->assign('texteCTA', t('TEXTE_GENERIQUE_' . $formType[$formulaire['FORM_TYPE_ID']]));
        $this->assign('isDeployed', $aData["isDeployed"]);
        // Récupération identifiant formulaire GTM
        $formTypeFull = \Pelican_Cache::fetch("Frontend/Citroen/FormType", array(true));
        // Google Tag Manager - initialisation variables générales
        GTM::$dataLayer['brand'] = Pelican::$config['GTM']['brand'];
        GTM::$dataLayer['virtualPageURL'] = $_SERVER['REQUEST_URI'];
        GTM::$dataLayer['pageName'] = 'Formulaire';
        GTM::$dataLayer['language'] = strtolower($_SESSION[APP]['LANGUE_CODE']);
        GTM::$dataLayer['country'] = strtolower($_SESSION[APP]['CODE_PAYS']);
        GTM::$dataLayer['siteTypeLevel1'] = Pelican::$config['GTM']['siteTypeLevel1'];
        GTM::$dataLayer['siteTypeLevel2'] = '';
        GTM::$dataLayer['scoringVisit'] = GTM::serializeScore();
        GTM::$dataLayer['profiles'] = GTM::serializeProfile();
        GTM::$dataLayer['vehicleModelBodystyle'] = '';
        GTM::$dataLayer['vehicleModelBodystyleLabel'] = '';
        GTM::$dataLayer['vehicleFinition'] = '';
        GTM::$dataLayer['vehicleFinitionLabel'] = '';
        GTM::$dataLayer['vehicleMotor'] = '';
        GTM::$dataLayer['vehicleMotorLabel'] = '';
        GTM::$dataLayer['edealerName'] = '';
        GTM::$dataLayer['edealerSiteGeo'] = '';
        GTM::$dataLayer['edealerID'] = '';
        GTM::$dataLayer['edealerCity'] = '';
        GTM::$dataLayer['edealerAddress'] = '';
        GTM::$dataLayer['edealerPostalCode'] = '';
        GTM::$dataLayer['edealerRegion'] = '';
        GTM::$dataLayer['edealerCountry'] = '';
        GTM::$dataLayer['internalSearchKeyword'] = '';
        GTM::$dataLayer['internalSearchType'] = '';
        $zonePage = Pelican_Cache::fetch("Frontend/Page", array(
            $iPageId,
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion()
        ));
        if (in_array($zonePage['TEMPLATE_PAGE_ID'], array(Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'], Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_INTERNE']))) {
            GTM::$dataLayer['siteTypeLevel2'] = 'Showroom';
        }
        if ($aData['params']['timeslotrenewcar'] == "DPR_0") {
            $aData['params']['FORMS_TYPE'] = "hot lead";
        } else {
            $aData['params']['FORMS_TYPE'] = "cold lead";
        }
        $parcours = "";
        if (isset($aData['params']['status'])) {
            $parcours = ucfirst($aData['params']['status']);
        }
//            if($this->isMobile()){
//                switch ($zonePage['TEMPLATE_PAGE_ID']) {
//                    case Pelican::$config['TEMPLATE_PAGE']['GABARIT_BLANC']:
//
//                        if($zonePage['PAGE_VEHICULE'] != ""){
//                            $aData['params']['contextForm'] = 'CAR';
//                        }else{
//                            //Gblanc PDV
//                           $aData['params']['contextForm'] = 'RTO';
//                        }
//                        break;
//                    case Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']:
//                        //showroom accueil
//                        $aData['params']['contextForm'] = 'CAR';
//                        break;
//                }
//            }
//		 switch($aData['params']['contextForm']){
//			case "CAR":
//				$context =  "context-car";
//			break;
//			case "RTO":
//				$context =  "context-dealer";
//			break;
//			default:
//				$context =  "context-none";
//			break;
//		 }
        if (strlen($aData['params']['car']) > 6) {
            $aData['params']['car'] = substr($aData['params']['car'], 0, 6);
        }

        $sLangue = empty($_SESSION[APP]['LANGUE_CODE']) ? 'fr' : strtolower($_SESSION[APP]['LANGUE_CODE']);
        $sPays = empty($_SESSION[APP]['CODE_PAYS']) ? 'FR' : (strtoupper($_SESSION[APP]['CODE_PAYS']) == 'CT' ? 'FR' : strtoupper($_SESSION[APP]['CODE_PAYS']));
        if ($aData['params']['search']) {
            $dealer = Pelican_Cache::fetch("Frontend/Citroen/Annuaire/Dealer", array(
                $aData['params']['search'],
                $sPays,
                $sLangue
            ));

            $this->assign("dealer", $dealer);
        }
        GTM::$dataLayer['uiGender'] = $aData['params']['gender'] == 'CI_1' ? 'M' : $aData['params']['gender'] == 'CI_2' ? 'F' : '';
        GTM::$dataLayer['uiPostalCode'] = $aData['params']['zipcode'];
        GTM::$dataLayer['uiCity'] = $aData['params']['city'] ? $aData['params']['city'] : '';
        GTM::$dataLayer['uiLogged'] = $aData['params']['usr_type_login'] ? '1' : '0';
        GTM::$dataLayer['uiState'] = $aData['params']['usr_addr_country'] ? $aData['params']['usr_addr_country'] : '';
        GTM::$dataLayer['uiBirthday'] = '';
        GTM::$dataLayer['uiEdealerID'] = $aData['params']['DEALER_RRDI'];
        GTM::$dataLayer['uiEdealerIDLocal'] = '';
        GTM::$dataLayer['uiEdealerSiteGeo'] = $aData['params']['search'];
        GTM::$dataLayer['uiVehicleModelBodystyle'] = $aData['params']['car'];
        GTM::$dataLayer['uiVehicleModelBodystyleLabel'] = $aData['params']['TESTDRIVE_CAR'];
        GTM::$dataLayer['uiUser'] = $aData['params']['instance_id'] ? $aData['params']['instance_id'] : '';

        switch ($aData['params']['contextForm']) {
            case "CAR":
            case "CAR13":
                $context = "context-car";
                break;
            case "RTO":
                $context = "context-dealer";
                break;
            default:
                $context = "context-none";
                break;
        }
        $parcours_2 = $_SESSION[APP]["FORM_TYPE_CLIENT"] == 'IND' ? 'part' : 'pro';

        if (empty(GTM::$dataLayer['siteTypeLevel2'])) {
            GTM::$dataLayer['siteTypeLevel2'] = 'forms';
        }


        //debug($aData);die;
        $aGtm = array(
            'TestDrive' => array(
                'event' => 'updatevirtualpath',
                'brand' => 'citroen',
                'virtualPageURL' => 'cpp/test-drive/' . $aData['params']['status'] . '/' . ($this->isMobile() == 1 ? 'mobile' : 'desktop') . '/step-4',
                'pageName' => GTM::$dataLayer['siteTypeLevel1'] . '/' . GTM::$dataLayer['siteTypeLevel2'] . '/central/' . ($aData['params']['status'] == "part" ? "B2C" : "B2B") . '/new cars/G37_Forms/' . ($this->isMobile() == 1 ? 'mobile' : 'desktop') . '/' . $aData['params']['car'] . '/' . addslashes($aData['page_title']),
                'language' => GTM::$dataLayer['language'],
                'country' => GTM::$dataLayer['country'],
                'siteTypeLevel1' => GTM::$dataLayer['siteTypeLevel1'],
                'siteTypeLevel2' => GTM::$dataLayer['siteTypeLevel2'],
                'siteOwner' => "central",
                'siteTarget' => ($aData['params']['status'] == "part" ? "B2C" : "B2B"),
                'siteFamily' => "new cars",
                'pageCategory' => "lead page",
                'pageVariant' => $context,
                'formsName' => "test drive",
                'formsLeadID' => $aData['params']['GITID'],
                'formsLeadType' => 'hot lead',
                'mainStepIndicator' => 4,
                'mainStepName' => 'confirmation',
                'formsPostalCode' => $aData['params']['zipcode'],
                'uiExpectedPurchase' => $aData['params']['timeslotrenewcar'],
                'vehicleModelBodystyle' => $aData['params']['car'],
                'vehicleModelBodystyleLabel' => addslashes($aData['params']['TESTDRIVE_CAR']),
                'vehicleVersionId' => "",
                'edealerName' => $aData['params']['DEALER_NAME'],
                'edealerSiteGeo' => $aData['params']['search'],
                'edealerID' => $aData['params']['DEALER_RRDI'],
                'edealerCity' => $aData['params']['DEALER_CITY'],
                'edealerIDLocal' => GTM::$dataLayer['edealerIDLocal'],
                'edealerAddress' => $aData['params']['DEALER_ADDR_1'],
                'edealerPostalCode' => $aData['params']['DEALER_POSTAL_CODE'],
                'edealerRegion' => $dealer['addressDetail']['Region'],
                'edealerPDV' => '',
                'edealerCountry' => GTM::$dataLayer['edealerCountry'],
                /*'uiGender' => GTM::$dataLayer['uiGender'],
                'uiPostalCode' => GTM::$dataLayer['uiPostalCode'],
                'uiCity' => '',
                'uiLogged' => GTM::$dataLayer['uiLogged'],
                'uiState' => GTM::$dataLayer['uiState'],
                'uiBirthday' => GTM::$dataLayer['uiBirthday'],
                'uiEdealerID' => GTM::$dataLayer['uiEdealerID'],
                'uiEdealerIDLocal' => GTM::$dataLayer['uiEdealerIDLocal'],
                'uiEdealerSiteGeo' => GTM::$dataLayer['uiEdealerSiteGeo'],
                'uiVehicleModelBodystyle' => "",
                'uiVehicleModelBodystyleLabel' => "",
                'uiUser' => GTM::$dataLayer['uiUser']*/
            ),
            'Brochure' => array(
                'event' => 'updatevirtualpath',
                'brand' => 'citroen',
                'virtualPageURL' => 'cpp/brochure-request/' . $aData['params']['status'] . '/' . ($this->isMobile() == 1 ? 'mobile' : 'desktop') . '/step-3',
                'pageName' => GTM::$dataLayer['siteTypeLevel1'] . '/' . GTM::$dataLayer['siteTypeLevel2'] . '/central/' . ($aData['params']['status'] == "part" ? "B2C" : "B2B") . '/new cars/G37_Forms/' . ($this->isMobile() == 1 ? 'mobile' : 'desktop') . '/' . $aData['params']['car'] . '/' . addslashes($aData['page_title']),
                'language' => GTM::$dataLayer['language'],
                'country' => GTM::$dataLayer['country'],
                'siteTypeLevel1' => GTM::$dataLayer['siteTypeLevel1'],
                'siteTypeLevel2' => GTM::$dataLayer['siteTypeLevel2'],
                'siteOwner' => "central",
                'siteTarget' => ($aData['params']['status'] == "part" ? "B2C" : "B2B"),
                'siteFamily' => "new cars",
                'pageCategory' => "lead page",
                'pageVariant' => $context,
                'formsName' => "brochure request",
                'formsLeadID' => $aData['params']['GITID'],
                'formsLeadType' => 'hot lead',
                'mainStepIndicator' => 3,
                'mainStepName' => 'confirmation',
                'formsPostalCode' => $aData['params']['zipcode'],
                'uiExpectedPurchase' => $aData['params']['timeslotrenewcar'],
                'vehicleModelBodystyle' => $aData['params']['TESTDRIVE_CAR_LCDV'],
                'vehicleModelBodystyleLabel' => addslashes($aData['params']['DOC_CAR_NAME']),
                'vehicleVersionId' => "",
                'edealerName' => $aData['params']['DEALER_NAME'],
                'edealerSiteGeo' => $aData['params']['search'],
                'edealerID' => $aData['params']['DEALER_RRDI'],
                'edealerCity' => $aData['params']['DEALER_CITY'],
                'edealerIDLocal' => GTM::$dataLayer['edealerIDLocal'],
                'edealerAddress' => $aData['params']['DEALER_ADDR_1'],
                'edealerPostalCode' => $aData['params']['DEALER_POSTAL_CODE'],
                'edealerRegion' => $dealer['addressDetail']['Region'],
                'edealerPDV' => '',
                'edealerCountry' => GTM::$dataLayer['edealerCountry'],
                /*'uiGender' => GTM::$dataLayer['uiGender'],
                'uiPostalCode' => GTM::$dataLayer['uiPostalCode'],
                'uiCity' => '',
                'uiLogged' => GTM::$dataLayer['uiLogged'],
                'uiState' => GTM::$dataLayer['uiState'],
                'uiBirthday' => GTM::$dataLayer['uiBirthday'],
                'uiEdealerID' => GTM::$dataLayer['uiEdealerID'],
                'uiEdealerIDLocal' => GTM::$dataLayer['uiEdealerIDLocal'],
                'uiEdealerSiteGeo' => GTM::$dataLayer['uiEdealerSiteGeo'],
                'uiVehicleModelBodystyle' => "",
                'uiVehicleModelBodystyleLabel' => "",
                'uiUser' => GTM::$dataLayer['uiUser']*/
            ),
            'Offer' => array(
                'event' => 'updatevirtualpath',
                'brand' => 'citroen',
                'virtualPageURL' => 'cpp/offer-request/' . $aData['params']['status'] . '/' . ($this->isMobile() == 1 ? 'mobile' : 'desktop') . '/step-4',
                'pageName' => GTM::$dataLayer['siteTypeLevel1'] . '/' . GTM::$dataLayer['siteTypeLevel2'] . '/central/' . ($aData['params']['status'] == "part" ? "B2C" : "B2B") . '/new cars/G37_Forms/' . ($this->isMobile() == 1 ? 'mobile' : 'desktop') . '/' . $aData['params']['car'] . '/' . addslashes($aData['page_title']),
                'language' => GTM::$dataLayer['language'],
                'country' => GTM::$dataLayer['country'],
                'siteTypeLevel1' => GTM::$dataLayer['siteTypeLevel1'],
                'siteTypeLevel2' => GTM::$dataLayer['siteTypeLevel2'],
                'siteOwner' => "central",
                'siteTarget' => ($aData['params']['status'] == "part" ? "B2C" : "B2B"),
                'siteFamily' => "new cars",
                'pageCategory' => "lead page",
                'pageVariant' => $context,
                'formsName' => "offer request",
                'formsLeadID' => $aData['params']['GITID'],
                'formsLeadType' => 'hot lead',
                'mainStepIndicator' => 4,
                'mainStepName' => 'confirmation',
                'formsPostalCode' => $aData['params']['zipcode'],
                'uiExpectedPurchase' => $aData['params']['timeslotrenewcar'],
                'vehicleModelBodystyle' => $aData['params']['car'],
                'vehicleModelBodystyleLabel' => addslashes($aData['params']['TESTDRIVE_CAR']),
                'vehicleVersionId' => "",
                'edealerName' => $aData['params']['DEALER_NAME'],
                'edealerSiteGeo' => $aData['params']['search'],
                'edealerID' => $aData['params']['DEALER_RRDI'],
                'edealerCity' => $aData['params']['DEALER_CITY'],
                'edealerIDLocal' => GTM::$dataLayer['edealerIDLocal'],
                'edealerAddress' => $aData['params']['DEALER_ADDR_1'],
                'edealerPostalCode' => $aData['params']['DEALER_POSTAL_CODE'],
                'edealerRegion' => $dealer['addressDetail']['Region'],
                'edealerPDV' => '',
                'edealerCountry' => GTM::$dataLayer['edealerCountry'],
                /*'uiGender' => GTM::$dataLayer['uiGender'],
                'uiPostalCode' => GTM::$dataLayer['uiPostalCode'],
                'uiCity' => '',
                'uiLogged' => GTM::$dataLayer['uiLogged'],
                'uiState' => GTM::$dataLayer['uiState'],
                'uiBirthday' => GTM::$dataLayer['uiBirthday'],
                'uiEdealerID' => GTM::$dataLayer['uiEdealerID'],
                'uiEdealerIDLocal' => GTM::$dataLayer['uiEdealerIDLocal'],
                'uiEdealerSiteGeo' => GTM::$dataLayer['uiEdealerSiteGeo'],
                'uiVehicleModelBodystyle' => "",
                'uiVehicleModelBodystyleLabel' => "",
                'uiUser' => GTM::$dataLayer['uiUser']*/
            ),
            'Business' => array(
                'event' => 'updatevirtualpath',
                'brand' => 'citroen',
                'virtualPageURL' => 'cpp/business-request/' . $parcours_2 . '/' . ($this->isMobile() == 1 ? 'mobile' : 'desktop') . '/step-3',
                'pageName' => GTM::$dataLayer['siteTypeLevel1'] . '/' . GTM::$dataLayer['siteTypeLevel2'] . '/central/B2B/new cars/G37_Forms/' . ($this->isMobile() == 1 ? 'mobile' : 'desktop') . '/' . $aData['params']['car'] . '/' . addslashes($aData['page_title']),
                'language' => GTM::$dataLayer['language'],
                'country' => GTM::$dataLayer['country'],
                'siteTypeLevel1' => GTM::$dataLayer['siteTypeLevel1'],
                'siteTypeLevel2' => GTM::$dataLayer['siteTypeLevel2'],
                'siteOwner' => "central",
                'siteTarget' => ($aData['params']['status'] == "part" ? "B2C" : "B2B"),
                'siteFamily' => "new cars",
                'pageCategory' => "lead page",
                'pageVariant' => $context,
                'formsName' => "business request",
                'formsLeadID' => $aData['params']['GITID'],
                'formsLeadType' => 'hot lead',
                'mainStepIndicator' => 3,
                'mainStepName' => 'confirmation',
                'formsPostalCode' => $aData['params']['zipcode'],
                'uiExpectedPurchase' => $aData['params']['timeslotrenewcar'],
                'vehicleModelBodystyle' => $aData['params']['car'],
                'vehicleModelBodystyleLabel' => addslashes($aData['params']['TESTDRIVE_CAR']),
                'edealerName' => $aData['params']['DEALER_NAME'],
                'edealerSiteGeo' => $aData['params']['search'],
                'edealerID' => $aData['params']['DEALER_RRDI'],
                'edealerCity' => $aData['params']['DEALER_CITY'],
                'edealerIDLocal' => GTM::$dataLayer['edealerIDLocal'],
                'edealerAddress' => $aData['params']['DEALER_ADDR_1'],
                'edealerPostalCode' => $aData['params']['DEALER_POSTAL_CODE'],
                'edealerRegion' => $dealer['addressDetail']['Region'],
                'edealerPDV' => '',
                'edealerCountry' => GTM::$dataLayer['edealerCountry'],
                /*'uiGender' => GTM::$dataLayer['uiGender'],
                'uiPostalCode' => GTM::$dataLayer['uiPostalCode'],
                'uiCity' => '',
                'uiLogged' => GTM::$dataLayer['uiLogged'],
                'uiState' => GTM::$dataLayer['uiState'],
                'uiBirthday' => GTM::$dataLayer['uiBirthday'],
                'uiEdealerID' => GTM::$dataLayer['uiEdealerID'],
                'uiEdealerIDLocal' => GTM::$dataLayer['uiEdealerIDLocal'],
                'uiEdealerSiteGeo' => GTM::$dataLayer['uiEdealerSiteGeo'],
                'uiVehicleModelBodystyle' => "",
                'uiVehicleModelBodystyleLabel' => "",
                'uiUser' => GTM::$dataLayer['uiUser']*/
            ),
            'Info' => array(
                'event' => 'updatevirtualpath',
                'brand' => 'citroen',
                'virtualPageURL' => 'cpp/information-request/' . $parcours_2 . '/' . ($this->isMobile() == 1 ? 'mobile' : 'desktop') . '/step-3',
                'pageName' => GTM::$dataLayer['siteTypeLevel1'] . '/' . GTM::$dataLayer['siteTypeLevel2'] . '/central/' . ($aData['params']['status'] == "part" ? "B2C" : "B2B") . '/brand/G37_Forms/' . ($this->isMobile() == 1 ? 'mobile' : 'desktop') . '/' . $aData['params']['car'] . '/' . addslashes($aData['page_title']),
                'language' => GTM::$dataLayer['language'],
                'country' => GTM::$dataLayer['country'],
                'siteTypeLevel1' => GTM::$dataLayer['siteTypeLevel1'],
                'siteTypeLevel2' => GTM::$dataLayer['siteTypeLevel2'],
                'siteOwner' => "central",
                'siteTarget' => ($aData['params']['status'] == "part" ? "B2C" : "B2B"),
                'siteFamily' => "brand",
                'pageCategory' => "lead page",
                'formsName' => 'information request',
                'mainStepIndicator' => 3,
                'mainStepName' => 'confirmation',
                /*'uiGender' => GTM::$dataLayer['uiGender'],
                'uiPostalCode' => GTM::$dataLayer['uiPostalCode'],
                'uiCity' => '',
                'uiLogged' => GTM::$dataLayer['uiLogged'],
                'uiState' => GTM::$dataLayer['uiState'],
                'uiBirthday' => GTM::$dataLayer['uiBirthday'],
                'uiEdealerID' => GTM::$dataLayer['uiEdealerID'],
                'uiEdealerIDLocal' => GTM::$dataLayer['uiEdealerIDLocal'],
                'uiEdealerSiteGeo' => GTM::$dataLayer['uiEdealerSiteGeo'],
                'uiVehicleModelBodystyle' => "",
                'uiVehicleModelBodystyleLabel' => "",
                'uiUser' => GTM::$dataLayer['uiUser']*/
            ),
            'Claim' => array(
                'event' => 'updatevirtualpath',
                'brand' => 'citroen',
                'virtualPageURL' => 'cpp/reclamation-request/' . $aData['params']['status'] . '/' . ($this->isMobile() == 1 ? 'mobile' : 'desktop') . '/step-3',
                'pageName' => GTM::$dataLayer['siteTypeLevel1'] . '/' . GTM::$dataLayer['siteTypeLevel2'] . '/central/' . ($aData['params']['status'] == "part" ? "B2C" : "B2B") . '/brand/G37_Forms/' . ($this->isMobile() == 1 ? 'mobile' : 'desktop') . '/' . $aData['params']['car'] . '/' . addslashes($aData['page_title']),
                'language' => GTM::$dataLayer['language'],
                'country' => GTM::$dataLayer['country'],
                'siteTypeLevel1' => GTM::$dataLayer['siteTypeLevel1'],
                'siteTypeLevel2' => GTM::$dataLayer['siteTypeLevel2'],
                'siteOwner' => "central",
                'siteTarget' => ($aData['params']['status'] == "part" ? "B2C" : "B2B"),
                'siteFamily' => "brand",
                'pageCategory' => "lead page",
                'formsName' => 'reclamation request',
                'mainStepIndicator' => 3,
                'mainStepName' => 'confirmation',
                /*'uiGender' => GTM::$dataLayer['uiGender'],
                'uiPostalCode' => GTM::$dataLayer['uiPostalCode'],
                'uiCity' => '',
                'uiLogged' => GTM::$dataLayer['uiLogged'],
                'uiState' => GTM::$dataLayer['uiState'],
                'uiBirthday' => GTM::$dataLayer['uiBirthday'],
                'uiEdealerID' => GTM::$dataLayer['uiEdealerID'],
                'uiEdealerIDLocal' => GTM::$dataLayer['uiEdealerIDLocal'],
                'uiEdealerSiteGeo' => GTM::$dataLayer['uiEdealerSiteGeo'],
                'uiVehicleModelBodystyle' => "",
                'uiVehicleModelBodystyleLabel' => "",
                'uiUser' => GTM::$dataLayer['uiUser']*/
            ),
            'NewsletterSub' => array(
                'event' => 'updatevirtualpath',
                'brand' => 'citroen',
                'virtualPageURL' => 'cpp/newsletter-registration/' . $parcours_2 . '/' . ($this->isMobile() == 1 ? 'mobile' : 'desktop') . '/confirmation',
                'pageName' => GTM::$dataLayer['siteTypeLevel1'] . '/' . GTM::$dataLayer['siteTypeLevel2'] . '/central/' . ($aData['params']['status'] == "part" ? "B2C" : "B2B") . '/brand/G37_Forms/' . ($this->isMobile() == 1 ? 'mobile' : 'desktop') . '/' . $aData['params']['car'] . '/' . addslashes($aData['page_title']),
                'language' => GTM::$dataLayer['language'],
                'country' => GTM::$dataLayer['country'],
                'siteTypeLevel1' => GTM::$dataLayer['siteTypeLevel1'],
                'siteTypeLevel2' => GTM::$dataLayer['siteTypeLevel2'],
                'siteOwner' => "central",
                'siteTarget' => ($aData['params']['status'] == "part" ? "B2C" : "B2B"),
                'siteFamily' => "brand",
                'pageCategory' => "lead page",
                'formsName' => 'newsletter registration',
                'formsLeadID' => $aData['params']['GITID'],
                'formsLeadType' => 'cold lead'
            ),
            'NewsletterUnsub' => array(
                'event' => 'updatevirtualpath',
                'brand' => 'citroen',
                'virtualPageURL' => 'cpp/newsletter-cancellation/' . $parcours_2 . '/' . ($this->isMobile() == 1 ? 'mobile' : 'desktop') . '/confirmation',
                'pageName' => GTM::$dataLayer['siteTypeLevel1'] . '/' . GTM::$dataLayer['siteTypeLevel2'] . '/central/' . ($aData['params']['status'] == "part" ? "B2C" : "B2B") . '/brand/G37_Forms/' . ($this->isMobile() == 1 ? 'mobile' : 'desktop') . '/' . $aData['params']['car'] . '/' . addslashes($aData['page_title']),
                'language' => GTM::$dataLayer['language'],
                'country' => GTM::$dataLayer['country'],
                'siteTypeLevel1' => GTM::$dataLayer['siteTypeLevel1'],
                'siteTypeLevel2' => GTM::$dataLayer['siteTypeLevel2'],
                'siteOwner' => "central",
                'siteTarget' => ($aData['params']['status'] == "part" ? "B2C" : "B2B"),
                'siteFamily' => "brand",
                'pageCategory' => "lead page",
                'formsName' => 'newsletter deregistration',
                'formsLeadType' => 'cold lead'
            ),
            'pre-lead' => array(
                'event' => 'updatevirtualpath',
                'brand' => 'citroen',
                'virtualPageURL' => 'cpp/pre-lead/part/' . ($this->isMobile() == 1 ? 'mobile' : 'desktop') . '/step-2',
                'pageName' => GTM::$dataLayer['siteTypeLevel1'] . '/' . GTM::$dataLayer['siteTypeLevel2'] . '/central/B2C/new cars/G37_Forms/' . ($this->isMobile() == 1 ? 'mobile' : 'desktop') . '/' . $aData['params']['car'] . '/' . addslashes($aData['page_title']),
                'language' => GTM::$dataLayer['language'],
                'country' => GTM::$dataLayer['country'],
                'siteTypeLevel1' => GTM::$dataLayer['siteTypeLevel1'],
                'siteTypeLevel2' => GTM::$dataLayer['siteTypeLevel2'],
                'siteOwner' => "central",
                'siteTarget' => "B2C",
                'siteFamily' => "new cars",
                'pageCategory' => "lead page",
                'pageVariant' => $context,
                'formsName' => "pre lead",
                'formsLeadID' => $aData['params']['GITID'],
                'formsLeadType' => 'cold lead',
                'mainStepIndicator' => 2,
                'mainStepName' => 'confirmation',
                'vehicleModelBodystyle' => $aData['params']['car'],
                'vehicleModelBodystyleLabel' => addslashes($aData['params']['TESTDRIVE_CAR']),
                'vehicleVersionId' => ""
                /*'edealerName' => mb_strtolower(addslashes($aData['params']['DEALER_NAME'])),
                'edealerSiteGeo' => $aData['params']['search'],
                'edealerID' => $aData['params']['DEALER_RRDI'],
                'edealerCity' => mb_strtolower(addslashes($aData['params']['DEALER_CITY'])),
                'edealerIDLocal' => GTM::$dataLayer['edealerIDLocal'],
                'edealerAddress' => $aData['params']['DEALER_ADDR_1'],
                'edealerPostalCode' => $aData['params']['DEALER_POSTAL_CODE'],
                'edealerRegion' => $dealer['addressDetail']['Region'],
                'edealerPDV' => '',
                'edealerCountry' => GTM::$dataLayer['edealerCountry'],
                /*'uiGender' => GTM::$dataLayer['uiGender'],
                'uiPostalCode' => GTM::$dataLayer['uiPostalCode'],
                'uiCity' => '',
                'uiLogged' => GTM::$dataLayer['uiLogged'],
                'uiState' => GTM::$dataLayer['uiState'],
                'uiBirthday' => GTM::$dataLayer['uiBirthday'],
                'uiEdealerID' => GTM::$dataLayer['uiEdealerID'],
                'uiEdealerIDLocal' => GTM::$dataLayer['uiEdealerIDLocal'],
                'uiEdealerSiteGeo' => GTM::$dataLayer['uiEdealerSiteGeo'],
                'uiVehicleModelBodystyle' => "",
                'uiVehicleModelBodystyleLabel' => "",
                'uiUser' => GTM::$dataLayer['uiUser']*/
            )
        );
        $sSharer = Backoffice_Share_Helper::getSharer($zone['ZONE_LABEL2'], $zone['SITE_ID'], $zone['LANGUE_ID'], Pelican::$config['MODE_SHARER'][0], array('getParams' => $zone));
        $this->assign("sSharer", $sSharer);

        if (strlen($aData['params']['car']) > 6) {
            $aData['params']['car'] = substr($aData['params']['car'], 0, 6);
        }
        if ($aData['params']['car'] || $aData['params']['TESTDRIVE_CAR_LCDV']) {
            $carId = $aData['params']['car'] ? $aData['params']['car'] : $aData['params']['TESTDRIVE_CAR_LCDV'];
            $gamme = ($formulaire['FORM_USER_TYPE_CODE'] == 'IND') ? 'VP' : 'VU';
            $car = VehiculeGamme::getVehiculeByLCDVGamme(
                $carId, $gamme, $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']
            );
            $mediaPath = Pelican::$config['MEDIA_HTTP'] . Citroen_Media::getFileNameMediaFormat($car['VISUEL_VEHICULE'], Pelican::$config['MEDIA_FORMAT_ID']['EXPAND_VEHICULE_X4']);
            $mediaPathMobile = Pelican::$config['MEDIA_HTTP'] . Citroen_Media::getFileNameMediaFormat($car['VISUEL_VEHICULE'], Pelican::$config['MEDIA_FORMAT_ID']['GAMME_VEHICULE_MOBILE']);
            $this->assign("car", $car);
            $this->assign("carId", $carId);
            $this->assign("mediaPath", $mediaPath);
            $this->assign("mediaPathMobile", $mediaPathMobile);
            $oUser = \Citroen\UserProvider::getUser();
            if (!is_null($oUser)) {
                $iUserId = $oUser->getId();
            } else {
                $iUserId = null;
            }
            $aSelectionVehicules = SelectionVehicule::getUserSelection($iUserId);
            $bActiveAddToSelection = true;
            if (is_array($aSelectionVehicules) && !empty($aSelectionVehicules)) {
                $aSortedByLcdv6 = array();
                foreach ($aSelectionVehicules as $aSelectionVehicule) {
                    if ($aSelectionVehicule['lcdv6_code'] == $car['LCDV6']) {
                        //vehicule dans la selection
                        $bActiveAddToSelection = false;
                    }
                }
                if (count($aSelectionVehicules) <= 3) {
                    $iOrder = count($aSelectionVehicules);
                }
            } else {
                $iOrder = 0;
            }
            $this->assign("iOrder", $iOrder);
            $this->assign("bActiveAddToSelection", $bActiveAddToSelection);
        }
        if ($aData['params']["gender"] == 'CI_2') {
            $civility = $civility = t('MADAME');
        } else if ($aData['params']["gender"] == 'CI_3') {
            $civility = $civility = t('MADEMOISELLE');
        } else {
            $civility = t('MONSIEUR');
        }
        $address = '';
        if ($aData['params']['address']) {
            $address = $aData['params']['address'] . "&nbsp;" . $aData['params']['zipcode'] . "&nbsp;" . $aData['params']['city'];
        }
        //Cta
        $aCta = Citroen_Cache::fetchProfiling($aData['ZONE_PERSO'], "Frontend/Citroen/ZoneMulti", array(
            $aData["idPage"],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            $zone['ZONE_TEMPLATE_ID'],
            'CTAFORM',
            $zone['AREA_ID'],
            $zone['ZONE_ORDER']
        ));
        if (is_array($aCta) && !empty($aCta)) {
            foreach ($aCta as $key => $multi) {
                if (isset($multi['OUTIL']) && !empty($multi['OUTIL'])) {
                    $aData['CTA'] = $multi['OUTIL'];
                    $aCta[$key]['OUTIL'] = Pelican_Request::call('_/Layout_Citroen_CTA/', $aData);
                }
            }
        }
        $this->assign('aCta', $aCta);
        //Mentions légales
        if ($zone['ZONE_TITRE7'] != '') {
            $aMentionsLegales = Pelican_Cache::fetch("Frontend/Page", array(
                $zone['ZONE_TITRE7'],
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                Pelican::getPreviewVersion()
            ));
        }
        if ($zone['MEDIA_ID4'] != '') {
            $sVisuelML = Pelican::$config['MEDIA_HTTP'] . Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($zone['MEDIA_ID4']), Pelican::$config['MEDIA_FORMAT_ID']['ACTUALITES_PETIT']);
        }
        // Vérification arrivée depuis un CTA perso
        if (isset($aData['formOrigin']) && $aData['formOrigin'] == "ctaperso") {
            $this->assign('formOriginCtaPerso', true);
        }
        $this->assign('aMentionsLegales', $aMentionsLegales);
        $this->assign('sVisuelML', $sVisuelML);
        $this->assign("formTypeFull", $formTypeFull[$formulaire['FORM_TYPE_ID']]);
        $zone['ZONE_TEXTE2'] = str_replace(array('#civility#', '#firstname#', '#name#', '#email#', '#carlib#', '#lcdv#', '#address#'), array($civility, $aData['params']['firstname'], $aData['params']['name'], $aData['params']['email'], '', $aData['params']['lcdv'], $address), $zone['ZONE_TEXTE2']);
        $this->assign("aZone", $zone);
        $this->assign("parcours", $context . '/' . $parcours);
        $this->assign("aGtm", $aGtm);
        $this->fetch();

    }
}

