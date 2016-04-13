<?php
namespace Citroen\Perso\Score;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Citroen\Perso\Score\ScoreEvent;
use Citroen\Perso\Score\IndicateurManager;

class ScoreEventSubscriber implements EventSubscriberInterface {

    private $started = false;

    public static function getSubscribedEvents() {
        return array(
            ScoreEvent::SAVE => array(
                'onScoreSave', 0
            )
        );
    }

    public function onScoreSave(ScoreEvent $event) {

        //$sm = new ScoreManager();
        if (false === $this->started) {
            //$subscriber = new StoreSubscriber();
            //$event->getDispatcher()->addSubscriber($subscriber);
        }

        //UPDATE INDICATEUR TRANCHE_SCORE
       /* 
        $oFlag = new Flag();
        $oFlag->process();
        /**
         *     a ajouter
         *     public static $trancheScore = null;
         

        $aIndicateur= array(
            
        
            'product_best_score'=>Detail::$productBestScore,//score
            'recent_product'=>Detail::$recentProduct,
            'client'=>Detail::$client,
            'product_owned'=>Detail::$productOwned,
            'pro'=>Detail::$pro,
            'recent_client'=>Detail::$recentClient,
            'email'=>Detail::$email,
            'date_purchase'=>Detail::$datePurchase,
            'extended_warranty'=>Detail::$extendedWarranty,
            'service_contract'=>Detail::$serviceContract,
            'current_product'=>Detail::$currentProduct,
            'preferred_product'=>Detail::$preferredProduct,
            'project_open'=>Detail::$projectOpen
        );
        
        $oIndicateurManager = new IndicateurManager();
        //public function saveIndicateur($iUserId = null, $sSessionId,$data)
        ($event->oUser)?$user_id=$event->oUser->getId():null;
        $oIndicateurManager->saveIndicateur($user_id,$event->sSessionId,$aIndicateur);
        */
        $this->started =true;
    }

}

