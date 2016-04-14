<?php
/**
 * Gestion des onglets de formulaires.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */

/**
 * Gestion des onglets de formulaires.
 *
 * @author __AUTHOR__
 */
class Pelican_Form_Tab
{
    /**
     * @access public
     *
     * @var __TYPE__ __DESC__
     */
    public $aTabDef = array();

    /**
     * @access public
     *
     * @var __TYPE__ __DESC__
     */
    public $aTab = array();

    /**
     * @access public
     *
     * @var __TYPE__ __DESC__
     */
    public $i;
    public static $countOnglet;

    /**
     * @static
     * @access public
     *
     * @var __TYPE__ __DESC__
     */
    public static $imgPath;

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $id      __DESC__
     * @param string   $imgPath (option) __DESC__
     *
     * @return __TYPE__
     */
    public function __construct($id, $imgPath = '')
    {
        $this->id = $id;
        $this->setImgPath($imgPath);

        return true;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $imgPath __DESC__
     *
     * @return __TYPE__
     */
    public function setImgPath($imgPath)
    {
        if (!empty($imgPath)) {
            self::$imgPath = $imgPath;
        }
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $sLabel        __DESC__
     * @param string   $id            (option) __DESC__
     * @param bool     $bSelected     (option) __DESC__
     * @param string   $sHref         (option) __DESC__
     * @param string   $onclick       (option) __DESC__
     * @param string   $title         (option) __DESC__
     * @param string   $size          (option) __DESC__
     * @param string   $width         (option) __DESC__
     * @param bool     $bDirectOutput (option) __DESC__
     * @param string   $limit         (option) __DESC__
     *
     * @return __TYPE__
     */
    public function addTab($sLabel, $id = "", $bSelected = false, $sHref = "", $onclick = "", $title = "", $size = "", $width = "", $bDirectOutput = true, $limit = "")
    {
        if (!$title) {
            $title = t('RUBRIQUES');
        }
        $this->aTabDef[] = array(label => $sLabel, id => $id, selected => $bSelected, href => $sHref, onclick => $onclick, title => $title, size => $size, width => $width, limit => $limit);
        $this->last = count($this->aTabDef);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $i __DESC__
     *
     * @return __TYPE__
     */
    public function buildTab($i)
    {
        global $title_left;
        if ($this->aTabDef[$i]) {
            $sLabel = $this->aTabDef[$i]["label"];
            $id = $this->aTabDef[$i]["id"];
            $bSelected = $this->aTabDef[$i]["selected"];
            if ($this->aTabDef[$i]["href"]) {
                $onclick = "document.location.href='".$this->aTabDef[$i]["href"]."'";
            } else {
                $onclick = $this->aTabDef[$i]["onclick"];
            }
            $title = $this->aTabDef[$i]["title"];
            $size = $this->aTabDef[$i]["size"];
            $width = $this->aTabDef[$i]["width"];
            $limit = $this->aTabDef[$i]["limit"];

            /*
             * @static
             * @access public
             * @var __TYPE__ __DESC__
             */
            ++self::$countOnglet;
            if ($size) {
                $size .= "_";
            }
            $state = ($bSelected ? "on" : "off");
            $img = self::$imgPath."/images/".$size."onglet_".$state;
            $imageLeft = $img."_gauche".(self::$countOnglet > 1 ? "_int" : "").".gif";
            $imageRight = $img."_droite".(self::$countOnglet < $this->last ? "_int" : "").".gif";
            if ($state == "off" || $onclick) {
                $sLabel = Pelican_Html::a(array(onclick => $onclick), $sLabel);
            }
            $onglet = Pelican_Html::div(array("class" => $size."onglet ".$size."onglet_side"), Pelican_Html::img(array(id => $id."_1", border => 0, alt => "", src => $imageLeft)));
            $onglet .= Pelican_Html::div(array(id => $id."_2", "class" => $size."onglet ".$size."onglet_centre", style => "background-image: url(".$img."_centre.gif);", width => $width, onclick => $onclick), $sLabel);
            $onglet .= Pelican_Html::div(array("class" => $size."onglet ".$size."onglet_side"), Pelican_Html::img(array(id => $id."_3", border => 0, alt => "", src => $imageRight)));
            $return = Pelican_Html::div(array("class" => $size."onglet"), $onglet);
            if ($bSelected) {
                $title_left = $title;
            }
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function getTabs()
    {
        $return = "";
        if ($this->aTabDef) {
            for ($i = 0;$i < count($this->aTabDef);$i++) {
                $return .= $this->buildTab($i);
            }
        }

        return $return;
    }
}
