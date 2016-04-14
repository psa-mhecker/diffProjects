<?php

namespace PsaNdp\MappingBundle\Object\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PSA\MigrationBundle\Entity\Cta\PsaCta;
use PSA\MigrationBundle\Entity\Cta\PsaCtaReferenceCommonInterface;
use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PsaNdp\MappingBundle\Object\Cta;
use PsaNdp\MappingBundle\Object\Vehicle;
use PsaNdp\MappingBundle\Services\MediaServerInitializer;
use PsaNdp\MappingBundle\Services\ShareServices\ShareObjectService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtaFactory
{
    const OPTION_CLASS = 'class';
    const OPTION_HREF = '#';
    const OPTION_PREFIX_TITLE = 'prefixTitle';

    // Style: dropdown, boutton, lien, media, (désactivé?)
    // couleur: pour les boutons
    // url pour tous sauf la liste déroulante
    // titre pour tous
    // target pour tous sauf la liste déroulante
    // dropdown les options
    // le media que pour certaine tranche (passer en argument de la méthode de création)
    // attribut inline les ctas s'affiche en ligne (true par defaut) sinon en colonne


    /**
     * @var string $mediaServer
     */
    protected $mediaServer;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var ShareObjectService
     */
    protected $shareObjectService;

    /**
     * @var array
     */
    private $definedOptions = array(
        'href' ,
        'type' , //TYPE_HIDDEN, TYPE_DROPDOWNLIST, TYPE_SIMPLELINK, TYPE_BUTTON, TYPE_CLICK_TO_CALL, TYPE_CLICK_TO_CHAT, TYPE_CLICK_WEB_CALLBACK, TYPE_JS
        'color',
        'inline' ,
        self::OPTION_PREFIX_TITLE,
        'media', // true or false
        'icon', //true or false
        'target', // _self or _blank
        'options' ,
        'image',
        'alt',
        'popinId',
        'dropDownId',
        'lcdv16', // code lcdv16 du véhicule configurer pour la page
    );

    /**
     * Constructor
     *
     * @param MediaServerInitializer $mediaServer
     * @param RequestStack           $requestStack
     * @param ShareObjectService     $shareObjectService
     */
    public function __construct(MediaServerInitializer $mediaServer, RequestStack $requestStack, ShareObjectService $shareObjectService)
    {
        $this->mediaServer = $mediaServer->getMediaServer();
        $this->requestStack = $requestStack;
        $this->shareObjectService = $shareObjectService;
    }

    /**
     * @param Collection $ctaReferences
     * @param array      $options
     * @see
     *
     * @return ArrayCollection
     */
    public function create($ctaReferences, array $options = array())
    {
        $ctaCollection = new ArrayCollection();
        $displayThumbnails = false;
        $request = $this->requestStack->getCurrentRequest();
        $hasMedia = !empty($options['media']) || !empty($options['icon']);
        $dropDownId = 0;
        $id = str_replace('.', '_', $request->get('blockPermanentId'));

        if (isset($options['dropDownId'])) {
            $id .= '_'.$options['dropDownId'];
        }

        foreach ($ctaReferences as $ctaReference) {
            $options['dropDownId'] = $dropDownId . '_' . $id;
            $cta = $this->createFromReference($ctaReference, $options);

            if ($cta) {
                if ($hasMedia && $cta->getImage()) {
                    $displayThumbnails = true;
                }

                $dropDownId++;

                $ctaCollection->add($cta);
            }
        }

        if(!$displayThumbnails && $ctaCollection->count() > 0){
            $ctaCollection->map(function(Cta $cta){
                $cta->setImage(null);
            });
        }

        return $ctaCollection;
    }

    /**
     * @param PsaCtaReferenceCommonInterface $ctaReference
     * @param array                          $options voir les options disponible dans $definedOptions
     *
     * @return Cta
     */
    public function createFromReference(PsaCtaReferenceCommonInterface $ctaReference, array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $cta = $ctaReference->getCta();

        // some time reference exist but not cta ...
        if ($cta === null) {
            return;
        }

        $options['url'] = $cta->getAction();

        // title
        $options['title'] = $cta->getTitle();
        $titleMobile = $cta->getTitleMobile();

        if ($this->shareObjectService->isMobile() && !empty($titleMobile)) {
            $options['title'] = $titleMobile;
        }

        if (!empty($options[self::OPTION_PREFIX_TITLE])) {
            $options['title'] = sprintf('%s %s', $options[self::OPTION_PREFIX_TITLE], $options['title']);
        }

        //Type
        $this->getType($ctaReference, $options);

        // target
        $options['target'] = $this->getTarget($ctaReference);

        if($options['target'] === PsaCta::TARGET_POPIN ){
            $request = $this->requestStack->getCurrentRequest();
            $options['popinId'] = $request->get('blockPermanentId').$cta->getId();
        }

        $this->defineMediaOption($cta, $options);
        $this->defineMediaIcon($cta, $options);
        $this->contextualize($options);

        return $this->createFromArray($resolver->resolve($options));
    }

    /**
     * @param PsaCta $cta
     * @param array $options
     */
    protected function defineMediaOption(PsaCta $cta, &$options = array())
    {
        if (!empty($options['media']) && $options['media']) {
            $media = $cta->getMediaWeb();
            if (!empty($media)) {
                $this->getMedia($media, $options);
            }
        }
    }

    /**
     * @param PsaCta $cta
     * @param array  $options
     */
    protected function defineMediaIcon(PsaCta $cta, &$options = array())
    {
        if (!empty($options['icon']) && $options['icon']) {
            $media = $cta->getMediaMobile();
            if (!empty($media)) {
                $this->getMedia($media, $options);
            }
        }
    }

    /**
     * @param array $options
     */
    protected function contextualize(&$options = array())
    {
        if ($this->shareObjectService->hasVehicle()) {
            $vehicle = $this->shareObjectService->getVehicle();
            if ($vehicle instanceof Vehicle) {
                $options['lcdv16'] = $vehicle->getLcdv16();
            }
        }
    }

    /**
     * @param array $data
     *
     * @return Cta
     */
    public function createCtaFromArray(array $data)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        return $this->createFromArray($resolver->resolve($data));
    }

    /**
     * @param array $data
     *
     * @return Cta
     */
    public function createFromArray(array $data)
    {
        $cta = new Cta();

        $cta->setDataFromArray($data);

        return $cta;
    }

    /**
     * @param PsaCtaReferenceCommonInterface $ctaReference
     * @param array                          $options
     *
     * @return array
     */
    protected function getType(PsaCtaReferenceCommonInterface $ctaReference, &$options = array())
    {
        switch ($ctaReference->getReferenceStatus()) {
            case PsaCtaReferenceCommonInterface::PSA_REFERENCE_STATUS_DROPDOWN:
                //get children cta
                $options['type']    = Cta::NDP_CTA_TYPE_DROPDOWNLIST;
                $options['options'] = $this->create($ctaReference->getChildCtas());
                if ($ctaReference->getReferenceLabel() !== null) {
                    $options['title'] = $ctaReference->getReferenceLabel();
                }
                break;
            case PsaCtaReferenceCommonInterface::PSA_REFERENCE_STATUS_DISABLED:
                $options['type'] = Cta::NDP_CTA_TYPE_HIDDEN;
                break;
            default:
                $this->getStyleFromReference($ctaReference->getStyle(), $options);
                $this->getSpecificType($ctaReference->getCta(), $options);
                break;
        }
    }

    protected function getSpecificType(PsaCta $cta, &$options = array())
    {
        switch ($cta->getType()) {
            case PsaCta::TYPE_CLICK_TO_CALL:
                //
            case PsaCta::TYPE_CLICK_TO_CHAT:
                //
            case PsaCta::TYPE_CLICK_WEB_CALLBACK:
                //
            case PsaCta::TYPE_JS:
            $options['type'] = $cta->getType();
            break;
        }
    }

    /**
     * @param string $style
     * @param array  $options
     *
     * @return array
     */
    protected function getStyleFromReference($style, &$options = array())
    {
        switch ($style) {
            case PsaCta::STYLE_NIVEAU1:
                $options['type']  = Cta::NDP_CTA_TYPE_BUTTON;
                $options['color'] = Cta::NDP_CTA_VERSION_DARK_BLUE;
                break;
            case PsaCta::STYLE_NIVEAU2:
                $options['type']  = Cta::NDP_CTA_TYPE_BUTTON;
                $options['color'] = Cta::NDP_CTA_VERSION_LIGHT_BLUE;
                break;
            case PsaCta::STYLE_NIVEAU3:
                $options['type']  = Cta::NDP_CTA_TYPE_BUTTON;
                $options['color'] = Cta::NDP_CTA_VERSION_GREY;
                break;
            case PsaCta::STYLE_NIVEAU4:
                $options['type']  = Cta::NDP_CTA_TYPE_SIMPLELINK;
                break;
        }
    }

    /**
     * @param PsaMedia $media
     * @param array    $options
     */
    protected function getMedia(PsaMedia $media, &$options = array())
    {
        $options['image'] = $this->mediaServer . $media->getMediaPath();
        $options['alt'] = $media->getMediaAlt();
    }

    /**
     * @param PsaCtaReferenceCommonInterface $ctaReference
     *
     * @return string
     */
    protected function getTarget(PsaCtaReferenceCommonInterface $ctaReference)
    {
        $target = $ctaReference->getTarget();
        $cta = $ctaReference->getCta();

        if (empty($target)) {
            $target = $cta->getTarget();
        }

        return $target;
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined($this->definedOptions);
        $resolver->setDefaults(array(
            'href' => null,
            'type' => Cta::NDP_CTA_TYPE_BUTTON,
            'color' => Cta::NDP_CTA_VERSION_DARK_BLUE,
            'inline' => true,
            self::OPTION_PREFIX_TITLE => '',
            'media' => false,
            'icon' => false,
            'target' => '_self',
            'lcdv16' => null,
           ));

        $resolver->setRequired(array('title', 'url', 'type', 'inline', 'target'));
    }
}
