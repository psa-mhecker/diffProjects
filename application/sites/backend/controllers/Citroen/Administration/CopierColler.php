<?php

require_once(Pelican::$config["APPLICATION_CONTROLLERS"] . "/Administration/Directory.php");
require_once(Pelican::$config['APPLICATION_CONTROLLERS'] . '/Citroen.php');
require_once(Pelican::$config['LIB_ROOT'] . '/Pelican/Mail.php');

/**
 *
 */
class Citroen_Administration_CopierColler_Controller extends Citroen_Controller
{

	protected $administration = true;
	protected $form_name = "copiecolle";

	/**
	 *
	 */
	public function editAction()
	{
		parent::editAction();
		$oForm = Pelican_Factory::getInstance('Form', true);
		$oForm->bDirectOutput = false;
		$form = $oForm->open(Pelican::$config['DB_PATH']);
		$form .= $this->beginForm($oForm);
		$form .= $oForm->beginFormTable();
		if (isset($_REQUEST['msg']) && !empty($_REQUEST['msg'])) {
			$form .= '<p>' . $_REQUEST['msg'] . '</p><br/><br/>';
		}else{
			$sSqlListe = "
				select
				site_id as id,
				site_label as lib
				from #pref#_site
				where site_id not in (" . Pelican:: $config['SITE_BO'] . ")
				ORDER BY id";
			$oConnection = Pelican_Db::getInstance();
			$aTemp = $oConnection->queryTab($sSqlListe);
			$aPaysSources = array();
			if ($aTemp) {
				foreach ($aTemp as $tmp) {
					$aPaysSources[$tmp['id']] = $tmp['lib'];
				}
			}
			$form .= $oForm->createComboFromSql($oConnection, 'SITE_ID_SOURCE', t('PAYS_SOURCE'), $sSqlListe, $sSqlListe, true, $readO, "1", false, "", true, false, "onchange=\"callAjax('/Citroen_Administration_CopierColler/ajaxShowComboLang',this.value);\"");
			$form .= $oForm->createHidden("NB_LANGUES", "");
			$form .= $oForm->endFormTable();
			$form .= $oForm->createFreeHtml("<table id='comboLang' class='form'></table>");
			$form .= $oForm->createFreeHtml("<div id='arborescence'></div>");
			$form .= $this->endForm($oForm);
			$oForm->createJS('
				if ($("input[name=\'LANGUE_SOURCE\']:checked").length == 0) {
					alert(\'' . t('LANGUE_SOURCE_OBLIGATOIRE', 'js2') . '\');
					return false;
				} else
				if ($("input[name=\'ARBORESCENCE_SOURCE_ID[]\']:checked").length == 0) {
					alert(\'' . t('ARBORESCENCE_SOURCE_OBLIGATOIRE', 'js2') . '\');
					return false;
				} else
				if ($("input[name=\'ARBORESCENCE_CIBLE_ID[]\']:checked").length == 0) {
					alert(\'' . t('ARBORESCENCE_CIBLE_OBLIGATOIRE', 'js2') . '\');
					return false;
				}
				else {
					for (i=0; i<=$("td.langues").length; i++) {
						if ($("input[name=\'LANGUE_CIBLE_"+i+"\']:checked").val() && $("#LANGUE_SOURCE_"+i+" option:selected").val() == "") {
							alert(\'' . t('PAYS_CIBLE_OBLIGATOIRE', 'js2') . '\');
							return false;
						}
					}
				}

			');
		}
		$form .= $oForm->close();
		$this->setResponse($form);
	}

	/**
	 *
	 */
	public function saveAction()
	{
		$oConnection = Pelican_Db::getInstance();
		if (Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE) {
			// Création d'un composite de page
			$pageComposite = Pelican_Factory::getInstance('PageComposite');
			$surchargeTitleBo = '';
			$siteIdCible = $_SESSION[APP]['SITE_ID'];
			$pageIdCible = Pelican_Db::$values['ARBORESCENCE_CIBLE_ID'][0];
			$siteIdSource = Pelican_Db::$values['SITE_ID_SOURCE'];
			$aIdPagesSource	=	$this->getPageParenteEtEnfants(Pelican_Db::$values['ARBORESCENCE_SOURCE_ID'][0], Pelican_Db::$values['SITE_ID_SOURCE'], Pelican_Db::$values['LANGUE_SOURCE']);
			
            //Pour la 1ere page on va mettre le pageIdCible comme parentID
			$aIdPagesSource[0]['PAGE_PARENT_ID']	=	$pageIdCible;
			
			//On boucle sur toutes les pages à diffuser
			foreach ($aIdPagesSource as $pageIdSource) {
				$etat = Pelican:: $config["PAGE_ETAT"]['BROUILLON'];
				//La page diffusée provient du site master
				// On récupère pour un site source toutes les langues cibles et sources associées
				$i = 0;
				while ($i < Pelican_Db::$values['NB_LANGUES']) {
					if (Pelican_Db::$values['LANGUE_SOURCE_' . $i] && Pelican_Db::$values['LANGUE_CIBLE_' . $i]) {
						$langIdSource = Pelican_Db::$values['LANGUE_SOURCE_' . $i];
						$langIdCible = Pelican_Db::$values['LANGUE_CIBLE_' . $i];
						$bDiffusion = false;
						$bXml = false;
						// On ajoute une page dans le composite
                        if ($pageComposite->verifGabaritUnique($pageIdSource['PAGE_ID'], $langIdSource, $langIdCible, $siteIdCible)) {
                            $msg = t('GABARIT_ONE_USE_ALREADY_EXIST');debug('eee');
                            Pelican_Db::$values["form_retour"] = '/_/Index/child?tid=307&id=1&msg=' . $msg;
                            return false;
                        }
						$pageComposite->addPage($pageIdSource['PAGE_ID'], $langIdSource, $siteIdSource, $langIdCible, $siteIdCible, $bXml, $etat, $surchargeTitleBo, $bDiffusion, $pageIdSource['PAGE_PARENT_ID']);
					}
					$i++;
				}
			}
			//On enregistre toutes les pages dans le composite
			$aPageSave = $pageComposite->save();
			if (false == $aPageSave['ERROR']) {
				// Modification du PAGE_CLEAR_URL des copies pour éviter les doublons rewriting
				if(isset($aPageSave['NEW_PID'])){
					$updateStmt = "UPDATE #pref#_page_version SET PAGE_CLEAR_URL = NULL WHERE PAGE_ID = :PAGE_ID";
					$bind = array();
					foreach($aPageSave['NEW_PID'] as $key => $val){
						$bind[':PAGE_ID'] = $val;
						$oConnection->query($updateStmt, $bind);
					}
				}
				
				$msg = t('LE_COPIER_COLLER_EST_UN_SUCCES');
			} else {
				$msg = t('ERREUR_PENDANT_LE_COPIER_COLLER');
			}
		}
		Pelican_Db::$values["form_retour"] = '/_/Index/child?tid=307&id=1&msg=' . $msg;
	}

	/**
	 *
	 */
	public function ajaxShowComboLangAction($idSiteSource)
	{
		$this->getRequest()->addResponseCommand('assign', array(
			'id' => 'comboLang',
			'attr' => 'innerHTML',
			'value' => $this->getHtmlComboLang($idSiteSource)
			)
		);
		$this->getRequest()->addResponseCommand('assign', array(
			'id' => 'arborescence',
			'attr' => 'innerHTML',
			'value' => ''
			)
		);
	}

	/**
	 *
	 */
	public function ajaxShowArborescenceAction($idLangSource, $idSiteSource)
	{
		$idSiteCible = $_SESSION[APP]['SITE_ID'];
		$this->getRequest()->addResponseCommand('assign', array(
			'id' => 'arborescence',
			'attr' => 'innerHTML',
			'value' => $this->getHtmlArboAndMappingLang($idLangSource, $idSiteSource, $idSiteCible)
			)
		);
	}

	/**
	 *
	 */
	public function getHtmlArboAndMappingLang($idLangSource, $idSiteSource, $idSiteCible)
	{
		$oConnection = Pelican_Db::getInstance();
		$oFormAjax = Pelican_Factory::getInstance('Form', false);
		$oFormAjax->sFormName = "fForm";

		//Gestion de l'arborescence source
		$return .= $oFormAjax->createFreeHtml("<table class='form'>");
		$return .= $oFormAjax->createFreeHtml("<tr><td>");
		$return .= $oFormAjax->createSubFormHmvc(
			"ARBO_SOURCE", t('ARBORESCENCE_SOURCE') . " *", array('class' => 'Administration_Directory_Controller', 'method' => 'pageArboSource'), array('SITE_ID' => $idSiteSource,
			'LANGUE_ID' => $idLangSource), $this->readO
		);
		$return .= $oFormAjax->createFreeHtml("</td></tr>");
		$sSqlListe = "
            select
            site_id as id,
            site_label as lib
            from #pref#_site
            where site_id = " . $_SESSION[APP]['SITE_ID'] . "
            ORDER BY id";
		$return .= $oFormAjax->createComboFromSql($oConnection, $multi . 'SITE_ID', t('PAYS_CIBLE'), $sSqlListe, $_SESSION[APP]['SITE_ID'], true, true, "1", false, "", true, false);
		// Gestion du mapping de langue
		$sSqlLanguesSource = "
            select
                l.langue_id as id,
                l.langue_label as lib
            from #pref#_site_language sl
            inner join #pref#_language l
            on (sl.langue_id = l.langue_id)
            where sl.site_id = " . $idSiteSource . "
            ORDER BY id";
		$sSqlLanguesCible = "
            select
                l.langue_id as id,
                l.langue_label as lib
            from #pref#_site_language sl
            inner join #pref#_language l
            on (sl.langue_id = l.langue_id)
            where sl.site_id = " . $idSiteCible . "
            ORDER BY id";
		$aLanguesCible = $oConnection->queryTab($sSqlLanguesCible);
		$return .= $oFormAjax->createFreeHtml("<tr><td class=\"formlib\">" . t('LANGUE_CIBLE') . "</td></tr>");
		foreach ($aLanguesCible as $idx => $langue) {
			$return .= $oFormAjax->createFreeHtml("<tr><td class='langues'>");
			$return .= $oFormAjax->createCheckBoxFromList("LANGUE_CIBLE_" . $idx, "", array($langue['id'] => $langue['lib']), $langue['id'], false, false, "h", true);
			$return .= $oFormAjax->createFreeHtml("</td><td>" . t('LANGUE_SOURCE') . " : ");
			$return .= $oFormAjax->createComboFromSql($oConnection, "LANGUE_SOURCE_" . $idx, "", $sSqlLanguesSource, null, false, $readO, "1", false, "", true, true);
			$return .= $oFormAjax->createFreeHtml("</td></tr>");
		}
		$return .= $oFormAjax->createFreeHtml("</table>");

		$return .= $oFormAjax->showSeparator();
		//Gestion de l'arborescence source
		$return .= $oFormAjax->createFreeHtml("<table class='form'>");
		$return .= $oFormAjax->createFreeHtml("<tr><td>");
		$return .= $oFormAjax->createSubFormHmvc(
			"ARBO_CIBLE", t('ARBORESCENCE_CIBLE') . " *", array('class' => 'Administration_Directory_Controller', 'method' => 'pageArboCible'), array('SITE_ID' => $idSiteCible), $this->readO
		);
		$return .= $oFormAjax->createFreeHtml("</td></tr>");
		$return .= $oFormAjax->createFreeHtml("</table>");
		$sJS .= "$('#NB_LANGUES').val(" . sizeof($aLanguesCible) . ");";
		$oConnection->close();
		$return .= Pelican_Html::script($sJS);
		return $return;
	}

	/**
	 *
	 */
	public function getHtmlComboLang($idSiteSource)
	{
		$oConnection = Pelican_Db::getInstance();
		$oFormAjax = Pelican_Factory::getInstance('Form', false);
		$oFormAjax->sFormName = "fForm";
		$sSqlLanguesSource = "
            select
                l.langue_id as id,
                l.langue_label as lib
            from #pref#_site_language sl
            inner join #pref#_language l
            on (sl.langue_id = l.langue_id)
            where sl.site_id = " . $idSiteSource . "
            ORDER BY id";
		$aTemp = $oConnection->queryTab($sSqlLanguesSource);
		$aLanguesSource = array();
		if ($aTemp) {
			foreach ($aTemp as $tmp) {
				$aLanguesSource[$tmp['id']] = $tmp['lib'];
			}
		}
		$return = $oFormAjax->createRadioFromList("LANGUE_SOURCE", t('LANGUE_SOURCE'), $aLanguesSource, null, true, $readO, "h", false, "onchange=\"callAjax('/Citroen_Administration_CopierColler/ajaxShowArborescence',this.value, $idSiteSource);\"");
		$oConnection->close();
		return $return;
	}

	/**
	 * Fonction de lecture des multis
	 */
	public static function myReadMultiLangue($table, $prefixe)
	{
		$aMulti = array();
		if (is_array($table) && !empty($table)) {
			foreach ($table as $key => $value) {
				$iPrefixeLenght = strlen($prefixe);
				if (substr($key, 0, $iPrefixeLenght) === $prefixe) {
					$aTemp = explode('_', $key);
					$aMulti[$aTemp[2]] = $value;
				}
			}
		}
		return $aMulti;
	}
	
	public function getPageParenteEtEnfants($idPage, $idSite, $idLang){
		$oConnection = Pelican_Db::getInstance();
		$sSQLParent = "SELECT PAGE_ID, PAGE_PARENT_ID FROM `#pref#_page` WHERE  SITE_ID = " .$idSite. " AND LANGUE_ID = " .$idLang. " AND`PAGE_PATH` like '%#" . $idPage . "'";
		$sSQLEnfant = "SELECT PAGE_ID, PAGE_PARENT_ID FROM `#pref#_page` WHERE  SITE_ID = " .$idSite. " AND LANGUE_ID = " .$idLang. " AND`PAGE_PATH` like '%#" . $idPage . "#%'";
		$resultParent	=	$oConnection->queryTab($sSQLParent);
		$resultEnfant	=	$oConnection->queryTab($sSQLEnfant);
		return array_merge ($resultParent, $resultEnfant);
	}

}