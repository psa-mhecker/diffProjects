<?php
/**
 * Enter description here...
 *
 */

class Subscription
{

    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    public $iServiceId;

    public $iSubscriberId;

    public $dSubscriptionStartDate;

    public $dSubscriptionEndDate;

    public $dSubscriptionRecoveryDate;

    public $bSubscriptionEnable;

    public $bSubscriptionPayment;

    public $dSubscriptionDate;

    public $bSubscriptionOption1;

    public $bSubscriptionOption2;

    public $dSubscriptionInterStartDate;

    public $dSubscriptionInterEndDate;

    /**
     * Enter description here...
     *
     * @return unknown
     */
    
    public function getCountry ()
    {
        return $this->sCountry;
    }

    /**
     * @param int $iSiteId
     */
    public function setSiteId ($iSiteId)
    {
        $this->iSiteId = $iSiteId;
    }

    /**
     * Enter description here...
     *
     */
    public function Subscription ($service_id = "", $subscriber_id = "")
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        
        $aBind = array();
        
        $aBind[":SERVICE_ID"] = $service_id;
        $aBind[":SUBSCRIBER_ID"] = $subscriber_id;
        
        $strSql = "SELECT sub.* ,
						DATE_FORMAT(subscription_start_date,'%d/%m/%Y') SUBSCRIPTION_START_DATE,
						DATE_FORMAT(subscription_end_date,'%d/%m/%Y') SUBSCRIPTION_END_DATE,
						DATE_FORMAT(subscription_date,'%d/%m/%Y') SUBSCRIPTION_DATE,
						DATE_FORMAT(subscription_recovery_date,'%d/%m/%Y') SUBSCRIPTION_RECOVERY_DATE,
						DATE_FORMAT(subscription_date_start_inter,'%d/%m/%Y') SUBSCRIPTION_DATE_START_INTER,
						DATE_FORMAT(subscription_date_end_inter,'%d/%m/%Y') SUBSCRIPTION_DATE_END_INTER
	            FROM 	#pref#_subscription sub
	            WHERE 1=1 ";
        if ($service_id) {
            $strSql .= "AND sub.service_id = :SERVICE_ID ";
        }
        if ($subscriber_id) {
            $strSql .= "AND		sub.subscriber_id = :SUBSCRIBER_ID";
        }
        $aSubscription = $oConnection->queryRow($strSql, $aBind);
        
        $this->load($aSubscription);
    }

    /**
     * Enter description here...
     *
     */
    public function load ($aSubscription)
    {
        
        $this->iServiceId = $aSubscription["SERVICE_ID"];
        $this->iSubscriberId = $aSubscription["SUBSCRIBER_ID"];
        $this->dSubscriptionDate = $aSubscription["SUBSCRIPTION_DATE"];
        $this->dSubscriptionStartDate = $aSubscription["SUBSCRIPTION_START_DATE"];
        $this->dSubscriptionEndDate = $aSubscription["SUBSCRIPTION_END_DATE"];
        $this->dSubscriptionInterStartDate = $aSubscription["SUBSCRIPTION_DATE_START_INTER"];
        $this->dSubscriptionInterEndDate = $aSubscription["SUBSCRIPTION_DATE_END_INTER"];
        $this->bSubscriptionEnable = $aSubscription["SUBSCRIPTION_ENABLED"];
        $this->bSubscriptionOption1 = $aSubscription["SUBSCRIPTION_OPTION1"];
        $this->bSubscriptionOption2 = $aSubscription["SUBSCRIPTION_OPTION2"];
        $this->bSubscriptionPayment = $aSubscription["SUBSCRIPTION_PAYMENT"];
        $this->dSubscriptionRecoveryDate = $aSubscription["SUBSCRIPTION_RECOVERY_DATE"];
    
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $ubscriber_id
     * @return unknown
     */
    public function subscription_all_by_subscriber ($subscriber_id)
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        
        $aBind = array();
        $aBind[":SUBSCRIBER_ID"] = $subscriber_id;
        
        $strSql = "
					SELECT 	ser.SERVICE_ID, 
							SERVICE_LABEL
					FROM 	#pref#_service ser, #pref#_subscription subs
					WHERE	subs.subscriber_id = :SUBSCRIBER_ID 
					AND		ser.service_id = subs.service_id";
        
        if ($parent) {
            $strSql .= "AND service_parent_id = :PARENT_ID";
        }
        
        $result = $oConnection->queryTab($strSql, $aBind);
        
        return $result;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $service_id
     * @return unknown
     */
    public function subscription_all_by_service ($service_id)
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        
        $aBind = array();
        $aBind[":PARENT_ID"] = $parent;
        
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
        
        $result = $oConnection->queryTab($strSql, $aBind);
        
        return $result;
    }

    public function save ($subscriber_id, $service_id)
    {

    }
}

?>