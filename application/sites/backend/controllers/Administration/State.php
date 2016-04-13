<?php
/**
 * Formulaire de gestion des états de workflow
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 02/07/2004
 */

class Administration_State_Controller extends Pelican_Controller_Back {
	
	protected $administration = true;
	
	protected $form_name = "state";
	
	protected $field_id = "STATE_ID";
	
	protected $defaultOrder = "STATE_REPORT_ORDER";
	
	protected function setListModel() {
		$this->listModel = "SELECT * FROM #pref#_state order by " . $this->listOrder;
	}
	
	protected function setEditModel() {
		$this->editModel = "SELECT * from #pref#_state WHERE " . "state" . "_id='" . $this->id . "'";
	}
	
	public function listAction() {
		parent::listAction ();
		$table = Pelican_Factory::getInstance ( 'List', "", "", 0, 0, 0, "liste" );
		$table->setFilterField ( "STATE_ID", "<b>" . t ( 'LOGIN' ) . "</b> :", array ("STATE_ID", "STATE_ID" ), "", "1", true, true );
		$table->setFilterField ( "STATE_LABEL", "<b>" . t ( 'FORM_LABEL' ) . "</b> :", array ("STATE_LABEL", "STATE_LABEL" ), 2 );
		$table->getFilter ( 3 );
		$table->setCSS ( array ("tblalt1", "tblalt2" ) );
		$table->setTableOrder ( "#pref#_state", "STATE_ID", "STATE_REPORT_ORDER" );
		$table->setValues ( $this->getListModel (), "STATE_ID" );
		$table->addColumn ( t ( 'ID' ), "STATE_ID", "10", "left", "", "tblheader" );
		$table->addColumn ( t ( 'FORM_LABEL' ), "STATE_LABEL", "90", "left", "", "tblheader" );
		
		$table->addInput ( t ( 'FORM_BUTTON_EDIT' ), "button", array ("id" => "STATE_ID" ), "center" );
		$table->addInput ( t ( 'POPUP_LABEL_DEL' ), "button", array ("id" => "STATE_ID", "" => "readO=true" ), "center" );
		$this->setResponse ( $table->getTable () );
	}
	
	public function editAction() {
		parent::editAction ();
		$form = $this->startStandardForm ();
		
		$form .= $this->oForm->createHidden ( $this->field_id, $this->id );
		$form .= $this->oForm->createInput ( "STATE_LABEL", t ( 'Label (state)' ), 255, "", true, $this->values ["STATE_LABEL"], $this->readO, 100 );
		$form .= $this->oForm->createInput ( "STATE_LABEL2", t ( 'Label (saction)' ), 255, "", true, $this->values ["STATE_LABEL2"], $this->readO, 100 );
		if (! $this->values ["STATE_REPORT_ORDER"]) {
			$this->values ["STATE_REPORT_ORDER"] = "999";
		}
		$form .= $this->oForm->createHidden ( "STATE_REPORT_ORDER", $this->values ["STATE_REPORT_ORDER"] );
		$form .= $this->oForm->createCheckBoxFromList ( "STATE_PUBLICATION", t ( 'Publication state' ), array (1 => "" ), $this->values ["STATE_PUBLICATION"], false, $this->readO );
		$form .= $this->stopStandardForm ();
		
		// Zend_Form start
		$form = formToString ( $this->oForm, $form );
		// Zend_Form stop
		
		$this->setResponse ( $form );
	}
	
	public static function path($oForm, $values, $readO, $multi) {
		
		$aDataValues = array ();
		
		$oConnection = Pelican_Db::getInstance ();
		$strSQL = "select STATE_ID as id, STATE_LABEL as lib from #pref#_state order by lib";
		$oForm->_getValuesFromSQL ( $oConnection, $strSQL, $aDataValues );
		
		$return = "<tr>\n";
		$return .= " <td class=\"formlib\" valign=\"top\">&nbsp;";
		$return .= "</td>\n";
		$return .= " <td class=\"formval\"><table border=\"0\"><tr><td width=\"33%\">";
		
		$return .= $oForm->_createCombo ( $multi . "STATE_PARENT_ID", t ( 'Start state' ), $aDataValues, $values ["STATE_PARENT_ID"], false, $readO, 1, false, 100, true, false, true, "", "", "", "", "", $aTag );
		
		$return .= "</td><td align=\"center\">";
		$return .= "-->";
		$return .= "</td><td width=\"33%\">";
		
		$return .= $oForm->_createCombo ( $multi . "STATE_ID", t ( 'Next state' ), $aDataValues, $values ["STATE_ID"], false, $readO, 1, false, 100, true, false, true, "", "", "", "", "", $aTag );
		
		$return .= "</td></tr></table></td></tr>";
		
		return $return;
	}
	
	public function beforeDelete() {
		$oConnection = Pelican_Db::getInstance ();
		$aBind [':STATE_ID'] = Pelican_Db::$values ['STATE_ID'];
		$oConnection->query ( "delete from #pref#_state_dependencies where STATE_ID=:STATE_ID OR STATE_PARENT_ID=:STATE_ID", $aBind );
	}
}