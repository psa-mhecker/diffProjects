<?php
/**
 * Classe de génération et d'acquisition d'un format d'image.
 *
 * Si le format existe il est renvoyé sur la sortie standard, sinon il est génér�éen Pelican_Cache
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @since 15/06/2004
 * @link http://www.interakting.com
 */

/**
 * Librairie de la mediathéque.
 */
class Ndp_Cache_Media extends Pelican_Cache_Media
{
    /**
     * Constante utilisée en Bdd pour ne pas réaliser
     * le redimensionnement sur la largeur et/ou la hauteur.
     */
    const NO_RESIZE_WIDTH_HEIGHT = 1000;
    protected $autocrop= false;

    /**
     * Constructeur.
     *
     * Détermine en fonction de l'id de format passé en paramétre si le fichier
     * existe déjà (format "forcé")
     * ou s'il faut faire appel au gestionnaire de Pelican_Cache qui va soitcréer le
     * fichier de Pelican_Cache soit le lire s'il existe
     *
     * @access public
     *
     * @param $args
     * @internal param string $path Chemin absolu de l'image originale
     * @internal param int $format (option) Id de format d'image
     * @internal param string $crop (option) Coordonnées de recadrage
     * @internal param bool $bypass (option) Pour éviter l'utilisation des formats "forcés" (lors de tests découpages)
     * @internal param int $lifeTime (option) Constantes de durée de vie du fichier de Pelican_Cache
     * @internal param string $colorComplete (option)
     * @internal param string $width (option)
     * @internal param string $height (option)
     * @internal param string $extension (option)
     *
     */
    public function __construct($args)
    {
        list(
            $path,
            $format,
            $crop,
            $bypass,
            $lifeTime,
            $colorComplete,
            $width,
            $height,
            $extension,
            $ratioWithoutBlank,
            $autocrop) = $args;

        /*
         * Paramètres du Pelican_Cache
         */
        // ...
        $this->params[5] = $crop;
        $this->params[6] = $ratioWithoutBlank;
        $this->autocrop = $autocrop;

        parent::__construct($path, $format, $crop, $bypass, $lifeTime, $colorComplete, $width, $height, $extension);
    }

    /**
     * Méthode de création du fichier de Pelican_Cache.
     *
     * @access public
     */
    public function getValue()
    {
        /* @var Ndp_Media_ImageMagick $im */
        $im = Pelican_Factory::getInstance('Media.ImageMagick');

        // Extension forcé
        if ($this->params[4]) {
            $extension = $this->params[4];
        } else {
            $extension = Pelican::$config["IM_EXT"];
        }

        if ($this->colorComplete) {
            $im->colorComplete = $this->colorComplete;
        }

        $im->setOldFile($this->_oldFile);
        $temp1 = tempnam("/tmp", "tmpmedia");
        if (file_exists($temp1)) {
            unlink($temp1);
        }

        $new = str_replace("\\", "/", str_replace("/\\", "/", $temp1.".".$extension));
        $im->setNewFile($new);

        /*
         * Cas de la génétation à la volée
         */
        if ($this->_crop) {
            $temp2 = tempnam("/tmp", "tmpmedia");
            if(file_exists($temp2)) {
                unlink($temp2);
            }
            $new0 = str_replace("\\", "/", str_replace("/\\", "/", $temp2.".".$extension));
            $im->newFile0 = $new0;
        }

        if ($this->_format || $this->_format === "0" || ($this->params[2] && $this->params[3])) {

            // Cas de GraphicsMagick
            if (! Pelican::$config["GRAPHICS_MAGICK"]) {
                $im->setOpt('strip', ' ');
            }

            $im->setOpt('antialias', ' ');
            $im->setOpt('quality', escapeshellcmd(Pelican::$config['IMAGE_MAGICK']['OPT']['QUALITY']));
            $im->setOpt('type', Pelican::$config['IMAGE_MAGICK']['OPT']['TYPE']);
            //$im->setOpt('unsharp', Pelican::$config['IMAGE_MAGICK']['OPT']['UNSHARP']);

            /*
             * Cropage
             */
            if ($this->_crop) {
                $params = explode(",", $this->_crop);
                $im->setOpt('crop', array(
                    $params[2],
                    $params[3],
                    $params[0],
                    $params[1],
                ));
            }

            /*
             * Soit on récupère le binaire de l'image : "path" non null soit on retourne les dimensions du format à la fenètre parente
             */
            if ($this->_format) {
                $imageGab = Pelican_Cache::fetch("Frontend/MediaFormat", $this->_format);
                // Cas de l'optimisation de l'image originale
            }

            if ($this->params[2] && $this->params[3] && $this->params[4]) {
                $imageGab["MEDIA_FORMAT_WIDTH"] = $this->params[2];
                $imageGab["MEDIA_FORMAT_HEIGHT"] = $this->params[3];
                $imageGab["MEDIA_FORMAT_KEEP_RATIO"] = true;
            }

            $size = @getimagesize($this->_oldFile);

            /*
             * Si ratio sans blanc
             */
            if (valueExists($imageGab, "MEDIA_FORMAT_KEEP_RATIO") && $this->params[6]) {
                if ($size[0] > $size[1]) {
                    $imageGab["MEDIA_FORMAT_WIDTH"] = '';
                } else {
                    $imageGab["MEDIA_FORMAT_HEIGHT"] = '';
                }
            }

            /*
             * Format de l'image
             */
            if (! valueExists($imageGab, "MEDIA_FORMAT_WIDTH") && ! valueExists($imageGab, "MEDIA_FORMAT_HEIGHT")) {
                $imageGab["MEDIA_FORMAT_WIDTH"] = $size[0];
                $imageGab["MEDIA_FORMAT_HEIGHT"] = $size[1];
            } elseif ($imageGab["MEDIA_FORMAT_WIDTH"] > $size[0] && $imageGab["MEDIA_FORMAT_HEIGHT"] > $size[1]) {
                $imageGab["MEDIA_FORMAT_WIDTH"] = $size[0];
                $imageGab["MEDIA_FORMAT_HEIGHT"] = $size[1];
            }

            /*
             * Sans largeur ou sans hauteur
             */
            if ($imageGab["MEDIA_FORMAT_WIDTH"] == self::NO_RESIZE_WIDTH_HEIGHT) {
                $imageGab["MEDIA_FORMAT_WIDTH"] = '';
            }
            if ($imageGab["MEDIA_FORMAT_HEIGHT"] == self::NO_RESIZE_WIDTH_HEIGHT) {
                $imageGab["MEDIA_FORMAT_HEIGHT"] = '';
            }

            /*
             * Resize
             */
            $ratio = (valueExists($imageGab, "MEDIA_FORMAT_KEEP_RATIO") ? "" : "!");
            $im->setOpt('geometry', array(
                $imageGab["MEDIA_FORMAT_WIDTH"].$ratio,
                $imageGab["MEDIA_FORMAT_HEIGHT"].$ratio,
            ));

            $this->value = $im->create();

            $this->imFile = $im->newFile;
        }
    }
}
