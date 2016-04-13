<?php
/** Class Decorator de l'Element TabCroiseGenerique
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 17/06/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_TabCroiseGenerique extends Pelican_Form_Decorator_Abstract {	
	
	 //Variable pour sql
    protected $_sTableSuffixeId = '_id';

    protected $_sTableSuffixeLabel = '_label';

    protected $_sTablePrefix = 'pel_';
	
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {
		$strName 	 = $oElement->getFullyQualifiedName();
		$aProperties = $oElement->getProperties();
		$oConnection = $oElement->getConnection();
		
		$strTag = '';
		$iColSpan = 1;
		
		if ($aProperties['bReadOnly']) {
            $aProperties['bHelpButtons'] = false;
            if ($strQueryData) {
                $oConnection->Query($strQueryData);
                $aFiltre = $oConnection->data["id_row"];
            }
            if (! is_array($aFiltre))
                $aFiltre = array();
        }
		
		if ($aProperties['bHelpButtons'] == true) {
			$aAttribs['width'] = '20';
			$aAttribs['height'] = '21';
			$aAttribs['align'] = 'absmiddle';
			$aAttribs['onmouseover'] = 'ChangeImg(this, "check_all_over");';
			$aAttribs['onmouseout'] = 'ChangeImg(this, "check_all");';
			$aAttribs['onMouseDown'] = 'ChangeImg(this, "check_all_click");';
			$aAttribs['onclick'] = 'GlobalCheck(document.' . $aProperties['strFormName'] . ', "' . $strName . '", "%%COL%%", "%%ROW%%", "check", "' . ($aProperties['bRadio'] ? "true" : "false") . '");';
			$strBoutons = $oView->myImg('/library/Pelican/Form/public/images/check_all.gif', $aAttribs);
			unset($aAttribs);
			if (!$aProperties['bRadio']) {
				$strBoutons .= '&nbsp;' . str_replace("check", "uncheck", $strBoutons);
				$iColSpan ++;
			}
		}
		//Abscisses
        if (! is_array($aProperties['strQueryColumn'])) {
            $oConnection->Query($aProperties['strQueryColumn']);
            
            if ($oConnection->data) {
                if ($aProperties['bRadio']) {
                    $aAbscisses["0"] = t('NONE');
                    $iColSpan += 2;
                }
                while (list ($key, $value) = each($oConnection->data["id"])) {
                    $aAbscisses[$value] = $oConnection->data["lib"][$key];
                    $iColSpan += 2;
                }
            } else {
                $aAbscisses = array();
            }
        } else {
            $aAbscisses = $aProperties['strQueryColumn'];
            $iColSpan += count($aAbscisses) * 2;
        }
		//Ordonnées
        $oConnection->Query($aProperties['strQueryRow']);
        if ($oConnection->data) {
            while (list ($key, $value) = each($oConnection->data["id"])) {
                if (!$aProperties['bReadOnly'] || ($aProperties['bReadOnly'] && in_array($value, $aFiltre))) {
                    $aOrdonnees[$value] = $oConnection->data["lib"][$key];
                }
            }
        } else {
            $aOrdonnees = array();
        }
		//Filtre
		if (($aProperties['strFilterColumn'] != "") && ($aProperties['iFilterID'] != "")) {
            $oConnection->Query("select " . str_replace($this->_sTableSuffixeId, $this->_sTableSuffixeLabel, $aProperties['strFilterColumn']) . " as 'lib' from " . $this->_sTablePrefix . str_replace($this->_sTableSuffixeId, "", $aProperties['strFilterColumn']) . " where " . $aProperties['strFilterColumn'] . " = " . $aProperties['iFilterID']);
            $strFiltre = $oConnection->data["lib"][0];
            Pelican_Form::addHidden($strName . "_Filter", $aProperties['iFilterID']);
            Pelican_Form::addHidden($strName . "_FilterC", $aProperties['strFilterColumn']);
        }
		Pelican_Form::addHidden($strName . $this->_sTableSuffixeId, $iID);
        Pelican_Form::addHidden($strName . $this->_sTableSuffixeId . "C", $strIDColumn);
        Pelican_Form::addHidden($strName . "_is_radio", ($aProperties['bRadio'] ? "1" : ""));
		if (! is_array($aProperties['strQueryData'])) {
            if (strlen($aProperties['strQueryData']) != 0) {
                // Lecture des données
                $strSQL = $aProperties['strQueryData'];
                if (($aProperties['strFilterColumn'] != "") && ($aProperties['iFilterID'] != ""))
                    $strSQL .= " AND " . $aProperties['strFilterColumn'] . " = " . $aProperties['iFilterID'];
                $oConnection->Query($strSQL);
                $aData = array();
                if ($oConnection->rows != 0) {
                    while (list ($key, $value) = each($oConnection->data["id_row"])) {
                        $aData[$value][$oConnection->data["id_col"][$key]] = 1;
                    }
                }
            } else {
                $aData = array();
            }
        } else {
            $aData = $aProperties['strQueryData'];
        }
        $columnWidth = (1 / (count($aAbscisses) + 2)) * 100;
		
		$strTag .= "<table cellpadding='0' cellspacing='0' border='1' width='100%' class='tableaucroise'>";
        // En-tête
        $strTag .= "<tr><td align='center' class='croiselib'";
        if ($aProperties['bHelpButtons']) {
            if (! $aProperties['bRadio']) {
                $strTag .= " colspan='2'";
            }
            $strTag .= " rowspan='2'";
        }
        $strTag .= " >" . Pelican_Html::nbsp();
        $strTag .= $strFiltre;
		if ($aProperties['bHelpButtons'] && ! $aProperties['bRadio'])
            $strTag .= strtr($strBoutons, array("%%COL%%" => "" , "%%ROW%%" => ""));
        $strTag .= Pelican_Html::nbsp() . "</td>";
        if ($aProperties['bHelpButtons']) {
            $strTmp2 = "<tr>";
        }
        foreach ($aAbscisses as $key => $value) {
            $strTag .= "<td align='center' class='croiselib'>" . $value . "</td>";
            $strTag .= "<td style='width:1px'></td>";
            if ($aProperties['bHelpButtons'])
                $strTmp2 .= "<td align='center' class='croiselib'>" . strtr($strBoutons, array("%%COL%%" => $key , "%%ROW%%" => "")) . "</td>";
            $strTmp2 .= "<td style='width:1px'></td>";
        }
		
		// Affichage des cases
        if ($aProperties['bHelpButtons']) {
            $strTag .= $strTmp2 . "</tr>\n";
        }
        $strTag .= "</tr>\n";
        
        $strTag .= "<tr><td colspan='" . $iColSpan . "' style='height:1px;'></td></tr>\n";
        if ($aOrdonnees) {
            foreach ($aOrdonnees as $iY => $strLibY) {
                $strTag .= "<tr>";
                $strTag .= "<td  class='croiselib'>" . $strLibY . "</td>";
                if ($aProperties['bHelpButtons'] && ! $aProperties['bRadio'])
                    $strTag .= "<td align='center'  class='croiselib'>" . strtr($strBoutons, array("%%COL%%" => "" , "%%ROW%%" => $iY)) . "</td>";
                reset($aAbscisses);

                //$this->countInputName($strName . "_Y" . $iY);
                if (! $aData[$iY] && $aProperties['bRadio']) {
                    $aData[$iY][0] = 1;
                }
                while (list ($iX, $strLibX) = each($aAbscisses)) {
                    $strTag .= "<td align='center'  class='croiseval'>";
                    $checked = false;
                    if (isset($aData[$iY][$iX])) {
                        if ($aData[$iY][$iX] === 1) {
                            $checked = true;
                        }
                    }
                    if ($aProperties['bReadOnly']) {
                        $strTag .= ($checked ? "X" : "");
                    } else {
                        if ($aProperties['bRadio']) {
                            $strTag .= "<input type='Radio' name='" . $strName . "_Y" . $iY . "' value='" . $iX . "'" . ($checked ? " checked='checked'" : "") . " />";
                        } else {
                            $strTag .= "<input type='Checkbox' name='" . $strName . "_Y" . $iY . "_X" . $iX . "' id='" . $strName . "_Y" . $iY . "_X" . $iX . "' value='1'" . ($checked ? " checked='checked'" : "") . " />";
                        }
                    }
                    $strTag .= "</td>";
                    $strTag .= "<td style='width:1px;'></td>";
                }
                $strTag .= "</tr>\n";
                $strTag .= "<tr><td colspan='" . $iColSpan . "' style='height:1px;'></td></tr>\n";
            }
        }
        $strTag .= "</table>\n";
		return $strTag;
	}
}
?>