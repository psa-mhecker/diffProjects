<?php

class Image_Controller extends Pelican_Controller_Image
{
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
            $store = getUploadRoot(Pelican_Media::getFileNameMediaFormat($params['path'], $params["format"]));
        }

        /*
         * récupération de l'image
         */
        $imageGab = Pelican_Cache::fetch("Frontend/MediaFormat", $params["format"]);

        if(isset($params['autocrop']) && $params['autocrop'] && !empty($imageGab)) {
           $this->getAutoCrop($imageGab, $params);
        }



        $image = Pelican_Factory::getInstance('Cache.Media', array(
            (!empty($params['path']) ? $params['path'] : ''),
            (!empty($params["format"]) ? $params["format"] : ''), (!empty($params["crop"]) ? $params["crop"] : ''),
            (!empty($params["bypass"]) ? true : false), (!empty($params['life']) ? $params['life'] : ''),
            (!empty($imageGab["MEDIA_FORMAT_COMPLETE_COLOR"]) ? $imageGab["MEDIA_FORMAT_COMPLETE_COLOR"] : ''),
            (!empty($params['width']) ? $params['width'] : ''), (!empty($params['height']) ? $params['height'] : ''),
            (!empty($params['extension']) ? $params['extension'] : ''),
            (!empty($params['ratioWithoutBlank']) ? $params['ratioWithoutBlank'] : false),
            (!empty($params['autocrop']) ? $params['autocrop'] : false)
        ));
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

    /**
     * Generation des porametre des valeurs de crop pour le mode automatique
     *
     * @param array $imageFormat
     * @param array $params
     */
    protected function getAutoCrop(array $imageFormat, array &$params) {

        // il faut que l'image soit plus grande que le la taille destination
        list($width, $height) = @getimagesize( getUploadRoot($params['path']));
        $newWidth = $imageFormat['MEDIA_FORMAT_WIDTH'];
        $newHeight = $imageFormat['MEDIA_FORMAT_HEIGHT'];
        if ($newWidth> $width || $newHeight > $height)
        {
            return ;
        }
        $ratioW= $width/$newWidth;
        $ratioH =$height/$newHeight;

        // on agrandi le format final pour matcher une dimension de l'image
        if($ratioW >= $ratioH) {
            // centrage horizontal

            $cropedWidth = floor($newWidth * $ratioH);
            $decalX = floor(($width-$cropedWidth)/2);
            $params['crop'] = implode(',', [$decalX, 0, $cropedWidth, $height]);
        }
        if($ratioW < $ratioH) {
            //centrage vertical
            $cropedHeight = floor($newHeight * $ratioW);
            $decalY = floor(($height-$cropedHeight)/2);
            $params['crop'] = implode(',', [0, $decalY, $width, $cropedHeight]);
        }
    }
}
