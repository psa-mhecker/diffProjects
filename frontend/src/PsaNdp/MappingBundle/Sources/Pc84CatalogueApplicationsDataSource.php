<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PSA\MigrationBundle\Repository\PsaMediaRepository;
use PsaNdp\MappingBundle\Entity\PsaConnectServices;
use PsaNdp\MappingBundle\Object\Cta;
use PsaNdp\MappingBundle\Repository\PsaConnectServicesRepository;
use PsaNdp\MappingBundle\Repository\PsaServiceConnectFinitionGroupingRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Pc84CatalogueApplicationsDataSource
 * @package PsaNdp\MappingBundle\Sources
 */
class Pc84CatalogueApplicationsDataSource extends AbstractDataSource
{
    /**
     * @var PsaConnectServicesRepository
     */
    private $connectServicesRepository;

    /**
     * @var PsaMediaRepository
     */
    private $mediaRepository;

    /**
     * @var PsaServiceConnectFinitionGroupingRepository
     */
    private $serviceConnectFinitionGroupingRepository;

    public function __construct(
        PsaConnectServicesRepository $connectServicesRepository,
        PsaMediaRepository $psaMediaRepository,
        PsaServiceConnectFinitionGroupingRepository $serviceConnectFinitionGroupingRepository
    )
    {
        $this->connectServicesRepository = $connectServicesRepository;
        $this->mediaRepository = $psaMediaRepository;
        $this->serviceConnectFinitionGroupingRepository = $serviceConnectFinitionGroupingRepository;
    }

    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        $strServiceConnectes = $block->getZoneTexte();
        $arrServiceConnecteIds = explode('#', $strServiceConnectes);
        $serviceConnectes = $this->connectServicesRepository->findByIds($arrServiceConnecteIds);

        $data['block'] = $block;
        $data['title'] = $block->getZoneTitre();
        $data['subtitle'] = $block->getZoneTitre2();
        $data['items'] = $this->initItems($serviceConnectes);
        $data['mapApplicationVisuals'] = $this->initMapApplicationVisuals($serviceConnectes);

        return $data;
    }

    private function initMapApplicationVisuals(array $serviceConnectes)
    {
        $applicationVisualIds = [];
        $mapApplicationVisuals = [];

        foreach ($serviceConnectes as $serviceConnecte) {
            $applicationVisualIds[] = $serviceConnecte->getApplicationVisual();
        }

        $medias = $this->mediaRepository->findByMediaId($applicationVisualIds);

        /**
         * @var PsaMedia $media
         */
        foreach ($medias as $media) {
            $mapApplicationVisuals[$media->getMediaId()] = $media;
        }

        return $mapApplicationVisuals;
    }

    private function initItems(array $serviceConnectes)
    {
        $items = [];

        /**
         * @var PsaConnectServices $serviceConnecte
         */
        foreach ($serviceConnectes as $serviceConnecte) {
            $item = [
                'id' => $serviceConnecte->getId(),
                'visual' => $serviceConnecte->getApplicationVisual(),
                'title' => $serviceConnecte->getLabel(),
                'text'  => $serviceConnecte->getDescription(),
                'note'  => $serviceConnecte->getLegalNotice()
                ];

            if ($serviceConnecte->getUrl()) {
                $item['cta'] = array(
                    array(
                        'url' => $serviceConnecte->getUrl(), // Configuré a partir d'une page publiée "NDP - Fiche service connecté"
                        'style' => 'cta',
                        'version' => Cta::NDP_CTA_VERSION_LIGHT_BLUE,
                        'title' => 'TRAD_NDP_DOWNLOAD_APPLICATION', // repris l'objet depuis les traductions
                        'target' => '_self'
                    )
                );
            }

            $list = $this->serviceConnectFinitionGroupingRepository->findModelLabelFromConnectServiceAndSiteAndLanguage(
                $serviceConnecte->getId(),
                $serviceConnecte->getSite()->getId(),
                $serviceConnecte->getLangue()->getLangueId()
            );

            if ($list) {
                $item['popin'] = array(
                    'id' => $serviceConnecte->getId(),
                    'title' => $serviceConnecte->getLabel(),
                    'text' => 'TRAD_NDP_COMPATIBLE_EXCLUSIVELY_WITH_FOLLOWING_VEHICLES', // repris dans l'objet depuis les traductions
                    'list' => $list,
                    'question' => 'TRAD_NDP_IS_MY_VEHICLE_COMPATIBLE_WITH_THIS_SERVICE', // repris dans l'objet depuis les traductions
//                    'cta' => array(), // Le cta est construit à partir du cta configuré en BO
                    'close' => 'TRAD_NDP_CLOSE' // repris dans l'objet depuis les traductions
                );
            }

            $items[] = $item;
        }

        return $items;
    }
}
