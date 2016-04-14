<?php
namespace PsaNdp\MappingBundle\Object\Block;

use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMultiConfigurableInterface;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Media;

/**
 * Class Pc78UspMosaique
 */
class Pc78UspMosaique extends Content
{
    /**
     * @inheritdoc
     */
    protected $mapping = array(
        'datalayer' => 'dataLayer',
    );

    /**
     * @var PsaPageZoneConfigurableInterface
     */
    protected $block;

    /**
     * @var string
     */
    protected $close;

    /**
     * @var Media $media
     */
    protected $media;

    /**
     * @var array
     */
    protected $articles;

    /** @var string ex: id1,id2,id3... */
    protected $popinIds = '';

    /**
     * @return PsaPageZoneConfigurableInterface
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param PsaPageZoneConfigurableInterface $block
     * @return Pc78UspMosaique
     */
    public function setBlock(PsaPageZoneConfigurableInterface $block)
    {
        $this->block = $block;

        $this->setTitle($block->getZoneTitre());

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setTranslate(array $translate)
    {
        parent::setTranslate($translate);

        $this->setClose($translate['close']);

        return $this;
    }

    /**
     * @param string $close
     * @return Pc78UspMosaique
     */
    public function setClose($close)
    {
        $this->close = $close;

        return $this;
    }

    /**
     * @return string
     */
    public function getClose()
    {
        return $this->close;
    }

    /**
     * @param $multis
     * @param string $mediaServer
     * @param bool $isMobile
     * @return Pc78UspMosaique
     */
    public function setArticles($multis, $mediaServer, $isMobile)
    {
        $iterate = 0;
        $colMedia = [];
        $popinIds = [];

        foreach($multis as $multi) {
            /** @var PsaPageZoneMultiConfigurableInterface $multi */

            $media = $this->initializeMedia($multi, $mediaServer, $isMobile, $iterate);

            $colMedia[] = array(
                'small' => ($iterate === 1 || $iterate === 2) ? 1 : null,
                'img' => $media,
                'text' => $multi->getPageZoneMultiLabel(),
                'index' => $iterate,
            );

            $popinIds[] = $multi->getPageZoneMultiValue();
            $iterate++;
        }

        $this->popinIds = implode(',', $popinIds);

        if($isMobile){
            $this->initializeMobileArticles($colMedia);
        }

        if (!$isMobile) {
            $this->initializeDesktopArticles($colMedia);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param PsaPageZoneMultiConfigurableInterface $multi
     * @param $mediaServer
     * @param boolean $isMobile
     * @param integer $iteration
     * @return Media
     */
    private function initializeMedia(PsaPageZoneMultiConfigurableInterface $multi, $mediaServer, $isMobile, $iteration)
    {
        $formats = [
            'mobile' => [
                96,
                104,
                104,
                96,
                107,
                107
            ],
            'desktop' => [
                97,
                105,
                105,
                97,
                108,
                100
            ]
        ];

        /** @var PsaMedia $media */
        $psaMedia = $multi->getMedia();

        $media = new Media();
        $media->setSource($mediaServer.$psaMedia->getMediaPathWithFormat($formats[$isMobile ? 'mobile' : 'desktop'][$iteration]));
        $media->setAlt($psaMedia->getMediaAlt());
        return $media;
    }

    /**
     * @param $colMedia
     */
    private function initializeDesktopArticles($colMedia)
    {
        $this->articles[] = array(
            'unique' => false,
            'cols' => array(
                array(
                    (isset($colMedia[0]) ? $colMedia[0] : array()),
                    (isset($colMedia[4]) ? $colMedia[4] : array()),
                ),
                array(
                    (isset($colMedia[1]) ? $colMedia[1] : array()),
                    (isset($colMedia[2]) ? $colMedia[2] : array()),
                    (isset($colMedia[3]) ? $colMedia[3] : array()),
                )
            )
        );

        if (isset($colMedia[5])){
            $this->articles[] = array(
                'unique' => true,
                'cols' => array(
                    array(
                        $colMedia[5],
                    )
                )
            )
            ;
        }
    }

    /**
     * @param $colMedia
     */
    private function initializeMobileArticles($colMedia)
    {
        $this->articles[] = array(
            'unique' => true,
            'cols' => array(
                array(
                    (isset($colMedia[0]) ? $colMedia[0] : array()),
                ),
            )
        );

        $this->articles[] = array(
            'unique' => false,
            'cols' => array(
                array(
                    (isset($colMedia[1]) ? $colMedia[1] : array()),
                    (isset($colMedia[2]) ? $colMedia[2] : array()),
                ),
            )
        );

        $this->articles[] = array(
            'unique' => true,
            'cols' => array(
                array(
                    (isset($colMedia[3]) ? $colMedia[3] : array()),
                ),
            )
        );

        $this->articles[] = array(
            'unique' => true,
            'cols' => array(
                array(
                    (isset($colMedia[4]) ? $colMedia[4] : array()),
                ),
            )
        );

        if (isset($colMedia[5])){
            $this->articles[] = array(
                'unique' => true,
                'cols' => array(
                    array(
                        (isset($colMedia[5]) ? $colMedia[5] : array()),
                    ),
                )
            );
        }
    }

    /**
     * @return string
     */
    public function getPopinIds()
    {
        return $this->popinIds;
    }

    /**
     * @param string $popinIds
     *
     * @return Pc78UspMosaique
     */
    public function setPopinIds($popinIds)
    {
        $this->popinIds = $popinIds;

        return $this;
    }
}
