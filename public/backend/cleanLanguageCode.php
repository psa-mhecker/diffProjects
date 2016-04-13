<?php
/** nettoyage des codes langue ISO
 * 
 */
include_once 'config.php';

if (! $_SESSION[APP]["user"]["id"]) {
    echo ("Veuillez vous identifier en Back Office");
    exit();
}

$oConnection = Pelican_Db::getInstance();

$sql[] = "UPDATE  psa_language SET  LANGUE_CODE =  'cs' WHERE  psa_language.LANGUE_ID =8";
$sql[] = "UPDATE  psa_language SET  LANGUE_CODE =  'da' WHERE  psa_language.LANGUE_ID =9";
$sql[] = "UPDATE  psa_language SET  LANGUE_CODE =  'el' WHERE  psa_language.LANGUE_ID =11";
$sql[] = "UPDATE  psa_language SET  LANGUE_CODE =  'nb' WHERE  psa_language.LANGUE_ID =28";
$sql[] = "UPDATE  psa_language SET  LANGUE_CODE =  'sl' WHERE  psa_language.LANGUE_ID =34";
$sql[] = "UPDATE  psa_language SET  LANGUE_CODE =  'sv' WHERE  psa_language.LANGUE_ID =37";

foreach ($sql as $query) {
    doSql($oConnection, $query);
}

Pelican_Cache::clean('Language');
Pelican_Cache::clean('LanguageCode');
Pelican_Cache::clean('Translation');
Pelican_Cache::clean('Citroen/LanguageCodeById');
Pelican_Cache::clean('Frontend/Citroen/SiteLangues');
Pelican_Cache::clean('Frontend/Site/Init');

function doSql ($oConnection, $query)
{
    $oConnection->query($query);
    var_dump($query);
    var_dump('lignes modifiees : ' . $oConnection->affectedRows);
}
