<?php

include_once (Pelican::$config ['APPLICATION_CONTROLLERS'] . '/Administration/Template.php');

class Administration_Build_Controller extends Pelican_Controller_Back {
	
	protected $administration = true;
	
	protected $form_name = "build";
	
	protected $field_id = "BUILD_ID";
	
	protected $defaultOrder = "BUILD_LABEL";
	
	protected $useConnection = false;
	
	protected function setListModel() {
		$this->listModel = "select * from #pref#_build
		order by " . $this->listOrder;
	}
	
	protected function setEditModel() {
		$this->editModel = "SELECT * from #pref#_build WHERE BUILD_ID='" . $this->id . "'";
	}
	
	public function listAction() {
		if ($_GET ['generate']) {
			$this->_build ();
		} else {
			parent::listAction ();
			$table = Pelican_Factory::getInstance ( 'List', "", "", 0, 0, 0, "liste" );
			$table->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", "BUILD_LABEL");
        	$table->getFilter(1);
			$table->setCSS ( array ("tblalt1", "tblalt2" ) );
			$table->setValues ( $this->getListModel (), "BUILD_ID" );
			$table->addColumn ( t ( 'ID' ), "BUILD_ID", "10", "left", "", "tblheader", "BUILD_ID" );
			$table->addColumn ( t ( 'NAME' ), "BUILD_LABEL", "80", "left", "", "tblheader", "BUILD_LABEL" );
			$table->addColumn ( t ( 'MODULE' ), "BUILD_MODULE", "10", "left", "", "tblheader", "BUILD_MODULE" );
			
			$table->addInput ( t ( 'FORM_BUTTON_EDIT' ), "button", array ("id" => "BUILD_ID" ), "center" );
			$table->addInput ( t ( 'POPUP_LABEL_DEL' ), "button", array ("id" => "BUILD_ID", "" => "readO=true" ), "center", array ("NB=0" ) );
			$response = $table->getTable () . "<br /><br />";
			
			$response .= Pelican_Html::button ( array (onclick => "document.location.href=document.location.href+'&generate=true'" ), "Générer les composants" );
			
			$this->setResponse ( $response );
		}
	
	}
	
	public function editAction() {
		parent::editAction ();
		$form = $this->startStandardForm ();
		
		$form .= $this->oForm->createInput ( "BUILD_TYPE", 'Type (Bloc/Contenu/Formulaire)', 1, "", true, $this->values ["BUILD_TYPE"], $this->readO, 1 );
		$form .= $this->oForm->createInput ( "BUILD_LABEL", t ( 'NAME' ), 100, "", true, $this->values ["BUILD_LABEL"], $this->readO, 100 );
		$form .= $this->oForm->createInput ( "BUILD_BO_PATH", 'BO', 255, "", false, $this->values ["BUILD_BO_PATH"], $this->readO, 50 );
		$form .= $this->oForm->createInput ( "BUILD_FO_PATH", 'FO', 255, "", false, $this->values ["BUILD_FO_PATH"], $this->readO, 50 );
		$form .= $this->oForm->createCheckBoxFromList ( "BUILD_MODULE", t ( 'MODULE' ), array ("1" => "" ), $this->values ["BUILD_MODULE"], false, $this->readO, "h" );
		$form .= $this->stopStandardForm ();
		
		// Zend_Form start
		$form = formToString ( $this->oForm, $form );
		// Zend_Form stop
		

		$this->setResponse ( $form );
	}
	
	private function _build() {
		$title = 'Citroën'; //APP_TITLE;
		$oConnection = Pelican_Db::getInstance ();
		$build = $oConnection->queryTab ( 'select * from #pref#_build' );
		if ($build) {
			foreach ( $build as $item ) {
				if (! $item ['BUILD_MODULE']) {
					$item ['BUILD_LABEL'] = '(' . $title . ') ' . $item ['BUILD_LABEL'];
				}
				$fields = $this->_buildFields ( $title, $item ['BUILD_TYPE'], $item );
				$this->_buildFile ( $title, $fields, $item ['BUILD_TYPE'], $item ['BUILD_BO_PATH'], $item ['BUILD_FO_PATH'], $item ['BUILD_FIELDID'], ($item ['BUILD_MODULE'] ? $item ['BUILD_LABEL']:'')  );
				$this->_executeSql ( $title, $item ['BUILD_TYPE'], $item );
			}
		}
	}
	
