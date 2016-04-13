<?php
include_once( Pelican::$config['APPLICATION_LIBRARY'].'/Citroen/CTA.php');

class Citroen_CTA_Toolbar extends \Citroen_CTA
{

    public $form;
    public $type_outil;
    public $index; // index de positionnement du CTA outil dans la toolbar

    public function __construct($data)
    {
        parent::__construct($data);
		
		

        $this->type = 'toolbar';

        $this->index = $data['INDEX'];

        $this->url_mobile = !empty($data['BARRE_OUTILS_URL_MOBILE']) ? $data['BARRE_OUTILS_URL_MOBILE'] : $data['BARRE_OUTILS_URL_MOBILE2'];
        $this->url_web = $data['BARRE_OUTILS_URL_WEB'];
        $this->form = $data['BARRE_OUTILS_FORMULAIRE'];
        $this->title = $data['BARRE_OUTILS_TITRE'];
		$this->title_court = $data['BARRE_OUTILS_TITRE_COURT'];
        $this->id = $data['BARRE_OUTILS_ID'];
        $this->media_generique = (!empty($data['MEDIA_GENERIQUE_OFF']) ? $data['MEDIA_GENERIQUE_OFF'] : $data['MEDIA_GENERIQUE_ON']);
		$this->media_generique_on =  (intval($data['MEDIA_GENERIQUE_ON'])>0) ? Pelican::$config['MEDIA_HTTP'].Pelican_Media::getMediaPath($data['MEDIA_GENERIQUE_ON']):'';
        $this->media_ds = (!empty($data['MEDIA_DS_OFF']) ? $data['MEDIA_DS_OFF'] : $data['MEDIA_DS_ON']);
        $this->picto_off = $data['picto']['off'];
        $this->picto_on = $data['picto']['on'];
        $this->picto_new_shoroom = $data['picto']['new_show'];
        $this->eventAction = Pelican::$config['TYPE_TOOLBAR_GTM_ACTION'][$data['BARRE_OUTILS_FORMULAIRE']];
        $this->mode_ouverture = $data['BARRE_OUTILS_MODE_OUVERTURE'];
        $this->service = $data['services'];
		if(isset($data['CTA_GENERAL'])){
		$this->cta_general = $data['CTA_GENERAL'];
		}
		
		
		
        if (is_array($data['services']) && sizeof($data['services']) > 0) {
            $this->serviceAllowed = implode(',', $data['services']);
        }

        if ($this->isDeployableBloc()) {
            $this->addDeployableBloc($this->form);
            if ($this->isPDVTool()) {
                $this->generateBloc();
            } else {
                if (!$this->isMobile) {
                    $this->getFormData();
                }
            }
        }
		
		
    }

    private function isPDVTool()
    {
        $aTab = explode('_', $this->form);
        return ($aTab[0] == 'PDV');
    }

    private function addDeployableBloc($deployable)
    {

        Pelican::$config['DEPLOYABLE'] ++;
        if ($this->isPDVTool()) {
            $deployable = 'PDV';
        }
        Pelican::$config['DEPLOYABLE_BLOC'][Pelican::$config['DEPLOYABLE']] = $deployable;
        Pelican::$config['DEPLOYABLE_BLOC_TOOL_ID'][Pelican::$config['DEPLOYABLE']] = $this->id;
    }

