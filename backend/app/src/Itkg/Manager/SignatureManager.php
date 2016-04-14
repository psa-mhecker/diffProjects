<?php

namespace Itkg\Manager;

use Itkg\Utils\ImageCompareUtils;
use PSA\MigrationBundle\Entity\Media\PsaMedia;

class SignatureManager
{
    /**
     * @var string
     */
    protected $tmpDwdDirectory;

    /**
     * @var ImageCompareUtils
     */
    private $compareUtils;

    /**
     * @var int
     */
    private $siteId = 0;

    /**
     * SignatureManager constructor.
     *
     * @param ImageCompareUtils $compareUtils
     * @param string            $migrationDirectory
     */
    public function __construct(ImageCompareUtils $compareUtils, $migrationDirectory)
    {
        $this->compareUtils = $compareUtils;
        $this->tmpDwdDirectory = getenv('BACKEND_VAR_PATH').DIRECTORY_SEPARATOR.$migrationDirectory;
    }

    /**
     * @return int
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @param int $siteId
     *
     * @return $this
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;

        return $this;
    }

    private function getSignaturePath()
    {
        return  $this->tmpDwdDirectory.DIRECTORY_SEPARATOR.'images_'.$this->siteId.'.signatures';
    }

    /**
     * @return array
     */
    public function getSignatures()
    {
        if (!file_exists($this->getSignaturePath())) {
            $this->saveSignatures([]);
        }

        return json_decode(file_get_contents($this->getSignaturePath()), true);
    }

    private function saveSignatures($signatures)
    {
        file_put_contents($this->getSignaturePath(), json_encode($signatures));
    }

    /**
     * @param PsaMedia $media
     *
     * @return $this
     */
    public function generateImageSignature(PsaMedia $media)
    {
        $signatures = $this->getSignatures();
        $signature = $this->compareUtils->getSignature(\Pelican::$config['MEDIA_ROOT'].$media->getMediaPath());
        $img = [
            'signature' => $signature,
            'id' => $media->getMediaId(),
            'path' => $media->getMediaPath(),
            'width' => $media->getMediaWidth(),
            'height' => $media->getMediaHeight(),
        ];
        $signatures[$media->getMediaId()] = $img;
        $this->saveSignatures($signatures);

        return $this;
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function removeSignature($id)
    {
        $signatures = $this->getSignatures();
        unset($signatures[$id]);
        $this->saveSignatures($signatures);

        return $this;
    }
}
