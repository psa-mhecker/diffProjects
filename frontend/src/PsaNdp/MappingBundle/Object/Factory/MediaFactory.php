<?php

namespace PsaNdp\MappingBundle\Object\Factory;

use PSA\MigrationBundle\Repository\PsaSiteRepository;
use PsaNdp\MappingBundle\Object\Image;
use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PsaNdp\MappingBundle\Object\Streamlike;
use PsaNdp\MappingBundle\Services\MediaServerInitializer;
use PsaNdp\MappingBundle\Utils\StreamlikeMedia;
use PsaNdp\MappingBundle\Object\MediaInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PSA\MigrationBundle\Repository\PsaMediaFormatRepository;

/**
 * Class MediaFactory.
 */
class MediaFactory
{
    const DEFAULT_FORMAT = 'NDP_DEFAULT_FORMAT';

    /**
     * @var array
     */
    protected $definedSize = array(
        'original', // original image from media library
        'default', // d image from media library
        'mobile', // mobile version
        'desktop', // desktop version
        'retina-desktop', // retina desktop version
        'retina-mobile', //retina-mobile retina version
    );

    /**
     * @var array
     */
    protected $definedOptions = array(
        'original', // original image from media library
        'alt', // string
        'title', // string
        'src', // string
        'format', // format id
        'autoCrop', // boolean
        'blank', // blank image for lazyload
        'size', // array of image possible size
    );

    /**
     * @var StreamlikeMedia
     */
    protected $streamLikeMedia;
    /**
     * @var string
     */
    protected $mediaServer;

    /**
     * @var array
     */
    private $formatCache;

    /**
     * @var PsaMedia
     */
    protected $streamlikeDefaultCover;

    /**
     * @var PsaSiteRepository
     */
    private $siteRepository;

    /**
     * @var Request
     */
    private $request;

    /**
     * MediaFactory constructor.
     *
     * @param StreamlikeMedia          $streamlikeMedia
     * @param MediaServerInitializer   $mediaServer
     * @param PsaMediaFormatRepository $mediaFormatRepository
     * @param PsaSiteRepository        $siteRepository
     * @param RequestStack             $requestStack
     */
    public function __construct(
        StreamlikeMedia $streamlikeMedia,
        MediaServerInitializer $mediaServer,
        PsaMediaFormatRepository $mediaFormatRepository,
        PsaSiteRepository $siteRepository,
        RequestStack $requestStack = null
    ) {
        $this->streamLikeMedia = $streamlikeMedia;
        $this->mediaServer = $mediaServer->getMediaServer();
        $this->siteRepository = $siteRepository;
        if ($requestStack !== null) {
            $this->request = $requestStack->getCurrentRequest();
        }
        $this->formatCache = [];
        // initialisation du cache des formats d'image
        $formats = $mediaFormatRepository->findAll();
        /** @var \PSA\MigrationBundle\Entity\Media\PsaMediaFormat  $format */
        foreach ($formats as $format) {
            $this->formatCache[$format->getMediaFormatLabel()] = $format->getMediaFormatId();
        }
    }

    /**
     * @throws \Exception
     * @codeCoverageIgnore
     */
    public function createMedia()
    {
        throw new \Exception('Deprecated');
    }

    /**
     * @throws \Exception
     * @codeCoverageIgnore
     */
    public function createFromArray()
    {
        throw new \Exception('Deprecated');
    }

    /**
     * @param PsaMedia $media
     * @param array    $options
     *
     * @return MediaInterface
     *
     * @throws \Exception
     */
    protected function createMediaImage(PsaMedia $media, $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $image = new Image();
        $options['alt'] = $media->getMediaAlt();
        $options['title'] = $media->getMediaTitle();
        // kepp src for BC until userstory replace this
        $options['src'] = $options['original'] = $this->mediaServer.$media->getMediaPath();
        $options['blank'] = $this->mediaServer.Image::BLANK_IMAGE;
        // keep format for BC until userstory replace this
        if (!empty($options['format'])) {
            $formatId = $this->getMediaFormatid($options['format']);
            $options['src'] = $this->mediaServer.$media->getMediaPathWithFormat($formatId);
        }
        // new way to define multiple image size for each display port
        $options = $resolver->resolve($options);
        $options['size'] = $this->buildImageSize($options['size'], $media, $options['autoCrop']);

        $image->setDataFromArray($options);

        return $image;
    }

