<?php

namespace Citroen\Batch;

use Itkg\Batch;
use Citroen\GammeFinition\Gamme as GammeFinition;

define('extract_fo_labels_regex_directive',
    '/' .
    // {t('Masquer_le_Contenu')}, {t("Masquer_le_Contenu")}
    //'({t\([\'"])(.*?)([\'"]\)})'.
    //'|'.
    // t('Voir plus'), t("Voir plus"), {t('Masquer_le_Contenu')}, {t("Masquer_le_Contenu")}
    '(t\([\'"])(.*?)([\'"]\))' .
    '|' .
    // {'SLIDE_TECHNOLOGIE'|t}, {"SLIDE_TECHNOLOGIE"|t}
    '(({[\'"])(.*?)([\'"]\|t}))' .
    '|' .
    // alt="RAC" ou alt='Hello world', mais pas alt='' ni alt='C' ni alt="{$slideShow.PAGE_ZONE_MULTI_TITRE}"
    '(((alt|href)=[\'"])([\w\s-:@éèàù\è\'\.]{2,}?)([\'"]))' .
    '|' .
    // <a href="{$Detail.PAGE_CLEAR_URL}">En savoir plus</a>, <span>Demande d'essai</span>
    '((<([\w]+)[^>]*>)(([\w\s-:@éèàù\'\.]|(<br>)){2,}?)(<\/\15>))' .
    //'|' .
    // 	Nom du contact N<br>
    //'(^([\w\s]+?[^<]*)(.*?)$)' .
    '/i');
define('extract_fo_labels_regex_file_ext', '/^.+\.(tpl|mobi)$/i');
define('extract_fo_labels_memory_limit', '1200M');
define('extract_fo_labels_max_execution_time', '0');
define('extract_fo_labels_layout_dir', 'application/sites/frontend/views/scripts/Layout/Citroen/');

/**
 * Classe Extractfolabels
 *
 * Cette classe permet d'extraire tous les libellés utilisés en Front Office à des fins de traduction
 * Appel CLI: php batch/console.php batch CITROEN_BATCH_EXTRACTFOLABELS -env=DEV
 *
 * @author Christophe VRIGNAUD <christophe.vrignaud@businessdecision.com>
 */
class Extractfolabels extends Batch
{
    private $header = array('file', 'line', 'key', 'label');

    public function execute()
    {
        ini_set('memory_limit', extract_fo_labels_memory_limit);
        ini_set('max_execution_time', extract_fo_labels_max_execution_time);
        error_log('Start process');

        $path = realpath(extract_fo_labels_layout_dir);

        $Directory = new \RecursiveDirectoryIterator($path);
        $Iterator = new \RecursiveIteratorIterator($Directory);
        $Regex = new \RegexIterator($Iterator, extract_fo_labels_regex_file_ext, \RecursiveRegexIterator::GET_MATCH);

        $count_keys = $count_hardcoded = $fetched = 0;

        $file = fopen('file.csv', 'w');
        fputcsv($file, $this->header);

        foreach ($Regex as $name => $object) {
            $fetched++;
            $lines = file($name);
            foreach ($lines as $line_num => $line) {
                if (preg_match_all(extract_fo_labels_regex_directive, trim($line), $matches, PREG_SET_ORDER) > 0) {

//                    error_log($name . print_r($matches, true));
                    foreach ($matches as $val) {
//                        error_log($name . print_r($val, true));
                        if (strlen($val[2])) {
                            fputcsv($file, array($name, ($line_num + 1), $val[2], $val[2]));
                            $count_keys++;
                        }
                        if (strlen($val[6])) {
                            fputcsv($file, array($name, ($line_num + 1), $val[6], $val[6]));
                            $count_keys++;
                        }
                        if (strlen($val[11])) {
                            fputcsv($file, array($name, ($line_num + 1), '', $val[11]));
                            $count_hardcoded++;
                        }
                        if (strlen($val[16])) {
                            fputcsv($file, array($name, ($line_num + 1), '', $val[16]));
                            $count_hardcoded++;
                        }
                        if (strlen($val[21])) {
                            fputcsv($file, array($name, ($line_num + 1), '', $val[21]));
                            $count_hardcoded++;
                        }
                    }
                }
            }
        }

        fclose($file);

        error_log($fetched . ' files fetched...');
        error_log($count_keys . ' translated labels found...');
        error_log($count_hardcoded . ' hardcoded strings found...');
        error_log('End process');
    }
}