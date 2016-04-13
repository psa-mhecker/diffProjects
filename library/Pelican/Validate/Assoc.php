<?php
/** Class Validateur de Assoc
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 25/05/2010
 * @package Pelican + Zend
 */

require_once 'Zend/Validate/Abstract.php';

class Pelican_Validate_Assoc extends Zend_Validate_Abstract
{
    const MSG_INCOMPLETE = "msgIncomplete";

    protected $_messageTemplates = array(
        self::MSG_INCOMPLETE => "Chosse a value"
    );

    /**
     * Verifie la validité de l'element
     *
     * @param  string  $Value
     * @return booleen
     */
    public function isValid($Value)
    {
        $this->_setValue($Value);
        if (count($Value) == 0) {
            $this->_error(self::MSG_INCOMPLETE);

            return false;
        }

        return true;
    }
}
