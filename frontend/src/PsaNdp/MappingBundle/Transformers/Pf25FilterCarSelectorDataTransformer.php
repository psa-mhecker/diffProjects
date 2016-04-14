<?php
namespace PsaNdp\MappingBundle\Transformers;

use Itkg\Core\Route\Route;
use PsaNdp\MappingBundle\Object\Block\Pf25FilterCarSelector;
use Symfony\Component\Routing\RouterInterface;

/**
 * Data transformer for Pf25FilterCarSelector block
 * @todo Get Data from WS
 */
class Pf25FilterCarSelectorDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    const TYPE_AFFICHAGE_CATEGORIES = 1;
    const TYPE_AFFICHAGE_MODELES = 2;


    const NDP_FILTER_PRICE = 'configPrice';
    const NDP_FILTER_ENERGY = 'configEnergy';
    const NDP_FILTER_GEARBOX_TYPE = 'configSpeed';
    const NDP_FILTER_CONSO = 'configConsumption';
    const NDP_FILTER_CLASS = 'configEmission';
    const NDP_FILTER_SEAT_NB = 'configSeats';
    const NDP_FILTER_LENGTH = 'configLength';
    const NDP_FILTER_WIDTH = 'configWidth';
    const NDP_FILTER_HEIGHT = 'configHeight';
    const NDP_FILTER_VOLUME = 'configVolume';

    private $pf25FilterCarSelector;

    /**
     * @var array
     */
    protected $dataSource;

    /**
     * @var bool
     */
    protected $isMobile;

    /**
     * @var array
     */
    protected $result = [];

    /**
     * @param Pf25FilterCarSelector $pf25FilterCarSelector
     */
    public function __construct(Pf25FilterCarSelector $pf25FilterCarSelector)
    {
        $this->pf25FilterCarSelector = $pf25FilterCarSelector;
    }

    /**
     *  Fetching data slice Filter car selector (Pf25)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->dataSource = $dataSource;
        $this->isMobile   = $isMobile;
        $dataSource['translate'] = array(
            'results'      =>  $this->trans('NDP_SHOW_RESULTS'),
            'criterias'    => $this->trans('NDP_HIDE_MY_CRITERIA'),
            'errorload'    => $this->trans('NDP_AJAX_LOADING_ISSUE'),
            'noresult'     => $this->trans('NDP_NO_RESULTS_FOR_YOUR_SEARCH_PLEASE_CHANGE_CRITERIA'),
            'legalMention' => $this->trans('NDP_LEGAL_MENTION'),
            'models'       => $this->trans('NDP_AVAILABLE_RANGE_MODELS')
        );
        $this->pf25FilterCarSelector->setDataFromArray($dataSource);

        return array(
            'slicePF25' => $this->pf25FilterCarSelector
        );
    }

    private function genericTranslation()
    {
        $this->result['translate'] = array(
            'results' =>  $this->trans('NDP_SHOW_RESULTS'),
            'criterias' => $this->trans('NDP_HIDE_MY_CRITERIA'),
            'errorload'   => $this->trans('NDP_AJAX_LOADING_ISSUE'),
            'noresult'     => $this->trans('NDP_NO_RESULTS_FOR_YOUR_SEARCH_PLEASE_CHANGE_CRITERIA'),
        );
    }

    private function configureFilters()
    {
        $filtersEnabled = explode('#', $this->dataSource['block']->getZoneLabel());
        $this->result['showFilters '] = true;
        $this->result['models '] = [];

        // RG_FO_PF25_02 : Le filtre par catégorie est en premier si activé
        // RG_FO_PF25_21 : le filtre cate catégorie n'est pas désactivable en mobile
        if(self::TYPE_AFFICHAGE_CATEGORIES === $this->dataSource['block']->getZoneAttribut() || $this->isMobile ) {
            $this->configCategory();
        }
    }

    private function configCategory()
    {
        return;
        // ajout des traductions
        $this->result['translate']['models'] = $this->trans('NDP_AVAILABLE_RANGE_MODELS');
        //activation du filtre en premier  RG_FO_PF25_02
        array_unshift($this->result['filters '], 'categories') ;
        // configuration du filtre
        $this->result['models '] = $this->dataSource['categories'];
    }
}
