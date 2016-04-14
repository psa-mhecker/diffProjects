<?php
include_once 'config.php';
Pelican::$config['SHOW_DEBUG'] = true;

$root = Pelican::$config['MEDIA_ROOT'].'/mobile';

if (! is_dir($root)) {
    mkdir($root);
}

$result = reorganizeImages();

var_dump($result);

function reorganizeImages()
{
    $oConnection = Pelican_Db::getInstance();

    $root = Pelican::$config['MEDIA_ROOT'].'/mobile/images';
    $files = glob($root.'/*.gif');
    $ok = $root.'/ok/';
    $ko = $root.'/ko/';

    foreach ($files as $img) {
        $pathinfo = pathinfo($img);
        $id = $pathinfo['filename'];
        $data = $oConnection->getRow("select * from #pref#_wurfl where device_id='".$id."'");
        if ($data) {
            $path = $ok.getModelName($data['brand_name'], $data['model_name']);
        } else {
            $path = $ko.basename($img);
        }
        file_put_contents($path, file_get_contents($img));
        echo($path.'<br />');
    }
}

function getModelName($brand, $model)
{
    $brand = str_replace(array(
        'sonyericsson',
        'rim',
    ), array(
        'sony_ericsson',
        'blackberry',
    ), strtolower($brand));

    return str_replace('blackberry_blackberry', 'blackberry', cleanName($brand).'_'.cleanName(strtolower($model)).'.gif');
}

function cleanName($name)
{
    $return = str_replace(array(
        '.',
        ' ',
        '-',
        '/',
    ), array(
        '_',
        '_',
        '_',
        '_',
    ), $name);

    return $return;
}
