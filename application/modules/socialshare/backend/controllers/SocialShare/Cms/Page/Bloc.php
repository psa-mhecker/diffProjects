<?php

class SocialShare_Cms_Page_Bloc extends Cms_Page_Module
{

    public static function render (Pelican_Controller $controller)
    {
        require_once (Pelican::$config['PLUGIN_ROOT'] . '/socialshare/library/SocialShare.php');
        
        $head = Pelican_View::getInstance()->getHead();
        $head->setJquery('ui.sortable');
        $head->setJquery('effects');
        $head->setJquery('effects.transfer');
        
        $aLike = array(
            'facebook_like' => 'Facebook',
            'twitter_tweet' => 'Twitter',
            'google_plusone' => 'Google+'
        );
        $aShare = array(
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'google_plus_share' => 'Google+',
            'linkedin' => 'Linkedin'
        );
        
        $tmp = (array) json_decode($controller->zoneValues["ZONE_TEXTE2"]);
        $controller->zoneValues["LIKE"] = $tmp['LIKE'];
        $controller->zoneValues["SHARE"] = $tmp['SHARE'];
        
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi . "LIKE", 'Boutons like', $aLike, $controller->zoneValues["LIKE"], false, $controller->readO, "h");
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi . "SHARE", 'Boutons partage', $aShare, $controller->zoneValues["SHARE"], false, $controller->readO, "h");
        
        $temp = array_keys(Plugin_SocialShare::getBookmarks());
        $asocialshares = array_combine($temp, $temp);
        
        $return .= $controller->oForm->createHidden($controller->multi . "ZONE_TEXTE", $controller->zoneValues["ZONE_TEXTE"]);
        
        $return .= $controller->oForm->createCheckboxFromList($controller->multi . "ZONE_TITRE2", "Afficher le nom du service", array(
            1 => "Oui"
        ), $controller->zoneValues["ZONE_TITRE2"], false, $controller->readO, "1", false, "", true);
        $return .= $controller->oForm->createJs("getSelected('" . $controller->multi . "ZONE_TEXTE');");
        
        $head->setIncludeHeader(Pelican::$config['PLUGIN_ROOT'] . '/socialshare/public/css/bookmark.css.php');
        
        $head->setCss(Pelican_Plugin::getMediaPath("socialshare") . "css/bookmark_sprite.css");
        
        $echo = '';
        if ($controller->zoneValues["ZONE_TEXTE"]) {
            $echo .= Pelican_Html::script(array(), "var plugin_bookmark_values = '" . $controller->zoneValues["ZONE_TEXTE"] . "'.split('#');");
        } else {
            $echo .= Pelican_Html::script(array(), "var plugin_bookmark_values = '';");
        }
        $echo .= Pelican_Html::script(array(
            src => Pelican_Plugin::getMediaPath("socialshare") . "js/assoc_sortable.js"
        ));
        
        foreach (Plugin_SocialShare::$bookmark as $book) {
            $li[] = Pelican_Html::li(array(
                id => $book[0]
            ), Pelican_Html::img(array(
                "class" => "plugin_bookmark_" . $book[1],
                src => Pelican_Plugin::getMediaPath("socialshare") . "images/pixel.gif",
                alt => "",
                height => "16",
                width => "16"
            )), $book[2]);
        }
        $ul = Pelican_Html::ul(array(
            id => "selectable",
            "class" => "control_list"
        ), implode('', $li));
        
        $tmp = Pelican_Html::div(array(
            "class" => "socialshares selected_chooser tgthr"
        ), Pelican_Html::h3('Services disponibles') . Pelican_Html::div(array(
            "class" => "socialshares_content"
        ), $ul));
        
        $tmp .= Pelican_Html::div(array(
            style => "top: 0px;",
            "class" => "socialshares tgthr",
            id => "selected_confirmation_box"
        ), Pelican_Html::h3('Services sélectionnés') . Pelican_Html::div(array(
            "class" => "socialshares_content"
        ), Pelican_Html::div(array(), '( Sélectionner des services ! )') . Pelican_Html::ul(array(
            id => "sortable",
            "class" => "control_list ui-sortable"
        ), '')));
        $echo .= Pelican_Html::div(array(
            "class" => ""
        ), $tmp);
        
        $return .= Pelican_Html::tr(array(), Pelican_Html::td(array(
            colspan => 2
        ), $echo));
        
        return $return;
    }

    public static function save (Pelican_Controller $controller)
    {
        Pelican_Db::$values['ZONE_TEXTE2'] = json_encode(array(
            'LIKE' => Pelican_Db::$values['LIKE'],
            'SHARE' => Pelican_Db::$values['SHARE']
        ));
        parent::save($controller);
    }
}
