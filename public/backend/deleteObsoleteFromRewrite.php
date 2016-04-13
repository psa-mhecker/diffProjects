<?php
/**
 * Created by PhpStorm.
 * User: kmessaoudi
 * Date: 18/02/14
 * Time: 10:56
 */

include("config.php");
$connection = Pelican_Db::getInstance();

$bind[':SITE_BO'] = Pelican::$config['SITE_BO'];

$sql = "
    SELECT
        *
    FROM
        #pref#_site
    WHERE
        SITE_ID <> :SITE_BO
";
$sites = $connection->queryTab($sql,$bind);
if(is_array($sites) && count($sites)>0){
    foreach($sites as $site){
        $bindSite[':SITE_ID'] = $site['SITE_ID'];
        //Pages
        $sql = "
            SELECT
                *
            FROM
                #pref#_rewrite
            WHERE
                SITE_ID = :SITE_ID
            and PAGE_ID not in (
                SELECT
                  PAGE_ID
                FROM
                  #pref#_page
            )
        ";
        $pages = $connection->queryTab($sql,$bindSite);
        if(is_array($pages) && count($pages)>0){
            foreach($pages as $page){
                $bindPage[':PAGE_ID'] = $page['PAGE_ID'];
                $sql = "
                        DELETE FROM
                          #pref#_rewrite
                        WHERE
                          PAGE_ID = :PAGE_ID
                    ";
                $connection->query($sql,$bindPage);
                echo '--- PAGE --- '.$page['PAGE_ID'].' --- SUPPRIMEE de la table REWRITE';
            }
        }
        //Contents
        $sql = "
            SELECT
                *
            FROM
                #pref#_rewrite
            WHERE
                SITE_ID = :SITE_ID
            and CONTENT_ID not in (
                SELECT
                  CONTENT_ID
                FROM
                  #pref#_content
            )
        ";
        $contents = $connection->queryTab($sql,$bindSite);
        if(is_array($contents) && count($contents)>0){
            foreach($contents as $content){
                $bindContent[':CONTENT_ID'] = $content['CONTENT_ID'];
                $sql = "
                        DELETE FROM
                          #pref#_rewrite
                        WHERE
                          PAGE_ID = :PAGE_ID
                    ";
                $connection->query($sql,$bindContent);
                echo '--- CONTENU --- '.$content['CONTENT_ID'].' --- SUPPRIME de la table REWRITE';
            }
        }
    }
}



