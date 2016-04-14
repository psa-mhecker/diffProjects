<?php

/**
 * Content - Formulaire
 *
 * @package Pelican_BackOffice
 * @subpackage Content
 * @author Joseph FRANCLIN <joseph.franclin.com>
 * @since 06/07/2015
 */
class Cms_Content_Ndp_Pf17Form extends Cms_Content_Module {

    const TYPE_FORM = "TYPE_FORM";
    const NO_TYPE = "NDP_NO_TYPE";
    const TYPE_PDV = "NDP_TYPE_PDV";
    const TYPE_CAR = "NDP_TYPE_CAR";
    const DISABLE = 0;
    const ENABLE = 1;
    const PUBLISHED = '4';
    const TYPE_CTA = "FORM_CTA";
    const CTA_MYPEUGEOT = 'CTA_MYPEUGEOT';

    /**
     * render template
     */
    public static function render(Pelican_Controller $controller) {
        $return = self::createContentCode($controller);
        $return .= self::createText($controller);
        $return .= self::getCtaAffichage($controller);
        $return .= self::createMyPeugeot($controller);

        if ($controller->values['STATE_ID'] === self::PUBLISHED) {
            $return .= $controller->oForm->createJS('return confirm("'.t('NDP_UPDATE_CONTENT_FORM').'");');
        }

        return $return;
    }

    public static function createContentCode(Pelican_Controller $controller) {
        /* Connexion à la bdd */
        $oConnection = Pelican_Db::getInstance();
        $return = '';
        $showInputVersion = true;
        /* Code instance de formulaire INCE */
        try {
            $codePaysById = Pelican_Cache::fetch('Ndp/CodePaysById');
            
            $service = Pelican_Application::getContainer()->get('bo_forms');            
            $instances = $service->setDefaultContext()->addContext('country',$codePaysById[$_SESSION[APP]['SITE_ID']])->getInstances();
            
            $values = [];
            
            $showInputVersion = empty($instances);
            
            if( false === $showInputVersion ){
                foreach ($instances as $instance) {
                    $values[$instance->instanceId] = $instance->instanceId . ' ' . $instance->instanceName;
                }

                $return .= $controller->oForm->createComboFromList('CONTENT_CODE', t('NDP_CODE_INSTANCE_FORM'), $values, $controller->values["CONTENT_CODE"], false);
                $return .= $controller->oForm->createComboFromList('CONTENT_TITLE13', t('NDP_CODE_INSTANCE_FORM_MOBILE'), $values, $controller->values["CONTENT_TITLE13"], false);
            }
        } catch (Exception $e) {
            $return .= $controller->oForm->createDescription(t('NDP_ERROR_WS_BO_FORMS_BO'));
        }
        
        if( true === $showInputVersion){
            $return .= $controller->oForm->createInput('CONTENT_CODE', t('NDP_CODE_INSTANCE_FORM'), 255, '', false, $controller->values['CONTENT_CODE'], $controller->readO, 44);
            $return .= $controller->oForm->createInput('CONTENT_TITLE13', t('NDP_CODE_INSTANCE_FORM_MOBILE'), 255, '', false, $controller->values['CONTENT_TITLE13'], $controller->readO, 44);
        }
        //validation si un des 2 champs code instance est rempli
       self::addJSValidator($controller);

        return $return;
    }

    /**
     * @param Pelican_Controller $controller
     */
    public static function addJSValidator(Pelican_Controller $controller){
        $controller->oForm->createJS(''
            . 'var bv_value = $("#CONTENT_CODE").val();'
            . 'var sv_value = $("#CONTENT_TITLE13").val();'
            . 'if( sv_value == "" && bv_value == ""){    alert("'.t('NDP_MSG_NEED_CODE_INSTANCE').'"); return false;}'
        );
    }

