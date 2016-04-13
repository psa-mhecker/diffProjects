<?php
/**
 * Classe de g�n�ration et d'acquisition d'un format d'image
 *
 * Si le format existe il est renvoy� sur la sortie standard, sinon il est g�n�r� en Pelican_Cache
 *
 * @package Pelican
 * @subpackage Cache
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @since 15/06/2004
 * @link http://www.interakting.com
 */

/**
 * Librairie de la mediath�que
 */
require_once (pelican_path('Media'));
pelican_import('Media.ImageMagick');

/**
 * Classe permettant la g�n�ration d'une format d'image
 *
 * @package Pelican
 * @subpackage Cache
 * @author Rapha�l Carles <rcarles@businessdecision.com>
 * @since 15/06/2004
 */
class Pelican_Cache_Media extends Pelican_Cache
{

    /**
     * Dur�e de vie
     *
     * @var integer $lifeTime
     */
    public $lifeTime = WEEK;

    /**
     * Dur�e de vie
     *
     * @access public
     * @var int
     */
    // public $lifeTime = MONTH;

    /**
     * Chemin de l'image originale
     *
     * @access private
     * @var string
     */
    public $_oldFile;

    /**
     * __DESC__
     *
     * @access private
     * @var string
     */
    public $_newFile;

    /**
     * Id du format de l'image
     *
     * @access private
     * @var string
     */
    public $_format;

    /**
     * __DESC__
     *
     * @access private
     * @var string
     */
    public $_crop;

    /**
     * Chemin d'acc�s � "convert"
     *
     * @access private
     * @var string
     */
    public $_converPath = "";

    /**
     *
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $path = "";

    /**
     * Constructeur
     *
     * D�termine en fonction de l'id de format pass� en param�tre si le fichier
     * existe d�j� (format "forc�")
     * ou s'il faut faire appel au gestionnaire de Pelican_Cache qui va soitcr�er le
     * fichier de Pelican_Cache soit le lire s'il existe
     *
     * @access public
     * @param  string              $path
     *                                            Chemin absolu de l'image originale
     * @param  int                 $format
     *                                            (option) Id de format d'image
     * @param  string              $crop
     *                                            (option) Coordonn�es de recadrage
     * @param  bool                $bypass
     *                                            (option) Pour �viter l'utilisation des formats "forc�s"
     *                                            (lors de tests d�coupages)
     * @param  int                 $lifeTime
     *                                            (option) Constantes de dur�e de vie du fichier de
     *                                            Pelican_Cache
     * @param  string              $colorComplete
     *                                            (option) __DESC__
     * @param  string              $width
     *                                            (option) __DESC__
     * @param  string              $height
     *                                            (option) __DESC__
     * @param  string              $extension
     *                                            (option) __DESC__
     * @return Pelican_Cache_Media
     */
    public function Pelican_Cache_Media($path, $format = "", $crop = "", $bypass = false, $lifeTime = "", $colorComplete = "", $width = "", $height = "", $extension = "")
    {

        /**
         * param�tres du Pelican_Cache
         */
        $this->params[0] = $path;
        $this->params[1] = $format;
        $this->params[2] = $width;
        $this->params[3] = $height;
        $this->params[4] = $extension;
        $this->colorComplete = $colorComplete;
        // $this->params[] = $crop;

        /**
         * Pelican_Cache d'un binaire
         */
        $this->_binaryCache = true;

        /**
         * Formatage
         */
        $this->_format = $format;
        $this->_crop = $crop;

        /**
         * Fichier d'origine
         */
        $this->_oldFile = getUploadRoot($path);
        $pathinfo = pathinfo($this->_oldFile);
        // cas d'un changement d'extension
        $listDir = glob($pathinfo['dirname'] . '/' . $pathinfo['filename'] . '.[a-zA-Z]*');
        if ($listDir[0] != $this->_oldFile) {
            $this->_oldFile = $listDir[0];
        }

        /**
         * Nouveau Fichier
         */
        $this->_newFile = $this->_oldFile;

        /**
         * si le format est d�fini (0 indique le fichier optimis� sans changements)
         */
        if ($this->_format || $this->_format === "0") {
            $fileName = Pelican_Media::getFileNameMediaFormat($path, $this->_format);
            $this->_newFile = getUploadRoot($fileName);
        }
        // si redimensionnement force
        if ($width && $height && $extension) {
            $this->_newFile = $this->_oldFile . '.' . $extension;
        }
        if ($lifeTime) {
            $this->lifeTime = $lifeTime;
        }

        /**
         * si le fichier existe, on r�cup�re le binaire
         */
        if (file_exists($this->_newFile) && ! $bypass) {
            $fp = fopen($this->_newFile, "r");
            $filesize = filesize($this->_newFile);
            if ($filesize !== false && $filesize > 0) {
                $this->value = fread($fp, $filesize);
            }
            fclose($fp);

            /**
             * il n'est pas n�cessaire de g�n�rer le Pelican_Cache
             */
            $this->deprecated = false;
        }
        if (! isset($this->value)) {
            parent::__construct();
            if (valueExists($_REQUEST, "nocache")) {
                $this->deprecated = false;
            }
            $this->get();
        }
    }

