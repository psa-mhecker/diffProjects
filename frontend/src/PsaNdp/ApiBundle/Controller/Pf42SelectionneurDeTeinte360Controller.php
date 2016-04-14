<?php

namespace PsaNdp\ApiBundle\Controller;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use OpenOrchestra\BaseApiBundle\Controller\Annotation as Api;

/**
 * Class Pf42SelectionneurDeTeinte360Controller
 *
 * @Config\Route("color_picker")
 */
class Pf42SelectionneurDeTeinte360Controller extends Controller
{
    /**
     * @Config\Route("/{siteId}/{version}", requirements={"siteId" = "\d+"}, name="psa_ndp_api_color_picker")
     * @Config\Method({"GET"})
     *
     * @Api\Serialize()
     *
     * @throw RuntimeException
     *
     * @return FacadeInterface
     */
    public function colorPickerAction($siteId, $version)
    {
        // ordonnée les visuels BO (pas encore fait en BO, affiché toutes les vues).
         return $this->container->get('open_orchestra_api.transformer_manager')->get('color_picker')->transform(
                array('siteId' => $siteId, 'version' => $version)
         );
    }
}
