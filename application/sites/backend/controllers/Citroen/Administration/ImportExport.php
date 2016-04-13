<?php

require_once(Pelican::$config['APPLICATION_CONTROLLERS'] . "/Administration/Directory.php");
include_once(Pelican::$config['APPLICATION_LIBRARY'] . "/Citroen/Page/Page.php");
include_once(Pelican::$config['APPLICATION_LIBRARY'] . "/Citroen/Page/Content.php");

/**
 *
 */
class Citroen_Administration_ImportExport_Controller extends Pelican_Controller_Back
{

	protected $administration = true;
	protected $form_name = "importexport";

	/**
	 *
	 */
	public function editAction()
	{
		parent::editAction();
		$oForm = Pelican_Factory::getInstance('Form', true);
		$oForm->bDirectOutput = false;
		$oForm->setTab('1', t('EXPORT'));
		$oForm->setTab('2', t('IMPORT'));
		$form = $oForm->open(Pelican::$config['DB_PATH'], "post", "fForm", true);
		if (isset($_REQUEST['msg']) && !empty($_REQUEST['msg'])) {
			$form .= '<p class="alerte">' . $_REQUEST['msg'] . '</p><br/><br/>';
		}
		$form .= $this->beginForm($oForm);
		$form .= $oForm->createHidden('IMPORT_OU_EXPORT', '');
		$form .= $oForm->beginTab('1');
		if ($_GET['file']) {
			$form .= '<p class="alerte"><a href="/_/Index/downloadExport?file=' . $_GET['file'] . '">' . t('TELECHARGER_LE_FICHIER') . '</a></p><br/><br/>';
		}
		$form .= $oForm->createRadioFromList('TYPE_EXPORT', t('TYPE_EXPORT') . " *", array('1' => t('PAGE'), '2' => t('ARBORESCENCE')), null, "", false);
		$form .= $oForm->showSeparator();
		$sSql = "
            select
                l.langue_id as id,
                l.langue_label as lib
            from #pref#_site_language sl
            inner join #pref#_language l
            on (sl.langue_id = l.langue_id)
            where sl.site_id = " . $_SESSION[APP]['SITE_ID'] . "
            ORDER BY id";
		$oConnection = Pelican_Db::getInstance();
		$aTemp = $oConnection->queryTab($sSql);
		$aSiteLangues = array();
		if ($aTemp) {
			foreach ($aTemp as $langue) {
				$aSiteLangues[$langue['id']] = $langue['lib'];
			}
		}
		$form .= $oForm->createRadioFromList('LANGUE_EXPORT', t('LANGUE') . " *", $aSiteLangues, null, "", (sizeof($aSiteLangues > 1)) ? '' : false);
		$form .= $oForm->createSubFormHmvc(
			"ARBO_EXPORT", t('ARBORESCENCE') . " *", array('class' => 'Administration_Directory_Controller', 'method' => 'pageArbo'), array('SITE_ID' => $_SESSION[APP]['SITE_ID']), $this->readO, false, "subformjs", "formsub formsub2"
		);
		$form .= $oForm->createFreeHtml("<tr><td></td><td>") . $oForm->createButton("exporter", t('EXPORTER'), "$('#fForm').submit();") . $oForm->createFreeHtml("</td></tr>");
		$form .= $oForm->beginTab('2');
		$form .= $oForm->createInput('FICHIER_IMPORT', t('FICHIER_IMPORT') . " *", "255", "", false, "", false, "75", false, "", "file");
		$form .= $oForm->createRadioFromList('LANGUE_IMPORT', t('LANGUE') . " *", $aSiteLangues, null, "", (sizeof($aSiteLangues > 1)) ? '' : false);
		/* $form .= $oForm->createSubFormHmvc(
		  "ARBO_IMPORT",
		  t('ARBORESCENCE')." *",
		  array('class' => 'Administration_Directory_Controller', 'method' => 'pageArbo'),
		  array('SITE_ID' => $_SESSION[APP]['SITE_ID']),
		  $this->readO
		  ); */
		$form .= $oForm->createFreeHtml("<tr><td></td><td>") . $oForm->createButton("importer", t('IMPORTER'), "$('#fForm').submit();") . $oForm->createFreeHtml("</td></tr>");
		$oForm->createJS('
            if ($("#fForm_tab_1:visible").length) {
                if(!$("input:radio[name=\'TYPE_EXPORT\']:checked").val()) {
                    alert(\'' . t('TYPE_EXPORT_OBLIGATOIRE', 'js2') . '\');
                    return false;
                }
                if(!$("input:radio[name=\'LANGUE_EXPORT\']:checked").val()) {
                    alert(\'' . t('LANGUE_OBLIGATOIRE', 'js2') . '\');
                    return false;
                }
                if(($("input:radio[name=\'TYPE_EXPORT\']:checked").val() == 1) && ($("#ARBO_EXPORT input[name=\'ARBO_ID[]\']:checked").length != 1)) {
                    alert(\'' . t('ARBORESCENCE_UNIQUE', 'js2') . '\');
                    return false;
                }
                if(($("input:radio[name=\'TYPE_EXPORT\']:checked").val() == 2) && ($("#ARBO_EXPORT input[name=\'ARBO_ID[]\']:checked").length == 0)) {
                    alert(\'' . t('ARBORESCENCE_OBLIGATOIRE', 'js2') . '\');
                    return false;
                }
                $("input[name=\'IMPORT_OU_EXPORT\']").val(1);
                //$("#ARBO_IMPORT input[name=\'ARBO_ID[]\']").attr(\'checked\', false);
            } else if ($("#fForm_tab_2:visible").length) {
                if($("input[name=\'FICHIER_IMPORT\']").val() == "") {
                    alert(\'' . t('FICHIER_IMPORT_OBLIGATOIRE', 'js2') . '\');
                    return false;
                }
                if(!$("input:radio[name=\'LANGUE_IMPORT\']:checked").val()) {
                    alert(\'' . t('LANGUE_OBLIGATOIRE', 'js2') . '\');
                    return false;
                }
                /*if($("#ARBO_IMPORT input[name=\'ARBO_ID[]\']:checked").length != 1) {
                    alert(\'' . t('ARBORESCENCE_OBLIGATOIRE', 'js2') . '\');
                    return false;
                }*/
                $("input[name=\'IMPORT_OU_EXPORT\']").val(2);
                $("#ARBO_EXPORT input[name=\'ARBO_ID[]\']").attr(\'checked\', false);
            } else {
                return false;
            }
        ');
		$form .= $this->endForm($oForm, array(), "", true, true, true);
		$form .= $oForm->close();
		$this->setResponse($form);
	}

	/**
	 *
	 */
	public function saveAction()
	{
		// Export
		if (Pelican_Db::$values['IMPORT_OU_EXPORT'] == "1") {
			$oPageExport = new Citroen_Page_Page();
			$siteIdSource = $_SESSION[APP]['SITE_ID'];
			$langueIdSource = Pelican_Db::$values['LANGUE_EXPORT'];
			$aData = array();
			$aData['page'] = array();
			$aData['content'] = array();
			$j = 0;
			foreach (Pelican_Db::$values['ARBO_ID'] as $idx => $pageIdSource) {
				$aTemp = $oPageExport->copie($pageIdSource, $langueIdSource, $siteIdSource);
				$aDataPage = $oPageExport->copieDependanceTable(0, 0, 0, 1, '', $pageIdSource)->getDatasTables();
				$aData['page'][$idx] = $aDataPage;
				$aData['page'][$idx]['#pref#_page'] = $aTemp;
				$oContent = new Citroen_Page_Content(NULL);
				$aDataContentCopie = $oContent->copie($pageIdSource, $langueIdSource);
				if ($aDataContentCopie) {
					foreach ($aDataContentCopie as $i => $dataContentCopie) {
						$oContent->aBind[':CONTENT_ID'] = $dataContentCopie['CONTENT_ID'];
						$oContent->aBind[':CONTENT_CURRENT_VERSION'] = $dataContentCopie['CONTENT_CURRENT_VERSION'];
						$oContent->aBind[':CONTENT_DRAFT_VERSION'] = $dataContentCopie['CONTENT_DRAFT_VERSION'];
						$aData['content'][$j] = $oContent->copieDependanceTable()->getDatasTables();
						$aData['content'][$j]['#pref#_content'] = $dataContentCopie;
						$j++;
					}
				}
			}
			$sXML = '<?xml version="1.0"?><data>';
			$sXML .= $this->createArrayToXML('page', $aData['page']);
			$sXML .= $this->createArrayToXML('content', $aData['content']);
			$sXML .= '</data>';
			$filename = time() . rand(0, 100);
			$file = fopen(Pelican::$config['VAR_ROOT'] . "/export/" . $filename . ".xml", 'a+');
			fwrite($file, $sXML);
			fclose($file);
			Pelican_Db::$values["form_retour"] = "/_/Index/child?tid=" . Pelican::$config['TPL_IMPORTEXPORT'] . "&id=1&file=" . $filename;
		}
		// Import
		elseif (Pelican_Db::$values['IMPORT_OU_EXPORT'] == "2") {
			$siteIdCible = Pelican_Db::$values['SITE_ID'];
			$langueIdCible = Pelican_Db::$values['LANGUE_IMPORT'];
			$sXML = file_get_contents($_FILES['FICHIER_IMPORT']['tmp_name']);
			// Controle sur le type du fichier
			$finfo = new finfo(FILEINFO_MIME_TYPE);
			if ($finfo->buffer($sXML) == "application/xml") {
				$aData = $this->createXMLToArray($sXML);
				$nbPages = sizeof($aData['page']);
				if ($nbPages > 0) {
					if ($aData['page'][0]['#pref#_page'][0]['SITE_ID'] == $siteIdCible) {
						foreach ($aData['page'] as $aPage) {
							$aDataPageCopie = $aPage['#pref#_page'][0];
							$iPageSource = $aDataPageCopie['PAGE_ID'];
							unset($aPage['id']);
							unset($aPage['#pref#_page']);
							$oPageImport = new Citroen_Page_Page();
							$oPageImport->setDatasTables($aPage);
							$oPageImport->colle($aDataPageCopie, $langueIdCible, $siteIdCible, null, true);
							$oPageImport->update();
						}
						$nbContents = sizeof($aData['content']);
						$aContentByPid = array();
						if ($nbContents > 0) {
							foreach ($aData['content'] as $aContent) {
								if ($aContent['#pref#_content_version']) {
									unset($temp);
									foreach ($aContent['#pref#_content_version'] as $i => $version) {
										$iPageSource = $aContent['#pref#_content_version'][$i]['PAGE_ID'];
										if ($iPageSource != $tmp) {
											$aContentByPid[$iPageSource][] = $aContent;
											$tmp = $iPageSource;
										}
									}
								}
							}
						}
						if ($aContentByPid) {
							foreach ($aContentByPid as $pid => $contents) {
								$oContentImport = new Citroen_Page_Content($pid);
								$aDataContentCopie = array();
								foreach ($contents as $i => $content) {
									$langueId = $langueIdCible;
									unset($aDataContent);
									$iContentIdSource = $content['#pref#_content'][0]['CONTENT_ID'];
									$aDataContentCopie[$i] = $content['#pref#_content'][0];
									unset($content['id']);
									unset($content['#pref#_content']);
									$aDataContent[$iContentIdSource] = $content;
									$oContentImport->setDatasTables($aDataContent);
									$oContentImport->colle($aDataContentCopie, $langueId, $siteIdCible, '', true, $iContentIdSource);
								}
							}
						}
						$msg = t('SUCCES_IMPORT');
					} else {
						$msg = t('ERREUR_SITE_NON_CONFORME');
					}
				} else {
					$msg = t('ERREUR_FICHIER_VIDE');
				}
			} else {
				$msg = t('ERREUR_TYPE_FICHIER');
			}
			Pelican_Db::$values["form_retour"] = "/_/Index/child?tid=" . Pelican::$config['TPL_IMPORTEXPORT'] . "&id=1&msg=" . $msg;
		}
	}

	/**
	 * Création d'une chaine XML à partir d'un array
	 * @param string $root libelé de l'élément racine
	 * @param array $data données
	 * @return string
	 */
	function createArrayToXML($root, $data)
	{
		$sXML = '';
		foreach ($data as $key => $value) {
			$key = str_replace('#pref#_', 'psa_', $key);
			if (is_array($value)) {
				if (is_numeric($key)) {
					$sXML .= '<' . $root . ' id="' . $key . '">' . $this->createArrayToXML('', $value) . '</' . $root . '>';
				} else {
					if (is_array($value) && isset($value['0'])) {
						$sXML .= $this->createArrayToXML($key, $value);
					} else {
						$sXML .= '<' . $key . '>' . $this->createArrayToXML($key, $value) . '</' . $key . '>';
					}
				}
			} else {
				if (is_numeric($value) || $value == '') {
					$sXML .= '<' . $key . '>' . $value . '</' . $key . '>';
				} else {
					$sXML .= '<' . $key . '><![CDATA[' . $value . ']]></' . $key . '>';
				}
			}
		}
		return $sXML;
	}

	/**
	 * Création d'un array à partir d'une chaine XML
	 * @param string $sXML
	 * @return array
	 */
	function createXMLToArray($sXML)
	{
		$aData = array();
		$oXML = simplexml_load_string($sXML);
		$aData = \Itkg\Helper\DataTransformer::simplexml2array($oXML);
		$aData = remapArray($aData);
		cleanArray($aData);
		$types = array('page', 'content');
		foreach ($types as $type) {
			if ($aData["$type"]) {
				if (!$aData["$type"][0]) {
					$tmp = $aData["$type"];
					unset($aData["$type"]);
					$aData["$type"][0] = $tmp;
				}
				foreach ($aData["$type"] as $key1 => $value1) {
					foreach ($value1 as $key2 => $value2) {
						if (is_array($value2) && !$value2[0]) {
							$tmp = $value2;
							unset($aData["$type"][$key1][$key2]);
							$aData["$type"][$key1][$key2][0] = $tmp;
						}
					}
				}
			}
		}
		return $aData;
	}

}

/**
 * Suppression des toutes les entrés @attributes dans un array
 * @param array &$a
 */
function cleanArray(&$a)
{
	if (is_array($a)) {
		unset($a['@attributes']);
		array_walk($a, __FUNCTION__);
	}
}

/**
 * Remplacement de toutes les noms de tables dans un array
 * @param array $input
 * @return array
 */
function remapArray(array $input)
{
	$return = array();
	foreach ($input as $key => $value) {
		if (strpos($key, 'psa_') === 0)
			$key = str_replace('psa_', '#pref#_', $key);

		if (is_array($value))
			$value = remapArray($value);

		$return[$key] = $value;
	}
	return $return;
}