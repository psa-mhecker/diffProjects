<?php
/** Gestion des onglets de formulaires
 *
 * @package Pelican
 * @subpackage Pelican_Index
 */

class Backoffice_Tab_Helper
{

    public $aTabDef = array();

    public $aTab = array();

    public $i;
    
    public static $countOnglet;

    public function __construct($id, $skinPath = "")
    {
        $this->id = $id;
        $this->skinPath = $skinPath;
        return true;
    }

    public function addTab ($sLabel, $id = "", $bSelected = false, $sHref = "", $onclick = "", $title = "", $size = "", $width = "", $bDirectOutput = true, $limit = "")
    {
        if (!$title) {
                $title = t('RUBRIQUES');
        }

        $this->aTabDef[] = array(label => $sLabel , id => $id , selected => $bSelected , href => $sHref , onclick => $onclick , title => $title , size => $size , width => $width , limit => $limit);
        $this->last = count($this->aTabDef);
    
    }

    public function buildTab ($i)
    {
        global $title_left;
        
        if ($this->aTabDef[$i]) {
            
            $sLabel = $this->aTabDef[$i]["label"];
            $id = $this->aTabDef[$i]["id"];
            $bSelected = $this->aTabDef[$i]["selected"];
            if ($this->aTabDef[$i]["href"]) {
                $onclick = "document.location.href='" . $this->aTabDef[$i]["href"] . "'";
            } else {
                $onclick = $this->aTabDef[$i]["onclick"];
            }
            $title = $this->aTabDef[$i]["title"];
            $size = $this->aTabDef[$i]["size"];
            $width = $this->aTabDef[$i]["width"];
            $limit = $this->aTabDef[$i]["limit"];
            
            ++ self::$countOnglet;
            if ($size) {
                $size .= "_";
            }
            
            $state = ($bSelected ? "on" : "off");
            
            $img = $this->skinPath . "/images/" . $size . "onglet_" . $state;
            $imageLeft = $img . "_gauche" . (self::$countOnglet > 1 ? "_int" : "") . ".gif";
            $imageRight = $img . "_droite" . (self::$countOnglet < $this->last ? "_int" : "") . ".gif";
            
            if ($state == "off" || $onclick) {
                $sLabel = Pelican_Html::a(array(onclick => $onclick), $sLabel);
            }
            
            $onglet = Pelican_Html::div(array("class" => $size . "onglet " . $size . "onglet_side"), Pelican_Html::img(array(id => $id . "_1" , border => 0 , alt => "" , src => $imageLeft)));
            $onglet .= Pelican_Html::div(array(id => $id . "_2" , "class" => $size . "onglet " . $size . "onglet_centre" , style => "background-image: url(" . $img . "_centre.gif);" , width => $width , onclick => $onclick), $sLabel);
            $onglet .= Pelican_Html::div(array("class" => $size . "onglet " . $size . "onglet_side"), Pelican_Html::img(array(id => $id . "_3" , border => 0 , alt => "" , src => $imageRight)));
            
            $return = Pelican_Html::div(array("class" => $size . "onglet"), $onglet);
            
            if ($bSelected) {
                $title_left = $title;
            }
        }
        return $return;
    }

    public function getTabs ()
    {
        $return = "";
        if ($this->aTabDef) {
            for ($i = 0; $i < count($this->aTabDef); $i ++) {
                $return .= $this->buildTab($i);
            }
        }
        return $return;
    }
}
?>