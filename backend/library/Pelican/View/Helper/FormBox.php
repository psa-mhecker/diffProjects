<?php
/**
 * View Helper Box.
 *
 * @version 1.0
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 *
 * @since 06/05/2010
 */

/**
 * Abstract class for extension.
 */
require_once 'Zend/View/Helper/FormElement.php';

class Pelican_View_Helper_FormBox extends Zend_View_Helper_FormElement
{
    public function formBox($strName, $strType, $aValue = null, $aOptions = null, $aAttribs = null, $strSep = "<br />\n")
    {
        foreach ($aOptions as $OptionKey => $OptionValue) {
            $checked = '';
            if ((is_array($aValue) && in_array($OptionKey, $aValue)) ||
                ($OptionKey == $aValue)) {
                $checked = "checked='checked'";
            }

            $list[] = '<input type='.$strType.' '.$checked.
                        'value="'.$OptionKey.'"'.$this->_htmlAttribs($aAttribs).
                        'name='.$strName.' />&nbsp;'.$OptionValue;
        }
        $strXhtml = implode($strSep, $list);

        return $strXhtml;
    }
}
