<?php

use Itkg\Apis\Streamlike\V2\Streamlike;

class Service_Streamlike extends Pelican_Cache
{
    public $duration = UNLIMITED;

    public static function getStreamlikeCachetime($streamlikeCachetimeInterval = 1)
    {
        if($streamlikeCachetimeInterval < 1) {
            // si cache non sette ou < à une minute -> à la seconde
            $result = time();
        } else {
            $result = round((time()/(60*$streamlikeCachetimeInterval)), 0, PHP_ROUND_HALF_DOWN);
        }

        return $result;
    }

    public static function getStreamlikeConfig()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $sql = "SELECT STREAMLIKE_COMPANY_ID, STREAMLIKE_CACHETIME FROM #pref#_site WHERE SITE_ID = :SITE_ID";
        $result = $oConnection->queryRow($sql, $aBind);

        return $result;
    }

    public function getValue()
    {
        $type = $this->params[0];
        $companyId = $this->params[1];
        $mediaId = $this->params[2];
        $playlistId = $this->params[3];
        $query = $this->params[4];
        $list = array();

        switch ($type) {
            case 'list' : {
                $aDatas = array('companyId' => $companyId, 'playlistId' => $playlistId, 'query' => $query);

                try {
                    Itkg::$config['ITKG_APIS_STREAMLIKE_V2_STREAMLIKE']['PARAMETERS']['uri'] = '/ws/playlist';
                    $oService = Itkg\Service\Factory::getService('ITKG_APIS_STREAMLIKE_V2_STREAMLIKE');
                    $oResponse = $oService->call('playlist', $aDatas);
                    if (is_array($oResponse->playlist->medias) and count($oResponse->playlist->medias) > 0) {
                       foreach ($oResponse->playlist->medias as $video) {
                           $list[] = array(
                            'title' => $video->metadata['global']['name'],
                            'name' => $video->metadata['global']['name'],
                            'published_at' => '',
                            'description' => $video->metadata['global']['description'],
                            'viewCount' => '',
                            'url' => '',
                            'path' => $video->metadata['customization']['cover']['thumbnaillarge_url'],
                            'time' => '',
                            'width' => '',
                            'height' => '',
                            'id' => $video->metadata['global']['media_id'],
                            'status' => array(),
                          );
                       }

                    }
                } catch (Exception $e) {
                }
                break;
            }
            case 'id':
            {
                $aDatas = array('mediaId' => $mediaId);
                try {
                    Itkg::$config['ITKG_APIS_STREAMLIKE_V2_STREAMLIKE']['PARAMETERS']['uri'] = '/ws/media';
                    $oService = Itkg\Service\Factory::getService('ITKG_APIS_STREAMLIKE_V2_STREAMLIKE');
                    $rs = $oService->call('media', $aDatas);
                    $video = $rs->media;

                    if ($video != "") {
                         $list = array(
                            'title' => $video->metadata['global']['name'],
                            'name' => $video->metadata['global']['name'],
                            'description' => $video->metadata['global']['description'],
                            'credits' => $video->metadata['global']['credits'],
                            'url' => $video->metadata['share']['universal_url'],
                            'path' => $video->metadata['customization']['cover']['thumbnaillarge_url'],
                            'time' => $video->metadata['global']['duration'],
                            'ratio' => $video->metadata['global']['ratio'],
                            'id' => $video->metadata['global']['media_id'],
                            'creation_date' => $video->metadata['global']['creation_date'],
                            'lastupdated_date' => $video->metadata['global']['lastupdated_date'],
                            'visibility' => $video->metadata['global']['visibility'],

                            );
                    }
                } catch (Exception $e) {
                }

                break;
            }
        }

        $this->value = $list;
    }
}