	private function _buildFile($project, $fields = array(), $type, $bo = '', $fo = '', $fieldid = '', $module = '') {
		$build = '/_build';
		$site = 'sites';
		if ($module) {
			$site = 'modules/' . strtolower($module);
		}
		$root = Pelican::$config ["DOCUMENT_INIT"] . $build . '/application/' . $site;
		$rootBo = $root . '/backend';
		$rootFo = $root . '/frontend';
		
		switch ($type) {
			case 'B' :
				{
					if (! empty ( $fo )) {
						//Layout_XXXXX
						$path ['fo'] ['php'] ['content'] = Administration_Template_Controller::getSkeleton ( 20, $fo );
						$path ['fo'] ['tpl'] ['content'] = '';
						
						$path ['fo'] ['php'] ['path'] = $rootFo . '/controllers/' . str_replace ( '_', '/', $fo ) . '.php';
						$path ['fo'] ['tpl'] ['path'] = str_replace ( 'controllers', 'views/scripts', $rootFo . '/controllers/' . str_replace ( '_', '/', $fo ) . '/index.tpl' );
					}
					
					if (! empty ( $bo )) {
						//Cms_Page_Module_XXXXX
						$path ['bo'] ['php'] ['content'] = str_replace ( array ("// {FORM}" ), array ($fields ['input'] ), Administration_Template_Controller::getSkeleton ( 30, $bo ) );
						
						$path ['bo'] ['php'] ['path'] = $rootBo . '/controllers/' . str_replace ( '_', '/', $bo ) . '.php';
					}
					break;
				
				}
			case 'C' :
				{
					if (! empty ( $fo )) {
						// Content_XXXX
						$path ['fo'] ['php'] ['path'] = $rootFo . '/controllers/' . str_replace ( '_', '/', $fo ) . '.php';
						$path ['fo'] ['php'] ['content'] = Administration_Template_Controller::getSkeleton ( 4, $fo );
						$path ['fo'] ['tpl'] ['path'] = str_replace ( 'controllers', 'views/scripts', $rootFo . '/controllers/' . str_replace ( '_', '/', $fo ) . '/index.tpl' );
						$path ['fo'] ['tpl'] ['content'] = '';
					}
					if (! empty ( $bo )) {
						//Cms_Content_Module_XXXX
						$path ['bo'] ['php'] ['path'] = $rootBo . '/controllers/' . str_replace ( '_', '/', $bo ) . '.php';
						$path ['bo'] ['php'] ['content'] = str_replace ( array ("// {FORM}" ), array ($fields ['input'] ), Administration_Template_Controller::getSkeleton ( 3, $bo ) );
					}
					break;
				
				}
			case 'F' :
				{
					if (! empty ( $bo )) {
						
						$path ['bo'] ['php'] ['path'] = $rootBo . '/controllers/' . str_replace ( '_', '/', $bo ) . '.php';
						$path ['bo'] ['php'] ['content'] = str_replace ( array ("// {LIST}", "// {FORM}" ), array ($fields ['list'], $fields ['input'] ), Administration_Template_Controller::getSkeleton ( 1, $bo, str_replace ( '_ID', '', $fieldid ) ) );
					}
					break;
				
				}
			case 'N' :
				{
					if (! empty ( $bo )) {
						$path ['bo'] ['php'] ['path'] = $rootBo . '/controllers/' . str_replace ( '_', '/', $bo ) . '.php';
						$path ['bo'] ['php'] ['content'] = Administration_Template_Controller::getSkeleton ( 2, $bo );
					}
					break;
				
				}
		}
		
		if ($module) {
			$path ['module'] ['config'] ['path'] = $root . '/config';
			$path ['module'] ['config'] ['content'] = Administration_Template_Controller::getSkeleton ( 100, $module );
			$path ['module'] ['init'] ['path'] = $root . '/'.$module.'.php';
			$path ['module'] ['init'] ['content'] = Administration_Template_Controller::getSkeleton ( 110, $module );
		}
		
		foreach ( $path as $site => $path2 ) {
			foreach ( $path2 as $pathType => $item ) {
				$dir = dirname ( $item ['path'] );
				if (! is_dir ( $dir )) {
					mkdir ( $dir, 0777, true );
				}
				file_put_contents ( $item ['path'], $item ['content'] );
				echo 'creation de ' . $item ['path'] . '<br />';
			}
		}
	}
	
