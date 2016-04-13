<?php

class Portal_Controller extends Pelican_Controller_Front {
	
	public $oUser;
	
	public $langue = true;
	
	public $form_name = "page_zone";
	public $field_id = "PAGE_ID";
	public $form_path = "/layout/portal";
	public $form_action = "UPD";
	public $form_button = "save";
	
	public function getUser() {
		if (! $this->oUser) {
			$this->oUser = Pelican_Factory::getUser ( 'Portal' );
		}
		return $this->oUser;
	}
	
	public function headerAction() {
		$data = $this->getParams ();
		
		$oUser = $this->getUser ();
		
		if ($oUser->isLoggedIn ()) {
			$this->assign ( 'userid', $oUser->get ( 'id' ) );
			$this->assign ( 'isLogged', true );
		} else {
			$this->assign ( 'isLogged', false );
			$this->assign ( 'aMessage', $_SESSION [APP] [Pelican::$config ["AUTH_ERROR_SESSION"]] );
			unset ( $_SESSION [APP] [Pelican::$config ["AUTH_ERROR_SESSION"]] );
		}
		
		$this->assign ( "base_url", Pelican::$config ['LIB_PATH'] . Pelican::$config ['LIB_FRONT'] . '/portal' );
		$this->assign ( "tpl", $data ["TEMPLATE_PAGE_ID"] );
		$this->assign ( "aMode", array (0 => "Normal", 1 => "Edition" ) );
		$this->fetch ();
	}
	
	public function layoutAction() {
		$data = $this->getParams ();
		$oUser = $this->getUser ();
		$zoneStart = Zone_Portal::getEditableZoneStart ( $data, $oUser );
		$zoneEnd = Zone_Portal::getEditableZoneEnd ( $data );
		$this->assign ( "zoneStart", $zoneStart );
		$this->assign ( "zoneEnd", $zoneEnd );
		$this->fetch ();
	
	}
	
	public function addAction() {
		$data = $this->getParams ();
		$oUser = $this->getUser ();
		if (! $data ["AREA_ID"]) {
			// cas 1 : pas de Pelican_Index_Frontoffice_Zone (area) spécifiée
			// pour l'ajout
			// récupération des types de blocs disponibles pour cette page
			$aZones = Zone_Portal::getAvailableZoneTemplatesForPageTemplate ( $data ["TEMPLATE_PAGE_ID"] );
			$this->assign ( "aZones", $aZones );
			// récupération des types de blocs sélectionnés pour cette page pour
			// le user courant
			$aBind [":PORTAL_USER_ID"] = $oConnection->strToBind ( $oUser->get ( "id" ) );
			$aBind [":PAGE_ID"] = $data ["PAGE_ID"];
			$oConnection->query ( "select zt.ZONE_TEMPLATE_ID as \"id\" from " . Pelican::$config ['FW_PREFIXE_TABLE'] . "portal_user_zone_template uzt inner join " . Pelican::$config ['FW_PREFIXE_TABLE'] . "zone_template zt on (uzt.ZONE_TEMPLATE_ID=zt.ZONE_TEMPLATE_ID) where uzt.PORTAL_USER_ID=:PORTAL_USER_ID AND uzt.PAGE_ID=:PAGE_ID AND zt.TEMPLATE_PAGE_ID=:TEMPLATE_PAGE_ID", $aBind );
			$aSelected = $oConnection->data ["id"];
			$this->assign ( "aSelected", $aSelected );
			$this->assign ( "tpl", $data ["TEMPLATE_PAGE_ID"] );
			$this->assign ( "pid", $data ["PAGE_ID"] );
			$this->assign ( "aid", "" );
		} else {
			// cas 2 : Pelican_Index_Frontoffice_Zone (area) spécifiée pour
			// l'ajout
			// récupération des types de blocs disponibles pour cette page
			$aZones = Zone_Portal::getAvailableZonesForPageTemplate ( $data ["TEMPLATE_PAGE_ID"] );
			$this->assign ( "aZones", $aZones );
			// récupération des types de blocs sélectionnés pour cette page pour
			// le user courant
			$this->assign ( "tpl", $data ["TEMPLATE_PAGE_ID"] );
			$this->assign ( "pid", $data ["PAGE_ID"] );
			$this->assign ( "aid", $data ["AREA_ID"] );
		}
		$this->fetch ();
	}
	
