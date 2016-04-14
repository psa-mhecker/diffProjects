<?php
/**
 * Class Cms_Page_Ajax_Controller
 *
 * Classe gerant l'ajax en general du BO :)
 */
include_once dirname(__FILE__).'/../Page.php';
include_once pelican_path('Form');
require_once pelican_path('Text.Utf8');
include_once Pelican::$config['APPLICATION_VIEW_HELPERS'].'/Form.php';
include_once Pelican::$config['APPLICATION_VIEW_HELPERS'].'/Button.php';
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Cms/Page/Ndp.php';
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Cms/Page/Ndp/Pf42SelectionneurDeTeinte360.php';

class Cms_Page_Ajax_Controller extends Cms_Page_Controller
{
    /*
     * Ajax de génération du multi du mur media
     */
    public function searchVersionsByModeleRegroupementSilhouetteAction()
    {
        $params = $this->getParams();

        $this->zoneValues['MODELE'] = $params['mrs'];
        $versions = Cms_Page_Ndp_Pf42SelectionneurDeTeinte360::getListVersionVehicule($this);

        echo json_encode($versions);
    }

    public function generateGroupingCompatibilityArrayAction()
    {
        $params = $this->getParams();

        $form = Pelican_Request::call('_/Ndp_ServFinitionConnectedGrouping/', $params);
        
        echo json_encode($form);
    }
}
