<?php

class Citroen_CTA
{

    public $type;
    public $id;
    protected $isMobile;
    protected $url_web;
    protected $url_mobile;
    public $context = array();
    protected $href;
    public $title;
    public $target;
    public $form_data;
    public $addCss;
    public $eventAction; // gtm Action
    public $vehicule;
    public $configuration;
    public $defaultColor = '';
    public $noSpan = false;
    protected $site_id;
    protected $langue_id;
    protected $typeRefOutil;

    public function __construct($params)
    {
        if (isset($params['MORE_URL_PARAMETERS'])) {
            $this->addToUrl = $params['MORE_URL_PARAMETERS'];
        }
        if (isset($params['COLOR']) && !empty($params['COLOR'])) {
            $this->defaultColor = $params['COLOR'];
        }
        if (isset($params['NO_SPAN']) && $params['NO_SPAN']) {
            $this->noSpan = true;
        }
        $this->site_id = $_SESSION[APP]['SITE_ID'];
        if (isset($params['SITE_ID']) && $params['SITE_ID']) {
            $this->site_id = $params['SITE_ID'];
        }
        $this->langue_id = $_SESSION[APP]['LANGUE_ID'];
        if (isset($params['LANGUE_ID']) && $params['LANGUE_ID']) {
            $this->langue_id = $params['LANGUE_ID'];
        }
        $this->perso = $params['PERSO'];
        $this->addCss = $params['ADD_CSS'];
		if(isset($params['ADD_CTT'])){
		$this->addCtt = $params['ADD_CTT'];
		}
    }

    public function setContext($context_key, $context_value)
    {
        $this->context[$context_key] = $context_value;
    }

    public function get($attr)
    {
        if (isset($this->{$attr}))
            return $this->{$attr};
    }

    public function setIsMobile($m = true)
    {
        $this->isMobile = $m;
        $this->href = ( ( $m && !empty($this->url_mobile) ) ? $this->url_mobile : $this->url_web);
    }

    public function setVehicule($v)
    {
        $this->vehicule = $v;
    }

    public function setConf($c)
    {
        $this->configuration = $c;
    }

    private function isConfigurateurUrl()
    {
        return (preg_match('~(##URL_CONFIGURATEUR##|##URL_CONFIGURATEUR_PRO##)~i', $this->href));
    }

    private function getRealConfigurateurUrl()
    {
        return \Citroen\Configurateur::getConfigurateurUrl($this->vehicule, $this->configuration, $this->isMobile);
    }

    public function getValidUrl()
    {

        if ($this->isConfigurateurUrl()) {
            $url = $this->getRealConfigurateurUrl();
        } else {
            $tags = array(
                '#LCVD#' => $this->vehicule['LCDV6'],
                '##LCDV_CURRENT##' => $this->vehicule['LCDV6']
            );

            $url = \Citroen\Html\Util::replaceTagsInUrl($this->href, $tags, false, $this->addToUrl);
            if ($this->perso && $this->mode_ouverture == 3) {
                $url = Frontoffice_Zone_Helper::setUrlQueryString($url, array('origin' => 'ctaperso'));
            }
        }
      

         if (Pelican_Controller::isMobile() && ((isset($_GET['ppid']) && intval($_GET['ppid']) > 0) || (isset($_GET['pid']) && intval($_GET['pid']) > 0))) {
			 
			$iPpid = $_GET['ppid'];
			if(empty($iPpid)){
				$iPpid = $_GET['pid'];
			}
			
            $aAddParam = array('ppid' => $iPpid);
            if (strpos($url, '?') !== false) {
                $url = $url.'&'.http_build_query($aAddParam);
            } else {
                $url = $url.'?'.http_build_query($aAddParam);
            }
        }
        return $url;
    }

    function getTypeRefOutil()
    {
        return $this->typeRefOutil;
    }

    public function setTypeRefOutil($type_refOutil)
    {
        $this->typeRefOutil = $type_refOutil;

        return $this;
    }

    public function getReferentielOutils()
    {
        if ($this->isConfigurateurUrl()) {
            $typeRefOutils = 'configurator';
        } else {
            $typeRefOutils = Pelican_Cache::fetch("Frontend/Citroen/CTA/ReferentielOutils", array($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], $this->isMobile ? "mobile" : "web", $this->id));
        }
        $this->setTypeRefOutil($typeRefOutils);

        return $this;
    }

    public function isDeployableBloc()
    {
        return $this->mode_ouverture == 3;
    }

    public function getEventAction()
    {
        return Pelican::$config['TYPE_TOOLBAR_GTM_ACTION'][$this->getTypeRefOutil()];
    }
}
