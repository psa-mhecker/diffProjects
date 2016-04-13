<?php
pelican_import('User');

class Service extends Pelican_User
{

    public $iServiceId;

    public $sServiceLabel;

    public $iServiceParentId;

    public $iSiteId;

    public $bServiceEnable;

    public $dServiceDateStart;

    public $dServiceDateEnd;

    public $bServicePaid;

    public $iServiceOrder;

    public $iServiceLevel;

    public $iServiceDuration;

    public $sServiceEmail;

    public $sServiceDescription;

    public $iMediaId;

    public $bServiceShared;

    public $iServiceTypeId;

    public $sServicePath;

    public $bServiceSubscription;

    public $bServiceDefault;

    public $iserviceTypeLevel;

    /**
     *
     * @return int
     */
    public function getServiceId ()
    {
        return $this->iServiceId;
    }

    /**
     *
     * @param int $service_id
     * @return void
     */
    public function setServiceId ($service_id)
    {
        $this->iServiceId = $service_id;
    }

    /**
     *
     * @return string
     */
    public function getServiceLabel ()
    {
        return $this->sServiceLabel;
    }

    /**
     *
     * @param sting $service_label
     */
    public function setServiceLabel ($service_label)
    {
        $this->sServiceLabel = $service_label;
    }

    /**
     * Récupère le parent
     *
     * @return int
     */
    public function getServiceParentId ()
    {
        return $this->iServiceParentId;
    }

    /**
     * Modifie le parent
     *
     * @param int $parent_id
     */
    public function setServiceParentId ($parent_id)
    {
        $this->iServiceParentId = $parent_id;
    }

    /**
     *
     * @return int
     */
    public function getSiteId ()
    {
        return $this->iSiteId;
    }

    /**
     * @param int $iSiteId
     */
    public function setSiteId ($iSiteId)
    {
        $this->iSiteId = $iSiteId;
    }

    /**
     * Récupère le booléen pour savoir si le service est actif
     *
     * @return boolean
     */
    public function getServiceEnable ()
    {
        return $this->bServiceEnable;
    }

    /**
     * Modifie l'activation du service
     *
     * @param int $enable
     */
    public function setServiceEnable ($enable)
    {
        $this->bServiceEnable = $enable;
    }

    /**
     *
     * @return date
     */
    public function getServiceStartDate ()
    {
        return $this->dServiceDateStart;
    }

    /**
     *
     * @param date $date_start
     */
    public function setServiceStartDate ($date_start)
    {
        $this->dServiceDateStart = $date_start;
    }

    /**
     *
     * @return date
     */
    public function getServiceEndDate ()
    {
        return $this->dServiceEndStart;
    }

    /**
     *
     * @param date $date_start
     */
    public function setServiceEndDate ($date_end)
    {
        $this->dServiceDateEnd = $date_end;
    }

    /**
     *
     * @return boolean
     */
    public function getServicePaid ()
    {
        return $this->bServicePaid;
    }

    /**
     *
     *
     * @param int $service_paid
     */
    public function setServicePaid ($service_paid)
    {
        $this->bServicePaid = $service_paid;
    }

    /**
     * 
     *
     * @return int
     */
    public function getServiceOrder ()
    {
        return $this->iServiceOrder;
    }

    /**
     * 
     *
     * @return int
     */
    public function getServiceLevel ()
    {
        return $this->iServiceLevel;
    }

    /**
     * 
     *
     * @return int
     */
    public function getServiceDuration ()
    {
        return $this->iServiceDuration;
    }

    /**
     * 
     *
     * @return string
     */
    public function getServiceEmail ()
    {
        return $this->sServiceEmail;
    }

    /**
     * 
     *
     * @param string $email
     */
    public function setServiceEmail ($email)
    {
        $this->sServiceEmail = $email;
    }

    /**
     * 
     *
     * @return string
     */
    public function getServiceDescription ()
    {
        return $this->sServiceDescription;
    }

    /**
     *
     *
     * @return int
     */
    public function getServiceMediaId ()
    {
        return $this->iMediaId;
    }

    public function getServiceShared ()
    {
        return $this->bServiceShared;
    }

    public function getServiceTypeId ()
    {
        return $this->iServiceTypeId;
    }

    public function getServicePath ()
    {
        return $this->sServicePath;
    }

    public function getServiceSubscription ()
    {
        return $this->bServiceSubscription;
    }

    public function getServiceDefault ()
    {
        return $this->bServiceDefault;
    }

    public function getServiceTypeLevel ()
    {
        return $this->iserviceTypeLevel;
    }

    public function Service ($service_id = "")
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        $aBind = array();
        $aBind[":SERVICE_ID"] = $service_id;
        
        $strSql = "SELECT s.* ,
						st.SERVICE_TYPE_LEVEL,
						DATE_FORMAT(service_start_date,'%d/%m/%Y') SERVICE_START_DATE,
						DATE_FORMAT(service_end_date,'%d/%m/%Y') SERVICE_END_DATE
	            FROM 	#pref#_service s, 
	             		#pref#_service_type st
	            WHERE 	st.service_type_id = s.service_type_id ";
        if ($service_id) {
            $strSql .= "AND 	s.service_id = :SERVICE_ID ";
        }
        $aService = $oConnection->queryRow($strSql, $aBind);
        
