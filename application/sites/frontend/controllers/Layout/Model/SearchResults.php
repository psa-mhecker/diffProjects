<?php

class Layout_Model_SearchResults_Controller extends Pelican_Controller_Front
{

    public function indexAction ()
    {
        $zone = $this->getParams();
        if (! $_GET["step"]) {
            $step = Pelican::$config["RESULT_STEP"];
        } else {
            $step = $_GET["step"];
        }
        
        // recPer, recPage, recMot, recRub, recCategory, recAv, recDate1, recDate2, recFic, recLang, recChamp
        

        if (! $_GET["recPer"])
            $_GET["recPer"] = "date";
        if (! $_GET["recPage"])
            $_GET["recPage"] = 1;
        
        $url = str_replace("&recPage=" . $_GET["recPage"], "", $_SERVER['REQUEST_URI']);
        $per = str_replace("&recPer=" . $_GET["recPer"], "", $url);
        $urlStep = str_replace("&step=" . $_GET["step"], "", $url);
        
        if ($_GET["research"]) {
            $oSearch = Pelican_Factory::getInstance('Search', "Db");
            
            if ($_GET["recChamp"] == "titre") {
                $aFilter[] = array(
                    "r.RESEARCH_TITLE" , 
                    $_GET["recMot"] , 
                    "keyword"
                );
            } elseif ($_GET["recChamp"] == "contenu") {
                $aFilter[] = array(
                    "RESEARCH_CONTENT" , 
                    $_GET["recMot"] , 
                    "keyword"
                );
            } else {
                $aFilter[] = array(
                    "r.RESEARCH_TITLE" , 
                    $_GET["recMot"] , 
                    "keyword"
                );
                $aFilter[] = array(
                    "RESEARCH_CONTENT" , 
                    $_GET["recMot"] , 
                    "keyword"
                );
            }
            
            //$aFilter[] = array("MORE_LEVEL1" , @explode(",", $_GET["recRub"]) , "list");
            //$aFilter[] = array("MORE_CATEGORY" , @explode(",", $_GET["recCategory"]) , "list");
            // $aFilter[] = array("MORE_THEME", $_GET["recTheme"]);
            //$aFilter[] = array("MORE_FLAG" , $_GET["recLang"] , "string");
            $aFilter[] = array(
                "RESEARCH_DATE" , 
                $_GET["recDate1"] , 
                "date" , 
                ">="
            );
            $aFilter[] = array(
                "RESEARCH_DATE" , 
                $_GET["recDate2"] , 
                "date" , 
                "<="
            );
            
            $oSearch->getStatistics($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], array(
                "MORE_LEVEL1"
            ), $aFilter);
            
            if ($_SESSION[APP]["current_search"]["statistics"]) {
                $result = $oSearch->getResult($_GET["recPage"], $step, $_GET["recPer"], array(
                    "MORE_FILESIZE" , 
                    "MORE_FILETYPE" , 
                    "MORE_FLAG" , 
                    "MORE_PATH"
                ));
                
                if ($result) {
                    foreach ($result as $key => $tmp) {
                        $path = array();
                        if ($tmp["MORE_PATH"]) {
                            $Items = explode("#", $tmp["MORE_PATH"]);
                            foreach ($Items as $item) {
                                $path[] = explode("|", $item);
                            }
                            $result[$key]["MORE_PATH"] = $path;
                            array_shift($result[$key]["MORE_PATH"]);
                        }
                    }
                }
            }
            
            /** bilan */
            $aReport["step"] = $step;
            $aReport["total"] = $_SESSION[APP]["current_search"]["statistics"]["count"];
            $aReport["totalpage"] = (int) ($aReport["total"] / $step) + 1;
            
            $aReport["page"] = ($_GET["recPage"] <= $aReport["totalpage"] ? $_GET["recPage"] : $aReport["totalpage"]);
            $aReport["min"] = ($aReport["page"] - 1) * $step + 1;
            $aReport["max"] = $aReport["min"] + $step - 1;
            $aReport["max"] = ($aReport["max"] < $aReport["total"] ? $aReport["max"] : $aReport["total"]);
            $aReport["url"] = $url;
            $aReport["urlStep"] = $urlStep;
            $aReport["per"] = $per;
            $aReport["typeper"] = $_GET["recPer"];
        }
        
        $this->assign("aResearch", $_SESSION[APP]["current_search"]);
        $this->assign("aReport", $aReport);
        $this->assign("list", $result);
        
        $this->assign("title", $zone['PAGE_TITLE']);
        $this->assign("zone", $zone);
        $this->assign("recMot", Pelican_Security::escapeXSS($_GET["recMot"]));
        
        //$this->model();
        $this->fetch();
    }
}