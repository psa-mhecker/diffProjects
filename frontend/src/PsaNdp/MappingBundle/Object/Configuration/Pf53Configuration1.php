<?php

namespace PsaNdp\MappingBundle\Object\configuration;

use PsaNdp\MappingBundle\Object\BlockTrait\ConfigurationTrait;
use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Pf53Configuration1
 */
class Pf53Configuration1 extends Content
{
    protected $mapping = array(
        'text1' => 'title',
        'link' => 'ctaList',
    );

    use ConfigurationTrait;
}
