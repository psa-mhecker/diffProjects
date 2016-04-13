<?php

pelican_import("Media");

/**
 * pelican plugin
 *
 * This plugin is only for Smarty2 BC
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {media} function plugin
 *
 * Type:     function<br>
 * Name:     format<br>
 * Purpose:  format a media for pelican resize<br>
 * @author   Benoit vincent <benoit.vincent@businessdecision.com>
 * @param string $sString media path
 * @param int $iFormat format
 * @return string|null
 */
function smarty_modifier_format($sString, $iFormat)
{

    if (is_string($sString) && intval($iFormat)) {
        $sFormatPath = Pelican_Media::getFileNameMediaFormat($sString, intval($iFormat));
    }

    return $sFormatPath;
}
