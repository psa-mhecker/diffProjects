<?php

namespace Citroen\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Citroen\Perso\Score\ScoreManager;
use Citroen\Perso\Score\ScoreEvent;

//use Citroen\Perso\Score\ScoreEventSubscriber;

class PageEventSubscriber implements EventSubscriberInterface
{
    private $started = false;

    public static function getSubscribedEvents()
    {
        return array(
            \Pelican_Observer_TrackEvent::TRACK => array(
                'onActionTrack', 5,
            ),
        );
    }

    public function onActionTrack($event)
    {
        if (false === $this->started) {
            //$event->getDispatcher()->addSubscriber(new ScoreEventSubscriber());
        }

        ($event->oUser) ? $user_id = $event->oUser->getId() : null;
        $aPageProducts = \Pelican_Cache::fetch('Frontend/Citroen/Perso/ProductPageTrack', array($_SESSION[APP]['SITE_ID'], $event->uri));

        try {
            $oScoreManager = new ScoreManager();
        } catch (\MongoConnectionException $ex) {
            return;
        }

        if (isset(\Pelican::$config['PERSO']['AJAX_LIST'][$event->uri])) {
            $aConfiguredParams = \Pelican::$config['PERSO']['AJAX_LIST'][$event->uri]['params'];
        } else {
            $aConfiguredParams = array();
        }

        // Modification du score lorsque le visiteur arrive depuis une bannière (seulement si le score configuré est inférieur au score bannière) [CPW-2167]
        if (!empty($event->params['from-banner']) && count($aPageProducts)) {
            foreach ($aPageProducts as $key => $val) {
                $aPageProducts[$key]['PRODUCT_PAGE_SCORE'] = max($val['PRODUCT_PAGE_SCORE'], \Pelican::$config['PERSO']['BANNER_SCORE']);
            }
        } elseif (!empty($event->params['from-banner']) && array_key_exists('Car', $event->params) && !empty($event->params['Car'])) {
            //cas d'une page avec code lcdv6 en get
            //get associated product
            $aProductsByLcdv6 = \Pelican_Cache::fetch('Frontend/Citroen/Perso/Lcdv6ByProduct', array($_SESSION[APP]['SITE_ID']));
            $iFoundProduct = array_search($event->params['Car'], $aProductsByLcdv6);
            if ($iFoundProduct) {
                $aPageProducts[] = array(
                        'PRODUCT_ID' => $iFoundProduct,
                        'PRODUCT_PAGE_SCORE' => \Pelican::$config['PERSO']['BANNER_SCORE'],
                    );
            }
        }

        // Recherche externe
        $searchBot = \Citroen_View_Helper_Global::querySearch();
        if ($searchBot != '') {
            $sKeyword = urldecode($searchBot);
            $aTerms = \Pelican_Cache::fetch('Frontend/Citroen/Perso/SearchTermTrack', array($_SESSION[APP]['SITE_ID'], $sKeyword));
            if (count($aTerms) > 0) {
                $aPageProducts = array();
                foreach ($aTerms as $aOneTerm) {
                    $aPageProducts[] = array(
                        'PRODUCT_ID' => $aOneTerm['PRODUCT_ID'],
                        'PRODUCT_PAGE_SCORE' => \Pelican::$config['PERSO']['SEARCH_TERM_SCORE_EXTERNE'],
                    );
                }
            }
        }

        // Recherche interne
        if (array_key_exists('search', $event->params)) {
            $sKeyword = $event->params['search'];
            $aTerms = \Pelican_Cache::fetch('Frontend/Citroen/Perso/SearchTermTrack', array($_SESSION[APP]['SITE_ID'], $sKeyword));
            if (count($aTerms) > 0) {
                $aPageProducts = array();
                foreach ($aTerms as $aOneTerm) {
                    $aPageProducts[] = array(
                        'PRODUCT_ID' => $aOneTerm['PRODUCT_ID'],
                        'PRODUCT_PAGE_SCORE' => \Pelican::$config['PERSO']['SEARCH_TERM_SCORE'],
                    );
                }
            }
        }

        if (count($aPageProducts) > 1) {
            $aParams = $event->params;
            foreach ($aPageProducts as $aOnePageProduct) {
                foreach ($aParams as $sParamName => $mParamValue) {
                    //param est un tableau
                    if (is_array($mParamValue) && $sParamName == 'values') {
                        foreach ($mParamValue as $key => $value) {
                            if (is_array($aConfiguredParams) && count($aConfiguredParams)) {
                                if (in_array($key, $aConfiguredParams)) {
                                    if (isset($aOnePageProduct['lcdv6']) && $value == $aOnePageProduct['lcdv6']) {
                                        $aProductToTouch = $aOnePageProduct;
                                        break;
                                    }
                                }
                            }
                        }
                    } else {
                        //param est une valeur
                        if (is_array($aConfiguredParams) && count($aConfiguredParams)) {
                            if (in_array($sParamName, $aConfiguredParams)) {
                                if (isset($aOnePageProduct['lcdv6']) && $mParamValue == $aOnePageProduct['lcdv6']) {
                                    $aProductToTouch = $aOnePageProduct;
                                    break;
                                }
                            }
                        } elseif ($mParamValue == $aOnePageProduct['lcdv6']) {
                            $aProductToTouch = $aOnePageProduct;
                            break;
                        }
                    }
                }
                /*if(
                    is_array($aParams['referer_params'])&&
                    !empty($aParams['referer_params']) &&
                    array_key_exists('lcdv6', $aParams['referer_params']) &&
                    $aParams['referer_params']['lcdv6'] == $aOnePageProduct['lcdv6']
                    )
                {
                    $aProductToTouch = $aOnePageProduct;
                    break;
                }*/
            }
        } elseif (count($aPageProducts) > 0) {
            //dirty hack
             $lcv6Key = '';
            if (array_key_exists('lcdv6', $event->params)) {
                $lcv6Key = 'lcdv6';
            }

            if (array_key_exists('Car', $event->params)) {
                $lcv6Key = 'Car';
            }

            if (array_key_exists('lcdv', $event->params)) {
                $lcv6Key = 'lcdv';
            }

            if (!empty($lcv6Key)) {
                if ($aPageProducts[0]['lcdv6'] == $event->params[$lcv6Key]) {
                    $aProductToTouch = $aPageProducts[0];
                }
            } else {
                $aProductToTouch = $aPageProducts[0];
            }
        }

        if (count($aPageProducts)) {
            if (isset($aProductToTouch['PRODUCT_ID']) && $aProductToTouch['PRODUCT_ID'] != null) {
                $oScoreManager->saveProductScore(
                        $user_id, $aProductToTouch['PRODUCT_ID'], $event->getSessionId(), $aProductToTouch['PRODUCT_PAGE_SCORE'], $event->getTime(), $_SESSION[APP]['SITE_ID']
                );
                $event->getDispatcher()->dispatch(ScoreEvent::SAVE, new ScoreEvent());
            }
        }
        $this->started = true;
    }
}
