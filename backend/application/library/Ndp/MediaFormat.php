<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class Media_Format {
    const VARIABLE = 'variable';
    const RATIO_MEDIA_CFG_FILE = '/application/configs/ratio_media.ini.php';
    const RATIO_MEDIA_CG_SOURCE_FILE = '/application/configs/formats.ini.php';
    
    
    public static function init() {
        
        $fs = new Filesystem();
        
        if ($fs->exists(Pelican::$config["DOCUMENT_INIT"].self::RATIO_MEDIA_CFG_FILE)) {
           return;
        }
        
        if (!$fs->exists(Pelican::$config["DOCUMENT_INIT"].self::RATIO_MEDIA_CG_SOURCE_FILE)) {
            throw new Exception('NDP_NO_RATION_MEDIA_CFG_SOURCE_FILE');
        }
        $formatsByRatios = [];
        include(Pelican::$config["DOCUMENT_INIT"].self::RATIO_MEDIA_CG_SOURCE_FILE);
        
        $ratio_key = array();
        Pelican::$config['RECHERCHE_RATIO'] = array();
        Pelican::$config['RECHERCHE_RATIO_DETAIL'] = array();
        foreach ($formatsByRatios as $name_format => $format) {
            if (is_array($format['formats']) && count($format['formats'])) {                
                $ratio = $format['value'];
                foreach ($format['formats'] as $ratio_format) {
                  if (strpos($ratio_format['pixel'], self::VARIABLE)) {
                      $ratio = $name_format.':'.$ratio_format['pixel'];
                  }
                  if (!array_key_exists($ratio, Pelican::$config['RECHERCHE_RATIO'])) {
                    Pelican::$config['RECHERCHE_RATIO'][$ratio] = 't("'.$name_format.'")';
                    $ratio_key[$ratio] = $name_format;
                  }
                  Pelican::$config['RECHERCHE_RATIO_DETAIL'][$ratio_key[$ratio].':'.$ratio_format['pixel']] = [
                        'value' => $ratio,
                        'lib' => 't("'.$name_format.'")',
                        'marge' => $format['marge'],
                        'pixel' =>$ratio_format['pixel']
                    ];
                }
            }
        }
        asort(Pelican::$config['RECHERCHE_RATIO']);
        $cfg_file = "<?php \n".'Pelican::$config[\'RECHERCHE_RATIO\'] = '
                    .var_export(Pelican::$config['RECHERCHE_RATIO'], true)
            .";\n"
            .'Pelican::$config[\'RECHERCHE_RATIO_DETAIL\'] = '
            .var_export(Pelican::$config['RECHERCHE_RATIO_DETAIL'], true)
            .";\n";
        $cfg_file = str_replace('\'t(', "t(", $cfg_file);
        $cfg_file = str_replace(')\',', "),", $cfg_file);
        
        $fs->dumpFile(Pelican::$config["DOCUMENT_INIT"].self::RATIO_MEDIA_CFG_FILE, $cfg_file);
        
    }
}
 
Media_Format::init();
include_once(Pelican::$config["DOCUMENT_INIT"].Media_Format::RATIO_MEDIA_CFG_FILE);