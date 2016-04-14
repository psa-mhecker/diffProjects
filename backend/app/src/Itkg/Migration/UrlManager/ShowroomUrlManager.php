<?php


namespace Itkg\Migration\UrlManager;

use Doctrine\DBAL\Migrations\MigrationException;
use Itkg\Transaction\PsaEntityFactory;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Entity\Site\PsaSiteCode;
use PSA\MigrationBundle\Entity\User\PsaUser;
use Normalizer;


/**
 * Class to manage input for the migration: url and file path related to showroom (xml, media, sub-pages..)
 * All urls to generate are initialised by calling the initUrls() function
 * This manager also contains the current PsaSite and Language launching the migration
 *
 * Class ShowroomUrlHelper
 * @package Itkg\Migration
 *
 */
class ShowroomUrlManager
{
    const URL_TYPE_VN_PUBLISHED = 'VN_PUBLISHED';
    const URL_TYPE_CONCEPT_PUBLISHED = 'CONCEPT_PUBLISHED';
    const URL_TYPE_TECHNO_PUBLISHED = 'TECHNO_PUBLISHED';
    const URL_TYPE_NOT_PUBLISHED = 'NOT_PUBLISHED';

    /** @var array 'Delia' multilingual country codes to migrate. ex: ['be', 'ch'] */
    private $multilingualCountryCodes;

    /** @var string actual showroom welcome */
    private $welcomePageUrl;
    /** @var string url path without host to use for rebuilding migrated home page and subpages url path */
    private $welcomePageNewPath;
    /** @var string */
    private $urlType;
    /** @var PsaLanguage */
    private $language;
    /** @var PsaSite */
    private $site;
    /** @var PsaSiteCode */
    private $siteCode;
    /** @var $user */
    private $user;
    /** @var string */
    private $host;

    /** @var string url for showroom back Office */
    private $backOfficeBaseUrl;
    /** @var null|string url for xml associated to showroom */
    private $xmlUrl = null;
    /** @var null|string path for downloaded xml content */
    private $xmlFilePath = null;


    /**
     * @param array $multilingualCountryCodes multilingualCountryCodes
     * @param string $welcomePageUrl showroom url welcome page
     * @param string $urlType showroom welcome page url type
     * @param string $languageCode Language code selected to migrate data (fr, en...)
     * @param PsaSite $site Site launching the migration
     * @param PsaSiteCode $siteCode
     * @param PsaUser $user
     *
     * @throws MigrationException
     */
    public function __construct(
        array $multilingualCountryCodes,
        $welcomePageUrl,
        $urlType,
        $languageCode,
        PsaSite $site,
        PsaSiteCode $siteCode,
        PsaUser $user
    )
    {
        $this->multilingualCountryCodes = $multilingualCountryCodes;
        $this->welcomePageUrl = $welcomePageUrl;
        $this->urlType = $urlType;
        $this->site = $site;
        $this->siteCode = $siteCode;
        $this->user = $user;

        foreach($site->getLangues() as $language) {
            /** @var PsaLanguage $language */
            if ($language->getLangueCode() === $languageCode) {
                $this->language = $language;
            }
        }
        if ($this->language === null) {
            throw new MigrationException('No language for code ' . $languageCode . 'found for PsaSite : ' . $site->getSiteLabel());
        }
    }

    /**
     * Generate back office urls associated to showroom url
     *
     */
    public function initUrls()
    {
        $showroomUrlInfos = parse_url($this->welcomePageUrl);

        $this->welcomePageNewPath = $this->buildWelcomePageNewPath(
            $showroomUrlInfos['host'],
            $showroomUrlInfos['path'],
            $showroomUrlInfos['query']
        );

        // build backoffice and xml url
        $backOfficeBaseUrl = http_build_url(
            '',
            [
                'scheme' => $showroomUrlInfos['scheme'],
                'host' => $showroomUrlInfos['host'],
                'path' => $this->buildBackOfficePath(
                    $showroomUrlInfos['host'],
                    $showroomUrlInfos['path'],
                    $showroomUrlInfos['query']
                ),
                "query" => null
            ]
        );

        $this->host = $showroomUrlInfos['host'];
        $this->backOfficeBaseUrl = $backOfficeBaseUrl;
        $this->xmlUrl = $backOfficeBaseUrl . '/index.xml';
    }

