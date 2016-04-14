<?php

class SocialTimeline_Cms_Page_Bloc_Controller extends Pelican_Controller_Front
{
    public function indexAction()
    {
        if ($this->getParam('ZONE_TEXTE')) {
            $mediaPath = Pelican_Plugin::getMediaPath("socialtimeline");
            $values = (array) json_decode($this->getParam('ZONE_TEXTE'));

            $custom = '';
            if ($values['rss']) {
                $tmp = explode('=>', $values['rss']);
                if (is_array($tmp)) {
                    $parse = parse_url($tmp[1]);
                    $host = $parse['host'];

                    $custom = "custom:
            {
                'rss': {
                    name: '".$tmp[0]."',
                    url: '".$tmp[1]."',
                    icon: '".$values['favicon']."',
                    limit: 6
                }
            },";
                }
            }

            $head = $this->getView()->getHead();
            $head->setJquery('');
            //$head->setJs($mediaPath . 'js/jquery.colorbox.min.js');
            $head->setJs($mediaPath.'js/jquery.isotope.min.js');
            $head->setJs($mediaPath.'js/jquery.dpSocialTimeline.js');
            $head->setCss($mediaPath.'css/styles.css');
            $head->setCss($mediaPath.'css/colorbox.css');
            $head->setCss($mediaPath.'css/dpSocialTimeline.css');

            $providers = array(
                'delicious',
                'digg',
                'dribbble',
                'facebook_page',
                'instagram',
                'instagram_hash',
                'flickr',
                'flickr_hash',
                'pinterest',
                'tumblr',
                'vimeo',
                'youtube',
                'youtube_search',
                'google',
            );

            foreach ($providers as $provider) {
                if ($values[$provider]) {
                    $feeds[] = "'".$provider."': {data: '".$values[$provider]."', limit: ".$values['limit']."}";
                }
            }

            if ($values['twitter']) {
                $feeds[] = "'twitter': {data: '".Pelican::$config["MODULES_HTTP"].'/socialtimeline/twitter_oauth/user_timeline.php?screen_name='.$values['twitter']."', limit: ".$values['limit']."}";
            }

            if ($values['twitter_hash']) {
                $feeds[] = "'twitter_hash': {data: '".Pelican::$config["MODULES_HTTP"].'/socialtimeline/twitter_oauth/search.php?q=%23'.urlencode($values['twitter_hash'])."', limit: ".$values['limit']."}";
            }

            $jsFeeds = "jQuery('#socialTimeline').dpSocialTimeline({
            feeds:
			{".implode(',', $feeds)."},
            ".$custom."
            layoutMode: 'columns',
            showSocialIcons: ".($values['showSocialIcons'] ? 'true' : 'false').",
            showFilter: ".($values['showFilter'] ? 'true' : 'false').",
            showLayout: ".($values['showLayout'] ? 'true' : 'false').",
            addColorbox: ".($values['addColorbox'] ? 'true' : 'false').",
            itemWidth: 200,
            total: ".$values['total']."
			});";

            $head->endScript($jsFeeds);

            // $this->setParam ( 'ZONE_TITRE', 'titre' );
            $this->assign("mediaPath", $mediaPath);
            $this->assign('data', $this->getParams());
            $this->fetch();
        }
    }
}
