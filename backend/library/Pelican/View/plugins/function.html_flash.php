<?php
/**
 * Smarty plugin.
 */

/**
 * Smarty {html_flash} function plugin.
 *
 * Type:     function<br>
 * Name:     html_flash<br>
 * Date:     29/10/09<br>
 * Examples: {html_flash file=$pelican_config.FLASH_FRONT_HTTP|cat:'/_work/testDJC.swf' width='660' height='200' wmode='transparent' content='<p>Image ou texte alternatif</p>'}
 *
 * @author   Erwann MEST <erwann.mest@businessdecision.com>
 *
 * @version  1.0
 *
 * @param array
 * @param Smarty
 *
 * @return string
 */
function smarty_function_html_flash($params, &$view)
{
    $object = '<object type="application/x-shockwave-flash" data="'.$params['file'].'" width="'.$params['width'].'" height="'.$params['height'].'">';
    $object .= '<param name="movie" value="'.$params['file'].'" />';
    if (!empty($params['wmode'])) {
        $object .= '<param name="wmode" value="'.$params['wmode'].'" />';
    }
    if (!empty($params['flashvars'])) {
        $object .= '<param name="flashVars" value="'.$params['flashvars'].'" />';
    }
    if (!empty($params['content'])) {
        $object .= $params['content'];
    }
    $object .= '</object>';

    return $object;
}
