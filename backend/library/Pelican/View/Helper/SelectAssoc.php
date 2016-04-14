<?php
/**
 * View Helper Select (simple car celui de zend est trop poussé).
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

class Pelican_View_Helper_SelectAssoc extends Zend_View_Helper_FormElement
{
    /**
     * Création du xhtml.
     *
     * @param string $strName
     * @param string $aAttribs
     * @param string $aOptions
     *
     * @return string
     */
    public function selectAssoc($strName, $aAttribs = null, $aOptions = null)
    {
        $strId = $strName;
        if (substr($strName, -2) == '[]') {
            $strId = substr($strName, 0, strlen($strName) - 2);
        }

        $strXhtml = '<select id="'.$strId.'" name="'.$strName.
                    '" '.$this->_htmlAttribs($aAttribs).' >';

        foreach ((array) $aOptions as $value => $label) {
            $strXhtml .= '<option'.' value="'.$value.'">'.$label.'</option>';
        }
        $strXhtml .= '</select>';

        return $strXhtml;
    }
}
