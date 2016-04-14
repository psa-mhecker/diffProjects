<?php
use Symfony\Component\EventDispatcher\EventDispatcher;
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Observer/FormEventSubscriber.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Observer/FormEvent.php');

class Boforms_Dispatcher {

	public static function setBoFormsDispatcher() {
		
	
		if (Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE) {
			{
				
				$dispatcher = new EventDispatcher();
				$dispatcher->addSubscriber(new Boforms_Observer_FormEventSubscriber());
			
				$oEvent = new Boforms_Observer_FormEvent(Pelican_Db::$values);
				 
				$dispatcher->dispatch(Boforms_Observer_FormEvent::TRACK, $oEvent);
			}
		}	
	}
}