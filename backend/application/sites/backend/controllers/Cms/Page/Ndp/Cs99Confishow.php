<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 *
 */
class Cms_Page_Ndp_Cs99Confishow extends Cms_Page_Ndp
{
    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));

        $return .=  $controller->oForm->createComboFromList($controller->multi.'ZONE_PARAMETERS',
            'Template', self::getListOfTemplates(),
            $controller->zoneValues['ZONE_PARAMETERS'], true, false, 1, false);

        return $return;
    }

    /**
     * @return array
     */
    private static function getListOfTemplates()
    {
        $result = [];
        $templateDir = dirname(Pelican::$config['DOCUMENT_INIT']).'/assets/patternlab/public/';
        $finder = new Finder();
        $files = $finder->name('*organism*confishow.html')->in($templateDir);

        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            $result[$file->getPathname()] = $file->getBasename();
        }

        return $result;
    }
}