	public function blocAction() {
		$data = $this->getParams ();
		$oUser = $this->getUser ();
		
		$datBloc = explode ( '#', $data ["ZONE_PARAMETERS"] );
		$this->assign ( "page", $datBloc [0] );
		$this->assign ( "bloc", $datBloc [1] );
		$this->assign ( "version", $datBloc [2] );
		$this->assign ( "langue", $datBloc [3] );
		
		if ($datBloc [0] && $datBloc [1] && $datBloc [2] && $datBloc [3]) {
			$z = Pelican_Cache::fetch ( 'Portal/Bloc', array ($datBloc [0], $datBloc [1], $oUser->get ( 'id' ), $datBloc [3], $datBloc [2] ) );
			switch ($data ["ZONE_MODE"]) {
				case "EDIT" :
					$this->assign ( "blocContent", Zone_Portal::templateEdit ( $z, false, $oUser ) );
					break;
				case "RELOAD" :
					$zoneStart = Zone_Portal::getMoveableZoneStart ( $z, $oUser );
					$zoneEnd = Zone_Portal::getMoveableZoneEnd ( $z );
					$this->assign ( "zoneStart", $zoneStart );
					$this->assign ( "zoneEnd", $zoneEnd );
					$this->assign ( "blocContent", templateSmarty ( $z ["ZONE_FO_PATH"], false, "", $z, 0, false, $oUser ) );
					break;
				default :
					$this->assign ( "zoneStart", "" );
					$this->assign ( "zoneEnd", "" );
					$this->assign ( "blocContent", templateSmarty ( $z ["ZONE_FO_PATH"], false, "", $z, 0, false, $oUser ) );
					break;
			}
		} else {
			$this->assign ( "blocContent", "" );
		}
		$this->fetch ();
	}
	
	protected function setListModel() {
		$this->listModel = "SELECT
    	distinct (pz.ZONE_TEMPLATE_ID),
    	pz.ZONE_TITRE,
    	zt.ZONE_TEMPLATE_LABEL,
    	pv.PAGE_VERSION,
    	pv.LANGUE_ID,
    	pv.PAGE_TITLE as PAGE_TITLE,
    	pc.PAGE_ID
    	FROM
    	" . Pelican::$config ['FW_PREFIXE_TABLE'] . "page_zone pz
    	INNER JOIN " . Pelican::$config ['FW_PREFIXE_TABLE'] . "page p ON (p.PAGE_ID=pz.PAGE_ID AND p.PAGE_CURRENT_VERSION=pz.PAGE_VERSION AND p.LANGUE_ID=:LANGUE_ID)
    	LEFT JOIN " . Pelican::$config ['FW_PREFIXE_TABLE'] . "page_version pv ON (p.PAGE_ID=pv.PAGE_ID AND p.PAGE_CURRENT_VERSION=pv.PAGE_VERSION AND pv.LANGUE_ID=:LANGUE_ID)
    	INNER JOIN " . Pelican::$config ['FW_PREFIXE_TABLE'] . "zone_template zt ON (pz.ZONE_TEMPLATE_ID=zt.ZONE_TEMPLATE_ID)
    	INNER JOIN " . Pelican::$config ['FW_PREFIXE_TABLE'] . "zone z ON (zt.ZONE_ID=z.ZONE_ID)
    	";
		
		$this->listModel .= " WHERE ZONE_TYPE_ID=:ZONE_TYPE_ID" . " " . $conditions . "
    	ORDER BY :ORDRE";
	
	}
	
