<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\PsaGroupeReseauxSociaux;
use PSA\MigrationBundle\Repository\PsaGroupeReseauxSociauxRepository;
use PSA\MigrationBundle\Repository\PsaGroupeReseauxSociauxRsRepository;
use PSA\MigrationBundle\Repository\PsaReseauSocialRepository;
use PsaNdp\MappingBundle\Utils\SocialLinksManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Pc79MurMediaManuelDataSource
 * @package PsaNdp\MappingBundle\Sources
 */
class Pc79MurMediaManuelDataSource extends AbstractDataSource
{
    /**
     * @var PsaGroupeReseauxSociauxRsRepository
     */
    private $psaGroupeReseauxSociauxRsRepository;

    /**
     * @var PsaGroupeReseauxSociauxRepository
     */
    private $psaGroupeReseauxSociauxRepository;

    /**
     * @var PsaReseauSocialRepository
     */
    private $psaReseauSocialRepository;

    /**
     * @var SocialLinksManager
     */
    private $linkManager;

    public function __construct(PsaGroupeReseauxSociauxRepository $psaGroupeReseauxSociauxRepository, PsaGroupeReseauxSociauxRsRepository $psaGroupeReseauxSociauxRsRepository, PsaReseauSocialRepository $psaReseauSocialRepository, SocialLinksManager $linkManager)
    {
        $this->psaGroupeReseauxSociauxRsRepository = $psaGroupeReseauxSociauxRsRepository;
        $this->psaGroupeReseauxSociauxRepository = $psaGroupeReseauxSociauxRepository;
        $this->psaReseauSocialRepository = $psaReseauSocialRepository;
        $this->linkManager = $linkManager;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param  ReadBlockInterface $block
     * @param  Request            $request  Current url request displaying th block
     * @param  bool               $isMobile Indicate if is a mobile display
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        $siteId = $block->getPage()->getSiteId();
        $langId = $block->getLangue()->getLangueId();
        $resultInitReseauSocial = $this->initArrReseauSocial($siteId, $langId);

        $data['block']       = $block;
        $data['currentPageAbsoluteUrl'] = $request->getSchemeAndHttpHost() . $block->getPage()->getVersion()->getPageClearUrl() . '?' . $request->getQueryString();
        $data['pageMetaTitle'] = $block->getPage()->getVersion()->getPageMetaTitle();
        $data['pageMetaDesc'] = $block->getPage()->getVersion()->getPageMetaDesc();
        $data['arrReseauSocial'] = $resultInitReseauSocial[0];
        $data['errorMsg'] = $resultInitReseauSocial[1];

        return $data;
    }

    private function initArrReseauSocial($siteId, $langId)
    {
        /**
         * s'il y en a 2 marqués par défaut je prends la 1ere que tu trouves car aucune règle n'a été établie la dessus. Donc ça sera à l'admin de faire sa saisie correctement
         * @var PsaGroupeReseauxSociaux $groupeReseauSocial
         */
        $result  = [];
        $arrGroupeReseauSocialRs = $this->psaGroupeReseauxSociauxRepository->findWithReseauSocialBySiteIdLangueIdGroupeReseauxSociauxId($siteId, $langId); 
        $msgErreurs = [];
        if (!empty($arrGroupeReseauSocialRs)) {     
            foreach ($arrGroupeReseauSocialRs as $key => $groupeReseauSocialRs) {
                try { 
                    $arrGroupeReseauSocialRs[$key]['provider'] = $this->linkManager->getProviderName($groupeReseauSocialRs['prs_reseauSocialType']);
                } catch (\Exception $e) {
                    $msgErreurs[] = sprintf("Veuillez vérifier le parametrage du resau social %s. Une erreur s'est produite en récuperant sont provider.", $groupeReseauSocialRs['prs_reseauSocialLabel']);
                }
            }
            $result = $arrGroupeReseauSocialRs;
        }

        return [$result, $msgErreurs];
    }
}
