<?php
use Symfony\Component\EventDispatcher\Event;


class Boforms_Observer_FormEvent extends Event {
    
    const TRACK = 'form.save';
    
    public $page_id;
    public $page_version;
    public $langue_id;
    public $date_update;
    public $state_id;
    public $site_id;
    public $zone_id;
    
    
    public $type;
    public $target;
    public $device;
    
    
    public function __construct($args = null) {
           if (is_array($args)) {

            $this->page_id = $_POST['PAGE_ID'];
            $this->page_version = $_POST['PAGE_VERSION'];
            $this->langue_id = $_POST['LANGUE_ID'];
            $this->date_update = date("Y-m-d H:i:s");
            $this->state_id = $_POST['STATE_ID'];
            $this->site_id = $_POST['SITE_ID'];
            $this->zone_id = $args['ZONE_ID'];    
            $this->target = $args['ZONE_TITRE4'];          
            $this->device = $args['ZONE_ATTRIBUT'];
            $this->type = $args['ZONE_TITRE3'];
       
        }
    }

    public function getPageId() {
        return $this->page_id; 
    }
    public function getPageVersion() {
        return $this->page_version; 
    }
    public function getLangueId() {
    	return $this->langue_id;
    }
    public function getDateUpdate() {
    	return $this->date_update;
    }
    public function getStateId() {
    	return $this->state_id;
    }
    public function getSiteId() {
    	return $this->site_id;
    }
    public function getZoneId() {
    	return $this->zone_id;
    }
    public function getType() {
    	return $this->type;
    }
    public function getTarget() {
    	return $this->target;
    }
    public function getDevice() {
    	return $this->device;
    }
    

}