<?php
use Citroen\GammeFinition\VehiculeGamme;

class Layout_Citroen_CTA_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {

        $cta = null;
        $aParams = $this->getParams();
        if (!isset($aParams['SITE_ID'])) {
            $aParams['SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        }


        if (isset($aParams['CTA'])) {
            if($aParams['CTA']['TYPE'] == \Citroen_CTA_Expand::TYPE){
                   $cta = new \Citroen_CTA_Expand($aParams['CTA']);
            }else{
                $cta = new \Citroen_CTA_Toolbar($aParams['CTA']);
            }

            $cta->setContext('ZONE', Pelican::$config['ZONE_CODE'][$aParams['ZONE_ID']]);
            $cta->setContext('PAGE', Pelican::$config['TEMPLATE_PAGE_CODE'][$aParams['TEMPLATE_PAGE_ID']]);
        }

        if ($cta) {
            $cta->setIsMobile($this->isMobile());
            if (isset($aParams['vehicule'])) {
                $cta->setVehicule($aParams['vehicule']);
            } else {
                if (isset($aParams['PAGE_VEHICULE']) && !empty($aParams['PAGE_VEHICULE'])  ) {
                    $aVehicule = Pelican_Cache::fetch("Frontend/Citroen/VehiculeById", array(
                            $aParams['PAGE_VEHICULE'],
                            $aParams['SITE_ID'],
                            $_SESSION[APP]['LANGUE_ID']
                    ));
                    $lcdvGamme = VehiculeGamme::getLCDV6Gamme($aParams['PAGE_VEHICULE'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
                    $aVehicule = array_merge($aVehicule, $lcdvGamme);
                    if(isset($aParams['VEHICULE_URL'])){
                        $aVehicule['PAGE_CLEAR_URL'] = $aParams['VEHICULE_URL'];
                    }
                    $cta->setVehicule($aVehicule);
                }
            }

            $aConfiguration = Pelican_Cache::fetch("Frontend/Citroen/Configuration", array(
                    $aParams['SITE_ID'],
                    $_SESSION[APP]['LANGUE_ID'],
                    Pelican::getPreviewVersion()
            ));
            $cta->setConf($aConfiguration);



            $this->assign('cta', $cta);
            $this->assign('aData', $aParams);
        }

        $this->fetch();
    }
}
