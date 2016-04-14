<?php
namespace PsaNdp\MappingBundle\Object\Block;

use PSA\MigrationBundle\Entity\Cta\PsaCtaReferenceInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Cta;

/**
 * Class Pc84CatalogueApplications
 * @package PsaNdp\MappingBundle\Object\Block
 */
class Pc84CatalogueApplications extends Content
{
    const NDP_COMPATIBLE_VEHICLES = 'NDP_COMPATIBLE_VEHICLES';
    const NDP_DOWNLOAD_APPLICATION = 'NDP_DOWNLOAD_APPLICATION';
    const NDP_COMPATIBLE_EXCLUSIVELY_WITH_FOLLOWING_VEHICLES = 'NDP_COMPATIBLE_EXCLUSIVELY_WITH_FOLLOWING_VEHICLES';
    const NDP_IS_MY_VEHICLE_COMPATIBLE_WITH_THIS_SERVICE = 'NDP_IS_MY_VEHICLE_COMPATIBLE_WITH_THIS_SERVICE';

    protected $mapping = array(
        'datalayer' => 'dataLayer',
        'subTitle' => 'subtitle',
    );

    /**
     * @var string
     */
    protected $mediaServer;

    /**
     * @var array
     */
    protected $items;

    /**
     * @var array
     */
    protected $mapApplicationVisuals;

    /**
     * @param string $mediaServer
     * @return Pc84CatalogueApplications
     */
    public function setMediaServer($mediaServer)
    {
        $this->mediaServer = $mediaServer;

        return $this;
    }

    /**
     * @return string
     */
    public function getMediaServer()
    {
        return $this->mediaServer;
    }

    /**
     * @param array $items
     * @return Pc84CatalogueApplications
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $mapApplicationVisuals
     * @return Pc84CatalogueApplications
     */
    public function setMapApplicationVisuals($mapApplicationVisuals)
    {
        $this->mapApplicationVisuals = $mapApplicationVisuals;

        return $this;
    }

    /**
     * @return array
     */
    public function getMapApplicationVisuals()
    {
        return $this->mapApplicationVisuals;
    }

    /**
     * @return Pc84CatalogueApplications
     */
    public function populate()
    {
        $oCtaPopin = $this->getCtaFactory()->createFromArray([
            'url'   => '#',
            'style' => Cta::ISOBAR_CTA_STYLE_SIMPLELINK,
            'title'   => $this->translate[static::NDP_COMPATIBLE_VEHICLES],
            'class' => 'call-popin'
        ]);

        $ctaReferences = $this->getBlock()->getCtaReferences();
        if (isset($ctaReferences) && count($ctaReferences->toArray()) > 0) {
            /** @var PsaCtaReferenceInterface $ctaReference */
            $ctaReference = reset($ctaReferences->toArray());

            $oCtaCompatibilite = $this->getCtaFactory()->create($ctaReference);
        }

        $mapApplicationVisuals = $this->getMapApplicationVisuals();

        for($idx = 0; $idx < count($this->getItems()); $idx++) {
            // Libelles de traduction à changer avant le reste autant que possible
            if(isset($this->items[$idx]['cta']) && isset($this->translate[static::NDP_DOWNLOAD_APPLICATION])) {
                $this->items[$idx]['cta'][0]['title'] = $this->translate[static::NDP_DOWNLOAD_APPLICATION];
            }

            if (isset($this->items[$idx]['popin'])) {
                if (isset($this->translate[static::NDP_COMPATIBLE_EXCLUSIVELY_WITH_FOLLOWING_VEHICLES])) {
                    $this->items[$idx]['popin']['text'] = $this->translate[static::NDP_COMPATIBLE_EXCLUSIVELY_WITH_FOLLOWING_VEHICLES];
                }

                if (isset($this->translate[static::NDP_IS_MY_VEHICLE_COMPATIBLE_WITH_THIS_SERVICE])) {
                    $this->items[$idx]['popin']['question'] = $this->translate[static::NDP_IS_MY_VEHICLE_COMPATIBLE_WITH_THIS_SERVICE];
                }

                if (isset($this->translate[static::NDP_CLOSE])) {
                    $this->items[$idx]['popin']['close'] = $this->translate[static::NDP_CLOSE];
                }

                $this->items[$idx]['link'] = [$oCtaPopin];

                if (isset($oCtaCompatibilite)) {
                    $this->items[$idx]['popin']['cta'] = [$oCtaCompatibilite];
                }
            }

            if(isset($this->items[$idx]['cta'])) {
                $this->items[$idx]['ctaList'] = $this->items[$idx]['cta'];
            }

            if (isset($mapApplicationVisuals[$this->items[$idx]['visual']])) {
                $this->items[$idx]['visual'] = $this->getMediaFactory()->createFromArray([
                    'source' => $this->getMediaServer() . $mapApplicationVisuals[$this->items[$idx]['visual']]->getMediaPath(),
                ]);
            }
        }

        return $this;
    }
}
