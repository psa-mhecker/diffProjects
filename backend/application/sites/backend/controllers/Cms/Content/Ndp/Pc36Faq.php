<?php

/**
 * Content - Faq
 *
 * @package Pelican_BackOffice
 * @subpackage Content
 * @author Laurent Franchomme <laurent.franchomme@businessdecision.com>
 * @since 02/06/2015
 */
class Cms_Content_Ndp_Pc36Faq extends Cms_Content_Module
{


    /**
     * render template
     */
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createInput(
          "CONTENT_TITLE2",
          t('NDP_QUESTION'),
          250,
          "",
          true,
          $controller->values["CONTENT_TITLE2"],
          $controller->read0,
          100
        );

        $oConnection = Pelican_Db::getInstance();

        $return .= $controller->oForm->createCheckBoxFromList('CONTENT_CODE2', t('NDP_FAQ_CHECKBOX_MOST_ASKED_QUESTION'), array(1 => ''), $controller->values['CONTENT_CODE2'], false, $controller->readO);

        $return .= $controller->oForm->createComboFromSql($oConnection,
           'CONTENT_CATEGORY_ID',
          t('FORM_FAQ_CAT'). ' 1',
          self::getSqlListFaqCategory(),
          $controller->values["CONTENT_CATEGORY_ID"],
          true,
          $controller->read0,
          "1",
          false,
          "",
          true,
          false,
          "",
          "",
          self::getBindListFaqCategory($oConnection)
        );

        $return .= $controller->oForm->createComboFromSql($oConnection,
          'CONTENT_SUB_CATEGORY_ID',
          t('FORM_FAQ_CAT'). ' 2',
          self::getSqlListFaqCategory(),
          $controller->values["CONTENT_SUB_CATEGORY_ID"],
          false,
          $controller->read0,
          "1",
          false,
          "",
          true,
          false,
          "",
          "",
          self::getBindListFaqCategory($oConnection)
        );

        $return .= $controller->oForm->createCheckBoxFromList('CONTENT_WEB', t('AFFICHAGE_WEB'), array(1 => ''), $controller->values['CONTENT_WEB'], false, $controller->readO);
        $return .= $controller->oForm->createCheckBoxFromList('CONTENT_MOBILE', t('AFFICHAGE_MOB'), array(1 => ''), $controller->values['CONTENT_MOBILE'], false, $controller->readO);

        $return .= $controller->oForm->createEditor('CONTENT_TEXT', t('NDP_ANSWER'), true, $controller->values['CONTENT_TEXT'], $controller->readO, true);

        if(!isset($controller->values['CONTENT_ID'])) {
            $controller->values['CONTENT_CODE3']=  true;
        }
        $return .= $controller->oForm->createCheckBoxFromList('CONTENT_CODE3', t('NDP_DISPLAY_SATISFACTION_QUESTION'), array(1 => ''), $controller->values['CONTENT_CODE3'], false, $controller->readO);

        return $return;
    }


    /**
     * get sql request for list faq category
     *
     * @return string sql
     *
     */
    public static function getSqlListFaqCategory()
    {
        $sql = "SELECT  CONTENT_CATEGORY_ID  AS id, CONTENT_CATEGORY_LABEL AS lib
                    FROM #pref#_content_category c

                    WHERE c.SITE_ID =:SITE_ID
                    AND c.LANGUE_ID =:LANGUE_ID
                    AND c.CONTENT_CATEGORY_CODE =:CONTENT_CATEGORY_CODE

                    ORDER BY c.CONTENT_CATEGORY_CODE";

        return $sql;
    }

    /**
     * get bind array for list faq category request
     *
     * @return array bind
     */
    public static function getBindListFaqCategory($oConnection)
    {
        $bind = array(':SITE_ID' => $_SESSION[APP]['SITE_ID'],
                      ':LANGUE_ID' => $_SESSION[APP]['LANGUE_ID'],
                      ':CONTENT_CATEGORY_CODE' => $oConnection->strToBind('FAQ_CAT')
             );

        return $bind;
    }
}
