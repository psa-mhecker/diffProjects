<?php
/**
* @package Pelican
* @subpackage Pelican_Security
*/
require 'php-captcha.inc.php';

$aFonts = array('fonts/industria.ttf', 'fonts/arialbi.ttf', 'fonts/ariblk.ttf');

$oVisualCaptcha = new PhpCaptcha($aFonts, 200, 60);
$oVisualCaptcha->UseColour(true);
//$oVisualCaptcha->SetCharSet(array('\'','a','c','d','e','e','i','l','m','n','o','r','s','t','u','è','M','E'));
//$oVisualCaptcha->SetBackgroundImages('images/2.jpg');
//$oVisualCaptcha->DisplayShadow(true);
$oVisualCaptcha->Create();
