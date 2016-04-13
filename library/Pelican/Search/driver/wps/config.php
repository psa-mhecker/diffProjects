<?php
$windows = "";
$linux = "";
$call = "";

$extension = ".txt";

$output = "";

function parseFile_wps($file, $withPorperties = false)
{
    $return = file_get_contents($file);
    $from = array("\\","hich\\","f1\\","themelang","fonttbl","fbidi","fmodern","irowband","irow","trpaddf","tblind","lsdpriority","adeflang");

    $return = str_replace($from, " ", $return);

    return $return;
}
