<?php 

    include('config.php');
    
    $oConnection = Pelican_Db::getInstance();
    
    $sql = <<<SQL
    SELECT *
    FROM psa_tmp_label
    WHERE label_id NOT IN (
        SELECT label_id
        FROM psa_label
    )
SQL;
    
    $aLabel = $oConnection->queryTab($sql);
    
    $a = array();
    if (is_array($aLabel) && !empty($aLabel)) {
        echo "INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE) VALUES\n";
        foreach($aLabel as $label) {
            $a[] = '('.$oConnection->strToBind($label['LABEL_ID']).', '.$oConnection->strToBind($label['LABEL_INFO']).', '.$oConnection->strToBind($label['LABEL_BACK']).', '.$oConnection->strToBind($label['LABEL_LENGTH']).', '.$oConnection->strToBind($label['LABEL_CASE']).' )';
        }
        echo implode(",\n", $a).";\n\n";
    }
    
    $sql2 = <<<SQL
    SELECT *
    FROM psa_tmp_label_langue
    WHERE label_id NOT IN (
        SELECT label_id
        FROM psa_label_langue
    )
SQL;
    
    $aLabelLangue = $oConnection->queryTab($sql2);
    
    $b = array();
    if (is_array($aLabelLangue) && !empty($aLabelLangue)) {
        echo "INSERT INTO psa_label_langue (LABEL_ID,LANGUE_ID,LABEL_TRANSLATE,LABEL_PATH) VALUES\n";
        foreach($aLabelLangue as $labelLangue) {
            $b[] = '('.$oConnection->strToBind($labelLangue['LABEL_ID']).', '.$oConnection->strToBind($labelLangue['LANGUE_ID']).', '.$oConnection->strToBind($labelLangue['LABEL_TRANSLATE']).', '.$oConnection->strToBind($labelLangue['LABEL_PATH']).')';
        }
        echo implode(",\n", $b).";\n\n";
    }
    
    
    $sql3 = <<<SQL
    SELECT *
    FROM psa_tmp_label_langue_site
    WHERE label_id NOT IN (
        SELECT label_id
        FROM psa_label_langue_site
    )
SQL;
    
    $aLabelLangueSite = $oConnection->queryTab($sql3);
    
    $c = array();
    if (is_array($aLabelLangueSite) && !empty($aLabelLangueSite)) {
        echo "INSERT INTO psa_label_langue_site (LABEL_ID,LANGUE_ID,SITE_ID,LABEL_TRANSLATE) VALUES\n";
        foreach($aLabelLangueSite as $labelLangueSite) {
            $c[] = '('.$oConnection->strToBind($labelLangueSite['LABEL_ID']).', '.$oConnection->strToBind($labelLangueSite['LANGUE_ID']).', '.$oConnection->strToBind($labelLangueSite['SITE_ID']).', '.$oConnection->strToBind($labelLangueSite['LABEL_TRANSLATE']).')';
        }
        echo implode(",\n", $c).";\n";
    }