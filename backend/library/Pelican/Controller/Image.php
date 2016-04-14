<?php
/**
 * __DESC__.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license   http://www.interakting.com/license/phpfactory
 *
 * @link      http://www.interakting.com
 */
pelican_import('Controller');
pelican_import('Media');
pelican_import('Cache.Media');

/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */
class Pelican_Controller_Image extends Pelican_Controller
{
    public function headers($file)
    {
        $filemtime = filemtime($file);
        // $etag = md5($filemtime);
        $etag = $filemtime.'.'.filesize($file); // __JFO optimisation calcul etag
        $offset = 60 * 60 * 24 * 1; // 1 jour
        $lastmodified = gmdate("D, d M Y H:i:s", $filemtime)." GMT";
        $ifmodifiedsince = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? trim($_SERVER['HTTP_IF_MODIFIED_SINCE'],
            ':') : false;
        $ifnonematch = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim(trim($_SERVER['HTTP_IF_NONE_MATCH'], ':')) : false;

        $this->getRequest()->setHeaders("Last-Modified: ".gmdate("D, d M Y H:i:s", $filemtime)." GMT");
        // $this->getRequest()->setHeaders("Cache-Control: public, must-revalidate");
        $this->getRequest()->setHeaders("Cache-Control: max-age=604800, public"); // optimisation PSA
        $this->getRequest()->setHeaders("Expires: ".gmdate("D, d M Y H:i:s", $filemtime + $offset)." GMT");
        $this->getRequest()->setHeaders('Etag: '.$etag);
        $this->getRequest()->setHeaders('Pragma: ');

        if (!$ifmodifiedsince && !$ifnonematch) {
            return;
        }

        if ($ifmodifiedsince == $lastmodified && $ifnonematch == $etag) {
            $this->getRequest()->setStatus(304);
            // $this->getRequest()->setHeaders("HTTP/1.1 304 Not Modified");
            $this->getRequest()->sendHeaders();
            exit();
        }
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function formatAction()
    {
        $request = Pelican_Request::getMain();
        $params = $request->getParams();
        if (!empty($params["path"])) {
            $params["path"] = str_replace('_http_/', 'http://', $params["path"]);
        }
        if (isset($params["preview"]) && $params["preview"] != "1") {
            $pathinfo = pathinfo($params['path']);
            $store = getUploadRoot(Pelican_Media::getFileNameMediaFormat($params['path'], $params["format"]));
        }

        /*
         * récupération de l'image
         */
        if (empty($imageGab) || !$imageGab) {
            $imageGab = Pelican_Cache::fetch("Frontend/MediaFormat", $params["format"]);
        }
        $image = new Pelican_Cache_Media((!empty($params['path']) ? $params['path'] : ''),
            (!empty($params["format"]) ? $params["format"] : ''), (!empty($params["crop"]) ? $params["crop"] : ''),
            (!empty($params["bypass"]) ? true : false), (!empty($params['life']) ? $params['life'] : ''),
            (!empty($imageGab["MEDIA_FORMAT_COMPLETE_COLOR"]) ? $imageGab["MEDIA_FORMAT_COMPLETE_COLOR"] : ''),
            (!empty($params['width']) ? $params['width'] : ''), (!empty($params['height']) ? $params['height'] : ''),
            (!empty($params['extension']) ? $params['extension'] : ''));

        if (!empty(Pelican::$config['IMAGE_CACHE_DURATION'])) {
            $this->headers($image->name);
        }

        if (!empty($image->value)) {
            if (!empty($store)) {
                $fp = fopen($store, "wb");
                if (fwrite($fp, $image->value)) {
                    fclose($fp);
                }
            } else {
                $this->getRequest()->setHeaders('Content-Type', Pelican_Media::getMimeType($params['path']));
                $this->setResponse($image->value);
            }
        } else {
            $this->getRequest()->sendError(404);
        }
    }
}
