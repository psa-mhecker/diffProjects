<?php

namespace PsaNdp\MappingBundle\Sources;


use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\PsaReseauSocial;
use PSA\MigrationBundle\Repository\PsaPageRepository;
use PSA\MigrationBundle\Repository\PsaPageZoneRepository;
use PSA\MigrationBundle\Repository\PsaReseauSocialRepository;
use Abraham\TwitterOAuth\TwitterOAuth;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Madcoda\Youtube;


/**
 * Data source for Pf14ReseauxSociaux block
 */
class Pf14ReseauxSociauxDataSource extends AbstractDataSource
{
    /**
     * @var PsaPageRepository
     */
    private $pageRepository;

    /**
     * @var PsaPageZoneRepository
     */
    private $pageZoneRepository;

    /**
     * @var PsaReseauSocialRepository
     */
    private $socialNetworkRepository;
    private $proxy;
    private $chCurl;
    private $twitter;
    private $facebook;
    private $nbItems;
    
    protected $isMobile = false;

    const IMAGE_FORMAT = 19;
    const MAX_CHAR     = 220;
    const HASHTAG      = "https://twitter.com/hashtag/";
    const HASHTAG_FB   = "https://www.facebook.com/hashtag/";
    const HASHTAG_SRC  = "?src=hash";
    const TWITTER      = "https://twitter.com/";
    const OPENING      = "_blank";
    const FACEBOOK     = "https://www.facebook.com/";
    const YOUTUBE      = "https://www.youtube.com/watch?v=";
    const INSTAGRAM    = "https://instagram.com/";

    /**
     * @param PsaPageRepository         $pageRepository
     * @param PsaPageZoneRepository     $pageZoneRepository
     * @param PsaReseauSocialRepository $socialNetworkRepository
     */
    public function __construct(PsaPageRepository $pageRepository, PsaPageZoneRepository $pageZoneRepository, PsaReseauSocialRepository $socialNetworkRepository)
    {
        $this->pageRepository = $pageRepository;
        $this->pageZoneRepository = $pageZoneRepository;
        $this->socialNetworkRepository = $socialNetworkRepository;
    }

    /**
     * @param int $nbItems
     */
    public function setNbItems($nbItems)
    {
        $this->nbItems = $nbItems;
    }

    /**
     * @param TwitterOAuth $twitterOAuth
     */
    public function setTwitter(TwitterOAuth $twitterOAuth)
    {
        $this->twitter = $twitterOAuth;
    }

    /**
     * @param Array $facebook
     */
    public function setFacebook($facebook = array())
    {
        $this->facebook = null;
        FacebookSession::setDefaultApplication($facebook['appid'], $facebook['secret']);
        $session = new FacebookSession($facebook['appid'].'|'.$facebook['secret']);
        $facebook['session'] = FacebookSession::newAppSession();
        
        $this->facebook = $facebook;
    }

