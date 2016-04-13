<?php
/**
 * * Gestion de tags de fréquentation
 *
 * @package Pelican
 * @subpackage Index/Frontoffice
 * @since 16/02/2005
 * @author Raphaël Carles <rcarles@businessdecision.com>
 */

class Pelican_Index_Analytics
{

    // getTag
    function getBackTag (&$oForm, $id, $site, $readO, $section = "", $rubrique = "", $param = "pid")
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        
        $temp = explode("=", $id);
        $type = ($temp[0] == "pid" ? "PAGE" : "CONTENT");
        $oForm->showSeparator();
        $values = $oConnection->queryRow("SELECT * from #pref#_tag WHERE TAG_ID='" . $id . "' AND SITE_ID=" . $site);
        $oForm->createInput("TAG_SECTION", "Stat (zone)", 255, "", false, ($values["TAG_SECTION"] ? $values["TAG_SECTION"] : $section), $readO, 30, false, "", "text");
        $oForm->createInput("TAG_RUBRIQUE", "Stat (page)", 255, "", false, ($values["TAG_RUBRIQUE"] ? $values["TAG_RUBRIQUE"] : $rubrique), $readO, 100, false, "", "text");
        $oForm->createHidden("TAG_TYPE", $param);
    }

    function getContentTag ($complementCybertag = "", $lang_code = "")
    {
        
        
        if (! isset($_GET["cid"])) {
            $_GET["cid"] = "";
        }
        
        $tag = Pelican_Cache::fetch("Frontend/Cybertag", array($_SESSION[APP]['SITE_ID'] , $_SERVER["QUERY_STRING"] , $_SESSION[APP]["HOME_PAGE_ID"] , $_GET["pid"] , $_GET["cid"] , "" , $complementCybertag));
        
        $script = Pelican_Cache::fetch("Tag/Type", array($_SESSION[APP]['SITE_ID'] , Pelican::$config["SERVER_PROTOCOL"]));
        
        $trans["%%CLIENT%%"] = $script["CLIENT"];
        if ($lang_code) {
            $trans["%%SECTION%%"] = $lang_code;
        } else {
            $trans["%%SECTION%%"] = $tag[1];
        }
        $trans["%%RUBRIQUE%%"] = $tag[0];
        if (Pelican::$config["TYPE_ENVIRONNEMENT"] == "prod" || Pelican::$config["TYPE_ENVIRONNEMENT"] == "preprod") {
            echo (strtr($script["TAG"], $trans));
        } else {
            echo nl2br(str_replace("\\\\r\\\\n", "\r\n", htmlentities(strtr($script["TAG"], $trans))));
        }
    }

    function getCustomTag ()
    {

    }

    function getROITag ()
    {

    }

    function getActionTag ()
    {

    }

    function getRefererTag ()
    {

    }

    // updateCyberTag
    function update ($type = "", $id = "")
    {
        
        
        $oConnection = getConection();
        
        if ((Pelican_Db::$values["PAGE_ID"] && ! is_array(Pelican_Db::$values["PAGE_ID"])) || (Pelican_Db::$values["CONTENT_ID"] && ! is_array(Pelican_Db::$values["CONTENT_ID"]))) {
            
            /** cas de la création d'un contenu */
            if (! Pelican_Db::$values["TAG_SECTION"]) {
                $pid = ($type == "PAGE" ? Pelican_Db::$values["PAGE_PARENT_ID"] : Pelican_Db::$values["PAGE_ID"]);
                Pelican_Db::$values["TAG_SECTION"] = $oConnection->queryItem("select TAG_SECTION from #pref#_tag WHERE TAG_PID=:PAGE_ID AND (TAG_CID IS NULL OR TAG_CID = '')", array(":PAGE_ID" => $pid));
            }
            
            if (Pelican_Db::$values["TAG_SECTION"] && ! Pelican_Db::$values["TAG_RUBRIQUE"]) {
                $pid = ($type == "PAGE" ? Pelican_Db::$values["PAGE_PARENT_ID"] : Pelican_Db::$values["PAGE_ID"]);
                Pelican_Db::$values["PAGE_LIBPATH"] = $oConnection->queryItem("select PAGE_LIBPATH from #pref#_page WHERE PAGE_ID=:PAGE_ID", array(":PAGE_ID" => $pid));
                Pelican_Db::$values["TAG_RUBRIQUE"] = cleanCybertag(Pelican_Db::$values["PAGE_LIBPATH"]);
                Pelican_Db::$values["TAG_RUBRIQUE"] .= "::" . cleanCybertag(Pelican_Db::$values[$type . "_TITLE_BO"]);
            }
            
            Pelican_Db::$values["TAG_RUBRIQUE"] = str_replace("-", "_", Pelican_Db::$values["TAG_RUBRIQUE"]);
            if (Pelican_Db::$values["TAG_RUBRIQUE"] != "page_d_accueil") {
                Pelican_Db::$values["TAG_RUBRIQUE"] = str_replace("page_d_accueil::", "", Pelican_Db::$values["TAG_RUBRIQUE"]);
            }
            
            $id = Pelican_Db::$values[$type . "_ID"];
            $label = Pelican_Db::$values[$type . "_TITLE_BO"];
            if ($id) {
                Pelican_Db::$values["TAG_ID"] = Pelican_Db::$values["TAG_TYPE"] . "=" . $id;
            }
            if (! Pelican_Db::$values["TAG_LABEL"]) {
                Pelican_Db::$values["TAG_LABEL"] = $label;
            }
            if (Pelican_Db::$values["TAG_ID"]) {
                $url = getParams(Pelican_Db::$values["TAG_ID"]);
                Pelican_Db::$values["TAG_ID"] = $url;
                $temp = array();
                parse_str($url, $output);
                foreach ($output as $param => $url_value) {
                    switch ($param) {
                        case "pid":
                        case "cid":
                        case "tpl":
                            {
                                Pelican_Db::$values["TAG_" . strtoupper($param)] = $url_value;
                                break;
                            }
                        default:
                            {
                                $temp[] = $param . "=" . $url_value;
                                break;
                            }
                    }
                }
                if ($temp)
                    Pelican_Db::$values["TAG_OTHER"] = implode("&", $temp);
                $oConnection->deleteQuery("#pref#_tag");
                if ($_REQUEST["form_action"] != Pelican::$config["DATABASE_DELETE"] && Pelican_Db::$values["TAG_RUBRIQUE"] && Pelican_Db::$values["TAG_SECTION"]) {
                    $oConnection->insertQuery("#pref#_tag");
                }
            }
        }
    }

    function cleanCybertag ($text)
    {
        $return = $text;
        $return = Pelican_Text::cleanText(str_replace("1|", "", preg_replace("/(\#([0-9]+)\|)/si", "", $return)), "_");
        $return = str_replace("xxxx", "::", $return);
        return $return;
    }

}

function getParams ($url)
{
    $return = $url;
    
    $temp = parse_url("http://fictif/" . $return);
    if ($temp["query"]) {
        $url = $temp["query"];
    } else {
        $url = $temp["path"];
    }
    $return = preg_replace("/^(\/?)(?)/si", "$2", $url);
    
    return $return;
}
?>