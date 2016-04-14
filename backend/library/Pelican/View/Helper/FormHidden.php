<?php
/**
 * View Helper FormHidden.
 *
 * @version 1.0
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 *
 * @since 10/05/2010
 */

/**
 * Abstract class for extension.
 */
require_once 'Zend/View/Helper/FormElement.php';

class Pelican_View_Helper_FormHidden extends Zend_View_Helper_FormElement
{
    /**
     * Crée un hidden.
     *
     * @param string $strName
     * @param string $strValue
     * @param array  $aAttribs
     *
     * @return string
     */
    public function formHidden($strName, $strValue = null, array $aAttribs = null)
    {
        $sXhtmlValue =  "";
        if ($strValue != null) {
            $sXhtmlValue = "value='".$strValue."'";
        }
        $strXhtml = "<input type='hidden' id='".$strName."' ".$sXhtmlValue."  ";
        $strXhtml .= $this->_htmlAttribs($aAttribs)."name='".$strName."'>";

        return $strXhtml;
    }
}
