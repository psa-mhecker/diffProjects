<?php
include 'config.php';

if (! $_SESSION[APP]["user"]["id"] && Pelican::$config["TYPE_ENVIRONNEMENT"] != "dev") {
    echo ("Veuillez vous identifier en Back Office");
    exit();
}

Pelican_Cache_File::removeInvalidCache();
