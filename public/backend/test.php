<?php
include("config.php");
Pelican::$config['PROXY']['URL'] = 'http://http.internetpsa.inetpsa.com';
Pelican::$config['PROXY']['PORT'] = '80';
Pelican::$config['PROXY']['LOGIN'] = 'mdecpw00';
Pelican::$config['PROXY']['PWD'] = 'svncpw00';
$sUrl = "http://widget.stagram.com/rss/n/citroenracing";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $sUrl);
curl_setopt($ch, CURLOPT_PROXY, Pelican::$config['PROXY']['URL'].":".Pelican::$config['PROXY']['PORT']."" );
curl_setopt($ch, CURLOPT_PROXYUSERPWD, "".Pelican::$config['PROXY']['LOGIN'].":".Pelican::$config['PROXY']['PWD']."");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
$sFeed = curl_exec($ch);
if(strpos($sFeed, '<?xml') !== false){
	$sXml = simplexml_load_string($sFeed);
	for($i=0;$i<count($sXml->channel->item);$i++){
		$aInstagram[] = array(
			"title" => (string)$sXml->channel->item[$i]->title,
			"image" => (string)$sXml->channel->item[$i]->image->url
		);
		
	}
}
var_dump($aInstagram);
?>