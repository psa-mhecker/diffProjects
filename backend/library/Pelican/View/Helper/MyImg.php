<?php
/**
 * View Helper Img.
 *
 * @version 1.0
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 *
 * @since 29/03/2010
 */

/**
 * Abstract class for extension.
 */
require_once 'Zend/View/Helper/FormElement.php';

class Pelican_View_Helper_MyImg extends Zend_View_Helper_FormElement
{
    /**
     * Crée le xhtml.
     *
     * @param string $strSrc
     * @param array  $aAttribs
     *
     * @return string
     */
    public function myImg($strSrc, $aAttribs = null)
    {
        $strXhtml = '<img src="'.$strSrc.'" '.$this->_htmlAttribs($aAttribs).' />';

        return $strXhtml;
    }
}
