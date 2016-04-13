<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

 /**
 * Smarty {t} function plugin
 *
 * Type:     function<br>
 * Name:     translate<br>
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_translate($params, &$view)
{
	return t($params);
}