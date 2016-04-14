<?php

pelican_import('Assetic_Filter_CompressorFilter');
use Assetic\Asset\AssetInterface;

/**
 * Pelican CSS compressor filter.
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Pelican_Assetic_Filter_CssCompressorFilter extends Pelican_Assetic_Filter_CompressorFilter
{
    public function filterDump(AssetInterface $asset)
    {
        $asset->setContent($this->compress($asset->getContent(), 'css'));
    }
}
