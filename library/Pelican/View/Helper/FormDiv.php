<?php
/**
 * View Helper Div
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 13/04/2010
 * @package Pelican + Zend
 */

/**
 * Abstract class for extension
 */
require_once 'Zend/View/Helper/FormElement.php';

Class Pelican_View_Helper_FormDiv extends Zend_View_Helper_FormElement {
    /**
     * Créer le xhtml d'une Div
     *
     * @param  array  $aAttribs
     * @param  string $Content
     * @return string
     */
    public function formDiv($aAttribs, $Content = null)
    {
        $strXhtml = "<div". $this->_htmlAttribs($aAttribs) . '>';
        if ($Content != null) {
            $strXhtml .= $Content;
        }
        $strXhtml .= "</div>";

        return $strXhtml;
    }
}
