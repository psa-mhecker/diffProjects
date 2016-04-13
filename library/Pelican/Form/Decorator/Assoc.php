<?php
/** Class Decorator de l'Element Assoc
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 24/03/2010
 * @package Pelican + Zend
 */
Class Pelican_form_Decorator_Assoc extends Pelican_Form_Decorator_Abstract {
	//Variable pour sql
	protected $_sTableSuffixeId = '_id';
	protected $_sTableSuffixeLabel = '_label';
	protected $_sTablePrefix = 'pel_';
	
	/**
     * Retourne les valeurs de la table $strTableName=>$aDataValues et les valeurs sélectionnées de la table $strRefTableName=>$aSelectedValues
     *
     * @return void
     * @param Pelican_Db $oConnection Objet connection è  la base
     * @param string $strTableName Nom de la table pour les valeurs sans $this->_sTablePrefix : "" par défaut
     * @param string $strRefTableName Nom de la table de jointure oè¹ trouver les valeurs sélectionnées : "" par défaut
     * @param string $iID id auquel sont associées les valeurs sélectionnées : "" par défaut
     * @param mixed $aDataValues Tableau de valeurs (id=>lib)
     * @param mixed $aSelectedValues Tableau des valeurs sélectionnées (liste des id)
     * @param string $strColRefTableName Nom de la colonne dans la table de référence correspondant è  $iID : "CONTENU_ID" par défaut
     */
	function _getValues (&$oConnection, $strTableName = "", $strRefTableName = "", $iID = "", &$aDataValues, &$aSelectedValues, $strColRefTableName = "contenu_id", $strOrderColName = '', $iSiteId = '')
    {
        $strSQL = "select " . $strTableName . $this->_sTableSuffixeId . " as \"id\", " . $strTableName . $this->_sTableSuffixeLabel . " as \"lib\" from " . $this->_sTablePrefix . $strTableName;
        if ($iSiteId != '') {
            $strSQL .= " where SITE_ID =" . $iSiteId;
        }
        $strSQL .= " order by \"lib\"";
        $oConnection->Query($strSQL);
        $aDataValues = array();
        if ($oConnection->data) {
            while ($ligne = each($oConnection->data["id"])) {
                $aDataValues[$ligne["value"]] = $oConnection->data["lib"][$ligne["key"]];
            }
        }
        $aSelectedValues = array();
        
        if (($strRefTableName != "") && ($iID != "")) {
            $strSQL = "select " . $strTableName . $this->_sTableSuffixeId . " as \"id\" from " . $strRefTableName . " where " . $strColRefTableName . " = " . $iID;
            if ($strOrderColName != "") {
                $strSQL .= " order by " . $strOrderColName;
            }
            $oConnection->Query($strSQL);
            if ($oConnection->data) {
                while ($ligne = each($oConnection->data["id"])) {
                    $aSelectedValues[count($aSelectedValues)] = $ligne["value"] . (($strTableName == "SECTEUR") ? " " : "");
                }
            }
        }
    }
	
	/**
	 * Gestion de la recherche et des valeurs selectionné
	 *
	 * @param Pelican_form_element $oElement
	 * @param array $aSelectedValues
	 * @return array
	 */
	private function getSelectedValue($oElement, $aSelectedValues) {
		/**
		 * Selection des valeurs selectionées
		 */
		$aProperties = $oElement->getProperties();
		$strName = $oElement->getFullyQualifiedName();
		$oConnection = $oElement->getConnection();
		
		if ($aProperties['bSearchEnabled']) {
			if ($aProperties['iID'] != ""){
				$aSelectedValues = array();
				$strSQL = "select A.".$aProperties['strTableName'].$this->_sTableSuffixeId." as \"id\", A.".$aProperties['strTableName'].$this->_sTableSuffixeLabel." as \"lib\"";
				$strSQL .= " from ".$this->_sTablePrefix.$aProperties['strTableName']." A, ".$aProperties['strRefTableName']." B";
				if ($aProperties['alternateId']) {
					$child = $strName;
				} else {
					$child = $aProperties['strTableName'].$this->_sTableSuffixeId;
				}
				$strSQL .= " where A.".$aProperties['strTableName'].$this->_sTableSuffixeId." = B.".$child;
				$strSQL .= " and B.".$aProperties['strColRefTableName']." = ".$aProperties['iID'];
				$strSQL .= " order by ";
				if ($aProperties['strOrderColName'] != "" ) {
					$strSQL .= $aProperties['strOrderColName'];
				} else {
					$strSQL .= "Lib";
				}
				$oConnection->Query($strSQL);
				if ($oConnection->rows > 0) {
					while ($ligne = each($oConnection->data["id"]) ) {
						$aSelectedValues[$ligne["value"]] = $oConnection->data["lib"][$ligne["key"]];
					}
				}
			} else {
				if (!isset($aSelectedValues)) {					
					$aSelectedValues = array ();
				}
			}
		} else {
			if ($aProperties['strTableName'] != "") {
				$aTmpSelectedValues = array();
				$this->_getValues($oConnection, $aProperties['strTableName'], $aProperties['strRefTableName'], $aProperties['iID'], $aDataValues, $aTmpSelectedValues, $aProperties['strColRefTableName'], $aProperties['strOrderColName']);
				$oElement->addMultiOptions($aDataValues);
			}
			if ($aSelectedValues == "" ) {
				$aSelectedValues = $aTmpSelectedValues;
			}
			if (!is_array($aSelectedValues) ) {
				if ($aSelectedValues != "" ) {
					$aSelectedValues = array($aSelectedValues);
				} else {
					$aSelectedValues = array();
				}
			}
		}
		
		if ($aSelectedValues == "" ) {
			$aSelectedValues = array();
		}
		return $aSelectedValues;
	}
	
	/**
	 * Création de la barre de recherche
	 *
	 * @param string $strName
	 * @param objet $oView
	 * @param int $iWidth
	 * @param array $arForeignKey
	 * @return string
	 */
	private function Search($oElement, $strName, $oView, $iWidth, $aProperties) {
		$oConnection = $oElement->getConnection();
		//sql recherche
		$sqlSearch = false;
		if ($aProperties['strTableName'] || $aProperties['arForeignKey']) {
			if ($aProperties['arForeignKey']) {
				// Si c'est un tableau, on défini le champ de recherche, la requête de la combo et la requête de recherche (sans clause where)
				if (is_array($aProperties['arForeignKey']) ) {
					$champForeign = $aProperties['arForeignKey'][0];
					// Si le second paramètre du tableau n'a pas été initialisé, on le défini avec une expressino générique (à partir du nom de la table)
					if (!$aProperties['arForeignKey'][1]) {
						$sqlForeign = "select ".$champForeign.$this->_sTableSuffixeId." \"id\",".$champForeign.$this->_sTableSuffixeLabel." \"lib\" from ".$this->_sTablePrefix.$champForeign." order by lib";
					} else {
						$sqlForeign = $aProperties['arForeignKey'][1];
					}
					$sqlSearch = $aProperties['arForeignKey'][2];
				} else {
					// sinon on prend juste le champ pour initialiser la procédure
					$champForeign = $aProperties['arForeignKey'];
					$sqlForeign = "select ".$champForeign.$this->_sTableSuffixeId." as \"id\",".$champForeign.$this->_sTableSuffixeLabel." \"lib\" from ".$this->_sTablePrefix.$champForeign." order by lib";
				}
				// Si la requête de recherche n'a pas été initialisée, on la définit de façon générique
				if (!$sqlSearch) {
					$sqlSearch = "select ".$aProperties['strTableName'].$this->_sTableSuffixeId." \"id\", ".$aProperties['strTableName'].$this->_sTableSuffixeLabel." \"lib\" from ".$this->_sTablePrefix.$aProperties['strTableName'];
					$sqlSearch .= " WHERE ".$champForeign.$this->_sTableSuffixeId." = ':RECHERCHE:'";
					$sqlSearch .= " order by lib";
				}
				// cas de la recherche par input
			} else {
				// Définition de la requête de recherche de façon générique pour la recherche par input
				$sqlSearch = "select ".$aProperties['strTableName'].$this->_sTableSuffixeId." \"id\", ".$aProperties['strTableName'].$this->_sTableSuffixeLabel." \"lib\" from ".$this->_sTablePrefix.$aProperties['strTableName'];
				$sqlSearch .= " WHERE ".$aProperties['strTableName'].$this->_sTableSuffixeLabel." LIKE ('%:RECHERCHE:%')";
				$sqlSearch .= " order by lib";
			}
			$action = "searchIndexation('/library/Pelican/Form/public/', 'src".$strName."', '".$aProperties['strTableName']."', eval('this.form.iSearchVal".$strName.".value'),'".base64_encode($sqlSearch)."'," . ($aProperties['showAll'] ? 1 : 0) . ");";
	  	} else {
			$action = "searchIndexation('/library/Pelican/Form/public/', 'src".$strName."', '', eval('this.form.iSearchVal".$strName.".value'),'".base64_encode($aProperties['strFormName']."_".$strName)."'," . ($aProperties['showAll'] ? 1 : 0) . ");";
	 	}				
				
		//création des champs de recherche
		if ($aProperties['arForeignKey']) {
			$aResult_Foreign = $oConnection->queryTab($sqlForeign);
			foreach ($aResult_Foreign as $aLigne) {
                $aOption[$aLigne['id']] = $aLigne['lib'];
			}
			$aOption = array('' => t('FORM_SELECT_CHOOSE')) + $aOption;
			$aAttribsSearch['id'] = "iSearchVal".$strName;
			$aAttribsSearch['style'] = 'width:'.$iWidth.'px;';
			$aAttribsSearch['size'] = 1;
			$aAttribsSearch['onchange'] = $action;
			$strSearch = $oView->selectAssoc("iSearchVal".$strName, $aAttribsSearch, $aOption);
		} else {
			$aAttribsSearch['size'] = 14;
			$aAttribsSearch['onkeydown'] = "submitIndexation('/library/Pelican/Form/public/', '" . ($aProperties['strTableName'] ? $aProperties['strTableName'] . "','" . base64_encode($sqlSearch) : "','" . base64_encode($aProperties['strFormName'] . "_" . $strName)) . "')";
			$strSearch = $oView->formText("iSearchVal".$strName, '', $aAttribsSearch);
			unset($aAttribsSearch);
			$aAttribsSearch['class'] = 'button';		
			$aAttribsSearch['onclick'] = $action;
			$strSearch .= $oView->formButton('bSearch'.$strName, t('FORM_BUTTON_SEARCH'), $aAttribsSearch);				
		}
		return $strSearch . '<br />';
	}
	
	/**
	 * Création de la barre de Order
	 *
	 * @param string $strName
	 * @param objet $oView
	 * @param string $strUrlImg
	 * @param booleen $bSingle
	 * @return string
	 */
	private function OrderName($strName, $oView, $strUrlImg, $bSingle, $strFormName) {
		$aPic['width'] = 13;
		$aPic['height'] = 15;
			
		$aPic['onMouseOver'] = "ChangeImg(this, 'top_over');";
		$aPic['onMouseOut']  = "ChangeImg(this, 'top');";
		$aPic['onMouseDown'] = "ChangeImg(this, 'top_click');";
		$aPic['onMouseUp']   = "ChangeImg(this, 'top_over');";
		$aPic['onClick'] 	 = "MoveTop(document.".$strFormName.".elements('".$strName.($bSingle?"":"[]")."'));";
		$strOrderColName = $oView->myImg($strUrlImg.'top.gif', $aPic) . '<br />';
		
		$aPic['onMouseOver'] = "ChangeImg(this, 'up_over');";
		$aPic['onMouseOut']  = "ChangeImg(this, 'up');";
		$aPic['onMouseDown'] = "ChangeImg(this, 'up_click');";
		$aPic['onMouseUp'] 	 = "ChangeImg(this, 'up_over');";
		$aPic['onClick']     = "MoveUp(document.".$strFormName.".elements('".$strName.($bSingle?"":"[]")."'));";
		$strOrderColName .= $oView->myImg($strUrlImg.'up.gif', $aPic) . '<br />';
		
		$aPic['onMouseOver'] = "ChangeImg(this, 'down_over');";
		$aPic['onMouseOut']  = "ChangeImg(this, 'down');";
		$aPic['onMouseDown'] = "ChangeImg(this, 'down_click');";
		$aPic['onMouseUp']   = "ChangeImg(this, 'down_over');";
		$aPic['onClick']     = "MoveDown(document.".$strFormName.".elements('".$strName.($bSingle?"":"[]")."'));";
		$strOrderColName .= $oView->myImg($strUrlImg.'down.gif', $aPic) . '<br />';
		
		$aPic['onMouseOver'] = "ChangeImg(this, 'bottom_over');";
		$aPic['onMouseOut']  = "ChangeImg(this, 'bottom');";
		$aPic['onMouseDown'] = "ChangeImg(this, 'bottom_click');";
		$aPic['onMouseUp']   = "ChangeImg(this, 'bottom_over');";
		$aPic['onClick']     = "MoveBottom(document.".$strFormName.".elements('".$strName.($bSingle?"":"[]")."'));";
		$strOrderColName .= $oView->myImg($strUrlImg.'bottom.gif', $aPic) . '<br />';
		return '<td>' . $strOrderColName . '</td>';
	}
	
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {	
		$aProperties = $oElement->getProperties();
		$aValueDst		= $this->getSelectedValue($oElement, $oElement->getValue());
		$aValueSrc	  	= $oElement->getMultiOptions();		
		$strName	   	= $oElement->getFullyQualifiedName();
		$aAttribSelect  = $oElement->getAttribs();
		
		$bDeleteOnAdd = ($aProperties['bDeleteOnAdd']) ? "true" : "false";
		$strBoolColName = ($aProperties['strOrderColName'] != "") ? ", true" : "" ;
		
		//debug ($aValueDst);
		
		//attributs des selects
		$aAttribSelect['multiple'] = 'multiple';
		$aAttribSelect['style'] = 'width:'.$aAttribSelect['width'].'px;';
		//attributs des liens/images
		$aAttribsPic['width']  = 7;
		$aAttribsPic['border'] = 0;
		$aAttribsPic['height'] = 12;
		
		$strUrlImg = '/library/Pelican/Form/public/images/';
		
		//Création du hidden
		Pelican_Form::addHidden($strName.'_last_selected');		
		
		//Création de la recherche
		if ($aProperties['bSearchEnabled']) {
			$strSearch = $this->Search($oElement, $strName, $oView, $aAttribSelect['width'], $aProperties);
		}
		unset($aAttribSelect['width']);
		
		//Création de OrderColName
		if ($aProperties['strOrderColName']) {
			$strOrderColName = $this->OrderName($strName, $oView, $strUrlImg, $aProperties['bSingle'], $aProperties['strFormName']);
		}
		
		//Popup de mannagement
		$strMannage = '';
		if ($aProperties['bEnableManagement']) {
			$aAttribsLinks['onclick'] = 'addRef("'.$aProperties['sLibPath'].$aProperties['sLibForm'].'/", "document.'.$aProperties['strFormName'].'", "'.$strName.'", "'.$aProperties['strTableName'].'", "'.(($aProperties['bDeleteOnAdd']) ? "1" : "0").'");';
			$strMannage = '<td>' . $oView->formLink(t('FORM_BUTTON_ADD_VALUE'), 'javascript://', $aAttribsLinks) . '</td>';
		}
		
		//mise en place des valeurs selectionnées
		if ($aValueDst) {			
			if (is_array($aValueDst)) {
				if ($aProperties['bSearchEnabled']) { 
					foreach ($aValueDst as $strKey => $strValue) {
						$aOptionDst[$strKey] = $strValue;
					}
				} else {
					foreach ($aValueDst as $strKey => $strValue) {
						if ($aValueSrc[$strValue]) {
							$aOptionDst[$strValue] = $aValueSrc[$strValue];
						}
					}					
				}
			}
		}
		
		//mise en place des valeurs sources
		if (!$aProperties['bSearchEnabled'] || ($aProperties['bSearchEnabled'] && $aProperties['showAll'] && $aValueSrc)) {
			if (is_array($aValueSrc)) {
				reset($aValueSrc);
				foreach ($aValueSrc as $strKey => $strValue) {
					if (!$aProperties['bDeleteOnAdd'] || !in_array($strKey, $aValueDst)) {
						$aOptionSrc[((substr($strKey, 0, 7) == "delete_" ? "" : $strKey))] = $strValue;
					}
				}
			}
		}
		
		//Création du Select de Destination
		$aAttribSelect['ondblclick'] = "assocDel(this, ".$bDeleteOnAdd.");";
		$strSelectDst  = $oView->selectAssoc($strName."[]", $aAttribSelect, $aOptionDst);
		
		//Création des Links/Fleches
		//Gauche dst <= src
		$aAttribsLinks['onclick'] = "assocAdd".($aProperties['bSingle']?"Single":"")."(document.".$aProperties['strFormName'].".src".$strName.", ".$bDeleteOnAdd."".$strBoolColName.");";		
		$strImgLeft	   = $oView->myImg($strUrlImg.'left.gif', $aAttribsPic);
		$strLinkLeft   = $oView->formLink($strImgLeft, 'javascript://', $aAttribsLinks);
		
		//Droite dst => src 
		$aAttribsLinks['onclick'] = "assocDel(document.".$aProperties['strFormName'].".elements['".$strName."[]'], ".$bDeleteOnAdd."".$strBoolColName.");";
		$strImgRight   = $oView->myImg($strUrlImg.'right.gif', $aAttribsPic);
		$strLinkRight  = $oView->formLink($strImgRight, 'javascript://', $aAttribsLinks);
				
		//modification de la taille pour le SearchEnabled
		$aAttribSelect['size'] = ($aProperties['bSearchEnabled']) ? $aAttribSelect['size'] - 1 : $aAttribSelect['size'];
		//Création du Select Source
		$aAttribSelect['ondblclick'] = "assocAdd".($aProperties['bSingle']?"Single":"")."(this, ".$bDeleteOnAdd."".$strBoolColName.");";
		$strSelectSrc  = $oView->selectAssoc("src".$strName, $aAttribSelect, $aOptionSrc);
		
		//Création de l'Element
		$strTag = "<table class='".$aProperties['sStyleVal']."' style='width: 430px;' summary='Associative'";
		$strTag .= "cellspacing='0' cellpading='0' border='0' >";
		$strTag .= "<tr><td class='".$aProperties['sStyleVal']."'><i>".t('FORM_MSG_LIST_SELECTED')."</i></td>"; 
		$strTag .= "<td class='".$aProperties['sStyleVal']."'>&nbsp;</td><td class='".$aProperties['sStyleVal']."'><i>".t('FORM_MSG_LIST_AVAILABLE')."</i></td></tr>";
		$strTag .= "<tr><td class='".$aProperties['sStyleVal']."'>" . $strSelectDst . "</td>";
		if ($aProperties['strOrderColName'])
			$strTag .= $strOrderColName;
		$strTag .= "<td valign='middle' style='width:15px;' align='center'>".$strLinkLeft.
				  "<br />".$strLinkRight."</td><td class='".$aProperties['sStyleVal']."'>";
		if ($aProperties['bSearchEnabled'])
			$strTag .= $strSearch;
		$strTag .= $strSelectSrc . "</td>".$strMannage."</tr></table>";		
		return $strTag;
	}
}
?>