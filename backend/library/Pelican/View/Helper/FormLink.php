<?php
/**
 * View Helper link.
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

class Pelican_View_Helper_FormLink extends Zend_View_Helper_FormElement
{
    /**
     * Créer le xhtml d'un lien hypertext.
     *
     * @param string $strLabel
     * @param string $strHref
     * @param array  $aAttribs
     *
     * @return string
     */
    public function formLink($strLabel, $strHref, $aAttribs = null)
    {
        //Construction du xhtml
         $strXhtml = '<a href="'.$strHref.'" '.$this->_htmlAttribs($aAttribs).' >'.$strLabel.'</a>';

        return $strXhtml;
    }
}
