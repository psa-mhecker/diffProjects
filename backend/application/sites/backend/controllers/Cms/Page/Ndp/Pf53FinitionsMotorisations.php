<?php
/**
 * Tranche PF53 - PF58 - Finitions et Motorisations
 *
 * @since 22/05/2015
 */
require_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';

class Cms_Page_Ndp_Pf53FinitionsMotorisations extends Cms_Page_Ndp
{
    const FINISHING = 1;
    const ENGINES = 2;
    const BOTH = 3;
    const COMPARISON_TABLE_DISABLED = 0;
    const FIELD_MODEL = "PAGE_GAMME_VEHICULE";

    /**
     * @var boolean
     */
    static protected $comparisonTableStatus;

    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        self::$comparisonTableStatus = self::getComparisonTableStatsus();

        $type = $controller->multi.'container_attribut';
        $type2 = $controller->multi.'container_ordre';
        if (empty($controller->zoneValues['ZONE_ATTRIBUT2'])) {
            $controller->zoneValues['ZONE_ATTRIBUT2'] = 0;
        }
        $return = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $return .= self::createSliceChoice($controller, $type, $type2);
        if (!empty($controller->zoneValues['ZONE_ATTRIBUT']) && !self::$comparisonTableStatus) {
            $return .= $controller->oForm->createComment(t('NDP_MSG_ACTIVATE_COMPARISON_TABLE'));
        }

        $return .= self::addHeadContainer(self::FINISHING, $controller->zoneValues['ZONE_ATTRIBUT'], $type);
        $return .= self::createChoiceFinishing($controller, self::FINISHING);
        $return .= self::addFootContainer();
        $return .= self::addHeadContainer(self::ENGINES, $controller->zoneValues['ZONE_ATTRIBUT'], $type);
        $return .= self::createChoiceFinishing($controller, self::ENGINES);
        $return .= self::addFootContainer();

        $return .= self::addHeadContainer(self::BOTH, $controller->zoneValues['ZONE_ATTRIBUT'], $type);

        $return .= self::createChoiceBoth($controller, $type2);
        $return .= self::addFootContainer();
        $flag = '';
        if (self::$comparisonTableStatus) {
            $flag = $controller->zoneValues['ZONE_ATTRIBUT'].'_'.$controller->zoneValues['ZONE_ATTRIBUT2'];
        }        
        $return .= self::addHeadContainer(self::BOTH.'_'.self::COMPARISON_TABLE_DISABLED, $flag, $type2);
        $return .= self::createChoiceOrder($controller, $type2);
        $return .= self::addFootContainer();
        $return .= $controller->oForm->createJS(self::getJsForCheckingModel());

