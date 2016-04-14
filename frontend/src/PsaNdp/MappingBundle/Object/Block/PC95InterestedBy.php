<?php
namespace PsaNdp\MappingBundle\Object\Block;

use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\ModelSilouhetteVignette;
use PsaNdp\MappingBundle\Manager\PriceManager;
use PsaNdp\MappingBundle\Translation\TranslatorAwareTrait;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;

/**
 * Class PC95InterestedBy
 * @package PsaNdp\MappingBundle\Object\Block
 */
class PC95InterestedBy extends Content
{
    use TranslatorAwareTrait;

    /**
     * @var array
     */
    protected $overrideMapping = array(
        'rangebarTitle'=>'title',
        'datalayer'=>'dataLayer',
    );

    /**
     * @var array
     */
    protected $models;

    /**
     * @var string
     */
    protected $rangebarMode =  'full';

    /**
     * @var array
     */
    protected $rangbarItems;

    /**
     * @var array
     */
    protected $noticeText;

    /**
     * @var array
     */
    protected $list = [];

    /**
     * @var
     */
    protected $version;

    /**
     * @var PriceManager
     */
    protected $priceManager;

    /**
     * @param CtaFactory $ctaFactory
     * @param PriceManager $priceManager
     */
    public function __construct(CtaFactory $ctaFactory, PriceManager $priceManager)
    {
        parent::__construct();
        $this->ctaFactory = $ctaFactory;
        $this->priceManager = $priceManager;
    }
    /**
     * @return string
     */
    public function getRangebarMode()
    {
        return $this->rangebarMode;
    }

    /**
     * @param string $rangebarMode
     *
     * @return PC95InterestedBy
     */
    public function setRangebarMode($rangebarMode)
    {
        $this->rangebarMode = $rangebarMode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNoticeText()
    {
        return $this->noticeText;
    }

    /**
     * @param mixed $noticeText
     *
     * @return PC95InterestedBy
     */
    public function setNoticeText($noticeText)
    {
        $this->noticeText = $noticeText;

        return $this;
    }

    protected function getCollectionModel() {

        $collection = [];
        $cashPriceLegalMentionNotice = '';
        $mentionMonthlyPrice = '';

        foreach($this->models as $modelData)
        {
            $model =new ModelSilouhetteVignette($this->ctaFactory, $this->priceManager);
            $model->setTranslator($this->translator, $this->domain, $this->locale);
            $model->setSiteId($this->getSiteId());
            $model->setDataFromArray($modelData);
            $collection[] = $model;

            // Can cash price mention legal to display if necessary
            $mentionMonthlyPrice = '';
            if ($model->getPriceManager()->getSfgStatus($this->getSiteId())) {
                $mentionMonthlyPrice = $model->getPriceManager()->getLegalNoticeByMonth();
            }

            $mentionCashPrice = $model->getPriceManager()->getLegalNoticeCashPrice();
            if ($mentionCashPrice !== null) {
                $cashPriceLegalMentionNotice = $mentionCashPrice;
            }
        }

        // Set notice for cash price legal mention
        $this->setNoticeText(
            array(
                'title' => $mentionMonthlyPrice,
                'text'  => $cashPriceLegalMentionNotice,
            )
        );

        return $collection;

    }

    protected function getModel()
    {

        return array(
            'name' =>  $this->version ? ($this->version->Model->label) : '',
            'collectionModel' => $this->getCollectionModel(),
            "noticeText" => $this->getNoticeText(),
        );
    }

    /**
     * @return array
     */
    public function getRangbarItems()
    {
        $item = array(
            'model' => $this->getModel(),
        );

        return array($item);
    }

    /**
     * @param array $rangbarItems
     *
     * @return PC95InterestedBy
     */
    public function setRangbarItems($rangbarItems)
    {
        $this->rangbarItems = $rangbarItems;

        return $this;
    }

    /**
     * @return array
     */
    public function getList()
    {

        return array(
            'items'=>$this->getCollectionModel(),
        );
    }

    /**
     * @param array $list
     *
     * @return PC95InterestedBy
     */
    public function setList($list)
    {
        $this->list = $list;

        return $this;
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
     * @return PC95InterestedBy
     */
    public function setPriceManager($priceManager)
    {
        $this->priceManager = $priceManager;

        return $this;
    }


}
