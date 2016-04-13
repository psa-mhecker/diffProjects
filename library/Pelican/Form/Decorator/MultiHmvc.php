<?php
/** Class Decorator de l'Element Multi en Hmvc
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 02/11/2011
 * @package Pelican + Zend
 */
 
 Class Pelican_Form_Decorator_MultiHmvc extends Pelican_Form_Decorator_Abstract {
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {	
		global $_GET, $_POST, $_SERVER;	
		
		$aProperties  = $oElement->getProperties();
		$strName	  = $oElement->getFullyQualifiedName();
		$strLib		  = $oElement->getLabel();
		
		$strTag = "<tr><td class=\"".$aProperties["sStyleLib"]."\"></td><td class=\"".$aProperties["sStyleVal"]."\">&nbsp;</td></tr>";
		$strTag = "<tr><td colspan=\"".$aProperties["sColspan"]."\" class=\"formsep\">&nbsp;</td></tr>";
		
		//Variable concernant le multi			
		$values	= $aProperties['tabValues'];
		$readO = $aProperties['bReadOnly'];				
		$bReadOnly = $aProperties['bReadOnly'];	
		
		/* On Sauvegarde les hiddens car sinon ils vont être effacé par la nouvelle 
		instance de la class form (la variable est static) */
		$aTmpHiddens = Pelican_Form::getHiddens();
		
		//Configuration de la nouvelle class form
		$oForm = Pelican_Factory::getInstance('Form', true);
		$oForm->open(Pelican::$config["DB_PATH"]);
        beginFormTable();
		/* On ne rajoute pas le tag form */
		$oForm->hideFormTabTag(array("hideTab" => true, "hideForm" => true, "createXhtml" => true));
		
        $oForm->bDirectOutput = false;
		
		/*****************************/
		/* Mettre le code ici		 */
		/*****************************/
		$compteur = - 1;
		$strPrefixe2 = $aProperties["strPrefixe"];
        $strTag .= "<tr><td id=\"td_" . $strName . "\" colspan=\"" . $aProperties['sColspan'] . "\" width=\"100%\">";
        foreach ($aProperties["tabValues"] as $line) {
			$compteur ++;
            
            if ($compteur % 2) {
                $strCss2 = "background-color=#F9FDF3;";
                $color = "#F9FDF3";
            } else {
                $strCss2 = "background-color=#FAEADA;";
                $color = "#FAEADA";
            }
			
			$$aProperties["strPrefixe"] = $strPrefixe2 . $compteur . "_";
			
			$strTag .= "<table bgcolor=\"".$color."\"cellspacing=\"0\" cellpadding=\"0\" style='" . $strCss2 . "' class=\"" . $aProperties['strCss'] . "\" id=\"" . $$aProperties["strPrefixe"] . "multi_table\" width=\"100%\">";
            $multi = $$aProperties["strPrefixe"];
            $strTag .= Pelican_Form::headMulti($multi, $compteur, $readO, $aProperties['bAllowDeletion'], $aProperties);
            $oForm->createJs("if (document.getElementById('" . $$aProperties["strPrefixe"]  . "multi_display')) {\n if (document.getElementById('" . $$aProperties["strPrefixe"] . "multi_display').value) {\n");
            // retro compatibite
            if (! empty($aProperties["call"]['path'])) {
                include_once ($aProperties["call"]['path']);
            }
            //hmvc
			$strTag .= call_user_func_array(array(
                $aProperties["call"]['class'] , 
                $aProperties["call"]['method']
            ), array(
                $oForm , 
                $line , 
                $bReadOnly ,
                $multi
            ));
            
            // fin du js
            $oForm->createJs("}\n}\n");
            $strTag .= "</table>\n";
		}
		
		//Iframe
		$aAttribs['id'] 		  = "iframe_" . $strName;
		$aAttribs['name'] 		  = "iframe_" . $strName;
		$aAttribs['width'] 		  = 0;
		$aAttribs['height'] 	  = 0;
		$aAttribs['scrolling'] 	  = "no";
		$aAttribs['marginwidth']  = 0;
		$aAttribs['frameborder']  = 0;
		$aAttribs['marginheight'] = 0;
		$aAttribs['src'] = "/library/blank.html";
		//$strTag .= $oView->formIframe($aAttribs);			
		unset($aAttribs);		
		$strTag .= "</td></tr>";		
		
		/* Referme le formulaire temporaire */
        endFormTable();
        $oForm->close();
		
		if (!$aProperties['bReadOnly'] && $aProperties['bAllowAdd']) {
			$value = ($aProperties['sButtonAddMulti'] ? $aProperties['sButtonAddMulti'] : t('FORM_BUTTON_ADD_MULTI') . " " . Pelican_Text::htmlentities($strLib));
			$aAttribs["style"] = "width:200px;";
			$aAttribs["class"] = "buttonmulti";
			$aAttribs["id"] = $strName;
			//$aAttribs["onclick"] = "addMulti(document." . $aProperties['strFormName'] . ", '" . $strName . "','" . $aProperties['call']['path'] . "," . $aProperties['call']['class'] . "," . $aProperties['call']['method'] . "','" . $strPrefixe2 . "',document." . $aProperties['strFormName'] . ".count_" . $strName . ".value,'" . $aProperties['intMaxIterations'] . "'," . ($aProperties['bAllowDeletion'] ? "true" : "false") . ",'" . $aProperties['complement'] . "')";
			$aAttribs["onclick"] = "pelican_form_addMulti(document." . $aProperties['strFormName'] . ", '" . $strName . "','" . $aProperties['call']['path'] . "," . $aProperties['call']['class'] . "," . $aProperties['call']['method'] . "','" . $strPrefixe2 . "',document." . $aProperties['strFormName'] . ".count_" . $strName . ".value,'" . $aProperties['intMaxIterations'] . "'," . ($aProperties['bAllowDeletion'] ? "true" : "false") . ",'" . $aProperties['complement'] . "')";
			$strTag .= "<tr><td class=\"".$aProperties["sStyleLib"]."\">".$oView->formButton($strName, $value, $aAttribs)."</td><td class=\"".$aProperties["sStyleVal"]."\"></td></tr>";
		}
		
		/* On replace les hiddens dans l'instance de form principale */
		Pelican_Form::addHiddens($aTmpHiddens);
		
		Pelican_Form::addHidden("prefixe_" . $strName, $aProperties["strPrefixe"]);
        Pelican_Form::addHidden("increment_" . $strName, $aProperties["incrementField"]);
        Pelican_Form::addHidden("count_" . $strName, (count($aProperties["tabValues"]) - 1));
        if ($aProperties["intMaxIterations"]) {
            Pelican_Form::addHidden("max_" . $strName, $aProperties["intMaxIterations"]);
        }
		
		return $strTag;
	}
	

 }
 ?>