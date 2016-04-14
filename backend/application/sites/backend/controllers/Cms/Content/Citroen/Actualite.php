<?php
/**
 *
 */
class Cms_Content_Citroen_Actualite extends Cms_Content_Module
{
    public static $decacheBack        = array(
        array('Frontend/Citroen/Actualites/Detail',
            array('CONTENT_ID', 'SITE_ID', 'LANGUE_ID'),
        ),
        array('Frontend/Citroen/Actualites/PageClearUrlByActu',
            array('PAGE_ID', 'SITE_ID', 'LANGUE_ID'),
        ),
        array('Frontend/Citroen/Actualites/Pager',
            array('PAGE_ID'),
        ),
        array('Frontend/Citroen/Actualites/Liste',
            array('PAGE_ID', 'SITE_ID', 'LANGUE_ID'),
        ),
        array('Frontend/Citroen/Home/Actualites',
            array('SITE_ID', 'LANGUE_ID'),
        ),
    );
    public static $decachePublication = array(
        array('Frontend/Citroen/Actualites/Detail',
            array('CONTENT_ID', 'SITE_ID', 'LANGUE_ID'),
        ),
        array('Frontend/Citroen/Actualites/PageClearUrlByActu',
            array('PAGE_ID', 'SITE_ID', 'LANGUE_ID'),
        ),
        array('Frontend/Citroen/Actualites/Pager',
            array('PAGE_ID'),
        ),
        array('Frontend/Citroen/Actualites/Liste',
            array('PAGE_ID', 'SITE_ID', 'LANGUE_ID'),
        ),
        array('Frontend/Citroen/Home/Actualites',
            array('SITE_ID', 'LANGUE_ID'),
        ),
    );

    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        $return = self::addVisuel($controller);

        $return .= $controller->oForm->createMedia('MEDIA_ID2', t('VISUEL_SEIZE_NEUVIEME_MOBILE'), true, 'image', '', $controller->values['MEDIA_ID2'], $controller->readO, true, false, '16_9');
        $return .= $controller->oForm->createInput('CONTENT_TITLE', t('TITRE'), 255, '', true, $controller->values['CONTENT_TITLE'], $controller->readO, 100, false, '', false);
        $return .= $controller->oForm->createTextArea('CONTENT_TEXT', t('DESCRIPTION'), true, $controller->values['CONTENT_TEXT'], 1000, $controller->readO, 2, 100, false, '', false);

        $cta     = array(
            1 => t('DESACTIVER'),
            2 => t('EDITOR_INTERNAL_EXTERNAL'),
        );
        $modeCta = 1;
        $style   = 'display:none';
        $class   = ' isNotRequired';
        if (!empty($controller->values['CONTENT_URL'])) {
            $modeCta = 2;
            $style   = '';
            $class   = '';
        }
        $return .= $controller->oForm->createRadioFromList('CAT_CHOICE', t('CTA'), $cta, $modeCta, true, $controller->readO, 'h');
        $return .= '</tbody><tbody id="container_cta" style="'.$style.'" class="'.$class.'">';
        $return .= $controller->oForm->createInput('CONTENT_URL', t('LIEN_WEB'), 255, 'internallink', true, $controller->values['CONTENT_URL'], $controller->readO, 100, false, '', false);
        $modeOuverture = array(
            '_self'  => t('SELF'),
            '_blank' => t('BLANK'),
        );
        if (empty($controller->values['CONTENT_CODE'])) {
            $controller->values['CONTENT_CODE'] = '_self';
        }
        $return .= $controller->oForm->createRadioFromList('CONTENT_CODE', t('CTA'), $modeOuverture, $controller->values['CONTENT_CODE'], true, $controller->readO, 'h');

        $return .= self::addJs();

        return $return;
    }

    /*
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function addVisuel(Pelican_Controller $controller)
    {
        $return  = '';
        $formats = array(
            'grand_visuel' => t('GRAND_VISUEL'),
            '16_9'         => t('VISUEL_SEIZE_NEUVIEME'),
            '4_3'          => t('VISUEL_QUATRE_TIER'),
        );
        $return .= $controller->oForm->createRadioFromList('CONTENT_CODE2', t('FORMAT_AFFICHAGE'), $formats, $controller->values['CONTENT_CODE2'], true, $controller->readO, 'h');

        foreach ($formats as $format => $label) {
            $display = 'display:none;';
            $class   = ' isNotRequired';
            if ($controller->values['CONTENT_CODE2'] == $format) {
                $display = '';
                $class   = '';
            }

            $return .= '</tbody><tbody id="media_id_container_'.$format.'" style="'.$display.'" class="container_visuel'.$class.'">';
            $return .= $controller->oForm->createMedia('MEDIA_ID_'.strtoupper($format), $label, true, 'image', '', $controller->values['MEDIA_ID'], $controller->readO, true, false, $format);
        }
        $return .= '</tbody><tbody>';

        return $return;
    }

    /**
     * @param Pelican_Controller $controller
     */
    public static function beforeSave(Pelican_Controller $controller)
    {
        self::preparSaveVisuel();
    }

    /**
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        self::preparSaveVisuel();
        parent::save($controller);
        foreach (self::$decachePublication as $valueCache) {
            Pelican_Cache::clean($valueCache[0]);
        }
    }

    /**
     * Move the value of selected visuel into the true media ID field.
     */
    public static function preparSaveVisuel()
    {
        $format                         = Pelican_Db::$values['CONTENT_CODE2'];
        Pelican_Db::$values['MEDIA_ID'] = Pelican_Db::$values['MEDIA_ID_'.strtoupper($format)];
    }

    /**
     * @return string
     */
    public static function addJs()
    {
        $jsText = '
                $("input[name=\"CONTENT_CODE2\"]").on("click",function(){
                    var $input = $(this);
                    $(".container_visuel").hide().addClass("isNotRequired");
                    $("#media_id_container_"+$input.val()).show().removeClass("isNotRequired");
                })

                $("input[name=\"CAT_CHOICE\"]").on("click",function(){
                    var $input = $(this);
                    if ($input.val() === "1") {
                        $("#container_cta").hide().addClass("isNotRequired");
                    } else {
                      $("#container_cta").show().removelass("isNotRequired");
                    }
                })
                ';
        $return = '';
        $return .= Pelican_Html::script(array(type => 'text/javascript'), $jsText);

        return $return;
    }
}
