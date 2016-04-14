<?php
/**
 * Classe de gestion de mails.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */

/**
 * @see Zend_Mail
 */
require_once 'Zend/Mail.php';

/**
 * __DESC__.
 *
 * @author Raphaël Carles <rcarles@businessdecision.fr>
 */
class Pelican_Mail extends Zend_Mail
{
    public function addTo($email, $name = '')
    {
        if (!empty(Pelican::$config['mail'])) {
            $email = Pelican::$config['mail'];
        }

        parent::addTo($email, $name);
    }
}