	/**
	 * Affichage de la combo des types de contenu pour création
	 */
	public function listAction() {
		parent::listAction ();
		$table = Pelican_Factory::getInstance ( 'List', "", "", 0, 0, 0, "liste", "", true, true );
		$table->setFilterField ( "rechercheTypeBloc", "<b>Type de bloc&nbsp;:</b><br />", "", $aBlocTypes, "", 1, false );
		$aPages = Pelican_Cache::fetch ( "Backend/Page", array ($_SESSION [APP] ['SITE_ID'], $_SESSION [APP] ['LANGUE_ID'], "", true ) );
		
		if ($aPages) {
			foreach ( $aPages as $key => $value ) {
				$aPages [$key] [0] = $value ["id"];
				$aPages [$key] [1] = $value ["lib"];
			}
		}
		
		$table->setFilterField ( "recherchePage", "<b>Rubrique&nbsp;:</b><br />", "", $aPages, "", 1, false );
		$table->getFilter ( 2 );
		
		echo ("<br />");
		/**
		 * dans le cas de la popup on limite à 6 pages à la fois
		 */
		$table->setCSS ( array ("tblalt1", "tblalt2" ) );
		$table->setValues ( $this->getListModel (), "pz.ZONE_TEMPLATE_ID", "", $aBind );
		// $table->setTableOrder( $aBind[':ORDRE'] );
		
		// $table->addColumn(t('index_assoc_bloc_ID'), "ZONE_TEMPLATE_ID", "10",
		// "center", "", "tblheader", "ZONE_TEMPLATE_ID", false, '', 0, 1, 1);
		$table->addColumn ( t ( 'index_assoc_bloc_ID' ), "ZONE_TEMPLATE_ID", "10", "center", "", "tblheader", "", false, '', 0, 1, 1 );
		
		$table->addColumn ( t ( 'index_assoc_bloc_title' ), "ZONE_TITRE", "60", "left", "", "tblheader", "" );
		$table->addColumn ( t ( 'index_assoc_bloc_page' ), "PAGE_TITLE", "60", "left", "", "tblheader", "" );
		
		if (! $aBind [':ZONE_TYPE_ID']) {
			$table->addColumn ( t ( 'index_assoc_bloc_type' ), "ZONE_TEMPLATE_LABEL", "50", "left", "", "tblheader", "" );
		}
		
		if ($_GET ["action"] == "duplicate_zone") {
			$ajaxJsCall = Pelican_Factory::staticCall ( Pelican::getAjaxEngine (), 'getJsCall' );
			$table->addInput ( t ( 'form_content_duplicate_to_bloc' ), "button", array ("#PID#" => "PAGE_ID", "#ZID#" => "ZONE_TEMPLATE_ID", "#LIB#" => "ZONE_TITRE" ), "center", "", "tblheader", 0, 1, 1, "", array ("onclick" => $ajaxJsCall . "('/controllers/Remote/Ajax/duplicateBloc', '#PID#', '#ZID#', '" . $aBind [':LANGUE_ID'] . "', '" . $_GET ['pid'] . "', '" . $_GET ['aid'] . "' )" ) );
		} elseif ($_GET ["action"] == "assoc_bloc") {
			$table->addInput ( t ( 'index_assoc_bloc_select' ), "button", array ("#PID#" => "PAGE_ID", "#ID#" => "ZONE_TEMPLATE_ID", "#LIB#" => "ZONE_TITRE" ), "center", "", "tblheader", 0, 1, 1, "", array ("onclick" => "parent.associate( '" . $_GET ['param'] . "', '#PID###ID#', 'Page #PID# - Bloc #ID#');window.close()" ) );
		} else {
			$table->addInput ( t ( 'form_generique_modifier' ), "button", array ("pid" => "PAGE_ID", "pver" => "PAGE_VERSION", "ztid" => "ZONE_TEMPLATE_ID", "lid" => 'LANGUE_ID' ), "center" );
		}
		
		echo ($table->getTable ());
		
		echo ("<br /><br />");
	
	}
	
