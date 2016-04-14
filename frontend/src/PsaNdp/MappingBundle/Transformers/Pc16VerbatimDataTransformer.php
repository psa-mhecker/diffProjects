<?php

namespace PsaNdp\MappingBundle\Transformers;

use Doctrine\Common\Collections\ArrayCollection;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Data transformer for Pc16Verbatim block
 */
class Pc16VerbatimDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    const FORMAT_STANDARD = 111;
    const FORMAT_MOBILE = 85;


    /**
     *  Fetching data slice Verbatim (pc16)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {

        $format = self::FORMAT_STANDARD;
        if($isMobile){
            $format =self::FORMAT_MOBILE ;
        }
        $data['verbatimListItem'] = $this->getVerbatimListData($dataSource['verbatimList'], $format);

        return  array( 'slicePC16' => $data);
    }
    /**
     * Return data array for list of Verbatim
     *
     * @param ArrayCollection                  $verbatimeList
     *
     * @param                                  $format
     * @return array
     */
    private function getVerbatimListData(ArrayCollection $verbatimeList, $format)
    {
        $result = [];

        foreach ($verbatimeList as $verbatim) {
            $img = ['src' => '', 'alt' => ''];
            if ($verbatim->getMedia() !== null) {
                $img = array(
                    'src' => $this->mediaServer . $verbatim->getMedia()->getMediaPathWithFormat($format),
                    'alt' => $verbatim->getMedia()->getMediaAlt()
                );
            }
            $helper = $this->getHelper('cta');
            $helper->setPrefixTitle($verbatim->getPageZoneMultiLabel());
            $via['ctaList'] = $helper->getCtaData($verbatim->getCtaReferences());

            $result[] = array(
                'img' => $img,
                'infoPerson' => $verbatim->getPageZoneMultiTitre(),
                'text' => $verbatim->getPageZoneMultiText(),
                'via' => $via
            );
        }

        return $result;
    }
}
