<?php
/**
 * View Helper File
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 03/06/2010
 * @package Pelican + Zend
 */

/**
 * Abstract class for extension
 */
require_once 'Zend/View/Helper/FormElement.php';

Class Pelican_View_Helper_FormFile extends Zend_View_Helper_FormElement {
    /**
     * Créer le xhtml d'un File
     *
     * @param  array  $aAttribs
     * @param  string $Content
     * @return string
     */
    public function formFile($strName, $strValue = '', $aAttribs = null)
    {
        if (!array_key_exists('id', $aAttribs)) {
            $aAttribs['id'] = $strName;
        }
        $strXhtml = '<input type="file" name="'.$strName.'" '. $this->_htmlAttribs($aAttribs) . " ";
        if ($strValue != '') {
            $strXhtml .= 'value="'.$strValue.'"';
        }
        $strXhtml .= ">";

        return $strXhtml;
    }
}
