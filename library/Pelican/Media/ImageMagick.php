<?php
/**
 * Classe de gestion d'ImageMagick
 *
 * @package Pelican
 * @subpackage Media
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 * @since 15/05/2006
 */

/**
 * utilisation du module PECL s'il existe
 */
define('USE_PECL', 0);

/**
 * Classe de gestion d'ImageMagick
 *
 * @package Pelican
 * @subpackage Media
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 15/05/2006
 */
class Pelican_Media_ImageMagick
{

    /**
     *
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $aOptions = array();

    /**
     *
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $oldFile = "";

    /**
     *
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $newFile = "";

    /**
     *
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $offset = 0;

    /**
     *
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $margin = 0;

    /**
     *
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $colorComplete = "";

    /**
     *
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $tmpFile;

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $name
     *            __DESC__
     * @param __TYPE__ $value
     *            __DESC__
     * @return __TYPE__
     */
    public function setOpt ($name, $value)
    {
        $this->aOptions[$name] = $value;
        // ksort($this->aOptions);
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $file
     *            __DESC__
     * @return __TYPE__
     */
    public function setOldFile ($file)
    {
        $this->oldFile = $file;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $file
     *            __DESC__
     * @return __TYPE__
     */
    public function setNewFile ($file)
    {
        $this->newFile = $file;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param bool $time
     *            (option) __DESC__
     * @return __TYPE__
     */
    public function create ($time = false)
    {
        if (extension_loaded('imagick') && USE_PECL) {
            if ($this->aOptions['draw']) {
                $handle = imagick_getcanvas("blue", $this->aOptions['size'][0], $this->aOptions['size'][1]);
                imagick_begindraw($handle);
                imagick_setfillcolor($handle, $this->aOptions['fill']);
                imagick_setfontface($handle, $this->aOptions['font']);
                imagick_setfontsize($handle, $this->aOptions['pointsize']);
                // imagick_setfontstyle( $handle, IMAGICK_FONTSTYLE_ITALIC )
                imagick_drawannotation($handle, $this->aOptions['size'][0], $this->aOptions['size'][1], $this->aOptions['draw'][0]);
            } else {
                $handle = imagick_readimage($this->oldFile);
                if ($this->aOptions['crop']) {
                    imagick_crop($handle, $this->aOptions['crop'][0], $this->aOptions['crop'][1], $this->aOptions['crop'][2], $this->aOptions['crop'][3]);
                }
                imagick_resize($handle, $this->aOptions['geometry'][0], $this->aOptions['geometry'][1], IMAGICK_FILTER_UNKNOWN, 1);
            }
            if ($this->newFile) {
                imagick_writeimage($handle, $this->newFile);
            }
            $return = imagick_image2blob($handle);
        } else {
            
            /**
             * Cas de la génétation à la volée
             */
            // On échappe les ( ) car ImageMagick les interprètes
            $this->oldFile = str_replace(array(
                '(',
                ')'
            ), array(
                '\(',
                '\)'
            ), $this->oldFile);
            if (! empty($this->aOptions['crop'])) {
                $geometry = $this->aOptions['geometry'];
                $crop = $this->aOptions['crop'];
                unset($this->aOptions['geometry']);
                unset($this->aOptions['crop']);
                $this->tmpFile = dirname($this->oldFile) . "/tmp" . basename($this->oldFile);
                $cmd = Pelican::$config["IM_ROOT"] . " " . $this->getCommandOptions($this->aOptions) . " " . $this->oldFile . " " . $this->getCommandOptions(array(
                    "crop" => $crop
                )) . " " . $this->tmpFile;
                Pelican::runCommand($cmd);
                $this->aOptions['geometry'] = $geometry;
                $this->oldFile = $this->tmpFile;
            }
            $cmd = Pelican::$config["IM_ROOT"] . " " . $this->getCommandOptions($this->aOptions) . " " . $this->oldFile . " " . $this->newFile;
            Pelican::runCommand($cmd);
            if ($this->tmpFile) {
                @unlink($this->tmpFile);
            }
            
            /**
             * Complément de l'image si une couleur est définie
             */
            if ($this->colorComplete) {
                $size = $this->aOptions['geometry'];
                $cmd = str_replace("convert", "composite", Pelican::$config["IM_ROOT"]) . " -gravity center " . $this->newFile . " -size " . $size[0] . "x" . $size[1] . " xc:" . strtolower($this->colorComplete) . " " . $this->newFile;
                Pelican::runCommand($cmd);
            }
            if ($time) {
                @touch($this->newFile, $time);
                @chmod($this->newFile, 0777);
                $return = $this->newFile;
            } else {
                $fp = @fopen($this->newFile, "r");
                $return = @fread($fp, filesize($this->newFile));
                @fclose($fp);
                @unlink($this->newFile);
            }
        }
        
        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $opt
     *            __DESC__
     * @return __TYPE__
     */
    public function getCommandOptions ($opt)
    {
        /**
         * cas particulier de graphic magick
         */
        $repage = ! (Pelican::$config["GRAPHICS_MAGICK"]);
        if ($opt) {
            foreach ($opt as $key => $value) {
                switch ($key) {
                    case 'crop':
                        {
                            // widthxheight{+-}x{+-}y{%}
                            $value = $value[0] . "x" . $value[1] . "+" . $value[2] . "+" . $value[3];
                            $return[] = "-" . $key . " " . $value . ($repage ? " +repage" : "");
                            break;
                        }
                    case 'size':
                    case 'geometry':
                        {
                            if (! $value[0]) {
                                $value[0] = "";
                            }
                            if (! $value[1]) {
                                $value[1] = "";
                            }
                            $value = str_replace('!', '\!', $value[0] . "x" . $value[1]);
                            $return[] = "-" . $key . " " . $value;
                            break;
                        }
                    case 'gradient':
                    case 'xc':
                        {
                            $return[] = $key . ":" . $value;
                            break;
                        }
                    case 'draw':
                        {
                            if (! is_array($value)) {
                                $value = array(
                                    $value
                                );
                            }
                            if (count($value) == 1) {
                                $offset = ($this->offset <= 10 ? 7 : 12);
                                $return[] = "-draw \"text " . $this->margin . "," . $offset . " '" . escapeshellcmd($value[0]) . "'\"";
                            } else {
                                $offset[0] = - 6;
                                $offset[1] = 30;
                                $return[] = "-draw \"text " . $this->margin . "," . $offset[0] . " '" . trim(escapeshellcmd($value[0])) . "'\"";
                                $return[] = "-draw \"text " . $this->margin . "," . $offset[1] . " '" . trim(escapeshellcmd($value[1])) . "'\"";
                            }
                            break;
                        }
                    case 'drawimage':
                        {
                            if (is_array($value)) {
                                if (count($value) == 6) {
                                    $return[] = "-draw \"image Over " . $value[0] . "," . $value[1] . " " . $value[2] . "," . $value[3] . " '" . $value[5] . "'\"";
                                    $return[] = "-draw \"text 5,0 '" . escapeshellcmd($value[4][0]) . "'\"";
                                }
                            }
                            break;
                        }
                    case "offset":
                        {
                            $this->offset = $value;
                            break;
                        }
                    default:
                        {
                            $return[] = "-" . $key . " " . $value;
                            break;
                        }
                }
            }
            $return = implode(" ", $return);
        }
        
        return $return;
    }
}