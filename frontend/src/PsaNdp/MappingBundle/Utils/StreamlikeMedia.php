<?php

namespace PsaNdp\MappingBundle\Utils;

use Guzzle\Http\Client;
use PsaNdp\MappingBundle\Object\Streamlike;
use PsaNdp\MappingBundle\Translation\TranslatorAwareTrait;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Utility class to retrieve information from streamlike medias
 *
 * @author sthibault
 */
class StreamlikeMedia
{
    use TranslatorAwareTrait;

    const ONLINE  = 'online';

    /** @var Client */
    private $client;
    /** @var array */
    private $options = [];

    /**
     * base url for the streamlike webservices
     * @var string
     */
    protected $server;

    /**
     * Constructor
     * @param string              $server
     * @param array               $options
     * @param TranslatorInterface $translator
     * @param RequestStack        $requestStack
     */
    public function __construct($server, array $options, TranslatorInterface $translator, RequestStack $requestStack)
    {
        $this->server = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://'.$server : 'http://'.$server;
        $this->client = new Client();
        $clientOptions = [];
        if(!empty($options) && isset($options['isActive']) && $options['isActive'])
        {
            $auth = '';
            if(!empty($options['login']) && !empty($options['password'])) {
                $auth = $options['login'].':'.$options['password'].'@';
            }
            $proxy =$options['protocole'].'://'.$auth.$options['host'].':'.$options['port'];
            $clientOptions['proxy'] = $proxy;
        }

        $this->translator = $translator;
        if(null !== $requestStack && (($request = $requestStack->getCurrentRequest()) !== null)) {
            $request = $requestStack->getCurrentRequest();
            $this->domain  = $request->get('siteId');
            $this->locale = $request->getLocale();
        }

        $this->options = $clientOptions;
    }

    /**
     * retrieve useful information from streamlike service for a media
     *
     * @param string $mediaId
     *
     * @return Streamlike
     */
    public function get($mediaId)
    {
        $sourceUrl  = $this->server . 'ws/media?media_id=' . $mediaId;
        $response = $this->client->get($sourceUrl, null, $this->options)->send();
        $mediaInfos = json_decode((string) $response->getBody(),true);
        $media      = null;
        if (self::ONLINE === $mediaInfos['media']['metadata']['global']['status']) {
            $media = new Streamlike();
            $media->setErrorMessage($this->trans('NDP_ERROR_VIDEO_STREAMLIKE'));
            $media['poster']    = strtr($mediaInfos['media']['metadata']['customization']['cover']['url'],['http://'=>'//','https://'=>'//']);
            $media['media_id']  = $mediaId;
            $media['width']     = $mediaInfos['media']['sources'][0]['source']['width'];
            $media['height']    = $mediaInfos['media']['sources'][0]['source']['height'];
            $media['title']     = $mediaInfos['media']['metadata']['global']['name'];
            $media['url']       = $mediaInfos['media']['metadata']['share']['universal_url'];

        }

        return $media;
    }
}
