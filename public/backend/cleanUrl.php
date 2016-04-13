<?php
/** nettoyage des urls en /fr pour les sites en fr monolingues
 * 
 */
include_once 'config.php';

if (! $_SESSION[APP]["user"]["id"]) {
    echo ("Veuillez vous identifier en Back Office");
    exit();
}

if (! empty($_GET['lang'])) {
    if ($_GET['lang'] == 'all') {
        $oConnection = Pelican_Db::getInstance();
        $sql = 'select distinct langue_code from psa_site_language sl inner join psa_language l on (l.langue_id=sl.langue_id) group by site_id having count(*) = 1';
        $result = $oConnection->queryTab($sql);
        var_dump($result);
        foreach ($result as $lang) {
            cleanLang($lang['langue_code']);
        }
    } else {
        cleanLang($_GET['lang']);
    }
}

function cleanLang ($lang)
{
    echo '<br /><b>-------------- Nettoyage des urls en /' . $lang . '/</b><br />';
    $langueCode = Pelican_Cache::fetch('LanguageCode');
    if (! empty($langueCode[$lang])) {
        $oConnection = Pelican_Db::getInstance();
        
        // recherche des sites monolingues français
        $mono = "select site_id  from psa_site_language where site_id in (select site_id from psa_site_language where langue_id=" . $langueCode[$lang] . ") group by site_id having count(*) = 1";
        $oConnection->query($mono);
        $sites = $oConnection->data['site_id'];
        if (! empty($sites)) {
            var_dump($sites);
            
            $base = "update psa_%table%_version set %TABLE%_CLEAR_URL = REPLACE(%TABLE%_CLEAR_URL,'/" . $lang . "/','/') where %TABLE%_CLEAR_URL like '/" . $lang . "/%' and %TABLE%_ID in (select %TABLE%_ID from psa_%table% where site_id in (" . implode(',', $sites) . "))";
            $base2 = "update psa_%table% set %field% = REPLACE(%field%,'/" . $lang . "/','/') where %field% like '/" . $lang . "/%' and PAGE_ID in (select PAGE_ID from psa_page where site_id in (" . implode(',', $sites) . "))";
            $baseSite = "update psa_%table% set %field% = REPLACE(%field%,'/" . $lang . "/','/') where %field% like '/" . $lang . "/%' and site_id in (" . implode(',', $sites) . ")";
            
            // Page
            $sql = str_replace(array(
                '%table%',
                '%TABLE%'
            ), array(
                'page',
                'PAGE'
            ), $base);
            doSql($oConnection, $sql);
            
            // Content
            $sql = str_replace(array(
                '%table%',
                '%TABLE%'
            ), array(
                'content',
                'CONTENT'
            ), $base);
            doSql($oConnection, $sql);
            
            // PAGE_META_URL_CANONIQUE
            $sql = str_replace(array(
                '%table%',
                '%field%'
            ), array(
                'page_version',
                'PAGE_META_URL_CANONIQUE'
            ), $base2);
            doSql($oConnection, $sql);
            
            // Navigation
            $sql = str_replace(array(
                '%table%',
                '%field%'
            ), array(
                'navigation',
                'NAVIGATION_URL'
            ), $base2);
            doSql($oConnection, $sql);
            
            // barre_outils
            $sql = str_replace(array(
                '%table%',
                '%field%'
            ), array(
                'barre_outils',
                'BARRE_OUTILS_URL_WEB'
            ), $baseSite);
            doSql($oConnection, $sql);
            
            // barre_outils
            $sql = str_replace(array(
                '%table%',
                '%field%'
            ), array(
                'barre_outils',
                'BARRE_OUTILS_URL_MOBILE'
            ), $baseSite);
            doSql($oConnection, $sql);
            
            // barre_outils
            $sql = str_replace(array(
                '%table%',
                '%field%'
            ), array(
                'contenu_recommande',
                'CONTENU_RECOMMANDE_URL'
            ), $baseSite);
            doSql($oConnection, $sql);
            
            // ZONE_TITRE
            $sql = str_replace(array(
                '%table%',
                '%field%'
            ), array(
                'page_zone',
                'ZONE_TITRE'
            ), $base2);
            doSql($oConnection, $sql);
            
            for ($i = 2; $i <= 17; $i ++) {
                $sql = str_replace(array(
                    '%table%',
                    '%field%'
                ), array(
                    'page_zone',
                    'ZONE_TITRE' . $i
                ), $base2);
                doSql($oConnection, $sql);
            }
            
            // PAGE_ZONE_MULTI_URL
            $sql = str_replace(array(
                '%table%',
                '%field%'
            ), array(
                'page_multi_zone_multi',
                'PAGE_ZONE_MULTI_URL'
            ), $base2);
            doSql($oConnection, $sql);
            
            for ($i = 2; $i <= 16; $i ++) {
                $sql = str_replace(array(
                    '%table%',
                    '%field%'
                ), array(
                    'page_multi_zone_multi',
                    'PAGE_ZONE_MULTI_URL' . $i
                ), $base2);
                doSql($oConnection, $sql);
            }
            
            // nettoyage du cache
            $param = '*';
            $dir = Pelican::$config["CACHE_FW_ROOT"];
            $cmd = buildCommand(Pelican::$config["CACHE_FW_ROOT"], $param);
            echo ($cmd);
            passthru($cmd);
        } else {
            echo 'pas de site Ã  traiter';
        }
    } else {
        echo 'langue inconnue';
    }
}

function doSql ($oConnection, $query)
{
    $oConnection->query($query);
    var_dump($query);
    var_dump('lignes modifiees : ' . $oConnection->affectedRows);
}

function buildCommand ($dir, $param, $subdir = false)
{
    $return = '';
    if (Pelican::$config['DOCUMENT_INIT'] != '/' && substr_count($dir, '/') >= 3) {
        if (is_dir($dir)) {
            $return = "rm -rf " . $dir . ($subdir ? '/*' : '') . "/*" . $param . "* &";
            $return = str_replace("\$", "", $return);
        }
    }
    
    return $return;
}
