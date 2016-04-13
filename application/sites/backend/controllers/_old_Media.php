<?php
include_once ('config.php');

require_once (Pelican::$config ['LIB_ROOT'] . Pelican::$config ['LIB_FILE'] . Pelican::$config ['CLASS_FILE']);
pelican_import ( 'Cache.Media' );
pelican_import ( 'Controller.Back' );

class Media_Controller extends Pelican_Controller_Back {
	
	protected $form_name = 'media';
	
	protected $field_id = "MEDIA_ID";
	
	protected $defaultOrder = "MEDIA_TITLE";
	
	protected $_updateList;
	
	protected $_counter;
	
	protected $currentDir;
	
	protected $aAllowedExtensions;
	
	protected $_folder;
	
	protected $_jsForm;
	
	protected $_folder_properties;
	
	public function init() {
		
		if (! valueExists ( $_REQUEST, "media" )) {
			$_REQUEST ["media"] = "";
		}
		if (! valueExists ( $_REQUEST, "zone" )) {
			$_REQUEST ["zone"] = "";
		}
		/*        if ($_REQUEST["media"] || $_REQUEST["zone"] == 'media') {
            define('JSTARGET', 'top');
        } else {
            define('JSTARGET', 'parent');
        }*/
		if ($_REQUEST ["zone"] == 'popup') {
			define ( 'JSTARGET', 'parent' );
		} else {
			define ( 'JSTARGET', 'top' );
		}
		/** Mutualisation */
		if (! valueExists ( $_SESSION [APP], "SITE_MEDIA" )) {
			if (valueExists ( $_SESSION [APP], 'SITE_ID' )) {
				$_SESSION [APP] ["SITE_MEDIA"] = $_SESSION [APP] ['SITE_ID'];
				$HTTP_SESSION_VARS [APP] ["SITE_MEDIA"] = $_SESSION [APP] ['SITE_ID'];
			}
		}
		if (valueExists ( $_GET, "SITE_MEDIA" )) {
			$_SESSION [APP] ["SITE_MEDIA"] = $_GET ["SITE_MEDIA"];
			$HTTP_SESSION_VARS [APP] ["SITE_MEDIA"] = $_GET ["SITE_MEDIA"];
		}
		
		if (! Pelican::$config ["MEDIA_ROOT"])
			Pelican::$config ["MEDIA_ROOT"] = Pelican::$config ['DOCUMENT_ROOT'];
		
		/** Définition du répertoire courant : Id pour le mode BDD, chemin pour le mode physique */
		if (Pelican::$config ["FW_MEDIA_TABLE_NAME"]) {
			if (valueExists ( $_REQUEST, "root" )) {
				$this->currentDir = $_REQUEST ["root"];
			}
		} else {
			$this->currentDir = Pelican::$config ["MEDIA_ROOT"] . $_REQUEST ["root"] . "/";
			$this->currentDir = str_replace ( "//", "/", $this->currentDir );
		}
		
		/*******************************************/
		/* Suppression de format et rechargement  */
		/*******************************************/
		if (valueExists ( $_GET, "delForcage" )) {
			$aBind [':FW_MEDIA_FIELD_ID'] = $_GET ["id"];
			$aBind [':FORCAGE'] = $_GET ["delForcage"];
			$strSQL1 = "
			DELETE FROM #pref#_media_format_intercept
			WHERE " . Pelican::$config ["FW_MEDIA_FIELD_ID"] . " = :FW_MEDIA_FIELD_ID
			AND MEDIA_FORMAT_ID = :FORCAGE";
			$oConnection = Pelican_Db::getInstance ();
			$oConnection->query ( $strSQL1, $aBind );
			//debug(getUploadRoot(Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($_GET["preview"]), $_GET["delForcage"])));
			$file = getUploadRoot ( Pelican_Media::getFileNameMediaFormat ( Pelican_Media::getMediaPath ( $_GET ["preview"] ), $_GET ["delForcage"] ) );
			if (file_exists ( $file )) {
				unlink ( $file );
			}
			header ( "location :" . str_replace ( "&delForcage=" . $_GET ["delForcage"], "", $_SERVER ["REQUEST_URI"] ) );
		}
		
		$this->aAllowedExtensions = getAllowedExtensions ();
	}
	
	public function listAction() {
		$this->sAddUrl = false;
		
		parent::listAction ();
		$preview = $this->_makeList ( str_replace ( Pelican::$config ["MEDIA_ROOT"], "", $_GET ["root"] ) );
		$this->assign ( 'list', $preview, false );
		$this->assign ( 'zone', $_REQUEST ["zone"] );
		
		$this->_getFilter ();
		$this->fetch ();
	}
	
	public function editAction() {
		pelican_import ( 'Index' );
		$head = $this->getView ()->getHead ();
		$head->setScript ( "var libDir='" . Pelican::$config ["LIB_PATH"] . "';" );
		$head->setScript ( "var mediaDir='.';" );
		$head->setScript ( "var httpMediaDir='" . Pelican::$config ["MEDIA_HTTP"] . "';" );
		$head->setScript ( "var fixedCalendarPos = true;" );
		$this->_setSkin ();
		
		if ($_REQUEST ["action"] == "del") {
			/** Génération du formulaire */
			$form = $this->_forward ( 'save' );
		}
		if ($_REQUEST ["action"] == "add" || $_REQUEST ["action"] == "edit" || $_REQUEST ["action"] == "replace") {
			/** Génération du formulaire */
			$form = $this->_makeForm ();
		}
		$this->assign ( 'doctype', $head->getDocType () );
		$this->assign ( 'header', $head->getHeader ( false ), false );
		$this->assign ( 'body', $form ["html"], false );
		$this->assign ( 'footer', $head->getFooter (), false );
		$this->fetch ();
	}
	
	public function saveAction() {
		if ($_REQUEST ["type"] == "folder") {
			/** Dossiers */
			$this->_saveFolder ();
		} else {
			/** Fichiers */
			$this->_saveFile ();
		}
		
		// Les actions liées aux fichiers ou aux dossier nécessitent l'exécution de javascript initialisé dans $js
		if ($this->_jsForm) {
			$js = "<script type=\"text/javascript\">";
			$js .= $this->_jsForm;
			$js .= "</script>";
		}
		$this->assign ( 'js', $js, false );
		$this->fetch ();
	}
	
	public function popupAction() {
		$tiny = ($this->getParam ( 'tiny' ) ? true : false);
		
		$head = $this->getView ()->getHead ();
		
		$head->setTitle ( t ( 'Médiathèque' ) );
		$this->_setSkin ();
		//$head->setCss($head->skinPath . "/css/popup.css.php?media=1");
		$head->setJs ( Pelican::$config ["MEDIA_LIB_PATH"] . "/js/media_translate.js.php" );
		$head->setJs ( Pelican::$config ["MEDIA_LIB_PATH"] . "/js/media_hmvc.js" );
		$head->setScript ( "var vIndexIframePath = '" . Pelican::$config ["PAGE_INDEX_IFRAME_PATH"] . "';" );
		$head->setScript ( "var vIndexPath = '" . Pelican::$config ["PAGE_INDEX_PATH"] . "';" );
		$head->setScript ( "var vTransactionPath = '" . Pelican::$config ["DB_PATH"] . "';" );
		$head->setScript ( "var vView = '" . (isset ( $_GET ["view"] ) ? $_GET ["view"] : "") . "';" );
		$head->setScript ( "var vOnline = '" . (isset ( Pelican::$config ["ACTION_ONLINE"] ) ? Pelican::$config ["ACTION_ONLINE"] : "") . "';" );
		$head->setScript ( "var libDir='" . Pelican::$config ["LIB_PATH"] . "';" );
		$head->setScript ( "var mediaDir='" . Pelican::$config ["MEDIA_LIB_PATH"] . "';" );
		$head->setScript ( "var httpMediaDir='" . Pelican::$config ["MEDIA_HTTP"] . "';" );
		$head->setScript ( "function closePopup() {
window.close();
}" );
		if ($tiny) {
			$head->endJs ( Pelican::$config ["LIB_PATH"] . Pelican::$config ['LIB_OTHER'] . "/tiny_mce/tiny_mce_popup.js" );
			$head->endJs ( Pelican::$config ["LIB_PATH"] . Pelican::$config ['LIB_OTHER'] . "/tiny_mce/plugins/betd_media/js/betd_media.js" );
		}
		$this->getHierarchieMenu ();
		
		/** body */
		$this->assign ( 'title', isset ( $head->titleLeft ) ? $head->titleLeft : t ( 'RUBRIQUES' ) );
		//var_dump($this->aHierarchie["onglet"]);
		/** body */
		Backoffice_Div_Helper::setSkin ( $head->skinPath );
		$_GET ['view'] = 'O_28';
		$this->assign ( 'left_middle', Backoffice_Div_Helper::leftMiddle ( $this->aHierarchie ["onglet"], $_GET ['view'], 'popup' ), false );
		$this->assign ( 'left_bottom', Backoffice_Div_Helper::leftBottom (), false );
		
		$this->assign ( 'right_middle', Backoffice_Div_Helper::rightMiddle (), false );
		$this->assign ( 'right_bottom', Backoffice_Div_Helper::rightBottom ( true ), false );
		
		/** action javascript par defaut */
		if (isset ( $this->getView ()->default )) {
			$default = $this->getView ()->default;
		}
		
		$this->assign ( 'mediaType', $_GET ["mediaType"] );
		$this->assign ( 'default', $default, false );
		$this->assign ( 'skin', $head->skinPath );
		$this->assign ( 'doctype', $head->getDocType () );
		$this->assign ( 'header', $head->getHeader ( false ), false );
		$this->assign ( 'footer', $head->getFooter (), false );
		
		$this->fetch ();
	}
	
	/**
	 *
	 * Onglets de choix du type de médias
	 *
	 */
	protected function _getFilter() {
		
		$oTab = Pelican_Factory::getInstance ( 'Form.Tab', "tabFilter" );
		foreach ( $this->aAllowedExtensions as $key => $value ) {
			$oTab->addTab ( $value ["libelle"], "onglet_filter_" . $key, ($_GET ["type"] == $key), "", "top.setFilter('" . $key . "')", "", "petit" );
		}
		
		$this->assign ( 'tabs', Pelican_Html::div ( array ("class" => "petit_onglet_bas", width => "100%" ), $oTab->getTabs () ), false );
	}
	
