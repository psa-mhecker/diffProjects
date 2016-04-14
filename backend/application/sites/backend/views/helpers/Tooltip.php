<?php
/**
 * Classe de gestion des tooltips du Pelican_Index_Backoffice.
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 * @since 18/03/2015
 */
class Backoffice_Tooltip_Helper
{
    public static $showTooltip=false;

    /**
     *
     * @param string $text
     * @param string $src
     *
     * @return string
     */
    public static function init($text, $src)
    {
        $retour= Pelican_Html::img(
          array(
              'border' => "0",
              'src' => $src,
              'class'=>'tooltip',
              'title'=> $text
          )
        );
        self::$showTooltip=true;

        return $retour;
    }
    /**
     * Création d'un Tooltip Help
     * @param string $text
     *
     * @return string
     */
    public static function help($text)
    {
       return self::init($text,Pelican::$config["MEDIA_HTTP"]."/design/backend/images/silk/help.png");
    }
}
