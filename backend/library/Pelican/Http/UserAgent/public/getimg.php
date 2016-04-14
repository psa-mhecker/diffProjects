<?php
include_once 'config.php';
Pelican::$config['SHOW_DEBUG'] = true;

$root = Pelican::$config['MEDIA_ROOT'].'/mobile';

if (! is_dir($root)) {
    mkdir($root);
}

$params['maisondugsm']['host'] = 'http://www.maisondugsm.com';
$params['maisondugsm']['brand']['url'] = 'http://www.maisondugsm.com/accessoire-telephone-portable-mobile.php';
$params['maisondugsm']['brand']['pattern'] = 'accessoire_gsm_pour_mobile_';
//$params['maisondugsm']['brand']['url'] = 'http://www.maisondugsm.com/a/produit/public/40/accessoire_gsm_pour_mobile_htc.html';
$params['maisondugsm']['model']['pattern'] = 'accessoires_pour_';

$params['handsetdetection']['host'] = 'http://www.handsetdetection.com';
$params['handsetdetection']['brand']['url'] = 'http://www.handsetdetection.com/properties/vendormodel';
$params['handsetdetection']['brand']['pattern'] = 'properties\/vendormodel\/';
//$params['maisondugsm']['brand']['url'] = 'http://www.maisondugsm.com/a/produit/public/40/accessoire_gsm_pour_mobile_htc.html';
$params['handsetdetection']['model']['pattern'] = 'hdimages.s3.amazonaws.com';

$params['mobilostore']['host'] = 'http://www.mobilostore.com';
$params['mobilostore']['brand']['url'] = 'http://www.mobilostore.com/index.php?id=match';
$params['mobilostore']['brand']['pattern'] = 'accessoires_telephone\/';
//$params['mobilostore']['brand']['url'] = 'http://www.maisondugsm.com/a/produit/public/40/accessoire_gsm_pour_mobile_htc.html';
$params['mobilostore']['model']['pattern'] = 'Accessoires_pour_';

//$result = getImages('maisondugsm', $params, 'brand');
//$result = getImages('mobilostore', $params, 'brand');
$result = getImages('handsetdetection', $params, 'brand');

var_dump($result);

function getImages($source, $params, $type, $name = '')
{
    $root = Pelican::$config['MEDIA_ROOT'].'/mobile/'.$source.'/'.$type;
    if ($name) {
        $root .= '/'.strtolower($name);
    }
    if (! is_dir($root)) {
        mkdir($root, 0777, true);
    }
    if ($params[$source][$type]['url']) {
        echo($type.' : '.$params[$source][$type]['url'].'<br />');
        flush();
        $htmlsource = file_get_contents($params[$source][$type]['url']);
        $pattern = "/((@import\s+[\"'`]([\w:?=@&\/#._;-]+)[\"'`];)|";
        $pattern .= "(:\s*url\s*\([\s\"'`]*([\w:?=@&\/#._;-]+)";
        $pattern .= "([\s\"'`]*\))|<[^>]*\s+(src|href|url)\=[\s\"'`]*";
        $pattern .= "([\w:?=@&\/#._;-]+)[\s\"'`]*[^>]*>))/i";
        //End pattern building.
        preg_match_all($pattern, $htmlsource, $matches);

        $count = count($matches[8]);
        var_dump($matches[8]);
        if ($matches[8]) {
            for ($i = 0; $i < $count - 1; $i ++) {
                if (preg_match('/'.$params[$source][$type]['pattern'].'(.*)(\.html)?/i', $matches[8][$i], $brand) && ! preg_match('/\.htm/i', $matches[8][$i + 1])) {
                    //echo ($matches[8][$i] . ' -> ' . $matches[8][$i + 1] . '<br />');
                    $result[$type][$brand[1]]['src'] = completeUrl($matches[8][$i + 1], $params[$source]['host']);
                    //var_dump($result[$type][$brand[1]]['src']);
                    $pathinfo = pathinfo($result[$type][$brand[1]]['src']);
                    if (in_array(strtolower($pathinfo['extension']), array(
                        'png',
                        'jpg',
                        'gif',
                    )) && !preg_match('/generic/i', $result[$type][$brand[1]]['src'])) {
                        $path = $root.'/'.strtolower($brand[1]).'.'.$pathinfo['extension'];
                        echo('image : '.$result[$type][$brand[1]]['src'].' -> '.$path.'<br />');
                        flush();
                        copyImg($result[$type][$brand[1]]['src'], $path);
                    }
                    if ($type == 'brand') {
                        $params[$source]['model']['url'] = completeUrl($matches[8][$i], $params[$source]['host']);

                        $result[$type][$brand[1]]['models'] = getImages('maisondugsm', $params, 'model', strtolower($brand[1]));
                    }
                }
            }
        }
    }

    return $result;
}

function copyImg($source, $target)
{
    if (! file_exists($target)) {
        $content = file_get_contents($source);
        if ($content) {
            file_put_contents($target, $content);
        }
    }
    /* grand format */
    $sourceBig = str_replace('75x100', '130x173', $source);
    if ($source != $sourceBig) {
        copyImg($sourceBig, str_replace(array(
            '.gif',
            '.png',
            '.jpg',
        ), array(
            '.big.gif',
            '.big.png',
            '.big.jpg',
        ), $target));
    }
}

function completeUrl($url, $host)
{
    $return = $url;
    if (! preg_match('/http/i', $url)) {
        $return = $host.$url;
    }

    return $return;
}
