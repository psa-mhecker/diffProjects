<?php
/**
 * @package Module Video Player FX for Joomla! 1.5
 * @version $Id: mod_videoplayerfx.php 10 December 2010 $
 * @author FlashXML.net
 * @copyright (C) 2010 FlashXML.net
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
**/

defined('_JEXEC') or die('Restricted access');

$videoplayerfx_path = $params->get('videoplayerfx_path');
if (strpos($videoplayerfx_path, '/') !== 0) {
	$videoplayerfx_path = '/'.$videoplayerfx_path;
	$params->def('videoplayerfx_path', $videoplayerfx_path);
}

$videoplayerfx_swf = 'player.swf';

$videoplayerfx_width = $videoplayerfx_height = 0;

if (function_exists('simplexml_load_file') && file_exists(JPATH_BASE.$videoplayerfx_path.'settings.xml')) {
	$xml = simplexml_load_file(JPATH_BASE.$videoplayerfx_path.'settings.xml');
	if ($xml) {
		$videoplayerfx_width = (int)$xml->Video_Size->appWidth->attributes()->value;
		$videoplayerfx_height = (int)$xml->Video_Size->appHeight->attributes()->value;
	}
}

if ($videoplayerfx_width == 0 || $videoplayerfx_height == 0) {
	if ((int)$params->get('videoplayerfx_width') > 0 && (int)$params->get('videoplayerfx_height') > 0) {
		$videoplayerfx_width = (int)$params->get('videoplayerfx_width');
		$videoplayerfx_height = (int)$params->get('videoplayerfx_height');
	} else {
		echo '<!--  invalid Video Player FX width / height -->';
	}
}

if ($videoplayerfx_width > 0 && $videoplayerfx_height > 0) {
	$joomla_install_dir_in_url = rtrim(JURI::root(true), '/');
	if (!empty($joomla_install_dir_in_url) && strpos($joomla_install_dir_in_url, '/') !== 0) {
		$joomla_install_dir_in_url = '/' . $joomla_install_dir_in_url;
	}

	global $mainframe;
	$mainframe->addCustomHeadTag('<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>');
	echo '<div id="videoplayerfx"></div><script type="text/javascript">'."swfobject.embedSWF('{$joomla_install_dir_in_url}{$videoplayerfx_path}{$videoplayerfx_swf}', 'videoplayerfx', '{$videoplayerfx_width}', '{$videoplayerfx_height}', '9.0.0.0', '', { folderPath: '{$joomla_install_dir_in_url}{$videoplayerfx_path}' }, { scale: 'noscale', salign: 'tl', wmode: 'transparent', allowScriptAccess: 'sameDomain', allowFullScreen: true }, {});</script>";
}