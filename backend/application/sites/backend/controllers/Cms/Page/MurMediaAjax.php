<?php
/**
 * Class Cms_Page_MurMediaAjax_Controller
 * Classe gerant l'ajax du mur media
 *
 */
use PsaNdp\MappingBundle\Object\Block\Pc23Object\StructureManager;

require_once dirname(__FILE__).'/../Page.php';
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Cms/Page/Ndp.php';
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Cms/Page/Ndp/Pc23MurMedia.php';


pelican_import('Controller.Back');

class Cms_Page_MurMediaAjax_Controller extends Cms_Page_Controller
{

    public function searchMediasAction()   {
        $structureManager = new StructureManager();
        $params = $this->getParams();
        $this->zoneValues = $params['zoneValues'];
        $this->multi = $params['multi'];
        $isZoneDynamique = $params['isZoneDynamique'];
        $medias = Cms_Page_Ndp_Pc23MurMedia::getMedias($this,  $this->zoneValues['AREA_ID']);
        $data = array(
            'medias'=>$medias,
            'structuresValues'=>$structureManager->autoFill($medias),
            'multi' => $this->multi
        )
        ;
        if($this->zoneValues['ZONE_ATTRIBUT'] == $this->zoneValues['ZONE_ATTRIBUT_ORIGINAL'] ) {
            $data['structuresValues'] = Cms_Page_Ndp_Pc23MurMedia::getStructures($this, $isZoneDynamique, $medias);
        }
        ;

        echo json_encode($data);
    }
}
