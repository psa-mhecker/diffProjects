<?php
$backend = true;
include 'config.php';
echo Pelican_Request::call('/_/Popup/internalLink', array(
    'tiny' => true
));