    /**
     *
     * @param Pelican_Controller $controller
     * @return string
     */
    public static function createText(Pelican_Controller $controller) {
        $return = $controller->oForm->createEditor('CONTENT_TEXT', t('NDP_REQUEST_ACCEPTED'), true, $controller->values['CONTENT_TEXT'], $controller->readO, true, '', '', 70, '');

        $option['infoBulle'] = array(
            'message' =>t('NDP_MSG_EMAIL_FORM'),
            'isIcon' => true
        );
        $return .= $controller->oForm->createEditor('CONTENT_TEXT2', t('NDP_CONFIRM_MAIL'), true, $controller->values['CONTENT_TEXT2'], $controller->readO, true, '', '', 70, '', $option);
        $return .= $controller->oForm->createEditor('CONTENT_SHORTTEXT', t('NDP_MORE_TEXT'), false, $controller->values['CONTENT_SHORTTEXT'], $controller->readO, true, '', '', 70);
        $return .= $controller->oForm->createLabel('', t('NDP_BO_ERROR_FORM_WS'));

        return $return;
    }

    /**
     *
     * @param Pelican_Controller $controller
     * @return string
     */
    public static function createMyPeugeot(Pelican_Controller $controller) {
        $return = $controller->oForm->createComment(t('NDP_MY_PEUGEOT'));
        $type = $controller->multi . self::TYPE_FORM;
        $js = self::addJsContainerRadio($type);
        $typAffichage = array(
            self::DISABLE => t('NDP_DESACTIVE'),
            self::ENABLE => t('NDP_ACTIVE')
        );
        if (empty($controller->values['CONTENT_CODE3'])) {
            $controller->values['CONTENT_CODE3'] = self::DISABLE;
        }
        $return .= $controller->oForm->createRadioFromList($controller->multi . 'CONTENT_CODE3', t('NDP_SHOW_CTA'), $typAffichage, $controller->values['CONTENT_CODE3'], true, $controller->readO, 'h', false, $js);

        $return .= self::addHeadContainer(self::DISABLE, $controller->values['CONTENT_CODE3'], $type);
        //disable
        $return .= self::addFootContainer();

        $return .= self::addHeadContainer(self::ENABLE, $controller->values['CONTENT_CODE3'], $type);
        $return .= $controller->oForm->createComment(t('NDP_GENERIC_VISUAL'));
        $return .= $controller->oForm->createMedia('MEDIA_ID', t("NDP_VISUEL"), true, "image", "", $controller->values['MEDIA_ID'], (Cms_Page_Ndp::isTranslator() || $controller->readO), true, false);
        $return .= $controller->oForm->createTextArea('CONTENT_SHORTTEXT2', t('NDP_DESC_MY_PEUGEOT'), true, $controller->values['CONTENT_SHORTTEXT2'], 200, $controller->readO, 3, 80, false, '', true);
        $return .= self::getCtaMulti($controller);
        $return .= self::addFootContainer();

        return $return;
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function getCtaAffichage(Pelican_Controller $controller) {
        Ndp_Cta_Factory::setContext('Content');
        $ctaMulti = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
        $ctaMulti->hydrate($controller->values);
        $ctaMulti->setIsMulti(true);
        $typeCta = self::TYPE_CTA;
        $ctaMulti->setCtaType($typeCta);
        $nom = t('NDP_CTA_AND_LIEN');
        $methode = 'addCtaMulti';
        $button = t('NDP_ADD_FORM_CTA_AND_LIEN');
        $valuesCta = $ctaMulti->getValues();
        $strLib = array(
            'multiTitle' => $nom,
            'multiAddButton' => $button
        );
        $return = $controller->oForm->createComment(t('NDP_OTHER_ASK'));
        $return .= $controller->oForm->createMultiHmvc(
            $typeCta,
            $strLib,
            array(
                "path" => __FILE__,
                "class" => __CLASS__,
                "method" => $methode,
            ),
            $valuesCta,
            $typeCta,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            array(1, 4),
            true,
            true,
            $typeCta,
            'values',
            'multi',
            "2",
            '',
            '',
            false,
            ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]
        );

        return $return;
    }

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function getCtaMulti(Pelican_Controller $controller) {
        Ndp_Cta_Factory::setContext('Content');
        $ctaMulti = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);
        $ctaMulti->hydrate($controller->values);
        $ctaMulti->setIsMulti(true);
        $typeCta = self::CTA_MYPEUGEOT;
        $ctaMulti->setCtaType($typeCta);
        $nom = t('NDP_CTA_AND_LIEN');
        $methode = 'addCtaSimple';
        $button = t('NDP_ADD_FORM_CTA_AND_LIEN');
        $valuesCta = $ctaMulti->getValues();

