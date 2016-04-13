<?php
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Exception
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Exception
 * @author __AUTHOR__
 */
class Pelican_Exception extends Exception {
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $message __DESC__
     * @param __TYPE__ $variables (option) __DESC__
     * @param string $code (option) __DESC__
     * @return __TYPE__
     */
    public function __construct($message, array $variables = NULL, $code = 0) {
        if (is_array($variables)) {
            $message = strtr($message, $variables);
        }
        parent::__construct($message, $code);
        // bug dans php
        $this->code = $code;
    }
}
