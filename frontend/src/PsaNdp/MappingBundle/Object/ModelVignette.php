<?php
namespace PsaNdp\MappingBundle\Object;

use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\Popin\PopinFinancement;
use PsaNdp\MappingBundle\Manager\PriceManager;
use PsaNdp\MappingBundle\Translation\TranslatorAwareTrait;
use PsaNdp\MappingBundle\Utils\ModelSilouhetteSiteUtils;


/**
 * Object for displaying a vehicule vignette
 *
 * Input can be generated should be generated using ModelSilouhetteSiteUtils
 * This object should be used for slices (SFD_FO_Transverses_final_20150414_v21.docx - section "LA VIGNETTE VEHICULE"):
 * PC77, PC82, PC95, PF8, PF23, PF25, PF27, PF31, PF33, PF37, PF40, PF42 PF46, PF53, PF57, PF58
 *
 * Class ModelSilouhetteVignette
 * @package PsaNdp\MappingBundle\Object
 */
class ModelVignette extends ModelSilouhetteVignette
{
    protected $overrideMapping = array(
        'subTitle'=>'subtitle',
    );

    /**
     * @var array
     */
    protected $list;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var array
     */
    protected $link;

    /**
     * @return string
     */
    public function getTitle()
    {

        return $this->version->Model->label;
    }

    public function getSubtitle()
    {
        return  $this->trans('NDP_FINISHING');
    }

    /**
     * @return array
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param array $list
     *
     * @return ModelVignette
     */
    public function setList(array $list)
    {
        $this->list = $list;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return ModelVignette
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return array
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param array $link
     *
     * @return ModelVignette
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }



}
