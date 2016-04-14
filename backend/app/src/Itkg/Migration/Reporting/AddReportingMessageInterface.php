<?php

namespace Itkg\Migration\Reporting;


use PSA\MigrationBundle\Entity\Language\PsaLanguage;

interface AddReportingMessageInterface
{


    /**
     * @return array
     */
    public function getUrls();

    /**
     * @param PsaLanguage $language
     * @param $url
     *
     * @return DataMigrationReporting
     */
    public function addUrl(PsaLanguage $language, $url);

    /**
     * @return array
     */
    public function getXmls();

    /**
     * @param PsaLanguage $language
     * @param string $url
     *
     * @return DataMigrationReporting
     */
    public function addXml(PsaLanguage $language, $url);


    /**
     * @return array
     */
    public function getSrtUrls();

    /**
     * @param string $srtUrl
     *
     * @return DataMigrationReporting
     */
    public function addSrtUrl($srtUrl);

    /**
     * @param $message
     *
     * @return AddReportingMessageInterface
     */
    public function addInfoMessage($message);

    /**
     * @param $message
     *
     * @return AddReportingMessageInterface
     */
    public function addWarningMessage($message);

    /**
     * @param $message
     *
     * @return AddReportingMessageInterface
     */
    public function addErrorMessage($message);

}
