<?php
namespace PsaNdp\MappingBundle\Object\Block;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\MenuItem;
use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Pn14NavigationConfishow
 * @package PsaNdp\MappingBundle\Object\Block
 */
class Pn14NavigationConfishow extends Content
{

    /**
     * @var array
     */
    protected  $menu;

    /**
     * @var
     */
    protected $titleMobile;


    /**
     * @var bool
     */
    protected $isMobile;

    /**
     * @var string
     */
    protected $color;

    /**
    * @param PsaPageZoneConfigurableInterface $block
     *
     * @return $this
    */
    public function setBlock(PsaPageZoneConfigurableInterface $block)
    {
        $this->block = $block;

        if ($block->getZoneParameters()) {
            $this->title = $block->getPage()->getVersion()->getPageTitle();
        }
        $chapo = $block->getPage()->getVersion()->getPageText();
        if (!empty($chapo)) {
            $this['text'] = $chapo;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     *
     * @return Pn14NavigationConfishow
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return array
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param array $items
     *
     * @return $this
     */
    public function setMenu($items)
    {
        $this->menu = [];
        foreach($items as $item ) {
            $menuItem = new MenuItem();
            $menuItem->setDataFromArray($item);
            $this->menu[] = $menuItem;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitleMobile()
    {
        return $this->titleMobile;
    }

    /**
     * @param mixed $titleMobile
     *
     * @return Pn14NavigationConfishow
     */
    public function setTitleMobile($titleMobile)
    {
        $this->titleMobile = $titleMobile;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsMobile()
    {
        return $this->isMobile;
    }

    /**
     * @param boolean $isMobile
     *
     * @return $this
     */
    public function setIsMobile($isMobile)
    {
        $this->isMobile = $isMobile;

        return $this;
    }
}