	public function _executeSql($project, $type, $values) {
		static $zoneCategoryId;
		
		$oConnection = Pelican_Db::getInstance ();
		
		$values ['TEMPLATE_LABEL'] = $values ['BUILD_LABEL'];
		$values ['ZONE_LABEL'] = $values ['BUILD_LABEL'];
		$values ['TEMPLATE_PATH'] = $values ['BUILD_BO_PATH'];
		$values ['ZONE_BO_PATH'] = $values ['BUILD_BO_PATH'];
		$values ['TEMPLATE_PATH_FO'] = $values ['BUILD_FO_PATH'];
		$values ['ZONE_FO_PATH'] = $values ['BUILD_FO_PATH'];
		$values ['ZONE_AJAX'] = 0;
		$values ['ZONE_IFRAME'] = 0;
		$values ['PLUGIN_ID'] = ($values ['BUILD_MODULE'] ? strtolower ( $values ['BUILD_LABEL'] ) : '');
		if (empty ( $values ['BUILD_BO_PATH'] )) {
			$values ['ZONE_TYPE_ID'] = 'auto';
		} else {
			$values ['ZONE_TYPE_ID'] = 'param';
		}
		
		$plugin = '';
		if ($values ['PLUGIN_ID']) {
			$plugin = strtolower ( $values ['PLUGIN_ID'] );
		
		}
		switch ($type) {
			case 'B' :
				{
					if (empty ( $zoneCategoryId )) {
						$zoneCategoryId = self::getSqlIdFromLabel ( '#pref#_zone_category', 'ZONE_CATEGORY_ID', 'ZONE_CATEGORY_LABEL', $project, true );
					}
					
					$zoneType ['param'] = 1;
					$zoneType ['auto'] = 2;
					$zoneType ['herit'] = 3;
					
					Pelican_Db::$values = array ();
					Pelican_Db::$values ['ZONE_ID'] = self::getSqlIdFromLabel ( '#pref#_zone', 'ZONE_ID', 'ZONE_LABEL', $values ['ZONE_LABEL'], false );
					Pelican_Db::$values ['ZONE_TYPE_ID'] = $zoneType [$values ['ZONE_TYPE_ID']];
					Pelican_Db::$values ['ZONE_LABEL'] = $values ['ZONE_LABEL'];
					Pelican_Db::$values ['ZONE_FREE'] = 0;
					Pelican_Db::$values ['ZONE_COMMENT'] = '';
					Pelican_Db::$values ['ZONE_BO_PATH'] = $values ['ZONE_BO_PATH'];
					Pelican_Db::$values ['ZONE_FO_PATH'] = $values ['ZONE_FO_PATH'];
					Pelican_Db::$values ['ZONE_IFRAME'] = $values ['ZONE_IFRAME'];
					Pelican_Db::$values ['ZONE_AJAX'] = $values ['ZONE_AJAX'];
					Pelican_Db::$values ['ZONE_PROGRAM'] = 0;
					Pelican_Db::$values ['ZONE_DB_MULTI'] = 0;
					Pelican_Db::$values ['ZONE_IMAGE'] = '';
					Pelican_Db::$values ['ZONE_CATEGORY_ID'] = $zoneCategoryId;
					Pelican_Db::$values ['ZONE_CONTENT'] = 0;
					Pelican_Db::$values ['PLUGIN_ID'] = $plugin;
					
					$table = '#pref#_zone';
					$where = 'ZONE_ID = ' . Pelican_Db::$values ['ZONE_ID'];
					$id = 'ZONE_ID';
					break;
				
				}
			case 'C' :
				{
					
					Pelican_Db::$values = array ();
					Pelican_Db::$values ['TEMPLATE_ID'] = self::getSqlIdFromLabel ( '#pref#_template', 'TEMPLATE_ID', 'TEMPLATE_LABEL', $values ['TEMPLATE_LABEL'], false );
					Pelican_Db::$values ['TEMPLATE_TYPE_ID'] = 3;
					Pelican_Db::$values ['TEMPLATE_GROUP_ID'] = 1;
					Pelican_Db::$values ['TEMPLATE_LABEL'] = $values ['TEMPLATE_LABEL'];
					Pelican_Db::$values ['TEMPLATE_PATH'] = $values ['TEMPLATE_PATH'];
					Pelican_Db::$values ['TEMPLATE_PATH_FO'] = '';
					Pelican_Db::$values ['TEMPLATE_COMPLEMENT'] = '';
					Pelican_Db::$values ['PLUGIN_ID'] = $plugin;
					
					$table = '#pref#_template';
					$where = 'TEMPLATE_ID = ' . Pelican_Db::$values ['TEMPLATE_ID'];
					$id = 'TEMPLATE_ID';
					break;
				
				}
			case 'F' :
				{
					
					Pelican_Db::$values = array ();
					Pelican_Db::$values ['TEMPLATE_ID'] = self::getSqlIdFromLabel ( '#pref#_template', 'TEMPLATE_ID', 'TEMPLATE_LABEL', $values ['TEMPLATE_LABEL'], false );
					Pelican_Db::$values ['TEMPLATE_TYPE_ID'] = 1;
					Pelican_Db::$values ['TEMPLATE_GROUP_ID'] = 1;
					Pelican_Db::$values ['TEMPLATE_LABEL'] = $values ['TEMPLATE_LABEL'];
					Pelican_Db::$values ['TEMPLATE_PATH'] = $values ['TEMPLATE_PATH'];
					Pelican_Db::$values ['TEMPLATE_PATH_FO'] = $values ['TEMPLATE_PATH_FO'];
					Pelican_Db::$values ['TEMPLATE_COMPLEMENT'] = '';
					Pelican_Db::$values ['PLUGIN_ID'] = $plugin;
					
					$table = '#pref#_template';
					$where = 'TEMPLATE_ID = ' . Pelican_Db::$values ['TEMPLATE_ID'];
					$id = 'TEMPLATE_ID';
					break;
				
				}
		}
		
		if (! empty ( $table )) {
			$oConnection->replaceQuery ( $table, $where );
		}
		
		if ($type == 'C') {
			Pelican_Db::$values ['CONTENT_TYPE_ID'] = self::getSqlIdFromLabel ( '#pref#_content_type', 'CONTENT_TYPE_ID', 'CONTENT_TYPE_LABEL', $values ['TEMPLATE_LABEL'], false );
			Pelican_Db::$values ['CONTENT_TYPE_LABEL'] = $values ['TEMPLATE_LABEL'];
			$oConnection->replaceQuery ( '#pref#_content_type', 'CONTENT_TYPE_ID=' . Pelican_Db::$values ['CONTENT_TYPE_ID'] );
		}
		
		return Pelican_Db::$values [$id];
	}
	
