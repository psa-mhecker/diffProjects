<?php
include ('config.php');

include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Div.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Button.php');
//include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Tab.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Form.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Media.php');

echo Pelican_Request::call('/_/Media/popup', array(
    'tiny' => true , 
    'zone' => 'popup'
));
echo $i++;
?>