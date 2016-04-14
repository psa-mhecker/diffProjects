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
 **  nocache : 1 pour forcer le décache
 **
 **/
include_once("config.php"); // <-- si on inclu le config de phpfactory, on perd la mise en cache par les headers du navigateur
@ob_clean();

$serviceConfig['proxy_host'] = Pelican::$config['PROXY']['HOST'];
$serviceConfig['proxy_login'] = Pelican::$config['PROXY']['LOGIN'];
$serviceConfig['proxy_password'] = Pelican::$config['PROXY']['PWD'];
$serviceConfig['proxy_port'] = Pelican::$config['PROXY']['PORT'];


$CONFIGS['aoa'] = array(
    // Config AOA
    // URL internet : extimage.php?service=aoa&image=948266/0/948266_1CB7_A5_CSA01_wide.png
    // URL intranet : http://aoaccessoire.inetpsa.com/aoa00Pds/servlet/948266/0/948266_1CB7_A5_CSA01_wide.png
    'cache_duration' => 6 * 3600, /** 6 heures . Si la durée change, il faut modifier le batch de purge des fichiers anciens **/
    'base_url' => 'http://aoaccessoire.inetpsa.com/aoa00Pds/servlet%image%'
);

$context = null;
// Faut-il passer par un proxy ?
if ($serviceConfig['proxy_host'] != '') {
    $auth = base64_encode($serviceConfig['proxy_login'] . ':' . $serviceConfig['proxy_password']);
    $opts = array('http' =>
        array(
            'proxy' => 'tcp://' . $serviceConfig['proxy_host'] . ':' . $serviceConfig['proxy_port'],
            'request_fulluri' => true,
            'header' => array(
                "Proxy-Authorization: Basic $auth"
            )
        )
    );
    $context = stream_context_create($opts);

}

$service = isset($_REQUEST['service']) ? $_REQUEST['service'] :  '';
$image   = isset($_REQUEST['image'])  ? $_REQUEST['image'] : '';
$nocache = (isset($_REQUEST['nocache']) && $_REQUEST['nocache']=== '1');
$debug   = (isset($_REQUEST['debug']) && $_REQUEST['debug']=== '1');

$cache = Pelican::$config["CACHE_FW_ROOT"] . '/../extimage/';
$base_url = $CONFIGS['aoa']['base_url'];
$default_chmod = 0777;

function die_404($hidden_reason)
{
    header("HTTP/1.0 404 Not Found");
    ?>
    <html>
    <h1>404 Not Found !</h1>
    <!--
<?= $hidden_reason ?>
-->
    </html>
    <?
    die;
}


if ($service != '' && $image != '' && isset($CONFIGS[$service])) {
    $SERVICE_CONFIG = $CONFIGS[$service];
    $hash = md5($service . $base_url . $image);

    $dir1 = $hash[0] . $hash[1];
    $dir2 = $hash[2];
    $ext = strrchr($image, '.');
    $file = $cache . $dir1 . DIRECTORY_SEPARATOR . $dir2 . DIRECTORY_SEPARATOR . $hash . $ext;

    if ($debug) {
        echo "Local time : ", date('r'), '<br/>';
        echo "Image request : $image<br/>";
        echo "From service : $service<br/>";
        echo "Service settings : ";
        var_dump($CONFIGS[$service]);
        echo "<br/>";
        echo "Local file : $file<br/>";
    }
    $revalidate = false;
    if (!file_exists($file) || $nocache === true) {
        $revalidate = true;
        header('Extimage: not-in-cache');
    } else {
        $filemtime = filemtime($file);

        if (time() - $filemtime > $SERVICE_CONFIG['cache_duration']) {
            $revalidate = true;
            header('Extimage: expired');
        }
    }
    if ($debug) {
        echo "File exists : ";
        var_dump(file_exists($file));
        echo "<br/>File mtime : ", date('r', $filemtime), "<br/>";
        echo "Cache duration : ", $SERVICE_CONFIG['cache_duration'], "<br/>";
        echo "=> revalidate : ";
        var_dump($revalidate);
        echo "<hr/>";

    }
    if ($revalidate) {
        $real_url = str_replace(
            array('%image%', '%service%', '%cache_duration%', '%ts%'),
            array($image, $service, $SERVICE_CONFIG['cache_duration'], time()),
            $SERVICE_CONFIG['base_url']);
        @mkdir($cache.$dir1.DIRECTORY_SEPARATOR.$dir2, $default_chmod, true);

        if ($debug) {
            echo "Copy from $real_url<br/>to $file<br/>";
            echo "Use proxy : ";
            var_dump($context != null);
            echo "<br/>";
        }
        $T = microtime(true);

        if ($context != null)
            $res = @copy($real_url, $file, $context);// Copy avec un proxy
        else
            $res = @copy($real_url, $file);
        $T = microtime(true) - $T;
        if ($res === false) {
            $error = error_get_last();
            die_404($error['message']);
        }
        @touch($file);

        if ($debug) {
            echo "Copy done in $T s<br/>";
        }

    } else {
        header('Extimage: in-cache');
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file);


    $filemtime = filemtime($file);
    header("Extimage-date: " . date('r', $filemtime));
    header("Content-type: $mime");
    if ($debug) header("Content-type: text/html");
    header('Pragma: ');
    header('Cache-Control: public, max-age=' . $SERVICE_CONFIG['cache_duration']);
    header('Expires: ' . date('r', $filemtime + $SERVICE_CONFIG['cache_duration']));
    if (!$debug) {
        @readfile($file, false);
    } else {
        echo "Image transmitted to browser :<br/>";
        echo "$file<br>";
        echo " size = ", filesize($file);
    }
    die;

} else {
    die_404('');
}

