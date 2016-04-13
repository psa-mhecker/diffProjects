<?php
namespace Citroen\Perso\Score;
use Citroen\Perso\Flag;
use Citroen\Perso\Profile;
use Citroen\Perso\Flag\Detail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Citroen\Perso\Score\ScoreEvent;
use Citroen\Perso\Score\IndicateurManager;

class IndicateurEventSubscriber implements EventSubscriberInterface {

    private $started = false;

    public static function getSubscribedEvents() {
        return array(
            /*ScoreEvent::SAVE => array(
                'onScoreSave', 5
            ),*/
            \Pelican_Observer_TrackEvent::TRACK => array(
                'onActionTrack', 5
            )
            
        );
    }
    
    public function onScoreSave($event){
        //var_dump(get_class($event));
        self::saveIndicateurs($event);
        
        
    }
    public function onActionTrack($event){
        //var_dump(get_class($event));
        self::saveIndicateurs($event);
        
    }
    
    public function saveIndicateurs($event)
    {
        // Enregistrement du pid de la page courante
        Detail::$__currentPid = isset($event->oPage['PAGE_ID']) ? $event->oPage['PAGE_ID'] : null;
        
        $oFlag = new Flag();
        $oFlag->process();
        /**
         *     a ajouter
         *     public static $trancheScore = null;
         */
        $aIndicateur= array(
            
            'tranche_score'=>Detail::$trancheScore,
            'tranche_true_score'=>Detail::$trancheTrueScore,
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
            'project_open'=>Detail::$projectOpen,
            '__pro_source'=>Detail::$__proSource,
            '__consultations'=>Detail::$__consultations,
            'reconsultation'=>Detail::$reconsultation,
        );
        //var_dump($aIndicateur);

        try {
            $oIndicateurManager = new IndicateurManager();
        } catch (\MongoConnectionException $ex) {
            return;
        }
         
        //public function saveIndicateur($iUserId = null, $sSessionId,$data)
        ($event->oUser)?$user_id=$event->oUser->getId():null;
        $oIndicateurManager->saveIndicateur($user_id,$event->sSessionId,$aIndicateur);
        $oProfile = new Profile();
        $oProfile->process();
        $_SESSION[APP]['FLAGS_USER'] = $aIndicateur;
        $_SESSION[APP]['PROFILES_USER'] = $oProfile->getProfile();
        
        // Lecture du compteur de consultations de la page courante
        if (isset(Detail::$__consultations[Detail::$__currentPid])) {
            $_SESSION[APP]['perso_consultation_page'] =  Detail::$__consultations[Detail::$__currentPid]['cpt'];
        }
    }
}
