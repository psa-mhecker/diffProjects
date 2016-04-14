<?php
$windows = "";
$linux = "";
$call = "";

$extension = ".txt";

$output = "";

function parseFile_txt($file, $withPorperties = false)
{
    $return = file_get_contents($file);

    return $return;
}
