<?php
/**
* @package Pelican
* @subpackage Pelican_Security
*/
require 'php-captcha.inc.php';

$oAudioCaptcha = new AudioPhpCaptcha('/usr/bin/flite', '/tmp/');
$oAudioCaptcha->Create();
