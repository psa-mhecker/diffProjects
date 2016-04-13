<?php
	/**
	* @package Pelican
	* @subpackage External
	*/
	//http://aprompt.snow.utoronto.ca/Tidy/accessibilitychecks.html
	include_once('config.php');
	 
	include_once(pelican_path('Html.Tidy'));
	 
	$options['show-errors'] = '1';
	$options['accessibility-check'] = '1';
	 
	$tidy = new Pelican_Html_Tidy($options);
	$tidy->clean(cleanHtmlInput($input));
	 
	$tidy->showErrors();
?>