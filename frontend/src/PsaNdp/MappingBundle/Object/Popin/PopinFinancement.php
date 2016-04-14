<?php

namespace PsaNdp\MappingBundle\Object\Popin;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Object\Media;
use PsaNdp\MappingBundle\Object\PriceList;
use PsaNdp\MappingBundle\Manager\PriceManager;

/**
 * Class PopinFinancement
 */
class PopinFinancement extends Content
{

    /**
     * @var PriceManager
     */
    protected $priceManager;

    /**
     * @var array
     */
    protected $sfg;

    /**
     * @var string
     */
    protected $symbol;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var Collection $priceList
     */
    protected $priceList;

    /**
     * @var array $asterix
     */
    protected $asterix = array(array('text' => ''));

    /**
     * @var string $mention
     */
    protected $mention;

    /**
     * @var Media $img
     */
    protected $img;

    /**
     * @var string $id
     */
    protected $id;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->priceList = new ArrayCollection();
        $this->mediaFactory = new MediaFactory();
        $this->img = $this->mediaFactory->createMedia();
    }

    /**
     * @return PriceManager
     */
    public function getPriceManager()
    {
        return $this->priceManager;
    }

    /**
     * @param PriceManager $priceManager
     *
     * @return PopinFinancement
     */
    public function setPriceManager($priceManager)
    {
        $this->priceManager = $priceManager;

        return $this;
    }

    /**
     * @return array
     */
    public function getAsterix()
    {
        $return = array(array('text' => ''));
        if (!empty($this->sfg)) {
            $return = array(array('text' => $this->symbol. $this->sfg[PriceManager::SFG_GENERAL_LEGAL_TEXT]));
        }

        return $return;
    }

    /**
     * @param array $asterix
     *
     * @return $this
     */
    public function setAsterix($asterix)
    {
        $this->asterix = $asterix;

        return $this;
    }

    /**
     * @return string
     */
    public function getMention()
    {
        $return = '';
        if (!empty($this->sfg)) {
            $return = $this->sfg[PriceManager::SFG_FINANCEMENT_DETAILS][PriceManager::FINANCEMENT_DETAILS_TEXT_LEGAL_TEXT];
        }

        return $return;
    }

    /**
     * @param string $mention
     *
     * @return $this
     */
    public function setMention($mention)
    {
        $this->mention = $mention;

        return $this;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $model
     *
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPriceList()
    {
        $return = array();

        if (!empty($this->sfg)) {
            $return = array(
                // Options
                array(
                    'title' => $this->sfg[PriceManager::SFG_FINANCEMENT_DETAILS][PriceManager::FINANCEMENT_DETAILS_TEXT_HEADER],
                    'price' => $this->formatFinancementDetailsariables(
                        $this->sfg[PriceManager::SFG_FINANCEMENT_DETAILS][PriceManager::FINANCEMENT_DETAILS_VARIABLES_OPTIONS]
                    )
                ),
                // Insurance
                array(
                    'title' => $this->sfg[PriceManager::SFG_FINANCEMENT_DETAILS][PriceManager::FINANCEMENT_DETAILS_TEXT_INSURANCE_TITLE],
                    'price' => $this->formatFinancementDetailsariables(
                        $this->sfg[PriceManager::SFG_FINANCEMENT_DETAILS][PriceManager::FINANCEMENT_DETAILS_VARIABLES_INSURANCE]
                    )
                )
            );
        }

        return $return;
    }

    /**
     * @param array $variables
     *
     * @return array
     */
    private function formatFinancementDetailsariables($variables)
    {
        $result = [];

        if (is_array($variables)) {
            foreach ($variables as $detail) {
                $price = array(
                    'label' => $detail['label'],
                    'sum' => $detail['value'],
                    'devise' => $detail['unit']
                );

                $result[] = $price;
            }
        }

        return $result;
    }

    /**
     * @param Collection $priceList
     *
     * @return $this
     */
    public function setPriceList($priceList)
    {
        $this->priceList = $priceList;

        return $this;
    }

    /**
     * @param PriceList $priceList
     */
    public function addPriceList(PriceList $priceList)
    {
        $this->priceList->add($priceList);
    }

    /**
     * @return Media
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param array $img
     *
     * @return $this
     */
    public function setImg(array $img)
    {
        $this->img = $this->mediaFactory->createFromArray($img);

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
     }

    /**
     * PriceManager has been initialize correctly before calling this init
     *
     * @return $this
     */
    public function init()
    {
        $this->sfg = $this->getPriceManager()->getSfg();

        return $this;
    }

}
