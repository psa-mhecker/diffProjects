<?php

//namespace Observer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Pelican_Observer_TrackEventSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            Pelican_Observer_TrackEvent::TRACK => array(
                'onActionTrack', 10
            )
        );
    }

    /**
     * 
     * @todo nettoyer le code: enlever le code specifique à citroen.
     */
    public function onActionTrack($event) {
        try {
            $oMongoClient = new \MongoClient(Pelican::$config['MONGODB_URI'], \Pelican::$config['MONGODB_PARAMS']);
        } catch (MongoConnectionException $ex) {
            return;
        }

        $oUserActionsCollection = $oMongoClient->selectCollection(Pelican::$config['MONGODB_PARAMS']['db'], 'user_actions');

        /* code propre à citroen mis ici temporairement */

        $aPageProducts = \Pelican_Cache::fetch('Frontend/Citroen/Perso/ProductPageTrack', array($_SESSION[APP]['SITE_ID'], $event->uri));

        $aProductScores = array();

        if (isset(\Pelican::$config['PERSO']['AJAX_LIST'][$event->uri])) {
            $aConfiguredParams = \Pelican::$config['PERSO']['AJAX_LIST'][$event->uri]['params'];
        } else {
            $aConfiguredParams = array();
        }

        if (count($aPageProducts) > 1) {
            foreach ($aPageProducts as $aOnePageProduct) {
                $aParams = $event->params;
                foreach ($aParams as $sParamName => $mParamValue) {
                    if (in_array($sParamName, $aConfiguredParams)) {

                        if (isset($aOnePageProduct['lcdv6']) && $mParamValue == $aOnePageProduct['lcdv6']) {
                            if ($aOnePageProduct['PRODUCT_ID']) {
                                $aProductScores[] = array(
                                    'product' => $aOnePageProduct['PRODUCT_ID'],
                                    'score' => (float) $aOnePageProduct['PRODUCT_PAGE_SCORE'],
                                    'site_id' => $_SESSION[APP]['SITE_ID']
                                );
                            }
                        }
                    } elseif ($sParamName == 'lcdv6' && $mParamValue == $aOnePageProduct['lcdv6']) {
                        $aProductScores[] = array(
                            'product' => $aOnePageProduct['PRODUCT_ID'],
                            'score' => (float) $aOnePageProduct['PRODUCT_PAGE_SCORE'],
                            'site_id' => $_SESSION[APP]['SITE_ID']
                        );
                    }
                }
            }
        } elseif (count($aPageProducts) > 0) {
            $aProductScores[] = array(
                'product' => $aPageProducts[0]['PRODUCT_ID'],
                'score' => (float) $aPageProducts[0]['PRODUCT_PAGE_SCORE'],
                'site_id' => $_SESSION[APP]['SITE_ID']
            );
        }
		
		/*CPW-3920*/
		$aParamsValue=array(); 
		if(is_array($event->params) && sizeof($event->params) >0){
			foreach($event->params as $key=>$sParam){
				if(is_string($sParam)){
					$aParamsValue[$key] = htmlspecialchars($sParam, ENT_QUOTES, 'UTF-8');
				}else{
					$aParamsValue[$key] = $sParam;
				}
			}
		}
		/*CPW-3920*/
		
        /* end dirty hack */
        $aAction = array(
            'url' => $event->getPageClearUrl(),
            'uri' => $event->uri,
            'controller' => $event->controller,
            'action' => $event->action,
            'is_ajax' => $event->isAjax,
			'params' => $aParamsValue,
            //'user'=>$event->getUser(),
            'session_id' => $event->getSessionId(),
            'time' => $event->getTime(),
            'ip' => $event->getIp(),
            'user_agent' => $event->getUserAgent(),
            'products_scores' => $aProductScores,
        );

        $oInserted = $oUserActionsCollection->insert($aAction);
    }

}
