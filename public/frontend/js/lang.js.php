<?php 

include('config.php');

$filemtime = filemtime(__FILE__);
$etag = $filemtime . '.' . filesize($file); 
//$offset = 60 * 60 * 24 * 1; // 1 jour
$offset = 60 * 15; // 15 minutes
$lastmodified = gmdate("D, d M Y H:i:s", $filemtime) . " GMT";
$ifmodifiedsince = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? trim($_SERVER['HTTP_IF_MODIFIED_SINCE'], ':') : false;
$ifnonematch = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim(trim($_SERVER['HTTP_IF_NONE_MATCH'], ':')) : false;

header("Last-Modified: " . gmdate("D, d M Y H:i:s", $filemtime) . " GMT");
header("Cache-Control: max-age=604800, public"); // optimisation PSA
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT");
header('Etag: ' . $etag);
header('Pragma: ');

header('Content-Type: text/javascript');

/*if (! $ifmodifiedsince && ! $ifnonematch)
    return;

if ($ifmodifiedsince == $lastmodified && $ifnonematch == $etag) {
    header('Content-Type: application/javascript', true, 304);
    exit();
}*/

//include('config.php');

$js = array(
'GEOLOCALISATION_IMPOSSIBLE_VEUILLEZ_VERIFIER_QUE_VOTRE_NAVIGATEUR_ACCEPTE_CETTE_FONCTIONNALITE_ET_SI_ELLE_EST_ACTIVEE'
);

?>
var aLabel = new Object();
<?php
foreach ($js as $key) {
    echo ("aLabel[\"" . $key . "\"]='" . str_replace("'", "\\'", t($key)) . "';\n");
}
?>

function t(label) {
    return aLabel[label];
}
