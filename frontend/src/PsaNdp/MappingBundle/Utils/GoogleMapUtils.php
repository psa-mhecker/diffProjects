<?php

namespace PsaNdp\MappingBundle\Utils;

use Symfony\Component\Config\Definition\Exception\Exception;


/**
 * Class GoogleMapHelper
 * @package PsaNpd\MappingBundle\Helpers
 */
class GoogleMapUtils
{
    private $clientId;
    private $signaturePrivateKey;
    private $apiKey;
    private $googleMapUrl = 'https://maps.googleapis.com/maps/api/staticmap?';

    /**
     * Constructor with default parameters to generate url with Cliend Id or Api Key
     *
     * - without any Api Key or Client Id:
     *   ex : https://maps.googleapis.com/maps/api/staticmap?center=New+York,NY&zoom=13&size=600x300
     * - with a ClientID/Private Key (Signature generated with a Private Key):
     *   ex : https://maps.googleapis.com/maps/api/staticmap?markers=1+rue+de+Paris&size=640x160&zoom=17&client=CLIENT_ID&signature=SIGNATURE
     * - with a Api Key:
     *   ex : https://maps.googleapis.com/maps/api/staticmap?center=New+York,NY&zoom=13&size=600x300&key=API_KEY
     *
     *
     * @param string|null $clientId            Client id, if set then apiKey is not needed
     * @param string|null $signaturePrivateKey To set if client id is set. Private Key to generate Signature
     * @param string|null $apiKey              Api Key, if set, the Client Id and Api Key should not be needed
     */
    public function __construct($clientId = null, $signaturePrivateKey = null, $apiKey = null)
    {
        if ($clientId !== null && $signaturePrivateKey === null) {
            throw new Exception('GoogleMapUtils - Client Id ' . $clientId . ' was given without its associated Private Key. Google Map siganture can be generated');
        }

        $this->clientId = $clientId;
        $this->signaturePrivateKey = $signaturePrivateKey;
        $this->apiKey = $apiKey;
    }

    /**
     * @return null|string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param null|string $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return null|string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param null|string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return null
     */
    public function getSignaturePrivateKey()
    {
        return $this->signaturePrivateKey;
    }

    /**
     * @param null $signaturePrivateKey
     */
    public function setSignaturePrivateKey($signaturePrivateKey)
    {
        $this->signaturePrivateKey = $signaturePrivateKey;
    }

    /**
     * Create a Google Map url to image map
     * Doc: https://developers.google.com/maps/documentation/staticmaps/
     *
     * @param array $urlParameters Parameters for google map url. Ex : ['markers' => '1 rue de Paris', 'size' => '640x160', 'zoom' => 17]
     *                             Check Google Map doc for more information
     *
     * @return string
     */
    public function createGoogleMapUrl(array $urlParameters)
    {
        $url = $this->googleMapUrl . $this->generateParameters($urlParameters);

        if ($this->apiKey !== null) {
            $url .= '&key=' . $this->apiKey;
        }
        if ($this->apiKey === null && $this->clientId !== null && $this->signaturePrivateKey !== null) {
            $url .= '&client=' . $this->clientId;
            $url = $this->signUrl($url, $this->signaturePrivateKey);
        }

        return $url;
    }

    /**
     * Generate parameters string for array
     *
     * @param array $urlParameters Parameters to format as url
     *
     * @return string output format :
     */
    private function generateParameters(array $urlParameters)
    {
        $paramsStringArray = [];

        if (0 === count($urlParameters)) {
            throw new Exception('GoogleMapUtils - No parameters given to generate googleMapUrl');
        }

        foreach ($urlParameters as $key => $parameter) {
            $paramsStringArray[] = $key . '=' . urlencode($parameter);
        }

        return implode('&', $paramsStringArray);
    }


    /**
     * Encode a string to URL-safe base64
     * Based on : http://gmaps-samples.googlecode.com/svn/trunk/urlsigning/index.html
     *
     * @param $value
     *
     * @return mixed
     */
    private function encodeBase64UrlSafe($value)
    {

        return str_replace(
            array('+', '/'),
            array('-', '_'),
            base64_encode($value)
        );
    }

    /**
     * Decode a string from URL-safe base64
     * Based on : http://gmaps-samples.googlecode.com/svn/trunk/urlsigning/index.html
     *
     * @param $value
     *
     * @return string
     */
    private function decodeBase64UrlSafe($value)
    {

        return base64_decode(
            str_replace(array('-', '_'),
                array('+', '/'),
            $value)
        );
    }

    /**
     * Sign a URL with a given crypto key
     * Note that this URL must be properly URL-encoded
     * Based on : http://gmaps-samples.googlecode.com/svn/trunk/urlsigning/index.html
     *
     * @param string $myUrlToSign Full Google Map url including client id parameter, excluding signature paramters
     * @param string $privateKey  Private key related to the client id
     *
     * @return string
     */
    private function signUrl($myUrlToSign, $privateKey)
    {
        // parse the url
        $url = parse_url($myUrlToSign);

        $urlPartToSign = $url['path'] . '?' . $url['query'];

        // Decode the private key into its binary format
        $decodedKey = $this->decodeBase64UrlSafe($privateKey);

        // Create a signature using the private key and the URL-encoded
        // string using HMAC SHA1. This signature will be binary.
        $signature = hash_hmac('sha1', $urlPartToSign, $decodedKey, true);

        $encodedSignature = $this->encodeBase64UrlSafe($signature);

        return $myUrlToSign.'&signature='.$encodedSignature;
    }
}
