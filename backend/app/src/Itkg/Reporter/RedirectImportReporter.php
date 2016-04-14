<?php

namespace Itkg\Reporter;

use XtoY\Reporter\ReporterInterface;

/**
 * Class RedirectImportReporter
 * @package Itkg\Reporter
 * @codeCoverageIgnore
 */
class RedirectImportReporter implements ReporterInterface
{
    /**
     * @var array
     */
    protected $existing = [];

    /**
     * @var array
     */
    protected $ignored = [];
    /**
     * @var array
     */
    protected $invalid;

    /**
     * @param $fetched
     *
     * @return $this
     */
    public function setFetchedLines($fetched)
    {
        return $this;
    }

    /**
     * @param $total
     *
     * @return $this
     */
    public function setTotalLines($total)
    {
        return $this;
    }

    /**
     * @param $mapped
     *
     * @return $this
     */
    public function setMappedLines($mapped)
    {
        return $this;
    }

    /**
     * @param $written
     *
     * @return $this
     */
    public function setWrittenLines($written)
    {
        return $this;
    }

    /**
     * @param $url
     *
     * @return $this
     */
    public function addExistingRedirection($url)
    {
        $this->existing[] = $url;

        return $this;
    }


    /**
     * @param $url
     *
     * @return $this
     */
    public function addIgnoredRedirection($url)
    {
        $this->ignored[] = $url;

        return $this;
    }

    /**
     * @param $url
     *
     * @return $this
     */
    public function addInvalidRedirection($url)
    {
        $this->invalid[] = $url;

        return $this;
    }

    /**
     * @return array
     */
    public function getExisting()
    {
        return $this->existing;
    }

    /**
     * @return array
     */
    public function getIgnored()
    {
        return $this->ignored;
    }

    /**
     * @return array
     */
    public function getInvalid()
    {
        return $this->invalid;
    }
}
