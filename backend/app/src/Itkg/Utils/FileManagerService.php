<?php


namespace Itkg\Utils;

use Guzzle\Http\Client;


/**
 * Helper for manage file
 * - Download file from web using proxy
 * - Check a local downloaded file is an valid XML file
 *
 * Class ShowroomXMLChecker
 * @package Itkg\Migration
 */
class FileManagerService
{
    /** @var Client */
    private $client;
    /** @var array */
    private $options = [];

    public function __construct()
    {
        $this->client = new Client();

        if (isset(\Pelican::$config['PROXY']) && is_array(\Pelican::$config['PROXY'])) {
            $proxy = 'http://' . \Pelican::$config['PROXY']['LOGIN'] . ':' . \Pelican::$config['PROXY']['PWD'] .
                     '@' . \Pelican::$config['PROXY']['HOST'] . ':' . \Pelican::$config['PROXY']['PORT'];

            $this->options = [
                'proxy' => $proxy
            ];
        }
    }


    /**
     * Check that the file in $filePath is a valid XML file
     *
     * @param string $filePath
     *
     * @return bool
     */
    public function isValidXmlFile($filePath)
    {
        $prev = libxml_use_internal_errors(true);
        $isValid = true;

        try {
            new \SimpleXMLElement($filePath, 0, true);
        } catch(\Exception $e) {
            $isValid = false;
        }
        if(count(libxml_get_errors()) > 0) {
            // There has been XML errors
            $isValid = false;
        }
        // Tidy up.
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        return $isValid;
    }


    /**
     * @param $sourceUrl
     * @param $fileNameDestination
     *
     * @return null|string
     *
     */
    public function downloadFileInTempDir($sourceUrl, $fileNameDestination)
    {
        $tempDir = sys_get_temp_dir();
        $tempFilePath = null;

        try {
            $tempFilePath = $this->download(
                $sourceUrl,
                $tempDir,
                $fileNameDestination
            );
        } catch (\Exception $e) {
            $tempFilePath = null;
        }

        return $tempFilePath;
    }

    /**
     * Dwd file file to '$this->directory' folder
     *
     * If $temp boolean is
     *
     * Return filePath of file downloaded from $xmlUrl
     * If no XML is downloaded return null
     *
     * @param string $sourceUrl
     * @param string $destinationDirectory
     * @param string $filename
     * @param bool $temp if true filename destination will be generated using tempnam()
     *
     * @return null|string
     */
    public function download($sourceUrl, $destinationDirectory, $filename, $temp = true)
    {
        // Create a unique filename using timestamp and random number (note : sometime dwd is too fast for timestamp to be enough)
        if ($temp) {
            $filePath = tempnam($destinationDirectory, $filename . '_');
        } else {
            $filePath = $destinationDirectory . DIRECTORY_SEPARATOR . $filename;
        }
        $this->client
            ->get($sourceUrl, null, $this->options)
            ->setResponseBody($filePath)
            ->send();

        return $filePath;
    }

}
