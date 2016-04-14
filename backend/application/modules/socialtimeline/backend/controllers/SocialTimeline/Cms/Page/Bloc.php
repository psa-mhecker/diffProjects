<?php

class SocialTimeline_Cms_Page_Bloc extends Cms_Page_Module
{
    public static function render(Pelican_Controller $controller)
    {
        $values = (array) json_decode($controller->values['ZONE_TEXTE']);
        if (! $values) {
            $values['showSocialIcons'] = 1;
            $values['showLayout'] = 1;
            $values['addColorbox'] = 1;
            $values['total'] = 20;
            $values['limit'] = 6;
        }

        $return = $controller->oForm->createInput($controller->multi."twitter", t('Twitter Username'), 255, "", false, $values['twitter'], $controller->readO, 50, false);
        $return .= $controller->oForm->createInput($controller->multi."twitter_hash", t('Twitter Hashtag'), 255, "", false, $values['twitter_hash'], $controller->readO, 50, false);
        $return .= $controller->oForm->createInput($controller->multi."youtube", t('Youtube Username'), 255, "", false, $values['youtube'], $controller->readO, 50, false);
        $return .= $controller->oForm->createInput($controller->multi."youtube_search", t('Youtube Search'), 255, "", false, $values['youtube_search'], $controller->readO, 50, false);
        $return .= $controller->oForm->createInput($controller->multi."facebook_page", t('Facebook Page ID'), 255, "", false, $values['facebook_page'], $controller->readO, 50, false);
        $return .= $controller->oForm->createInput($controller->multi."instagram", t('Instagram'), 255, "", false, $values['instagram'], $controller->readO, 50, false);
        $return .= $controller->oForm->createInput($controller->multi."instagram_hash", t('Instagram Hashtag'), 255, "", false, $values['instagram_hash'], $controller->readO, 50, false);
        $return .= $controller->oForm->createInput($controller->multi."delicious", t('Delicious Username'), 255, "", false, $values['delicious'], $controller->readO, 50, false);
        $return .= $controller->oForm->createInput($controller->multi."flickr", t('Flickr User ID'), 255, "", false, $values['flickr'], $controller->readO, 50, false);
        $return .= $controller->oForm->createInput($controller->multi."flickr_hash", t('Flickr Hashtag'), 255, "", false, $values['flickr'], $controller->readO, 50, false);
        $return .= $controller->oForm->createInput($controller->multi."tumblr", t('Tumblr Username'), 255, "", false, $values['tumblr'], $controller->readO, 50, false);
        $return .= $controller->oForm->createInput($controller->multi."dribbble", t('Dribbble Username'), 255, "", false, $values['dribbble'], $controller->readO, 50, false);
        $return .= $controller->oForm->createInput($controller->multi."digg", t('Digg Username'), 255, "", false, $values['digg'], $controller->readO, 50, false);
        $return .= $controller->oForm->createInput($controller->multi."pinterest", t('Pinterest Username'), 255, "", false, $values['pinterest'], $controller->readO, 50, false);
        $return .= $controller->oForm->createInput($controller->multi."vimeo", t('Vimeo Username'), 255, "", false, $values['vimeo'], $controller->readO, 50, false);
        $return .= $controller->oForm->createInput($controller->multi."google", t('Google+ url'), 500, "", false, $values['google'], $controller->readO, 50, false);
        $return .= $controller->oForm->createInput($controller->multi."rss", 'Flux RSS', 255, "", false, $values['rss'], $controller->readO, 50, false);
        /*
         * $return = $controller->oForm->createInput ( $controller->multi . "layoutMode"> <option value="timeline" selected="selected">Timeline</option> <option value="columns">Columns</option> <option value="one_column">One Column</option> </select>
         */

        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."showSocialIcons", t('Show Icons'), array(
            "1" => "",
        ), $values['showSocialIcons'], false, $controller->readO, "h");
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."showFilter", t('Show Filter'), array(
            "1" => "",
        ), $values['showFilter'], false, $controller->readO, "h");
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."showLayout", t('Show Layout'), array(
            "1" => "",
        ), $values['showLayout'], false, $controller->readO, "h");
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."addColorbox", t('Add Colorbox'), array(
            "1" => "",
        ), $values['addColorbox'], false, $controller->readO, "h");
        $return .= $controller->oForm->createInput($controller->multi."total", 'Nombre total', 5, "", false, $values['total'], $controller->readO, 5, false);
        $return .= $controller->oForm->createInput($controller->multi."limit", 'Limite par flux', 5, "", false, $values['limit'], $controller->readO, 5, false);

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        Pelican_Db::$values['ZONE_TEXTE'] = json_encode(Pelican_Db::$values);
        parent::save($controller);
    }
}
