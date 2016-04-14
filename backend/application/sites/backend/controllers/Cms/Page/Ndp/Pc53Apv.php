<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';


/**
 *
 */
class Cms_Page_Ndp_Pc53Apv extends Cms_Page_Ndp
{

    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();

        $return  = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $return .= $controller->oForm->createComboFromList(
            $controller->multi."ZONE_PARAMETERS",
            t('NDP_APV'),
            self::getValuesListApv(),
            $controller->zoneValues["ZONE_PARAMETERS"],
            true,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            1,
            false,
            '',
            true,
            false
        );

        $return .= $controller->oForm->createHR();

        $index = 1;
        $max = 2;
        foreach (self::getTypesLevels() as $level => $levelLabel) {

            $min = $max - $index;

            $return .= $controller->oForm->showSeparator();

            $return .= self::getLevelCta(
                $controller,
                $level,
                t($levelLabel).($index++),
                array($min, $max),
                2,
                false,
                [
                    'CTA' => [
                        'forceValues' => ['CTADisable' => false],
                        'CTA_READONLY' =>(Cms_Page_Ndp::isTranslator() || $controller->readO),
                        'noDragNDrop' => Cms_Page_Ndp::isTranslator()
                    ],
                    'numberLabel' => 'Colonne ',
                    'noSeparator' => false,
                    'needed' => true
                ]
            );
        }

        return $return;
    }

    /**
     * @param $bind
     *
     * @return array
     */
    public static function getValuesListApv()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
        $aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];

        $query = "SELECT
				ID as \"id\",
				LABEL as \"lib\"
				FROM
				#pref#_after_sale_services
				where
				SITE_ID = :SITE_ID
				AND LANGUE_ID = :LANGUE_ID
				ORDER BY ID";

        $sqlData = $oConnection->queryTab($query, $aBind);

        $listApv = array();
        foreach ($sqlData as $row) {
            $listApv[$row['id']] = $row['lib'];
        }
        return $listApv;
    }

    /**
     * getLevelsType
     *
     * @return array
     */
    public static function getTypesLevels()
    {
        return array('LEVEL1_' => 'NDP_LEVEL', 'LEVEL2_' => 'NDP_LEVEL');
    }

    /**
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        parent::save();

        foreach (self::getTypesLevels() as $type => $label) {
            $ctaHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
            $ctaHmvc->setCtaType($type.self::TYPE_CTA)
                ->setTypeCtaDropDown($type.self::TYPE_CTA_LD)
                ->delete() //suppression des anciens CTA (liste deroulante compris)
                ->save();
            $ctaLDHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
            $ctaLDHmvc->setCtaType($type.self::TYPE_CTA_LD)
                ->setCtaDropDown(true)
                ->save();


        }
    }
}
