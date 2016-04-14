<?php namespace PsaNdp\MappingBundle\Object\Block;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\ModelVignette;
use PsaNdp\MappingBundle\Manager\PriceManager;
use PsaNdp\MappingBundle\Translation\TranslatorAwareTrait;

/**
 * Class Pf33CarCompatibility
 */
class Pf33CarCompatibility extends Content
{

    const PF33_DELIMITER = '#';
    const PF33_COMPORTEMENT_LIGHT = 'light';
    const PF33_COMPORTEMENT_FULL = 'full';
    const NDP_ACCORDING_FINISHING = 'NDP_ACCORDING_FINISHING'; //Selon finition
    const NDP_SERIES = 'NDP_SERIES'; //de série
    const NDP_OPTIONAL = 'NDP_OPTIONAL'; //en option
    const CONNECTED_SERVICES = 2;
    const BENEFICE = 1;

    use TranslatorAwareTrait;

    protected $overrideMapping = array(
        'accordion'=>'carousel',
    );

    /**
     * 
     *
     * @var string $urlJson
     */
    protected $urlJson;

    /**
     *
     * @var array $legend 
     */
    protected $legend;

    /**
     *
     * @var array $datalayer 
     */
    protected $datalayer;

    /**
     * @var array $carousel
     */
    protected $carousel;

    /**
     * @return array
     */
    public function getCarousel()
    {
        return $this->carousel;
    }

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
        $this->legend = [];
    }

    /**
     * @param array $carousel
     *
     * @return Pf27CarPicker
     */
    public function setCarousel(array $carousel = array())
    {
        $collection = [];
        foreach ($carousel as $index => $modelData) {
            
            $modelSilh = new ModelVignette($this->ctaFactory, $this->priceManager);
            $modelSilh->setTranslator($this->translator, $this->domain, $this->locale);
            $modelSilh->setDataFromArray($modelData);
            $actions = $modelSilh->getActions();
            $modelSilh->setId($index);
            $modelSilh->setLink(array('url'=>$actions['discover']['url'], 'text'=>  $this->trans('NDP_DISCOVER_THE').' '.$modelSilh->getTitle()));
            $collection[] =$modelSilh;
            $this->addCtaList($modelSilh->getId(), $modelSilh->getTitle(), $actions['discover']['url']);
        }
        
        $this->carousel = $collection;

        return $this;
    }


    public function getPriceManager()
    {
        return $this->priceManager;
    }

    public function addCtaList($id, $title, $url)
    {
        $this->ctaList[$id] = [
            'style' => 'cta',
            'version' => '4',
            'text' => $title,
            'url' =>  $url,
            'title' =>  $this->trans('NDP_DISCOVER_THE').' '.$title
        ];
    }

    public function setPriceManager(PriceManager $priceManager)
    {
        $this->priceManager = $priceManager;
    }

    /**
     * 
     * @return array
     */
    public function getLegend()
    {
        return $this->legend;
    }

    /**
     * 
     * @param array $legend
     * @return \PsaNdp\MappingBundle\Object\Block\Pf33CarCompatibility
     */
    public function setLegend(array $legend = [])
    {
        foreach ($legend as $css => $label) {
            $this->legend[] = [
                'label' => $label,
                'class' => $css
            ];
        }

        return $this;
    }

    /**
     * @param string $urlJson
     * @return Pf33CarCompatibility
     */
    public function setUrlJson($urlJson)
    {
        $this->urlJson = $urlJson;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlJson()
    {
        return $this->urlJson;
    }
}
