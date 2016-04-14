<?php

namespace Citroen;

use Citroen\Perso\Score\IndicateurManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Citroen\Event\UserEvent;
use Citroen\Event\UserEventSubscriber;

/**
 * Class User.
 */
class UserProvider
{
    public static function getUser()
    {
        if (isset($_SESSION[APP]['USER']) && is_object($_SESSION[APP]['USER'])) {
            $user = $_SESSION[APP]['USER'];
        }

        return $user;
    }

    public static function setUser($user)
    {
        $_SESSION[APP]['USER'] = $user;

        try {
            $indicateur = new IndicateurManager();
        } catch (\MongoConnectionException $ex) {
            $indicateur = null;
        }

        //Connexion BDI pour perso
        $userBdi = new \Cpw_GRCOnline_CustomerAt_User();
        $userBdi->loadUser($user->getEmail());

        $_SESSION[APP]['USER_BDI']['IS_CUSTOMER'] = $userBdi->IsCustomer;
        unset($_SESSION[APP]['USER_BDI']);
        if ($userBdi->LastBoughtVehicle != null) {
            $_SESSION[APP]['USER_BDI']['LAST_BOUGHT'] = $userBdi->LastBoughtVehicle->UserSinceDate;
        }
        if ($userBdi->LastMainDrivedVehicle  != null) {
            $_SESSION[APP]['USER_BDI']['PRODUCT_OWNED'] = $userBdi->LastMainDrivedVehicle->LCDV;
            $_SESSION[APP]['USER_BDI']['DATE_PURCHASE'] = $userBdi->LastMainDrivedVehicle->ReleaseDate;
        }

        if ($user->isLogged()) {
            $_dispatcher = new EventDispatcher();
            $_dispatcher->addSubscriber(new UserEventSubscriber());
            $oEvent = new UserEvent(array('user' => $user));
            $_dispatcher->dispatch(UserEvent::LOGIN, $oEvent);
        }

        if ($indicateur !== null) {
            $indicateur->saveIndicateur($user->getId(), null, $_SESSION[APP]['USER_BDI']);
        }

        return $user;
    }

    public static function destroy()
    {
        if (isset($_SESSION[APP]['USER'])) {
            unset($_SESSION[APP]['USER']);
            unset($_SESSION[APP]['FLAGS_USER']);
            unset($_SESSION[APP]['PROFILES_USER']);
        }
    }

    public static function destroyRS()
    {
        if ($_SESSION[APP]['FACEBOOK_ID']) {
            $facebook = new Citroen_Facebook(array(
                'appId' => Pelican::$config['FACEBOOK']['appId'],
                'secret' => Pelican::$config['FACEBOOK']['secret'],
            ));
            $facebook->destroySession();
            unset($_SESSION[APP]['FACEBOOK_ID']);
            unset($_SESSION[APP]['facebook_profile']);
        }
        unset($_SESSION[APP]['TWITTER_ACCESS_TOKEN']);
        unset($_SESSION[APP]['twitter_profile']);
        unset($_SESSION[APP]['GOOGLE_ACCESS_TOKEN']);
        unset($_SESSION[APP]['google_profile']);
        unset($_SESSION[APP]['iduser_temp_cid']);
        unset($_SESSION[APP]['iduser_temp_rs']);
    }
}