    /**
     * Return media url using pre-formatted url
     *
     * @param $mediaUrl
     *
     * @return string
     */
    public function generateMediaUrl($mediaUrl)
    {
        if (substr($mediaUrl, 0, 1) !== '/') {
            $mediaUrl = '/' . $mediaUrl;
        }

        return $this->backOfficeBaseUrl . $mediaUrl;
    }


    /**
     * Return new welcome page url path for new page created
     *
     * @param string $urlKey
     *
     * @return string
     */
    public function generateWelcomePagePath($urlKey)
    {
        $result = $this->welcomePageNewPath;
        $urlKey = $this->slug($urlKey);

        if ($this->urlType === self::URL_TYPE_NOT_PUBLISHED) {
            $result = $this->urlPathConcat($result, $urlKey);
        }

        return $result;
    }

    /**
     * Return new suppages page url path for new page created
     *
     * @param $urlKey
     * @param string $urlKeyPathPrefix
     * @param string $urlKeyPrefix
     *
     * @return string
     */
    public function generateSubPagePath($urlKey, $urlKeyPathPrefix, $urlKeyPrefix = 'p=')
    {
        $result = $this->welcomePageNewPath;
        $urlKey = $this->slug($urlKey);

        if ($this->urlType === self::URL_TYPE_NOT_PUBLISHED) {
            $result = $this->urlPathConcat($result, $urlKeyPathPrefix);
            $result = $this->urlPathConcat($result, $urlKey . '/');
        } else {
            $result = $this->urlPathConcat($result, $urlKeyPrefix . $urlKey. '/');
        }

        return $result;
    }

    /**
     * @param $path1
     * @param $path2
     *
     * @return string
     */
    private function urlPathConcat($path1, $path2)
    {
        if (substr($path1, -1) !== '/') {
            $path1 .= '/';
        }

        return $path1 . $path2;
    }



    /**
     * @param $string
     * @param string $slug
     * @param null $extra
     * @return string
     */
    public function slug($string, $slug = '-', $extra = null)
    {
        return strtolower(trim(preg_replace('~[^0-9a-z' . preg_quote($extra, '~') . ']+~i', $slug, $this->UnAccent($string)), $slug));
    }

    /**
     * return Zone Template Id for the gabarit to use according to type of url selected by User to import (Technology or Car)
     *
     * @return int
     */
    public function getGabaritTemplateId()
    {
        switch ($this->urlType) {
            case self::URL_TYPE_TECHNO_PUBLISHED:
                return PsaEntityFactory::TEMPLATE_PAGE_ID_GABARIT_TECHNOLOGY_G36;
            default:
                return PsaEntityFactory::TEMPLATE_PAGE_ID_GABARIT_SHOWROOM_G27;
        }
    }


