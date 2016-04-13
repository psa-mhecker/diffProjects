<?php

use Itkg\Apis\Google\Youtube\V3\Youtube;
use Itkg\Authentication\Provider\OAuth2;

class Service_Youtube extends Pelican_Cache {

    var $duration = UNLIMITED;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue() {

        $type = $this->params [0];
        $val = $this->params [1];
        $forMine = $this->params [2];
        $key = Pelican::$config ['youtube']['key'];
        
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $sql = "SELECT y.* FROM  #pref#_youtube y INNER JOIN #pref#_site s ON s.YOUTUBE_ID = y.YOUTUBE_ID WHERE s.SITE_ID = :SITE_ID";
        $aDatas = $oConnection->queryRow($sql, $aBind);
        if (empty($aDatas)) {
            $refreshToken = false;
        }else{
            $refreshToken = $aDatas;
        }

        if ($refreshToken != false) {
            $_SESSION[APP]["refresh_token"] = $refreshToken['TOKEN_ID'];
            $oauth = new OAuth2();
            $oauth->setParameters(Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['authentication_provider']['PARAMETERS']);
            $oauth->setIsRedirect(false);
            $isShowList = false;
            if ($oRefreshTokenData = $oauth->authenticateRefreshToken()) {
                $_SESSION[APP]["ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE"]["OAuth2"]["access_token"] = $oRefreshTokenData->getAccessToken();
                $isShowList = true;
            }
        }
        switch ($type) {
            case 'user' : {
                    $users = explode(',', $val);

                    //pas d'oauth necessaire car liste souhaitee pour un user defini
                    $tmpAuthent = Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['authentication_provider'];
                    unset(Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['authentication_provider']);
                    foreach ($users as $id) {

                        $aDatas = array('part' => 'id', 'forUsername' => $id, 'access_token' => $_SESSION[APP]["ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE"]["OAuth2"]["access_token"]);
                        //$aDatas = array('part' => 'id', 'forUsername' => $id);
                        Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['PARAMETERS']['uri'] = '/youtube/v3/channels';
                        $oService = Itkg\Service\Factory::getService('ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE');
                        $oResponse = $oService->call('channelsList', $aDatas);
                        if (is_array($oResponse->items) and count($oResponse->items) > 0) {
                            Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['PARAMETERS']['uri'] = '/youtube/v3/search';
                            $oService = Itkg\Service\Factory::getService('ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE');
                            foreach ($oResponse->items as $cur_channel) {

                                if ($cur_channel->getId()) {
                                    $pageToken = '';
                                    do {
                                        //$aDatasSearch = array('part' => 'snippet', 'type' => 'video', 'channelId' => $cur_channel->getId(), 'key' => $key, 'order' => 'date', 'maxResults' => '20', 'pageToken' => $pageToken);
                                        $aDatasSearch = array('part' => 'snippet', 'type' => 'video', 'access_token' => $_SESSION[APP]["ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE"]["OAuth2"]["access_token"], 'channelId' => $cur_channel->getId(), 'order' => 'date', 'maxResults' => '20', 'pageToken' => $pageToken);
                                        $oVideo = $oService->call('searchList', $aDatasSearch);
                                        $pageToken = $oVideo->nextPageToken;
                                        if (is_array($oVideo->items) && !empty($oVideo->items)) {
                                            foreach ($oVideo->items as $video) {
                                                $list[] = array(
                                                    'title' => $video->snippet->title,
                                                    'name' => $video->snippet->title,
                                                    'published_at' => $video->snippet->publishedAt,
                                                    'description' => $video->snippet->description,
                                                    'viewCount' => '',
                                                    'url' => '',
                                                    'path' => $video->snippet->thumbnails->high->getUrl(),
                                                    'time' => '',
                                                    'width' => '',
                                                    'height' => '',
                                                    'id' => $video->id,
                                                    'status' => array('privacyStatus' => 'public')
                                                );
                                            }
                                        }
                                    } while ($pageToken != '');
                                }
                            }
                        }
                    }

                    // reassignation de l'authent pour eventuels traitements ulterieurs
                    Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['authentication_provider'] = $tmpAuthent;

                    // forMine permet de recuperer les videos du user qui ne remontent pas dans les chaines car ne sont pas au status 'public'
                    if ($forMine != "") {
                        Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['PARAMETERS']['uri'] = '/youtube/v3/search';
                        $oService = Itkg\Service\Factory::getService('ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE');
                        $pageToken = '';
                        do {
                            $aDatasSearch = array('part' => 'id', 'type' => 'video', 'forMine' => 'true', 'access_token' => $_SESSION[APP]["ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE"]["OAuth2"]["access_token"], 'order' => 'date', 'maxResults' => '20', 'pageToken' => $pageToken);
                            $oVideo = $oService->call('searchList', $aDatasSearch);
                            $pageToken = $oVideo->nextPageToken;
                            if (is_array($oVideo->items) && !empty($oVideo->items)) {
                                foreach ($oVideo->items as $video) {
                                    $listIdForMine[] = $video->id;
                                }
                            }
                        } while ($pageToken != '');
                    }
                    // on recupere les datas des videos, pour ne garder que celles aux status non public (deja remontees via les chaines)
                    if (is_array($listIdForMine) && count($listIdForMine) > 0) {
                        Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['PARAMETERS']['uri'] = '/youtube/v3/videos';
                        $oService = Itkg\Service\Factory::getService('ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE');

                        $nbParLot = 50; // on limite et on boucle pour que le GET vers l'API soit < 2048 chars

                        for ($i = 0; $i < count($listIdForMine); $i = $i + $nbParLot) {
                            $alistIdForMineSlice = array_slice($listIdForMine, $i, $nbParLot);
                            $val = implode(',', $alistIdForMineSlice);
                            $pageToken = '';
                            do {
                                //$aDatas = array('part'=>'snippet,status', 'id'=>$val,'key'=>$key, 'maxResults'=> $nbParLot, 'pageToken' => $pageToken);
                                $aDatas = array('part' => 'snippet,status', 'id' => $val, 'access_token' => $_SESSION[APP]["ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE"]["OAuth2"]["access_token"], 'maxResults' => $nbParLot, 'pageToken' => $pageToken);
                                $oVideo = $oService->call('videosList', $aDatas);
                                $pageToken = $oVideo->nextPageToken;
                                if (is_array($oVideo->items) && !empty($oVideo->items)) {
                                    foreach ($oVideo->items as $video) {
                                        if ($oVideo->status->privacyStatus != 'public') {
                                            $list[] = array(
                                                'title' => $video->snippet->title,
                                                'name' => $video->snippet->title,
                                                'published_at' => $video->snippet->publishedAt,
                                                'description' => $video->snippet->description,
                                                'viewCount' => '',
                                                'url' => '',
                                                'path' => $video->snippet->thumbnails->high->getUrl(),
                                                'time' => '',
                                                'width' => '',
                                                'height' => '',
                                                'id' => $video->id,
                                                'status' => array(
                                                    'privacyStatus' => $video->status->privacyStatus,
                                                    'license' => $video->status->license
                                                )
                                            );
                                        }
                                    }
                                }
                            } while ($pageToken != '');
                        }
                    }
                    break;
                }
            case 'id' : {
                    Itkg::$config['ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE']['PARAMETERS']['uri'] = '/youtube/v3/videos';
                    $oService = Itkg\Service\Factory::getService('ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE');

                    //$aDatas = array('part'=>'snippet,contentDetails,statistics,player,status,recordingDetails', 'id'=>$val,'key'=>$key);
                    $aDatas = array('part' => 'snippet,contentDetails,statistics,player,status,recordingDetails', 'access_token' => $_SESSION[APP]["ITKG_APIS_GOOGLE_YOUTUBE_V3_YOUTUBE"]["OAuth2"]["access_token"], 'id' => $val);
                    $oResponse = $oService->call('videosList', $aDatas);
                    if (is_array($oResponse->items)) {
                        $oVideo = $oResponse->items[0];
                        if ($oVideo != "") {
                            $list = array(
                                'title' => $oVideo->snippet->title,
                                'name' => $oVideo->snippet->title,
                                'description' => $oVideo->snippet->description,
                                'viewCount' => $oVideo->statistics->viewCount,
                                'url' => $oVideo->player->embedHtmlUrl,
                                'path' => $oVideo->snippet->thumbnails->high->getUrl(),
                                'time' => $oVideo->contentDetails->duration,
                                'width' => $oVideo->player->embedHtmlWidth,
                                'height' => $oVideo->player->embedHtmlHeight,
                                'id' => $oVideo->id,
                                'date' => $oVideo->snippet->publishedAt,
                                'categoryId' => $oVideo->snippet->categoryId,
                                'channel' => array('id' => $oVideo->snippet->channelId, 'title' => $oVideo->snippet->channelTitle),
                                'status' => array('privacyStatus' => $oVideo->status->privacyStatus, 'license' => $oVideo->status->license),
                                'recordingDetails' => array(
                                    'locationDescription' => $oVideo->recordingDetails->locationDescription,
                                    'location' => array('latitude' => $oVideo->recordingDetails->location->latitude,
                                        'longitude' => $oVideo->recordingDetails->location->longitude
                                    ),
                                    'recordingDate' => $oVideo->recordingDetails->recordingDate
                                )
                            );
                        }
                    }
                    break;
                }
        }

        $this->value = $list;
    }

}
