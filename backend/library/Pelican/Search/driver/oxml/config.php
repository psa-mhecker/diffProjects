<?php
$windows = "";
$linux = "";
$call = "";

$extension = ".docx";

$output = "";

require_once dirname(__FILE__).'/openxml.php';
function parseFile_oxml($file, $withPorperties = false)
{
    try {
        $return = OpenXMLDocumentFactory::openDocument($file);

        return $return;
    } catch (OpenXMLFatalException $e) {
        echo $e->getMessage();
    }
}
