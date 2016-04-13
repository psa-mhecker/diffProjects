<?php
use Citroen\Accessoires;
use Citroen\GammeFinition\VehiculeGamme;

class Layout_Citroen_Accessoires_Controller extends Pelican_Controller_Front
{

    public function indexAction ()
    {
        $aData = $this->getParams();
        
        $bTrancheVisible = true;
        // Dans le gabarit Mon Projet / Mes s�lections, la tranche utilise le v�hicule selectionn� et ne s'affiche que si celui-ci est d�fini.
        if ($aData['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']) {
            if (! isset($_GET['PROFITER'])) {
                $bTrancheVisible = false;
            } else {
                if ($_GET['select_vehicule']) {
                    $aData['ZONE_ATTRIBUT'] = $_GET['select_vehicule'];
                } else {
                    $bTrancheVisible = false;
                }
            }
        }
        if ($bTrancheVisible) {
            Frontoffice_Zone_Helper::setPositionZone($aData['ZONE_ID'], $aData['ZONE_ORDER'], $aData['AREA_ID']);
            /*
             * Page globale
             */
            $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
                $_SESSION[APP]['SITE_ID'],
                $_SESSION[APP]['LANGUE_ID'],
                Pelican::getPreviewVersion(),
                Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
            ));
            /*
             * Configuration
             */
            $aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
                $pageGlobal['PAGE_ID'],
                Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
                $pageGlobal['PAGE_VERSION'],
                $_SESSION[APP]['LANGUE_ID']
            ));
            
            if ($_SESSION[APP]['LANGUE_CODE'] && $_SESSION[APP]['CODE_PAYS']) {
                $sCodePays = ($_SESSION[APP]['CODE_PAYS'] == 'CT') ? 'FR' : $_SESSION[APP]['CODE_PAYS'];
                $sLanguageCode = strtolower($_SESSION[APP]['LANGUE_CODE']) . '_' . $sCodePays;
            } else {
                $sLanguageCode = 'fr_FR';
            }
            
            // $sLanguageCode = empty($_SESSION[APP]['LANGUAGE_CODE']) ? 'fr_FR' : $_SESSION[APP]['LANGUAGE_CODE'];
            $iStart = 0;
            $iCount = 12;
            // LCDV6
            
            $sLCDV6 = VehiculeGamme::getLCDV6($aData['ZONE_ATTRIBUT'], $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']);
            $sModelCode = '';
            $sBodyStyleCode = '';
            if ($sLCDV6 != '') {
                $sModelCode = substr($sLCDV6, 0, 4);
                $sBodyStyleCode = substr($sLCDV6, 4);
            }
            $aUniverses = Pelican_Cache::fetch("Frontend/Citroen/Accessoires/CriteriaValues", array(
                "Universes",
                $sLanguageCode,
                Pelican_Cache::getTimeStep(360)
            ));
            
            $aAccessoires = array();
            if (is_array($aUniverses) && count($aUniverses) > 0) {
                foreach ($aUniverses['universes']['universe'] as $key => $univers) {
                    
                    $aAccessoires[$univers['order']]['UNIVERS'] = $univers['label'];
                    $aAccessoires[$univers['order']]['CODE'] = $univers['code'];
                    
                    if (is_array($univers['subUniverses']['subUniverse']) && count($univers['subUniverses']['subUniverse']) > 0) {
                        
                        $count = 0;
                        
                        foreach ($univers['subUniverses']['subUniverse'] as $i => $sousUnivers) {
                            
                            $aAccessoires[$univers['order']]['SOUS_UNIVERS'][$sousUnivers['order']]['LABEL'] = $sousUnivers['label'];
                            $aAccessoires[$univers['order']]['SOUS_UNIVERS'][$sousUnivers['order']]['CODE'] = $sousUnivers['code'];
                            $aResults = self::_getAccessories($sLanguageCode, $sousUnivers['code'], $sModelCode, $sBodyStyleCode, $iStart, $aConfiguration['ZONE_TITRE13']);
                            
                            //if ($count == 0 && is_array($aResults['CONTENTS']) && ! empty($aResults['CONTENTS'])) {
                            //    $aAccessoires[$univers['order']]['UNIVERS_IMG'] = $aResults['CONTENTS'][0]['IMAGE'];
                            //    $count ++;
                            //}
                            
                            if ($aConfiguration['ZONE_TITRE13'] == 'CSA01' && is_array($aResults['CONTENTS']) && count($aResults['CONTENTS']) > 0) {
                                foreach ($aResults['CONTENTS'] as $j => $result) {
                                    /*
                                     * Url pour acheter
                                     */
                                    $aResults['CONTENTS'][$j]['URL_BUY'] = str_replace(array(
                                        '##LCDV##',
                                        '##ARTICLE##',
                                        '##LANGUAGE##'
                                    ), array(
                                        $sLCDV6,
                                        $result['REF'],
                                        str_replace('_', '-', $sLanguageCode)
                                    ), $aConfiguration['ZONE_URL2']);
									if($aResults['CONTENTS'][$j]['IMAGE'] == '')
									{
									}
                                }
                            }
                            
                            $aAccessoires[$univers['order']]['SOUS_UNIVERS'][$sousUnivers['order']]['ACCESSOIRES'] = $aResults;
                        							ksort( $aAccessoires[$univers['order']]['SOUS_UNIVERS']); // __JFO trier aussi par ordre de sous univers
							
                        } // Parcours sous univers
                    }
					
					// On vient d'ajouter l'univers $aAccessoires[$univers['order']]['UNIVERS']
					// On cherche maintenant le visuel pour illustrer ce ss univ (1ere image non vide du 1er acc)
					$i = '';
					foreach($aAccessoires[$univers['order']]['SOUS_UNIVERS'] as $subu)
					{
if (is_array($subu['ACCESSOIRES']['CONTENTS']) && count($subu['ACCESSOIRES']['CONTENTS']) > 0) {
						foreach($subu['ACCESSOIRES']['CONTENTS'] as $acc)
						{
							$i = $acc['IMAGE'];
							if ($i == '' || strpos($i, '/zoom_CSA01.jpg') !== false)
							{
								$i = '';
								continue;
}
							break;
                    }
}
						if ($i != '') break;
                }
					$aAccessoires[$univers['order']]['UNIVERS_IMG'] = $i;
                } // Parcours univers
                    ksort($aAccessoires);
            }
            
            // Application des visuels univers définis en backoffice
            $visuelUnivers = Pelican_Cache::fetch("Frontend/Citroen/Accessoires/VisuelUnivers", array($_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID']));
            foreach ($aAccessoires as $key => $val) {
                if (isset($visuelUnivers[$val['CODE']]['MEDIA_PATH'])) {
                    $aAccessoires[$key]['UNIVERS_IMG'] = $visuelUnivers[$val['CODE']]['MEDIA_PATH'];
                }
            }
			


           
            /*
             * Position
             */
            $iPosition = Frontoffice_Zone_Helper::getPositionZone($aData['ZONE_ID'], $aData['ZONE_ORDER'], $aData['AREA_ID']);
            $this->assign('aAccessoires', $aAccessoires);
            $this->assign('aConfiguration', $aConfiguration);
            $this->assign('iPosition', $iPosition);
            $this->assign('sUrlBuyWeb', $sUrlBuyWeb);
            $this->assign('sLCDV6', $sLCDV6);
            $this->assign('iCount', $iCount);
        }
        $this->assign('aData', $aData);
        $this->assign('bTrancheVisible', $bTrancheVisible);
        $this->fetch();
    }

    public function moreAccessoriesAction ()
    {
        $aData = $this->getParams();
        $this->assign('aData', $aData);
        
        /*
         * Page globale
         */
        $pageGlobal = Pelican_Cache::fetch("Frontend/Page/Template", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID'],
            Pelican::getPreviewVersion(),
            Pelican::$config['TEMPLATE_PAGE']['GLOBAL']
        ));
        /*
         * Configuration
         */
        $aConfiguration = Pelican_Cache::fetch("Frontend/Page/ZoneTemplate", array(
            $pageGlobal['PAGE_ID'],
            Pelican::$config['ZONE_TEMPLATE_ID']['CONFIGURATION'],
            $pageGlobal['PAGE_VERSION'],
            $_SESSION[APP]['LANGUE_ID']
        ));
        
        if ($_SESSION[APP]['LANGUE_CODE'] && $_SESSION[APP]['CODE_PAYS']) {
            $sCodePays = ($_SESSION[APP]['CODE_PAYS'] == 'CT') ? 'FR' : $_SESSION[APP]['CODE_PAYS'];
            $sLanguageCode = strtolower($_SESSION[APP]['LANGUE_CODE']) . '_' . $sCodePays;
        } else {
            $sLanguageCode = 'fr_FR';
        }
            
        //$sLanguageCode = empty($_SESSION[APP]['LANGUAGE_CODE']) ? 'fr_FR' : $_SESSION[APP]['LANGUAGE_CODE'];
        $iStart = $aData['iStart'];
        $sUniv = $aData['univ'];
        $sSsUniv = $aData['ssUniv'];
        $sLCDV6 = $aData['lcdv6'];
        $iPosition = $aData['iPosition'];
        $sModelCode = substr($sLCDV6, 0, 4);
        $sBodyStyleCode = substr($sLCDV6, 4);
        $aAccessoires = self::_getAccessories($sLanguageCode, $sSsUniv, $sModelCode, $sBodyStyleCode, $iStart, $aConfiguration['ZONE_TITRE13']);
        if ($aConfiguration['ZONE_TITRE13'] == 'CSA01' && is_array($aAccessoires['CONTENTS']) && count($aAccessoires['CONTENTS']) > 0) {
            foreach ($aAccessoires['CONTENTS'] as $j => $result) {
                /*
                 * Url pour acheter
                 */
                $aAccessoires['CONTENTS'][$j]['URL_BUY'] = str_replace(array(
                    '##LCDV##',
                    '##ARTICLE##',
                    '##LANGUAGE##'
                ), array(
                    $sLCDV6,
                    $result['REF'],
                    str_replace('_', '-', $sLanguageCode)
                ), $aConfiguration['ZONE_URL2']);
            }
        }
        $this->assign('aAccessoires', $aAccessoires);
        $this->assign('aConfiguration', $aConfiguration);
        $this->fetch();
        $this->getRequest()->addResponseCommand("append", array(
            'id' => 'allAccessories_' . $iPosition . '_' . $sUniv . '_' . $sSsUniv,
            'attr' => 'innerHTML',
            'value' => $this->getResponse()
        ));
        $iCount = $iStart + 12;
        if (count($aAccessoires['CONTENTS']) < 12 || $aAccessoires['COUNT'] < $iCount) {
            $this->getRequest()->addResponseCommand('script', array(
                'value' => "$('#moreAcc_" . $iPosition . "_" . $sUniv . "_" . $sSsUniv . "').css('display','none');"
            ));
        } else {
            $this->getRequest()->addResponseCommand('script', array(
                'value' => "$('#iCount_" . $sUniv . "_" . $sSsUniv . "_" . $iPosition . "').val(" . $iCount . ");"
            ));
        }
    }

    private static function _getAccessories ($sLanguageCode, $sSousUnivers, $sModelCode, $sBodyStyleCode, $iStart, $clientId)
    {
        $aAccessoires = Pelican_Cache::fetch("Frontend/Citroen/Accessoires/Accessories", array(
            $sLanguageCode,
            false,
            $sModelCode,
            $sBodyStyleCode,
            $clientId,
            Pelican_Cache::getTimeStep(360)
        ));
        
        if (is_array($aAccessoires['CONTENTS'][$sSousUnivers]) && ! empty($aAccessoires['CONTENTS'][$sSousUnivers])) {
            $aAccessoires['CONTENTS'] = array_slice($aAccessoires['CONTENTS'][$sSousUnivers], $iStart, 12);
        } else {
            $aAccessoires['CONTENTS'] = '';
        }
        $aAccessoires['COUNT'] = $aAccessoires['COUNT'][$sSousUnivers];
        return $aAccessoires;
    }
}
