<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once ('config.php');

$oConnection = Pelican_Db::getInstance();
$sSQL = 'SELECT * FROM #pref#_label ORDER BY LABEL_ID';
$aLabel = $oConnection->queryTab($sSQL);

$aPath = array(
    '/application/sites/frontend/views/scripts/Layout/' => '*.tpl',
    '/application/sites/frontend/views/scripts/Layout' => '*.mobi',
    '/application/sites/frontend/controllers/' => '*.php',

    '/application/sites/backend/controllers/' => '*.php',
    '/application/sites/backend/views/scripts/' => '*.tpl',
    
    '/application/configs/' => '*.php',
    
    '/application/library/Citroen/' => '*.php',
    
    '/application/caches/' => '*.php',
    '/library/Pelican/' => '*.php'
);
$return = '';
$i = $j = 0;
$page = ($_GET['page'] != '')?$_GET['page']:0;

$aLabel2 = array_chunk($aLabel, 500);
$aLabel = $aLabel2[$page];

while(sizeof($aLabel) > 0) {
    $label = array_shift($aLabel);
    //debug($label);die;
    if ($label['LABEL_ID'] != '' && strpos($label['LABEL_ID'], 'ADD_') !== 0 
            && strpos($label['LABEL_ID'], 'CAL_') !== 0 && strpos($label['LABEL_ID'], 'EDITOR_') !== 0) {
        $var1 = $var2 = array();
        $delete = true;
        foreach($aPath as $path => $ext) {
            $cmd1 = "find ".Pelican::$config['DOCUMENT_INIT'].$path." -iname '".$ext."' | xargs grep '".$label['LABEL_ID']."' -sl";
            //echo "<br/>".$cmd1."<br/>";
            exec($cmd1, $var1);//var_dump($var1);
            if (!empty($var1)) {
                $delete = false;
                break;
            }
        }
        if ($delete) {
            $return .= "DELETE FROM psa_label_langue_site WHERE LABEL_ID = '".$oConnection->strToBind($label['LABEL_ID'])."';\n";
            $return .= "DELETE FROM psa_label_langue WHERE LABEL_ID = '".$oConnection->strToBind($label['LABEL_ID'])."';\n";
            $return .= "DELETE FROM psa_label WHERE LABEL_ID = '".$oConnection->strToBind($label['LABEL_ID'])."';\n";
        }
        //die;
        $i++;
    }
    $j++;
    
}
if ($page < sizeof($aLabel2)) echo '<a href="?page='.($page+1).'">page suivante >></a><br/>';
mail('boulay.l@gmail.com', 'cppv2 > libelle', $return);
echo $return;
?>
