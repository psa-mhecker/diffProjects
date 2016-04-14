<?php

include_once Pelican::$config["TEMPLATE_CACHE_ROOT"] . '/Service/Streamlike.php';


class Backeoffice_Streamlike_Helper
{
    public static function getById($id)
    {
        $streamlikeConfig = Service_Streamlike::getStreamlikeConfig($_SESSION[APP]['SITE_ID']);
        $cachetime = Service_Streamlike::getStreamlikeCachetime($streamlikeConfig['STREAMLIKE_CACHETIME']);
        $details = Pelican_Cache::fetch("Service/Streamlike", array(
            'id',
            null,
            $id,
            null,
            null,
            $cachetime
        ));
        return $details;
    }
}