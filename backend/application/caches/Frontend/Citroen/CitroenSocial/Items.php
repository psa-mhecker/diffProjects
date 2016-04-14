<?php
class Frontend_Citroen_CitroenSocial_Items extends Pelican_Cache
{
    public $duration = HOUR;

    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $network = $this->params[0];
        $return = array();

        if (count($network)) {
            foreach ($network as $type => $socials) {
                if (is_array($socials) && !empty($socials)) {
                    foreach ($socials as $social) {
                        switch ($type) {
                            case 'FACEBOOK':
                                $items = $this->getFacebook($social['RESEAU_SOCIAL_ID_COMPTE']);
                                break;
                            case 'TWITTER':
                                $items = $this->getTwitter($social['RESEAU_SOCIAL_ID_COMPTE']);
                                break;
                            case 'YOUTUBE':
                                $items = $this->getYoutube($social['RESEAU_SOCIAL_ID_COMPTE']);
                                break;
                            case 'PINTEREST':
                                $items = $this->getPinterest($social['RESEAU_SOCIAL_ID_COMPTE']);
                                break;
                            case 'INSTAGRAM':
                                $items = $this->getInstagram($social['RESEAU_SOCIAL_ID_COMPTE']);
                                break;
                        }
                        if (!empty($items)) {
                            $return = array_merge($return, $items);
                        }
                    }
                }
            }
           // shuffle ($return);


            $return = array_values($return);
            usort($return, function ($a, $b) {
                        $x = intval(strtotime($a['post']['date']));
                        $y = intval(strtotime($b['post']['date']));

                        if ($x<$y) {
                            return 1;
                        } elseif ($x<$y) {
                            return -1;
                        } else {
                            return 0;
                        }
                    });

            $return = array_chunk($return, $this->params[1]);
        }

