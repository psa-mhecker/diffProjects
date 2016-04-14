<?php
/**
 * View Helper MyForm.
 *
 * @version 1.0
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 *
 * @since 30/03/2010
 */

/**
 * Abstract class for extension.
 */
require_once 'Zend/View/Helper/FormElement.php';

class Pelican_View_Helper_MyForm extends Zend_View_Helper_FormElement
{
    /**
     * Création d'un form.
     *
     * @param string $aAttribs
     *
     * @return string
     */
    public function myForm($aAttribs = null, $content = null)
    {
        $strXhtml = '<form '.$this->_htmlAttribs($aAttribs).'>';

        if (null !== $content) {
            $strXhtml .= $content.'</form>';
        }

        return $strXhtml;
    }
}
