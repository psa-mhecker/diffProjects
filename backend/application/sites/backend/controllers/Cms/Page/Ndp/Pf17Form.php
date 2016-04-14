<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';

/**
 *
 */
class Cms_Page_Ndp_Pf17Form extends Cms_Page_Ndp
{
    const TYPE_CONTENT = 7;

    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con     = Pelican_Db::getInstance();
        $return        = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $defaultValues = [];
        if (isset($controller->zoneValues['CONTENT_ID']) && $controller->zoneValues['CONTENT_ID'] != 0) {
            $tmpValues = self::getContentById($controller->zoneValues['CONTENT_ID']);
            $defaultValues[$tmpValues['ID']] = $tmpValues['TITLE'];
        }
        $return .= $controller->oForm->createContentFromList($controller->multi.'CONTENT_ID',
            t('NDP_CHOOSE_CONTENT'),
            $defaultValues, true,
            $controller->readO, '1', 200, false, true, self::TYPE_CONTENT);

        return $return;
    }
}
