<?php
	/**
	* @package Pelican
	* @subpackage File
	*/
	 
	/** Fichier de configuration */
	include_once('config.php');

	require_once(Pelican::$config['LIB_ROOT'].Pelican::$config['LIB_FILE'].Pelican::$config['CLASS_FILE']);
	 
	if ($_GET["template"] == "" && $_POST["template"] != "") {
		$_GET["template"] = $_POST["template"];
	}
	if ($_GET["projet_id"] == "" && $_POST["projet_id"] != "") {
		$_GET["projet_id"] = $_POST["projet_id"];
	}
	 
	// Si la page d'admin est appelée sans en avoir les droits => on réinitialise le template appelé
	if ($_SESSION[APP]["session_admin"] != "1" && $_SESSION[APP]["session_gestion_client"] != "1") {
		if ($_GET["template"] == "admin") {
			$_GET["template"] = "";
		}
	}
	 
	$css = Pelican::$config["CSS_BACK_PATH"];
?>

<html>
<head>
	<title>Archive</title>
	<link rel="stylesheet" href="<?=$css?>" type="text/css" />
	<script type="text/javascript" src="<?=Pelican::$config["LIB_PATH"]?>/Pelican/Form/public/js/xt_toggle.js"></script>
</head>
<body>

<?php
	 
	require_once(Pelican::$config['LIB_ROOT'].Pelican::$config['LIB_FILE']."/_work/PclZip.php");
	$zipfile = Pelican::$config["MEDIA_ROOT"]."/".$_GET["projet_id"]."/doc".$_GET["file"];
	$zipfile = str_replace("//", "/", $zipfile);
	 
	$zip = new PclZip($zipfile);
	if (($list = $zip->listContent()) == 0) {
		die("Error : ".$zip->errorInfo(true));
	}
	$arr = array();
	for ($i = 0; $i < sizeof($list); $i++) {
		$dir = "./".substr($list[$i]["filename"], 0, strrpos($list[$i]["filename"], "/"));
		$list[$i]["directory"] = $dir;
		$arr[$dir][] = $list[$i];
	}
	 
	// Affichage du titre de la page
	 
	echo("<center>");
	echo("<br /><span class=\"title\">Contenu du fichier ".str_replace("/", "", $_GET["file"])."</span><br /><br />");
	echo ("<table width=\"75%\" border=\"1\" cellspacing=\"1\" cellpadding=\"2\" class=\"filtre\">");
	 
	$css = ($css == "tblalt2"?"tblalt1":"tblalt2");
	echo "<tr>";
	echo "<td class=\"filtre\" align=\"center\" colspan=\"3\" nowrap=\"nowrap\"><b>".t('POPUP_MEDIA_LABEL_NEW_FILE')."</b></td>";
	echo "<td class=\"filtre\" align=\"center\" nowrap=\"nowrap\"><b>".t('POPUP_MEDIA_LABEL_SIZE')."<br />Origine</b></td>";
	echo "<td class=\"filtre\" align=\"center\" nowrap=\"nowrap\"><b>".t('POPUP_MEDIA_LABEL_SIZE')."<br />Compr.</b></td>";
	//echo "<td class=\"filtre\" align=\"center\" nowrap=\"nowrap\"><b>Taux<br />Compr.</b></td>";
	echo "<td class=\"filtre\" align=\"center\" nowrap=\"nowrap\"><b>Modifié le</b></td>";
	echo "</tr>";
	 
	if (array_key_exists("./", $arr)) {
		echo "<tr>";
		$css = ($css == "tblalt2"?"tblalt1":"tblalt2");
		echo "<td class=\"".$css."\" colspan=\"5\"><b>./</b></td>";
		echo "</tr>";
	}
	 
	//for ($i=0; $i<sizeof($list); $i++) {
	foreach(array_keys($arr) as $key) {
		foreach(array_values($arr[$key]) as $value) {
			echo "<tr>\n";
			$css = ($css == "tblalt2"?"tblalt1":"tblalt2");
			if ($value["folder"] == 0) {
				echo "<td class=\"".$css."\" align=\"center\"><img alt=\"\" border=\"0\" src=\"".getAssociatedIcon($value["filename"])."\" width=\"16\" height=\"16\"></td>\n";
				echo "<td class=\"".$css."\" nowrap=\"nowrap\">".dirname($value["filename"])."/"."</td>\n";
				echo "<td class=\"".$css."\" nowrap=\"nowrap\">".basename($value["filename"])."</td>\n";
				echo "<td class=\"".$css."\" align=\"right\" nowrap=\"nowrap\">".formatSize($value["size"])."</td>\n";
				echo "<td class=\"".$css."\" align=\"right\" nowrap=\"nowrap\">".formatSize($value["compressed_size"])."</td>\n";
				//echo "<td class=\"".$css."\" align=\"right\" nowrap=\"nowrap\">".(intval((1-$value["compressed_size"]/$value["size"])*1000000/100)/100)." %</td>\n";
				$thetime = date(t('DATE_FORMAT_PHP')." H:i:s", $value["mtime"]);
				echo "<td class=\"".$css."\" align=\"right\" nowrap=\"nowrap\">".$thetime."</td>\n";
			} else {
				echo "<td class=\"tblFooter\" colspan=\"6\"><b>".$value["filename"]."</b></td>\n";
				$folder_name = $value["filename"];
				$css = $tblalt1;
			}
			echo "</tr>\n";
		}
	}
	 
	echo("</table>");
	echo("<br />");
	echo("<input name=\"Fermer\" type=\"button\" class=\"button\" value=\"Fermer cette fenêtre\" onclick=\"window.close();\"><br /><br />");
	echo("</center>");
?>

</body>
</html>