<?php

/**
 */

/**
 * Fichier de Pelican_Cache : Liste des skins.
 *
 * retour : id, lib
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 14/07/2007
 */
class Frontend_Skins extends Pelican_Cache
{
    public static $storage = 'file';

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $cmd = "find ".Pelican::$config['MEDIA_ROOT']."/design/skins  -type d -name \"*images\" -ls |awk '{print $11\"#\";}'";

        $handle = popen($cmd, 'r');
        $read = '';
        while (! feof($handle)) {
            $read .= fread($handle, 2096);
        }
        pclose($handle);
        $temp = explode("#", $read);
        if ($temp) {
            foreach ($temp as $dir) {
                if (! substr_count($dir, '_old') && ! substr_count($dir, '_work') && ! substr_count($dir, '/_work/zz') && ! substr_count($dir, '/system')) {
                    $id = str_replace(array(
                        Pelican::$config['MEDIA_ROOT']."/design/skins/",
                        "/joomla_images",
                        "/images",
                    ), "", $dir);
                    $label = basename($id);
                    $aValues[trim($id)] = trim($label);
                }
            }
        }

        $this->value = $aValues;
    }
}
