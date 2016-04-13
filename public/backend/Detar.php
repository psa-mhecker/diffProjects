<?php
include("config.php");
var_dump(file_exists(Pelican::$config["DOCUMENT_INIT"]."/_package/Lot_IT_1.0_(14_11_2013)/Lot IT 1.0 (14_11_2013).r229823.tar.gz"));
chmod (Pelican::$config["DOCUMENT_INIT"]."/_package/Lot_IT_1.0_(14_11_2013)/Lot IT 1.0 (14_11_2013).r229823.tar.gz", 0777);
chmod (Pelican::$config["DOCUMENT_INIT"]."/_package/Lot_IT_1.0_(14_11_2013)", 0777);
exec("chmod -R ".Pelican::$config["DOCUMENT_INIT"]."/_package 777");
//gzip -dc filename.tar.gz | tar xf -
?>