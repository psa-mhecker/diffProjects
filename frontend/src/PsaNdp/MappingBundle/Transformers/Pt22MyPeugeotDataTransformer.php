<?php

namespace PsaNdp\MappingBundle\Transformers;

use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PsaNdp\MappingBundle\Object\BadgeApplication;
use PsaNdp\MappingBundle\Object\Block\Pt22ActionCompte;
use PsaNdp\MappingBundle\Object\Block\Pt22MyPeugeot;
use PsaNdp\MappingBundle\Object\Content;

/**
 * Data transformer for Pt22MyPeugeot block
 */
class Pt22MyPeugeotDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    /**
     * @var Pt22MyPeugeot
     */
    protected $pt22MyPeugeot;

    /**
     * @param Pt22MyPeugeot $pt22MyPeugeot
     */
    public function __construct(Pt22MyPeugeot $pt22MyPeugeot)
    {
        $this->pt22MyPeugeot = $pt22MyPeugeot;
    }

    /**
     *  Fetching data slice My Peugeot (pt22)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $result = [];

        if ($isMobile) {
            $result = $this->fetchMobile($dataSource);
        }

        if (!$isMobile) {
            $result = $this->fetchDesktop($dataSource);
        }

        $pt22 = $this->pt22MyPeugeot->setDataFromArray($result);

        return array('slicePT22' => $pt22);

    }

    /**
     * @param $dataSource
     * @return array
     */
    private function fetchMobile($dataSource)
    {
        $result = [];

        if (isset($dataSource['pageZone'])) {
            /** @var PsaPageZone $pageZone */
            $pageZone = $dataSource['pageZone'];

            // RG_FO_PT22_05
            $result['title'] = $pageZone->getZoneTitre();

            $mainLinkUser = new Content();
            $result['mainLinkUser'] = $mainLinkUser->setDataFromArray(
                array(
                    "title" => $this->trans('NDP_MY_PEUGEOT'),
                    "url" => "#",
                    'target' => '_blank',
                    'class' => 'active'
                )
            );

            $signIn = new Pt22ActionCompte();
            $dataSignIn = $dataSource['ctaSignIn'];
            $dataSignIn['title'] = $this->trans('NDP_SIGN_IN');
            $result['signIn'] = $signIn->setDataFromArray(
                array(
                    "label" => $this->trans('NDP_ALREADY_REGISTERED'),
                    "ctaList" => [
                        $this->pt22MyPeugeot->getCtaFactory()->createFromArray($dataSignIn)
                    ]
                )
            );

            // RG_FO_PT22_14 - RG_FO_PT22_16 , RG_FO_PT22_11
            $signUp = new Pt22ActionCompte();
            $dataSignUp = $dataSource['ctaSignUp'];
            $dataSignUp['title'] = $this->trans('NDP_PT22_SIGN_UP_MOBILE');
            $result['signUp'] = $signUp->setDataFromArray(
                array(
                    "label" => $this->trans('NDP_PT22_NEW_MEMBER'),
                    "ctaList" => [
                        $this->pt22MyPeugeot->getCtaFactory()->createFromArray($dataSignUp)
                    ]
                )
            );

            // RG_FO_PT22_06
            $result['description'] =  $pageZone->getZoneTexte();

            $result['datalayer'] = '';
        }

        return $result;
    }

    /**
     * @param array $dataSource
     * @return array
     */
    private function fetchDesktop(array $dataSource)
    {
        $result = [];

        if (isset($dataSource['pageZone'])) {
            /** @var PsaPageZone $pageZone */
            $pageZone = $dataSource['pageZone'];

            // RG_FO_PT22_05
            $result['title'] = $pageZone->getZoneTitre();

            // RG_FO_PT22_01 - RG_FO_PT22_04
            $mainLinkUser = new Content();
            $result['mainLinkUser'] = $mainLinkUser->setDataFromArray(
                array(
                    "title" => $this->trans('NDP_MY_PEUGEOT'),
                    "url" => "#"
                )
            );

            // RG_FO_PT22_06
            $result['blockContent'] = $pageZone->getZoneTexte();

            // RG_FO_PT22_07 - RG_FO_PT22_11
            $signIn = new Pt22ActionCompte();
            $dataSignIn = $dataSource['ctaSignIn'];
            $dataSignIn['title'] = $this->trans('NDP_SIGN_IN');
            $result['signIn'] = $signIn->setDataFromArray(
                array(
                    "label" => $this->trans('NDP_ALREADY_REGISTERED'),
                    "ctaList" => $this->pt22MyPeugeot->getCtaFactory()->createFromArray($dataSignIn)
                )
            );

            // RG_FO_PT22_14 - RG_FO_PT22_16 , RG_FO_PT22_11
            $signUp = new Pt22ActionCompte();
            $dataSignUp = $dataSource['ctaSignUp'];
            $dataSignUp['title'] = $this->trans('NDP_PT22_SIGN_UP_WEB');
            $result['signUp'] = $signUp->setDataFromArray(
                array(
                    "label" => $this->trans('NDP_PT22_NEW_MEMBER'),
                    "ctaList" => $this->pt22MyPeugeot->getCtaFactory()->createFromArray($dataSignUp)
                )
            );

            if ($pageZone->getZoneAttribut() === 1 && isset($dataSource['pushMobile'])) {
                // RG_FO_PT22_19 , RG_FO_PT22_20

                // RG_FO_PT22_21
                $result['descriptionStoreApp'] = $this->trans('NDP_PT22_HEADER_TITLE');

                // RG_FO_PT22_22 - RG_FO_PT22_24
                $badges = array();
                $target = isset($dataSource['pushMobile']['modeOuverture']) ? $dataSource['pushMobile']['modeOuverture'] : '_blank';

                if (isset($dataSource['pushMobile']['urlAppleStore']) && !empty($dataSource['pushMobile']['mediaAppleStore'])) {
                    $badges[] = array(
                        "title" => $this->trans('NDP_APP_STORE'),
                        "href" => $dataSource['pushMobile']['urlAppleStore'],
                        "src" => $this->mediaServer . $dataSource['pushMobile']['mediaAppleStore']->getMediaPath(),
                        "target" => $target
                    );
                }

                if (isset($dataSource['pushMobile']['urlGooglePlay']) && !empty($dataSource['pushMobile']['mediaGooglePlay'])) {
                    $badges[] = array(
                        "title" => $this->trans('NDP_GOOGLE_PLAY'),
                        "href" => $dataSource['pushMobile']['urlGooglePlay'],
                        "src" => $this->mediaServer .$dataSource['pushMobile']['mediaGooglePlay']->getMediaPath() ,
                        "target" => $target
                    );
                }

                if (isset($dataSource['pushMobile']['urlWindows']) && !empty($dataSource['pushMobile']['mediaWindows'])) {
                    $badges[] = array(
                        "title" => $this->trans('NDP_WINDOWS'),
                        "href" => $dataSource['pushMobile']['urlWindows'],
                        "src" => $this->mediaServer .$dataSource['pushMobile']['mediaWindows']->getMediaPath() ,
                        "target" => $target
                    );
                }


                $appstores = array();
                foreach($badges as $badge) {
                    $appstore = new BadgeApplication();
                    $appstores[] = $appstore->setDataFromArray($badge);
                }

                $result['appstores'] = $appstores;
                $result['contentFooter'] = $this->mediaServer.$dataSource['pushMobile']['mediaPath'];
            }

            $result['datalayer'] = '';
        }

        return $result;
    }
}
