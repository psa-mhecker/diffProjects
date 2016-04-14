<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Data source for Pc72Colonnes block
 */
class Pc72ColonnesDataSource extends AbstractDataSource
{
    /**
     */
    public function __construct()
    {
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param  ReadBlockInterface $block
     * @param  Request            $request  Current url request displaying th block
     * @param  bool               $isMobile Indicate if is a mobile display
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        $data['block'] = $block;
        $data['multis'] = $block->getMultis();

        return $data;
    }
}
