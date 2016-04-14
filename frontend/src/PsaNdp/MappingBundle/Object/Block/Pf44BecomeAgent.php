<?php

namespace PsaNdp\MappingBundle\Object\Block;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\BlockTrait\AgentPointOfSaleSearchBasicConfigurationTrait;
use PsaNdp\MappingBundle\Object\BlockTrait\AgentPointOfSaleSearchTrait;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;

/**
 * Class Pf44BecomeAgent
 */
class Pf44BecomeAgent extends Content
{

    protected $mapping = array(
        'info' => 'information',
        'datalayer' => 'dataLayer'
    );

    use AgentPointOfSaleSearchBasicConfigurationTrait;
    use AgentPointOfSaleSearchTrait;

    /**
     * @var Content $information
     */
    protected $information;

    /**
     * @var string
     */
    protected $titleInformation;

    /**
     * @var string $formAction
     */
    protected $formAction = '';
    /**
     * @var string $aroundMe
     */
    protected $aroundMe;

    /**
     * @var Content $moreInformation
     */
    protected $moreInformation;

    /**
     * @var integer $length
     */
    protected $length;

    /**
     * @var string $placeholderInput
     */
    protected $placeholderInput;

    /**
     * @var array $visuMap
     */
    protected $visuMap;

    /**
     * @var string $pictoMap
     */
    protected $pictoMap;

    /**
     * @var string $beforeName
     */
    protected $beforeName;

    /**
     * @var array $linkMorInfo
     */
    protected $linkMorInfo;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->moreInformation = new Content();
        $this->information = new Content();
    }

    /**
     * @return string
     */
    public function getAroundMe()
    {
        return $this->aroundMe;
    }

    /**
     * @param string $aroundMe
     *
     * @return $this
     */
    public function setAroundMe($aroundMe)
    {
        $this->aroundMe = $aroundMe;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormAction()
    {
        return $this->formAction;
    }

    /**
     * @param string $formAction
     *
     * @return $this
     */
    public function setFormAction($formAction)
    {
        $this->formAction = $formAction;

        return $this;
    }


    /**
     * @return string
     */
    public function getTitleInformation()
    {
        return $this->information->getTitle();
    }

    /**
     * @param string $titleInformation
     */
    public function setTitleInformation($titleInformation)
    {
        $this->information->setTitle($titleInformation);
    }

    /**
     * @return Content
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * @param Content $information
     *
     * @return $this
     */
    public function setInformation($information)
    {
        $this->information = $information;

        return $this;
    }

    /**
     * @param array $listFilter
     * @param array $values
     * @param array $translations
     *
     * @return $this
     */
    public function initListFilter($listFilter, array $values, array $translations)
    {
        // Empty array by default
        $this->listFilter = null;
        $listFilterValue = [];

        // Get Filter Value
        if (null !== $listFilter) {
            $listFilterValue = explode('#', $listFilter);
        }
        foreach ($listFilterValue as $filter) {
            $this->listFilter[] = array(
                'value' => $values[$filter],
                'label' => $translations[$filter]
            );
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getResultFound()
    {
        return $this->resultFound;
    }

    /**
     * @param string $resultFound
     *
     * @return $this
     */
    public function setResultFound($resultFound)
    {
        $this->resultFound = $resultFound;

        return $this;
    }

    /**
     * @param PsaPageZoneConfigurableInterface $block
     *
     * @return $this
     */
    public function setBlock(PsaPageZoneConfigurableInterface $block)
    {
        $this->block = $block;
        $this->setRegroupement($block->getZoneAttribut() === 1);
        $this->setAutocompletion($block->getZoneAttribut2() === 1);
        $this->information->setSubtitle($block->getZoneTexte());

        // Display CTA if activated
        if ($block->getZoneTitre2()) {
            $this->initCtaListFromBlock(
                $block,
                [CtaFactory::OPTION_CLASS => 'more-info']
            );
        }

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
     * @param int $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param string $placeholderInput
     */
    public function setPlaceholderInput($placeholderInput)
    {
        $this->placeholderInput = $placeholderInput;
    }

    /**
     * @return string
     */
    public function getPlaceholderInput()
    {
        return $this->placeholderInput;
    }




    /**
     * @param array $visuMap
     */
    public function setVisuMap($visuMap)
    {
        $this->visuMap = $visuMap;
    }

    /**
     * @return array
     */
    public function getVisuMap()
    {
        return $this->visuMap;
    }

    /**
     * @param string $pictoMap
     */
    public function setPictoMap($pictoMap)
    {
        $this->pictoMap = $pictoMap;
    }

    /**
     * @return string
     */
    public function getPictoMap()
    {
        return $this->pictoMap;
    }

    /**
     * @param string $beforeName
     */
    public function setBeforeName($beforeName)
    {
        $this->beforeName = $beforeName;
    }

    /**
     * @return string
     */
    public function getBeforeName()
    {
        return $this->beforeName;
    }

    /**
     * @param array $linkMoreInfo
     */
    public function setLinkMoreInfo($linkMoreInfo)
    {
        $this->linkMoreInfo = $linkMoreInfo;
    }

    /**
     * @return array
     */
    public function getLinkMoreInfo()
    {
        return $this->linkMoreInfo;
    }

    /**
     * @param array $linkMorInfo
     */
    public function setLinkMorInfo($linkMorInfo)
    {
        $this->linkMorInfo = $linkMorInfo;
    }

    /**
     * @return array
     */
    public function getLinkMorInfo()
    {
        return $this->linkMorInfo;
    }


}
