<?php


namespace Itkg\Migration\UrlManager;

use Doctrine\ORM\EntityManager;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Entity\Site\PsaSiteCode;
use PSA\MigrationBundle\Entity\User\PsaUser;


/**
 * Class DataMigrationReportingLogFactory
 * @package Itkg\Log
 */
class ShowroomUrlManagerFactory
{
    /** @var array 'Delia' multilingual country codes to migrate. ex: ['be', 'ch'] */
    private $multilingualCountryCodes;
    /** @var EntityManager */
    protected $em;

    /**
     * @param EntityManager $em
     * @param array $multilingualCountryCodes
     */
    public function __construct(EntityManager $em, array $multilingualCountryCodes)
    {
        $this->multilingualCountryCodes = $multilingualCountryCodes;
        $this->em = $em;
    }

    /**
     * @param string $welcomePageUrl
     * @param string $urlType
     * @param string $language
     * @param PsaSite $site
     * @param PsaUser $user
     *
     * @return ShowroomUrlManager
     */
    public function create($welcomePageUrl, $urlType, $language, PsaSite $site, PsaUser $user)
    {
        $siteCodeRepository = $this->em->getRepository('PSA\MigrationBundle\Entity\Site\PsaSiteCode');
        /** @var PsaSiteCode $siteCode */
        $siteCode = $siteCodeRepository->find($site->getSiteId());

        $urlManager = new ShowroomUrlManager(
            $this->multilingualCountryCodes,
            $welcomePageUrl,
            $urlType,
            $language,
            $site,
            $siteCode,
            $user

        );
        $urlManager->initUrls();

        return $urlManager;
    }

}
