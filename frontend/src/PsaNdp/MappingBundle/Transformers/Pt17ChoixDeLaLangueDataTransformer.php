<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Helper\CtaHelper;
use Symfony\Component\Routing\Router;

/**
 * Data transformer for Pt17ChoixDeLaLangue block
 */
class Pt17ChoixDeLaLangueDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /** @var CtaHelper $ctaHelper */
    protected $ctaHelper;

    /**
     *  Fetching data slice Choix de la Langue (pt17)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $result = [];
        $this->ctaHelper = $this->getHelper('cta');

        if (!$isMobile) {
            $result = $this->getLanguageChoiceDesktopData($dataSource);
        }

        if ($isMobile) {
            $result = $this->getLanguageChoiceMobileData($dataSource);
        }

        return $result;
    }

    /**
     * Data Transformer for language choice Desktop
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function getLanguageChoiceDesktopData(array $dataSource)
    {
        $data = [];

        foreach ($dataSource['language'] as $idx => $lang) {
            $home = $this->getHome($lang['links']);

            $data['listLanguage'][$idx] = $this->ctaHelper->createCtaFromTitleAndUrl(
                $this->trans('NDP_LANGUAGE_SITE', array(), null, $lang['langueCode']),
                $home['currentVersion']['pageClearUrl'],
                CtaHelper::NDP_CTA_VERSION_NIVEAU4
            );
            $data['modalLanguage'][$idx]['title'] = $this->trans('NDP_CHOOSE_LANGUAGE', array(), null, $lang['langueCode']);
            $data['listLanguage'][$idx]['links'] = $this->getNavLevel1($lang['links'], $home);
        }

        return array('slicePT17' => $data);
    }

    /**
     * Data Transformer for language choice Mobile
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function getLanguageChoiceMobileData(array $dataSource)
    {
        $data = [];

        foreach ($dataSource['language'] as $idx => $lang) {
            $home = $this->getHome($lang['links']);

            $data['ctaList'][$idx] = $this->ctaHelper->createCtaFromTitleAndUrl(
                $this->trans('NDP_LANGUAGE_SITE', array(), null, $lang['langueCode']),
                $home['currentVersion']['pageClearUrl'],
                CtaHelper::NDP_CTA_VERSION_NIVEAU4
            );
            $data['titleSection'][$idx]['title'] = $this->trans('NDP_CHOOSE_LANGUAGE', array(), null, $lang['langueCode']);
            $data['ctaList'][$idx]['dimension'] = '12';
        }

        return array('slicePT17' => $data);
    }

    /**
     * Return the formated list of link
     *
     * @param array $links
     * @param array $home
     *
     * @return array
     */
    private function getNavLevel1(array $links, $home)
    {
        $result = array();

        foreach ($links as $link) {

            if ($link['pageParentId'] === $home['pageId']) {
                $nav          = array();
                $nav['url']   = $link['currentVersion']['pageClearUrl'];
                $nav['title'] = $link['currentVersion']['pageTitle'];
                $result[]     = $nav;
            }
        }

        $smallResult = array_slice($result, 0, 7);
        
        return $smallResult;
    }

    /**
     * @param array $links
     *
     * @return array
     */
    private function getHome(array $links)
    {
        $home = array(
            'pageId' => null,
            'currentVersion' => array('pageClearUrl' => null),
        );

        foreach ($links as $link) {
            if ($link['pageParentId'] === null) {
                $home = $link;
            }
        }

        return $home;
    }
}
