<?php

namespace PsaNdp\MappingBundle\Object;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Class SiteMap
 */
class SiteMap extends Content

{
    /**
     * @var PsaPageZoneConfigurableInterface
     */
    protected $quickAccess;


    /**
     * @var array
     */
    protected $mapping = array(
        'subrub' => 'child',
        'list'   => 'child',
    );

    /**
     * @var Collection
     */
    protected $child;

    /**
     * @var int
     */
    protected $directOpen;

    /**
     * @var int
     */
    protected $templateId;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->child = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * @param Collection $children
     */
    public function setChild($children)
    {
        foreach ($children as $child) {
            $this->child->add($child);
        }
    }

    /**
     * @return SiteMap
     */
    public static function createSiteMap()
    {
        $siteMap = new SiteMap();

        return $siteMap;
    }

    /**
     * @param mixed $quickAccess
     */
    public function setQuickAccess($quickAccess)
    {
        $this->quickAccess = $quickAccess;
    }

    /**
     * @return PsaPageZoneConfigurableInterface
     */
    public function getQuickAccess()
    {
        return $this->quickAccess;
    }

    /**
     * @return int
     */
    public function getDirectOpen()
    {
        return $this->directOpen;
    }

    /**
     * @param int $directOpen
     *
     * @return SiteMap
     */
    public function setDirectOpen($directOpen)
    {
        $this->directOpen = $directOpen;

        return $this;
    }

    /**
     * @return int
     */
    public function getTemplateId()
    {
        return $this->templateId;
    }

    /**
     * @param int $templateId
     *
     * @return SiteMap
     */
    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;

        return $this;
    }

}
