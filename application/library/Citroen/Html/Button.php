<?php

namespace Citroen\Html;
/**
 * __DESC__
 *
 * @package Citroen
 * @subpackage Html
 * @copyright Copyright (c) 2014- Business&Decision
 * @author Pierre Pottié  <pierre.pottie@businessdecision.com>
 */

class Button{

     static public $className = 'Button';
     static private $defaultCss = 'button';

     protected $classCss = array();
     protected $name;
     protected $type;
     protected $lib;
     protected $function;
     protected $disabled;
     protected $moreAttr;
     protected $id;
     protected $value;

      protected $wrap;

	 /**
       * Génère un bouton
       *
       * @access public
       * @param string $name Nom du champ
       * @param string $type type de boutton : button, reset, submit
       * @param string $lib (option) Libellé du champ : "" par défaut
       * @param array $eventFunction (option) Fonction js à exécuter selon l'event fourni en clf du tableau
       * @param bolean $disabled (option) Bolean indiquant si le bouton à generer est
       * desactiver ou
       * @param string $classCss classes Css qui seront ajouté à l'attribut class de la balise button
       * @param string $moreAttr ajouter d'autres attributs à la balise button
       * @return string
       */

       public function __construct ($name, $type ,$lib = "", $eventFunction = "", $disabled = false, $classCss="" , $moreAttr ="") {
       		  $this->name =$name;
            $this->id =$name;
            $this->type = $type;
            $this->lib = $lib;
            $this->eventFunction = $eventFunction;
            $this->disabled = $disabled;

            $this->addClassCss(self::$defaultCss);
            $this->addClassCss($classCss);
     
            $this->moreAttr = $moreAttr;

            
        }
        public function addClassCss($css){
          if( !empty($css) ){
            $css = preg_split("/[\s]+/",$css);
            $this->classCss = array_merge($this->classCss,$css);
          }
        }

        public function set($attr, $value){
        	if (property_exists(self::$className,$attr)){
        		$this->{$attr} = $value;
        	}
        }

        public function get($attr){
        	if (property_exists(self::$className,$attr)){
        		$r = $this->{$attr} ;
        	}
        	return $r;
        }

      public function wrap($wrap){
        $this->wrap = explode('|',$wrap);
      }

     public function render(){
     	$html = "<button class=\"".implode(' ',$this->classCss)."\" name=\"".$this->name."\" id=\"".$this->id."\" type=\"".$this->type."\" ".$this->moreAttr;
      $html.= " value=\"" . \Pelican_Text::htmlentities($this->lib) . "\"";
           if ($this->disable) {
                $html.= " disabled";
            }
      if(is_array($this->eventFunction)){
        foreach($this->eventFunction as $event=>$function){
          $html .= " ".$event."=\"".$function."\"";
        }
      }
      $html .= " >".\Pelican_Text::htmlentities($this->lib)."</button>";
      return $this->wrap[0].$html.$this->wrap[1];
     }

}