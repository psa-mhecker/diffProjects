<?php

class Layout_Model_Navigation_Controller extends Pelican_Controller_Front
{

    protected $_level;

    protected $_tree;

    public function indexAction()
    {
        
        $temp = Pelican_Cache::fetch("Frontend/Navigation", array(
            $_SESSION[APP]['SITE_ID'] , 
            $_GET["pid"]));
        
        $aPath = array();
        if ($temp["PAGE_PATH"]) {
            $aPath = explode("#", $temp["PAGE_PATH"]);
        }
        
        $this->_level = $aPath;
        
        $this->_tree = Pelican_Cache::fetch("Frontend/Site/Tree", array(
            $_SESSION[APP]['SITE_ID'] , 
            $_SESSION[APP]['LANGUE_ID'] , 
            Pelican::getPreviewVersion() , 
            false , 
            implode("#", $aPath)));
        
        if ($this->_tree) {
            $aMenu0 = $this->_buildLevel(0);
            $aMenu1 = $this->_buildLevel(1);
            $aMenu2 = $this->_buildLevel(2);
            $aMenu3 = $this->_buildLevel(3);
        }
        
        $url = preg_replace("#\?\.*#", "", $_SERVER["REQUEST_URI"]);
        
        /** Libelle pour le contenu libre */
        $lib = '';
        if (! empty($_GET["cid"])) {
            $tmpLib = Pelican_Cache::fetch("Frontend/Content/Template", array(
                $_GET["cid"] , 
                $_SESSION[APP]['SITE_ID'] , 
                $_SESSION[APP]['LANGUE_ID'] , 
                Pelican::getPreviewVersion()));
            $lib = $tmpLib["CONTENT_TITLE"];
        }
        
        $this->assign("url", $url);
        $this->assign("lib", $lib);
        $this->assign("aMenu0", $aMenu0);
        $this->assign("aMenu1", $aMenu1);
        $this->assign("aMenu2", $aMenu2);
        $this->assign("aMenu3", $aMenu3);
        $this->fetch();
    }

    protected function _buildLevel($level)
    {
        $var = '';
        if (! empty($this->_level[$level])) {
            if (! empty($this->_tree->aParams[$this->_level[$level]]["child"])) {
                $aChild = $this->_tree->aParams[$this->_level[$level]]["child"];
                $i = 0;
                foreach ($aChild as $key) {
                    $values = $this->_tree->aNodes[$this->_tree->aParams[$key]["record"]];
                    $var[$i]["id"] = $values->id;
                    $var[$i]["lib"] = $values->lib;
                    $var[$i]["shortlib"] = (! empty($values->PAGE_TITLE_BO) ? $values->PAGE_TITLE_BO : '');
                    $lientemp = (! empty($values->PAGE_CLEAR_URL) ? $values->PAGE_CLEAR_URL : makeClearUrl($values->id, "pid", $values->lib));
                    $var[$i]["href"] = $lientemp;
                    $var[$i]["selected"] = (! empty($this->_level[$level + 1]) ? ($values->id == $this->_level[$level + 1]) : false);
                    if (! empty($this->_tree->aParams[$values->id]["child"])) {
                        $var[$i]["ssmenu"] = true;
                    }
                    $i ++;
                }
            }
        }
        return $var;
    }

}
