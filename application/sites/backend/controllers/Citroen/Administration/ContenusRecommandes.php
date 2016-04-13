<?php
require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php');
class Citroen_Administration_ContenusRecommandes_Controller extends Citroen_Controller
{
	protected $multiLangue = true;
    protected $administration = true;
    protected $form_name = "contenu_recommande";
    protected $field_id = "CONTENU_RECOMMANDE_ID";
    protected $defaultOrder = "CONTENU_RECOMMANDE_ID";

    protected function setListModel()
    {
		$oConnection = Pelican_Db::getInstance();
		$aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $aBind[':LANGUE_ID'] = (int)$_SESSION[APP]['LANGUE_ID'];
        $sqlList = "SELECT
						*
					FROM
						#pref#_contenu_recommande
					WHERE
						SITE_ID = :SITE_ID
					AND LANGUE_ID = :LANGUE_ID ";

        if ($_GET['filter_search_keyword'] != '') {
            $sqlList.= " AND (
            CONTENU_RECOMMANDE_TITRE_BO like '%" . $_GET['filter_search_keyword'] . "%' 
            )
            ";
            }           

		$sqlList.= "ORDER BY " . $this->listOrder;
        $this->listModel = $oConnection->queryTab($sqlList,$aBind);
    }

    protected function setEditModel()
    {
		$this->aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID'] = (int)$_SESSION[APP]['LANGUE_ID'];
        $this->aBind[':'.$this->field_id] = (int)$this->id;
        $this->editModel = "SELECT
								*
							FROM
								#pref#_contenu_recommande
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
        $table->setValues($this->getListModel(), "CONTENU_RECOMMANDE_ID");
        $table->addColumn(t('ID'), "CONTENU_RECOMMANDE_ID", "10", "left", "", "tblheader", "CONTENU_RECOMMANDE_ID");
        $table->addColumn(t('LIBELLE'), "CONTENU_RECOMMANDE_TITRE_BO", "90", "left", "", "tblheader", "CONTENU_RECOMMANDE_TITRE_BO");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "CONTENU_RECOMMANDE_ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "CONTENU_RECOMMANDE_ID", "" => "readO=true"), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        // Message d'information lors de la tentative de suppresssion d'un contenu utilis� dans des pages.
        if ($this->form_action == 'DEL') {
            $aPagesConflictuelles = Backoffice_Form_Helper::verificationSuppressionReferentiel('CONTENUS_RECOMMANDES', $this->values['CONTENU_RECOMMANDE_ID'], $this->values['SITE_ID']);
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
        $oConnection = Pelican_Db::getInstance();
        $form = $error.$this->startStandardForm();
        $form .= $this->oForm->createHidden($this->field_id, $this->id);
        $form .= $this->oForm->createHidden('SITE_ID',  $_SESSION[APP]['SITE_ID']);
        $form .= $this->oForm->createInput("CONTENU_RECOMMANDE_TITRE_BO", t('LIBELLE_INTERNE'), 255, "", true, $this->values['CONTENU_RECOMMANDE_TITRE_BO'], $this->readO, 75);
        $form .= $this->oForm->createInput("CONTENU_RECOMMANDE_TITRE", t('LIBELLE'), 255, "", true, $this->values['CONTENU_RECOMMANDE_TITRE'], $this->readO, 75);
        $form .= $this->oForm->createMedia("MEDIA_ID", t('IMAGE'), true,  "image", "", $this->values['MEDIA_ID'] , $this->readO, true, false, 'carre', null, true, $this->values['MEDIA_ID_GENERIQUE']);
        $form .= $this->oForm->createInput("CONTENU_RECOMMANDE_URL", t('URL_WEB'), 255, "internallink", true, $this->values['CONTENU_RECOMMANDE_URL'], $this->readO, 75);
        $form .= $this->oForm->createRadioFromList("CONTENU_RECOMMANDE_MODE_OUVERTURE", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $this->values['CONTENU_RECOMMANDE_MODE_OUVERTURE'], true, $this->readO);
        $form .= $this->stopStandardForm();
        if ($aPagesConflictuelles) {
            $this->aButton["delete"] = "";
            Backoffice_Button_Helper::init($this->aButton);
        }
        $form = formToString($this->oForm, $form);
        $this->setResponse ($form);
    }

    public function saveAction()
    {
        parent::saveAction();
        Pelican_Cache::clean("Frontend/Citroen/ContenusRecommandes");
    }

}