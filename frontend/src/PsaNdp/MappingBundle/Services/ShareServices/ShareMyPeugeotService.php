<?php

namespace PsaNdp\MappingBundle\Services\ShareServices;

use PSA\MigrationBundle\Entity\Page\PsaPage;
use PsaNdp\MappingBundle\Manager\BlockManager;
use PsaNdp\MappingBundle\Translation\TranslatorAwareTrait;

/**
 * Class ShareMyPeugeotService
 */
class ShareMyPeugeotService
{
    const PT22_MY_PEUGEOT = '832';

    use TranslatorAwareTrait;

    /**
     * @var BlockManager
     */
    protected $blockManager;

    /**
     * @var array
     */
    protected $myPeugeot = array();

    /**
     * @param BlockManager $blockManager

     */
    public function __construct(BlockManager $blockManager) {

        $this->blockManager = $blockManager;
    }

    /**
     * @param PsaPage $node
     *
     * @return array
     */
    public function getMyPeugeot(PsaPage $node)
    {
        $key = $node->getPageId().'_'.$node->getLanguage();
        if (!isset($this->myPeugeot[$key])) {
            $this->myPeugeot[$key] = [];
            $block = $this->blockManager->getAdminBlockByNodeAndZoneId($node, self::PT22_MY_PEUGEOT);
            if ($block !== null && $block->getZoneParameters() > 0) {
                $this->myPeugeot[$key]['url'] = $block->getZoneUrl();
                $this->myPeugeot[$key]['label'] = $this->trans('NDP_FO_MY_PEUGEOT');
            }
        }

        return  $this->myPeugeot[$key];
    }
}
