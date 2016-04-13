<?php
/**
 * View Helper Scipt
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 21/04/2010
 * @package Pelican + Zend
 */

/**
 * Abstract class for extension
 */
require_once 'Zend/View/Helper/FormElement.php';

Class Pelican_View_Helper_FormScript extends Zend_View_Helper_FormElement {
    /**
     * Créer le xhtml d'un script
     *
     * @param  array  $aAttribs
     * @param  string $Content
     * @return string
     */
    public function formScript($aAttribs, $Content = null)
    {
        $strXhtml = "<script". $this->_htmlAttribs($aAttribs) . '>';
        if ($Content != null) {
            $strXhtml .= $Content;
        }
        $strXhtml .= "</script>";

        return $strXhtml;
    }
}
