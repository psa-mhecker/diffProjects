<?php

class Backoffice_Div_Helper {

	static $skin = '';

	public static function setSkin($skin) {
		self::$skin = $skin;
	}

	public static function top($name = '') {
		/*$option [] = Pelican_Html::option ( array (value => "pelican", selected => ($_SESSION [APP] ["css"] == "pelican" ? "selected" : "") ), "Pelican" );
		$option [] = Pelican_Html::option ( array (value => "aqua", selected => ($_SESSION [APP] ["css"] == "aqua" ? "selected" : "") ), "Aqua" );
		$option [] = Pelican_Html::option ( array (value => "itunes", selected => ($_SESSION [APP] ["css"] == "itunes" ? "selected" : "") ), "iTunes" );
		$option [] = Pelican_Html::option ( array (value => "outlook", selected => ($_SESSION [APP] ["css"] == "outlook" ? "selected" : "") ), "Outlook 2003" );
		$option [] = Pelican_Html::option ( array (value => "liferay", selected => ($_SESSION [APP] ["css"] == "liferay" ? "selected" : "") ), "Liferay" );
		$option [] = Pelican_Html::option ( array (value => "calm", selected => ($_SESSION [APP] ["css"] == "calm" ? "selected" : "") ), "Calm" );
		$option [] = Pelican_Html::option ( array (value => "nmb", selected => ($_SESSION [APP] ["css"] == "nmb" ? "selected" : "") ), "NMB" );
		$css = Pelican_Html::select ( array (name => "css", onchange => "document.location.href='/?" . ($_GET ['view'] ? "view=" . $_GET ['view'] . "&amp;" : "") . "css=' + this.value;" ), implode ( "", $option ) );
		*/
		$title [] = Pelican_Html::img ( array (id => "header_logout", src => self::$skin . "/images/logout.gif", alt => "D&eacute;connexion", onclick => "document.location.href=&#39;/_/Index/login&#39;" ) );
		$title [] = Pelican_Html::u ( t ( 'UTILISATEUR' ) ) . " : " . $_SESSION [APP] ["user"] ["name"] . ' - ' . $_SESSION [APP] ["user"] ["id"];
		$title [] = " - " . Pelican_Html::u ( t ( 'SITE' ) ) . " : " . $name;
		$title [] = "";
		$title [] = $_SESSION [APP] ["htmlComboSite"];
		$title [] = "";
		if (Pelican::$config ["ENVIRONNEMENT"]) {
			$title [] = Pelican_Html::u ( t ( Pelican::$config ["ENVIRONNEMENT"] ) );
			$title [] = "";
		}
		//$title [] = $css;

		/* Ajout de l'heure GMT */
		$date = getdate();
		$site = Pelican_Cache::fetch("Frontend/Site", $_SESSION[APP]['SITE_ID']);

		$date['hours'] = date('H') + $site['SITE_FUSEAU'];
		switch($site['SITE_FUSEAU']) {
	    	case ($site['SITE_FUSEAU'] === '' ) :
	    	    $GMT = '';
	    	break;
	    	case ($site['SITE_FUSEAU'] >= 1) :
	    	    $GMT = ' +' . $site['SITE_FUSEAU'];
	    	break;
	    	case ($site['SITE_FUSEAU'] < 0) :
	    	    $GMT = ' ' . $site['SITE_FUSEAU'];
	    	break;
	    	default:
	    	    $GMT = '';
	    	break;
		}

		$title [] =  '<span id="heureOnTop" style="font-weight:700;font-size:13px"></span> (GMT' . $GMT .')';

		$header_tag [] = Pelican_Html::div ( array (id => "header_middle" ), implode ( "&nbsp;&nbsp;", $title ) );
		$header_tag [] = Pelican_Html::div ( array (id => "header_left" ), Pelican_Html::img ( array (src => self::$skin . "/images/top_left.gif", alt => "" ) ) );
		$header_tag [] = Pelican_Html::div ( array (id => "header_right" ), Pelican_Html::img ( array (src => self::$skin . "/images/top_right.gif", alt => "" ) ) );
		$return = implode ( "\n", $header_tag );

		// Sélection du site courant
		if ($_SESSION [APP] ["htmlComboSite"]) {
			$return .= Pelican_Html::script ( array (type => "text/javascript" ), "document.getElementById(\"SITE_ID\").value='" . $_SESSION [APP] ["PROFILE_ID"] . "_" . $_SESSION [APP] ['SITE_ID'] . "'" );
		}

		$heure = "

		    ejs_server_date = new Date(0,0,0," . $date['hours'] . "," . date("i,s") . ");
		    ejs_server_heu = ejs_server_date.getHours();
            ejs_server_min = ejs_server_date.getMinutes();
            ejs_server_sec = ejs_server_date.getSeconds();

		    function ejs_server_calc()
            {
                if (ejs_server_sec < 10)
                	ejs_server_sec = '0'+Math.round(ejs_server_sec);
                else if(ejs_server_sec >= 60)
                {
                	ejs_server_sec = '00';
                	ejs_server_min++;
                }
                if (ejs_server_min < 10)
                	ejs_server_min = '0'+Math.round(ejs_server_min);
                else if(ejs_server_min >= 60)
                {
                	ejs_server_min = '00';
                	ejs_server_heu++;
                }
                if (ejs_server_heu < 10)
                	ejs_server_heu = '0'+Math.round(ejs_server_heu);
                else if(ejs_server_heu >= 24)
                {
                	ejs_server_heu = '00';
                }
                ejs_server_texte = ejs_server_heu + 'h' + ejs_server_min;
                if (document.getElementById){
                	document.getElementById('heureOnTop').innerHTML=ejs_server_texte;
                }
                ejs_server_sec++;
            }
		    setInterval('ejs_server_calc()', 1000);
		";
		$return .= Pelican_Html::script ( array (type => "text/javascript" ), $heure );

		return $return;
	}

