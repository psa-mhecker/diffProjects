<?php
/**
 * Created by PhpStorm.
 * User: kmessaoudi
 * Date: 24/04/14
 * Time: 10:41
 */
include("config.php");
$file = "psa_label2.csv";
$row = 1;
$oConnection = Pelican_Db::getInstance();
if (($handle = fopen($file, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        echo "<p> $num champs à la ligne $row: <br /></p>\n";
        $row++;
        for ($c=0; $c < $num; $c++) {
           $column = array();
           $column = explode(';',$data[$c]);

           if(is_array($column) && !empty($column)){
               var_dump($column);
              $aBind =  array();
              $aBind[':LABEL_ID'] = utf8_decode($oConnection->strToBind($column[0]));
              $aBind[':LABEL_BO'] = $column[5];
              $aBind[':LABEL_FO'] = $column[6];
              $sql = '
                        UPDATE
                            #pref#_label
                        SET
                            LABEL_BO = :LABEL_BO,
                            LABEL_FO = :LABEL_FO
                        WHERE
                            LABEL_ID = :LABEL_ID

              ';

              $oConnection->query($sql,$aBind);
           }
        }
    }
    fclose($handle);
}