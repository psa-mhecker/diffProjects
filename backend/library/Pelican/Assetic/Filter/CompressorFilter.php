<?php
pelican_import('Index_Pack');

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;

/**
 * Pelican Base compressor filter.
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
abstract class Pelican_Assetic_Filter_CompressorFilter implements FilterInterface
{
    private $charset;
    private $lineBreak;

    public function __construct()
    {
    }

    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    public function setLineBreak($lineBreak)
    {
        $this->lineBreak = $lineBreak;
    }

    public function filterLoad(AssetInterface $asset)
    {
    }

    /**
     * Compresses a string.
     *
     * @param string $content The content to compress
     * @param string $type    The type of content, either "js" or "css"
     * @param array  $options An indexed array of additional options
     *
     * @return string The compressed content
     */
    protected function compress($content, $type)
    {
        $code = Pelican_Index_Pack::minify($content, $type);

        // input and output files
        $tempDir = realpath(sys_get_temp_dir());
        $hash = substr(sha1(time().rand(11111, 99999)), 0, 7);
        $output = $tempDir.DIRECTORY_SEPARATOR.$hash.'-min.'.$type;
        file_put_contents($output, $code);

        if (0 < $code) {
            if (file_exists($output)) {
                unlink($output);
            }

            throw new \RuntimeException($proc->getErrorOutput());
        } elseif (!file_exists($output)) {
            throw new \RuntimeException('Error creating output file.');
        }

        $retval = file_get_contents($output);
        unlink($output);

        return $retval;
    }
}