        return $return;
    }

    public static function getJsForCheckingModel()
    {
        $js = "
            var model = $('#".self::FIELD_MODEL."');
            if (model.val() == '') {
                alert('".t('NDP_MSG_ERREUR_NO_MODEL')."');
                fwFocus(eval(".self::FIELD_MODEL."));

                return false;
            }";

        return $js;
    }

    /**
     *
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {

        $DB_VALUES = Pelican_Db::$values;
        self::$con = Pelican_Db::getInstance();

        foreach (self::getChoices() as $value => $label) {
            if (Pelican_Db::$values['ZONE_ATTRIBUT'] == $value) {
                Pelican_Db::$values['ZONE_ATTRIBUT2'] = Pelican_Db::$values['ZONE_ATTRIBUT2_'.$value];
            }
            unset(Pelican_Db::$values['ZONE_ATTRIBUT2_'.$value]);
        }
        parent::save();
        Pelican_Db::$values = $DB_VALUES;
    }

    /**
     * @return array
     */
    private static function getChoices()
    {
        return array(
            self::FINISHING => t('NDP_FINISHING'),
            self::ENGINES => t('NDP_ENGINES'),
            self::BOTH => t('NDP_FINISHING_AND_ENGINES')
        );
    }

    /**
     * @return bool
     */
    private static function getComparisonTableStatsus()
    {

        $sql = 'SELECT SHOW_COMPARISONCHART FROM #pref#_model_config WHERE LANGUE_ID= :LANGUE_ID AND SITE_ID=:SITE_ID';
        $bind = [];
        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $bind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];

        $res = self::$con->queryItem($sql, $bind);
        $returnValue = false;
        if (!empty($res)) {
            $returnValue = $res['SHOW_COMPARISONCHART'];
        }

        return $returnValue;
    }

    /**
     * @param Pelican_Controller $controller
     * @param string $type
     * @param string $type2
     *
     * @return mixed
     */
    private static function createSliceChoice(Pelican_Controller $controller, $type, $type2)
    {

        $display = self::getChoices();
        $jsContainerDisplay = self::addJsContainerRadioChoice($controller, $type, $type2);


        $return = $controller->oForm->createRadioFromList(
            $controller->multi.'ZONE_ATTRIBUT',
            t('AFFICHAGE'),
            $display,
            $controller->zoneValues['ZONE_ATTRIBUT'],
            true,
            $controller->readO,
            'h',
            false,
            $jsContainerDisplay
        );

        return $return;
    }

    /**
     * @param Pelican_Controller $controller
     * @param string $suffix
     *
     * @return mixed
     */
    private static function createChoiceFinishing(Pelican_Controller $controller, $suffix)
    {
        $choices = array(
            self::COMPARISON_TABLE_DISABLED => t('DISABLED'),
            $suffix => t('ENABLED'),
        );

        $return = $controller->oForm->createRadioFromList(
            $controller->multi.'ZONE_ATTRIBUT2_'.$suffix,
            t('NDP_LABEL_COMPARISON_TABLE'),
            $choices,
            $controller->zoneValues['ZONE_ATTRIBUT2'],
            false,
            !self::$comparisonTableStatus,
            'h',
            false
        );

        return $return;
    }

    /**
     * @param Pelican_Controller $controller
     * @param string $type
     *
     * @return mixed
     */
    private static function createChoiceBoth(Pelican_Controller $controller, $type)
    {
        $choices = array(
            self::COMPARISON_TABLE_DISABLED => t('DISABLED'),
            self::FINISHING => t('NDP_BY_FINISHING'),
            self::ENGINES => t('NDP_BY_ENGINES'),
        );

        $jsContainerDisplay = self::addJsContainerRadioOrder($controller, $type);
        $return = $controller->oForm->createRadioFromList(
            $controller->multi.'ZONE_ATTRIBUT2_'.self::BOTH,
            t('NDP_LABEL_COMPARISON_TABLE'),
            $choices,
            $controller->zoneValues['ZONE_ATTRIBUT2'],
            false,
            !self::$comparisonTableStatus,
            'h',
            false,
            $jsContainerDisplay
        );

        return $return;
    }


    /**
     * @param Pelican_Controller $controller
     *
     * @return mixed
     */
    private static function createChoiceOrder(Pelican_Controller $controller)
    {

        $display = array(
            self::FINISHING => t('NDP_FINISHING'),
            self::ENGINES => t('NDP_ENGINES'),
        );

        $return = $controller->oForm->createRadioFromList(
            $controller->multi.'ZONE_ATTRIBUT3',
            t('NDP_DISPLAY_FIRST'),
            $display,
            $controller->zoneValues['ZONE_ATTRIBUT3'],
            true,
            $controller->readO,
            'h',
            false
        );

        return $return;
    }

    /**
     *
     * @param Pelican_Controller $controller
     * @param string $type
     *
     * @return string
     */
    private static function addJsContainerRadioOrder(Pelican_Controller $controller, $type)
    {

        $js = 'onclick="
                    $(\'.'.$type.'\').hide();
                    var field1 = $(\'input[name=&quot;'.$controller->multi.'ZONE_ATTRIBUT&quot;]:checked\');
                    var field2 = $(\'input[name=&quot;'.$controller->multi.'ZONE_ATTRIBUT2_'.self::BOTH.'&quot;]:checked\');
                    var selectedRadio =  field1.val()+\'_\'+field2.val() ;

                    $(\'.'.$type.'_\' + selectedRadio).show();
                    $(\'.'.$type.'\').addClass(\'isNotRequired\');
                    $(\'.'.$type.'_\' + selectedRadio + \'\').removeClass(\'isNotRequired\');
                "';

        return $js;
    }

    /**
     *
     * @param Pelican_Controller $controller
     * @param string $type
     * @param string $type2
     *
     * @return string
     */
    private static function addJsContainerRadioChoice(Pelican_Controller $controller, $type, $type2)
    {

        $js = 'onclick="
                    $(\'.'.$type.'\').hide();
                    var selectedRadio =   $(this).val();
                    var field1 = $(\'input[name=&quot;'.$controller->multi.'ZONE_ATTRIBUT&quot;]:checked\');
                    var field2 = $(\'input[name=&quot;'.$controller->multi.'ZONE_ATTRIBUT2_'.self::BOTH.'&quot;]:checked\');


                    $(\'.'.$type.'_\' + selectedRadio).show();
                    $(\'.'.$type.'\').addClass(\'isNotRequired\');
                    $(\'.'.$type.'_\' + selectedRadio + \'\').removeClass(\'isNotRequired\');

                     $(\'.'.$type2.'\').hide();
                    var selectedRadio =  field1.val()+\'_\'+field2.val() ;

                    $(\'.'.$type2.'_\' + selectedRadio).show();

                    $(\'.'.$type2.'\').addClass(\'isNotRequired\');
                    $(\'.'.$type2.'_\' + selectedRadio + \'\').removeClass(\'isNotRequired\');

                "';

        return $js;
    }
}
