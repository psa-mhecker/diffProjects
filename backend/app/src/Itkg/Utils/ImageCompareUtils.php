<?php

namespace  Itkg\Utils;

class ImageCompareUtils
{
    /**
     * @var int
     */
    protected $size = 15;

    /**
     * @var
     */
    private $mime;

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     * @codeCoverageIgnore
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->mime[0];
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->mime[1];
    }


    /**
     * @return int
     */
    public function getMaxDistance()
    {
        return $this->size * $this->size;
    }

    private function mimeType($i)
    {
        /*returns array with mime type and if its jpg or png. Returns false if it isn't jpg or png*/
        try {
            $mime = getimagesize($i);
            $return = array($mime[0], $mime[1]);

            switch ($mime['mime']) {
                case 'image/jpeg':
                    $return[] = 'jpg';

                    return $return;
                case 'image/png':
                    $return[] = 'png';

                    return $return;
                default:
                    return false;
            }
        } catch (\Exception $e) {
            return false;
        }

    }

    private function createImage($i)
    {
        /*retuns image resource or false if its not jpg or png*/
        $mime = $this->mimeType($i);
        $this->mime = $mime;
        if ($mime[2] == 'jpg') {
            return imagecreatefromjpeg($i);
        } else {
            if ($mime[2] == 'png') {
                return imagecreatefrompng($i);
            } else {
                return false;
            }
        }
    }

    private function resizeImage($source)
    {
        /*resizes the image to a 8x8 squere and returns as image resource*/
        $mime = $this->mimeType($source);

        $t = imagecreatetruecolor($this->size, $this->size);

        $source = $this->createImage($source);

        imagecopyresized($t, $source, 0, 0, 0, 0, $this->size, $this->size, $mime[0], $mime[1]);

        return $t;
    }

    private function colorMeanValue($i)
    {
        /*returns the mean value of the colors and the list of all pixel's colors*/
        $colorList = array();
        $colorSum = 0;
        for($a = 0;$a<$this->size;$a++)
        {

            for($b = 0;$b<$this->size;$b++)
            {

                $rgb = imagecolorat($i, $a, $b);

                $colorList[] = $rgb & 0xFF;
                $colorSum += $rgb & 0xFF;

            }

        }

        return array($colorSum / ($this->size * $this->size), $colorList);
    }

    private function bits($colorMean)
    {
        /*returns an array with 1 and zeros. If a color is bigger than the mean value of colors it is 1*/
        $bits = array();

        foreach ($colorMean[1] as $color) {
            $bits[] = ($color >= $colorMean[0]) ? 1 : 0;
        }

        return $bits;

    }

    /**
     * @param string $path
     *
     * @return array
     */
    public function getSignature($path)
    {
        if (!$this->createImage($path)) {
            return false;
        }
        $img = $this->resizeImage($path);

        imagefilter($img, IMG_FILTER_GRAYSCALE);
        $colorMean = $this->colorMeanValue($img);
        $bits = $this->bits($colorMean);

        return $bits;
    }


    /**
     * @param array $bits1
     * @param array $bits2
     *
     * @return int
     */
    public function hammer($bits1, $bits2)
    {
        $hammeringDistance = 0;
        $max = $this->getMaxDistance();
        for ($a = 0; $a < $max; $a++) {

            if ($bits1[$a] != $bits2[$a]) {
                $hammeringDistance++;
            }

        }

        return $hammeringDistance;
    }

    /**
     * @param string $img1
     * @param string $img2
     *
     * @return bool|int
     *
     */
    public function compare($img1, $img2)
    {
        /*main function. returns the hammering distance of two images' bit value*/
        $i1 = $this->createImage($img1);
        $i2 = $this->createImage($img2);
        if (!$i1 || !$i2) {
            return false;
        }


        $bits1 = $this->getSignature($img1);
        $bits2 = $this->getSignature($img2);

        return $this->hammer($bits1, $bits2);
    }
}