        $this->value = $return;
    }

    private function getTwitter($channel)
    {
        require_once Pelican::$config['LIB_ROOT']."/External/TwitterOAuth/twitteroauth.php";

        $consumer_key        = Pelican::$config['TWITTER']['consumerKey']; //Provide your application consumer key
        $consumer_secret     = Pelican::$config['TWITTER']['consumerSecret']; //Provide your application consumer secret
        $oauth_token         = Pelican::$config['TWITTER']['oauth_token']; //Provide your oAuth Token
        $oauth_token_secret  = Pelican::$config['TWITTER']['oauth_token_secret']; //Provide your oAuth Token Secret

        $proxy = null;
        if (isset(Pelican::$config['PROXY']) && !empty(Pelican::$config['PROXY'])) {
            $proxy = array(
                CURLOPT_PROXYTYPE => CURLPROXY_HTTP,
                CURLOPT_PROXY => Pelican::$config['PROXY']['URL'],
                CURLOPT_PROXYPORT => Pelican::$config['PROXY']['PORT'],
                CURLOPT_PROXYUSERPWD => sprintf('%s:%s', Pelican::$config['PROXY']['LOGIN'], Pelican::$config['PROXY']['PWD']),
            );
        }

        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret, $proxy);
        $query = "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$channel."&count=10";
        $content = $connection->get($query);

        $tweets = array();
        if (is_array($content)) {
            foreach ($content as $tweet) {
                $order = date("YmdHis", strtotime($tweet->created_at)).'-twitter-'.$channel;
                $tweets[$order] = array(
                    'network' => array(
                        "name" => "twitter",
                        "page" => $channel,
                        "url"  => "https://twitter.com/".$channel,
                    ),
                    "post" => array(
                        "date"  => $tweet->created_at,
                        "title" => "",
                        "text"  => $tweet->text,
                        "url"   => "https://twitter.com/".$channel."/status/".$tweet->id_str,
                    ),
                );
            }
        }

        return $tweets;
    }

    private function getFacebook($channel)
    {
        $fb = new facebook(array(
            'appId' =>  Pelican::$config['FACEBOOK']['appId'], // get this info from the facebook developers page
            'secret' =>  Pelican::$config['FACEBOOK']['secret'], // by registering an app
            'oauth' =>  true,
        ));
        if (isset(Pelican::$config['PROXY']) && !empty(Pelican::$config['PROXY'])) {
            $fb::$CURL_OPTS[CURLOPT_PROXYTYPE] = CURLPROXY_HTTP;
            $fb::$CURL_OPTS[CURLOPT_PROXY] = Pelican::$config['PROXY']['URL'];
            $fb::$CURL_OPTS[CURLOPT_PROXYPORT] = Pelican::$config['PROXY']['PORT'];
            $fb::$CURL_OPTS[CURLOPT_PROXYUSERPWD] = sprintf('%s:%s', Pelican::$config['PROXY']['LOGIN'], Pelican::$config['PROXY']['PWD']);
        }
        $response = $fb->api('/'.$channel.'/'); // replace "spreetable" with your fb page name or username

        $aData = $fb->api(
            '/'.$response['id'].'/posts',
            'GET',
            array(
                'limit' => 20,
                'fields' => 'id,message,type,name,link,created_time,picture,object_id',
            )
        );

        if ($aData['data']) {
            foreach ($aData['data'] as $data) {
                if ($data['type'] != 'status') {
                    $order = date("YmdHis", strtotime($data['created_time'])).'-facebook-'.$channel;

                    /*
                     * Récupération d'une image de meilleur qualite
                     */
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'http://graph.facebook.com/'.$data['object_id']);
                    if (isset(Pelican::$config['PROXY']) && !empty(Pelican::$config['PROXY'])) {
                        curl_setopt($ch, CURLOPT_PROXY, Pelican::$config['PROXY']['URL'].":".Pelican::$config['PROXY']['PORT']."");
                        curl_setopt($ch, CURLOPT_PROXYUSERPWD, "".Pelican::$config['PROXY']['LOGIN'].":".Pelican::$config['PROXY']['PWD']."");
                    }
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                    $sXml = json_decode(curl_exec($ch));

                    //$aPicture = $fb->api('/'.$data['object_id']);
                    $picture = '';
                    if ($sXml->images) {
                        foreach ($sXml->images as $image) {
                            $aImages[$image->width] = $image->source;
                        }

                        ksort($aImages);
                        foreach ($aImages as $width => $source) {
                            if ($width > 300) {
                                $picture = $source;
                                break;
                            }
                        }
                        if ($picture == '') {
                            $picture = $aImages[$width];
                        }
                        unset($aImages);
                    } else {
                        $picture = $data['picture'];
                    }

                    $post[$order] = array(
                        'network' => array(
                            "name" => "facebook",
                            "page" => $response['name'],
                            "url"  => $response['link'],
                        ),
                        "post" => array(
                            "date"  => date('r', strtotime($data['created_time'])),
                            "title" => $data['name'],
                            "text"  => $data['message'],
                            "media" => $picture,
                            "url"   => $data['link'],
                        ),
                    );
                }
            }
        }

        return $post;
    }

    private function getInstagram($channel)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://widget.stagram.com/rss/n/'.$channel.'/');
        if (isset(Pelican::$config['PROXY']) && !empty(Pelican::$config['PROXY'])) {
            curl_setopt($ch, CURLOPT_PROXY, Pelican::$config['PROXY']['URL'].":".Pelican::$config['PROXY']['PORT']."");
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, "".Pelican::$config['PROXY']['LOGIN'].":".Pelican::$config['PROXY']['PWD']."");
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $xml = curl_exec($ch);
        if (strpos($xml, '<?xml') !== false) {
            $xml = simplexml_load_string($xml);
            if ($xml->channel->item) {
                foreach ($xml->channel->item as $entry) {
                    $order = date("YmdHis", strtotime($entry->pubDate->__toString())).'-pinterest-'.$channel;

                    $media = '';
                    if (preg_match_all('#src="([^"]+)"#', $entry->description, $matches)) {
                        $media = $matches[1][1];
                    }

                    $post[$order] = array(
                        'network' => array(
                            "name" => "instagram",
                            "page" => '@'.$channel,
                            "url"  => 'http://widget.stagram.com/rss/n/'.$channel.'/',
                        ),
                        "post" => array(
                            "date"  => $entry->pubDate->__toString(),
                            "title" => $entry->title->__toString(),
                            "text"  => '',
                            "media" => $entry->image->url->__toString(),
                            "url"   => $entry->link->__toString(),
                        ),
                    );
                }
            }
        }

        return $post;
    }

    private function getPinterest($channel)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://www.pinterest.com/'.$channel.'/feed.rss');
        //curl_setopt($ch, CURLOPT_URL, 'https://api.pinterest.com/v3/pidgets/users/'.$channel.'/pins/');
        if (isset(Pelican::$config['PROXY']) && !empty(Pelican::$config['PROXY'])) {
            curl_setopt($ch, CURLOPT_PROXY, Pelican::$config['PROXY']['URL'].":".Pelican::$config['PROXY']['PORT']."");
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, "".Pelican::$config['PROXY']['LOGIN'].":".Pelican::$config['PROXY']['PWD']."");
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $xml = curl_exec($ch);
        /*$json = json_decode(curl_exec($ch));
        if ($json->data->pins) {
            foreach($json->data->pins as $entry) {
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
        }*/
        if (strpos($xml, '<?xml') !== false) {
            $xml = simplexml_load_string($xml);
            if ($xml->channel->item) {
                foreach ($xml->channel->item as $entry) {
                    $order = date("YmdHis", strtotime($entry->pubDate->__toString())).'-pinterest-'.$channel;

                    $media = '';
                    if (preg_match('#src="([^"]+)"#', $entry->description, $matches)) {
                        $media = str_replace('/192x/', '/237x/', $matches[1]);
                    }

                    $post[$order] = array(
                        'network' => array(
                            "name" => "pinterest",
                            "page" => $xml->channel->title->__toString(),
                            "url"  => $xml->channel->link->__toString(),
                        ),
                        "post" => array(
                            "date"  => $entry->pubDate->__toString(),
                            "title" => $entry->title->__toString(),
                            "text"  => strip_tags($entry->description),
                            "media" => $media,
                            "url"   => $entry->guid->__toString(),
                        ),
                    );
                }
            }
        }

        return $post;
    }

    private function getYoutube($channel)
    {

        //$sXml = file_get_contents('http://gdata.youtube.com/feeds/base/users/'.$channel.'/uploads');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://gdata.youtube.com/feeds/base/users/'.$channel.'/uploads');
        if (isset(Pelican::$config['PROXY']) && !empty(Pelican::$config['PROXY'])) {
            curl_setopt($ch, CURLOPT_PROXY, Pelican::$config['PROXY']['URL'].":".Pelican::$config['PROXY']['PORT']."");
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, "".Pelican::$config['PROXY']['LOGIN'].":".Pelican::$config['PROXY']['PWD']."");
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $sXml = curl_exec($ch);
        if (strpos($sXml, '<?xml') !== false) {
            $xml = simplexml_load_string($sXml);

            if ($xml->link) {
                foreach ($xml->link as $link) {
                    $attr = $link->attributes();
                    if ($attr['type']->__toString() == 'text/html') {
                        $page = $attr['href']->__toString();
                    }
                }
            }

            if ($xml->entry) {
                foreach ($xml->entry as $entry) {
                    $order = date("YmdHis", strtotime($entry->published->__toString())).'-youtube-'.$channel;

                    $media = '';
                    if (preg_match('#src="([^"]+)"#', $entry->description, $matches)) {
                        $media = $matches[1];
                    }

                    $url = '';
                    if ($entry->link) {
                        foreach ($entry->link as $link) {
                            $attr = $link->attributes();
                            if ($attr['type']->__toString() == 'text/html') {
                                $url = $attr['href']->__toString();
                            }
                        }
                    }

                    $media = '';
                    if ($url && preg_match('/http:\/\/www[.]youtube[.]com\/watch[?]v=([^&]+)&/', $url, $matches)) {
                        $media = 'http://i.ytimg.com/vi/'.$matches[1].'/0.jpg';
                    }

                    if ($entry->content) {
                        $content = strip_tags($entry->content->__toString());
                        $content = substr($content, 0, strpos($content, 'From'));
                    }

                    $post[$order] = array(
                        'network' => array(
                            "name" => "youtube",
                            "page" => $channel,
                            "url"  => $page,
                        ),
                        "post" => array(
                            "date"  => $entry->published->__toString(),
                            "title" => $entry->title->__toString(),
                            "text"  => $content,
                            "media" => $media,
                            "url"   => $url,
                        ),
                    );
                }
            }
        }

        return $post;
    }
}
