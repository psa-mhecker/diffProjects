<?php



$aInclude = realpath(dirname(__FILE__).'/../../backend');
echo "Répertoire de base du backend : $aInclude\r\n";

$TYPE_ENVIRONNEMENT=@$_ENV['TYPE_ENVIRONNEMENT'];
if ($TYPE_ENVIRONNEMENT == '')
{
	echo "ERREUR : Variable d'environnement TYPE_ENVIRONNEMENT non définie\r\n";
	exit(1);
}

// RecrÃ©ation d'un objet Pelican
class Pelican {
	static $config;
}

$inc = $aInclude.'/application/configs/local/'.$TYPE_ENVIRONNEMENT.'.ini.php';
if (!file_exists($inc))
{
	echo "ERREUR : Impossible de charger le fichier de configuration $inc\r\n";
	exit(2);
}
include_once($inc);

if (@Pelican::$config['PROXY']['HOST'] != '')
{
	// Utilisation du proxy
	$wget_opt = "-e use_proxy=on -e http_proxy=".Pelican::$config['PROXY']['HOST']." -e https_proxy=".Pelican::$config['PROXY']['HOST'].
		" -e proxy_user=".Pelican::$config['PROXY']['LOGIN']." -e proxy_password=".Pelican::$config['PROXY']['PWD'];
}
else
{
	$wget_opt = '';
}
$wurfl_out = $_ENV['BACKEND_VAR_PATH']."/wurfl/wurfl.zip";
$wurfl_tmp = "/tmp/wurfl.zip";
@mkdir(dirname($wurfl_out));

@chmod(dirname($wurfl_out), 0777);
$cmd = "wget $wget_opt -T 20 -N --header=\"If-Modified-Since: `date -r $wurfl_out --utc --rfc-2822 2>/dev/null || date --utc --rfc-2822 --date='1 week ago'`\" http://www.scientiamobile.com/wurfl/nhure/wurfl.zip -O $wurfl_tmp";
echo "$cmd\r\n";
passthru($cmd);
if (filesize($wurfl_tmp) === 0)
{
	echo "Pas de nouvelle version a installer.\r\n";
	exit(0);
}
echo "Un nouveau fichier WURFL doit être installé.\r\n";

$cmd = "mv $wurfl_tmp $wurfl_out";
passthru($cmd);

$cmd = "unzip -o $wurfl_out -d ".dirname($wurfl_out);
passthru($cmd);

@mkdir(dirname($wurfl_out).'/cache');
@mkdir(dirname($wurfl_out).'/cache/mobile');
@chmod(dirname($wurfl_out).'/cache/mobile', 0777);

