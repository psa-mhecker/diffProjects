<?php
include_once 'Abstract.php';

class Pelican_Request_Route_Image extends Pelican_Request_Route_Abstract
{
    public $folder = array(
        'image',
        'png',
        'gif',
        'jpg',
        'jpeg',
        'bmp',
    );

    protected $type;

    public function eligible()
    {
        $return = false;

        if (substr($this->uri, 0, 6) != 'design') {
            foreach ($this->folder as $type) {
                if (substr($this->uri, 0, strlen($type)) == $type) {
                    $this->type = $type;
                    $return = true;
                    break;
                }
            }
        }

        return $return;
    }

    public function match()
    {
        $return = '';

        $var = array(
            'format',
            'path',
            'bypass',
            'crop',
            'format',
            'path',
            'preview',
        );

        foreach ($var as $get) {
            if (!empty($_REQUEST[$get])) {
                $params[$get] = $_REQUEST[$get];
            }
        }

        preg_match('#\/(png|jpg|gif|jpeg|bmp)\/([0-9]+)\/([0-9]+)\/(.*)#', '/'.$this->uri, $match);
        if ($match) {
            $params['extension'] = $match[1];
            $params['width'] = $match[2];
            $params['height'] = $match[3];
            $params['path'] = str_replace(Pelican::$config['MEDIA_HTTP'], '', $match[4]);
        } else {
            $file = str_replace(Pelican::$config["MEDIA_ROOT"], "", getUploadRoot('/'.$this->uri));
            $pathinfo = pathinfo($file);
            $explode = explode(".", $pathinfo["basename"]);
            if ($explode[count($explode) - 1] == $pathinfo["extension"]) {
                $params['path'] = str_replace(".".$explode[count($explode) - 2].".".$pathinfo["extension"], ".".$pathinfo["extension"], '/'.$this->uri);
                $params['format'] = str_replace("_", "", $explode[count($explode) - 2]);
                $params['extension'] = $pathinfo["extension"];
            }
        }

        if (!$params["format"] && !$params["path"]) {
            //    $params['path'] = "/xtrans.gif";
        }

        if ($params['path']) {
            $params['type'] = $this->route;
            $params['root'] = 'library';
            $params['directory'] = 'Pelican/Controller';
            $params['controller'] = 'Image';
            $params['action'] = 'format';

            $return['route'] = 'image';
            $return['params'] = $params;
        }

        return $return;
    }
}
