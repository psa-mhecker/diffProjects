<?php

/**
 * Formulaire de gestion des rôles utilisateur du workflow
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 07/03/2004
 */
class Administration_Role_Controller extends Pelican_Controller_Back
{

    protected $administration = true;

    protected $form_name = "role";

    protected $field_id = "ROLE_ID";

    protected $defaultOrder = "ROLE_LABEL";

    protected $processus = array(
        "#pref#_role" , 
        array(
            "method" , 
            "Administration_Role_Controller::dependance"
        )
    );

    protected $decacheBack = array(
        "state_dependencies_php"
    );

    protected function setListModel ()
    {
        $this->listModel = "SELECT #pref#_role.ROLE_ID, ROLE_LABEL, count(STATE_ID) as DEPENDENCIES_COUNT
			FROM #pref#_role
			left join #pref#_state_dependencies on (#pref#_state_dependencies.ROLE_ID=#pref#_role.ROLE_ID)
			group by #pref#_role.ROLE_ID, ROLE_LABEL
			order by " . $this->listOrder;
    }

    protected function setEditModel ()
    {
        $this->editModel = "SELECT * from #pref#_role WHERE ROLE_ID='" . $this->id . "'";
    }

    public function listAction ()
    {
        parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", "ROLE_LABEL");
        $table->getFilter(1);
        $table->setCSS(array(
            "tblalt1" , 
            "tblalt2"
        ));
        $table->setValues($this->getListModel(), "#pref#_role.ROLE_ID");
        //  $table->addColumn(t('ID'), "ROLE_ID", "10", "left", "", "tblheader", "ROLE_ID");
        $table->addColumn(t('ROLE'), "ROLE_LABEL", "50", "left", "", "tblheader", "ROLE_LABEL");
        $table->addColumn(t('DEPENDANCES'), "DEPENDENCIES_COUNT", "10", "center", "", "tblheader", "DEPENDENCIES_COUNT");
        $table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
            "id" => "ROLE_ID"
        ), "center");
        $table->addInput(t('POPUP_LABEL_DEL'), "button", array(
            "id" => "ROLE_ID" , 
            "" => "readO=true"
        ), "center");
        $this->setResponse($table->getTable());
    }

    public function editAction ()
    {
        $oConnection = Pelican_Db::getInstance();
        parent::editAction();
        $form = $this->startStandardForm();
        
        $form .= $this->oForm
            ->createInput("ROLE_LABEL", t('FORM_LABEL'), 255, "", true, $this->values["ROLE_LABEL"], $this->readO, 75);
        $form .= $this->oForm
            ->createLabel(t('DEPENDANCES'), "&nbsp;");
        $strSql = " SELECT
				*
				FROM
				#pref#_state_dependencies
				WHERE
				ROLE_ID=" . $this->id . "
				ORDER BY STATE_PARENT_ID, STATE_ID";
        $multiValues = $oConnection->queryTab($strSql);
        
        $form .= $this->oForm
            ->createMultiHmvc("state", t('ETAT'), array(
            'path' => Pelican::$config["APPLICATION_CONTROLLERS"] . '/Administration/State.php' , 
            'class' => 'Administration_State_Controller' , 
            'method' => 'path'
        ), $multiValues, 'multi', $this->readO);
        
        $form .= $this->oForm
            ->createHidden("TEMPLATE_COMPLEMENT", "role");
        
        $form .= $this->stopStandardForm();
		
		// Zend_Form start
		$form = formToString($this->oForm, $form);
        // Zend_Form stop
		
        $this->setResponse($form);
    }

    public function beforeDelete ()
    {
        
        $oConnection = Pelican_Db::getInstance();
        $aBind[':ROLE_ID'] = Pelican_Db::$values['ROLE_ID'];
        $oConnection->query("delete from #pref#_user_role where ROLE_ID=:ROLE_ID", $aBind);
    }

    public static function dependance ()
    {
        
        $oConnection = Pelican_Db::getInstance();
        
        readMulti("state");
        
        $DBVALUES_MONO = Pelican_Db::$values;
        if ($DBVALUES_MONO["state"]) {
            // suppression pour annule/remplace
            $oConnection->query("DELETE from #pref#_state_dependencies where ROLE_ID=" . Pelican_Db::$values["ROLE_ID"]);
            foreach ($DBVALUES_MONO["state"] as Pelican_Db::$values) {
                // récupération de la clé
                Pelican_Db::$values["ROLE_ID"] = $DBVALUES_MONO["ROLE_ID"];
                if (Pelican_Db::$values['form_action'] != Pelican_Db::DATABASE_DELETE && Pelican_Db::$values["STATE_PARENT_ID"] && Pelican_Db::$values["STATE_ID"] && Pelican_Db::$values["STATE_PARENT_ID"] != Pelican_Db::$values["STATE_ID"]) {
                    $oConnection->updateTable(Pelican_Db::DATABASE_INSERT, "#pref#_state_dependencies");
                }
            }
        }
        Pelican_Db::$values = $DBVALUES_MONO;
    }
}
