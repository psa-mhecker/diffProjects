<?php

/*
 * Creer par Kristopher Perin
 * Dans le cadre du ticket CPW-2964
 * mail : kristopher.perin@businessdecision.com
 * 19/08/2014 
 */

include("config.php");
$oConnection = Pelican_Db::getInstance();
$aBind = array();

echo "<br><span style=\"color:red\">Merci de régénérer le fichier de traduction FO de chaque site et de compléter la traduction selon le pays.</span><br><br>";

$reqFO = "REPLACE INTO #pref#_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) 
VALUES ('Standard', 'used', 2, NULL, NULL, NULL, 1),
('Option', 'used', 2, NULL, NULL, NULL, 1)";  
$oConnection->query($reqFO, $aBind);

echo '<u>Requete :</u> <br><br>' . $reqFO . ';<br><br><u>Statut :</u> OK<br><br>';

$sql_label_langue = "REPLACE INTO #pref#_label_langue (LABEL_ID, LANGUE_ID, LABEL_TRANSLATE, LABEL_PATH) VALUES ";
$sql_label_langue_site = "REPLACE INTO #pref#_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES ";
$value_langue = "";
$value_langue_site = "";

$sql_site = "
    SELECT
        s.SITE_ID
    FROM
        #pref#_site s
    WHERE
        s.SITE_ID != 1
";
$aSiteId = $oConnection->queryTab($sql_site, $aBind);

foreach($aSiteId as $key=>$Sid){
    $aBind[':SITE_ID'] = $Sid['SITE_ID'];
    
    $sql_langue = "
    SELECT
        sl.LANGUE_ID
    FROM
        #pref#_site_language sl
    WHERE
        sl.SITE_ID = :SITE_ID
    ";
    
    $aLangueId = $oConnection->queryTab($sql_langue, $aBind);
    $value_langue = "";
    $value_langue_site = "";
    
    foreach($aLangueId as $key2=>$Lid){
        
        $value_langue = "('Standard', " . $Lid['LANGUE_ID'] . ", '', ''), ('Option', " . $Lid['LANGUE_ID'] . ", '', '')";
        $value_langue_site = "('Standard', " . $Lid['LANGUE_ID'] . ", " . $Sid['SITE_ID'] . ", ''), ('Option', " . $Lid['LANGUE_ID'] . ", " . $Sid['SITE_ID'] . ", '')";
        
        $oConnection->query($sql_label_langue . $value_langue, $aBind);
        $oConnection->query($sql_label_langue_site . $value_langue_site, $aBind);
        
        echo '<u>Requete :</u> <br><br>' . $sql_label_langue . $value_langue . ';<br><br><u>Statut :</u> OK<br><br>';
        echo '<u>Requete :</u> <br><br>' . $sql_label_langue_site . $value_langue_site . ';<br><br><u>Statut :</u> OK<br><br>';
        
    }    
}

?>