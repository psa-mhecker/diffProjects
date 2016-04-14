<?php
/**
 * View Helper Span.
 *
 * @version 1.0
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 *
 * @since 21/04/2010
 */

/**
 * Abstract class for extension.
 */
require_once 'Zend/View/Helper/FormElement.php';

class Pelican_View_Helper_FormSpan extends Zend_View_Helper_FormElement
{
    /**
     * Créer le xhtml d'un span.
     *
     * @param array  $aAttribs
     * @param string $Content
     *
     * @return string
     */
    public function formSpan($aAttribs, $Content = null)
    {
        $strXhtml = "<span".$this->_htmlAttribs($aAttribs).'>';
        if ($Content != null) {
            $strXhtml .= $Content;
        }
        $strXhtml .= "</span>";

        return $strXhtml;
    }
}
