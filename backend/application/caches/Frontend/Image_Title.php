<?php
/**
 * Classe de génération de titre en image d'image.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 15/06/2004
 */

/** Fichier de configuration */
include_once 'config.php';

/** Librairie de la mediathèque */
require_once pelican_path('Media');
pelican_import('Media.ImageMagick');

/**
 * Classe permettant la génération d'un titre en image.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 15/06/2004
 */
class Frontend_Image_Title extends Pelican_Cache
{
    public static $storage = 'file';

    /**
     * Méthode de création du fichier de cache.
     */
    public function getValue()
    {
        $text = $this->params[0];
        $width = $this->params[2];
        $height = $this->params[3];
        $font = $this->params[4];
        $pointsize = $this->params[5];
        $back = $this->params[6];
        $color = $this->params[7];

        $im = Pelican_Factory::getInstance('Media.ImageMagick');

        if ($_SERVER['WINDIR']) {
            $color = str_replace("'", "\"", $color);
            $text = Pelican_Text::ansi2asci($text);
        }
        $text = rawurldecode($text);
        $text = stripslashes($text);
        if ($_SERVER['WINDIR']) {
            $text = Pelican_Text::ansi2asci($text);
        }
        $text = explode("<br />", $text);

        $im->setNewFile(str_replace("\\", "/", str_replace("/\\", "/", $this->name)));
        $im->setOpt('antialias', ' ');
        $im->setOpt('quality', escapeshellcmd('90%'));
        $im->setOpt('size', array(
            $width,
            $height * count($text),
        ));
        if ($back) {
            $im->setOpt('xc', $back);
        }
        if ($color) {
            $im->setOpt('fill', $color);
        }
        $im->setOpt('font', $font);
        $im->setOpt('pointsize', $pointsize);
        $im->setOpt('gravity', 'West');

        $im->setOpt('draw', $text);

        $im->margin = 0; //5


        $this->values = $im->create(5);
    }
}