    /**
     * @param Array $proxy
     */
    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
    }

    /**
     * init curl
     */
    public function setChCurl()
    {
        $ch = curl_init();
        $this->chCurl = $ch;
    }

    /**
     * Set curl opt
     *
     * @param string $curloptUrl
     */
    public function setCurlOpt($curloptUrl = '')
    {
        if (!$this->chCurl) {
            $this->setChCurl();
        }

        if ($curloptUrl != '') {
            curl_setopt($this->chCurl, CURLOPT_URL, $curloptUrl);
        }

        if ($this->proxy['isActive']) {
            curl_setopt($this->chCurl, CURLOPT_PROXY, $this->proxy['CURLOPT_PROXY'].':'.$this->proxy['CURLOPT_PROXYPORT'].'');
            curl_setopt($this->chCurl, CURLOPT_PROXYUSERPWD, ''.$this->proxy['CURLOPT_PROXYUSERPWD'].'');
        }

        curl_setopt($this->chCurl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->chCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->chCurl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($this->chCurl, CURLOPT_USERAGENT, $this->requestServer->get('HTTP_USER_AGENT'));
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param  ReadBlockInterface $block
     * @param  Request            $request  Current url request displaying the block
     * @param  bool               $isMobile Indicate if is a mobile display
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        $this->requestServer = $request->server;
        $this->isMobile = $isMobile;

        /** @var $block PsaPageZoneConfigurableInterface */
        $realBlock = $block;

        $siteId = $realBlock->getPage()->getSiteId();
        $langId = $realBlock->getLangue()->getLangueId();

        $data['block'] = $realBlock;
        $data['reseauxSociaux'] = $this->getReseauxSociauxData($realBlock, $siteId, $langId);

        return $data;
    }

    /**
     * Return data
     *
     * @param PsaPageZoneConfigurableInterface $pageZoneConfigurable
     * @param int                              $siteId
     * @param int                              $langId
     *
     * @return array
     */
    public function getReseauxSociauxData(PsaPageZoneConfigurableInterface $pageZoneConfigurable, $siteId, $langId)
    {
        $data = [];

        $reseauxSociauxIds = $pageZoneConfigurable->getParameters();

        if (count($reseauxSociauxIds) > 0 && $reseauxSociauxIds[0] !== '') {
            $reseauxSociaux = $this->socialNetworkRepository->findBy(
                array('siteId' => $siteId, 'langueId' => $langId, 'reseauSocialId' => $reseauxSociauxIds)
            );

            // flip array ids to have appearance order as value
            $reseauxSociauxIds = array_flip($reseauxSociauxIds);

            foreach ($reseauxSociaux as $reseauSocial) {
                $reseauSocialId = $reseauSocial->getReseauSocialId();
                $order = $reseauxSociauxIds[$reseauSocialId];
                $data[$order] = $this->getReseauSocialData($reseauSocial);
            }

            // sort by key to have the right order of social networks as in page_zone_parameterss
            ksort($data);
        }

        return $data;
    }

    /**
     * Exec curl
     *
     * @param string $curloptUrl
     *
     * @return string $xml
     */
    public function execCurl($curloptUrl = '')
    {
        $this->setCurlOpt($curloptUrl);
        $xml = curl_exec($this->chCurl);

        return $xml;
    }

    /**
     * Return array of reseauSocial Data
     *
     * @param PsaReseauSocial $reseauSocial
     *
     * @return array
     */
    private function getReseauSocialData(PsaReseauSocial $reseauSocial)
    {
        $reseauSocialData = array();

        switch ($reseauSocial->getReseauSocialType()) {
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_FACEBOOK:
                $reseauSocialData = array(
                    'CLASS' => 'soc-net-face',
                    'POSTS' => $this->getItemsFacebook($reseauSocial),
                    'WIDGET' => $this->getWidgetFacebook($reseauSocial),
                    'ENTITE' => $reseauSocial
                );
                break;
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_INSTAGRAM:
                $reseauSocialData = $this->getItemsInstagram($reseauSocial);
                break;
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_TWITTER:
                $reseauSocialData = array(
                    'CLASS' => 'soc-net-twit',
                    'POSTS' => $this->getItemsTwitter($reseauSocial),
                    'WIDGET' => $this->getWidgetTwitter($reseauSocial->getReseauSocialIdCompte()),
                    'ENTITE' => $reseauSocial
                );
                break;
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_LINKEDIN:
                $reseauSocialData = array(
                    'CLASS' => 'soc-net-link',
                    'POSTS' => $this->getItemsLinkedIn($reseauSocial),
                    'WIDGET' => $this->getWidgetLinkedIn(),
                    'ENTITE' => $reseauSocial
                );
                break;
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_YOUTUBE:
                $reseauSocialData = array(
                    'CLASS' => 'soc-net-yout',
                    'POSTS' => $this->getItemsYouTube($reseauSocial),
                    'WIDGET' => $this->getWidgetYouTube($reseauSocial->getReseauSocialIdCompte()),
                    'ENTITE' => $reseauSocial
                );
                break;
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_VIADEO:
                $reseauSocialData = array(
                    'CLASS' => 'soc-net-viad',
                    'POSTS' => $this->getItemsViadeo($reseauSocial),
                    'WIDGET' => $this->getWidgetViadeo(),
                    'ENTITE' => $reseauSocial
                );
                break;
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_GOOGLEPLUS:
                $reseauSocialData = array(
                    'CLASS' => 'soc-net-goog',
                    'POSTS' => $this->getItemsGooglePlus($reseauSocial),
                    'WIDGET' => $this->getWidgetGooglePlus(),
                    'ENTITE' => $reseauSocial
                );
                break;
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_PINTEREST:
                $reseauSocialData = array(
                    'CLASS' => 'soc-net-pint',
                    'POSTS' => $this->getItemsPinterest($reseauSocial),
                    'WIDGET' => $this->getWidgetPinterest(),
                    'ENTITE' => $reseauSocial
                );
                break;
            case PsaReseauSocial::RESEAU_SOCIAL_TYPE_FLICKR:
                $reseauSocialData = array(
                    'CLASS' => 'soc-net-flic',
                    'POSTS' => $this->getItemsFlickr($reseauSocial),
                    'WIDGET' => $this->getWidgetFlickr(),
                    'ENTITE' => $reseauSocial
                );
                break;
            default:
                $reseauSocialData['UNDEFINED'] = $reseauSocial->getReseauSocialId();
                break;
        }

        return $reseauSocialData;
    }

    /**
     * Return array of Facebook posts
     *
     * @param PsaReseauSocial $reseauSocial
     *
     * @return array
     */
    private function getItemsFacebook(PsaReseauSocial $reseauSocial)
    {
        $datas = [];
        $channel = $reseauSocial->getReseauSocialIdCompte();
        $return = '';
        if ($reseauSocial->getAppId() && $reseauSocial->getAppIdSecret() && $channel) {
            $this->setFacebook(array('appid' => $reseauSocial->getAppId(), 'secret' => $reseauSocial->getAppIdSecret()));
            $datas = $this->getFacebookGraphObject2Array('GET', '/'.$channel.'/posts?fields=message,attachments,created_time&limit='.$this->nbItems);
            $return = $this->buildFacebookPost($reseauSocial, $datas);
        }

        return $return;
    }

    /**
     *
     * @param PsaReseauSocial $reseauSocial
     * @param array $datas
     *
     * @return type
     */
    private function buildFacebookPost(PsaReseauSocial $reseauSocial, $datas = [])
    {
        $posts = [];
        if (isset($datas['data'])) {
            foreach ($datas['data'] as $keyPost => $post) {
                $src = '';
                $url = self::FACEBOOK.$post->id;
                if ($reseauSocial->getMedia2()) {
                    $src = $this->getMediaServer().$reseauSocial->getMedia2()->getMediaPathWithFormat(self::IMAGE_FORMAT);
                }
                if (isset($post->attachments->data[0]->media->image->src)) {
                    $src = $post->attachments->data[0]->media->image->src;
                }
                if(!empty($post->attachments->data)) {
                    foreach ($post->attachments->data as $keyData => $valueData) {
                        if (isset($valueData->subattachments->data[0]->media->image->src)) {
                            $src = $valueData->subattachments->data[0]->media->image->src;
                            break;
                        }
                        if (isset($valueData->media->image->src)) {
                            $src = $valueData->media->image->src;
                        }
                        break;
                    }
                }
                $postCreationDate = (!empty($post->created_time)) ? $post->created_time : '';

                $posts[] = $this->addPost(
                    $url,
                    $src,
                    '',
                    $this->setHtmlForText(isset($post->message) ? $post->message : '' , self::FACEBOOK),
                    $this->setDiffOfPostDate($postCreationDate)
                );
            }
        }

        return $posts;
    }

    /**
     * Return array of Instagram posts
     *
     * @param PsaReseauSocial $reseauSocial
     *
     * @return array
     */
    private function getItemsInstagram(PsaReseauSocial $reseauSocial)
    {
        $id = '';
        $user  = $reseauSocial->getReseauSocialIdCompte();
        $return = '';
        if ($reseauSocial->getAppId() && $user) {
            $xml = $this->execCurl('https://api.instagram.com/v1/users/search?q='.$user.'&client_id='.$reseauSocial->getAppId());
            $xml = json_decode($xml);
            if (isset($xml->data[0]->id)) {
                $id  = $xml->data[0]->id;
            }
            $return = $this->buildPostForInstagram($reseauSocial, $id);
        }

        return $return;
    }

    /**
     *
     * @param PsaReseauSocial $reseauSocial
     * @param string $id
     *
     * @return array
     */
    private function buildPostForInstagram(PsaReseauSocial $reseauSocial, $id)
    {
        $posts = [];
        $reseauSocialData = array(
            'CLASS' => 'soc-net-inst',
            'ENTITE' => $reseauSocial
        );
        $reseauSocialData['WIDGET'] = $this->getWidgetInstagram($reseauSocial->getReseauSocialIdCompte());
        $xml = $this->execCurl('https://api.instagram.com/v1/users/'.$id.'/media/recent/?client_id='.$reseauSocial->getAppId());
        $xml = json_decode($xml);
        $added = 0;
        foreach ($xml->data as $entry) {
            $added++;
            $img = null;
            if (isset($entry->images->standard_resolution->url)) {
                $img = $entry->images->standard_resolution->url;
            }
            $posts[] = $this->addPost(isset($entry->link) ? $entry->link : '', $img, '',
                $this->setHtmlForText(isset($entry->caption->text) ? $entry->caption->text : '', self::INSTAGRAM),
                $this->setDiffOfPostDate(isset($entry->created_time) ? $entry->created_time : '', true));
            if ($this->nbItems <= $added) {
                break;
            }
        }
        $reseauSocialData['POSTS'] = $posts;

        return $reseauSocialData;
    }

    /**
     * Return array of Twitter items
     *
     * @param PsaReseauSocial $reseauSocial
     *
     * @return array
     */
    private function getItemsTwitter(PsaReseauSocial $reseauSocial)
    {
        $content = [];
        $channel = $reseauSocial->getReseauSocialIdCompte();
        $return = '';
        if ($channel && $reseauSocial->getTwitterConsummerKey() && $reseauSocial->getTwitterConsummerSecret() && $reseauSocial->getTwitterAccessToken() && $reseauSocial->getTwitterAccessTokenSecret()) {
            $connection = new TwitterOAuth($reseauSocial->getTwitterConsummerKey(), $reseauSocial->getTwitterConsummerSecret(), $reseauSocial->getTwitterAccessToken(), $reseauSocial->getTwitterAccessTokenSecret());
            if ($this->proxy['isActive']) {
                $connection->setProxy(array(
                    'CURLOPT_PROXY' => (string) $this->proxy['CURLOPT_PROXY'],
                    'CURLOPT_PROXYUSERPWD' => (string) $this->proxy['CURLOPT_PROXYUSERPWD'],
                    'CURLOPT_PROXYPORT' => (string) $this->proxy['CURLOPT_PROXYPORT'],
                ));
            }
            // add 'exclude_replies' => true if we don't want tweet with @someone in the array
            $result = $connection->get('statuses/user_timeline', array('screen_name' => $channel, 'count' => $this->nbItems + 1));
            if (is_array($result)) {
                $content = $result;
            }
            $return = $this->buildPostForTwitter($reseauSocial, $content);
        }

        return $return;
    }

    /**
     *
     * @param PsaReseauSocial $reseauSocial
     * @param array           $content
     * 
     * @return type
     */
    private function buildPostForTwitter(PsaReseauSocial $reseauSocial, $content = [])
    {
        $posts = [];
        if (is_array($content)) {
            foreach ($content as $tweet) {
                $mediaUrlHttps = '';
                if ($reseauSocial->getMedia2()) {
                    $mediaUrlHttps = $this->getMediaServer().$reseauSocial->getMedia2()->getMediaPathWithFormat(self::IMAGE_FORMAT);
                }
                if (isset($tweet->entities->media[0]->media_url_https)) {
                    $mediaUrlHttps = $tweet->entities->media[0]->media_url_https;
                }
                $url = self::TWITTER.$tweet->user->screen_name."/status/".$tweet->id_str;
                $posts[] = $this->addPost($url, $mediaUrlHttps, '', $this->setHtmlForText(isset($tweet->text) ? $tweet->text : '', self::TWITTER), $this->setDiffOfPostDate(isset($tweet->created_at) ? $tweet->created_at : ''));
            }
        }

        return $posts;
    }

    /**
     *
     * @param string $date
     * @param boolean $isTimeStamp
     * 
     * @return \DateInterval|null
     */
    private function setDiffOfPostDate($date, $isTimeStamp = false)
    {
        $diffDate = null;
        if (!empty($date)) {
            $dateToCompare = date_create($date);
            if ($isTimeStamp) {
                $dateToCompare = new \DateTime('@'.$date);
            }
            $now = new \DateTime("now", new \DateTimeZone("UTC"));
            $diffDate = $dateToCompare->diff($now);
        }
        
        return $diffDate;
    }
    
    /**
     *
     * @param string $text
     * @param string $social
     *
     * @return string
     */
    private function setHtmlForText($text, $social = null)
    {
        $text = $this->getHtmlLink($text);
        switch ($social) {
            case self::FACEBOOK:
                $text = preg_replace('/#(\w+)/', '<strong><a target="'.self::OPENING.'" href="'.self::HASHTAG_FB.'$1">$0</a></strong>', $text);
                break;
            case self::TWITTER:
                $text = preg_replace('#@(\w+)#', '<strong><a target="'.self::OPENING.'" href="'.self::TWITTER.'$1">$0</a></strong>', $text);
                $text = preg_replace('/#(\w+)/', '<strong><a target="'.self::OPENING.'" href="'.self::HASHTAG.'$1'.self::HASHTAG_SRC.'">$0</a></strong>', $text);
                break;
            case self::INSTAGRAM:
                $text = preg_replace('#@(\w+)#', '<strong><a target="'.self::OPENING.'" href="'.self::INSTAGRAM.'$1">$0</a></strong>', $text);
                $text = preg_replace('/#(\w+)/', '<strong><a target="'.self::OPENING.'" href="'.self::INSTAGRAM.'explore/tags/$1">$0</a></strong>', $text);
                break;
            default:
                //nothing
                break;
        }
        
        return $text;
    }

    /**
     *
     * @param string $text
     * 
     * @return string
     */
    private function getHtmlLink($text)
    {
        $htmlText = preg_replace_callback(
            '@((https?:\/\/)?([-\w\.]+[-\w])+(:\d+)?([^/]\/([\w\/_\.#-]*(\?\S+)?[^\.\s])?))@',
                function ($matches) {
                    $return = '<strong><a target="'.self::OPENING.'" href="'.$matches[0].'">'.$matches[0].'</a></strong>';
                    if (strpos($matches[0], 'http') === false) {
                        $return = '<strong><a target="'.self::OPENING.'" href="http://'.$matches[0].'">'.$matches[0].'</a></strong>';
                    }
                
                return $return;
            }, $text
        );

        return $htmlText;
    }

    /**
     * Return array of LinkedId posts
     *
     * @param PsaReseauSocial $reseauSocial
     *
     * @return array
     */
    private function getItemsLinkedIn(PsaReseauSocial $reseauSocial)
    {
        $posts = [];
        //        $posts[] = $this->addPost();
        return $posts;
    }

    /**
     * Return array of YouTube posts
     *
     * @param PsaReseauSocial $reseauSocial
     *
     * @return array
     */
    private function getItemsYouTube(PsaReseauSocial $reseauSocial)
    {
        $activities = [];
        $this->youtube = null;
        $channel = $reseauSocial->getReseauSocialIdCompte();
        if ($channel && $reseauSocial->getYoutubeApiKey()) {
            $this->youtube = new Youtube(array('key' => $reseauSocial->getYoutubeApiKey()));            
            $apiUrl = $this->youtube->getApi('activities');
            $params = array(
                'channelId' => $channel,
                'part' => 'id, snippet, contentDetails',
                'key' => $reseauSocial->getYoutubeApiKey()
            );
            $apiData = $this->getYoutubeApiData($apiUrl, $params);
            $result = $this->youtube->decodeList($apiData);
            if (is_array($result)) {
                $activities = $result;
            }
        }

      
        return $this->buildYoutubeActivities($activities);
    }

    /**
     *
     * @param array $activities
     * @return array
     */
    private function buildYoutubeActivities($activities = [])
    {
        $posts = [];
        $added = 0;
        $oldId = '';
        if (!empty($activities)) {
            foreach ($activities as $keyActivitie => $activite) {
                if (isset($activite->contentDetails->upload->videoId)) {
                    $added++;
                    $oldId   = $activite->contentDetails->upload->videoId;
                    $posts[] = $this->addPost(self::YOUTUBE.$activite->contentDetails->upload->videoId,
                        $activite->snippet->thumbnails->high->url, '',
                        $this->setHtmlForText(isset($activite->snippet->description) ? $activite->snippet->description : ''),
                        $this->setDiffOfPostDate(isset($activite->snippet->publishedAt) ? $activite->snippet->publishedAt : ''));
                }
                if ($this->nbItems <= $added) {
                    break;
                }
            }
        }

        return $posts;
    }

    /**
     * Using CURL to issue a GET request
     *
     * @param $url
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    private function getYoutubeApiData($url, $params)
    {
        $tuCurl = curl_init();
        curl_setopt($tuCurl, CURLOPT_URL, $url . (strpos($url, '?') === false ? '?' : '') . http_build_query($params));
        curl_setopt($tuCurl, CURLOPT_PORT, 443);
        if (strpos($url, 'https') === false) {
            curl_setopt($tuCurl, CURLOPT_PORT, 80);
        }
        if ($this->proxy['isActive']) {
            curl_setopt($tuCurl, CURLOPT_PROXY, $this->proxy['CURLOPT_PROXY'].':'.$this->proxy['CURLOPT_PROXYPORT']);
            curl_setopt($tuCurl, CURLOPT_PROXYUSERPWD, $this->proxy['CURLOPT_PROXYUSERPWD']);
        }
        curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
        $tuData = curl_exec($tuCurl);
        if (curl_errno($tuCurl)) {
            throw new \Exception('Curl Error : ' . curl_error($tuCurl), curl_errno($tuCurl));
        }
        
        return $tuData;
    }

    /**
     * Return array of Viadeo posts
     *
     * @param PsaReseauSocial $reseauSocial
     *
     * @return array
     */
    private function getItemsViadeo(PsaReseauSocial $reseauSocial)
    {
        $posts = [];
        //        $posts[] = $this->addPost();
        return $posts;
    }

    /**
     * Return array of GooglePlus posts
     *
     * @param PsaReseauSocial $reseauSocial
     *
     * @return array
     */
    private function getItemsGooglePlus(PsaReseauSocial $reseauSocial)
    {
        $posts = [];
        //        $posts[] = $this->addPost();
        return $posts;
    }

    /**
     * Return array of Pinterest posts
     *
     * @param PsaReseauSocial $reseauSocial
     *
     * @return array
     */
    private function getItemsPinterest(PsaReseauSocial $reseauSocial)
    {
        $posts = [];
        //@TODO: Pinterest
        //                $ch = curl_init();
        //        curl_setopt($ch, CURLOPT_URL, 'http://www.pinterest.com/'.$channel.'/feed.rss');
        //        //curl_setopt($ch, CURLOPT_URL, 'https://api.pinterest.com/v3/pidgets/users/'.$channel.'/pins/');
        //        if (isset(Pelican::$config['PROXY']) && !empty(Pelican::$config['PROXY'])) {
        //            curl_setopt($ch, CURLOPT_PROXY, Pelican::$config['PROXY']['URL'].":".Pelican::$config['PROXY']['PORT']."" );
        //            curl_setopt($ch, CURLOPT_PROXYUSERPWD, "".Pelican::$config['PROXY']['LOGIN'].":".Pelican::$config['PROXY']['PWD']."");
        //        }
        //        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        //        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        //        $xml = curl_exec($ch);
        /* $json = json_decode(curl_exec($ch));
          if ($json->data->pins) {
          foreach ($json->data->pins as $entry) {
          //var_dump($entry);die;
          $order = '-pinterest-'.$entry->pinner->full_name;

          $media = '';
          if (preg_match('#src="([^"]+)"#', $entry->description, $matches)) {
          $media = $matches[1];
          }

          $post[$order] = array(
          'network' => array(
          "name" => "pinterest",
          "page" => $entry->pinner->full_name,
          "url"  => $entry->pinner->profile_url
          ),
          "post" => array(
          "date"  => '$entry->pubDate->__toString()',
          "title" => ($entry->attribution)?$entry->attribution->title:'',
          "text"  => $entry->description,
          "media" => $entry->images->{'237x'}->url,
          "url"   => $entry->link
          )
          );
          }
          } */
        //        if (strpos($xml,'<?xml') !== false) {
        //        $xml = simplexml_load_string($xml);
        //            if ($xml->channel->item) {
        //                foreach ($xml->channel->item as $entry) {
        //                    $order = date("YmdHis",strtotime($entry->pubDate->__toString())).'-pinterest-'.$channel;
        //                    $media = '';
        //                    if (preg_match('#src="([^"]+)"#', $entry->description, $matches)) {
        //                        $media = str_replace('/192x/', '/237x/', $matches[1]);
        //                    }
        //                    $post[$order] = array(
        //                        'network' => array(
        //                            "name" => "pinterest",
        //                            "page" => $xml->channel->title->__toString(),
        //                            "url"  => $xml->channel->link->__toString()
        //                        ),
        //                        "post" => array(
        //                            "date"  => $entry->pubDate->__toString(),
        //                            "title" => $entry->title->__toString(),
        //                            "text"  => strip_tags($entry->description),
        //                            "media" => $media,
        //                            "url"   => $entry->guid->__toString()
        //                        )
        //                    );
        //                }
        //            }
        //        }
        //        return $post;
        //        $posts[] = $this->addPost();
        return $posts;
    }

    /**
     * Return array of Flickr posts
     *
     * @param PsaReseauSocial $reseauSocial
     *
     * @return array
     */
    private function getItemsFlickr(PsaReseauSocial $reseauSocial)
    {
        $posts = [];
        //        $posts[] = $this->addPost();
        return $posts;
    }

    /**
     * Return string of Mock widget
     *
     * @return string
     */
    private function getWidgetMock()
    {

        return '';
    }

    /**
     * Return widget of Facebook
     *
     * @param PsaReseauSocial $reseauSocial
     *
     * @return string
     */
    private function getWidgetFacebook(PsaReseauSocial $reseauSocial)
    {
        $widget = '';
        if ($reseauSocial) {
            $url = $reseauSocial->getReseauSocialUrlWeb();
            if ($url) {
                $widget = '<iframe src="http://www.facebook.com/plugins/like.php?href='.$url.'&layout=standard&show_faces=false&action=like&colorscheme=light&share=true" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden;"></iframe>';
            }
        }
        
        return $widget;
    }

    /**
     * Return widget of Instagram
     *
     * @param string $user
     *
     * @return string
     */
    private function getWidgetInstagram($user)
    {
        $widget  = '<style>.ig-b- { display: inline-block; }
            .ig-b- img { visibility: hidden; }
            .ig-b-:hover { background-position: 0 -60px; } .ig-b-:active { background-position: 0 -120px; }
            .ig-b-v-24 { width: 137px; height: 24px; background: url(//badges.instagram.com/static/images/ig-badge-view-sprite-24.png) no-repeat 0 0; }
            @media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2 / 1), only screen and (min-device-pixel-ratio: 2), only screen and (min-resolution: 192dpi), only screen and (min-resolution: 2dppx) {
            .ig-b-v-24 { background-image: url(//badges.instagram.com/static/images/ig-badge-view-sprite-24@2x.png); background-size: 160px 178px; } }</style>
            <a href="http://instagram.com/'.$user.'?ref=badge" target="_blank" class="ig-b- ig-b-v-24"><img src="//badges.instagram.com/static/images/ig-badge-view-24.png" alt="Instagram" /></a>';

        return $widget;
    }

    /**
     * Return widget of Twitter
     *
     * @param string $id
     *
     * @return string
     */
    private function getWidgetTwitter($id)
    {
        $widget = '';
        if ($id) {
            $widget = "<a href='https://twitter.com/$id' class='twitter-follow-button' data-show-count='true'>Follow @$id</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
        }
        
        return $widget;
    }

    /**
     * Return widget of LinkedIn
     *
     * @param
     *
     * @return array
     */
    private function getWidgetLinkedIn()
    {
        //@TODO faire l'appel a l'API
        $widget = $this->getWidgetMock();

        return $widget;
    }

    /**
     * Return widget of YouTube
     *
     * @param string $user
     *
     * @return string
     */
    private function getWidgetYouTube($user)
    {
        $widget = '';
        if ($user) {
            $widget = '<script src="https://apis.google.com/js/platform.js"></script>

                       <div class="g-ytsubscribe" data-channelid="'.$user.'" data-layout="full" data-count="default"></div>';
        }
        
        return $widget;
    }

    /**
     * Return widget of Viadeo
     *
     * @param
     *
     * @return array
     */
    private function getWidgetViadeo()
    {
        //@TODO faire l'appel a l'API
        $widget = $this->getWidgetMock();

        return $widget;
    }

    /**
     * Return widget of GooglePlus
     *
     * @param
     *
     * @return array
     */
    private function getWidgetGooglePlus()
    {
        //@TODO faire l'appel a l'API
        $widget = $this->getWidgetMock();

        return $widget;
    }

    /**
     * Return widget of Pinterest
     *
     * @param
     *
     * @return array
     */
    private function getWidgetPinterest()
    {
        //@TODO faire l'appel a l'API
        $widget = $this->getWidgetMock();

        return $widget;
    }

    /**
     * Return widget of Flickr
     *
     * @param
     *
     * @return array
     */
    private function getWidgetFlickr()
    {
        //@TODO faire l'appel a l'API
        $widget = $this->getWidgetMock();

        return $widget;
    }

    /**
     * Return array of Facebook GraphObject result of request
     *
     * @param string $method ('GET', ...)
     * @param string $path
     *
     * @return array
     */
    private function getFacebookGraphObject2Array($method, $path)
    {
        $request = new FacebookRequest($this->facebook['session'], $method, $path);
        if ($this->proxy['isActive']) {
            $facebookCurl = new FacebookCurl();
            $facebookCurl->init();
            $facebookCurl->setopt(CURLOPT_PROXY, $this->proxy['CURLOPT_PROXY'].':'.$this->proxy['CURLOPT_PROXYPORT'].'');
            $facebookCurl->setopt(CURLOPT_PROXYUSERPWD, ''.$this->proxy['CURLOPT_PROXYUSERPWD'].'');
            $facebookCurlHttpCLient = new FacebookCurlHttpClient($facebookCurl);
            $request->setHttpClientHandler($facebookCurlHttpCLient);
        }
        
        $response = $request->execute();
        $graphObject = $response->getGraphObject();
        $data = $graphObject->asArray() + array('images' => []);

        return $data;
    }

    /**
     * Return array of Facebook GraphObject result of request
     *
     * @param string    $imgSrc
     * @param string    $imgAlt
     * @param string    $text
     * @param \DateInterval $diffDate
     *
     * @return array
     */
    private function addPost($url = '#', $imgSrc = '', $imgAlt = '', $text = '', $diffDate = NULL)
    {
        $text = $this->truncateHtml($text, self::MAX_CHAR);
        $post = array(
            'img' => array(
                'src' => $imgSrc,
                'alt' => $imgAlt,
                'url' => $url
            ),
            'text' => $text,
            'diffDate' => $diffDate
        );

        return $post;
    }

    /**
     * Truncates text.
     *
     * Cuts a string to the length of $length and replaces the last characters
     * with the ending if the text is longer than length.
     *
     * @param string  $text String to truncate.
     * @param integer $length Length of returned string, including ellipsis.
     * @param mixed $ending If string, will be used as Ending and appended to the trimmed string. Can also be an associative array that can contain the last three params of this method.
     * 
     * @return string Trimmed string.
     */
    private function truncateHtml($text, $length = 100, $ending = '...')
    {
        if (is_array($ending)) {
            extract($ending);
        }
        $truncate = '';
        if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            $truncate = $text;
        }
        $openTags    = array();
        if (empty($truncate)) {
            $totalLength = mb_strlen($ending);
            preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                        if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                            array_unshift($openTags, $tag[2]);
                        } else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                            $pos = array_search($closeTag[1], $openTags);
                            if ($pos !== false) {
                                array_splice($openTags, $pos, 1);
                            }
                        }
                    }
                    $truncate .= $tag[1];
                    $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
                    if ($contentLength + $totalLength > $length) {
                        $left           = $length - $totalLength;
                        $entitiesLength = 0;
                        if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i',
                                $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                            foreach ($entities[0] as $entity) {
                                if ($entity[1] + 1 - $entitiesLength <= $left) {
                                    $left--;
                                    $entitiesLength += mb_strlen($entity[0]);
                                } else {
                                    break;
                                }
                            }
                        }
                        $truncate .= mb_substr($tag[3], 0,
                            $left + $entitiesLength);
                        break;
                    } else {
                        $truncate .= $tag[3];
                        $totalLength += $contentLength;
                    }
                    if ($totalLength >= $length) {
                        break;
                    }
                }
            }
        }
        if ($truncate != $text) {
            $truncate .= $ending;
            foreach ($openTags as $tag) {
                $truncate .= '</'.$tag.'>';
            }
        }

        return $truncate;
    }
}
