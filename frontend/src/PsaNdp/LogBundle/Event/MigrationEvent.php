<?php

namespace PsaNdp\LogBundle\Event;

use Itkg\Migration\Reporting\DataMigrationReporting;
use OpenOrchestra\ModelInterface\Model\ReadSiteInterface;
use PSA\MigrationBundle\Entity\User\PsaUser;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class MigrationEvent
 */
class MigrationEvent extends Event
{
    /**
     * @var ReadSiteInterface
     */
    protected $site;

    /**
     * @var PsaUser
     */
    protected $user;

    /**
     * @var array
     */
    protected $urls = array();

    /**
     * @var string
     */
    protected $urlType;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var float
     */
    protected $time;

    /**
     * @var DataMigrationReporting
     */
    protected $reporting;

    /**
     * @param ReadSiteInterface           $site
     * @param PsaUser                     $user
     * @param array                       $urls
     * @param string                      $urlType
     * @param float                       $time
     * @param string|null                 $message
     * @param DataMigrationReporting|null $reporting
     */
    public function __construct(ReadSiteInterface $site, PsaUser $user, $urls, $urlType, $time, $message = null, DataMigrationReporting $reporting = null)
    {
        $this->site = $site;
        $this->user = $user;
        $this->urls = $urls;
        $this->urlType = $urlType;
        $this->reporting = $reporting;
        $this->message = $message;
        $this->time = $time;
    }

    /**
     * @var string
     */
    protected $url;

    /**
     * @return ReadSiteInterface
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @return string
     */
    public function getUrlType()
    {
        return $this->urlType;
    }

    /**
     * @return array
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * @return PsaUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $return = '?';
        if (!empty( $this->reporting))  {
            $return =  $this->reporting->getUrl();
        }

        return $return;
    }

    /**
     * @return string
     */
    public function getException()
    {
        $return = '?';
        if (!empty( $this->reporting))  {
            $return =  $this->reporting->getException();
        }

        return $return;
    }

    /**
     * @return float
     */
    public function getTime()
    {
        return $this->time;
    }
}
