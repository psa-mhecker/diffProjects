<?php
class Frontend_Citroen_MasterPageVehiculesN1 extends Pelican_Cache
{

    var $duration = DAY;

    /*
     *
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':PAGE_PARENT_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $sVersion = ($this->params[2]) ? $this->params[2] : "CURRENT";
        $aBind[':PAGE_STATUS'] = 1;
        $sSQL = "
            select *
            from #pref#_page p
            inner join #pref#_page_version pv
                on (p.PAGE_ID = pv.PAGE_ID
                    and p.LANGUE_ID = pv.LANGUE_ID
                    and p.PAGE_" . $sVersion . "_VERSION = pv.PAGE_VERSION)
            left join #pref#_media m
                on (pv.MEDIA_ID2 = m.MEDIA_ID)
            where p.PAGE_PARENT_ID = :PAGE_PARENT_ID
            and pv.LANGUE_ID = :LANGUE_ID
            and p.PAGE_STATUS = :PAGE_STATUS
            and pv.STATE_ID = 4
            order by p.PAGE_ORDER asc
        ";
        $aResult = $oConnection->queryTab($sSQL, $aBind);
        $this->value = $aResult;
    }

}