        $strLib = array(
            'multiTitle' => $nom,
            'multiAddButton' => $button
        );

        $return = $controller->oForm->createMultiHmvc(
            $typeCta,
            $strLib,
            array(
                "path" => __FILE__,
                "class" => __CLASS__,
                "method" => $methode,
            ),
            $valuesCta,
            $typeCta,
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            array(1, 2),
            true,
            true,
            $typeCta,
            'values',
            'multi',
            "2",
            '',
            '',
            false,
            ['noDragNDrop' => Cms_Page_Ndp::isTranslator()]
        );

        return $return;
    }

    /**
     *
     * @param Ndp_Form $form
     * @param array $values
     * @param bool $readO
     * @param array $multi
     *
     * @return string
     */
    public static function addCtaMulti(Ndp_Form $form, $values, $readO, $multi) {
        // Ajout formulaire des CTA
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setCta($form, $values, $multi, '', true, (Cms_Page_Ndp::isTranslator() || $readO));
        $ctaComposite->setNeeded(true);

        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->hideStyle(true);

        $ctaComposite->addInputCta($ctaRef);

        return $ctaComposite->generate();
    }

    /**
     * @param Ndp_Form $form
     * @param array    $values
     * @param string   $multi
     *
     * @return string
     */
    public function addCtaSimple(Ndp_Form $form, $values, $readO, $multi)
    {
        $ctaComposite = Pelican_Factory::getInstance('CtaComposite');
        $ctaComposite->setCta($form, $values, $multi, '', true, (Cms_Page_Ndp::isTranslator() || $readO));

        $ctaRef = Pelican_Factory::getInstance('CtaRef');
        $ctaRef->hideStyle(true);

        $ctaComposite->addInputCta($ctaRef);

        return $ctaComposite->generate();
    }

    /**
     * @return string
     */
    public static function displayConfirmMessage()
    {
        return sprintf('confirm(%s);', t('NDP_UPDATE_CONTENT_FORM'));
    }

    /**
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller) {
        Ndp_Cta_Factory::setContext('Content');

        $saved = Pelican_Db::$values;
        switch (Pelican_Db::$values['CONTENT_CODE2']) {
            case self::NO_TYPE:
                unset(Pelican_Db::$values['CONTENT_TITLE2']);
                unset(Pelican_Db::$values['CONTENT_TITLE3']);
                unset(Pelican_Db::$values['CONTENT_TITLE4']);
                unset(Pelican_Db::$values['CONTENT_TITLE5']);
                break;
            case self::TYPE_CAR:
                unset(Pelican_Db::$values['CONTENT_TITLE5']);
                break;
            case self::TYPE_PDV:
                unset(Pelican_Db::$values['CONTENT_TITLE2']);
                unset(Pelican_Db::$values['CONTENT_TITLE3']);
                unset(Pelican_Db::$values['CONTENT_TITLE4']);
                break;
            default:
                //nothing
                break;
        }
        parent::save($controller);

        $ctaHmvc = Ndp_Cta_Factory::getInstance(Ndp_Cta::HMVC);

        $ctaHmvc->setCtaType(self::TYPE_CTA)
                ->setMulti($controller->multi)
                ->delete()
                ->save();

        $ctaHmvc->setCtaType(self::CTA_MYPEUGEOT)
            ->setMulti($controller->multi)
            ->delete()
            ->save();

        Pelican_Db::$values = $saved;

        Ndp_Cta_Factory::setDefaultContext();
    }
}
