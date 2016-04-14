<?php

namespace PsaNdp\MappingBundle\Helper;

use PsaNdp\MappingBundle\Transformers\DataTransformerInterface;

interface HelperInterface
{


    public function getName();

    public function registerTransformer(DataTransformerInterface $transformer);
}