	public static function getSqlIdFromLabel($table, $fieldid, $fieldLabel, $label, $create = false) {
		$oConnection = Pelican_Db::getInstance ();
		
		$aBind [':LABEL'] = $oConnection->strToBind ( $label );
		$id = $oConnection->queryItem ( 'select ' . $fieldid . ' from ' . strtolower ( $table ) . ' where ' . $fieldLabel . '=:LABEL', $aBind );
		if (empty ( $id )) {
			$id = - 2;
		}
		
		if ($id == - 2 && $create) {
			Pelican_Db::$values = array ();
			Pelican_Db::$values [$fieldid] = - 2;
			Pelican_Db::$values [$fieldLabel] = $label;
			$oConnection->insertQuery ( $table );
			$id = Pelican_Db::$values [$fieldid];
		}
		
		return $id;
	}
	
	private function _buildFields($project, $type, $values) {
		$oConnection = Pelican_Db::getInstance ();
		
		if ($values ['BUILD_TYPE'] == 'F') {
			$listData = $oConnection->queryTab ( "select * from #pref#_build_input where BUILD_ID=" . $values ['BUILD_ID'] . " and BUILD_INPUT_LIST is not null AND BUILD_INPUT_LIST>''" );
			if ($listData) {
				$col = count ( $listData ) + 1;
				$aList [] = '$table->setValues($this->getListModel(), "' . $values ['BUILD_FIELDID'] . '");';
				$aList [] = '$table->addColumn(t(\'ID\'), "' . $values ['BUILD_FIELDID'] . '", "10", "left", "", "tblheader");';
				foreach ( $listData as $item ) {
					$aList [] = $this->_buildList ( $item, $col );
				}
				$aList [] = '$table->addInput(t(\'FORM_BUTTON_EDIT\'), "button", array("id" => "' . $values ['BUILD_FIELDID'] . '"), "center");';
				$aList [] = '$table->addInput(t(\'POPUP_LABEL_DEL\'), "button", array("id" => "' . $values ['BUILD_FIELDID'] . '", "" => "readO=true"), "center");';
				$return ['list'] = implode ( "\r\n        ", $aList );
			}
		}
		
		$inputData = $oConnection->queryTab ( "select * from #pref#_build_input where BUILD_ID=" . $values ['BUILD_ID'] . " and BUILD_INPUT_FIELD is not null AND BUILD_INPUT_FIELD>''" );
		if ($inputData) {
			$this->useConnection = false;
			foreach ( $inputData as $input ) {
				$aInput [] = $this->_buildInput ( $input, $type );
			}
			$return ['input'] = implode ( "\r\n        ", $aInput );
			if ($this->useConnection) {
				$return ['input'] = "\$oConnection = Pelican_Db::getInstance();\r\n        " . $return ['input'];
			}
		}
		
		return $return;
	
	}
	
