<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

class Ndp_ReseauSocial_Controller extends Ndp_Controller
{
    protected $multiLangue    = true;
    protected $administration = true;
    protected $form_name      = "reseau_social";
    protected $field_id       = "RESEAU_SOCIAL_ID";
    protected $defaultOrder   = "RESEAU_SOCIAL_ORDER";

    const SOCIALE_CONT = "RX";
    const FACEBOOK     = 1;
    const YOUTUBE      = 3;
    const TWITTER      = 2;
    const INSTAGRAM = 5;
    const BLANK = 2;
    

    protected function setListModel()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $aBind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $sqlList = "SELECT
						*
					FROM
						#pref#_reseau_social
					WHERE
						SITE_ID = :SITE_ID
					AND LANGUE_ID = :LANGUE_ID
                    ORDER BY ".$this->listOrder;

        $aRx = $oConnection->queryTab($sqlList, $aBind);

        if ($_GET['filter_search_keyword'] != '') {
            $aNewRx = array();
            $aListRx = array_flip(Pelican::$config['TYPE_RESEAUX_SOCIAUX']);
            foreach ($aRx as $keyRX => $valueRX) {
                if ($_GET['filter_search_keyword'] != "" && (stristr($aListRx[$valueRX['RESEAU_SOCIAL_TYPE']], $_GET['filter_search_keyword']) || stristr($valueRX['RESEAU_SOCIAL_LABEL'], $_GET['filter_search_keyword']))) {
                    $aNewRx[] = $valueRX;
                }
            }
            $aRx = $aNewRx;
        }

