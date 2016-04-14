<?php
namespace PsaNdp\MappingBundle\Transformers;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Block\Pf11\Pf11RecherchePointDeVente;
use PsaNdp\MappingBundle\Object\Block\Pf11\Pf11RecherchePointDeVenteCas;

/**
 * Class Pf11RecherchePointDeVenteDataTransformer
 * Data transformer for Pf11RecherchePointDeVente block
 * @package PsaNdp\MappingBundle\Transformers
 */
class Pf11RecherchePointDeVenteDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    const MODE_SEARCH_PDV = 1;
    const MODE_PROMO_APV = 2;
    const FILTER_BY_RADIUS = 1;
    const FILTER_BY_PDV_PDN = 2;

    /**
     * @var Pf11RecherchePointDeVente
     */
    protected $pf11RecherchePointDeVente;

    /**
     * @var Pf11RecherchePointDeVenteCas
     */
    protected $pf11RecherchePointDeVenteCas;

    /**
     * @param Pf11RecherchePointDeVente    $pf11RecherchePointDeVente
     * @param Pf11RecherchePointDeVenteCas $pf11RecherchePointDeVenteCas
     */
    public function __construct(
        Pf11RecherchePointDeVente $pf11RecherchePointDeVente,
        Pf11RecherchePointDeVenteCas $pf11RecherchePointDeVenteCas
    ) {

        $this->pf11RecherchePointDeVente = $pf11RecherchePointDeVente;
        $this->pf11RecherchePointDeVenteCas = $pf11RecherchePointDeVenteCas;
    }

    /**
     *  Fetching data slice RecherchePointDeVente (pf11)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     * @throws \Exception
     */
    public function fetch(array $dataSource, $isMobile)
    {
        /** @var PsaPageZoneConfigurableInterface $block */

        $pf11 = $this->getDesktopData($dataSource);

        return array(
           'slicePf11' =>  $pf11,
        );
    }



    /**
     * @param array $dataSource
     * TODO management of "cas1", spec under clarification with MOA
     * @return $this
     */
    private function getDesktopData(array $dataSource)
    {
        $this->fillPf11RecherchePointDeVenteCas();
        $dataSource['errorload'] = $this->trans('NDP_AJAX_LOADING_ISSUE');
        $this->pf11RecherchePointDeVente->setMediaServer($this->mediaServer);
        $this->pf11RecherchePointDeVente->setDataFromArray($dataSource);

        // ** pf11RecherchePointDeVente main array object

        $this->pf11RecherchePointDeVente->setRegroupement($this->getBlock()->getZoneAttribut() === 1);
        $this->pf11RecherchePointDeVente->setAutocompletion($this->getBlock()->getZoneAttribut2() === 1);
        if ($dataSource['isFull']) {
            $this->pf11RecherchePointDeVente->setCas2($this->pf11RecherchePointDeVenteCas);
        } else {
            $this->pf11RecherchePointDeVente->setCas1($this->pf11RecherchePointDeVenteCas);
        }
        $this->pf11RecherchePointDeVente->initMapOptions();

        return $this->pf11RecherchePointDeVente;
    }

    /**
     *
     */
    private function fillPf11RecherchePointDeVenteCas()
    {
        // *** "cas1" and "cas2"
        // set translation to be used either in 'cas1' ou 'cas2'
        //RG_FO_PF11_01
        $dataCas['title'] = $this->getBlock()->getZoneTitre();
        //RG_FO_PF11_10
        $dataCas['btnAroundMe'] = $this->trans('NDP_AROUND_ME');
        $dataCas['translate'] = array(
            'btnSubmit' => $this->trans(Pf11RecherchePointDeVente::NDP_OK),
            'or' => $this->trans('NDP_OR'),
            'prefixTel' => $this->trans('NDP_TEL'),
        );
        $dataCas['searchSubmit'] = $this->trans(Pf11RecherchePointDeVente::NDP_OK);
        $dataCas['or'] = $this->trans('NDP_OR');
        $dataCas['tel'] = $this->trans('NDP_TEL');
        $dataCas['filterBy'] = $this->trans('NDP_FILTER_BY');
        $dataCas['moreFilter'] = $this->trans('NDP_MORE_FILTER');
        $dataCas['moreFilterClose'] = $this->trans('NDP_CLOSE');
        $dataCas['resultFound'] = $this->trans('NDP_RESULTS_FOUND');
        $dataCas['resultNotFound'] = $this->trans('NDP_NO_RESULT');
        $dataCas['seeMore'] = $this->trans('NDP_VIEW_DETAILED_SHEET');
        $dataCas['mapParam'] = array(
            'picto' => 'http://www.hostingpics.net/thumbs/12/51/96/mini_125196pin.png',
            'pictoOn' => 'http://www.hostingpics.net/thumbs/58/37/85/mini_583785pinon.png',
            'pictoOff' => 'http://www.hostingpics.net/thumbs/72/43/39/mini_724339pinoff.png',
            'textLinkInfoWindow' => $this->trans('NDP_VIEW_DETAILED_SHEET'),
        );

        $this->pf11RecherchePointDeVenteCas->initListFilter($this->getBlock()->getParameters());
        $this->pf11RecherchePointDeVenteCas->setDistance($this->getBlock()->getZoneTitre3());
        if($this->getBlock()->getZoneAttribut3()) {

            $this->pf11RecherchePointDeVenteCas->setHidePhone(array('seePhone'=> $this->trans('NDP_SEE_PHONE')));//
        }

        //RG_FO_PF11_04
        $filterActive = false;
        //activate if in MODE_SEARCH_PDV and "PDV Filter by name" choice is active
        if ($this->getBlock()->getZoneCriteriaId() === self::MODE_SEARCH_PDV && $this->getBlock()->getZoneCriteriaId3()) {
            $filterActive = true;
        }

        $this->pf11RecherchePointDeVenteCas->setSearchType(
            array(
                'filterActive' => $filterActive,
                'label1' => $this->trans('NDP_PF11_BY_CITY_OR_POSTAL_CODE'), //RG_FO_PF11_02
                'placeholder1' => $this->trans('NDP_INDICATE_CITY_OR_POSTAL_CODE'), // trans shared with pf44
                'label2' => $this->trans('NDP_PF11_BY_POINT_OF_SALE_NAME'), //RG_FO_PF11_02
                'placeholder2' => $this->trans('NDP_PF11_INDICATE_POINT_OF_SALE_NAME'),
                'autoCompletion' => $this->getBlock()->getZoneAttribut2(),
            ));
        $this->pf11RecherchePointDeVenteCas->setInfoDealer(
            array(
                'return' => $this->trans('NDP_RETURN_LIST_POINTS_OF_SALES'), //Retour aux points de vente
                'label1' => $this->trans('NDP_CONTACT'),//Contact
                'label2' => $this->trans('NDP_SERVICE'),//Service
                'ctaWebsiteLabel' => array(
                    'label' => $this->trans('NDP_VISIT_WEBSITE'), // Visitez le site
                    'target' => '_self',
                ),
                'vcfLabel' => array(
                    'label' => $this->trans('NDP_CONTACT_CARD'), //fiche contact (vcf)
                    'target' => '_self',
                ),
                'ctaList' => array(
                    array(
                        'style' => 'cta',
                        'url' => '{{url}}',
                        'version' => '{{version}}',
                        'title' => '{{title}}',
                        'target' => '{{target}}',
                    ),
                ),
            )
        );
        $this->pf11RecherchePointDeVenteCas->setDataFromArray($dataCas);
    }
 }