	private function _buildList($values, $col) {
		return '$table->addColumn("' . $values ['BUILD_INPUT_LABEL'] . '", "' . $values ['BUILD_INPUT_FIELD'] . '", "' . ( int ) (100 / $col) . '", "left", "", "tblheader", "' . $values ['BUILD_INPUT_FIELD'] . '");';
	}
	
	private function _buildInput($values, $type) {
		
		switch ($type) {
			case 'B' :
				{
					$prefixe = '$controller->multi . ';
					$object = '$controller';
					$valueArray = "zoneValues";
					$form = '$return .= ' . $object . '->oForm';
					break;
				}
			case 'C' :
				{
					$prefixe = '';
					$object = '$controller';
					$valueArray = "values";
					$form = '$return .= ' . $object . '->oForm';
					break;
				}
			case 'F' :
				{
					$prefixe = '';
					$object = '$this';
					$valueArray = "values";
					$form = '$form .= ' . $object . '->oForm';
					break;
				}
		}
		
		$value ['name'] = $values ['BUILD_INPUT_FIELD'];
		$value ['lib'] = $values ['BUILD_INPUT_LABEL'];
		$value ['control'] = $values ['BUILD_INPUT_CONTROL'];
		$value ['obl'] = ($values ['BUILD_INPUT_MANDATORY'] ? 'true' : 'false');
		$value ['maxlong'] = $values ['BUILD_INPUT_MAX'];
		$value ['value'] = $object . "->" . $valueArray . "['" . $values ['BUILD_INPUT_FIELD'] . "']";
		$value ['reado'] = $object . "->readO";
		$value ['size'] = $values ['BUILD_INPUT_LENGTH'];
		$value ['event'] = "";
		$value ['multiple'] = 'false';
		$value ['tablename'] = "";
		$value ['refcoltablenamee'] = "contenu_id";
		$value ['id'] = 0;
		$value ['selectedvalue'] = '""';
		$value ['checkedvalue'] = '""';
		$value ['reftablename'] = '';
		$value ['googlekey'] = '""';
		
		$return = '';
		switch ($values ['BUILD_INPUT_TYPE']) {
			case 'input' :
				{
					$return = $form . '->createInput(' . $prefixe . '"' . $value ['name'] . '", "' . $value ['lib'] . '", ' . $value ['maxlong'] . ' , "' . $value ['control'] . '" , ' . $value ['obl'] . ' , ' . $value ['value'] . ' , ' . $value ['reado'] . ', ' . $value ['size'] . ', false, "' . $value ['event'] . '" , "text");';
					break;
				}
			case 'combo' :
				{
					$this->useConnection = true;
					$return = $form . '->createCombo($oConnection, ' . $prefixe . '"' . $value ['name'] . '", "' . $value ['lib'] . '", "' . $value ['tablename'] . '", "' . $value ['reftablename'] . '" , ' . $value ['id'] . ' , ' . $value ['selectedvalue'] . ' , ' . $value ['obl'] . ' , ' . $value ['reado'] . ' , ' . $value ['size'] . ', ' . $value ['multiple'] . ' , "", true, false, false, "' . $value ['event'] . '" );';
					break;
				}
			case 'checkbox' :
				{
					if ($values ['BUILD_INPUT_ADDON'] == 'oui,non') {
						$return = 'if (' . $object . '->form_action == Pelican_Db::DATABASE_INSERT) {
        	' . $value ['value'] . ' = 1;
        }
        ' . $form . '->createCheckBoxFromList(' . $prefixe . '"' . $value ['name'] . '", "' . $value ['lib'] . '", array("1" => ""), ' . $value ['value'] . ', ' . $value ['obl'] . ', ' . $value ['reado'] . ', "h");';
					} else {
						$this->useConnection = true;
						$return = $form . '->createCheckBox($oConnection, ' . $prefixe . '"' . $value ['name'] . '", "' . $value ['lib'] . '", "' . $value ['tablename'] . '", "' . $value ['reftablename'] . '" , ' . $value ['id'] . ' , ' . $value ['checkedvalue'] . ' , ' . $value ['obl'] . ' , ' . $value ['reado'] . ' , "h", false, "' . $value ['event'] . '" , "' . $value ['refcoltablenamee'] . '" );';
					}
					break;
				}
			case 'radio' :
				{
					if (trim ( $values ['BUILD_INPUT_ADDON'] ) == 'oui,non') {
						$return = 'if (' . $object . '->form_action == Pelican_Db::DATABASE_INSERT) {
        	' . $value ['value'] . ' = 1;
        }
        ' . $form . '->createRadioFromList(' . $prefixe . '"' . $value ['name'] . '", "' . $value ['lib'] . '", array("1" => "oui", "0" => "non"), ' . $value ['value'] . ', ' . $value ['obl'] . ', ' . $value ['reado'] . ', "h");';
					} else {
						$this->useConnection = true;
						$return = $form . '->createRadio($oConnection, ' . $prefixe . '"' . $value ['name'] . '", "' . $value ['lib'] . '", "' . $value ['tablename'] . '", $aValue, ' . $value ['obl'] . ' , ' . $value ['reado'] . ' , "h", false, "' . $value ['event'] . '" );';
					}
					break;
				}
			case 'assoc' :
				{
					$this->useConnection = true;
					$return = $form . '->createAssoc($oConnection, ' . $prefixe . '"' . $value ['name'] . '", "' . $value ['lib'] . '", "' . $value ['tablename'] . '", "' . $value ['reftablename'] . '" , "' . $value ['id'] . '" , ' . $value ['selectedvalue'] . ' , ' . $value ['obl'] . ' , true, false, false, ' . $value ['reado'] . ' , "5", 200, "' . $value ['refcoltablenamee'] . '" , false,  "",  false,  false, "");';
					break;
				}
			case 'editor' :
				{
					$return = $form . '->createEditor(' . $prefixe . '"' . $value ['name'] . '", "' . $value ['lib'] . '", ' . $value ['obl'] . ' , ' . $value ['value'] . ' , ' . $value ['reado'] . ');';
					break;
				}
			case 'textarea' :
				{
					$return = $form . '->createTextArea(' . $prefixe . '"' . $value ['name'] . '", "' . $value ['lib'] . '", ' . $value ['obl'] . ' , ' . $value ['value'] . ' , ' . $value ['maxlong'] . ' , ' . $value ['reado'] . ' , 5, 30, false, "", true, "' . $value ['event'] . '" );';
					break;
				}
			case 'date' :
				{
					$return = $form . '->createDateTime(' . $prefixe . '"' . $value ['name'] . '", "' . $value ['lib'] . '", ' . $value ['obl'] . ' , ' . $value ['value'] . ' , ' . $value ['reado'] . ' , false, "' . $value ['event'] . '" );';
					break;
				}
			case 'password' :
				{
					$return = $form . '->createPassword(' . $prefixe . '"' . $value ['name'] . '", "' . $value ['lib'] . '", ' . $value ['maxlong'] . ', ' . $value ['obl'] . ' , ' . $value ['value'] . ' , ' . $value ['reado'] . ' , ' . $value ['size'] . ' = "10", false, "' . $value ['event'] . '" );';
					break;
				}
			case 'map' :
				{
					$return = $form . '->createMap(' . $prefixe . '"' . $value ['name'] . '", "' . $value ['lib'] . '", ' . $value ['obl'] . ' , ' . $value ['googlekey'] . ' , $strAddressValue , $strLatValue , $strLongValue , ' . $value ['reado'] . ' , false, "' . $value ['event'] . '");';
					break;
				}
			case 'hidden' :
				{
					$return = $form . '->createHidden(' . $prefixe . '"' . $value ['name'] . '", ' . $value ['value'] . ' , false, ' . $value ['multiple'] . ' );';
					break;
				}
			case 'label' :
				{
					$return = $form . '->createLabel("' . $value ['lib'] . '", ' . $value ['value'] . ', false);';
					break;
				}
			case 'image' :
				{
					$return = $form . '->createImage(' . $prefixe . '"' . $value ['name'] . '", "' . $value ['lib'] . '", ' . $value ['obl'] . ' , "", ' . $value ['value'] . ' , ' . $value ['reado'] . ' , false);';
					break;
				}
			case 'file' :
				{
					$return = $form . '->createFile(' . $prefixe . '"' . $value ['name'] . '", "' . $value ['lib'] . '", ' . $value ['obl'] . ' , "", ' . $value ['value'] . ' , ' . $value ['reado'] . ' , false);';
					break;
				}
			case 'flash' :
				{
					$return = $form . '->createFlash(' . $prefixe . '"' . $value ['name'] . '", "' . $value ['lib'] . '", ' . $value ['obl'] . ' , "", ' . $value ['value'] . ' , ' . $value ['reado'] . ' , false);';
					break;
				}
			case 'media' :
				{
					$return = $form . '->createMedia(' . $prefixe . '"' . $value ['name'] . '", "' . $value ['lib'] . '", ' . $value ['obl'] . ' , "image", "", ' . $value ['value'] . ' , ' . $value ['reado'] . ' , true, false);';
					break;
				}
		}
		
		return $return;
	}

}