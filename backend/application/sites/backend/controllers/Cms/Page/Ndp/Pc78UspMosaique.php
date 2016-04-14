<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';

/**
 *
 */
class Cms_Page_Ndp_Pc78UspMosaique extends Cms_Page_Ndp
{

    const MULTI_TYPE = 'USP_MOSAIQUE';
    const RATIO_ANCRE_DEFAULT = 'NDP_RATIO_LARGE_RECTANGLE:2699x764';
    const RATIO_ANCRE_1 = 'NDP_RATIO_SQUARE_1_1:1318x1318';
    const RATIO_ANCRE_2 = 'NDP_RATIO_SQUARE_1_1:624x624';
    const RATIO_ANCRE_3 = 'NDP_RATIO_SQUARE_1_1:624x624';
    const RATIO_ANCRE_4 = 'NDP_RATIO_SQUARE_1_1:1318x1318';
    const RATIO_ANCRE_5 = 'NDP_RATIO_SMALL_RECTANGLE_15_7:1318x627';
    
    const MULTI_MAX = 6;
    const FIRST_TEMPLATE = "NDP_TEMPLATE_DESKTOP";
    const SECOND_TEMPLATE = "NDP_TEMPLATE_MOBILE";
    const FIRST_TEMPLATE_PICTO = "Template-desktop.png";
    const SECOND_TEMPLATE_PICTO = "Template-mobile.png";
    const TEMPLATE_SEPARATOR_PICTO = "blank_16_16";

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        $form = self::createFormHeader($controller);
        $form .= self::createFormMulti($controller);
        $form .= self::getVerificationJs($controller);

        unset($_SESSION[APP]['PAGE_VERSION']);
        unset($_SESSION[APP]['PC78_ORDER']);
        unset($_SESSION[APP]['PC78_AREA_ID']);