        $this->load($aService);
    }

    public function load ($aService)
    {
        
        $this->iServiceId = $aService["SERVICE_ID"];
        $this->sServiceLabel = $aService["SERVICE_LABEL"];
        $this->iServiceParentId = $aService["SERVICE_PARENT_ID"];
        $this->iSiteId = $aService['SITE_ID'];
        $this->bServiceEnable = $aService["SERVICE_ENABLE"];
        $this->dServiceDateStart = $aService["SERVICE_START_DATE"];
        $this->dServiceDateEnd = $aService["SERVICE_END_DATE"];
        $this->bServicePaid = $aService["SERVICE_PAID"];
        $this->iServiceOrder = $aService["SERVICE_ORDER"];
        $this->iServiceLevel = $aService["SERVICE_LEVEL"];
        $this->iServiceDuration = $aService["SERVICE_DURATION"];
        $this->sServiceEmail = $aService["SERVICE_EMAIL"];
        $this->sServiceDescription = $aService["SERVICE_DESCRIPTION"];
        $this->iMediaId = $aService["MEDIA_ID"];
        $this->bServiceShared = $aService["SERVICE_SHARED"];
        $this->iServiceTypeId = $aService["SERVICE_TYPE_ID"];
        $this->sServicePath = $aService["SERVICE_PATH"];
        $this->bServiceSubscription = $aService["SERVICE_SUBSCRIPTION"];
        $this->bServiceDefault = $aService["SERVICE_DEFAULT"];
        $this->iserviceTypeLevel = $aService["SERVICE_TYPE_LEVEL"];
    
    }

    public function service_all ($site_id, $parent = "", $type = "all")
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        
        $aBind = array();
        $aBind[":PARENT_ID"] = $parent;
        $aBind[":SITE_ID"] = $site_id;
        //$aBind[":TYPE_ID"]		= $type;
        

        $strSql = "
					SELECT 	SERVICE_ID, 
							SERVICE_LABEL, 
							SERVICE_PARENT_ID,
							SERVICE_ENABLE,
							SERVICE_PAID, 
							SERVICE_SHARED, 
							SERVICE_TYPE_ID,
							SERVICE_SUBSCRIPTION,
							SERVICE_DEFAULT,
							DATE_FORMAT(service_start_date,'%d/%m/%Y') AS START_DATE 
					FROM #pref#_service
					WHERE	SITE_ID = :SITE_ID ";
        
        if ($parent) {
            $strSql .= "AND service_parent_id = :PARENT_ID";
        }
        
        switch ($type) {
            case "default":
                $strSql .= " AND service_default = 1
							 AND service_subscription is null";
                break;
            case "subscription":
                $strSql .= " AND service_subscription = 1";
                break;
            case "level1":
                $strSql .= " AND service_parent_id is null";
                break;
             case "subscription_lev1":
                $strSql .= " AND service_subscription = 1
                			 AND service_parent_id is null ";
                break;
        }
        
        $result = $oConnection->queryTab($strSql, $aBind);
        
        return $result;
    }

    public function service_payment ()
    {

    }

    public function service_is_enable ()
    {

    }

    public function service_hierarchy ($site_id)
    {
        
        $oConnection = Pelican_Db::getInstance();
        $aBind[":SITE_ID"] = $site_id;
        $strSql = "
					SELECT ser.SERVICE_ID, if(ser.service_parent_id !='',CONCAT('--',ser.service_label),ser.service_label) SERVICE_LABEL 
					FROM #pref#_service ser
					LEFT OUTER JOIN #pref#_service ser2 ON  ser.service_id = ser2.service_parent_id
					WHERE ser.site_id = :SITE_ID
					GROUP BY if(ser.service_parent_id !='',ser.service_parent_id,ser.service_id), ser.service_label, ser.service_parent_id, ser.service_order ";
        $result = $oConnection->queryTab($strSql, $aBind);
        
        return $result;
    }

    public function service_subscriber ($service_id, $order = "SUBSCRIBER_ID desc")
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        $aBind = array();
        $aBind[":SERVICE_ID"] = $service_id;
        
        $strSql = "	SELECT 	sb.SUBSCRIBER_ID, 
							SUBSCRIBER_LASTNAME,
							SUBSCRIBER_FIRSTNAME, 
							SUBSCRIBER_NICKNAME, 
							CONCAT('<a href=\"mailto:',SUBSCRIBER_EMAIL,'\">',SUBSCRIBER_EMAIL,'</a>') SUBSCRIBER_EMAIL, 
							DATE_FORMAT(sb.subscriber_date,'%d/%m/%Y') DATE_INSCR
					FROM #pref#_subscriber sb
					INNER JOIN #pref#_subscription st ON (st.SUBSCRIBER_ID = sb.SUBSCRIBER_ID and st.SERVICE_ID = :SERVICE_ID)
					ORDER BY $order";
        
        $result = $oConnection->queryTab($strSql, $aBind);
        
        return $result;
    
    }

}

?>