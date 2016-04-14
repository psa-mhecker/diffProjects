<?php
namespace PsaNdp\MappingBundle\Object\Block;

use Doctrine\Common\Collections\ArrayCollection;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Cta;

/**
 * Class Pt20MasterPage
 */
class Pt20MasterPage extends Content
{

    /**
     * @var collection of psapage
     */
    protected $subPages;

    /**
     * @var Array collection of subpage
     */
    protected $childrenContents;

    /**
     * Pt20MasterPage constructor.
     */
    public function __construct()
    {
        $this->childrenContents = new ArrayCollection();
    }

    /**
     * Initialize data for template usage
     */
    public function initData()
    {
        $tmpChildrenContents = new ArrayCollection();

        foreach ($this->subPages as $subPage) {

            $subPageVersion = $subPage->getVersion();
            $urlToChildPage = $subPageVersion->getPageUrlExterne() ? $subPageVersion->getPageUrlExterne() : $subPageVersion->getPageClearUrl();

            $cta = new Cta();
            $cta->setType('TYPE_SIMPLELINK')
                ->setTitle($this->translate['NDP_READ_MORE'])
                ->setTarget($subPageVersion->getPageUrlExterneModeOuverture() == 1 ? '_self' : '_blank')
                ->setUrl($urlToChildPage);

            $content = new Content();
            $content->setTitle($subPageVersion->getPageTitle())
                    ->setSubtitle($subPageVersion->getPageMetaDesc())
                    ->setUrl($urlToChildPage)
                    ->setCtaList([$cta]);

            $tmpChildrenContents->add($content);

        }

        $this->childrenContents = $tmpChildrenContents;
    }

    /**
     * @return array Return the list of children of the page
     */
    public function getChildrenContents()
    {
        return $this->childrenContents;
    }

    /**
     * @param $subPages ArrayCollection of PSA page
     *
     * @return $this
     */
    public function setSubPage($subPages)
    {
        $this->subPages = $subPages;

        return $this;
    }

}
