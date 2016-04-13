<?php
/** Class Validateur de Map
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 04/06/2010
 * @package Pelican + Zend
 */

require_once 'Zend/Validate/Abstract.php';

class Pelican_Validate_Map extends Zend_Validate_Abstract
{
    /**
     * Nom de l'element
     *
     * @var string
     */
    protected $_strName = '';

    const MSG_LATITUDE = "latitudeMissing";
    const MSG_LONGITUDE = "longitudeMissing";

    protected $_messageTemplates = array(
        self::MSG_LATITUDE => 'Veuillez saisir une valeur pour \"Latitude\" avec une valeur numérique.',
        self::MSG_LONGITUDE => 'Veuillez saisir une valeur pour \"Longitude\" avec une valeur numérique.'
    );


    /**
     * constructeur de la class
     *
     * @param string $strName
     */
    public function __construct($strName)
    {
        $this->_strName = $strName;
    }

    /**
     * Verifie la validité de l'element
     *
     * @param  string  $Value
     * @return booleen
     */
    public function isValid($Value, $Context = null)
    {
        $this->_setValue($Value);

        if ($Context[$this->_strName.'_LONGITUDE'] == '') {
            $this->_error(self::MSG_LONGITUDE);

            return false;
        }
        if ($Context[$this->_strName.'_LATITUDE'] == '') {
            $this->_error(self::MSG_LATITUDE);

            return false;
        }
        if (!is_float((float) $Context[$this->_strName.'_LATITUDE'])) {
            $this->_error(self::MSG_LATITUDE);

            return false;
        }
        if (!is_float((float) $Context[$this->_strName.'_LONGITUDE'])) {
            $this->_error(self::MSG_LONGITUDE);

            return false;
        }

        return true;
    }
}
