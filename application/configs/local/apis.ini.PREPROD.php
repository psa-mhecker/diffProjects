<?php
/**
 * googleKey
 */

//Citroen clé
Pelican::$config ['youtube']['key'] = 'AIzaSyByAi7_Hi4R5CyDhhrdJUU0sNNiA2hjMA8';
//Pelican::$config ['youtube']['key'] = 'AIzaSyDAawKnCZmcyNrr-FhemX7B2qlxzwgZGWc';
 	
										

if(class_exists('Itkg')){
    
    /**
     * google API oauth callback
     */

    Itkg::$config['ITKG_APIS_GOOGLE']['authentication_provider']['PARAMETERS']['redirect_uri'] = Pelican::$config['SERVER_PROTOCOL'].'://'.Pelican::$config['HTTP_HOST'].'/callback.php';

    // api google a creer citroen
    /*Itkg::$config['ITKG_APIS_GOOGLE_CLIENT'] = array(
                'client_id' => '192584187188-g7bq1h41f3ip6s9ncsuetr092dsanold.apps.googleusercontent.com',
                'user_id'   => '192584187188-g7bq1h41f3ip6s9ncsuetr092dsanold@developer.gserviceaccount.com',
                'client_secret' => '8ZkHRAN8WOOZnyVAJDxEwjhN'
    );*/
	/**
	*	On set la config api google pour le site en cours.
	*	Necessaire pour utilisé l'api youtube
	*/
	setConfigApiGoogle ();

	
    Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE'] = array(
        'configuration' => 'Itkg\Apis\Google\Youtube\V3\Youtube\Configuration',
        'class'         => 'Itkg\Apis\Google\Youtube\V3\Youtube',
        'authentication_provider' => array(
            'TYPE' => 'oauth2',
            'PARAMETERS' => array(
                'id' =>  'google_youtube_v3',
                'client_id' => Itkg::$config['ITKG_APIS_GOOGLE_CLIENT']['client_id'],
                'user_id'   => Itkg::$config['ITKG_APIS_GOOGLE_CLIENT']['user_id'],
                'client_secret' => Itkg::$config['ITKG_APIS_GOOGLE_CLIENT']['client_secret'],
				'access_type' => 'offline',
                'scope'  => 'https://gdata.youtube.com',			
                'token_endpoint' => 'https://accounts.google.com/o/oauth2/token',
                'authorize_endpoint' => 'https://accounts.google.com/o/oauth2/auth?access_type=offline',
                'credentials_in_request_body' => true,
                'redirect_uri' => Itkg::$config['ITKG_APIS_GOOGLE']['authentication_provider']['PARAMETERS']['redirect_uri'], // uri a surcharger par la page appelante
                'curl.options' => Pelican::$config['PROXY.CURL.OPTIONS']
            )
        ),
        'PARAMETERS' => array(
            'host' => 'https://www.googleapis.com',
            'uri'  => '/youtube/v3/videos', // uri a surcharger selon les objets à appeler (/videos, /search, /channels ...)
            'disableLogTrame'=>'true',
            'headers' => array(
                'Content-Type' => 'application/json'
            ),
            'curl.options' => Pelican::$config['PROXY.CURL.OPTIONS']
        )
    );
	
	Itkg::$config['ITKG_APIS_GOOGLE_PLUS_V1'] = array(
        'configuration' => 'Itkg\Apis\Google\Plus\V1\Plus\Configuration',
        'class'         => 'Itkg\Apis\Google\Plus\V1\Plus',
        'authentication_provider' => array(
            'TYPE' => 'oauth2',
            'PARAMETERS' => array(
                'id' =>  'google_youtube_v3',
                'client_id' => Itkg::$config['ITKG_APIS_GOOGLE_CLIENT']['client_id'],
                'user_id'   => Itkg::$config['ITKG_APIS_GOOGLE_CLIENT']['user_id'],
                'client_secret' => Itkg::$config['ITKG_APIS_GOOGLE_CLIENT']['client_secret'],
				'access_type' => 'offline',
				'scope'  => 'https://www.googleapis.com/auth/userinfo.email',				
                'token_endpoint' => 'https://accounts.google.com/o/oauth2/token',
                'authorize_endpoint' => 'https://accounts.google.com/o/oauth2/auth',
                'credentials_in_request_body' => true,
                'redirect_uri' => Itkg::$config['ITKG_APIS_GOOGLE']['authentication_provider']['PARAMETERS']['redirect_uri'], // uri a surcharger par la page appelante
                'curl.options' => Pelican::$config['PROXY.CURL.OPTIONS']
            )
        ),
        'PARAMETERS' => array(
            'host' => 'https://www.googleapis.com',
            'uri'  => '/plus/v1/people/me',
            'disableLogTrame'=>'true',
            'headers' => array(
                'Content-Type' => 'application/json'
            ),
            'curl.options' => Pelican::$config['PROXY.CURL.OPTIONS']
        )
    );
}

/**
 * Permet de setter en session le label du profil via l'id du profil selectionné
 *
 */
function setConfigApiGoogle ()
{
	/*$oConnection = Pelican_Db::getInstance();
	$aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
	$sqlApiGoogle = "Select  GOOGLE_KEY, CLIENT_ID, USER_ID, CLIENT_SECRET from #pref#_site where SITE_ID= :SITE_ID";
	$resultApiGoogle = $oConnection->getRow($sqlApiGoogle, $aBind);*/
        $resultApiGoogle = Pelican_Cache::fetch('Service/ConfigGoogleApi', array( $_SESSION[APP]['SITE_ID'] ) );

	Itkg::$config['ITKG_APIS_GOOGLE_CLIENT']['client_id'] 		= $resultApiGoogle['CLIENT_ID'];
	Itkg::$config['ITKG_APIS_GOOGLE_CLIENT']['user_id'] 		= $resultApiGoogle['USER_ID'];
	Itkg::$config['ITKG_APIS_GOOGLE_CLIENT']['client_secret'] 	= $resultApiGoogle['CLIENT_SECRET'];
	Pelican::$config ['youtube']['key'] 				= $resultApiGoogle['GOOGLE_KEY'];
}


// Facebook/Twitter/Google Connect
Pelican::$config['FACEBOOK']['appId'] = "226662007494188";
Pelican::$config['FACEBOOK']['secret'] = "82fd18d4a5654c8efd25d7bc7a5ff3a7";
Pelican::$config['GOOGLE']['clientId'] = "895361473414-bk6avk1kgihge47k8g9l7pt3ajpjbrs8.apps.googleusercontent.com";
Pelican::$config['GOOGLE']['clientSecret'] = "nNRuJ05miPM_g5rC9xGOPDe1";
Pelican::$config['GOOGLE']['developerKey'] = "AIzaSyB6jE9DY0nI7Lgr7-5qosnS9qkDfJOfp_g";
Pelican::$config['TWITTER']['consumerKey'] = "gCHCGe8kTkazRMx7eqCCig";
Pelican::$config['TWITTER']['consumerSecret'] = "0sQet9E9c0uxLuwtOFi5dbjvdEAT6l1flTy6NykO0";
Pelican::$config['TWITTER']['oauth_token'] = "82178459-7k73wL6KwV7NbJshm2U2vwkx2ISDPkZAnOqk1tdwN";
Pelican::$config['TWITTER']['oauth_token_secret'] = "ZTAEQ1UWaesmXAQSRCAehdq5uDRdleev2KRZyHaAmcXC4";
