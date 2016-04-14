<?php
/** Class Validateur de DateTime
 * @version 1.0
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 *
 * @since 20/05/2010
 */
require_once 'Zend/Validate/Abstract.php';

class Pelican_Validate_DateTime extends Zend_Validate_Abstract
{
    const MSG_INCOMPLETE = "msgIncomplete";

    protected $_messageTemplates = array(
        self::MSG_INCOMPLETE => "Date incomplete",
    );

    /**
     * Verifie la validité de l'element.
     *
     * @param string $Value
     *
     * @return booleen
     */
    public function isValid($Value)
    {
        $this->_setValue($Value);

        if (empty($Value)) {
            $this->_error(self::MSG_INCOMPLETE);

            return false;
        }
        //Separation date et heure
        $aRes = explode(' ', $Value);
        if (strlen($aRes[0]) == 0) {
            $this->_error(self::MSG_INCOMPLETE);

            return false;
        }
        if (!preg_match("#([0-9]{2,2})(/)([0-9]{2,2})(/)([0-9]{4,4})#", $aRes[0])) {
            $this->_error(self::MSG_INCOMPLETE);

            return false;
        }

        return true;
    }
}