    /**
     * M�thode de cr�ation du fichier de Pelican_Cache
     *
     * @access public
     * @return void
     */
    public function getValue()
    {
        $im = Pelican_Factory::getInstance('Media.ImageMagick');
        // extension forc�
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
        @unlink($temp1);
        $new = str_replace("\\", "/", str_replace("/\\", "/", $temp1 . "." . $extension));
        $im->setNewFile($new);

        /**
         * Cas de la g�n�tation � la vol�e
         */
        if ($this->_crop) {
            $temp2 = tempnam("/tmp", "tmpmedia");
            @unlink($temp2);
            $new0 = str_replace("\\", "/", str_replace("/\\", "/", $temp2 . "." . $extension));
            $im->newFile0 = $new0;
        }
        if ($this->_format || $this->_format === "0" || ($this->params[2] && $this->params[3])) {
            // cas de graphicsMagick
            if (! Pelican::$config["GRAPHICS_MAGICK"]) {
                $im->setOpt('strip', ' ');
            }
            $im->setOpt('antialias', ' ');
            $im->setOpt('quality', escapeshellcmd( Pelican::$config['IMAGE_MAGICK']['OPT']['QUALITY'] ));
            $im->setOpt('type', Pelican::$config['IMAGE_MAGICK']['OPT']['TYPE'] );
            if (isset(Pelican::$config['IMAGE_MAGICK']['OPT']['UNSHARP']))
                $im->setOpt('unsharp', Pelican::$config['IMAGE_MAGICK']['OPT']['UNSHARP']);

            /**
             * cropage
             */
            if ($this->_crop) {
                $params = explode(",", $this->_crop);
                $im->setOpt('crop', array(
                    $params[2],
                    $params[3],
                    $params[0],
                    $params[1]
                ));
            }

            /**
             * Soit on r�cup�re le binaire de l'image : "path" non null soit on retourne les dimensions du format � la fen�tre parente
             */
            if ($this->_format) {
                $imageGab = Pelican_Cache::fetch("Frontend/MediaFormat", $this->_format);
                // Cas de l'optimisation de l'image originale
            }
            if ($this->params[2] && $this->params[3] && $this->params[4]) {
                $imageGab["MEDIA_FORMAT_WIDTH"] = $this->params[2];
                $imageGab["MEDIA_FORMAT_HEIGHT"] = $this->params[3];
                $imageGab["MEDIA_FORMAT_RATIO"] = true;
            }
            $size = @getimagesize($this->_oldFile);

            /**
             * format de l'image
             */
            if (! valueExists($imageGab, "MEDIA_FORMAT_WIDTH") && ! valueExists($imageGab, "MEDIA_FORMAT_HEIGHT")) {
                $imageGab["MEDIA_FORMAT_WIDTH"] = $size[0];
                $imageGab["MEDIA_FORMAT_HEIGHT"] = $size[1];
            } elseif ($imageGab["MEDIA_FORMAT_WIDTH"] > $size[0] && $imageGab["MEDIA_FORMAT_HEIGHT"] > $size[1]) {
                $imageGab["MEDIA_FORMAT_WIDTH"] = $size[0];
                $imageGab["MEDIA_FORMAT_HEIGHT"] = $size[1];
            }

            /**
             * sans largeur ou sans hauteur
             */
            if ($imageGab["MEDIA_FORMAT_WIDTH"] == 1000) {
                $imageGab["MEDIA_FORMAT_WIDTH"] = '';
            }
            if ($imageGab["MEDIA_FORMAT_HEIGHT"] == 1000) {
                $imageGab["MEDIA_FORMAT_HEIGHT"] = '';
            }

            /**
             * sans largeur ou sans hauteur
             */

            /**
             * resize
             */
            $ratio = (valueExists($imageGab, "MEDIA_FORMAT_RATIO") ? "" : "!");
            $im->setOpt('geometry', array(
                $imageGab["MEDIA_FORMAT_WIDTH"] . $ratio,
                $imageGab["MEDIA_FORMAT_HEIGHT"] . $ratio
            ));
            $this->value = $im->create();
            $this->imFile = $im->newFile;
        }
    }
}
