<?php

class Cms_Content_Common_Comment extends Cms_Content_Module
{

    public static function render(Pelican_Controller $controller)
    {
        return Pelican_Html::iframe(array(
            src => "/_/Index/child?iframe=true&object=" . $controller->object_id . "&object_type=" . $controller->object_type_id . "&comment=true" , 
            name => "iframeComment" , 
            id => "iframeComment" , 
            marginwidth => "0" , 
            marginheight => "0" , 
            frameborder => "0" , 
            width => "100%" , 
            height => "400"));
    }
}