<?php
    /**
     * * Librairie des tags CyberTag.
     *
     * @since 16/02/2005
     *
     * @author Raphaël Carles <rcarles@businessdecision.com>
     */
    include_once 'config.php';

    function getTag(&$oForm, $id, $site, $readO, $section = "", $rubrique = "", $param = "pid")
    {
        $oConnection = Pelican_Db::getInstance();
        $form = '';

        $temp = explode("=", $id);
        $type = ($temp[0] == "pid" ? "PAGE" : "CONTENT");
        //if (!$readO) $oForm->createJs("if (!obj.TAG_RUBRIQUE.value && obj.".$type."_TITLE.value) obj.TAG_RUBRIQUE.value = Pelican_Text::cleanText(obj.".$type."_TITLE.value, false, true);");
        $form .= $oForm->showSeparator();
        $values = $oConnection->queryRow("SELECT * from #pref#_tag WHERE TAG_ID='".$id."' AND SITE_ID=".$site);
        //$sqlSection = "SELECT DISTINCT TAG_SECTION from #pref#_tag where SITE_ID=".$site." order by lower(TAG_SECTION)";
        //$oConnection->query($sqlSection);
        $form .= $oForm->createInput("TAG_SECTION", "Stat (zone)", 255, "", false, ($values["TAG_SECTION"] ? $values["TAG_SECTION"] : $section), $readO, 30, false, "", "text");//, $oConnection->data["TAG_SECTION"]);
        //$sqlRubrique = "SELECT DISTINCT TAG_RUBRIQUE from #pref#_tag where SITE_ID=".$site;
        //$oConnection->query($sqlRubrique);
        $form .= $oForm->createInput("TAG_RUBRIQUE", "Stat (page)", 255, "", false, ($values["TAG_RUBRIQUE"] ? $values["TAG_RUBRIQUE"] : $rubrique), $readO, 100, false, "", "text");//, $oConnection->data["TAG_RUBRIQUE"]);
        $form .= $oForm->createHidden("TAG_TYPE", $param);

        return $oForm->output($form);
    }

    function updateCyberTag($type = "", $id = "")
    {
        $oConnection = Pelican_Db::getInstance();

        if ((Pelican_Db::$values["PAGE_ID"] && !is_array(Pelican_Db::$values["PAGE_ID"])) || (Pelican_Db::$values["CONTENT_ID"] && !is_array(Pelican_Db::$values["CONTENT_ID"]))) {

            /* cas de la création d'un contenu */
            if (!Pelican_Db::$values["TAG_SECTION"]) {
                $pid = ($type == "PAGE" ? Pelican_Db::$values["PAGE_PARENT_ID"] : Pelican_Db::$values["PAGE_ID"]);
                Pelican_Db::$values["TAG_SECTION"] = $oConnection->queryItem("select TAG_SECTION from #pref#_tag WHERE TAG_PID=:PAGE_ID AND (TAG_CID IS NULL OR TAG_CID = '')", array(":PAGE_ID" => $pid));
            }

            if (Pelican_Db::$values["TAG_SECTION"] && !Pelican_Db::$values["TAG_RUBRIQUE"]) {
                $pid = ($type == "PAGE" ? Pelican_Db::$values["PAGE_PARENT_ID"] : Pelican_Db::$values["PAGE_ID"]);
                Pelican_Db::$values["PAGE_LIBPATH"] = $oConnection->queryItem("select PAGE_LIBPATH from #pref#_page WHERE PAGE_ID=:PAGE_ID", array(":PAGE_ID" => $pid));
                Pelican_Db::$values["TAG_RUBRIQUE"] = cleanCybertag(Pelican_Db::$values["PAGE_LIBPATH"]);
                Pelican_Db::$values["TAG_RUBRIQUE"] .= "::".cleanCybertag(Pelican_Db::$values[$type."_TITLE_BO"]);
            }

            Pelican_Db::$values["TAG_RUBRIQUE"] = str_replace("-", "_", Pelican_Db::$values["TAG_RUBRIQUE"]);
            if (Pelican_Db::$values["TAG_RUBRIQUE"] != "page_d_accueil") {
                Pelican_Db::$values["TAG_RUBRIQUE"] = str_replace("page_d_accueil::", "", Pelican_Db::$values["TAG_RUBRIQUE"]);
            }

            $id = Pelican_Db::$values[$type."_ID"];
            $label = Pelican_Db::$values[$type."_TITLE_BO"];
            if ($id) {
                Pelican_Db::$values["TAG_ID"] = Pelican_Db::$values["TAG_TYPE"]."=".$id;
            }
            if (!Pelican_Db::$values["TAG_LABEL"]) {
                Pelican_Db::$values["TAG_LABEL"] = $label;
            }
            if (Pelican_Db::$values["TAG_ID"]) {
                //$url = urldewrite(Pelican_Db::$values["TAG_ID"]);
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
                            Pelican_Db::$values["TAG_".strtoupper($param)] = $url_value;
                            break;
                        }
                        default :
                        {
                            $temp[] = $param."=".$url_value;
                            break;
                        }
                    }
                }
                if ($temp) {
                    Pelican_Db::$values["TAG_OTHER"] = implode("&", $temp);
                }
                $oConnection->deleteQuery("#pref#_tag");
                if ($_REQUEST["form_action"] != Pelican::$config["DATABASE_DELETE"] && Pelican_Db::$values["TAG_RUBRIQUE"] && Pelican_Db::$values["TAG_SECTION"]) {
                    $oConnection->insertQuery("#pref#_tag");
                }
                //Pelican_Cache::clean("Frontend/Cybertag", Pelican_Db::$values['SITE_ID']);
            }
        }
    }

    function getParams($url)
    {
        $return = $url;

        $temp = parse_url("http://fictif/".$return);
        if ($temp["query"]) {
            $url = $temp["query"];
        } else {
            $url = $temp["path"];
        }
        $return = preg_replace("/^(\/?)(?)/si", "$2", $url);

        return $return;
    }
    //obsolete
    function putCyberTag($CM_RUBRIQUE, $CM_CLIENT, $CM_SECTION1)
    {
        if ($CM_CLIENT && !$_SESSION["monitoring"]["sonde"]) {
            switch (Pelican::$config["SERVER_PROTOCOL"]) {
                case "http":
                {
                    $CyberTag = "<!-- DEBUT / Cyberestat / START -->
						<script language=\"javascript\"><!--
						CM_RUBRIQUE = \"".$CM_RUBRIQUE."\";
						CM_CLIENT = \"".$CM_CLIENT."\";
						CM_SECTION1 = \"".$CM_SECTION1."\";
						// --></script>
						<script language=\"JavaScript\" src=\"".Pelican::$config["SERVER_PROTOCOL"]."://js.cybermonitor.com/".$CM_CLIENT.".js\" defer=\"defer\"></script>
						<noscript>
						<img src=\"".Pelican::$config["SERVER_PROTOCOL"]."://stat3.cybermonitor.com/".$CM_CLIENT."_v?R=".$CM_RUBRIQUE."&S=total;".$CM_SECTION1."\">
						</noscript>
						<!-- FIN / Cyberestat / END -->";
                    break;
                }
                case "https":
                {
                    $CyberTag = "<!-- DEBUT / Cyberestat / START -->
						<script language=\"javascript\"><!--
						CM_RUBRIQUE = \"".$CM_RUBRIQUE."\";
						CM_CLIENT = \"".$CM_CLIENT."\";
						CM_SECTION1 = \"".$CM_SECTION1."\";
						// --></script>
						<script language=\"JavaScript\" src=\"".Pelican::$config["SERVER_PROTOCOL"]."://prof.estat.com/".$CM_CLIENT.".js\" defer=\"defer\"></script>
						<noscript>
						<img src=\"".Pelican::$config["SERVER_PROTOCOL"]."://prof.estat.com/m/web/08593?p=".$CM_RUBRIQUE."&c=total;".$CM_SECTION1."\">
						</noscript>
						<!-- FIN / Cyberestat / END -->";
                    break;
                }
            }
            if ($_SERVER["SCRIPT_NAME"] == "/index.php" || $_SERVER["SCRIPT_NAME"] == "/index_popup.php") {
                $_SESSION["CyberTag"] = str_replace(" defer=\"defer\"", "", $CyberTag);
            }

            return $CyberTag;
        }
    }

    function testUrl($url)
    {
        global $testTag;
        if ($url) {
            $result["URL testée"] = $url;
            //        $rewrite = urldewrite($url);
            $result["URL réelle"] = $rewrite;
            $url = getParams($rewrite);
            $result["TAG cherché"] = $url;
            parse_str($url, $_GET);

            $CyberTag = Pelican_Cache::fetch("Frontend/Cybertag", $_SESSION[APP]['SITE_ID']);
            $CYBER_CLIENT = ($CyberTag["client"] ? $CyberTag["client"] : APP);
            $CYBER = $CyberTag["values"];
            $tag = getSiteTag($CYBER, $url);

            $result["TAG utilisé"] = $url;
            $result["-----"] = "&nbsp;";
            $result["CM_CLIENT"] = Pelican_Html::b($CYBER_CLIENT);
            $result["CM_SECTION"] = Pelican_Html::b($tag[1]);
            $result["CM_RUBRIQUE"] = Pelican_Html::b($tag[0]);
            $testTag[0] = $tag[0];
            $testTag[1] = $tag[1];
            $result["javascript"] = putCyberTag($tag[0], $CYBER_CLIENT, $tag[1]);
            //        $result["PHP"] = "\$CYBER[\"".($result["url"] != $result["utilisé"]?$result["utilisé"]:$result["url"])."\"] = array(\"".$result["CM_RUBRIQUE"]."\", \"".$result["CM_SECTION"]."\");";
            if ($result) {
                foreach ($result as $key => $value) {
                    if ($key == "javascript") {
                        $temp = explode("\r\n", $value);
                        $temp = array_map("trim", $temp);
                        $value = implode("\r\n", $temp);
                        $value = "<pre>".str_replace("\t", "", htmlentities($value))."</pre>";
                    }
                    $tr[] = Pelican_Html::tr(array("class" => "line"), Pelican_Html::td(array("class" => "label"), $key).Pelican_Html::td(array("class" => "value"), "<nobr>".$value."</nobr>"));
                }
                $return = Pelican_Html::table(array("class" => "cyber"), implode("", $tr));
            }

            return $return;
        }
    }

    function cleanCybertag($text)
    {
        $return = $text;
        $return = Pelican_Text::cleanText(str_replace("1|", "", preg_replace("/(\#([0-9]+)\|)/si", "", $return)), "_");
        $return = str_replace("xxxx", "::", $return);

        return $return;
    }
