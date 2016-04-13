<?php
include ('config.php');

include (Pelican::$config['LIB_ROOT'] . "/External/feedcreator/feedcreator.class.php");

if (! empty($_GET['pid'])) {
    
    $timestamp = $_SESSION[APP]['SITE_ID'] . '_' . $_SESSION[APP]['LANGUE_ID'] . '_' . (int) $_GET['pid'] . '_' . date('Ymd');
    if (! is_dir(Pelican::$config["CACHE_FW_ROOT"] . '/rss')) {
        mkdir(Pelican::$config["CACHE_FW_ROOT"] . '/rss', 0777, true);
    }
    $cachename = Pelican::$config["CACHE_FW_ROOT"] . '/rss/rss_' . $timestamp . '.xml';
    
    $rss = new UniversalFeedCreator();
    $rss->useCached("", $cachename, 3600);
    
    $page = Pelican_Cache::fetch('Frontend/Page', array(
        $_GET['pid'],
        $_SESSION[APP]['SITE_ID'],
        $_SESSION[APP]['LANGUE_ID'],
        "CURRENT",
        '',
        date('Ymd')
    ));
    
    $child = Pelican_Cache::fetch('Frontend/Page/Childall', array(
        $_GET['pid'],
        $_SESSION[APP]['SITE_ID'],
        $_SESSION[APP]['LANGUE_ID']
    ));
    
    if (! empty($child) && ! empty($page)) {
        
        $rss->title = Pelican::$config["SITE"]['SITE_LABEL'];
        $rss->description = $page['PAGE_META_TITLE'];
        $rss->_feed->setEncoding('utf-8');
        $rss->encoding = 'utf-8';
        
        // optional
        // $rss->descriptionTruncSize = 500;
        // $rss->descriptionHtmlSyndicated = true;
        
        $rss->link = 'http://' . Pelican::$config['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        // $rss->syndicationURL = "http://www.dailyphp.net/" . $_SERVER["PHP_SELF"];
        
        /*
         * $image = new FeedImage(); $image->title = "dailyphp.net logo"; $image->url = "http://www.dailyphp.net/images/logo.gif"; $image->link = "http://www.dailyphp.net"; $image->description = "Feed provided by dailyphp.net. Click to visit."; // optional $image->descriptionTruncSize = 500; $image->descriptionHtmlSyndicated = true; $rss->image = $image;
         */
        
        // get your news items from somewhere, e.g. your database:
        foreach ($child as $array) {
            foreach ($array as $data) {
                if ($data['TYPE'] == 'PAGE') {
                    $item = new FeedItem();
                    $item->title = $data['TITLE'];
                    $item->link = 'http://' . Pelican::$config['HTTP_HOST'] . $data['URL'];
                    $item->description = $data['RSS_DESCRIPTION'];
                    
                    // optional
                    // $item->descriptionTruncSize = 500;
                    // $item->descriptionHtmlSyndicated = true;
                    
                    // $item->date = $data->newsdate;
                    // $item->source = "http://www.dailyphp.net";
                    $item->author = Pelican::$config["SITE"]['SITE_LABEL'];
                    
                    $rss->addItem($item);
                }
            }
        }
        
        // valid format strings are: RSS0.91, RSS1.0, RSS2.0, PIE0.1 (deprecated),
        // MBOX, OPML, ATOM, ATOM0.3, HTML, JS
        echo $rss->saveFeed("RSS1.0", $cachename);
    }
}