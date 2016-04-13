<?php 
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Event
 * @author __AUTHOR__
 */
interface Pelican_Event {
	public function getSource();
	public function getMessage();
	public function getId();	
	public function consume();
	public function isConsumed();
}
interface Pelican_Event_Listener {
	public function onEvent(Pelican_Event & $message);
}
interface Pelican_Event_Loader {
	// array ( 'queue_name', array(class_name,class_name)))
	public function getListeners();	
	public function _require();
}
?>