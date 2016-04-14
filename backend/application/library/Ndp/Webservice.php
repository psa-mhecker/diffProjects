<?php

/**
 * Gestion des webservices
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 * @since 13/05/2015
 */
class Ndp_Webservice
{

    protected $siteId;
    protected $name;
    protected $url;
    protected $status;

    const TABLENAME = 'liste_webservices';
    const TABLENAME_STATUS = 'site_webservice';
    const IS_ON = 1;
    const IS_OFF = 0;
    const CONFIGURATOR = 'WS_MOTEUR_CONFIG_PROD';
    const WEBSTORE = 'WS_WEBSTORE';
    const SFG = 'WS_SFG';

    /**
     *
     * @return int
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     *
     * @return string
     */
    public function getUrl()
    {

        return $this->url;
    }

    /**
     *
     * @param int $siteId
     *
     * @return \Ndp_Webservice
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     *
     * @param string $name
     *
     * @return \Ndp_Webservice
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     *
     * @param string $url
     *
     * @return \Ndp_Webservice
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * return boolean
     */
    public function isActive()
    {

        return !empty($this->status);
    }

    /**
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *
     * @param bool $status
     *
     * @return Ndp_Webservice
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
    /*
     * @return array
     */

    public function getValues()
    {
        $connection = Pelican_Db::getInstance();
        if (!$this->getSiteId()) {
            $this->setSiteId($_SESSION[APP]['SITE_ID']);
        }

        $params = [":SITE_ID" => $this->getSiteId()];
        $query = "SELECT sws.*, lws.* "
            ." from #pref#_".self::TABLENAME." lws"
            ." LEFT JOIN #pref#_".self::TABLENAME_STATUS." sws"
            ." ON (lws.ws_id = sws.ws_id AND site_Id = :SITE_ID )";


        if ($this->getName()) {
            $params[':WS_NAME'] = $this->getName();
            $query .= " WHERE ws_name = ':WS_NAME'";
        }

        $retour = $connection->queryTab($query, $params);

        if ($this->getName() && !empty($retour)) {
            $webservice = array_shift($retour);
            $this->setStatus($webservice['status']);
        }

        return $retour;
    }

    /**
     *
     * @return array
     */
    public function getWSList()
    {
        $connection = Pelican_Db::getInstance();
        $query = "select * from #pref#_".self::TABLENAME;

        return $connection->queryTab($query);
    }

    /**
     *
     * @return array
     */
    public function getSitesWsConf()
    {
        $connection = Pelican_Db::getInstance();
        $query = "select * from #pref#_".self::TABLENAME_STATUS;

        return $connection->queryTab($query);
    }
}