    private function generateBloc()
    {
        $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                Pelican::getPreviewVersion(),
                Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
        ));
        $aPdv = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                $pageGlobal['PAGE_ID'],
                Pelican::$config['ZONE_TEMPLATE_ID']['RECHERCHE_PDV'],
                $pageGlobal['PAGE_VERSION'],
                $_SESSION[APP]['LANGUE_ID']
        ));
        $aOutilsPdv = Pelican_Cache::fetch("Frontend/Citroen/VehiculeOutil", array(
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                $aPdv['ZONE_TOOL'],
                'WEB'
        ));
        if (is_array($aOutilsPdv)) {
            foreach ($aOutilsPdv as $aOutilPdv) {
                if ($aOutilPdv['BARRE_OUTILS_MODE_OUVERTURE'] == 3) {
                    $this->addDeployableBloc($aOutilPdv['BARRE_OUTILS_FORMULAIRE']);
                }
            }
        }
    }

    private function getFormData()
    {
        $aTab = explode('_', $this->form);
        $aPageFormulaire = Pelican_Cache::fetch("Frontend/Page", array(
                $aTab[0],
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                Pelican::getPreviewVersion()
        ));
        $aFormulaire = Pelican_Cache::fetch("Frontend/Page/ZoneInGabaritBlanc", array(
                $aPageFormulaire['PAGE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                Pelican::$config['AREA']['DYNAMIQUE'],
                Pelican::$config['ZONE']['FORMULAIRE'],
                $aPageFormulaire['PAGE_VERSION']
        ));
        $aFormTypes = Pelican_Cache::fetch('Frontend/Citroen/FormType', array(true));
        if (isset($aFormulaire[0]) && !empty($aFormulaire[0])) {
            $sJsonFormData = htmlspecialchars(
                json_encode(
                    array(
                        'form_type' => $aFormulaire[0]['ZONE_TITRE3'],
                        'form_gtm' => $aFormTypes[$aFormulaire[0]['ZONE_TITRE3']]
                    )
                ), ENT_QUOTES, 'UTF-8');
            $this->form_data = $sJsonFormData;
        }
    }

    public function getValidUrl()
    {


        if ($this->isDeployableBloc()) {
            if ($this->isMobile) {
                if ($this->isPDVTool()) {
                    $aPdv = Pelican_Cache::fetch("Frontend/Citroen/PageByMultiZone", array(
                            $_SESSION[APP]['SITE_ID'],
                            $_SESSION[APP]['LANGUE_ID'],
                            Pelican::getPreviewVersion(),
                            Pelican::$config['ZONE']['POINT_DE_VENTE'],
                            Pelican::$config['TEMPLATE_PAGE']['GABARIT_BLANC']
                    ));
                    if (!empty($aPdv)) {
                        $url = $aPdv['PAGE_CLEAR_URL'];
                    }
                } else {
                    $aTab = explode('_', $this->form);
                    $aFormulaire = Pelican_Cache::fetch("Frontend/Page", array(
                            $aTab[0],
                            $_SESSION[APP]['SITE_ID'],
                            $_SESSION[APP]['LANGUE_ID'],
                            Pelican::getPreviewVersion()
                    ));
                    $complement = '';
                    if (!empty($aFormulaire) && !empty($this->vehicule['LCDV6'])) {
                        $complement = '?lcdv='.$this->vehicule['LCDV6'];
                    }
                    $url = $aFormulaire['PAGE_CLEAR_URL'].$complement;
                }
                if ($this->perso && $this->mode_ouverture == 3 && !$this->isConfigurateurUrl()) {
                    $url = Frontoffice_Zone_Helper::setUrlQueryString($url, array('origin' => 'ctaperso'));
                }

                if (isset($_GET['pid']) && intval($_GET['pid']) > 0) {
                    $aAddParam = array('ppid' => $_GET['pid']);
                    if (strpos($url, '?') !== false) {
                        $url = $url.'&'.http_build_query($aAddParam);
                    } else {
                        $url = $url.'?'.http_build_query($aAddParam);
                    }
                }
            } else {
                $url = '#deployable_'.Pelican::$config['DEPLOYABLE'];
                if ($this->context['ZONE'] == 'POINT_DE_VENTE' || $this->isPDVTool()) {
                    $this->url_web_deploy = 'FormulaireDeploy'.Pelican::$config['DEPLOYABLE'];
                }
            }
        } else {
            $url = parent::getValidUrl();
        }

        return $url;
    }
}
