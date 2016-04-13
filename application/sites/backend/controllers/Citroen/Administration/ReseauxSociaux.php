<?php
require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php');

class Citroen_Administration_ReseauxSociaux_Controller extends Citroen_Controller
{
	protected $multiLangue = true;
    protected $administration = true;
    protected $form_name = "reseau_social";
    protected $field_id = "RESEAU_SOCIAL_ID";
    protected $defaultOrder = "RESEAU_SOCIAL_ORDER";

    protected function setListModel()
    {
		$oConnection = Pelican_Db::getInstance();
		$aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $aBind[':LANGUE_ID'] = (int)$_SESSION[APP]['LANGUE_ID'];
        $sqlList = "SELECT
						*
					FROM
						#pref#_reseau_social
					WHERE
						SITE_ID = :SITE_ID
					AND LANGUE_ID = :LANGUE_ID 
                    ORDER BY " . $this->listOrder;
                  
        $aRx = $oConnection->queryTab($sqlList,$aBind);

        if ($_GET['filter_search_keyword'] != '') {

            $aNewRx = array();
            $aListRx = array_flip(Pelican::$config['TYPE_RESEAUX_SOCIAUX']);
            foreach ($aRx as $keyRX => $valueRX) {
                if($_GET['filter_search_keyword'] != "" && (stristr($aListRx[$valueRX['RESEAU_SOCIAL_TYPE']], $_GET['filter_search_keyword']) || stristr($valueRX['RESEAU_SOCIAL_LABEL'], $_GET['filter_search_keyword'])))
                {
                    $aNewRx[] = $valueRX;
                }
            }
            $aRx = $aNewRx;
        }
                    
        $this->listModel = $aRx;
        }

