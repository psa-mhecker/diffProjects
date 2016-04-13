<?php

namespace Citroen\Event;

use Symfony\Component\EventDispatcher\Event;

class PAGE_EVENTS {

    const VISIT = 'page.visit';

}

class PageEvent extends Event {

    protected $oPage;
    protected $oUser;
    protected $sSessionId;
    protected $sUserAgent;
    protected $sIp;
    protected $iTime;
    protected $iSiteId;
    protected $iLangueId;
    protected $dispatcher = null;

    public function __construct($args = null) {
        if (is_array($args)) {
            //fetch page
            if (isset($args['page_id']) && isset($args['langue_id']) && isset($args['site_id'])) {
                $this->oPage = \Pelican_Cache::fetch(
                                'Frontend/Page', array(
                            $args['page_id'],
                            $args['site_id'],
                            $args['langue_id']
                                )
                );
                 debug($this->oPage);
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
    public function getUserId() {
        $iUserId = null;
        if($this->oUser !=null){
            $iUserId = $this->oUser->getId();
        }
        return $iUserId;
    }


}

