<?php
	/**
	* @package Pelican
	* @subpackage External
	*/
	include_once('config.php');
	 
	include(pelican_path('Html.Tidy'));
	 
	$options['quiet'] = '1';
	$options['force-output'] = '1';
	$options['show-warnings'] = 'false';
	$options['drop-proprietary-attributes'] = '1';
	$options['show-body-only'] = '1';
	$options['word-2000'] = '1';
	$options['indent'] = '0';
	$options['indent-attributes'] = '0';
	$options['indent-spaces'] = '0';
	$options['wrap'] = '0';
	$options['wrap-attributes'] = '0';
	$options['drop-proprietary-attributes'] = '0';
	$options['output-xhtml'] = '1';
	$options['accessibility-check'] = '0';
	$options['bare'] = '1';
	$options['enclose-block-text'] = '0';
	$options['enclose-text'] = '0';
	$options['fix-backslash'] = '1';
	$options['logical-emphasis'] = '1';
	$options['hide-comments'] = '1';
	//$options['drop-font-tags'] = '1';
	 
	 
	$tidy = new Pelican_Html_Tidy($options);
	 
	$tidy->clean(cleanHtmlInput($_REQUEST["html"]));
	 
	if ($_REQUEST["Pelican_Text::unhtmlentities"]) {
		$return = Pelican_Text::unhtmlentities($tidy->html);
		$return = str_replace("&", "&amp;", $return);
		$return = str_replace("< ", "&lt; ", $return);
		$return = Pelican_Text::rawurlencode($return);
	} else {
		$return = Pelican_Text::rawurlencode($tidy->html);
	}
	echo($return);//."<errors>".$tidy->errors."</errors>";
?>