    protected function setEditModel()
    {
		$this->aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID'] = (int)$_SESSION[APP]['LANGUE_ID'];
        $this->aBind[':'.$this->field_id] = (int)$this->id;
        $this->editModel = "SELECT
								*
							FROM
								#pref#_reseau_social
							WHERE
								SITE_ID = :SITE_ID
							AND LANGUE_ID = :LANGUE_ID
							AND ".$this->field_id." = :" . $this->field_id;
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
		$table->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", "");
        $table->getFilter(1);
        $table->setTableOrder ( "#pref#_reseau_social", "RESEAU_SOCIAL_ID", "RESEAU_SOCIAL_ORDER" , "", "SITE_ID = " . $_SESSION[APP]['SITE_ID'], array("Frontend/Citroen/ReseauxSociaux"));
        $table->setValues($this->getListModel(), "RESEAU_SOCIAL_ID");
        if ($table->aTableValues) {
            $aReseauxSociaux = array_flip(Pelican::$config['TYPE_RESEAUX_SOCIAUX']);
            foreach($table->aTableValues as $i => $val) {
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
        // Message d'information lors de la tentative de suppresssion d'un contenu utilisé dans des pages.
        if ($this->form_action == 'DEL') {
            $aPagesConflictuelles = Backoffice_Form_Helper::verificationSuppressionReferentiel('RESEAUX_SOCIAUX', $this->values['RESEAU_SOCIAL_ID'], $this->values['SITE_ID']);
            if ($aPagesConflictuelles) {
                foreach($aPagesConflictuelles as $p) {
                    $error .= $p['PAGE_TITLE_BO'].' (pid : '.$p['PAGE_ID'].')'.Pelican_Html::br();
                }
                $error = Pelican_Html::div(
                    array("class" => t('ERROR')),
                    Pelican_Html::br().Pelican_Html::b(t('SUPP_IMPOS')).Pelican_Html::br().t('CONTENU_UTILISE_DANS').Pelican_Html::br().$error.Pelican_Html::br()
                );
            }
        }
        $form = $error.$this->startStandardForm();
        if ($this->values['RESEAU_SOCIAL_AFFICHAGE_WEB']) {
            $this->values['AFFICHAGE'][] = 1;
        }
        if ($this->values['RESEAU_SOCIAL_AFFICHAGE_MOBILE']) {
            $this->values['AFFICHAGE'][] = 2;
        }
        $form .= $this->oForm->createHidden($this->field_id, $this->id);
        $form .= $this->oForm->createHidden('SITE_ID',  $_SESSION[APP]['SITE_ID']);
	$form .= $this->oForm->createHidden ( "RESEAU_SOCIAL_ORDER", $this->values ["RESEAU_SOCIAL_ORDER"] );
        $form .= $this->oForm->createInput("RESEAU_SOCIAL_LABEL", t('LIBELLE'), 255, "", true, $this->values['RESEAU_SOCIAL_LABEL'], $this->readO, 75);
        $aReseauxSociaux = array_map('ucfirst', array_map('strtolower', array_flip(Pelican::$config['TYPE_RESEAUX_SOCIAUX'])));
        $form .= $this->oForm->createComboFromList("RESEAU_SOCIAL_TYPE", t('TYPE_RESEAU'), $aReseauxSociaux, $this->values["RESEAU_SOCIAL_TYPE"], true, $this->readO);
        $form .= $this->oForm->createMedia("MEDIA_ID", t('VISUEL'), false, "image", "", $this->values["MEDIA_ID"], $this->readO, true, false, 'carre');
        $form .= $this->oForm->createInput("RESEAU_SOCIAL_ID_COMPTE", t('ID_COMPTE'), 255, "", false, $this->values['RESEAU_SOCIAL_ID_COMPTE'], $this->readO, 75);					
		$form .= $this->oForm->createInput("RESEAU_SOCIAL_KEY_API", t('CLE_PUBLIQUE_API'), 255, "", false, $this->values['RESEAU_SOCIAL_KEY_API'], $this->readO, 75);
		$form .= $this->oForm->createInput("RESEAU_SOCIAL_ID_WIDGET", t('ID_WIDGET'), 255, "", false, $this->values['RESEAU_SOCIAL_ID_WIDGET'], $this->readO, 75);
        $form .= $this->oForm->createInput("RESEAU_SOCIAL_URL_WEB", t('URL_WEB'), 255, "", false, $this->values['RESEAU_SOCIAL_URL_WEB'], $this->readO, 75);
        $form .= $this->oForm->createInput("RESEAU_SOCIAL_URL_MOBILE", t('URL_MOBILE'), 255, "", false, $this->values['RESEAU_SOCIAL_URL_MOBILE'], $this->readO, 75);
        $form .= $this->oForm->createRadioFromList("RESEAU_SOCIAL_URL_MODE_OUVERTURE", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $this->values['RESEAU_SOCIAL_URL_MODE_OUVERTURE'], true, $readO);
        $form .= $this->oForm->createCheckBoxFromList("AFFICHAGE", t('AFFICHAGE'), array(1 => t('WEB'), 2 => t('MOBILE')), $this->values['AFFICHAGE'], true, $this->readO);
        $form .= $this->oForm->createComboFromList("RESEAU_SOCIAL_NB_FLUX", t('CLEF1'), $this->getNbFluxArray(), $this->values['RESEAU_SOCIAL_NB_FLUX']?$this->values['RESEAU_SOCIAL_NB_FLUX']:'10', true, $this->readO, 1, false, '', false);
        $form .= $this->stopStandardForm();
        if ($aPagesConflictuelles) {
            $this->aButton["delete"] = "";
            Backoffice_Button_Helper::init($this->aButton);
        }
        $form = formToString ($this->oForm, $form);
        $this->setResponse ($form);
    }
    
    private function getNbFluxArray($min=5,$max=25){
        $nb_flux = array();
        for ($i = $min; $i <= $max; $i++) { 
            $nb_flux[$i] = $i;
        }
        return $nb_flux;
    }

    public function saveAction()
    {
        if (in_array(1, Pelican_Db::$values['AFFICHAGE'])) {
            Pelican_Db::$values['RESEAU_SOCIAL_AFFICHAGE_WEB'] = 1;
        }
        if (in_array(2, Pelican_Db::$values['AFFICHAGE'])) {
            Pelican_Db::$values['RESEAU_SOCIAL_AFFICHAGE_MOBILE'] = 1;
        }
        parent::saveAction();
        Pelican_Cache::clean("Frontend/Citroen/ReseauxSociaux");
        Pelican_Cache::clean("Frontend/Citroen/GroupeReseauxSociaux");
        Pelican_Cache::clean("Frontend/Citroen/FindGroupeReseauxSociaux");
        Pelican_Cache::clean("Frontend/Citroen/BoxSociales");
        Pelican_Cache::clean("Frontend/Citroen/CitroenSocial/SocialNetworks");
    }

}