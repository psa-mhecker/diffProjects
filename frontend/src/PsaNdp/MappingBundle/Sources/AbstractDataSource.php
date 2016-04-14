<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Translation\TranslatorAwareTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * Abstract Data source.
 */
class AbstractDataSource implements DataSourceInterface
{
    use TranslatorAwareTrait;
    /**
     * @var ReadBlockInterface
     */
    private $block;

    /**
     * @var ReadBlockInterface
     */
    protected $realBlock;

    /**
     * @var string
     */
    private $mediaServer;

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request.
     *
     * @param ReadBlockInterface $block
     * @param Request            $request  Current url request displaying th block
     * @param bool               $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        /* @var PsaPageZoneConfigurableInterface $block */
        $data['block'] = $block;

        return $data;
    }

    /**
     * @param ReadBlockInterface $block
     *
     * @return $this
     */
    public function setBlock(ReadBlockInterface $block)
    {
        $this->block = $block;

        return $this;
    }

    /**
     * @return PsaPageZoneConfigurableInterface
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param string $mediaServer
     *
     * @return $this
     */
    public function setMediaServer($mediaServer)
    {
        $this->mediaServer = $mediaServer;

        return $this;
    }

    /**
     * @return string
     */
    public function getMediaServer()
    {
        return $this->mediaServer;
    }

    /**
     * @return PsaPage
     */
    public function getPage()
    {
        return $this->block->getPage();
    }

    /**
     * @return ReadBlockInterface
     */
    public function getRealBlock()
    {
        return $this->realBlock;
    }

    /**
     * @param ReadBlockInterface $realBlock
     * @return AbstractDataSource
     */
    public function setRealBlock($realBlock)
    {
        $this->realBlock = $realBlock;

        return $this;
    }
}
