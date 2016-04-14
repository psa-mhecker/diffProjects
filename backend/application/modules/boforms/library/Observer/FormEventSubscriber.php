<?php

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Boforms_Observer_FormEventSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            Boforms_Observer_FormEvent::TRACK => array(
                'onActionForm', 10
            )
        );
    }

    public function onActionForm($event) {
    	
    	$oConnection = Pelican_Db::getInstance();
    	
    	Pelican_Db::$values["PAGE_ID"] = $event->getPageId();
    	Pelican_Db::$values["PAGE_VERSION"] = $event->getPageVersion();
    	Pelican_Db::$values["LANGUE_ID"] = $event->getLangueId();
    	Pelican_Db::$values["HISTORY_DATE"] = $event->getDateUpdate();
    	Pelican_Db::$values["STATE_ID"] = $event->getStateId();
    	Pelican_Db::$values["SITE_ID"] = $event->getSiteId();
    	Pelican_Db::$values["ZONE_ID"] = $event->getZoneId();
    	Pelican_Db::$values["HISTORY_TARGET"] = $event->getTarget();
    	Pelican_Db::$values["HISTORY_DEVICE"] = $event->getDevice();
    	Pelican_Db::$values["HISTORY_TYPE"] = $event->getType();
    	    	
    	$oConnection->updateTable("INS", "#pref#_boforms_state_history");
   
    }

}