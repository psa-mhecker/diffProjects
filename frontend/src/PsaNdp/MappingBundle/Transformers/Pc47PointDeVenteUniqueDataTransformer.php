<?php

namespace PsaNdp\MappingBundle\Transformers;

/**
 * Data transformer for Pc47PointDeVenteUnique block
 */
class Pc47PointDeVenteUniqueDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     *  Fetching data slice Point de Vente Unique (pc47)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        // Titles and contact informations
        $result = array(
            'pdvUniqueTitle' => $dataSource['title'],
            'pdvUniqueTabs' => array(
                array('title' => $dataSource['contactTitle'], 'url' => '#', 'selected' => 'selected'),
                array('title' => $dataSource['serviceTitle'], 'url' => '#')
            ),
            'pdvUniqueSubtitle' => $dataSource['posName'],
            'pdvUniqueAdr' => $dataSource['posAdress'],
            // @todo translate 'tel string'
            'pdvUniqueTel' => 'Tel . ' . $dataSource['posPhone'],
        );

        // Schedule
        $result['pdvUniqueTimesheet'] = $this->getScheduleSmartyData($dataSource['schedule']);

        // VCF file
        if (isset($dataSource['contactVcfTitle'])) {
            $result['pdvUniqueLink'] = array(
                'title' => $dataSource['contactVcfTitle'],
                'url'   => $dataSource['vcfUrl']
            );
        }

        // Closing Days
        // To ignore. Not to be displayed in FO for now (cf Specs)

        // Promotions
        $result = array_merge($result, $this->getPromotionsSmartyData($dataSource['promotionTitle'], $dataSource['promotions']));

        // Services
        $result['pdvUniqueServ'] = $dataSource['services'];

        // Ctas
        $result = array_merge($result, $this->getCtasDesktopSmartyData($dataSource['ctas']));

        return array('slicePC47' => $result);
    }

    /**
     * Data Transformer for Desktop Point of sale
     * @todo translation for string
     * @todo missing value in the template : back button, google map, Name & Address, Ctas
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function getPointOfSaleSmartyMobileData(array $dataSource)
    {
        $result = array(
            //@todo translation
            'backButton' => 'retour au menu',

            'dealerTitle' => $dataSource['posName'],
            'dealerAddress' => $dataSource['posAdress'],

            'pdvUniqueTabs' => array(
                // Schedule
                array(
                    'title' => $dataSource['mobileScheduleTitle'],
                    'pdvUniqueTimesheet' => $this->getScheduleSmartyData($dataSource['schedule'])
                ),
                // Services
                array(
                    'title' => $dataSource['mobileServicesTitle'],
                    'pdvUniqueServ' => $dataSource['services']
                )
            )
        );

        // VCF file
        $result['pdvUniqueDownload'] = '';
        if (isset($dataSource['contactVcfTitle'])) {
            // @todo vcf can be desactivated and LINK should be added
            $result['pdvUniqueDownload'] = $dataSource['contactVcfTitle'];
        }

        // Google Map
        $result['mapDealerInfo'] = $dataSource['googleMapUrl'];

        // Ctas
        $result = array_merge($result, $this->getCtasMobileSmartyData($dataSource['ctas'], $dataSource['ctasMissingOnePicto']));

        return array('slicePC47' => $result);
    }

    /**
     *
     * @param array $weekSchedule
     *
     * @return array
     */
    private function getScheduleSmartyData(array $weekSchedule = null)
    {
        $result = [];

        if ($weekSchedule !== null) {
            // @todo translation jour
            $dayName = ['1' => 'Lundi', '2' => 'Mardi', '3' => 'Mercredi', '4' => 'Jeudi',
                '5' => 'Vendredi', '6' => 'Samedi', '7' => 'Dimanche',];

            foreach ($weekSchedule as $key => $daySchedule) {
                $newDaySchedule = [];
                $newDaySchedule['days'] = $dayName[$key];
                $newDaySchedule['time'] = $this->getDayScheduleString($daySchedule['1O'], $daySchedule['1C'], $daySchedule['2O'], $daySchedule['2C']);

                $result[] = $newDaySchedule;
            }
        }

        return $result;
    }

    /**
     *
     * @param string $openAM
     * @param string $closeAM
     * @param string $openPM
     * @param string $closePM
     *
     * @return string
     */
    private function getDayScheduleString($openAM, $closeAM, $openPM, $closePM)
    {
        $result = '';
        // @todo translation close message
        $closeMsg = 'Fermé';

        $hours = [];
        $morning = $this->getOpenCloseTimeString($openAM, $closeAM);
        if ($morning !== null ) {
            $hours[] = $morning;
        }
        $afternoon = $this->getOpenCloseTimeString($openPM, $closePM);
        if ($morning !== null ) {
            $hours[] = $afternoon;
        }

        if (count($hours) === 1) {
            $result = $hours[0];
        }
        if (count($hours) >= 2) {
            $result = implode('   ', $hours);
        }

        if ($result === '') {
            $result = $closeMsg;
        }

        return $result;
    }

    /**
     *
     * @param string $open
     * @param string $close
     *
     * @return null|string
     */
    private function getOpenCloseTimeString($open, $close)
    {
        $result = null;

        if ($open !== null && $open !== '') {
            $result = str_replace('h00', 'h', date('G\hi', strtotime($open)));
        }
        if ($close !== null && $close !== '') {
            $closeTime = str_replace('h00', 'h', date('G\hi', strtotime($close)));
            $result = $result . '-' . $closeTime;
        }

        return $result;
    }

    /**
     *
     * @param string $promotionsTitle
     * @param array  $promotions
     *
     * @return array
     */
    private function getPromotionsSmartyData($promotionsTitle, array $promotions)
    {
        $result = [];

        if (count($promotions) > 0) {
            // set promotions title
            $result['pdvUniquePromo'] = $promotionsTitle;

            // set promotions list data
            $result['pdvUniquePromoList'] = $promotions;
        }

        return $result;
    }

    /**
     * @param array $ctas
     *
     * @return array
     */
    private function getCtasDesktopSmartyData(array $ctas)
    {
        $result = [];

        // @todo pdvUniqueCTA2 doit être optionel sur ltemplate Isobar
        $result['pdvUniqueCTA'] = array('url' => '', 'title' => '', 'target' => '');
        $result['pdvUniqueCTA2'] = array('url' => '', 'title' => '', 'target' => '');

        if (isset($ctas[0])) {
            $result['pdvUniqueCTA'] = $ctas[0];
        }
        if (isset($ctas[1])) {
            $result['pdvUniqueCTA2'] = $ctas[1];
        }

        return $result;
    }


    /**
     * @param array $ctas
     * @param bool  $ctasMissingOnePicto
     *
     * @return array
     */
    private function getCtasMobileSmartyData(array $ctas, $ctasMissingOnePicto)
    {
        $result = [];

        $ctaList = [];
        foreach ($ctas as $index => $cta) {
            $newCta = [];
            $newCta['label'] = $cta['title'];
            $newCta['link'] = $cta['url'];

            //@todo picto can be optionel
            $newCta['img'] = '';        //init to delete
            $newCta['imgActive'] = '';
            if (!$ctasMissingOnePicto && isset($cta['mediaPath'])) {
                $newCta['img'] = $cta['mediaPath'];
                $newCta['imgActive'] = $cta['mediaPath'];
            }

            $ctaList['cta' . ($index + 1)] = $newCta;
        }

        if (2 === count($ctas)) {
            $result['cta_two']['cta'] = $ctaList;
        }
        if (4 === count($ctas)) {
            $result['cta_four']['cta'] = $ctaList;
        }

        return $result;
    }
}
