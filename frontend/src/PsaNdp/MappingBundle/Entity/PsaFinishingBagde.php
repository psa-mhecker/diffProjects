<?php
namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PsaFinishingBagde
 *
 * @ORM\Table(name="psa_finishing_badge")})
 * @ORM\Entity
 */
class PsaFinishingBagde
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="LABEL", type="string", length=50, nullable=false)
     *
     */
    protected $label;

    /**
     * @var string
     * @ORM\Column(name="BADGE_URL", type="string", length=255, nullable=false)
     *
     */
    protected $badgeUrl;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return PsaFinishingBagde
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return PsaFinishingBagde
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getBadgeUrl()
    {
        return $this->badgeUrl;
    }

    /**
     * @param string $badgeUrl
     *
     * @return PsaFinishingBagde
     */
    public function setBadgeUrl($badgeUrl)
    {
        $this->badgeUrl = $badgeUrl;

        return $this;
    }
}
