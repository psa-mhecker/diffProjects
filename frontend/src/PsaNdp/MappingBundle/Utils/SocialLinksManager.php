<?php

namespace PsaNdp\MappingBundle\Utils;

use SocialLinks\Page;
use PSA\MigrationBundle\Repository\PsaGroupeReseauxSociauxRepository;
use PSA\MigrationBundle\Entity\PsaReseauSocial;

/**
 * Description of SocialLinkManager
 *
 * @author sthibault
 */
class SocialLinksManager
{
    /**
     * @var PsaGroupeReseauxSociauxRepository
     */
    protected $psaGroupeReseauxSociauxRepository;

    /**
     *
     * @param PsaGroupeReseauxSociauxRepository $psaGroupeReseauxSociauxRepository
     */
    public function __construct(PsaGroupeReseauxSociauxRepository $psaGroupeReseauxSociauxRepository)
    {
        $this->psaGroupeReseauxSociauxRepository = $psaGroupeReseauxSociauxRepository;
    }

    /**
     *
     * @return Page
     */
    public function getSocialLinks($site, $lang)
    {
        $socialLinks = array();
        $socialNetworksGroup = $this->psaGroupeReseauxSociauxRepository->findBy(array(
            'site' => $site,
            'langue' => $lang
        ));

        foreach ($socialNetworksGroup as $groupItem) {
            $rs = $groupItem->getReseauSocial();

            $socialLinks[] = array(
                'label'  => $rs->getReseauSocialLabel(),
                'class'  => $this->getClassForSocialNetwork($rs->getReseauSocialType()),
                'url'    => $rs->getReseauSocialUrlWeb(),
                'target' => ($rs->getReseauSocialUrlModeOuverture() == 1) ? '_self' : '_blank'
            );
        }

        return $socialLinks;
    }
    
    /**
     *
     * @param array $config
     *
     * @return Page
     */
    public function getSocialLinksPage($config)
    {

        //Create a Page instance with the url information
        return new Page($config);
    }
    
    /**
     *
     * @param int $socialNetworkType
     *
     * @return string
     */
    private function getClassForSocialNetwork($socialNetworkType)
    {
        $class = 'undefined-social-network';

        switch ($socialNetworkType) {
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_GOOGLEPLUS:
                $class = 'googleplus';
                break;
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_FACEBOOK:
                $class = 'facebook';
                break;
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_TWITTER:
                $class = 'twitter';
                break;
        }

        return $class;
    }
    
    /**
     *
     * @param int $reseauSocialType
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getProviderName($reseauSocialType)
    {
        switch ($reseauSocialType) {
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_FACEBOOK:
                $returnValue = 'facebook';
                break;
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_TWITTER:
                $returnValue = 'twitter';
                break;
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_LINKEDIN:
                $returnValue = 'linkedin';
                break;
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_GOOGLEPLUS:
                $returnValue = 'plus';
                break;
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_PINTEREST:
                $returnValue = 'pinterest';
                break;
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_VIADEO:
                // not yet implemented
                $returnValue = '';
                break;
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_INSTAGRAM:
                // not yet implemented
                $returnValue = '';
                break;
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_YOUTUBE:
                //not yet implemented
                $returnValue = '';
                break;
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_FLICKR:
                //not yet implemented
                $returnValue = '';
                break;
            default:
                $returnValue = '';
                break;
        }

        return $returnValue;
    }

    /**
     * @param $url
     * @param $media
     * @param $titre
     * @param $desc
     *
     * @return Page
     */
    public function getSocialLinksForImage($url, $media, $titre, $desc)
    {
        $config = array(
            'url'   => $url,
            'title' => $titre,
            'text'  => $desc,
            'image' => $media->getMediaPath()
        );

        return $this->getSocialLinksPage($config);
    }

    /**
     *
     * @param array  $media
     * @param string $titre
     * @param string $desc
     *
     * @return Page
     */
    public function getSocialLinksFormStreamlike($media, $titre, $desc)
    {
        $config = array(
            'url'   => $media['share_url'],
            'title' => $titre,
            'text'  =>$desc,
            'image' => $media['cover']
        );

        return $this->getSocialLinksPage($config);
    }

}
