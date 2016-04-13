<?php
require_once (Pelican::$config['APPLICATION_CONTROLLERS'].'/Citroen.php');
class Citroen_Administration_ThemesActualites_Controller extends Citroen_Controller
{
	protected $multiLangue = true;
    protected $administration = true;
    protected $form_name = "theme_actualites";
    protected $field_id = "THEME_ACTUALITES_ID";
    protected $defaultOrder = "THEME_ACTUALITES_ORDER";

    protected function setListModel()
    {	
		$oConnection = Pelican_Db::getInstance();
		$aBind[':SITE_ID'] = (int)$_SESSION[APP]['SITE_ID'];
        $aBind[':LANGUE_ID'] = (int)$_SESSION[APP]['LANGUE_ID'];
        $sqlList = "SELECT 
						* 
					FROM 
						#pref#_theme_actualites 
					WHERE 
						SITE_ID = :SITE_ID
					and LANGUE_ID = :LANGUE_ID ";

        if ($_GET['filter_search_keyword'] != '') {
            $sqlList.= " AND (
            THEME_ACTUALITES_LABEL like '%" . $_GET['filter_search_keyword'] . "%' 
            )
            ";
        }


		$sqlList.="ORDER BY " . $this->listOrder;
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
								#pref#_theme_actualites 
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
		$table->setTableOrder ( "#pref#_theme_actualites", "THEME_ACTUALITES_ID", "THEME_ACTUALITES_ORDER", "", "SITE_ID = " . $_SESSION[APP]['SITE_ID'], array("Frontend/Citroen/Actualites/Themes") );
        $table->setValues($this->getListModel(), "THEME_ACTUALITES_ID");
        $table->addColumn(t('ID'), "THEME_ACTUALITES_ID", "10", "left", "", "tblheader", "THEME_ACTUALITES_ID");
        $table->addColumn(t('LIBELLE'), "THEME_ACTUALITES_LABEL", "90", "left", "", "tblheader", "THEME_ACTUALITES_LABEL");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" => "THEME_ACTUALITES_ID"), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" => "THEME_ACTUALITES_ID", "" => "readO=true"), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction()
    {
        parent::editAction();
        $form = $this->startStandardForm();
		$form .= $this->oForm->createHidden ( "THEME_ACTUALITES_ORDER", $this->values ["THEME_ACTUALITES_ORDER"] );
        $form .= $this->oForm->createInput("THEME_ACTUALITES_LABEL", t('LIBELLE'), 255, "", true, $this->values['THEME_ACTUALITES_LABEL'], $this->readO, 75);
        $form .= $this->stopStandardForm();
        $this->setResponse($form);
    }

    public function saveAction()
    {
        parent::saveAction();
        Pelican_Cache::clean("Frontend/Citroen/Actualites/Themes");
        Pelican_Cache::clean("Backend/Themes");
    }

}