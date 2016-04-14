<?php
/**
 * Tranche PN14 - ConfiShow
 *
 * @package Pelican_BackOffice
 * @subpackage Tranche
 * @author Benjamin Fouché <benjamin.fouche@businessdecision.com>
 * @since 22/05/2015
 */

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';

class Cms_Page_Ndp_Pn14NavigationConfiShow extends Cms_Page_Ndp
{
    const ENABLE = 1;
    const DISABLE = 0;

    /**
     * @param Pelican_Controller $controller
     *
     * @return string|void
     */
    public static function render(Pelican_Controller $controller)
    {
        $result = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));

        $displayTitle = array(
            self::DISABLE => t('NDP_DESACTIVE'),
            self::ENABLE  => t('NDP_ACTIVE')
        );

        if (empty($controller->zoneValues['ZONE_PARAMETERS'])) {
            $controller->zoneValues['ZONE_PARAMETERS'] = self::DISABLE;
        }

        $result .= $controller->oForm->createRadioFromList(
            $controller->multi.'ZONE_PARAMETERS',
            t('NDP_DISPLAY_PAGE_TITLE'),
            $displayTitle,
            $controller->zoneValues['ZONE_PARAMETERS'],
            true,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            'h',
            false
        );

        return $result;
    }
}

