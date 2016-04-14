<?php
$windows = "";
$call = "";

switch ($ext) {
    case 'pnm':
    case 'pgm':
    case 'pbm':
    case 'ppm':
    case 'pcx': {
        $linux = "/usr/local/bin/gocr";
        $output = " > ";
        break;
    }
    case 'jpeg':
    case 'jpg':
    case 'png':
    case 'tif':
    case 'tiff':
    case 'gif': {
        //$linux = dirname(__FILE__)."/bin/linux/anytopnm.sh ".tempnam("/tmp", 'php-dest').".pnm";
        //debug($linux);
        function parseFile_ocr($file, $withPorperties = false)
        {
            $return = "";
            $params['%1'] = $file;
            $params['%2'] = tempnam("/tmp", 'php-dest').".pnm";
            $params['%3'] = tempnam("/tmp", 'php-dest').".txt";
            $cmd = dirname(__FILE__)."/bin/linux/anytopnm.sh %1 %2 %3";

            system(strtr($cmd, $params));
            //debug(strtr($cmd,$params));

            if (file_exists($params['%3'])) {
                $return = file_get_contents($params['%3']);
            }
            @unlink($params['%2']);
            @unlink($params['%3']);

            return $return;
        }
        break;
    }
}

$extension = ".txt";

/*{
$linux = "djpeg -pnm -gray";
$output = " | /usr/local/bin/gocr - > ";
break;
}*/
