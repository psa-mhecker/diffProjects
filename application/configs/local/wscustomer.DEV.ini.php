<?php
return array(
    'openid' => array(
        'identity' => "http://re7.iddcr.citroen.com",
        'realm' => Pelican::$config['DOCUMENT_HTTP'],
        'returnUrl' => Pelican::$config['DOCUMENT_HTTP'] . "/_/User/openid"
    ),
    'oauth' => array(
        'urlrequesttoken' => "http://re7.oadcr.citroen.com/oauth/request-token",
        'urlauthorize' => "http://re7.oadcr.citroen.com/oauth/authorize",
        'urlaccesstoken' => "http://re7.oadcr.citroen.com/oauth/access-token",
        'urlcallback' => Pelican::$config['DOCUMENT_HTTP'] . "/_/User/connexionCitroenIdCallback",
        'consumerkey' => "6f80cc70b6122e5bde83708ea524a444",
        'consumersecret' => "c1d830aaa44c2152012a979d6e74f494",
        'method' => "GET"
    ),
    'dcr' => array(
        'bend' => array(
            'crmservice' => "http://re7.dpdcr.citroen.com/crmws"
        )
    ),
    'sitecode' => "AC_FR_CPPV2"
);
