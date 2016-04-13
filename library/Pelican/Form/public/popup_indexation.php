<?php
	/** Popup d'ajout à distance d'entrées en base de données (tables de référence)
	*
	* @version 3.0
	* @author Jean-Baptiste Ruscassie <jbruscassie@businessdecision.com>
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 15/01/2002
	* @package Pelican
	* @subpackage Pelican_Form
	*/
	/** Fichier de configuration */
	include_once('config.php');

	if (!valueExists($_GET,"go")) {
		header("location: ".$_SERVER["SCRIPT_NAME"]."?go=1&Field=".$_GET["Field"]."&Table=".$_GET["Table"]."&Find=".rawurlencode($_GET["Find"])."&Search=".$_GET["Search"]."&combo=".@$_GET["combo"]."&choose=".@$_GET["choose"]);
	}
?>
<html>
<?php
pelican_import('Index');
Pelican::$frontController = new Pelican_Index ( false );
Pelican::$frontController->setTitle ( t('POPUP_SEARCH_TITLE') );
Pelican::$frontController->setBackofficeSkin ( Pelican::$config ["SKIN"], "/library/Pelican/Index/Backoffice/public/skins", Pelican::$config ["DOCUMENT_INIT"], "screen,print" );
Pelican::$frontController->setCss ( Pelican::$frontController->skinPath . "/css/popup.css.php" );
echo Pelican::$frontController->getHeader ();
?>
<body id="body_popup">
<div align="center">
<br /><br /><br />
<?php
	if (!valueExists($_GET,"go"))
		print ("<h2 id=\"message\">".t('POPUP_SEARCH_WAIT')."</h2>");

	$strMessage = "";

	if (valueExists($_GET,"go")) {
		if ($_GET["Find"] != "" ) {
			$oConnection = Pelican_Db::getInstance();
			if ($HTTP_SESSION_VARS["AssocFromSql_Search"][base64_decode($_GET["Search"])] ) {
				// Requête plus complexe, dans une variable de session
				$sqlSearch = str_replace(":RECHERCHE:", str_replace("'", "''", $_GET["Find"]), $HTTP_SESSION_VARS["AssocFromSql_Search"][base64_decode($_GET["Search"])]);
			} else {
				$sqlSearch = str_replace(":RECHERCHE:", str_replace("'", "''", $_GET["Find"]), base64_decode($_GET["Search"]));
			}
			// $sqlSearch = str_replace(":RECHERCHE:", str_replace("'", "''", $_GET["Find"]), base64_decode($_GET["Search"]));
			$oConnection->Query($sqlSearch);
			if ($oConnection->rows > 100 ) {
				$strMessage = t('POPUP_SEARCH_LIMIT');
			} elseif ($oConnection->rows != 0 ) {
				print "<script type=\"text/javascript\">\n";
				print "<!--\n";
				print " var obj = window.opener.".$_GET["Field"].".options;\n";
				print " obj.length = 0;\n";
				//    while ($ligne = each($oConnection->data["id"]) )
				//    print " window.opener.addValue(\"".$_GET["Field"]."\", \"".str_replace("\"", "&quot;", $oConnection->data["lib"][$ligne["key"]])."\", ".$ligne["value"].");\n";
				if ($_GET["choose"] ) {
					$sLibChoose = t('FORM_SELECT_CHOOSE');
					if (version_compare(phpversion(), "4.3.0", "<=") ) {
						$aTable = get_html_translation_table();
						while (list($key, $val) = each($aTable) ) {
							$sLibChoose = str_replace($val, $key, $sLibChoose);
						}
					} else {
						$sLibChoose = html_entity_decode($sLibChoose);
					}
					print " window.opener.addValue(\"".$_GET["Field"]."\", \"".str_replace("&gt;", ">", str_replace("\"", "&quot;", $sLibChoose))."\", '');\n";
				}
				if ($oConnection->data["id"]) {
					while ($ligne = each($oConnection->data["id"]) ) {
						print " window.opener.addValue(\"".$_GET["Field"]."\", unescape('".rawurlencode($oConnection->data["lib"][$ligne["key"]])."'), unescape('".rawurlencode($ligne["value"])."'));\n";
					}
				}
				if ($_GET["combo"] ) {
					print(" obj.selectedIndex = 0;\n");
				}
				print " self.close();";
				print "//-->\n";
				print "</script>\n";
			}
			else
				$strMessage = t('POPUP_SEARCH_NOFOUND');
		}
		else
			$strMessage = t('POPUP_SEARCH_EMPTY');
	}
?>
<? if ($strMessage) {

	print("<h2 id=\"message\">".$strMessage."</h2>");
	print ("<br /><br /><div align=\"center\"><button onClick=\"self.close();\">".t('POPUP_BUTTON_CLOSE')."</button></div><br /><br />\n");
}
?>
</div>
</body>
</html>