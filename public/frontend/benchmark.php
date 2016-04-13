<?php

$array = array ('/', '/pid12-actualites.html', '/actualites/cid11-zend-framework-components.html', '/pid34-contenu-flash.html', '/recherche?recMot=paris&research=Rechercher', '/pid41-inscription.html' );

$i = rand ( 0, count ( $array ) - 1 );

$_SERVER ['REQUEST_URI'] = $array [$i];
$_SERVER ['PHP_SELF'] = '/index.php';
$_SERVER ['SCRIPT_NAME'] = '/index.php';
$_SERVER ['SCRIPT_FILENAME'] = $_SERVER ['DOCUMENT_ROOT'] . '/index.php';
$_SERVER ['REDIRECT_URL'] = $array [$i];

$time_start = microtime ( true );
$aDir = explode ( '/', dirname ( __FILE__ ) );
include ('index.php');
$time = microtime ( true ) - $time_start;
echo '<!-- time : ' . substr ( $time, 0, 5 ) . ' sec. -->';
echo '------------- ' . substr ( $time, 0, 5 ) . ' ----------------------';
