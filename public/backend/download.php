<?php 
include("config.php");
$csv = $_POST['file'];
$filename = Pelican::$config["DOCUMENT_INIT"]."/var/i18n/backend/".$csv;
if ($filename != "" && file_exists ($filename)) { 
	header('Content-Type: application/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename='.$csv.'');
	header('Pragma: no-cache');
	readfile($filename);
	exit();
} else{
	echo t("ERROR_DOWNLOAD");
	exit();
}
?>