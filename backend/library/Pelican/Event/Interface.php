<?php
/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */
interface Pelican_Event_Interface
{
    public function getSource();
    public function getMessage();
    public function getId();
    public function consume();
    public function isConsumed();
}
