<?php
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Zone/Cta/Hmvc.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Multi/Zone/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Multi/Zone/Cta/Hmvc.php';

/**
 * Factory de CTA.
 *
 * @author David Moate <david.moate@businessdecision.com>
 *
 * @since 02/03/2015
 */
class Ndp_Cta_Factory
{

    private static $context = 'Zone';

    /**
     *
     * @param string $context  (Zone / Content)
     */
    public function setContext($context = 'Zone')
    {
        self::$context = $context;
    }

    /**
     *
     */
    public function setDefaultContext()
    {
        self::setContext();
    }

    /**
     * Créer une instance d'un type de cta
     * @param int $typeCta
     * @param bool $isZoneDynamique
     *
     * @return Ndp_Content_Version_Cta_Hmvc|Ndp_Content_Version_Cta_Simple|Ndp_Page_Multi_Zone_Cta|Ndp_Page_Multi_Zone_Cta_Hmvc|Ndp_Page_Zone_Cta|Ndp_Page_Zone_Cta_Hmvc|Ndp_Page_Zone_Multi_Cta
     */
    public static function getInstance($typeCta = Ndp_Cta::SIMPLE, $isZoneDynamique = null)
    {
        if ($isZoneDynamique === null) {
            $isZoneDynamique = Ndp_Cta::isZoneDynamique(Pelican_Db::$values['ZONE_TEMPLATE_ID']);
        }
        switch ($typeCta) {
            case Ndp_Cta::SIMPLE:

                $instance = new Ndp_Page_Zone_Cta();
                if ($isZoneDynamique) {
                    $instance = new Ndp_Page_Multi_Zone_Cta();
                }
                if (self::$context == 'Content') {
                    $instance = new Ndp_Content_Version_Cta_Simple();
                }
                break;
            case Ndp_Cta::HMVC:
                $instance = new Ndp_Page_Zone_Cta_Hmvc();
                if ($isZoneDynamique) {
                    $instance = new Ndp_Page_Multi_Zone_Cta_Hmvc();
                }
                if (self::$context == 'Content') {
                    $instance = new Ndp_Content_Version_Cta_Hmvc();
                }
                $instance->setIsMulti(true);
                break;
            case Ndp_Cta::SIMPLE_INTO_MULTI_HMVC:
                $instance = new Ndp_Page_Zone_Multi_Cta();
                if ($isZoneDynamique) {
                    $instance = new Ndp_Page_Multi_Zone_Multi_Cta();
                }
                if (self::$context == 'Content') {
                    //$instance = new Ndp_Content_Version_Multi_Cta();
                }
                break;
            case Ndp_Cta::HMVC_INTO_CTA:
                $instance = new Ndp_Page_Zone_Cta_Cta();
                if ($isZoneDynamique) {
                    $instance = new Ndp_Page_Multi_Zone_Cta_Cta();
                }
                if (self::$context == 'Content') {
                    $instance = new Ndp_Content_Version_Cta_Cta();
                }
                 break;
            case Ndp_Cta::HMVC_INTO_CTA_HMVC:
                //  $instance = new Ndp_Page_Zone_Cta_Cta_Hmvc();
                if ($isZoneDynamique) {
                    //$instance = new Ndp_Page_Multi_Zone_Cta_Cta_Hmvc();
                }
                break;
        }

        return $instance;
    }
}
