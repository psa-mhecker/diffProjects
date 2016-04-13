<?php
/**
 * View Helper Iframe
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 07/05/2010
 * @package Pelican + Zend
 */

/**
 * Abstract class for extension
 */
require_once 'Zend/View/Helper/FormElement.php';

Class Pelican_View_Helper_FormIframe extends Zend_View_Helper_FormElement {
    /**
     * Créer le xhtml d'une FormIframe
     *
     * @param  array  $aAttribs
     * @param  string $Content
     * @return string
     */
    public function formIframe($aAttribs, $Content = null)
    {
        $strXhtml = "<iframe". $this->_htmlAttribs($aAttribs) . '>';
        if ($Content != null) {
            $strXhtml .= $Content;
        }
        $strXhtml .= "</iframe>";

        return $strXhtml;
    }
}
