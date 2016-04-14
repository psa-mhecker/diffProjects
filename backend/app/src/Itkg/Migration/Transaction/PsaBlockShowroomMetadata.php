<?php

namespace Itkg\Migration\Transaction;

use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Class for adding to Original PsaPage entity other metadata to transport for the migration
 *
 * Class PsaPageShowroomMetadata
 * @package Itkg\Migration\XML\EntityParser
 */
class PsaBlockShowroomMetadata
{
    /** @var PsaPageZoneConfigurableInterface */
    private $block;
    /** @var string xml attribut 'id' from the node the slice block  */
    private $xmlId;

    /**
     * @return string
     */
    public function getXmlId()
    {
        return $this->xmlId;
    }

    /**
     * @param string $xmlId
     *
     * @return PsaPageShowroomMetadata
     */
    public function setXmlId($xmlId)
    {
        $this->xmlId = $xmlId;

        return $this;
    }

    /**
     * @return PsaPageZoneConfigurableInterface
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param PsaPageZoneConfigurableInterface $block
     *
     * @return PsaBlockShowroomMetadata
     */
    public function setBlock($block)
    {
        $this->block = $block;

        return $this;
    }

}
