<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class Pf25FilterCarSelectorDataSource
 */
class Pf25FilterCarSelectorDataSource extends AbstractDataSource
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param ReadBlockInterface $block
     * @param Request        $request  Current url request displaying th block
     * @param bool           $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        // Get data
        $data['block'] = $block;
        $data['blockId'] = $request->get('blockPermanentId');
        $data['urlJson'] = $this->router->generate('psa_ndp_api_car_selector',array(
                'siteId'=>$this->getBlock()->getPage()->getSite()->getId(),
                'languageCode'=>$this->getBlock()->getLanguage()->getLangueCode(),
                'blockId'=> $request->get('blockPermanentId'),
                'pageVersion'=>$this->getBlock()->getPage()->getVersion()->getPageVersion()
            ),
            false
        );

        return $data;
    }



}
