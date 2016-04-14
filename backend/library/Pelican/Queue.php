<?php
/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */
require_once 'Zend/Auth.php';

/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */
class Pelican_Queue extends Zend_Queue
{
    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $sMessage __DESC__
     *
     * @return __TYPE__
     */
    public function send($sMessage)
    {
        return parent::send(base64_encode($sMessage));
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function receive()
    {
        $oMessage = parent::receive(1);
        if ($oMessage && $oMessage->current() && $oMessage->current()->body) {
            $oMessage->current()->body = base64_decode($oMessage->current()->body);
        }

        return $oMessage;
    }
}
