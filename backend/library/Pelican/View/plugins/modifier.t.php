<?php
/**
 * Smarty plugin.
 */

/**
 * Smarty {t} modifier plugin.
 *
 * Type:     modifier<br>
 * Name:     t<br>
 *
 * @version  1.0
 *
 * @param array
 * @param Smarty
 *
 * @return string
 */
function smarty_modifier_t($string, $alter = '', $aDynamisationParams = array())
{
    $return = t($string, $alter, $aDynamisationParams);
    /*switch ($alter) {
        case 'escape' : {
            $return = str_replace(array("'","\""),array("\\'","\\\""),$return);
            break;
        }
    }*/

    return $return;
}