	public static function tab($aOnglet = '', $popup = false) {
		$countOnglet = 0;

		$return = '';
		/**
		 * Construction des onglets
		 */
		if (! $aOnglet) {
			// $return .= Pelican_Html::script(array(type => "text/javascript"),
			// "alert('Aucune navigation autorisée');document.location.href='" .
			// Pelican::$config["INDEX_PATH"] . "/_/Index/login';");
		} else {
			if (! $popup) {
				$return .= self::getTab ( $aOnglet );
			} else {
				/**
				 * dans le cas de la popup on simule la navigation dans l'onglet
				 * contenu
				 */
				$_GET ["view"] = "O_" . Pelican::$config ["ONGLET_CONTENT"];
			}
		}

		return $return;
	}

	public static function footer() {
		$footer [] = Pelican_Html::div ( array (id => "footer_left" ), Pelican_Html::img ( array (src => self::$skin . "/images/bottom_left.gif", alt => "" ) ) );
		$footer [] = Pelican_Html::div ( array (id => "footer_middle" ), "&nbsp;" );
		$footer [] = Pelican_Html::div ( array (id => "footer_right" ), Pelican_Html::img ( array (src => self::$skin . "/images/bottom_right.gif", alt => "" ) ) );

		return implode ( "", $footer );
	}

	public static function rightMiddle() {

		$return = Pelican_Html::iframe ( array (src => "about:blank", name => "iframeRight", id => "iframeRight", marginwidth => "0", marginheight => "0", frameborder => "0" ) );

		return $return;

	}

	public static function rightBottom($popup = false) {
		$return = Backoffice_Button_Helper::display ( "button_mutualisation", t ( 'FORM_BUTTON_COPY' ), self::$skin . "/images/button_mutualisation.gif", "clickButton('mutualisation');" );
                $return .= Backoffice_Button_Helper::display ( "button_close_schedule", t ( 'CLOSE_FORM_SCHEDULE' ), self::$skin . "/images/logout.gif", "clickButton('close_schedule');" );
                $return .= Backoffice_Button_Helper::display ( "button_schedule", t ( 'SCHEDULE' ), self::$skin . "/images/button_schedule.png", "clickButton('schedule');" );
		$return .= Backoffice_Button_Helper::display ( "button_preview", t ( 'Preview' ), self::$skin . "/images/button_preview.gif", "clickButton('preview');" );
                
		$aStates = Pelican_Cache::fetch ( "Backend/State" );
		if ($aStates) {
			foreach ( $aStates as $state ) {
				$return .= Backoffice_Button_Helper::display ( "button_state_" . $state ["id"], $state ["lib2"], self::$skin . "/images/tree_workflow_detail.gif", "clickButton('state_" . $state ["id"] . "');" );
			}
		}

	
                $return .= Backoffice_Button_Helper::display ( "button_add", t ( 'POPUP_LABEL_ADD' ), self::$skin . "/images/button_add.gif", "clickButton('add');" );
        
		$return .= Backoffice_Button_Helper::display ( "button_save", t ( 'EDITOR_SAVE' ), self::$skin . "/images/button_save.gif", "clickButton('save');" );
		$return .= Backoffice_Button_Helper::display ( "button_delete", t ( 'POPUP_LABEL_DEL' ), self::$skin . "/images/button_del.gif", "clickButton('delete');" );
		$return .= Backoffice_Button_Helper::display ( "button_back", t ( 'POPUP_BUTTON_BACK' ), self::$skin . "/images/button_back.gif", ($popup ? "goBack();" : "clickButton('back');") );

		// Médiathèque
		$return .= Backoffice_Button_Helper::display ( "buttonAddFile", t ( 'POPUP_LABEL_ADD' ), self::$skin . "/images/button_add.gif", "setAction('add','file');" );
		$return .= Backoffice_Button_Helper::display ( "buttonDelFile", t ( 'POPUP_LABEL_DEL' ), self::$skin . "/images/button_delfile.gif", "setAction('del','file');" );
		$return .= Backoffice_Button_Helper::display ( "buttonBack", t ( 'POPUP_BUTTON_BACK' ), self::$skin . "/images/button_back.gif", "top.goBack();" );

		return $return;
	}

