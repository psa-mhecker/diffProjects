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
 * Name:     media<br>
 * Purpose:  handle math computations in template<br>
 * @link http://smarty.php.net/manual/en/language.function.math.php {math}
 *          (Smarty online manual)
 * @author   Benoit vincent <benoit.vincent@businessdecision.com>
 * @param array $params parameters
 * @param object $template template object
 * @return string|null
 */
function smarty_function_media($params, $template)
{
    if ($params['format'] != "" && $params['path'] != "") {
        $sFormatPath = Pelican_Media::getFileNameMediaFormat($params['path'], $params["format"]);
    }

    return $sFormatPath;
}