	/**
	 * Traitements d'un dossier
	 *
	 * @return void
	 */
	protected function _saveFolder() {
		
		$oConnection = Pelican_Db::getInstance ();
		
		switch ($_REQUEST ["action"]) {
			case "save" :
				{
					if (Pelican::$config ["FW_MEDIA_TABLE_NAME"]) {
						$this->_folder = $oConnection->queryItem ( "select " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . " FROM " . Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"] . " WHERE " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . "=" . $_REQUEST ["root"] );
						$path = implode ( " > ", array ($this->_folder, str_replace ( "\\", "", str_replace ( "'", "", $_REQUEST ["folder_name"] ) ) ) );
						/** contrôle d'unicité */
						$aBind = array ();
						$aBind [":PATH"] = $oConnection->strToBind ( $path );
						$aBind [":SITE_ID"] = $_SESSION [APP] ['SITE_ID'];
						$nb = $oConnection->queryItem ( "select count(*)-1 from " . Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"] . " T1 where T1." . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . " = :PATH AND T1.SITE_ID = :SITE_ID", $aBind );
						if ((( int ) $nb == - 1)) {
							/** MODE BDD : gestion dans la table des dossiers (ou catégories) */
							Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"]] = Pelican::$config ["DATABASE_INSERT_ID"];
							Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"]] = $path;
							Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PARENT"]] = $_REQUEST ["root"];
							Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD_FOLDER_NAME"]] = $_REQUEST ["folder_name"];
							Pelican_Db::$values ['SITE_ID'] = $_SESSION [APP] ['SITE_ID'];
							$oConnection->insertQuery ( Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"], $aBind );
						} else {
							$this->_jsForm = "alert('La catégorie existe déjà');\r\n";
						}
					} else {
						if ($_REQUEST ["root"] && $_REQUEST ["folder_name"]) {
							$oldumask = umask ( 0 );
							@mkdir ( Pelican_Media::cleanDirectory ( $_REQUEST ["root"] . $_REQUEST ["folder_name"] ), 0755 );
							@umask ( $oldumask );
						}
					}
					if (! $this->_jsForm) {
						$this->_jsForm .= "top.reload();\r\n";
					} else {
						$this->_jsForm .= "document.location.href='" . $_SERVER ["HTTP_REFERER"] . "';\r\n";
					}
					break;
				}
			case "update" :
				{
					/** MODE BDD : gestion dans la table des dossiers (ou catégories) */
					Pelican_Db::$values = $_POST;
					Pelican_Db::$values ["form_action"] = Pelican::$config ["DATABASE_INSERT"];
					/** on récipère le nouveau nom pour la mise à jour */
					Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD_FOLDER_NAME"]] = Pelican_Db::$values ["folder_name"];
					$oConnection->updateQuery ( Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"] );
					/** Mise à jour des chemins */
					$old = Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"]];
					$new0 = str_replace ( "\\", "", str_replace ( "'", "", $_REQUEST ["folder_name"] ) );
					$path0 = explode ( " > ", $old );
					$tmp = array_pop ( $path0 );
					array_push ( $path0, $new0 );
					$new = implode ( " > ", $path0 );
					$str = "update " . Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"] . " set " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . "=REPLACE(" . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . ",'" . $old . "','" . $new . "') where (" . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . " like '" . $old . "%' OR " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . "='" . $old . "') AND SITE_ID = " . $_SESSION [APP] ['SITE_ID'];
					$oConnection->query ( $str );
					/** Mise à jour en cascade */
					$aBind = array ();
					$indexBind = 0;
					if (Pelican_Db::$values ["MEDIA_DEBUT_DATE"]) {
						$bind = $oConnection->setBindValue ( $aBind, $indexBind ++, Pelican_Db::$values ["MEDIA_DEBUT_DATE"], "date", false );
						$fieldUpdate [] = "MEDIA_DEBUT_DATE=" . $oConnection->getNVLClause ( "MEDIA_DEBUT_DATE", $bind );
					}
					if (Pelican_Db::$values ["MEDIA_FIN_DATE"]) {
						$bind = $oConnection->setBindValue ( $aBind, $indexBind ++, Pelican_Db::$values ["MEDIA_FIN_DATE"], "date", false );
						$fieldUpdate [] = "MEDIA_FIN_DATE=" . $oConnection->getNVLClause ( "MEDIA_FIN_DATE", $bind );
					}
					if (Pelican_Db::$values ["MEDIA_CREDIT"]) {
						$bind = $oConnection->setBindValue ( $aBind, $indexBind ++, Pelican_Db::$values ["MEDIA_CREDIT"], "string" );
						$fieldUpdate [] = "MEDIA_CREDIT=" . $oConnection->getNVLClause ( "MEDIA_CREDIT", $bind );
					}
					if ($fieldUpdate) {
						$bind = $oConnection->setBindValue ( $aBind, $indexBind ++, Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"]], "string" );
						$sql = "update #pref#_media set " . implode ( ",", $fieldUpdate ) . " where " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . " in (SELECT " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . " from " . Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"] . " where (" . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . " like " . $oConnection->getConcatClause ( array ($bind, "'%'" ) ) . " OR " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . "=" . $bind . ") AND SITE_ID = " . $_SESSION [APP] ['SITE_ID'] . ")";
						$oConnection->query ( $sql, $aBind );
					}
					$this->_jsForm = "" . JSTARGET . ".reload();\r\n";
					break;
				}
			case "del" :
				{
					If ($_REQUEST ["root"]) {
						// On vérifie qu'il n'y a plus rien de référencé en base pour ce répertoire
						$countDir = 0;
						if (Pelican::$config ["FW_MEDIA_TABLE_NAME"]) {
							/** on recherche les enfants */
							$sqlPath = "SELECT " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . " FROM " . Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"] . " where " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . " = " . $_REQUEST ["root"];
							$dirPath = $oConnection->queryItem ( $sqlPath );
							$sql = "select count(*)-1 from " . Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"] . " T1, " . Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"] . " T2 where  T1.SITE_ID = " . $_SESSION [APP] ['SITE_ID'] . " AND T1.SITE_ID=T2.SITE_ID AND T1." . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . " = " . $_REQUEST ["root"] . " and T1." . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . " != T2." . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . "  and T2." . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . " LIKE '" . $dirPath . " %'";
							$countFolder = $oConnection->queryItem ( $sql );
							if ((( int ) $countFolder >= 0)) {
								$this->_jsForm .= "alert('" . str_replace ( "'", "''", t ( 'POPUP_MEDIA_MSG_EMPTY_FOLDER' ) ) . "');";
								$this->_jsForm .= "" . JSTARGET . ".goBack();\r\n";
							} else {
								/** on recherche les Pelican_Media associés */
								$sql = "select count(*) from " . Pelican::$config ["FW_MEDIA_TABLE_NAME"] . " where " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . "=" . $_REQUEST ["root"];
								$countDir = $oConnection->queryItem ( $sql );
								if (! $countDir) {
									$sql = "delete from " . Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"] . " WHERE " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . " = " . $_REQUEST ["root"];
									$oConnection->query ( $sql );
								} else {
									$this->_jsForm .= "alert('" . str_replace ( "'", "''", t ( 'POPUP_MEDIA_MSG_EMPTY_FOLDER' ) ) . "');";
								}
							}
						} else {
							if (! @rmdir ( $_REQUEST ["root"] )) {
								$this->_jsForm .= "alert('" . str_replace ( "'", "''", t ( 'POPUP_MEDIA_MSG_EMPTY_FOLDER' ) ) . "');";
								$this->_jsForm .= "" . JSTARGET . ".goBack();\r\n";
							}
						}
						if (! $this->_jsForm) {
							$this->_jsForm = "" . JSTARGET . ".reload();\r\n";
						}
					}
					break;
				}
			case "move" :
				{
					$oConnection->query ( "update " . Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"] . " set " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PARENT"] . "=" . $_REQUEST ["to"] . " where " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . "=" . $_REQUEST ["from"] );
					/** Mise à jour des chemins */
					$old = $oConnection->queryItem ( "select " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . " from " . Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"] . " where " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . "=" . $_REQUEST ["from"] );
					$new0 = $oConnection->queryItem ( "select " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . " from " . Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"] . " where " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . "=" . $_REQUEST ["to"] );
					$path0 = explode ( " > ", $old );
					$tmp = array_pop ( $path0 );
					$new = $new0 . " > " . $tmp;
					$str = "update " . Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"] . " set " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . "=REPLACE(" . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . ",'" . $old . "','" . $new . "') where (" . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . " like '" . $old . "%' OR " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . "='" . $old . "')  AND SITE_ID = " . $_SESSION [APP] ['SITE_ID'];
					$oConnection->query ( $str );
					$this->_jsForm = "" . JSTARGET . ".reload();\r\n";
					break;
				}
		}
		Pelican_Cache::clean ( "Backend/MediaTree", $_SESSION [APP] ["SITE_MEDIA"] );
	}
	
	/**
	 * Traitements d'un fichier
	 *
	 * @return void
	 */
	protected function _saveFile() {
		
		$oConnection = Pelican_Db::getInstance ();
		
		/** s'il y a upload, on passe en action "save" */
		$action = $_REQUEST ["action"];
		$_REQUEST [Pelican::$config ["FW_MEDIA_FIELD_TYPE"]] = $_REQUEST ["view"];
		if ($_FILES ["file_name"] ["name"]) {
			$action = "save";
			$ID = $_REQUEST [Pelican::$config ["FW_MEDIA_FIELD_ID"]];
			/** on est en update si le chemin existe déjà */
			if ($_REQUEST ["action"] == "update") {
				$_REQUEST ["form_action"] = Pelican::$config ["DATABASE_UPDATE"];
			} elseif ($_REQUEST ["action"] == "save") {
				$_REQUEST ["form_action"] = Pelican::$config ["DATABASE_INSERT"];
				$_REQUEST [Pelican::$config ["FW_MEDIA_FIELD_CREATION_DATE"]] = ":DATE_COURANTE";
			}
		}
		
		switch ($action) {
			case "save" :
				{
					/** debut upload */
					if ($_FILES ["file_name"] ["name"]) {
						$file_name = $_FILES ["file_name"];
						// Si un fichier uploadé existe
						if ($file_name ["tmp_name"] == "none") {
							// le fichier n'existe pas
							$this->_jsForm = "alert('" . t ( 'POPUP_UPLOAD_NO_FILE' ) . "');\r\n";
						} else {
							// le fichier existe : initialisation des variables
							$_REQUEST ["file_name"] = $file_name ["name"];
							$_REQUEST ["file_name"] = strtr ( $_REQUEST ["file_name"], '����������������������������������������������������', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy' );
							$_REQUEST ["file_name"] = preg_replace ( '/([^.a-z0-9]+)/i', '-', $_REQUEST ["file_name"] );
							$_REQUEST ["tmp_name"] = $file_name ["tmp_name"];
							$_REQUEST ["size"] = $file_name ["size"];
						}
					}
					$upfile = getUploadRoot ( Pelican_Media::fileName ( $_REQUEST ["file_name"], $ID ) );
					$upfile = str_replace ( " ", "_", $upfile );
					$tmp_name = $_REQUEST ["tmp_name"];
					$size = $_REQUEST ["size"];
					
					// Informations liées au fichier
					$pathinfo = pathinfo ( $upfile );
					$file_md5 = md5_file ( $tmp_name );
					$imageinfo = @getimagesize ( $tmp_name );
					
					// vérification d'existence
					$database_exists = false; //Pelican_Media::fileDbExists($file_md5, $size);
					

					// Vérification de l'extension
					if (! $this->aAllowedExtensions [$_REQUEST ["view"]] [strtolower ( $pathinfo ["extension"] )] && $_REQUEST ["view"] != "all") {
						// L'extension n'est pas autorisée
						$this->_jsForm = "alert('" . t ( 'POPUP_UPLOAD_WRONG_TYPE' ) . "');\r\n";
						$this->_jsForm .= "document.location.href='" . $_SERVER ["HTTP_REFERER"] . "';\r\n";
					} elseif ($this->_jsForm) {
						$this->_jsForm .= "document.location.href='" . $_SERVER ["HTTP_REFERER"] . "';\r\n";
					} elseif ($database_exists) {
						$this->_jsForm = "alert('" . t ( 'POPUP_UPLOAD_ALREADY_EXISTS' ) . " " . str_replace ( "'", "\\'", $database_exists ["path"] ) . "');\r\n";
						$this->_jsForm .= "" . JSTARGET . ".previewMedia('" . rawurlencode ( $database_exists ["file"] ) . "', " . $database_exists ["id"] . ");\r\n";
					} else {
						// Tout est OK, on fait un annulé remplace (on supprime aussi sa vignette si elle existe)
						// suppression du fichier
						$file = $upfile;
						$dirFile = dirname ( $file ) . "/" . $ID . ".";
						
						Pelican_Media::deleteFullFile ( $file );
						//$cmd = "rm -rf ".$dirFile."*";
						//@passthru($cmd);
						

						/*$dirThumbnail = dirname(Pelican_Media::getThumbnailPath($file)) . "/" . $ID . "_";
                        $cmd = "rm -f " . $dirThumbnail . "*";
                        @passthru($cmd);*/
						
						/** dans le cas d'un remplacement */
						if ($_REQUEST [Pelican::$config ["FW_MEDIA_FIELD_PATH"]]) {
							@unlink ( Pelican_Media::cleanDirectory ( Pelican::$config ["MEDIA_ROOT"] . $_REQUEST [Pelican::$config ["FW_MEDIA_FIELD_PATH"]] ) );
						}
						
						if (! copy ( $tmp_name, $upfile )) {
							// Si on a un problème lors de la copie du fichier à l'emplacement de destination, on retourne en arrière
							switch ($file_name ['error']) {
								case 1 :
									{
										$message = t ( 'POPUP_UPLOAD_TOO_BIG_PHP' );
										break;
									}
								case 2 :
									{
										$message = t ( 'POPUP_UPLOAD_TOO_BIG' ) . " (" . fileSize ( ini_get ( 'upload_max_filesize' ) ) . "  max.)";
										break;
									}
								case 3 :
									{
										$message = t ( 'POPUP_UPLOAD_PART_ONLY' );
										break;
									}
								case 4 :
									{
										$message = t ( 'POPUP_UPLOAD_NO_FILE' );
										break;
									}
							}
							$this->_jsForm = "alert('" . $message . "');\r\n";
							$this->_jsForm .= "history.go(-1);\r\n";
						} else {
							// cas de la vidéo et d'un zip
							if ($_REQUEST [Pelican::$config ["FW_MEDIA_FIELD_TYPE"]] == "video") {
								$info = pathinfo ( $file );
								if ($info ["extension"] == "zip") {
									$unzip = str_replace ( "." . $info ["extension"], "", $file );
									$cmd = "unzip -j " . $file . " -d " . $unzip;
									@passthru ( $cmd );
								}
							}
							
							// insertion dans la table
							$info = Pelican_Media::cleanDirectory ( "/" . str_replace ( Pelican::$config ["MEDIA_ROOT"], "", $file ) );
							if (Pelican::$config ["FW_MEDIA_TABLE_NAME"]) {
								Pelican_Db::$values = $_REQUEST;
								Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD_ID"]] = $ID;
								Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD_WIDTH"]] = $imageinfo [0];
								Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD_HEIGHT"]] = $imageinfo [1];
								Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD_WEIGHT"]] = $size;
								Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD_MD5"]] = $file_md5;
								if (! Pelican_Db::$values ['SITE_ID']) {
									Pelican_Db::$values ['SITE_ID'] = $_SESSION [APP] ['SITE_ID'];
								}
								if (Pelican_Db::$values ['MEDIA_ALT'] == '') {
									Pelican_Db::$values ['MEDIA_ALT'] = Pelican::$config ['ALT_EMPTY'];
								}
								Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"]] = $_REQUEST ["root"];
								Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD_PATH"]] = $info;
								if (! Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD_FILE"]])
									Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD_FILE"]] = $file_name ["name"];
								if (Pelican::$config ["FW_MEDIA_FIELD"] ["TITLE"] && ! Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD"] ["TITLE"]]) {
									Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD"] ["TITLE"]] = str_replace ( $pathinfo ["extension"], "", basename ( $info ) );
								}
								$oConnection->updateTable ( Pelican_Db::$values ["form_action"], Pelican::$config ["FW_MEDIA_TABLE_NAME"] );
							}
							if ($_REQUEST ["zone"] == "upload") {
								// Cas de l'upload, il faut générer les éléments du fichier immédiatement
								if (Pelican::$config ["FW_MEDIA_TABLE_NAME"]) {
									$id = Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD_ID"]];
								} else {
									$id = rawurlencode ( $info );
								}
								$fileTag = basename ( $info );
								if ($this->aAllowedExtensions ["image"] [strtolower ( $pathinfo ["extension"] )]) {
									// si c'est une image, on fait le tag de preview
									Pelican_Media::reduceSize ( $imageinfo, (Pelican::$config ["IMG_WIDTH_THUMBNAIL"] ? Pelican::$config ["IMG_WIDTH_THUMBNAIL"] : "50") );
									$thumbnailTag = "<img src=\"" . $info . "\" alt=\"" . basename ( $info ) . "\" " . $imageinfo [3] . " border=\"0\">";
								} else {
									$thumbnailTag = basename ( $info );
								}
								$this->_jsForm = "" . JSTARGET . ".setMedia('" . rawurlencode ( $info ) . "','" . rawurlencode ( $fileTag ) . "','" . rawurlencode ( $thumbnailTag ) . "','" . $id . "');\r\n";
							} else {
								// sinon on rafraichi la fenêtre
								$this->_jsForm = "" . JSTARGET . ".resetMedia();\r\n";
							}
							$this->_jsForm .= "" . JSTARGET . ".goBack();\r\n";
						}
						// CACHE
						$pathCache = Pelican_Media::cleanDirectory ( "/" . str_replace ( Pelican::$config ["MEDIA_ROOT"], "", $file ) );
					}
					/** @todo -oRaphael Limiter le contrôle au site */
					$trigger = Pelican::$config ["DATABASE_INSERT"];
					break;
				}
			case "update" :
				{
					// N'est possible que si on a une table
					if (Pelican::$config ["FW_MEDIA_TABLE_NAME"]) {
						Pelican_Db::$values = $_REQUEST;
						if (! Pelican_Db::$values ['SITE_ID']) {
							Pelican_Db::$values ['SITE_ID'] = $_SESSION [APP] ['SITE_ID'];
						}
						if (Pelican_Db::$values ['MEDIA_ALT'] == '') {
							Pelican_Db::$values ['MEDIA_ALT'] = Pelican::$config ['ALT_EMPTY'];
						}
						$oConnection->updateQuery ( Pelican::$config ["FW_MEDIA_TABLE_NAME"] );
					}
					$this->_jsForm = "" . JSTARGET . ".goBack();";
					$trigger = Pelican::$config ["DATABASE_UPDATE"];
					break;
				}
			case "del" :
				{
					/** Si le Pelican_Media n'est pas utilisé */
					
					$file = Pelican_Media::getMediaPath ();
					$pathCache = Pelican_Media::cleanDirectory ( "/" . str_replace ( Pelican::$config ["MEDIA_ROOT"], "", $file ) );
					$usage = Pelican_Media::checkMediaUsage ( $_REQUEST ["id"] );
					if ($_REQUEST ["id"] && ! $usage) {
						// suppression du fichier
						

						$realFile = Pelican_Media::cleanDirectory ( getUploadRoot ( $file ) );
						Pelican_Media::deleteFullFile ( $realFile );
						
						// suppression dans la table
						if (Pelican::$config ["FW_MEDIA_TABLE_NAME"]) {
							Pelican_Db::$values = $_REQUEST;
							Pelican_Db::$values [Pelican::$config ["FW_MEDIA_FIELD_ID"]] = $_REQUEST ["id"];
							Pelican_Db::$values ["form_action"] = Pelican::$config ["DATABASE_DELETE"];
							$oConnection->updateTable ( Pelican::$config ["DATABASE_DELETE"], "#pref#_media_format_intercept", "MEDIA_FORMAT_ID" );
							$oConnection->deleteQuery ( Pelican::$config ["FW_MEDIA_TABLE_NAME"] );
						}
						/** nettoyage de la recherche */
						$this->_jsForm = "" . JSTARGET . ".resetMedia();\r\n" . JSTARGET . ".goBack();\r\n";
					} else {
						/** On va sur la page qui liste les utilisations du Pelican_Media */
						$this->_jsForm = "alert('Elément utilisé dans un contenu, suppression impossible');\r\n";
					}
					$trigger = Pelican::$config ["DATABASE_DELETE"];
					break;
				}
			case "move" :
				{
					$oConnection->query ( "update " . Pelican::$config ["FW_MEDIA_TABLE_NAME"] . " set " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . "=" . $_REQUEST ["to"] . " where " . Pelican::$config ["FW_MEDIA_FIELD_ID"] . "=" . $_REQUEST ["from"] );
					$this->_jsForm = "" . JSTARGET . ".goBack();\r\n";
					break;
				}
		}
		Pelican_Cache::clean ( "Media/Detail", $_REQUEST ["id"] );
		if ($pathCache) {
			Pelican_Cache::clean ( "MediaBuild", $pathCache );
		}
		if ($trigger) {
			$this->_trigger ( $trigger, Pelican_Db::$values );
		}
	}
		
	/**
	 * Création d'un liste déroulante avec les formats disponibles
	 * et de la fonction javascript associée pour changer le source de l'image
	 *
	 * @return string le code Pelican_Html et javascript générés
	 * @param string $file Chemin absolu de l'image de départ
	 * @param string $title Titre de l'image
	 * @param mixed $previewSize Taille de prévisualisation
	 */
	protected function _makeMediaFormat($file = "", $title = "", $previewSize = array()) {
		
		$return = Pelican_Cache::fetch ( "Frontend/MediaFormat_Combo" );
		$pathinfo = pathinfo ( $file );
		
		$return .= "
			<script type=\"text/javascript\" src=\"" . Pelican::$config ["MEDIA_LIB_PATH"] . "/js/media_format.js\"></script>
			<script type=\"text/javascript\">
			" . JSTARGET . ".current.img = document.getElementById(\"imgMediaFormat\");
			imgMediaFormat = " . JSTARGET . ".current.img;
			srcOriginal = '" . rawurlencode ( $file ) . "';
			extOriginal = '." . $pathinfo ["extension"] . "';
			oldWidth = '" . ($previewSize [0] ? $previewSize [0] : "") . "';
			oldHeight = '" . ($previewSize [1] ? $previewSize [1] : "") . "';
			httpPath = '" . Pelican::$config ["MEDIA_HTTP"] . "';
			absPath = '" . Pelican::$config ["MEDIA_LIB_PATH"] . "';
			 
			" . JSTARGET . ".current.mediaPath = srcOriginal;
			" . JSTARGET . ".current.mediaTitle = '" . rawurlencode ( $title ) . "';
			 
			if (" . JSTARGET . ".current.format) {
			document.getElementById(\"cboMediaFormat\").value = " . JSTARGET . ".current.format;
			changeMediaFormat();
			}
			showPicto();
			</script>";
		return $return;
	}
	
	/**
	 * CRéation de la prévisualisation du Pelican_Media
	 *
	 * @return mixed un tableau contenant :
	 * "file"=>le chemin du fichier
	 * "js"=>le javascript à exécuter suivant le contexte*
	 * "preview"=>le Pelican_Html de la prévisualisation
	 * "fileTag"=>Tag IMG contenant l'image ou le Tag A liant le fichier
	 * "thumbnailTag"]=>Tag IMG contenant la vignette ou le Tag A liant le fichier
	 * @param mixed $values Tableau de valeurs issues de la bdd si nécessaire
	 */
	protected function _makePreview($values = array(), $prop = true) {
		
		$oConnection = Pelican_Db::getInstance ();
		
		$file = $_REQUEST ["preview"];
		$pathinfo = pathinfo ( $file );
		$application = $this->aAllowedExtensions [$_REQUEST ["type"]] [strtolower ( $pathinfo ["extension"] )];
		
		// création des tags de prévisualisation et des propriétés
		$type = $_REQUEST ["type"];
		if ($type == "video" && ! in_array ( strtolower ( $pathinfo ["extension"] ), array ("zip", "xml" ) )) {
			$type = "file";
		}
		switch ($type) {
			case "image" :
				{
					// Hauteur figée pour l'affichage de la prévisualisation
					//$height = "height:".((int)Pelican::$config["FW_MEDIA_PREVIEW_LIMIT"]+20)."px;";
					$size = @$this->_getImageSizeMedia ( $file, $values );
					
					$sizeOrigine = $size;
					Pelican_Media::reduceSize ( $size, Pelican::$config ["FW_MEDIA_PREVIEW_LIMIT"] );
					$previewSize = $size;
					if ($size != $sizeOrigine) {
						$target = true;
					}
					
					// Tag de prévisualisation
					/** si un format est déjà sélectionné on l'affiche sinon on prend l'image originale */
					if ($_GET ["format"] && $_REQUEST ["format"] != "no") {
						$fileTag = "<img id=\"imgMediaFormat\" owidth=\"" . $sizeOrigine [0] . "\" oheight=\"" . $sizeOrigine [1] . "\" hspace=\"5\" vspace=\"5\" border=\"1\" src=\"" . Pelican::$config ["MEDIA_LIB_PATH"] . "/image_format.php?path=" . rawurlencode ( $file ) . "&format=" . $_GET ["format"] . "\" style=\"border-color:#CACACA;\" />";
					} else {
						$fileTag = "<img id=\"imgMediaFormat\" owidth=\"" . $sizeOrigine [0] . "\" oheight=\"" . $sizeOrigine [1] . "\" hspace=\"5\" vspace=\"5\" border=\"1\" " . $size [3] . " src=\"" . Pelican::$config ["MEDIA_HTTP"] . $file . "\" style=\"border-color:#CACACA;\" />";
					}
					
					// Tag de vignette (utilisée pour les pièces jointes de formulaire)
					$thumbnailTag = $this->_createThumbnail ( $file, $values );
					
					// Compléments des propriétés d'image
					if ($sizeOrigine) {
						$format = $sizeOrigine [0] . "x" . $sizeOrigine [1];
					
		//$complement = "<tr><td class=\"formlib\">Format&nbsp;:</td><td class=\"formval\">".$sizeOrigine[0]."x".$sizeOrigine[1]."</td></tr>";
					}
					
					// Initialisation des attributs de l'image utilisés pour le retour de la popup
					if ($sizeOrigine [0])
						$this->_jsForm .= JSTARGET . ".current.fileAttribut[\"width\"]=" . $sizeOrigine [0] . ";\r\n";
					if ($sizeOrigine [1])
						$this->_jsForm .= JSTARGET . ".current.fileAttribut[\"height\"]=" . $sizeOrigine [1] . ";\r\n";
					break;
				}
			case "flash" :
				{
					$target = true;
					
					$size = Pelican_Media::getFlashSize ( getUploadRoot ( $file ) );
					
					// Hauteur figée pour l'affichage de la prévisualisation
					//$height = "height:".((int)Pelican::$config["FW_MEDIA_PREVIEW_LIMIT"]+20)."px;";
					

					// Tag de prévisualisation
					$fileTag .= "<embed src='" . Pelican::$config ["MEDIA_HTTP"] . $file . "' quality='high' bgcolor='#FFFFFF' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash' align='center' scale='noscale' width='" . $size [0] . "' height='" . $size [1] . "' />";
					
					// Tag de vignette (utilisée pour les pièces jointes de formulaire)
					$thumbnailTag = $pathinfo ["basename"];
					
					// Initialisation des attributs du flash utilisés pour le retour de la popup
					$this->_jsForm = JSTARGET . ".current.fileAttribut[\"id\"]='" . $pathinfo ["basename"] . "';\r\n";
					$this->_jsForm .= JSTARGET . ".current.fileAttribut[\"quality\"]='high';\r\n";
					$this->_jsForm .= JSTARGET . ".current.fileAttribut[\"bgcolor\"]='#FFFFFF';\r\n";
					
					// Initialisation des attributs duflash utilisés pour le retour de la popup
					if ($size [0])
						$this->_jsForm .= JSTARGET . ".current.fileAttribut[\"width\"]=" . $size [0] . ";\r\n";
					if ($size [1])
						$this->_jsForm .= JSTARGET . ".current.fileAttribut[\"height\"]=" . $size [1] . ";\r\n";
					break;
				}
			default :
				{
					// Tag de prévisualisation
					

					$fileTag = "<img src=\"" . Pelican::$config ["LIB_PATH"] . Pelican::$config ['LIB_FILE'] . "/images/" . strtolower ( $pathinfo ["extension"] ) . ".gif\" alt=\"\" border=\"0\" align\"middle\">";
					$fileTag .= "&nbsp;<a href=\"" . Pelican::$config ["MEDIA_HTTP"] . $file . "\" target=\"_blank\"/>" . $pathinfo ["basename"] . "</a>";
					
					if ($type == "video") {
						$fileTag .= "&nbsp;" . Pelican_Html::img ( array (src => Pelican::$config ["MEDIA_LIB_PATH"] . "/images/voir.gif", style => "cursor:pointer;", onclick => "window.open('/library/Pelican/Media/public/popup_flash.php?id=" . $_REQUEST ["id"] . "','','width=380,height=700,menubar=0,status=1,titlebar=0,toolbar=0,resizable=1,scrollbars=1')", alt => "Voir la vidéo" ) );
					}
					
					// Tag de vignette (utilisée pour les pièces jointes de formulaire)
					$thumbnailTag = str_replace ( " ", "&nbsp;", $pathinfo ["basename"] );
					
					// Initialisation des attributs du fichier utilisés pour le retour de la popup
					$this->_jsForm = JSTARGET . ".current.fileAttribut[\"caption\"]='" . $pathinfo ["basename"] . "';\r\n";
					break;
				}
		}
		// Hauteur figée pour l'affichage de la prévisualisation
		$height = "padding:10 10 10 10;";
		
		// Compléments des propriétés de fichier
		$complement .= "<tr><td class=\"formlib\">" . t ( 'POPUP_MEDIA_LABEL_LAST_ACCES' ) . "&nbsp;:</td><td  class=\"formval\">" . $this->_fileMtimeMedia ( $file, $values ) . "</td></tr>";
		
		// partie supérieure : prévisualisation
		if ($target) {
			$preview = "<img src=\"" . Pelican::$config ["LIB_PATH"] . "/public/images/pixel.gif\" width=\"15\" height=\"1\" alt=\"\" border=\"0\">" . $fileTag . "<a href=\"" . Pelican::$config ["MEDIA_HTTP"] . $file . "\" target=\"_blank\"><img id=\"pictoView\" src=\"" . Pelican::$config ["MEDIA_LIB_PATH"] . "/images/view.gif\" width=\"15\" height=\"15\" alt=\"" . t ( 'POPUP_MEDIA_MSG_REAL_SIZE' ) . "\" border=\"0\" align=\"middle\" /></a>";
		} else {
			$preview = $fileTag;
		}
		
		if ($_REQUEST ["type"] == "image" && $_REQUEST ["format"] != "no") {
			if ($prop) {
				$preview .= "&nbsp;<a href=\"javascript:mediaEditor()\"><img id=\"pictoEditor\" src=\"" . Pelican::$config ["MEDIA_LIB_PATH"] . "/images/tool.gif\" width=\"15\" height=\"15\" alt=\"Modifier l'image\" border=\"0\" align=\"middle\" /></a>";
			}
			$preview = "<center>" . $preview . "<br />" . $this->_makeMediaFormat ( $file, $values [Pelican::$config ["FW_MEDIA_FIELD_LIB"]], $previewSize ) . "</center>";
		} else {
			$preview = "<center>" . $preview . "</center>";
		}
		
		//$preview .= "<tr><td class=\"formlib\">".t('POPUP_LABEL_FILE')."&nbsp;:</td><td class=\"formval\">".$pathinfo["basename"]."</td></tr>";
		//$preview .= "<tr><td class=\"formlib\">Type&nbsp;:</td><td class=\"formval\">".$application."</td></tr>";
		if (! $_REQUEST ["action"]) {
			$limit = 30;
		} else {
			$limit = 50;
		}
		/** Forcage */
		if ($values ["FORCAGE"] && $_REQUEST ["format"] != "no") {
			foreach ( $values ["FORCAGE"] as $force ) {
				$forcage .= $force ["MEDIA_FORMAT_LABEL"];
				if ($force ["MEDIA_FORMAT_UPLOAD"] == "1") {
					$forcage .= " (U)";
				}
				$forcage .= "&nbsp; <img src=\"" . Pelican::$config ["MEDIA_LIB_PATH"] . "/images/view.gif\" alt=\"Voir ce format\" border=\"0\" hspace=\"3\" align=\"middle\" onclick=\"viewMediaFormat(" . $force ["MEDIA_FORMAT_ID"] . ")\" style=\"cursor:pointer\"/>";
				if ($prop) {
					$forcage .= "<img src=\"" . Pelican::$config ["MEDIA_LIB_PATH"] . "/images/media_del.gif\" alt=\"Supprimer ce forçage (revenir en mode automatique pour ce format)\" border=\"0\" hspace=\"3\" align=\"middle\" onclick=\"delForcage(" . $force ["MEDIA_FORMAT_ID"] . ")\" style=\"cursor:pointer\"/><br />";
				}
			}
			$preview .= "<tr><td class=\"formlib\">Formats forcés&nbsp;:</td><td class=\"formval\">" . $forcage . "</td></tr>";
		}
		/* CHEMIN ET URL
	$preview .= "<tr><td class=\"formlib\">".t('POPUP_MEDIA_LABEL_PATH')."&nbsp;:</td><td class=\"formval\"><a title=\"".Pelican_Media::cleanDirectory(Pelican::$config["MEDIA_ROOT"].$file)."\" style=\"cursor:help;\">".Pelican_Media::reduceText(Pelican_Media::cleanDirectory(Pelican::$config["MEDIA_ROOT"].$file), $limit)."</a></td></tr>";
	$preview .= "<tr><td class=\"formlib\">".t('POPUP_MEDIA_LABEL_HTTP')."&nbsp;:</td><td class=\"formval\"><a title=\"".Pelican::$config["MEDIA_HTTP"].$file."\" style=\"cursor:help;\">".Pelican_Media::reduceText(Pelican::$config["MEDIA_HTTP"].$file, $limit)."</a></td></tr>";
	*/
		$taille = formatSize ( $this->_fileSizeMedia ( $file, $values ) );
		if ($taille) {
			$preview .= "<tr><td class=\"formlib\">" . $application . "&nbsp;:</td><td class=\"formval\">" . $format . " (" . $taille . ")</td></tr>";
		}
		$preview .= $complement;
		
		/** Si les infos ne sont pas en base, on les met à jour */
		if ($this->_updateList) {
			if ($this->_updateList ["width"])
				$where [] = Pelican::$config ["FW_MEDIA_FIELD_WIDTH"] . " = " . $this->_updateList ["width"];
			if ($this->_updateList ["height"])
				$where [] = Pelican::$config ["FW_MEDIA_FIELD_HEIGHT"] . " = " . $this->_updateList ["height"];
			if ($this->_updateList ["size"])
				$where [] = Pelican::$config ["FW_MEDIA_FIELD_WEIGHT"] . " = " . $this->_updateList ["size"];
			if ($this->_updateList ["date"])
				$where [] = Pelican::$config ["FW_MEDIA_FIELD_CREATION_DATE"] . " = " . $oConnection->dateStringToSql ( $this->_updateList ["date"] );
			if (! $values [Pelican::$config ["FW_MEDIA_FIELD_MD5"]] && $values [Pelican::$config ["FW_MEDIA_FIELD_PATH"]]) {
				$where [] = Pelican::$config ["FW_MEDIA_FIELD_MD5"] . "=" . "'" . md5_file ( Pelican_Media::cleanDirectory ( Pelican::$config ["MEDIA_ROOT"] . $values [Pelican::$config ["FW_MEDIA_FIELD_PATH"]] ) ) . "'";
			}
			if ($where) {
				$strSQL = "update " . Pelican::$config ["FW_MEDIA_TABLE_NAME"] . " set " . implode ( ",", $where ) . " where " . Pelican::$config ["FW_MEDIA_FIELD_ID"] . "=" . $_REQUEST ["id"];
				$oConnection->query ( $strSQL );
			}
		}
		//exit;
		

		$return ["file"] = $file;
		$return ["js"] = $this->_jsForm;
		$return ["preview"] = $preview;
		$return ["fileTag"] = $fileTag;
		$return ["thumbnailTag"] = $thumbnailTag;
		return $return;
	}
	
	/**
	 * Génération du formulaire de saisie ou de consultation
	 *
	 * @return mixed un tableau contenant :
	 * "file"=>le chemin du fichier
	 * "js"=>le javascript à exécuter suivant le contexte*
	 * "preview"=>le Pelican_Html de la prévisualisation
	 * "fileTag"=>Tag IMG contenant l'image ou le Tag A liant le fichier
	 * "thumbnailTag"]=>Tag IMG contenant la vignette ou le Tag A liant le fichier
	 * @param boolean $readO Champs en lecture seule ou non
	 */
	protected function _makeForm($readO = false) {
		
		$oConnection = Pelican_Db::getInstance ();
		
		$message = '';
		
		if ($_REQUEST ["root"]) {
			if (Pelican::$config ["FW_MEDIA_TABLE_NAME"]) {
				/** MODE BDD */
				$this->_folder = $oConnection->queryItem ( "select " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . " as \"folder\" FROM " . Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"] . " WHERE " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . "=" . $_REQUEST ["root"] );
			} else {
				/** MODE PHYSIQUE */
				$_REQUEST ["root"] = Pelican::$config ["MEDIA_ROOT"] . str_replace ( Pelican::$config ["MEDIA_ROOT"], "", $_REQUEST ["root"] );
				$this->_folder = str_replace ( $_REQUEST ["initial"], "", $_REQUEST ["root"] );
			}
		}
		
		/** CONFIGURATION DE L'AFFICHAGE FORMULAIRE */
		switch ($_REQUEST ["type"]) {
			/*** Traitement d'un dossier ***/
			case "folder" :
				{
					$configForm ["name"] = "folder_name";
					$configForm ["type"] = "text";
					$configForm ["submit"] = t ( 'POPUP_BUTTON_OK' );
					switch ($_REQUEST ["action"]) {
						case "add" :
							{
								$configForm ["title"] = t ( 'POPUP_MEDIA_LABEL_ADD_FOLDER' );
								$configForm ["label"] = t ( 'POPUP_MEDIA_LABEL_NEW_FOLDER' );
								$configForm ["action"] = "save";
								break;
							}
						case "edit" :
							{
								$this->_folder = "";
								$configForm ["title"] = t ( 'POPUP_MEDIA_LABEL_EDIT_FOLDER' );
								$configForm ["label"] = "Nom du dossier";
								$configForm ["action"] = "update";
								if (Pelican::$config ["FW_MEDIA_TABLE_NAME"] && $_REQUEST ["root"]) {
									$strSQL = "select * from " . Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"] . " C
								where " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . "=" . $_REQUEST ["root"];
									$this->_folder_properties = $oConnection->queryForm ( $strSQL );
								}
								$_FILES ["file_name"] = $this->_folder_properties [Pelican::$config ["FW_MEDIA_FIELD_FOLDER_NAME"]];
								break;
							}
					}
					break;
				}
			/*** Traitement d'un fichier ***/
			default :
				{
					$configForm ["name"] = "file_name";
					$configForm ["type"] = "text";
					$configForm ["action"] = "save";
					$configForm ["submit"] = t ( 'POPUP_BUTTON_OK' );
					if ($_REQUEST ["zone"] == "upload")
						$this->_folder = "";
					
					$configForm ["label"] = t ( 'POPUP_MEDIA_LABEL_NEW_FILE' );
					$configForm ["type"] = "file";
					$configForm ["required"] = true;
					switch ($_REQUEST ["action"]) {
						case "add" :
							{
								/** Ajout */
								$configForm ["title"] = t ( 'POPUP_MEDIA_LABEL_ADD_FILE' );
								
								break;
							}
						case "edit" :
							{
								/** Edition */
								$this->_folder = ""; //Pour ne pas afficher le dossier
								$configForm ["title"] = t ( 'POPUP_MEDIA_LABEL_PROPERTIES' );
								$configForm ["action"] = "update";
								$configForm ["label"] = t ( 'POPUP_MEDIA_LABEL_FILE_NAME' );
								$configForm ["required"] = false;
								break;
							}
						case "replace" :
							{
								/** Remplacement */
								$configForm ["title"] = t ( 'POPUP_MEDIA_LABEL_ADD_FILE' );
								$configForm ["label"] = t ( 'POPUP_MEDIA_LABEL_FILE_NAME' );
								$configForm ["submit"] = t ( 'POPUP_BUTTON_OK' );
								break;
							}
					}
					if ($_REQUEST ["action"] == "edit" || ! $_REQUEST ["action"]) {
						if (Pelican::$config ["FW_MEDIA_TABLE_NAME"]) {
							if ($_REQUEST ["id"]) {
								$strSQL = "select A.*, " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . " as \"folder\" ";
								if (Pelican::$config ["FW_MEDIA_FIELD_CREATION_DATE"]) {
									$strSQL .= ", " . $oConnection->dateSqlToString ( Pelican::$config ["FW_MEDIA_FIELD_CREATION_DATE"] ) . " as \"" . Pelican::$config ["FW_MEDIA_FIELD_CREATION_DATE"] . "\"";
								}
								if (Pelican::$config ["FW_MEDIA_FIELD_DEBUT_DATE"]) {
									$strSQL .= ", " . $oConnection->dateSqlToString ( Pelican::$config ["FW_MEDIA_FIELD_DEBUT_DATE"] ) . " as \"" . Pelican::$config ["FW_MEDIA_FIELD_DEBUT_DATE"] . "\"";
								}
								if (Pelican::$config ["FW_MEDIA_FIELD_FIN_DATE"]) {
									$strSQL .= ", " . $oConnection->dateSqlToString ( Pelican::$config ["FW_MEDIA_FIELD_FIN_DATE"] ) . " as \"" . Pelican::$config ["FW_MEDIA_FIELD_FIN_DATE"] . "\"";
								}
								if (Pelican::$config ["FW_MEDIA_FIELD_EXPIRATION_DATE"]) {
									$strSQL .= ", " . $oConnection->dateSqlToString ( Pelican::$config ["FW_MEDIA_FIELD_EXPIRATION_DATE"] ) . " as \"" . Pelican::$config ["FW_MEDIA_FIELD_EXPIRATION_DATE"] . "\"";
								}
								$strSQL .= " from " . Pelican::$config ["FW_MEDIA_TABLE_NAME"] . " A,
								" . Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"] . " C
								where A." . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . "=C." . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . "
								and " . Pelican::$config ["FW_MEDIA_FIELD_ID"] . "=" . $_REQUEST ["id"];
								$values = $oConnection->queryForm ( $strSQL );
							}
						} else {
							$configForm ["submit"] = ""; // on ne doit pas avoir de bouton submit
						}
					}
				}
		}
		
		/** contrôle des droits */
		$prop = true;
		$_REQUEST ["prop"] = true;
		if (! $usage) {
			if ($values) {
				$prop = (($values ["MEDIA_CREATION_USER"] == $_SESSION [APP] ["user"] ["id"]) || $_SESSION [APP] ["user"] ["main"]);
				if (! $prop) {
					$readO = true;
					$_REQUEST ["prop"] = false;
				}
			}
		}
		
		/** GENERATION DU FORMULAIRE */
		include_once (pelican_path ( 'Form' ));
		$oForm = Pelican_Factory::getInstance ( 'Form', false );
		$form .= $oForm->open ( "/_/Media/save", "post", "fForm", true );
		$form .= beginFormTable ( "0", "0", "form", false );
		$form .= $oForm->createHidden ( "action", $configForm ["action"] );
		$form .= $oForm->createHidden ( "type", $_REQUEST ["type"] );
		$form .= $oForm->createHidden ( "view", $_REQUEST ["view"] );
		$form .= $oForm->createHidden ( "media", $_REQUEST ["media"] );
		$form .= $oForm->createHidden ( "zone", $_REQUEST ["zone"] );
		$form .= $oForm->createHidden ( "root", $_REQUEST ["root"] );
		$form .= $oForm->createHidden ( "initial", $_REQUEST ["initial"] );
		$form .= $oForm->createHidden ( "tmp_name", $_REQUEST ["tmp_name"] );
		$form .= $oForm->createHidden ( "replace", "" );
		
		/** On recherche les formats forcés en base */
		if ($values [Pelican::$config ["FW_MEDIA_FIELD_ID"]]) {
			$strSQL = "
				SELECT mfi.MEDIA_FORMAT_ID, mf.MEDIA_FORMAT_LABEL, mfi.MEDIA_FORMAT_UPLOAD
				FROM #pref#_media_format_intercept mfi, #pref#_media_format mf
				WHERE mfi.MEDIA_FORMAT_ID = mf.MEDIA_FORMAT_ID
				AND mfi." . Pelican::$config ["FW_MEDIA_FIELD_ID"] . " = " . $values [Pelican::$config ["FW_MEDIA_FIELD_ID"]] . "
				ORDER BY mf.MEDIA_FORMAT_WIDTH, mf.MEDIA_FORMAT_HEIGHT";
			$values ["FORCAGE"] = $oConnection->queryTab ( $strSQL );
		}
		
		/** Contrôle d'utilisation */
		$usage = false;
		$usage = Pelican_Media::checkMediaUsage ( $_REQUEST ["id"] );
		
		if ($usage) {
			$message .= "<div class=\"erreur\">" . t ( "Media utilisé par un contenu" ) . "<br><b>" . t ( 'SUPP_IMPOS' ) . "</b></div>";
		}
		
		/** contrôle des droits */
		if (! $prop) {
			$message .= "<div class=\"erreur\">Media dont vous n'êtes pas le propriétaire<br><b>" . t ( 'SUPP_IMPOS' ) . "</b></div>";
		}
		
		/** Cas de l'edition  => on inclue dans le formulaire la prévisualisation */
		if ($_REQUEST ["type"] != "folder") {
			if ($_REQUEST ["action"] == "edit" || $readO) {
				$preview = $this->_makePreview ( $values, $prop );
				$form .= $preview ["preview"];
			}
			
			/** RECHERCHE : on récupère le folder du media, sinon on affiche celui par défaut */
			if ($values ["folder"])
				$this->_folder = $values ["folder"];
		}
		
		/** Si le formulaire est défini, on affiche son chemin d'accès à titre indicatif (lecture seule) */
		if ($this->_folder) {
			$form .= $oForm->createLabel ( t ( 'POPUP_LABEL_FOLDER' ), $this->_folder );
		}
		
		/** Si le champ de saisie du fichier est défini, on l'affiche : seule exception, l'edition */
		if ($configForm ["label"] && ! $readO) {
			if ($configForm ["type"] == "file") {
				$form .= $oForm->createBrowse ( $configForm ["name"], $configForm ["label"], "", $configForm ["required"], $_FILES ["file_name"], $readO, 25, false, "", false );
			} else {
				if ($_REQUEST ["type"] == "folder") { //     $control = "alphanum";
				}
				$form .= $oForm->createInput ( $configForm ["name"], $configForm ["label"], 50, $control, $configForm ["required"], $_FILES ["file_name"], $readO, 35 );
			}
		}
		
		/** Si on est en édition pour un dossier */
		if ($this->_folder_properties) {
			foreach ( $this->_folder_properties as $key => $value ) {
				$form .= $oForm->createHidden ( $key, $value );
			}
		}
		
		/** valeurs par défaut */
		if (! $values [Pelican::$config ["FW_MEDIA_FIELD_ID"]]) {
			$values [Pelican::$config ["FW_MEDIA_FIELD_ID"]] = Pelican::$config ["DATABASE_INSERT_ID"];
		}
		
		/** Si le traitement se fait en table, on affiche les Champs définis dans Pelican::$config["FW_MEDIA_FIELD"] */
		$showForm = (Pelican::$config ["FW_MEDIA_TABLE_NAME"] && ($_REQUEST ["zone"] != "upload" ? true : false) && $_REQUEST ["type"] != "folder");
		
		if (! $showForm) {
			$form .= $oForm->createHidden ( "folder_name_sauve", $this->_folder_properties [Pelican::$config ["FW_MEDIA_FIELD_FOLDER_NAME"]] );
		}
		
		if ($showForm || (Pelican::$config ["FW_MEDIA_FOLDER_BATCH"] && $_REQUEST ["action"] == "edit")) {
			/** dans le cas des propriétés de dossier : propagation d'éléments */
			if ($_REQUEST ["type"] == "folder" && $_REQUEST ["action"] == "edit") {
				$form .= $oForm->createLabel ( "", "<br /><br /><br /><br /><br /><br />" );
				$form .= $oForm->showSeparator ();
				$form .= $oForm->createLabel ( "", "Mise à jour globale" );
				$form .= $oForm->showSeparator ();
			}
			$values [Pelican::$config ["FW_MEDIA_FIELD_TYPE"]] = $_REQUEST ["type"];
			/** Taille par défaut des input */
			$taille = 38;
			while ( list ( $key, $value ) = each ( Pelican::$config ["FW_MEDIA_FIELD"] ) ) {
				if ($showForm || in_array ( $key, Pelican::$config ["FW_MEDIA_FOLDER_BATCH"] )) {
					if ($value [1] == "hidden") {
						$form .= $oForm->createHidden ( $key, $values [$key] );
					} else {
						$controlInput = $control;
						$tailleInput = $taille;
						switch ($value [1]) {
							case "date" :
								$controlInput = "date";
								$tailleInput = 10;
							case "readonly" :
							case "text" :
								$form .= $oForm->createInput ( $key, $value [0], "", $controlInput, $value [2], $values [$key], ($value [1] == "readonly" ? true : $readO), $tailleInput );
								break;
							case "file" :
								$form .= $oForm->createBrowse ( $key, $value [0], "", $value [2], $values [$key], $readO, ($taille - 2) );
								break;
							case "hidden" :
								$form .= $oForm->createHidden ( $key, $values [$key] );
								break;
							case "label" :
								$form .= $oForm->createLabel ( $value [0], $values [$key] );
								break;
							case "textarea" :
								$form .= $oForm->createTextArea ( $key, $value [0], $value [2], $values [$key], "", $readO );
								break;
							case "editor" :
								$width = 380 / ($_GET ["media"] ? 1 : 1.5);
								$form .= $oForm->createEditor ( $key, $value [0], $value [2], $values [$key], $readO, true, "", $width, 120, 1 );
								break;
							case "checkbox" :
								$form .= $oForm->createCheckBoxFromList ( $key, $value [0], array ("1" => "" ), $values [$key], false, $readO );
								break;
						}
					}
				
		// Méthode de la classe Pelican_Plugin appelée pour le traitement spécifique sur le formulaire média inclu dans les plugins.
				//$form .= Pelican_Plugin::hook('media', &$oForm, array($value, $values));
				}
			}
			if ($_REQUEST ["type"] == "folder" && $_REQUEST ["action"] == "edit") {
				$form .= $oForm->createLabel ( "", "<br /><br /><br /><br /><br /><br />" );
			}
		}
		/** Champs de suivi */
		if (! $values ["MEDIA_CREATION_USER"]) {
			$values ["MEDIA_CREATION_USER"] = $_SESSION [APP] ["user"] ["id"];
		}
		
		if ($_SESSION [APP] ["user"] ["main"] && $_REQUEST ["type"] != "folder") {
			$aUsers = getComboValuesFromCache ( "Backend/User", $_SESSION [APP] ['SITE_ID'] );
			$form .= $oForm->createComboFromList ( "MEDIA_CREATION_USER", t ( "Createur" ), $aUsers, $values ["MEDIA_CREATION_USER"], false, $readO, "1" );
		} else {
			$form .= $oForm->createHidden ( "MEDIA_CREATION_USER", $values ["MEDIA_CREATION_USER"] );
		}
		
		//$form .= $oForm->createHidden("MEDIA_CREATION_USER", $values["MEDIA_CREATION_USER"]);
		$form .= $oForm->createHidden ( "MEDIA_CREATION_DATE", (! $values ["MEDIA_CREATION_DATE"] ? ":DATE_COURANTE" : $values ["MEDIA_CREATION_DATE"]) );
		
		if (! $readO) {
			$form .= $oForm->createFreeHtml ( "<tr>" );
			$form .= $oForm->createFreeHtml ( "<td class=\"formval\" colspan=\"2\" align=\"center\">" );
			if ($_REQUEST ["tmp_name"]) {
				$form .= $oForm->createButton ( "replace", t ( 'POPUP_MEDIA_LABEL_REPLACE' ), "document.fForm.replace.value=true;document.fForm.submit();" );
				$form .= $oForm->createFreeHtml ( "&nbsp;" );
			}
			// Si le libellé du bouton de validation est défini, on l'affiche
			if ($configForm ["submit"]) {
				$form .= $oForm->createSubmit ( "submit", $configForm ["submit"] );
			}
			$form .= $oForm->createFreeHtml ( "</td></tr>" );
		}
		
		$form .= endFormTable ( false );
		$form .= $oForm->close ();
		
		// Zend_Form start
		if (($oForm instanceof Zend_Form)) {
			/********** Pour faire correspondre **********/
			$form = "<br /><div class=\"title\">" . t ( 'POPUP_SEARCH_TITLE' ) . "</div><br />";
			$form .= formToString ( $oForm, $form );
		/*********************************************/
		}
		// Zend_Form stop
		

		if ($preview ["fileTag"]) {
			if (Pelican::$config ["FW_MEDIA_TABLE_NAME"]) {
				// si la gestion se fait en table, on récupère l'id
				$id = $_REQUEST ["id"];
			} else {
				// sinon on récupère le chemin d'accès
				$id = rawurlencode ( $preview ["file"] );
			}
		/*** Définition dans la fenêtre parente du fichier utilisé : id, chemin, Tag HTML, Tag THUMBNAIL ***/
		}
		
		// alt du fichier
		if (Pelican::$config ["FW_MEDIA_FIELD_ALT"]) {
			$preview ["js"] .= JSTARGET . ".current.fileAttribut[\"alt\"]='" . str_replace ( "'", "\\'", $values [Pelican::$config ["FW_MEDIA_FIELD_ALT"]] ) . "';\r\n";
		}
		$form .= "<script type=\"text/javascript\">";
		$form .= JSTARGET . ".current.usage = " . ($usage ? "true" : ($prop ? "false" : "true")) . ";";
		$form .= JSTARGET . ".setMedia('" . rawurlencode ( $preview ["file"] ) . "','" . rawurlencode ( $preview ["fileTag"] ) . ">','" . rawurlencode ( $preview ["thumbnailTag"] ) . "','" . $id . "');";
		if ($_REQUEST ["type"] == "image") {
			$form .= "if (document.getElementById('cboMediaFormat')) {changeMediaFormat();}";
		}
		$form .= $this->_folder_js . $preview ["js"];
		$form .= "</script>";
		
		$return ["file"] = $preview ["file"];
		$return ["js"] = $preview ["js"];
		$return ["html"] = $message . $form;
		$return ["fileTag"] = $preview ["fileTag"];
		$return ["thumbnailTag"] = $preview ["thumbnailTag"];
		return $return;
	}
	
	/**
	 * Création du Pelican_Html d'affichage de d'une liste de Pelican_Media suivant le type (image, fichier ou flash)
	 *
	 * @return  string Le Pelican_Html de prévisualisation
	 * @param  string  $dir Répertoire racine
	 */
	protected function _makeList($dir) {
		
		$oConnection = Pelican_Db::getInstance ();
		
		/** nombre de colonnes et de pages par défaut */
		$cols = 3;
		$pages = 3;
		if ($_GET ["media"]) {
			/** pour l'affichage en onglet */
			$pages = 4;
		}
		$rows = 4;
		if ($_SESSION ["screen_height"]) {
			$rows = intVal ( $_SESSION ["screen_height"] / 127 ) - 1;
		}
		
		/** Sélection des données */
		if (Pelican::$config ["FW_MEDIA_TABLE_NAME"]) {
			
			$strSqlList = "select " . Pelican::$config ["FW_MEDIA_FIELD_ID"] . " as \"id\", " . Pelican::$config ["FW_MEDIA_FIELD_LIB"] . " as \"name\", " . Pelican::$config ["FW_MEDIA_FIELD_LIB"] . " as \"title\", " . Pelican::$config ["FW_MEDIA_FIELD_PATH"] . " as \"path\" ";
			
			/** si les champs de hauteur, largeur et poids sont définis on les rajoute à la requête */
			if (Pelican::$config ["FW_MEDIA_FIELD_WIDTH"]) {
				$strSqlList .= ", " . Pelican::$config ["FW_MEDIA_FIELD_WIDTH"];
			}
			if (Pelican::$config ["FW_MEDIA_FIELD_HEIGHT"]) {
				$strSqlList .= ", " . Pelican::$config ["FW_MEDIA_FIELD_HEIGHT"];
			}
			if (Pelican::$config ["FW_MEDIA_FIELD_WEIGHT"]) {
				$strSqlList .= ", " . Pelican::$config ["FW_MEDIA_FIELD_WEIGHT"];
			}
			if (Pelican::$config ["FW_MEDIA_FIELD_CREATION_DATE"]) {
				$strSqlList .= ", " . $oConnection->dateSqlToString ( Pelican::$config ["FW_MEDIA_FIELD_CREATION_DATE"] ) . " as \"" . Pelican::$config ["FW_MEDIA_FIELD_CREATION_DATE"] . "\"";
				$strSqlList .= ", " . Pelican::$config ["FW_MEDIA_FIELD_CREATION_DATE"] . " as LIST_ORDER";
			}
			if (isset ( $_GET ["recherche"] )) {
				if ($_GET ["recherche"] || $_GET ["action"] == "search") {
					$_SESSION [APP] ["media_search"] [$_REQUEST ['zone']] ["recherche"] = $_GET ["recherche"];
					$_SESSION [APP] ["media_search"] [$_REQUEST ['zone']] ["path"] = $_GET ["path"];
				} else {
					unset ( $_SESSION [APP] ["media_search"] [$_REQUEST ['zone']] );
				}
			}
			
			$aBind = array ();
			$indexBind = 1;
			
			if ($_SESSION [APP] ["media_search"] [$_REQUEST ['zone']]) {
				/** s'il y a un mot recherché on met le score */
				/*
			if ($_SESSION[APP]["media_search"][$_REQUEST['zone']]["recherche"] && Pelican::$config["DATABASE_TYPE"] == "oracle") {
			$strSqlList .= ", score(0) as PERTINENCE ";
			}*/
				$strSqlList .= " from " . Pelican::$config ["FW_MEDIA_TABLE_NAME"] . ",
					" . Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"] . " c
					where " . Pelican::$config ["FW_MEDIA_TABLE_NAME"] . "." . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . "=c." . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"];
				if ($_SESSION [APP] ["media_search"] [$_REQUEST ['zone']] ["path"]) {
					$bind = $oConnection->setBindValue ( $aBind, $indexBind ++, Pelican_Text::unhtmlentities ( $_SESSION [APP] ["media_search"] [$_REQUEST ['zone']] ["path"] ), "string" );
					$strSqlList .= " and " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] . " like " . $oConnection->getConcatClause ( array ($bind, "'%'" ) ) . " AND c.SITE_ID = " . $_SESSION [APP] ['SITE_ID'];
				}
				if ($_SESSION [APP] ["media_search"] [$_REQUEST ['zone']] ["recherche"]) {
					$strSqlList .= " and (";
					$searchFile = Pelican_Media::getSearchFile ( $_SESSION [APP] ["media_search"] [$_REQUEST ['zone']] ["recherche"] );
					$strSqlList .= $oConnection->getSearchClauseLike ( Pelican::$config ["FW_MEDIA_FIELD_LIB"], $searchFile, 0, ":" . $indexBind ++, $aBind );
					if (is_numeric ( $searchFile )) {
						$strSqlList .= " or ";
						$bind = $oConnection->setBindValue ( $aBind, $indexBind ++, $searchFile, "string" );
						$strSqlList .= Pelican::$config ["FW_MEDIA_FIELD_ID"] . "=" . $bind;
					}
					if (! is_numeric ( $searchFile )) {
						$strSqlList .= " or ";
						$bind = $oConnection->setBindValue ( $aBind, $indexBind ++, $searchFile, "string" );
						$strSqlList .= Pelican::$config ["FW_MEDIA_FIELD_PATH"] . " like " . $oConnection->getConcatClause ( array ("'%'", $bind ) );
					}
					$strSqlList .= ")";
				}
				$bind = $oConnection->setBindValue ( $aBind, $indexBind ++, $_REQUEST ["type"], "string" );
				$strSqlList .= " and " . Pelican::$config ["FW_MEDIA_FIELD_TYPE"] . " =" . $bind;
				/** Mutualisation : consultation des medias d'un autre site */
				if ($_SESSION [APP] ["SITE_MEDIA"] != $_SESSION [APP] ['SITE_ID']) {
					$strSqlList .= " and MEDIA_DIFFUSED = 1 ";
				}
				$strSqlList .= " order by ";
				/*
			if ($_SESSION[APP]["media_search"][$_REQUEST['zone']]["recherche"] && Pelican::$config["DATABASE_TYPE"] == "oracle") {
			$strSqlList .= " PERTINENCE desc, ";
			}*/
				$strSqlList .= " LIST_ORDER desc, \"id\" desc";
			
			} else {
				$bind = $oConnection->setBindValue ( $aBind, $indexBind ++, $dir, "string" );
				$strSqlList .= " from " . Pelican::$config ["FW_MEDIA_TABLE_NAME"] . " where " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . "=" . $bind;
				$bind = $oConnection->setBindValue ( $aBind, $indexBind ++, $_REQUEST ["type"], "string" );
				$strSqlList .= " and " . Pelican::$config ["FW_MEDIA_FIELD_TYPE"] . " =" . $bind . " ";
				if ($_SESSION [APP] ["SITE_MEDIA"] != $_SESSION [APP] ['SITE_ID']) {
					$strSqlList .= " and MEDIA_DIFFUSED = 1 ";
				}
				$strSqlList .= " order by LIST_ORDER desc, \"id\" desc";
			}
		} else {
			/** MODE PHYSIQUE : Parcours des dossiers, en filtrant sur les extensions autorisées */
			$fulldir = Pelican_Media::cleanDirectory ( Pelican::$config ["MEDIA_ROOT"] . $dir );
			$ressource = opendir ( $fulldir );
			while ( $file = readdir ( $ressource ) ) {
				$pathinfo = pathinfo ( $file );
				if ($this->aAllowedExtensions [$_REQUEST ["type"]] [strtolower ( $pathinfo ["extension"] )] || $_REQUEST ["type"] == "all") {
					$strSqlList [] = array ("id" => $file, "name" => $file, "path" => $dir . $file );
				}
			}
			closedir ( $ressource );
		}
		switch ($_REQUEST ["type"]) {
			case "image" :
				{
					/** Liste d'images */
					$table = Pelican_Factory::getInstance ( 'List', "", "", 0, 0, 0, "thumbnail", "", true, true );
					$table->setCSS ( array ("tblalt1", "tblalt2" ) );
					$table->navLimitRows = $rows * $cols;
					$table->navMaxLinks = $pages;
					$table->setCSS ( array ("tblmediatd", "tblmediatd" ) );
					$table->setValues ( $strSqlList, "", "", $aBind );
					$table->sNavClass = "tblmediafooter";
					/** Pagination en haut */
					if ($table->navRows && $table->bTablePages) {
						$navigation .= "<tr bordercolor=\"#000000\"><td valign=\"middle\" colspan=\"" . $cols . "\">";
						$navigation .= $table->getPages ();
						$navigation .= "</td></tr>";
					}
					
					$preview .= $navigation;
					/** Liste de vignettes */
					if ($table->aTableValues && $table->aTableValues [0]) {
						$preview .= "<table cellspacing=\"0\" cellpadding=\"0\" class=\"thumbnail\">";
						$preview .= "<tr>";
						foreach ( $table->aTableValues as $image ) {
							$i ++;
							if ($i >= ($cols + 1)) {
								$preview .= "</tr>";
								$preview .= "<tr>";
								$i = 1;
							}
							$img = $this->_createThumbnail ( $image ["path"], $image, $image ["title"] . "\n" . $image [Pelican::$config ["FW_MEDIA_FIELD_CREATION_DATE"]] . "\n" . $image [Pelican::$config ["FW_MEDIA_FIELD_WIDTH"]] . "x" . $image [Pelican::$config ["FW_MEDIA_FIELD_HEIGHT"]] . " (" . $image [Pelican::$config ["FW_MEDIA_FIELD_WEIGHT"]] . " octets)" );
							
							if (! $image ["title"]) {
								$info = pathinfo ( $image ["path"] );
								$image ["title"] = str_replace ( "." . $info ["extension"], "", $info ["basename"] );
							}
							$preview .= "<td class=\"thumbnail_td\" onclick=\"" . JSTARGET . ".previewMedia('" . rawurlencode ( $image ["path"] ) . "','" . $image ["id"] . "',this);\"><center>";
							//      $preview .= "<div class=\"thumbnail_div\">".str_replace("<img ", "<img onmousedown=\"".JSTARGET.".initDrag(this,'file');\" ", $img)."</div>".Pelican_Media::reduceText($image["title"], 18);
							$preview .= "<div class=\"thumbnail_div\">" . str_replace ( "vignette", $image ["title"], str_replace ( "<img ", "<img onmousedown=\"" . JSTARGET . ".initDrag(" . $image ["id"] . ",'file');\" ", $img ) ) . "</div>" . Pelican_Media::reduceText ( $image ["title"], 18 );
							$preview .= "</center></td>";
						}
						$preview .= "</tr>";
					} else {
						$preview .= "<tr>";
						$preview .= "<td colspan=\"\"><span class=erreur>" . t ( 'TABLE_NO_RECORD' ) . "</span></td>";
						$preview .= "</tr>";
					}
					$preview .= "</table>";
					$preview = "<center>" . $preview . "</center>";
					break;
				}
			default :
				{
					/** Liste de fichiers par défaut */
					$table = Pelican_Factory::getInstance ( 'List', "", "", 0, 0, 0, "thumbnail", "", false, true, true );
					$table->setCSS ( array ("tblalt1", "tblalt2" ) );
					$table->navLimitRows = 15;
					$table->navMaxLinks = $pages;
					$table->setCSS ( array ("tblmediatd", "tblmediatd" ) );
					$table->setValues ( $strSqlList, "", "", $aBind );
					$table->sNavClass = "tblmediafooter";
					$table->addImage ( "extension", Pelican::$config ["LIB_PATH"] . Pelican::$config ['LIB_FILE'] . "/images/", "path", "5", "center", "extension", "tblmediath" );
					$table->addColumn ( "name", "name", "95", "left", "", "tblmediath" );
					$table->addRowEvent ( "onclick", JSTARGET . ".previewMedia", array ("path" => "path", "id" => "id", "" => "this=this" ) );
					//$table->addRowEvent("onmousedown", "".JSTARGET.".initDrag", array("id" => "id", ""=>"file=file"));
					$preview = $table->getTable ();
					break;
				}
		}
		
		$this->_jsForm .= "<script type=\"text/javascript\">" . JSTARGET . ".lastPreview = document.location.href ;</script>\r\n";
		$preview = $this->_jsForm . $preview;
		
		return $preview;
	}
	
	/**
	 * Récupération de la date de création d'un fichier soit à partir de la BDD soit physiquement
	 *
	 * @return string une chaine date
	 * @param string $file Le chemin absolu de l'image
	 * @param mixed $values Tableau de valeurs issues de la bdd si nécessaire
	 */
	protected function _fileMtimeMedia($file, $values = array()) {
		
		/** si la taille existe en base, on la récupère */
		if ($values [Pelican::$config ["FW_MEDIA_FIELD_CREATION_DATE"]]) {
			$date = $values [Pelican::$config ["FW_MEDIA_FIELD_CREATION_DATE"]];
		} else {
			$date = date ( "d/m/Y", @filemtime ( Pelican_Media::cleanDirectory ( Pelican::$config ["MEDIA_ROOT"] . $file ) ) );
			$this->_updateList ["date"] = $date;
		}
		return $date;
	}
	
	/**
	 * Récupération du poids d'un fichier soit à partir de la BDD soit physiquement
	 *
	 * @return string une taille en octets
	 * @param string $file Le chemin absolu de l'image
	 * @param mixed $values Tableau de valeurs issues de la bdd si nécessaire
	 */
	protected function _fileSizeMedia($file, $values = array()) {
		
		/** si la taille existe en base, on la récupère */
		if ($values [Pelican::$config ["FW_MEDIA_FIELD_WEIGHT"]]) {
			$size = $values [Pelican::$config ["FW_MEDIA_FIELD_WEIGHT"]];
		} else {
			$size = filesize ( Pelican_Media::cleanDirectory ( Pelican::$config ["MEDIA_ROOT"] . $file ) );
			$this->_updateList ["size"] = $size;
		}
		return $size;
	}
	
	/**
	 * Récupération des tailles d'une image soit à partir de la BDD soit physiquement
	 *
	 * @return mixed un tableau sur le format du getimagesize PHP
	 * @param string $file Le chemin absolu de l'image
	 * @param mixed $values Tableau de valeurs issues de la bdd si nécessaire
	 */
	protected function _getImageSizeMedia($file, $values = array()) {
		
		/** si la taille existe en base, on la récupère */
		if ($values [Pelican::$config ["FW_MEDIA_FIELD_WIDTH"]] || $values [Pelican::$config ["FW_MEDIA_FIELD_HEIGHT"]]) {
			$size [0] = $values [Pelican::$config ["FW_MEDIA_FIELD_WIDTH"]];
			$size [1] = $values [Pelican::$config ["FW_MEDIA_FIELD_HEIGHT"]];
			$size [3] = ($size [0] ? "width=\"" . $size [0] . "\"" : "") . " " . ($size [1] ? "height=\"" . $size [1] . "\"" : "");
		} else {
			$filepath = Pelican_Media::cleanDirectory ( Pelican::$config ["MEDIA_ROOT"] . $file );
			if (file_exists ( $filepath )) {
				$size = @getimagesize ( $filepath );
				$this->_updateList ["width"] = $size [0];
				$this->_updateList ["height"] = $size [1];
			}
		}
		return $size;
	}
	
	/**
	 * Création du Pelican_Html d'affichage de la vignette d'une image (et création de la vignette à la volée si elle n'existe pas) : comportement dépendant du paramètre Pelican::$config["FW_MEDIA_USE_THUMBNAIL"]
	 *
	 * @return  string Le Tag Pelican_Html IMG contenant la vignette
	 * @param  string  $file    Chemin du fichier image
	 * @param mixed $values Tableau de valeurs issues de la bdd si nécessaire
	 * @param string $altText Info-Bulle
	 */
	protected function _createThumbnail($file, $values = array(), $altText = "") {
		
		$width = (Pelican::$config ["IMG_WIDTH_THUMBNAIL"] ? Pelican::$config ["IMG_WIDTH_THUMBNAIL"] : "50");
		$height = (Pelican::$config ["IMG_HEIGHT_THUMBNAIL"] ? Pelican::$config ["IMG_HEIGHT_THUMBNAIL"] : "50");
		
		$this->_counter ++;
		
		// Redimensionnement au niveau du tag Pelican_Html IMG
		$size = @$this->_getImageSizeMedia ( $file, $values );
		Pelican_Media::reduceSize ( $size, $width );
		
		if (! preg_match ( '/http:\/\//', $file ) && ! preg_match ( '/ftp:\/\//', $file ) && ! preg_match ( '/mms:\/\//', $file )) {
			$path = Pelican::$config ["MEDIA_HTTP"] . $file;
		} else {
			$path = $file;
		}
		
		$filePath = pathinfo ( $path );
		
		if ($filePath ['extension'] != 'gif') {
			$path = Pelican_Media::getFileNameMediaFormat ( $path, Pelican::$config ["IMG_FORMAT_THUMBNAIL"] );
		}
		
		$return = "<img id=\"media_" . $values ["id"] . "\" src=\"" . $path . "\" alt=\"" . rawurlencode ( $altText ) . "\" " . $size [3] . " border=\"0\" />";
		
		return $return;
	}
	
	protected function _trigger($action, $values) {
		
		$media = Pelican_Db::$values ["MEDIA_PATH"];
		$pathinfo = pathinfo ( $media );
		$extensions = getAllowedExtensions ();
		
		if (Pelican::$config ["FW_MEDIA_TRIGGER"]) {
			foreach ( Pelican::$config ["FW_MEDIA_TRIGGER"] as $post => $params ) {
				switch ($post) {
					case "research" :
						{
							/** recherche */
							if (in_array ( $pathinfo ["extension"], $params )) {
								Pelican_Db::$values = $values;
								$this->_triggerResearch ( $media, $action );
							}
							break;
						}
					case "rsync" :
						{
							if ($params) {
								foreach ( $params as $ip ) {
									$this->_triggerSync ( $media, $ip );
								}
							}
							break;
						}
					case "image_format" :
						{
							if ($params && in_array ( $pathinfo ["extension"], array_keys ( $extensions ['image'] ) )) {
								$rsync = Pelican::$config ["FW_MEDIA_TRIGGER"] ["rsync"];
								foreach ( $params as $format ) {
									$media2 = $this->_triggerImageFormat ( $media, $format, $action );
									/** rsync s'il a été défini */
									if ($rsync) {
										foreach ( $rsync as $host ) {
											$this->_triggerSync ( $media2, $host );
										}
									}
								}
							}
						}
				}
			}
		}
	}
	
	protected function _triggerResearch($media, $action) {
		
		$_POST ["form_action"] = $action;
		$publication = true;
		Pelican_Db::$values ['SITE_ID'] = $_SESSION [APP] ['SITE_ID'];
		$RESEARCH_TYPE = "MEDIA";
		Pelican_Db::$values ["MEDIA_STATUS"] = 1;
		Pelican_Db::$values ["RESEARCH_TYPE"] = "MEDIA";
		Pelican_Db::$values ["RESEARCH_ID"] = Pelican_Db::$values ["MEDIA_ID"];
		Pelican_Db::$values ["MEDIA_CLEAR_URL"] = "#MEDIA_PATH#" . $media;
		Pelican_Db::$values ["RESEARCH_STATUS"] = 1;
		Pelican_Db::$values ['LANGUE_ID'] = 1;
		include (Pelican::$config ["TRANSACTION_ROOT"] . "/db_research.php");
	}
	
	protected function _triggerSync($media, $host) {
		
		$file = getUploadRoot ( $media );
		$cmd = strtr ( Pelican::$config ["FW_MEDIA_RSYNC_CMD"], array ("%SRC%" => $file, "%HOST%" => $host, "%DEST%" => dirname ( $file ) ) );
		//debug($cmd);
		return $cmd;
	}
	
	protected function _triggerImageFormat($media, $format, $action) {
		
		/** On a forcé le format par un recadrage */
		if ($action != Pelican::$config ["DATABASE_DELETE"]) {
			$image = new Pelican_Cache_Media ( $media, $format, "", true, DAY );
			$image->name = $image->_newFile;
			$image->instance->storeValue ( $image->name, $image->value );
			$image->instance->setLifeTime ( $image->lifeTime );
			$return = $image->name;
		} else {
			$file = getUploadRoot ( Pelican_Media::getFileNameMediaFormat ( $media, $format ) );
			@unlink ( $file );
			$return = $file;
		}
		return $return;
	}

}