<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Repository\PsaPageRepository;
use PSA\MigrationBundle\Repository\PsaLanguageRepository;

/**
 * Data source for Pt17ChoixDeLaLangue block
 */
class Pt17ChoixDeLaLangueDataSource extends AbstractDataSource
{
    /**
     * @var PsaPageRepository
     */
    private $pageRepository;

    /**
     * @var PsaLanguageRepository
     */
    private $languageRepository;

    /**
     * @param PsaPageRepository     $pageRepository
     * @param PsaLanguageRepository $languageRepository
     */
    public function __construct(PsaPageRepository $pageRepository, PsaLanguageRepository $languageRepository)
    {
        $this->pageRepository = $pageRepository;
        $this->languageRepository = $languageRepository;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     * @param ReadBlockInterface $block
     * @param Request            $request
     * @param bool               $isMobile

     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        $params = $request->attributes;
        $siteId = $params->get('siteId');

        $data['language'] = $this->languageRepository->findBySiteId($siteId);

        foreach ($data['language'] as $idx => $lang) {
            $data['language'][$idx]['links'] = $this->pageRepository->getSiteMapPages($siteId, $lang['langueCode'], false, null, 1);
        }

        return $data;
    }
}