	public static function leftBottom() {

		$return = "<div class=\"arrows\">";
		$return .= Backoffice_Button_Helper::display ( "button_up", "", self::$skin . "/images/arrow/up.gif", "orderFolderHmvc(-1);" );
		$return .= Backoffice_Button_Helper::display ( "button_down", "", self::$skin . "/images/arrow/down.gif", "orderFolderHmvc(1);" );
		$return .= "</div>";

		$return .= Backoffice_Button_Helper::display ( "buttonAddFolder", t ( 'Aj.' ), self::$skin . "/images/button_addfolder.gif", "setAction('add','folder');" );
		$return .= Backoffice_Button_Helper::display ( "buttonEditFolder", t ( 'Ed.' ), self::$skin . "/images/button_editfolder.gif", "setAction('edit','folder');" );
		$return .= Backoffice_Button_Helper::display ( "buttonDelFolder", t ( 'Sup.' ), self::$skin . "/images/button_delfolder.gif", "setAction('del','folder');" );
		//if(strtoupper ( $_SESSION[APP]["PROFIL_LABEL"]) != Pelican:: $config['PROFILS']['CONTRIBUTEUR']){
			$return .= Backoffice_Button_Helper::display ( "button_addpage", t ( 'Aj.' ), self::$skin . "/images/button_addfolder.gif", "menu(lastMenu['tid'], lastMenu['tc'], -2, lastMenu['id']);" );
		//}
		$return .= Backoffice_Button_Helper::display ( "button_deletepage", t ( 'Sup.' ), self::$skin . "/images/button_delfolder.gif", "menu(lastMenu['tid'], lastMenu['tc'], lastMenu['id'], lastMenu['id'], 'true');" );
		return $return;

	}

	public static function leftMiddle($aOnglet, $selected, $zone = '') {
		$aView = $aOnglet [$selected];
		if ($aView) {
			/**
			 * Sélection de la navigation de gauche
			 */
			$aTemplate = Pelican_Cache::fetch ( "Template", $aView ["TEMPLATE_ID"] );
			if (! $aTemplate) { // $return = Pelican_Html::script(array(type =>
				                    // "text/javascript"), "alert('Aucun template
				                    // identifié');document.location.href='" .
				                    // Pelican::$config["INDEX_PATH"] .
			                    // "/_/Index/login';");
			} else {
				/**
				 * inclusion du template de navigation de gauche
				 */
				$controller = $aTemplate [0] ['TEMPLATE_PATH'];
				$params ['aOnglet'] = $aOnglet;
				if (! empty ( $zone )) {
					$params ['zone'] = $zone;
				}
				$return = Pelican_Request::call ( $controller, $params );
			}
		} else {
			// pb d'authentification ou de droit
			$return = '';
		}
		return $return;
	}

	public static function popupBottom() {
		$return = Backoffice_Button_Helper::display ( "button_mutualisation", t ( 'EDITOR_COPY' ), self::$skin . "/images/button_mutualisation.gif", "clickButton('mutualisation');" );
		$return .= Backoffice_Button_Helper::display ( "button_preview", t ( 'Preview' ), self::$skin . "/images/button_preview.gif", "clickButton('preview');" );
		$aStates = Pelican_Cache::fetch ( "Backend/State" );
		if ($aStates) {
			foreach ( $aStates as $state ) {
				$return .= Backoffice_Button_Helper::display ( "button_state_" . $state ["id"], $state ["lib"], self::$skin . "/images/tree_workflow_detail.gif", "clickButton('state_" . $state ["id"] . "');" );
			}
		}
		$return .= Backoffice_Button_Helper::display ( "button_addpage", t ( 'Aj.' ), "", "" );
		$return .= Backoffice_Button_Helper::display ( "button_deletepage", t ( 'Sup.' ), "", "" );
		$return .= Backoffice_Button_Helper::display ( "button_add", t ( 'POPUP_LABEL_ADD' ), "", "" );
		$return .= Backoffice_Button_Helper::display ( "button_save", t ( 'EDITOR_SAVE' ), "", "" );
		$return .= Backoffice_Button_Helper::display ( "button_delete", t ( 'POPUP_LABEL_DEL' ), "", "" );
		$return .= Backoffice_Button_Helper::display ( "button_back", t ( 'POPUP_BUTTON_BACK' ), "", "goBack();" );
		return $return;
	}

	public static function getTab($aValues) {
		global $titleLeft;

		$oTab = Pelican_Factory::getInstance('Form.Tab', "tab", self::$skin );

		foreach ( $aValues as $onglet ) {
			if ($onglet ["id"]) {
				if (! $_GET ["view"]) {
					/**
					 * si aucun onglet n'est sélectionné : on active le premier
					 */
					$_GET ["view"] = "O_" . $onglet ["id"];
				}
				$labelTrad	=	strtr(strtoupper (dropaccent($onglet ["lib"])), " ", "_");
				$oTab->addTab ( t($labelTrad), "onglet_top_" . $onglet ["id"], ($_GET ["view"] == "O_" . $onglet ["id"]), Pelican::$config ["PAGE_INDEX_PATH"] . "?view=O_" . $onglet ["id"], "", $onglet ["volet_gauche"] );
			}
		}
		$return = $oTab->getTabs ();
		return $return;
	}
}