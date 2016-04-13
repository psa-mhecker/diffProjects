<?php
/**
 * Classe avec des méthode commune du BO
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author Maillot Sébastien <sebastien.maillot@businessdecision.com>
 * @since 28/06/2013
 */

require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Cms/Page/Module.php');

abstract class Cms_Page_Citroen extends Cms_Page_Module
{
    /**
     * 
     * @param Pelican_Controller $controller
     * @return string
     */
    
    public static function beforeRender(Pelican_Controller $controller, $bPageGenerale)
    {
        if($bPageGenerale == false){
            return self::addPerso($controller);
        }
    }
    
    /**
     * 
     * @param Pelican_Controller $controller
     * @return string
     */
    
    public static function addPerso(Pelican_Controller $controller, $bGeneralZone = false)
    {
        if($bGeneralZone == false){
            $return = $controller->oForm->createHidden($controller->multi . "ZONE_PERSO", $controller->zoneValues['ZONE_PERSO']);
            $aQueryParams = array(
                'ztid'=>$controller->zoneValues['ZONE_TEMPLATE_ID'],
                'zid'=>$controller->zoneValues['ZONE_ID'],
                'area'=>$controller->zoneValues['AREA_ID'],
                'pid'=>$controller->zoneValues['PAGE_ID'],
                'pv'=>$controller->zoneValues['PAGE_VERSION'],
                'multi'=>$controller->multi,
                'general'=>$bGeneralZone
            );
        }else{
               $aQueryParams = array(
                'tpid'=>$controller->values['TEMPLATE_PAGE_ID'],
                'expand'=>$controller->values['PAGE_TYPE_EXPAND'],
                'pid'=>$controller->values['PAGE_ID'],
                'pv'=>$controller->values['PAGE_VERSION'],
                'general'=>$bGeneralZone
            );
             //$sRel = 'pid='.$controller->values['PAGE_ID'].'&tpid='.$controller->values['TEMPLATE_PAGE_ID'].'&general='.$bGeneralZone.'&expand='.$controller->values['PAGE_TYPE_EXPAND'];
        }
        $sRel = http_build_query($aQueryParams);
        $return .= '</table>
                        <div style="float:right;width:30%;padding:20px 5px;text-align:right">
                            <a class="persoDialog" href="#" rel="'.$sRel.'">'.t('GESTION_PERSONNALISATION').'</a>
                        </div>
                    
                    <table width="100%" border="0">';
        return $return;
    }

}
