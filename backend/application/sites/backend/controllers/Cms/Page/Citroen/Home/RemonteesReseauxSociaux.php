<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_Home_RemonteesReseauxSociaux extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return .= Backoffice_Form_Helper::getFormAffichage($controller, true, false);
        $aListeReseauxSociaux[] = array('champ' => "ZONE_TITRE2", 'type' => "FACEBOOK");
        $aListeReseauxSociaux[] = array('champ' => "ZONE_TITRE3", 'type' => "YOUTUBE");
        $aListeReseauxSociaux[] = array('champ' => "ZONE_TITRE4", 'type' => "TWITTER");
        $aListeReseauxSociaux[] = array('champ' => "ZONE_TITRE5", 'type' => "INSTAGRAM");
        foreach ($aListeReseauxSociaux as $rs) {
            $return .= Backoffice_Form_Helper::getFormReseauSocial($controller, $rs['type'], $rs['champ']);
        }

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
    }
}
