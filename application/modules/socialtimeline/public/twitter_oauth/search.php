<?php

/*************************************/
/* Introduce your credentials */
/*
1 - Add a new Twitter application - https://dev.twitter.com/apps/new
2 - Fill in Name, Description, Website, and Callback URL (don't leave any blank) with anything you want
3 - Agree to rules, fill out captcha, and submit your application
4 - Click the button "Create my access token" and then go to the OAuth tab.
5 - Copy the Consumer key, Consumer secret, Access token and Access token secret into the fields above
6 - When you upload the files to the server, you can set the twitter option using the url of the PHP file
*/

include_once('config.php');
$consumer_key = Pelican::$config['TWITTER_CONSUMER_KEY'] ;
$consumer_secret = Pelican::$config['TWITTER_CONSUMER_SECRET'];
$oauth_access_token = Pelican::$config['TWITTER_OAUTH_ACCESS_TOKEN'];
$oauth_access_token_secret = Pelican::$config['TWITTER_OAUTH_ACCESS_TOKEN_SECRET'];

/***************************************/

error_reporting(E_ALL ^ E_NOTICE);

if(!isset($_GET['q'])) 
	die('The search value is required');
	
$q = $_GET['q'];
$count = $_GET['count'];

if($count == "" || $count <= 0) 
	$count = 20;

function buildBaseString($baseURI, $method, $params) {
    $r = array();
    ksort($params);
    foreach($params as $key=>$value){
        $r[] = "$key=" . rawurlencode($value);

    }

    return  $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
}

function buildAuthorizationHeader($oauth) {
    $r = 'Authorization: OAuth ';
    $values = array();
    foreach($oauth as $key=>$value)
        $values[] = "$key=\"" . rawurlencode($value) . "\"";
    $r .= implode(', ', $values);
    return $r;
}

$url = "https://api.twitter.com/1.1/search/tweets.json";


$oauth = array( 'q' => $q,
				'count' => $count,
				'oauth_consumer_key' => $consumer_key,
                'oauth_nonce' => time(),
                'oauth_signature_method' => 'HMAC-SHA1',
                'oauth_token' => $oauth_access_token,
                'oauth_timestamp' => time(),
                'oauth_version' => '1.0');

$base_info = buildBaseString($url, 'GET', $oauth);
$composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
$oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
$oauth['oauth_signature'] = $oauth_signature;

// Make Requests
$header = array(buildAuthorizationHeader($oauth), 'Expect:');
$options = array( CURLOPT_HTTPHEADER => $header,
                  //CURLOPT_POSTFIELDS => $postfields,
                  CURLOPT_HEADER => false,
                  CURLOPT_URL => $url . '?q='.rawurlencode($q).'&count='.$count, 
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_SSL_VERIFYPEER => false);

$feed = curl_init();
curl_setopt_array($feed, $options);
$json = curl_exec($feed);
curl_close($feed);

$twitter_data = json_decode($json);
$count = 0;
$json_format = '{"responseData": {"feed":{"feedUrl": "http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'","title":"Twitter Search / '.$q.'","link":"http://search.twitter.com/search?q='.$q.'","author":"","description":"Twitter Search / '.$q.'","type":"rss20","entries":[';
if(is_array($twitter_data->statuses)) {
foreach($twitter_data->statuses as $row) {
	if($count > 0) {
		$json_format .= ',';	
	}
	$media = $row->entities->media;
	$attached = "";
	if(!empty($media)) {
		foreach($media as $key) {
			$attached .= ' <img alt="" src="'.$key->media_url.'" /> ';
		}
	}
	$json_format .= '{"title":' . json_encode($row->text) . ',"link":"http://twitter.com/'.$row->user->screen_name.'/statuses/'.$row->id_str.'","author":"'.$row->user->screen_name.'","publishedDate":"' . date("D, d M Y H:i:s O", strtotime($row->created_at)) . '","contentSnippet":' . json_encode($row->text.$attached) . ',"content":' . json_encode($row->text.$attached) . ',"categories":[]}';
	$count++;
}
}
$json_format .= ']}}, "responseDetails": null, "responseStatus": 200}';

header("Content-Type: application/json; charset=UTF-8");
echo $json_format;
?>