	public function editAction() {
		$page_id = ($page_id ? $page_id : $_GET ["pid"]);
		$langue_id = ($langue_id ? $langue_id : $_GET ["lid"]);
		$page_version = ($page_version ? $page_version : $_GET ["pver"]);
		$zone_template_id = ($zone_template_id ? $zone_template_id : $_GET ["ztid"]);
		
		// require_once(Pelican::$config["CONTROLLERS_ROOT"] .
		// "/template_include.php");
		
		// $type_workflow = "ZONE";
		// $version = false;
		
		// Pelican::$frontController->getLanguageView();
		Pelican::$frontController->form_action = Pelican::$config ['DATABASE_UPDATE'];
		// debug(Pelican::$config["DB_PATH"]);
		Pelican::$frontController->form_retour = Pelican_Text::htmlentities ( $_SERVER ["REQUEST_URI"] . "&reload=true" );
		
		/**
		 * On masque le bouton ajout, on affiche à la place la combo des types
		 * de contenu
		 */
		$ajout = $urlAjout;
		$urlAjout = "";
		$multi = "";
		
		initTemplate ( $aBind );
		
		if (! ($page_id && $langue_id && $page_version && $zone_template_id)) {
			// mode liste
			
			// //////////////////////////////////////////////////////////////////////
			// Initialisation
			// //////////////////////////////////////////////////////////////////////
			
			unset ( $_SESSION [APP] ["bloc_search"] );
			$_GET ["rechercheBlocType"] = "";
			$_GET ["recherchePage"] = "";
			if ($_GET ["filter_recherchePage"]) {
				$_GET ["recherchePage"] = $_GET ["filter_recherchePage"];
			}
			if ($_GET ["filter_rechercheTypeBloc"]) {
				$_GET ["rechercheBlocType"] = $_GET ["filter_rechercheTypeBloc"];
			}
			
			// Création des variables de session
			$_SESSION [APP] ["bloc_search"] ["rechercheBlocType"] = $_GET ["filter_rechercheTypeBloc"];
			$_SESSION [APP] ["bloc_search"] ["recherchePage"] = $_GET ["filter_recherchePage"];
			
			$aBind [':LANGUE_ID'] = ($_GET ["langue"] ? $_GET ["langue"] : $_SESSION [APP] ["EDIT_LANGUE_ID"]);
			
			if ($_GET ["order"]) {
				$aBind [':ORDRE'] = $oConnection->strToBind ( $_GET ["order"] );
			} else {
				$aBind [':ORDRE'] = "ZONE_TEMPLATE_ID DESC";
			}
			
			// //////////////////////////////////////////////////////////////////////
			// Récupération des infos
			// //////////////////////////////////////////////////////////////////////
			
			// Récupération des types de blocs de contenus
			$sqlTypes = "SELECT
			z.ZONE_ID ID,
			z.ZONE_LABEL LIB
			FROM " . Pelican::$config ['FW_PREFIXE_TABLE'] . "zone z
			where zone_type_id=:ZONE_TYPE_ID";
			$aBind [":ZONE_TYPE_ID"] = Pelican::$config ["ZONE_TYPE_PARAMETRABLE"];
			$aBlocTypes = $oConnection->queryTab ( $sqlTypes, $aBind );
			
			if ($_SESSION [APP] ["bloc_search"] ["rechercheBlocType"]) {
				$aBind [':ZONE_ID'] = $_SESSION [APP] ["bloc_search"] ["rechercheBlocType"];
				$conditions .= " AND z.ZONE_ID=:ZONE_ID ";
			}
			if ($_SESSION [APP] ["bloc_search"] ["recherchePage"]) {
				$aBind [':PAGE_ID'] = $_SESSION [APP] ["bloc_search"] ["recherchePage"];
				$conditions .= " AND pz.PAGE_ID=:PAGE_ID ";
			}
		
		}
		
		if (false) {  //??????
		// XXXXX else
		{
			// mode edition de bloc
			/*
			 * on vérifie que l'utilisateur a bien les droits de contribution
			 * sur cette zone
			 */
			// le user en cours a-t'il les droits de contrib sur la page?
			$this->id = $page_id;
			
			$aBind [":PAGE_ID"] = $page_id;
			$aBind [":LANGUE_ID"] = $langue_id;
			$aBind [":PAGE_VERSION"] = $page_version;
			$aBind [":ZONE_TEMPLATE_ID"] = $zone_template_id;
			
			$sSQL = "SELECT
			z.*,
			" . $oConnection->getConcatClause ( array ("'/layout'", "z.ZONE_FO_PATH" ) ) . " as ZONE_FO_PATH,
			pz.*,
			zt.*,";
			
			pelican_import ( 'User.Portal' );
			$oUser = Pelican_Factory::getInstance ( 'User.Portal' );
			
			if ($oUser->isLoggedIn ()) {
				$aBind [":PORTAL_USER_ID"] = $oConnection->strToBind ( $oUser->get ( 'id' ) );
				$sSQL .= " upz.ZONE_DATA,
				";
			}
			$sSQL .= ":PAGE_ID as PAGE_ID,
			:PAGE_VERSION as PAGE_VERSION,
			:LANGUE_ID as LANGUE_ID ";
			if ($oUser->isLoggedIn ()) {
				$sSQL .= " FROM " . Pelican::$config ['FW_PREFIXE_TABLE'] . "portal_user_zone_template zt";
			} else {
				$sSQL .= " FROM " . Pelican::$config ['FW_PREFIXE_TABLE'] . "zone_template zt";
			}
			
			$sSQL .= " INNER JOIN " . Pelican::$config ['FW_PREFIXE_TABLE'] . "zone z on (z.ZONE_ID = zt.ZONE_ID) ";
			$sSQL .= " LEFT JOIN " . Pelican::$config ['FW_PREFIXE_TABLE'] . "page_zone pz on (pz.ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID AND pz.PAGE_ID = :PAGE_ID AND pz.LANGUE_ID = :LANGUE_ID AND pz.PAGE_VERSION=:PAGE_VERSION) ";
			
			if ($oUser->isLoggedIn ()) {
				$sSQL .= "LEFT JOIN " . Pelican::$config ['FW_PREFIXE_TABLE'] . "portal_user_page_zone upz on (upz.PORTAL_USER_ID=:PORTAL_USER_ID AND upz.ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID AND upz.PAGE_ID = :PAGE_ID AND upz.LANGUE_ID = :LANGUE_ID) ";
			}
			$sSQL .= "WHERE zt.ZONE_TEMPLATE_ID =:ZONE_TEMPLATE_ID
			";
			if ($oUser->isLoggedIn ()) {
				$sSQL .= " AND zt.PORTAL_USER_ID = :PORTAL_USER_ID
				";
			}
			// AND pz.PAGE_VERSION=:PAGE_VERSION (à ajouter si on veut publier
			// en live (et supprimer la jointure sur page_draft_version), là on
			// modifie le draft)
			// and pv.PAGE_VERSION=p.PAGE_DRAFT_VERSION
			
			ob_start ();
			$oForm = Pelican_Factory::getInstance ( 'Form', true );
			$oForm->open ();
			$this->beginForm ( $oForm );
			beginFormTable ();
			$oConnection = Pelican_Factory::getInstance ( 'Db' );
			$zoneValues = $oConnection->queryRow ( $sSQL, $aBind );
			$aZoneData = array ();
			parse_str ( $zoneValues ["ZONE_DATA"], $aZoneData );
			foreach ( $aZoneData as $key => $zoneData ) {
				$zoneValues [$key] = str_replace ( array ("\\\\\"", "\\'", "\\\"", "\\\\" ), array ("\"", "'", "\"", "\\" ), stripcslashes ( $zoneData ) );
			}
			
			unset ( $zoneValues ["ZONE_DATA"] );
			$data = $zoneValues;
			
			$page ["PAGE_ID"] = $zoneValues ["PAGE_ID"];
			$page ["PAGE_VERSION"] = $zoneValues ["PAGE_VERSION"];
			$page ["PAGE_LEVEL"] = $zoneValues ["PAGE_LEVEL"];
			$page ["PAGE_DRAFT_VERSION"] = $zoneValues ["PAGE_VERSION"];
			$page ['LANGUE_ID'] = $zoneValues ['LANGUE_ID'];
			
			$oForm->createHidden ( "ZONE_TEMPLATE_ID", $zoneValues ["ZONE_TEMPLATE_ID"] );
			$oForm->createHidden ( "PAGE_ID", $zoneValues ["PAGE_ID"] );
			$oForm->createHidden ( "TEMPLATE_PAGE_ID", $zoneValues ["TEMPLATE_PAGE_ID"] );
			$oForm->createHidden ( 'LANGUE_ID', $zoneValues ['LANGUE_ID'] );
			$oForm->createHidden ( "PAGE_VERSION", $zoneValues ["PAGE_VERSION"] );
			$oForm->createHidden ( "ZONE_ID", $data ["ZONE_ID"] );
			
			$root = ($zoneValues ["PLUGIN_ID"] ? Pelican::$config ["PLUGIN_ROOT"] : Pelican::$config ["CONTROLLERS_ROOT"] . "/layout");
			$zone_template = $root . str_replace ( ".php", "", strtolower ( $zoneValues ["ZONE_BO_PATH"] ) ) . ".php";
			if (file_exists ( $zone_template )) {
				include ($zone_template);
			} else {
				echo ("<span class=\"erreur\">/layout" . $zone_template . " => A FAIRE</span>");
			}
			
			// debug(Pelican::$config["CONTROLLERS_ROOT"]."/layout".$zone_template);
			// include(Pelican::$config["CONTROLLERS_ROOT"]."/layout/common/zone_update_information.php");
			
			global $aDb;
			if ($aDB) {
				foreach ( $aDB as $db ) {
					echo ($oForm->createHidden ( "ZONE_DB[]", $db, true ));
				}
			}
			
			endFormTable ();
			
			// debug(Pelican::$config["CONTROLLERS_ROOT"]."/layout".$zone_template);
			
			$this->endForm ( $oForm, "" );
			$oForm->close ();
			$form = ob_get_contents ();
			ob_clean ();
			$this->setResponse ( $form );
		}
	}
}
