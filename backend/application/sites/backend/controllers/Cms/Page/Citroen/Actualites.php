<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';

/**
 *
 */
class Cms_Page_Citroen_Actualites extends Cms_Page_Citroen
{
    static public $contents = array(
        'CONTENT_ID_1',
        'CONTENT_ID_2',
        'CONTENT_ID_3',
    );

    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi.'ZONE_TITRE', t('TITRE'), 255, '', true, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75);
        $return .= $controller->oForm->showSeparator();

        $return .= self::addVisuel($controller);

        return $return;
    }

    /**
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        Backoffice_Form_Helper::saveFormAffichage();
        if (1 == Pelican_Db::$values['FORMAT_AFFICHAGE']) {
            unset(Pelican_Db::$values['CONTENT_ID_2']);
            unset(Pelican_Db::$values['CONTENT_ID_3']);
        }
        if (2 == Pelican_Db::$values['FORMAT_AFFICHAGE']) {
            unset(Pelican_Db::$values['CONTENT_ID_1']);
        }

        self::$con          = Pelican_Db::getInstance();
        self::deleteContents(self::$contents);
        $DB_VALUES          = Pelican_Db::$values;
        parent::save();
        Pelican_Db::$values = $DB_VALUES;
        if (Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE) {
            self::saveContents(self::$contents);
        }
    }

    /*
     *
     * @param Pelican_Controller $controller
     *
     * @return type
     */
    public static function addVisuel(Pelican_Controller $controller)
    {
        $bind     = self::getDefaultBinding($controller);
        $contents = self::getAllContents($bind);
        $return   = '';
        $formats  = array(
            1 => t('GRAND_VISUEL'),
            2 => t('DEUX_TIERS_UN_TIERS'),
        );
        $format   = count($contents);

        $return .= $controller->oForm->createRadioFromList($controller->multi.'FORMAT_AFFICHAGE', t('FORMAT_AFFICHAGE'), $formats, $format, true, $controller->readO, 'h');

        foreach ($formats as $format => $label) {
            $display = 'display:none;';
            $class   = ' isNotRequired';
            if (count($contents) == $format) {
                $display = '';
                $class   = '';
            }

            $return .= '</tbody><tbody id="container_'.$format.'_actu" style="'.$display.'" class="container_actu'.$class.'">';
            if (1 == $format) {
                $return .= $controller->oForm->createContentFromList($controller->multi.'CONTENT_ID_1', t('ACTU_GRAND_VISUEL'), $contents['CONTENT_ID_1'], true, $controller->readO, '1', 200, false, true, 8, false, '', 'grand_visuel');
            }
            if (2 == $format) {
                $return .= $controller->oForm->createContentFromList($controller->multi.'CONTENT_ID_2', t('ACTU_2/3'), $contents['CONTENT_ID_2'], true, $controller->readO, '1', 200, false, true, 8, false, '', '16_9');
                $return .= $controller->oForm->createContentFromList($controller->multi.'CONTENT_ID_3', t('ACTU_1/3'), $contents['CONTENT_ID_3'], true, $controller->readO, '1', 200, false, true, 8, false, '', '4_3');
            }
        }
        $return .= '</tbody><tbody>';
        $return .= self::addJs($controller);

        return $return;
    }

    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function addJs(Pelican_Controller $controller)
    {
        $jsText = '
            console.log("pelican cache");
                $("input[name=\"'.$controller->multi.'FORMAT_AFFICHAGE\"]").on("click",function(){

                    var $input = $(this);
                    $(".container_actu").hide().addClass("isNotRequired");
                    $("#container_"+$input.val()+"_actu").show().removeClass("isNotRequired");
                })
                ';
        $return = '';
        $return .= Pelican_Html::script(array(type => 'text/javascript'), $jsText);

        return $return;
    }
}
