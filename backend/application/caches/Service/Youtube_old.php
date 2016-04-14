<?php

use Itkg\Apis\Google\Youtube\V3\Search;
use Itkg\Apis\Google\Youtube\V3\Video;
use Itkg\Apis\Google\Youtube\V3\Channels;

class Service_Youtube extends Pelican_Cache
{
    public $duration = UNLIMITED;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $type = $this->params [0];
        $val = $this->params [1];
        $key = Pelican::$config ['youtube']['key'];
        $option = array();
        if (Pelican::$config['PROXY']['URL']) {
            $option = array(
                'CURLOPT_PROXY'          => Pelican::$config['PROXY']['URL'],
                'CURLOPT_PROXYUSERPWD'   => Pelican::$config['PROXY']['LOGIN'].':'.Pelican::$config['PROXY']['PWD'],
                'CURLOPT_SSL_VERIFYPEER' => false,
                'CURLOPT_SSL_VERIFYHOST' => false,
                'CURLOPT_PROXYTYPE' => 'CURLPROXY_HTTP',
            );
        }
        switch ($type) {

            case 'user' :
                {
                    $users = explode(',', $val);
                    //$yt = new Zend_Gdata_YouTube ();
                    $oChannels = new Channels($key, $option);

                    foreach ($users as $id) {
                        /*$videoFeed = $yt->getUserUploads ( $id );
                        foreach ( $videoFeed as $videoEntry ) {
                            $t = $videoEntry->getVideoThumbnails ();
                            $list2 [] = array (
                                'title' => $videoEntry->getVideoTitle (),
                                'name' => $videoEntry->getVideoTitle (),
                                'description' => $videoEntry->getVideoDescription (),
                                'viewCount' => $videoEntry->getVideoViewCount (),
                                'url' => $videoEntry->getFlashPlayerUrl (),
                                'path' => $t [0] ['url'],
                                'time' => $t [0] ['time'],
                                'width' => $t [0] ['width'],
                                'height' => $t [0] ['height'],
                                'id' => $videoEntry->getVideoId ()
                            );
                        }*/

                        $oChannels->getList("id", array(forUsername => $id), array());
                        if ($oChannels->getChannelId()) {
                            $pageToken = '';
                            do {
                                $oSearch = new Search($key, $option);
                                $oVideo = $oSearch->getList("id,snippet", array(channelId => $oChannels->getChannelId()), array(order => 'date', maxResults => 20, pageToken => $pageToken));
                                $pageToken = $oVideo->nextPageToken;
                                if (is_array($oVideo->items) && !empty($oVideo->items)) {
                                    foreach ($oVideo->items as $video) {
                                        $list[] = array(
                                            'title' => $video->snippet->title,
                                            'name' => $video->snippet->title,
                                            'description' => $video->snippet->description,
                                            'viewCount' => '',
                                            'url' =>  '',
                                            'path' =>  $video->snippet->thumbnails->high->url,
                                            'time' =>  '',
                                            'width' =>  '',
                                            'height' =>  '',
                                            'id' =>  $video->id->videoId,
                                        );
                                    }
                                }
                            } while ($pageToken != '');
                        }
                    }

                    break;
                }
            case 'id' :
                {
                    /*$yt = new Zend_Gdata_YouTube ();

                    $videoEntry = $yt->getVideoEntry ( $val );

                    $t = $videoEntry->getVideoThumbnails ();
                    $list = array (
                        'title' => $videoEntry->getVideoTitle (),
                        'name' => $videoEntry->getVideoTitle (),
                        'description' => $videoEntry->getVideoDescription (),
                        'viewCount' => $videoEntry->getVideoViewCount (),
                        'url' => $videoEntry->getFlashPlayerUrl (),
                        'path' => $t [0] ['url'],
                        'time' => $t [0] ['time'],
                        'width' => $t [0] ['width'],
                        'height' => $t [0] ['height'],
                        'id' => $videoEntry->getVideoId (),
                        'date' => $videoEntry->published->text
                    );
                    */
                    $oVideo = new Video($key, $option);
                    $oVideo->getVideo($val);
                    $list = array(
                        'title' => $oVideo->getTitle(),
                        'name' => $oVideo->getTitle(),
                        'description' => $oVideo->getDescription(),
                        'viewCount' => $oVideo->getViewCount(),
                        'url' => $oVideo->getUrl(),
                        'path' => $oVideo->getThumbnail(),
                        'time' => $oVideo->getTime(),
                        'width' => $oVideo->getWidth(),
                        'height' => $oVideo->getHeight(),
                        'id' => $oVideo->getId(),
                        'date' => $oVideo->getDate(),
                    );
                    break;
                }
        }

        $this->value = $list;
    }
}
