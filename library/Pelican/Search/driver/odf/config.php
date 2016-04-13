<?php
$windows = "";
$linux = "";
$call = "";

$extension = ".odf";

$output = "";

include(dirname(__FILE__)."/odfDoc.php");
function parseFile_odf($file, $withPorperties = false)
{
    $oODF = new odfDoc($file);
    $return = $oODF->content;

    return $return;
}
