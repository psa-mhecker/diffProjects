<?php
/**
 * Smarty plugin.
 */

/**
 * Smarty {t} function plugin.
 *
 * Type:     function<br>
 * Name:     t<br>
 *
 * @version  1.0
 *
 * @param array
 * @param Smarty
 *
 * @return string
 */
function smarty_function_t($params, &$view)
{
    return t($params);
}
