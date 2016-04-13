<?php
include_once( Pelican::$config['APPLICATION_LIBRARY'].'/Citroen/CTA.php');

class Citroen_CTA_Expand extends \Citroen_CTA
{

    const TYPE = 'expand';
    const EXPAND_GTM_ACTION = 'Showroom';
    const EXPAND_GTM_CONFIG = 'Configurator';
    const EXPAND_GTM_CATEGORY = 'ExpandBar::NewCar';

    public $form;

    public function __construct($data)
    {
        parent::__construct($data);

        $this->type = self::TYPE;

        $this->index = $data['INDEX'];
        $this->url_web = $data['VEHICULE_CTA_EXPAND_URL'];
        $this->title = $data['VEHICULE_CTA_EXPAND_LABEL'];
        $this->id = $data['VEHICULE_CTA_EXPAND_OUTIL'];
        $this->setIsMobile();
        
        if(isset($data['EXPAND_GTM_ACTION'])){
            $this->eventAction = $data['EXPAND_GTM_ACTION'];
        }else{
           $this->eventAction = $this->getReferentielOutils()->getEventAction();
        }
        if(empty($this->eventAction)){
				if(strpos($this->url_web,self::EXPAND_GTM_CONFIG)!==false){
					$this->eventAction = self::EXPAND_GTM_CONFIG;
				}else{
					$this->eventAction = self::EXPAND_GTM_ACTION;
				}
        }
		
        if (isset($data['POST_EXPAND_GTM_ACTION'])) {
            $this->eventAction .=$data['POST_EXPAND_GTM_ACTION'];
        }
        $this->eventCategory = (!isset($data['EXPAND_GTM_CATEGORY']) ? self::EXPAND_GTM_CATEGORY : $data['EXPAND_GTM_CATEGORY']);
        $this->mode_ouverture = $data['VEHICULE_CTA_EXPAND_VALUE'];
		if(isset($data['CTA_GENERAL'])){
			$this->cta_general = $data['CTA_GENERAL'];
		}
		
    }
    
    
      public function getValidUrl(){   
        if($this->isDeployableBloc()){
            $this->url_web = $this->url_mobile = $url = Frontoffice_Zone_Helper::setUrlQueryString($this->vehicule['PAGE_CLEAR_URL'], array('deployable_id' => $this->id));
        }
        
        return parent::getValidUrl();
      }
}