        $this->listModel = $aRx;
    }

    protected function setEditModel()
    {
        $this->aBind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID'] = (int) $_SESSION[APP]['LANGUE_ID'];
        $this->aBind[':'.$this->field_id] = (int) $this->id;
        $this->editModel = "SELECT
								*
							FROM
								#pref#_reseau_social
							WHERE
								SITE_ID = :SITE_ID
							AND LANGUE_ID = :LANGUE_ID
							AND ".$this->field_id." = :".$this->field_id;
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("search_keyword", "<b>".t('RECHERCHER')." :</b>", "");
        $table->getFilter(1);
        $table->setTableOrder("#pref#_reseau_social", "RESEAU_SOCIAL_ID", "RESEAU_SOCIAL_ORDER", "", "SITE_ID = ".$_SESSION[APP]['SITE_ID'], array("Frontend/Citroen/ReseauxSociaux"));
        $table->setValues($this->getListModel(), "RESEAU_SOCIAL_ID");
        if ($table->aTableValues) {
            $aReseauxSociaux = array_flip(Pelican::$config['TYPE_RESEAUX_SOCIAUX']);
            foreach ($table->aTableValues as $i => $val) {
                $table->aTableValues[$i]['RESEAU_SOCIAL_TYPE'] = ucfirst(strtolower($aReseauxSociaux[$table->aTableValues[$i]['RESEAU_SOCIAL_TYPE']]));
            }
        }
        $table->addColumn(t('ID'), "RESEAU_SOCIAL_ID", "10", "left", "", "tblheader", "RESEAU_SOCIAL_ID");
        $table->addColumn(t('LIBELLE'), "RESEAU_SOCIAL_LABEL", "45", "left", "", "tblheader", "RESEAU_SOCIAL_LABEL");
        $table->addColumn(t('TYPE'), "RESEAU_SOCIAL_TYPE", "45", "left", "", "tblheader", "RESEAU_SOCIAL_TYPE");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "RESEAU_SOCIAL_ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "RESEAU_SOCIAL_ID", "" => "readO=true"), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        // Message d'information lors de la tentative de suppression d'un contenu utilisé dans des pages.
        if ($this->form_action == 'DEL') {
            //@TOD replacer ce helper et l'utilisation de Pelican::$config['CONTRAINTES_SUPPRESSION_REFERENTIEL']
            $aPagesConflictuelles = Backoffice_Form_Helper::verificationSuppressionReferentiel('RESEAUX_SOCIAUX', $this->values['RESEAU_SOCIAL_ID'], $this->values['SITE_ID']);
            if ($aPagesConflictuelles) {
                $error = '';
                foreach ($aPagesConflictuelles as $p) {
                    $error .= $p['PAGE_TITLE_BO'].' (pid : '.$p['PAGE_ID'].')'.Pelican_Html::br();
                }
                $error = Pelican_Html::div(
                    array("class" => t('ERROR')),
                    Pelican_Html::br().Pelican_Html::b(t('SUPP_IMPOS')).Pelican_Html::br().t('CONTENU_UTILISE_DANS').Pelican_Html::br().$error.Pelican_Html::br()
                );
            }
        }
        $form = $error.$this->startStandardForm();
        $form .= $this->oForm->createHidden($this->field_id, $this->id);
        $form .= $this->oForm->createHidden('SITE_ID', $_SESSION[APP]['SITE_ID']);
        $form .= $this->oForm->createHidden("RESEAU_SOCIAL_ORDER", $this->values ["RESEAU_SOCIAL_ORDER"]);
        $this->setDefaultValueTo('RESEAU_SOCIAL_AFFICHAGE_WEB', true);
        $this->setDefaultValueTo('RESEAU_SOCIAL_AFFICHAGE_MOBILE', true);
        $form .= $this->oForm->createCheckBoxFromList("RESEAU_SOCIAL_AFFICHAGE_WEB", t('AFFICHAGE_WEB'), array(1 => ''), $this->values['RESEAU_SOCIAL_AFFICHAGE_WEB'], false, $this->readO);
        $form .= $this->oForm->createCheckBoxFromList("RESEAU_SOCIAL_AFFICHAGE_MOBILE", t('AFFICHAGE_MOB'), array('1' => ''), $this->values['RESEAU_SOCIAL_AFFICHAGE_MOBILE'], false, $this->readO);
        $form .= $this->oForm->createInput("RESEAU_SOCIAL_LABEL", t('NDP_LABEL_BO'), 255, "", true, $this->values['RESEAU_SOCIAL_LABEL'], $this->readO, 75);
        $aReseauxSociaux = array_map('ucfirst', array_map('strtolower', array_flip(Pelican::$config['TYPE_RESEAUX_SOCIAUX'])));
        $type  = self::SOCIALE_CONT;
        $js    = Cms_Page_Ndp::addJsContainerComboLD($type);
        $this->setDefaultValueTo('RESEAU_SOCIAL_TYPE', self::FACEBOOK);
        $form .= $this->oForm->createComboFromList("RESEAU_SOCIAL_TYPE", t('TYPE_RESEAU'), $aReseauxSociaux, $this->values["RESEAU_SOCIAL_TYPE"], true, $this->readO, 1, false, '', false, false, $js);
        $form .= $this->oForm->createMedia("MEDIA_ID", t('NDP_BO_PICTO'), true, "image", "", $this->values["MEDIA_ID"], $this->readO, true, false, 'NDP_RATIO_SQUARE_1_1:247x247');
        $form .= $this->oForm->createMedia("MEDIA_ID2", t('NDP_BO_IMAGE_PAR_DEFAUT'), false, "image", "", $this->values["MEDIA_ID2"], $this->readO, true, false, 'NDP_RATIO_4_3:425x319');
        $form .= $this->oForm->createInput("RESEAU_SOCIAL_ID_COMPTE", t('ID_COMPTE'), 255, "", false, $this->values['RESEAU_SOCIAL_ID_COMPTE'], $this->readO, 75);
        $form .= $this->oForm->createInput("RESEAU_SOCIAL_ID_WIDGET", t('NDP_WIDGET_ID'), 255, "", false, $this->values['RESEAU_SOCIAL_ID_WIDGET'], $this->readO, 75);
        $form .= $this->oForm->createInput("RESEAU_SOCIAL_URL_WEB", t('NDP_URL'), 255, "", true, $this->values['RESEAU_SOCIAL_URL_WEB'], $this->readO, 75);
        $this->setDefaultValueTo('RESEAU_SOCIAL_URL_MODE_OUVERTURE', self::BLANK);
        $form .= $this->oForm->createRadioFromList("RESEAU_SOCIAL_URL_MODE_OUVERTURE", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $this->values['RESEAU_SOCIAL_URL_MODE_OUVERTURE'], true, $this->readO);
        
        $form .= $this->addHeadContainer(self::TWITTER, $this->values["RESEAU_SOCIAL_TYPE"], $type);
        $form .= $this->oForm->createInput("TWITTER_CONSUMMER_KEY", t('NDP_CONSUMMER_KEY'), 255, "", false, $this->values['TWITTER_CONSUMMER_KEY'], $this->readO, 75);
        $form .= $this->oForm->createInput("TWITTER_CONSUMMER_SECRET", t('NDP_CONSUMMER_SECRET'), 255, "", false, $this->values['TWITTER_CONSUMMER_SECRET'], $this->readO, 75);
        $form .= $this->oForm->createInput("TWITTER_ACCESS_TOKEN", t('NDP_ACCESS_TOKEN'), 255, "", false, $this->values['TWITTER_ACCESS_TOKEN'], $this->readO, 75);
        $form .= $this->oForm->createInput("TWITTER_ACCESS_TOKEN_SECRET", t('NDP_ACCESS_TOKEN_SECRET'), 255, "", false, $this->values['TWITTER_ACCESS_TOKEN_SECRET'], $this->readO, 75);
        $form .= $this->addFootContainer();

        $form .= $this->addHeadContainer(array(self::FACEBOOK, self::INSTAGRAM), $this->values["RESEAU_SOCIAL_TYPE"], $type);
        $form .= $this->oForm->createInput("APP_ID", t('NDP_APP_ID'), 255, "", false, $this->values['APP_ID'], $this->readO, 75);
        $form .= $this->oForm->createInput("APP_ID_SECRET", t('NDP_APP_ID_SECRET'), 255, "", false, $this->values['APP_ID_SECRET'], $this->readO, 75);
        $form .= $this->addFootContainer();

        $form .= $this->addHeadContainer(self::YOUTUBE, $this->values["RESEAU_SOCIAL_TYPE"], $type);
        $form .= $this->oForm->createInput("YOUTUBE_API_KEY", t('NDP_API_KEY'), 255, "", false, $this->values['YOUTUBE_API_KEY'], $this->readO, 75);
        $form .= $this->addFootContainer();

        $form .= $this->stopStandardForm();
        if ($aPagesConflictuelles) {
            $this->aButton["delete"] = "";
            Backoffice_Button_Helper::init($this->aButton);
        }
        $form = formToString($this->oForm, $form);
        $this->setResponse($form);
    }

    public function saveAction()
    {
        parent::saveAction();
    }
}