        return $form;
    }

    public function createFormHeader(Pelican_Controller $controller)
    {
        $form = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $form .= $controller->oForm->createInput($controller->multi.'ZONE_TITRE', t('TITRE'), 60, "", false, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75, false, "", "text", [], false, self::getInfoBulle("NDP_MSG_PRECO_UPPERCASE_TITLE"), "60 ".t('NDP_MAX_CAR'));
        $optionsOfHeader = array(
            'infoBulle' => self::getInfoBulle("NDP_INFO_SLICE_WILL_SHOW_IN_POPIN"),
            'labels' => array(
                t(self::FIRST_TEMPLATE) => self::FIRST_TEMPLATE_PICTO,
                '' => self::TEMPLATE_SEPARATOR_PICTO,
                t(self::SECOND_TEMPLATE) => self::SECOND_TEMPLATE_PICTO
            )
        );
        $form .= $controller->oForm->createComment(t('NDP_GALLERY'), $optionsOfHeader);

        return $form;
    }

    public function createFormMulti(Pelican_Controller $controller)
    {
        $pageId = (isset($controller->zoneValues['PAGE_ID'])) ? $controller->zoneValues['PAGE_ID'] : $_SESSION[APP]['PAGE_ID'];
        $_SESSION[APP]['PAGE_VERSION'] = self::getPageVersion($pageId);
        $_SESSION[APP]['PC78_ORDER'] = (isset($controller->zoneValues['ZONE_ORDER'])) ? $controller->zoneValues['ZONE_ORDER'] : 1;
        $_SESSION[APP]['PC78_AREA_ID'] = (isset($controller->pageValues['TEMPLATE_PAGE_ID'])) ? self::getDynamicAreaIdByTemplateId($controller->pageValues['TEMPLATE_PAGE_ID']) : $controller->pageValues['AREA_ID'];
        $isZoneDynamique = Ndp_Multi::isZoneDynamique($controller->zoneValues['ZONE_TEMPLATE_ID']);
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC, $isZoneDynamique);
        $ancresVisuelsValues = $multi->setMultiType(self::MULTI_TYPE)
            ->hydrate($controller->zoneValues)
            ->getValues();
        foreach ($ancresVisuelsValues as $key => $ancreVisuelValues) {
            $ancresVisuelsValues[$key]['PAGE_ID'] = $controller->zoneValues['PAGE_ID'];
            $ancresVisuelsValues[$key]['PAGE_VERSION'] = $controller->zoneValues['PAGE_VERSION'];
            $ancresVisuelsValues[$key]['CURRENT_UID'] = $controller->zoneValues['UID'];
        }

        $form = $controller->oForm->createMultiHmvc(
            $controller->multi.self::MULTI_TYPE,
            "",
            array(
                "path" => __FILE__,
                "class" => __CLASS__,
                "method" => "addAncreVisuel"
            ),
            $ancresVisuelsValues,
            self::MULTI_TYPE,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            array(5, 6),
            true,
            true,
            $controller->multi.self::MULTI_TYPE,
            "values",
            "multi",
            "2",
            t('NDP_ADD_VISUEL'),
            "",
            false,
            ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]
        );

        return $form;
    }

    /**
     * 
     *  @param Pelican_Controller $controller
     * 
     *  @return string
     */
    public function getVerificationJs(Pelican_Controller $controller)
    {
        $js = ' var multi = "'.$controller->multi.self::MULTI_TYPE.'";
          var field = "_PAGE_ZONE_MULTI_VALUE";
          for (position = 0; position < '.self::MULTI_MAX.'; position++) {
            var ancre =  $("#"+multi + position + field);
            for(sousPosition = position; sousPosition < '.self::MULTI_MAX.'; sousPosition++ ) {
		if (position != sousPosition) {
                    var sousAncre =  $("#"+multi + sousPosition + field);
                    if(ancre.val() == sousAncre.val()) {
                        alert("'.t('NDP_MSG_TRANCHE_UNIQUE').'");
                        fwFocus(eval(multi + sousPosition + field));
                           
                        return false;
                    }
		}
            }
         }';

        return $controller->oForm->createJS($js);
    }

    /**
     *
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {       
        parent::save();
        $saved = Pelican_Db::$values;
        $multi = Ndp_Multi_Factory::getInstance(Ndp_Multi::HMVC);
        $multi->setMultiType(self::MULTI_TYPE)
            ->setMulti($controller->multi)
            ->delete()
            ->save();
        Pelican_Db::$values = $saved;
    }

    /**
     *
     * @param Ndp_Form $form
     * @param array    $values
     * @param boolean  $readO
     * @param string   $multi
     *
     * @return string
     */
    public static function addAncreVisuel(Ndp_Form $form, $values, $readO, $multi)
    {

        $listValues = self::getValuesListTranches(
                self::getBindListTranche($values['PAGE_ID'], $values['PAGE_VERSION'], $values['CURRENT_UID'])
        );
        
        $ratio=self::RATIO_ANCRE_DEFAULT;
        if(( $values['CPT_POS_MULTI'] !==null ) && constant('self::RATIO_ANCRE_'.((int)$values['CPT_POS_MULTI']+1)) !== null){
            $ratio =constant('self::RATIO_ANCRE_'.((int)$values['CPT_POS_MULTI']+1));
        }
        
        $formMulti = $form->createMedia($multi.'MEDIA_ID', t('NDP_VISUEL'), true, 'image', '', $values['MEDIA_ID'], (Cms_Page_Ndp::isTranslator() || $readO), true, false, $ratio);
        $formMulti .= $form->createInput($multi.'PAGE_ZONE_MULTI_LABEL', t('NDP_LABEL'), 50, '', true, $values['PAGE_ZONE_MULTI_LABEL'], $readO, 60, false, "", "text", array(), false, array('isIcon' => true, 'message' => t('NDP_MSG_PRECO_UPPERCASE_TITLE')), "60 ".t('NDP_LIMIT_LIB'));
        $saveStyleVal = $form->sStyleVal;
        //ajout une classe CSS pour permettre la mise à jour du select avec le JQUERY (script.js : zoneModule.PC78 )
        $form->sStyleVal.=' NDP_ASSOCIATED_SLICE78 '.'_NDP_ASSOCIATED_SLICE78_'.$values['CURRENT_UID'];
        $formMulti .= $form->createComboFromList($multi.'PAGE_ZONE_MULTI_VALUE', t('NDP_ASSOCIATED_SLICE'), $listValues, $values['PAGE_ZONE_MULTI_VALUE'], true, $readO, 1, false, false, false);
        $form->sStyleVal = $saveStyleVal;

        return $formMulti;
    }

    /**
     *
     * @param int $pageId
     * @param int $pageVersion
     * @param string $uid
     *
     * @return array
     */
    public static function getBindListTranche($pageId, $pageVersion, $uid)
    {
        $connection = Pelican_Db::getInstance();
        $bind = [
            ':PAGE_ID' => $pageId,
            ':PAGE_VERSION' => $pageVersion,
            ':MULTI_ZONE_UID' => $connection->strToBind($uid)
        ];

        return $bind;
    }

    /**
     * @return string
     */
    public static function getSqlListTranches()
    {
        $slicesToMatch = join(',', Pelican::$config['ZONE_MEDIA_USP_PC78']);
        $sqlData = "SELECT DISTINCT  CONCAT(pab.PERMANENT_ID) AS id, pab.ZONE_ORDER, zt.ZONE_TEMPLATE_LABEL AS lib
                    FROM #pref#_zone z
                    INNER JOIN #pref#_page_areas_blocks pab ON z.ZONE_ID = pab.ZONE_ID
                    INNER JOIN #pref#_zone_template zt ON zt.ZONE_ID = z.ZONE_ID
                    INNER JOIN #pref#_area ar ON ar.AREA_ID = zt.AREA_ID
                    WHERE pab.PAGE_ID =:PAGE_ID
                    AND pab.AREA_ID = ".$_SESSION[APP]['PC78_AREA_ID']."
                    AND pab.ZONE_ID IN (".$slicesToMatch.")
                    AND pab.ZONE_ORDER >= ".$_SESSION[APP]['PC78_ORDER']."
                    AND pab.PAGE_VERSION =:PAGE_VERSION
                    AND pab.TEMPLATE_PAGE_ID = zt.TEMPLATE_PAGE_ID
                    AND pab.MULTI_ZONE_UID != :MULTI_ZONE_UID
                    ORDER BY pab.TEMPLATE_PAGE_AREA_ORDER, pab.ZONE_ORDER";

        return $sqlData;
    }

    /**
     * @param $bind
     * 
     * @return array
     */
    public static function getValuesListTranches($bind)
    {
        $connection = Pelican_Db::getInstance();
        $sqlData = $connection->queryTab(self::getSqlListTranches(), $bind);
        $valuesList = array();
        foreach ($sqlData as $row) {
            $valuesList[$row['id']] = 'N°'.$row['ZONE_ORDER'].' '.t($row['lib']);
        }
        return $valuesList;
    }
}
