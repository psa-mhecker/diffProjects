<?php
require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php');
class Citroen_Administration_VehiculesCritere_Controller extends Citroen_Controller
{
	
    protected $form_name = "critere";
    protected $field_id = "CRITERE_ID";
    protected $defaultOrder = "CRITERE_ORDER";
    /* Activation de la barre de langue */
    protected $multiLangue = true;
	
	protected $decacheBack = array(
        array('Frontend/Citroen/Criteres', 
            array('SITE_ID', 'LANGUE_ID') 
        )
    );
	
    protected function setListModel()
    {
		$oConnection = Pelican_Db::getInstance();
		$aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $aBind[':LANGUE_ID'] = (int)$_SESSION[APP]['LANGUE_ID'];
        $aBind[':CRITERE_TYPE'] = (int)$this->getParam('tc');
        $sqlList = "SELECT 
						* 
					FROM 
						#pref#_critere  
					WHERE 
						SITE_ID = :SITE_ID 
					AND LANGUE_ID = :LANGUE_ID
					AND CRITERE_TYPE = :CRITERE_TYPE ";
     if ($_GET['filter_search_keyword'] != '') {
            $sqlList.= " AND (
            CRITERE_LABEL_INTERNE like '%" . $_GET['filter_search_keyword'] . "%' 
            )
            ";
    }
		$sqlList.= " ORDER BY " . $this->listOrder;
        $this->listModel = $oConnection->queryTab($sqlList,$aBind);
    }

    protected function setEditModel()
    {
		$this->aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID'] = (int)$_SESSION[APP]['LANGUE_ID'];
		$this->aBind[':CRITERE_TYPE'] = (int)$this->getParam('tc');
        $this->aBind[':'.$this->field_id] = (int)$this->id;
        $this->editModel = "SELECT 
								* 
							FROM
								#pref#_critere 
							WHERE 
								SITE_ID = :SITE_ID 
							AND LANGUE_ID = :LANGUE_ID
							AND CRITERE_TYPE = :CRITERE_TYPE 
							AND ".$this->field_id." = :".$this->field_id;
    }

    public function listAction()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", "");
        $table->getFilter(1);
		$table->setTableOrder ( "#pref#_critere", "CRITERE_ID", "CRITERE_ORDER", "", "SITE_ID = " . $_SESSION[APP]['SITE_ID'] . " AND CRITERE_TYPE = ".$this->getParam('tc'), array("Frontend/Citroen/Criteres") );
        $table->setValues($this->getListModel(), "CRITERE_ID");
        $table->addColumn(t('ID'), "CRITERE_ID", "20", "left", "", "tblheader", "CRITERE_ID");
        $table->addColumn(t('LIBELLE'), "CRITERE_LABEL_INTERNE", "80", "left", "", "tblheader", "CRITERE_LABEL_INTERNE");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "CRITERE_ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "CRITERE_ID", "" => "readO=true"), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
		if ($this->form_action == 'DEL') {
		$oConnection = Pelican_Db::getInstance();
		$this->aBind[':ID'] = $this->id;
		$this->aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $this->aBind[':LANGUE_ID'] = (int)$_SESSION[APP]['LANGUE_ID'];
			$sSQL = '
				SELECT
					v.VEHICULE_ID,
					v.VEHICULE_LABEL
				FROM
					#pref#_vehicule v
				INNER JOIN #pref#_vehicule_criteres vc ON (vc.VEHICULE_ID = v.VEHICULE_ID and v.SITE_ID = :SITE_ID and v.LANGUE_ID = :LANGUE_ID)
				WHERE 
					vc.CRITERE_ID = :ID
				AND vc.LANGUE_ID = :LANGUE_ID
				AND vc.SITE_ID = :SITE_ID
			';
            $aVehiculeConflictuelles = $oConnection->queryTab($sSQL, $this->aBind);
            if ($aVehiculeConflictuelles) {
                foreach($aVehiculeConflictuelles as $p) {
                    $error .= $p['VEHICULE_LABEL'].' ('.$p['VEHICULE_ID'].')'.Pelican_Html::br();
                }
                $error = Pelican_Html::div(
                    array("class" => t('ERROR')),
                    Pelican_Html::br().Pelican_Html::b(t('SUPP_IMPOS')).Pelican_Html::br().t('CONTENU_UTILISE_DANS').Pelican_Html::br().$error.Pelican_Html::br()
                );
            }
        }
		$form = $error.$this->startStandardForm();
        $form .= $this->oForm->createHidden($this->field_id, $this->id);
		$form .= $this->oForm->createHidden ( "CRITERE_ORDER", $this->values ["CRITERE_ORDER"] );
		$form .= $this->oForm->createHidden('SITE_ID',  $_SESSION[APP]['SITE_ID']);
        $form .= $this->oForm->createHidden("CRITERE_TYPE", $this->getParam('tc'));
		
        $form .= $this->oForm->createInput("CRITERE_LABEL_INTERNE", t('CRITERE_LABEL_INTERNE'), 40, "", true, $this->values['CRITERE_LABEL_INTERNE'], $this->readO, 75);
        $form .= $this->oForm->createInput("CRITERE_LABEL_PUBLIC", t('CRITERE_LABEL_PUBLIC'), 40, "", true, $this->values['CRITERE_LABEL_PUBLIC'], $this->readO, 75);
        
        $form .= $this->stopStandardForm();
        $form = formToString ($this->oForm, $form);
		if($aVehiculeConflictuelles){
			$this->aButton["delete"] = "";
			Backoffice_Button_Helper::init($this->aButton);
		}
        $this->setResponse($form);
    }
	
	public function saveAction()
    {
        parent::saveAction();
        Pelican_Cache::clean("Frontend/Citroen/Criteres");
    }
	
}