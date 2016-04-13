<?php

/**
 * Fichier de Pelican_Cache : liste des tags TAG associé à un site
 *
 * @package Pelican_Cache
 * @subpackage Cybertag
 * @author Raphael Carles <rcarles@businessdecision.com>
 * @since 14/06/2005
 */
class Frontend_Cybertag extends Pelican_Cache
{

    
    var $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue()
    {
        
        $oConnection = Pelican_Db::getInstance();
        
        $complementCybertag = '';
        $aBind = array();
        $aBind[":SITE_ID"] = $this->params[0];
        
        if (count($this->params) > 1) {
            $url = $this->params[1];
            $home_id = $this->params[2];
            $pid = $this->params[3];
            $cid = $this->params[4];
            $tpl = $this->params[5];
            $complement = $this->params[6];
            
            $data[] = $url;
            $data[] = "pid=" . $pid;
            $data[] = "tpl=" . $tpl;
            $data[] = "cid=" . $cid;
            $data[] = "pid=" . $pid;
            $data[] = "pid=" . $home_id;
            
            $complement = " AND TAG_ID IN ('" . implode("','", $data) . "')";
        }
        
        /*$sSql = "select SITE_TAG from #pref#_site WHERE SITE_ID=:SITE_ID";
		$sClient = $oConnection->queryItem($sSql, $aBind);*/
        
        $sSql = "select * from #pref#_tag WHERE SITE_ID=:SITE_ID " . $complement;
        $aResult = $oConnection->queryTab($sSql, $aBind);
        
        if ($aResult) {
            foreach ($aResult as $data) {
                $return[$data["TAG_ID"]] = array(
                    $data["TAG_RUBRIQUE"] , 
                    $data["TAG_SECTION"] , 
                    $data["TAG_LABEL"]);
            }
        }
        
        if (! $return["pid=" . $home_id])
            $return["pid=" . $home_id] = array(
                "home" , 
                "homegene");
        
        $data = getSiteTag($return, $url, "", $complementCybertag);
        
        $this->value = $data;
    }
}

function getSiteTag($CYBER, &$url, $TAG_ABSENT = "", $complement = "")
{
    global $notag;
    
    $notag = $url;
    
    if (! $TAG_ABSENT)
        $TAG_ABSENT = "tag_absent";
    /**
     * * tpl
     */
    if (! empty($CYBER["tpl=" . $_GET["tpl"]]) && empty($CYBER[$url])) {
        $url = "tpl=" . $_GET["tpl"];
    }
    
    /**
     * * cid
     */
    if (! empty($CYBER["cid=" . $_GET["cid"]]) && empty($CYBER[$url])) {
        $url = "cid=" . $_GET["cid"];
    }
    
    /**
     * * pid
     */
    if (! empty($CYBER["pid=" . $_GET["pid"]]) && empty($CYBER[$url])) {
        $url = "pid=" . $_GET["pid"];
    }
    
    /**
     *
     * @todo à changer
     */
    if (! $url || $url == "index.php")
        $url = "pid=" . $_SESSION[APP]["HOME_PAGE_ID"];
    $tag = $CYBER[$url];
    
    if (! $tag) {
        $tag = array(
            $TAG_ABSENT , 
            $TAG_ABSENT);
    }
    if ($complement) {
        $tag[0] = $tag[0] . "_" . $complement;
    }
    return $tag;
}
?>