<?php

namespace Citroen\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Citroen\Event\UserEvent;
use Citroen\Perso\Score\ScoreManager;
use Citroen\Perso\Score\ScoreEvent;

class UserEventSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            UserEvent::LOGIN => array(
                'onUserLogin', 10
            )
        );
    }

    public function onUserLogin($oEvent) {
        try {
            $oSm = new ScoreManager();
            $oMongoClient = new \MongoClient(\Pelican::$config['MONGODB_URI'], \Pelican::$config['MONGODB_PARAMS']);
        } catch (\MongoConnectionException $ex) {
            return;
        }
        $oUserActionsCollection = $oMongoClient->selectCollection(\Pelican::$config['MONGODB_PARAMS']['db'], 'user_actions');
        if (isset($oEvent->user) && !empty($oEvent->user)) {
            $iUserId = $oEvent->user->getId();
        }
        $aFilters = array(
            'products_scores' => array(
                '$not' => array(
                    '$size' => 0
                )
            ),
            'session_id' => $_SESSION[APP]['perso_sess']
        );
        $aSessionActivities = $oUserActionsCollection->find($aFilters);
        $aSessionProducts = array();
        if ($aSessionActivities->count()) {
            //construire un tableau avec tout les produits en session
            // avec le temps de sauvegarde de ces données
            foreach ($aSessionActivities as $aOneSessionActivity) {
                if (isset($aOneSessionActivity['products_scores']) && !empty($aOneSessionActivity['products_scores'])) {
                    foreach ($aOneSessionActivity['products_scores'] as $aOneProductScore) {
                        $aSessionProducts[] = array(
                            'product' => $aOneProductScore['product'],
                            'site_id' => $aOneProductScore['site_id'],
                            'score' => $aOneProductScore['score'],
                            'time' => $aOneSessionActivity['time']
                        );
                    }
                }
            }
        }
        //Sauvegarder les scores issues de la session sur la base mongo suivant les règles de calculs
        if (!empty($aSessionProducts)) {
            foreach ($aSessionProducts as $aOneSessionProduct){
                //saveProductScore($iUserId = null, $iProductId, $sSessionId, $fScore, $time, $siteId) {       
                $oSm->saveProductScore(
                        $iUserId,$aOneSessionProduct['product'],
                        $_SESSION[APP]['perso_sess'],
                        $aOneSessionProduct['score'],
                        time(),
                        $aOneSessionProduct['site_id']
                        );
            }
        }
    }

}
