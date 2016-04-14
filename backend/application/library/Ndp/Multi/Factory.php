<?php
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Zone/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Zone/Multi/Hmvc.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Multi/Zone/Multi/Hmvc.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Multi/Zone/Multi.php';

/**
 * Factory de CTA.
 *
 * @author David Moate <david.moate@businessdecision.com>
 *
 * @since 02/03/2015
 */
class Ndp_Multi_Factory 
{
    
    /**
     * Créer une instance de type de Multi
     * @param type $typeMulti
     * @param type $isZoneDynamique
     * @return string
     */
    public static function getInstance($typeMulti = Ndp_Multi::SIMPLE, $isZoneDynamique = null)
    {
        if ($isZoneDynamique === null) {
            $isZoneDynamique = Ndp_Multi::isZoneDynamique(Pelican_Db::$values['ZONE_TEMPLATE_ID']);
        }
        switch ($typeMulti)
        {
            case Ndp_Multi::SIMPLE:
                $instance = new Ndp_Page_Zone_Multi();
                if ($isZoneDynamique) {
                    $instance = new Ndp_Page_Multi_Zone_Multi();
                }
                break;
            case Ndp_Multi::HMVC:
                $instance = new Ndp_Page_Zone_Multi_Hmvc();                
                if ($isZoneDynamique) {
                    $instance = new Ndp_Page_Multi_Zone_Multi_Hmvc();
                }
                $instance->setIsMulti(true);
                break;
            case Ndp_Multi::CTA:
                if ($isZoneDynamique) {
                    $instance = ''; //@TODO
                }
                break;
            case Ndp_Multi::CONTENT:
                $instance = ''; //@TODO
                break;
        }

        return $instance;
    }
}

