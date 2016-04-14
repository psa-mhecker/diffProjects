<?php
/**
 * @ignore
 */

/**
 * xlhtml 0.4.9.2 converts excel files (.xls) to Html.
 */
$windows = "xlhtml.exe";
$linux = "/usr/bin/xlhtml";

$extension = ".html";

$output = ">";

$options[] = "-bcffffff";
$options[] = "-fw ";
$options[] = "-a";
$options[] = "-xml";

/** pb d'encodage utf8 avec xlhtml, la sortie est en utf8 mais en partie */
function postClean($value)
{
    return(strtr(trim($value),
    'àáâãäåòóôõöøèéêëçìíîïùúûü«»',
    'aaaaaaooooooeeeeciiiiuuuu""'));
}