    /**
     * @param $string
     *
     * @return string
     */
    private function UnAccent($string)
    {
        if (extension_loaded('intl') === true)
        {
            $string = Normalizer::normalize($string, Normalizer::FORM_KD);
        }
        if (strpos($string = htmlentities($string, ENT_QUOTES, 'UTF-8'), '&') !== false)
        {
            $string = html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|caron|cedil|circ|grave|lig|orn|ring|slash|tilde|uml);~i', '$1', $string), ENT_QUOTES, 'UTF-8');
        }
        return $string;
    }

    /**
     * @param string $host
     * @param string $urlPath
     * @param string $queryParams
     *
     * @return string
     */
    private function buildWelcomePageNewPath($host, $urlPath, $queryParams)
    {
        $pathResult = '';

        switch ($this->urlType) {
            case self::URL_TYPE_VN_PUBLISHED:
            case self::URL_TYPE_CONCEPT_PUBLISHED:
            case self::URL_TYPE_TECHNO_PUBLISHED:
                $pathResult = $this->getLanguagePrefixForCurrentSite() . $urlPath;
                break;
            case self::URL_TYPE_NOT_PUBLISHED:
                $pathResult = '/showroom/' . $this->getIdForUnpublishedUrl($queryParams);
                break;
        }

        return $pathResult;


    }
    /**
     * @param string $host
     * @param string $urlPath
     * @param string $queryParams
     *
     * @return string
     */
    private function buildBackOfficePath($host, $urlPath, $queryParams)
    {
        $urlPath = trim($urlPath, "/");
        $pathResult = '';

        switch ($this->urlType) {
            case self::URL_TYPE_VN_PUBLISHED:
            case self::URL_TYPE_CONCEPT_PUBLISHED:
                $pathResult = '/media/showrooms' . $this->getLanguagePrefixForBackOffice($host) .'/showroom-peugeot-';

                // Remove first folder path to get seo path
                $parts = explode('/', $urlPath);
                if (is_array($parts) && count($parts) > 0) {
                    unset($parts[0]);
                    $pathResult .= implode('-', $parts) . '-kppv3/medias';
                }
                break;

            case self::URL_TYPE_TECHNO_PUBLISHED:
                $pathResult = '/media/showrooms' . $this->getLanguagePrefixForBackOffice($host) . '/showroom-peugeot-';
                $pathResult .= str_replace("/", "-", $urlPath) . '-kppv3/medias';
                break;

            case self::URL_TYPE_NOT_PUBLISHED:
                $pathResult = '/media/showroom/workspace/' . $this->getIdForUnpublishedUrl($queryParams);
                break;
        }

        return $pathResult;
    }

    /**
     * Four unpublished url, get url string parameter 'showroom_id'
     *
     * @param string $queryParams
     *
     * @return string
     */
    private function getIdForUnpublishedUrl($queryParams)
    {
        $result = '';
        parse_str($queryParams, $queryArray);

        if (isset($queryArray['showroom_id']) && !is_array($queryArray['showroom_id'])) {
            $result = $queryArray['showroom_id'];
        }

        return $result;
    }

    /**
     * For old Delia multilingual website published showroom, a language prefix should be added to path
     * The multilingual country is checked according to the host extension .fr .be ... if part of $this->multilingualCountryCodes
     *
     * @param $host
     *
     * @return string
     */
    private function getLanguagePrefixForBackOffice($host)
    {
        $result = '';

        if ($this->urlType !== self::URL_TYPE_NOT_PUBLISHED && $host) {
            // Check if host last part (.fr .be .com...) is part of the multilingualCountryCodes list
            $parts = explode('.', $host);

            if (is_array($parts) && (in_array(end($parts), $this->multilingualCountryCodes))) {
                $result = '/' . $this->language->getLangueCode();
            }

        }

        return $result;
    }

    /**
     * For PSA multilingual site, a language prefix should be added to path
     * The multilingual country current is checked according current $this->siteCode if part of $this->multilingualCountryCodes
     *
     * @return string
     */
    private function getLanguagePrefixForCurrentSite()
    {
        $result = '';

        if ($this->urlType !== self::URL_TYPE_NOT_PUBLISHED && $this->siteCode
            && in_array(strtolower($this->siteCode->getSiteCodePays()), $this->multilingualCountryCodes)
        ) {
            $result = '/' . $this->language->getLangueCode();
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getWelcomePageUrl()
    {
        return $this->welcomePageUrl;
    }

    /**
     * @return string
     */
    public function getUrlType()
    {
        return $this->urlType;
    }

    /**
     * @return string
     */
    public function getXmlUrl()
    {
        return $this->xmlUrl;
    }

    /**
     * @return string
     */
    public function getBackOfficeBaseUrl()
    {
        return $this->backOfficeBaseUrl;
    }

    /**
     * @return string
     */
    public function getXmlFilePath()
    {
        return $this->xmlFilePath;
    }

    /**
     * @param string $xmlFilePath
     *
     * @return ShowroomUrlManager
     */
    public function setXmlFilePath($xmlFilePath)
    {
        $this->xmlFilePath = $xmlFilePath;

        return $this;
    }

    /**
     * @return PsaLanguage
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return PsaSite
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     *
     * @return ShowroomUrlManager
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

}
