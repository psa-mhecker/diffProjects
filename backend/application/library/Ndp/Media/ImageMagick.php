<?php
/**
 * Classe de gestion d'ImageMagick.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 * @since 15/05/2006
 */

/**
 * Utilisation du module PECL s'ilgit st existe.
 */
define('USE_PECL', 0);

/**
 * Classe de gestion d'ImageMagick.
 *
 * @author Raphaèl Carles <rcarles@businessdecision.com>
 *
 * @since 15/05/2006
 */
class Ndp_Media_ImageMagick extends Pelican_Media_ImageMagick
{
    /**
     * __DESC__.
     *
     * @access public
     *
     * @param bool $time
     *                   (option) __DESC__
     *
     * @return __TYPE__
     */
    public function create($time = false)
    {
        if (extension_loaded('imagick') && USE_PECL) {
            if ($this->aOptions['draw']) {
                $handle = imagick_getcanvas("blue", $this->aOptions['size'][0], $this->aOptions['size'][1]);
                imagick_begindraw($handle);
                imagick_setfillcolor($handle, $this->aOptions['fill']);
                imagick_setfontface($handle, $this->aOptions['font']);
                imagick_setfontsize($handle, $this->aOptions['pointsize']);
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

            /*
             * Cas de la génération à la volée
             */
            // On échappe les ( ) car ImageMagick les interprètes
            $this->oldFile = str_replace(array(
                '(',
                ')',
            ), array(
                '\(',
                '\)',
            ), $this->oldFile);

            if (!empty($this->aOptions['crop'])) {
                $crop = $this->aOptions['crop'];
                unset($this->aOptions['crop']);
            }
            $this->aOptions['profile']=true;
            $this->tmpFile = dirname($this->newFile) . "/tmp" . basename($this->newFile);
            if ($crop && !empty($crop)) {

                $cmd = Pelican::$config["IM_ROOT"] . " " . $this->getCommandOptions(array(
                        "crop" => $crop,
                    )) . " " . $this->oldFile . " " . $this->tmpFile;
                Pelican::runCommand($cmd);
            } else {
                Pelican::runCommand('cp '.$this->oldFile.' '.$this->tmpFile);
            }
            $cmd = Pelican::$config["IM_ROOT"]." ".$this->getCommandOptions($this->aOptions)." ".$this->tmpFile." ".$this->newFile;
            Pelican::runCommand($cmd);
            switch ($this->getExtension()) {
                case 'png':
                    $this->pngquant();
                    break;
                case 'jpeg':// manage jpeg files
                case 'jpg':
                    $this->jpegtran();
                    break;
                default: // do noting
            }



            /*
             * Complément de l'image si une couleur est définie
             */
            if ($this->colorComplete) {
                $size = $this->aOptions['geometry'];
                $cmd = str_replace("convert", "composite", Pelican::$config["IM_ROOT"])." -gravity center ".$this->newFile." -size ".$size[0]."x".$size[1]." xc:".strtolower($this->colorComplete)." ".$this->newFile;
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

    private function getExtension() {

        return str_replace('.','',strrchr($this->newFile, '.'));
    }

    private function jpegtran()
    {
        $options = [];
        $options[] = '-optimize';
        $options[] = '-progressive';
        $options[] = '-copy none';
        $options[] = '-outfile '.$this->newFile;
        $tmp = $this->newFile.'.tmp';
        if ( !empty(Pelican::$config["JPEGTRAN_ROOT"])  && file_exists(Pelican::$config["JPEGTRAN_ROOT"])) {
            $cmd = 'cp '.$this->newFile.' '.$tmp;
            Pelican::runCommand($cmd);
            $cmd = Pelican::$config["JPEGTRAN_ROOT"].' '.implode(' ',$options).' '.$tmp;
            Pelican::runCommand($cmd);
        }
    }

    private function pngquant()
    {
        $options = [];
        $options[] = '--skip-if-larger';
        $options[] = '--force';
        $options[] = '--output '.$this->newFile;
        $tmp = $this->newFile.'.tmp';
        if ( !empty(Pelican::$config["PNGQUANT_ROOT"])  && file_exists(Pelican::$config["PNGQUANT_ROOT"])) {
            $cmd = 'cp '.$this->newFile.' '.$tmp;
            Pelican::runCommand($cmd);
            $cmd = Pelican::$config["PNGQUANT_ROOT"].' '.implode(' ',$options).' '.$tmp;
            Pelican::runCommand($cmd);
        }
    }
}
