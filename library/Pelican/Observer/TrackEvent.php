<?php
use Symfony\Component\EventDispatcher\Event;


class Pelican_Observer_TrackEvent extends Event {
    
    const TRACK = 'action.track';
    
    public $oPage;
    public $oUser;
    public $sSessionId;
    public $sUserAgent;
    public $sIp;
    public $iTime;
    public $iSiteId;
    public $iLangueId;
    public $uri=null;
    public $controller=null;
    public $action=null;
    public $isAjax=null;
    public $dispatcher = null;

    public function __construct($args = null) {
           if (is_array($args)) {
               
            // var_dump($args)  ;
               
            //fetch page
            if (isset($args['page_id']) && isset($args['langue_id']) && isset($args['site_id'])) {
                $this->oPage = \Pelican_Cache::fetch(
                                'Frontend/Page', array(
                            $args['page_id'],
                            $args['site_id'],
                            $args['langue_id']
                                )
                );
            }
            if (isset($args['user'])) {
                $this->oUser = $args['user'];
            }
            if (isset($args['user_agent'])) {
                $this->sUserAgent = $args['user_agent'];
            }
            if (isset($args['ip'])) {
                $this->sIp = $args['ip'];
            }
            if (isset($args['session_id'])) {
                $this->sSessionId = $args['session_id'];
            }
            if (isset($args['time'])) {
                $this->iTime = $args['time'];
            }
            if (isset($args['controller'])) {
                $this->controller = $args['controller'];
            }
            if (isset($args['action'])) {
                $this->action = $args['action'];
            }
            if (isset($args['site_id'])) {
                $this->iSiteId = $args['site_id'];
            }
            if (isset($args['uri'])) {
                $this->uri = $args['uri'];
            }
            if (isset($args['is_ajax'])) {
                $this->isAjax = $args['is_ajax'];
            }
            if (isset($args['params'])) {
                $this->params = $args['params'];
            }

        }
    }

    public function getPageClearUrl() {
        if (isset($this->oPage) && !empty($this->oPage)) {
            return $this->oPage['PAGE_CLEAR_URL'];
        }
    }

    public function getTime() {
        return $this->iTime; 
    }
    public function getIp() {
        return $this->sIp; 
    }
    public function getSessionId() {
        return $this->sSessionId; 
    }
    public function getUserAgent() {
        return $this->sUserAgent; 
    }


}

