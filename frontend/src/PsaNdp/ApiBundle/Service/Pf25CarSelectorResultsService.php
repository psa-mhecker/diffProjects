<?php


namespace PsaNdp\ApiBundle\Service;

use PSA\MigrationBundle\Repository\PsaPageRepository;

class Pf25CarSelectorResultsService
{
    const SHOWROOM_WELCOME_PAGE_TEMPLATE = 290;
    protected $pageRepository;

    /**
     * @param PsaPageRepository $pageRepository
     */
    public function __construct(PsaPageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * @param string $lcdv6Code
     * @param string $grBody
     *
     * @return int
     */
    public function getShowrooms()
    {
        return $this->pageRepository
            ->findPublishedByTemplateIdQuery(self::SHOWROOM_WELCOME_PAGE_TEMPLATE)
            ->getQuery()
            ->getResult()
            ;
    }
}
