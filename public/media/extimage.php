<?php

/**
 ** Script permettant de transférer un fichier d'un serveur distant au serveur local, avec mise en cache.
 **
 ** Permet d'accéder au serveur de fichier d'image intranet a partir de l'internet. 
 ** Il faut configurer chaque site intranet que l'on souhaite accéder dans le tableau $CONFIGS.
 ** La clée du tableau indique le nom du service, et doit être passé en parametre de l'url.
 ** Les fichiers sont mis en cache dans le rep de cache de PHPFactory (var/cache/extimage)
 **
 ** Paramètres d'appel de l'url : 
 **  service : nom du service (clée d'entrée dans $CONFIGS)
 **  image : image ou fichier a aller chercher
 ** 
 **/
//include_once ("config.php"); <-- si on inclu le config de phpfactory, on perd la mise en cache par les headers du navigateur

@ob_clean();

$CONFIGS['aoa'] = array(
	// Config AOA
	// URL internet : extimage.php?service=aoa&image=948266/0/948266_1CB7_A5_CSA01_wide.png
	// URL intranet : http://aoaccessoire.inetpsa.com/aoa00Pds/servlet/948266/0/948266_1CB7_A5_CSA01_wide.png
	'cache_duration'=>6*3600, /** 6 heures . Si la durée change, il faut modifier le batch de purge des fichiers anciens **/
	'base_url'=>'http://aoaccessoire.inetpsa.com/aoa00Pds/servlet/%image%',
	'use_proy'=>false
);

$service = @$_REQUEST['service'];
$image  = @$_REQUEST['image'];

//$cache = Pelican::$config["CACHE_FW_ROOT"].'/../extimage/';
$cache = "../../var/cache/extimage/";

$default_chmod = 0777;

function die_404($hidden_reason)
{
header("HTTP/1.0 404 Not Found");
?>
<html>
<h1>404 Not Found !</h1>
<!---
<?= $hidden_reason ?>
-->
</html>
<?
die;
}
if ($service != '' && $image != '' && isset($CONFIGS[$service]) )
{
	$SERVICE_CONFIG = $CONFIGS[$service];
	$hash = md5($service.$base_url.$image);
	
	$dir1 = $hash[0].$hash[1];
	$dir2 = $hash[2];
	$ext = strrchr($image, '.');
	$file = $cache.$dir1.DIRECTORY_SEPARATOR.$dir2.DIRECTORY_SEPARATOR.$hash.$ext;
	if (!file_exists($file))
	{
		$revalidate = true;
		header('Extimage: not-in-cache');
	}
	else
	{
		$filemtime = filemtime($file);
		
		if (time() - $filemtime > $SERVICE_CONFIG['cache_duration'])
		{
			$revalidate = true;
			header('Extimage: expired');
		}
	}
	
	if ($revalidate)
	{		
		$real_url = str_replace( 
			array('%image%', '%service%', '%cache_duration%', '%ts%'), 
			array($image, $service, $SERVICE_CONFIG['cache_duration'], time()), 
			$SERVICE_CONFIG['base_url']);


                        
		@mkdir($cache.$dir1.DIRECTORY_SEPARATOR.$dir2,$default_chmod,true);
		//@chmod($cache.$dir2, $default_chmod);
		$auth = base64_encode('mdecpw00:svncpw00');

                               $aContext = array(
                                               'http' => array(
                                                               'proxy' => 'tcp://relaishttp.sgppsa.com:80',
                                                               'request_fulluri' => true,
                                                               'header' => "Proxy-Authorization: Basic $auth",
                                               ),
                               );
                               $cxContext = stream_context_create($aContext);
                               
                               $res = @copy($real_url, $file, $cxContext);
		
		if ($res === false)
		{
			$error = error_get_last();
			die_404($error['message']);
		}
		@touch($file);
		//@chmod($file, $default_chmod);
	}
	else
	{
		header('Extimage: in-cache');
	}
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$mime = finfo_file($finfo, $file);

	$filemtime = filemtime($file);
	
	header("Extimage-date: ". date('r', $filemtime));
	header("Content-type: $mime");
	header('Last-Modified: '.gmdate('D, d M Y H:i:s', $filemtime).' GMT', true, 200);
	header('Content-Length: '.filesize($file));
	///header("Expires: ",date('z'));
	@readfile($file, false);
	die;

}
else
{
	die_404('');
}