    /**
     * @param PsaMedia $media
     *
     * @return MediaInterface
     */
    protected function createMediaStreamlike(PsaMedia $media)
    {
        $return = null;
        if (!is_null($media->getMediaRemoteId())) {
            $return = $this->streamLikeMedia->get($media->getMediaRemoteId());
            if ($return instanceof Streamlike) {
                $return->setBlank($this->mediaServer.Streamlike::BLANK_IMAGE);
                if (!$return->getPoster()) {
                    $return->setPoster($this->mediaServer.$this->getStreamlikeDefaultCover());
                }
            }
        }

        return $return;
    }

    /**
     * @param PsaMedia $media
     * @param array    $options
     *
     * @return MediaInterface
     *
     * @throws \Exception
     */
    public function createFromMedia(PsaMedia $media, $options = array())
    {
        $type = $media->getMediaType()->getMediaTypeId();
        switch ($type) {

            case PsaMedia::IMAGE:
                $return = $this->createMediaImage($media, $options);
                break;
            case PsaMedia::STREAMLIKE:
                $return = $this->createMediaStreamlike($media);
                break;
            default:
                throw new \InvalidArgumentException(' Unsupported media type');

        }

        return $return;
    }

    /**
     * @param mixed $format
     *
     * @return int
     */
    protected function getMediaFormatid($format)
    {
        if (isset($this->formatCache[$format])) {
            $format = $this->formatCache[$format];
        }

        return $format;
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined($this->definedOptions);
        $resolver->setDefaults(array(
            'format' => null,
            'autoCrop' => false,
            'size' => [],
        ));

        $resolver->setRequired(array('title', 'alt', 'src', 'original'));
    }

    /**
     * @return null|MediaInterface
     */
    protected function getStreamlikeDefaultCover()
    {
        $cover = null;
        if (!empty($this->request)) {
            $psaSite = $this->siteRepository->findOneBy(
                array(
                    'siteId' => (int) $this->request->get('siteId'),
                )
            );
            $cover = $psaSite->getStreamlikeDefaultCover()->getMediaPath();
        }

        return $cover;
    }

    /**
     * @param array    $sizes
     * @param PsaMedia $media
     * @param bool     $autoCrop
     *
     * @return array
     */
    public function buildImageSize(array $sizes, PsaMedia $media, $autoCrop)
    {
        $resolver = new OptionsResolver();
        $this->configureSize($resolver);
        $sizes = $resolver->resolve($sizes);
        $return = [];
        foreach ($sizes as $name => $format) {
            $return[$name] = $this->mediaServer.$media->getMediaPath();
            if (!empty($format)) {
                $formatId = $this->getMediaFormatid($format);
                $return[$name] = $this->mediaServer.$media->getMediaPathWithFormat($formatId);
            }
            if ($autoCrop && $name != 'original') { // on desactive l'autocrop sur l'original
                $return[$name] .= '?autocrop=1';
            }
        }

        return $return;
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureSize(OptionsResolver $resolver)
    {
        $resolver->setDefined($this->definedSize);
        $resolver->setDefaults(array(
            'default' => self::DEFAULT_FORMAT,
            'original' =>  self::DEFAULT_FORMAT,
        ));
        $resolver->setDefault('desktop', function (Options $options) {
            return $options['default'];
        });
        $resolver->setDefault('mobile', function (Options $options) {
            return $options['desktop'];
        });
    }
}
