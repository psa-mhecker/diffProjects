<?php
namespace PsaNdp\ApiBundle\Tests\Transformers;

use Phake;
use PsaNdp\ApiBundle\Transformers\ColorTransformer;

class ColorTransformerTest extends \PHPUnit_Framework_TestCase
{
    protected $colorTransformer;

    public function setup()
    {
        $repo = Phake::mock('PsaNdp\MappingBundle\Repository\Vehicle\PsaModelSilhouetteAngleRepository');
        $this->colorTransformer = new ColorTransformer($repo);
    }

    public function testTransform()
    {
        /** @todo  */
    }
}
