<?php

namespace Citroen;

use Citroen\Service\SimulFin;

/**
 * Class Accessoires gérant les appels vers WS SimulFin.
 *
 * @author Khadidja Messaoudi <khadidja.messaoudi@businessdecision.com>
 */
class Financement
{
    /**
     * Appel WS SimulFin : SaveCalculationDisplay.
     *
     * @param string $sPays            : Code pays
     * @param string $sLanguage        : Code langue pays
     * @param string $sDevise          : Devise du pays
     * @param string $sLCVD6           : identifiant vehicule
     * @param string $sLabelVehicule   : Label vehicule
     * @param string $sDescVehicule    : Description du véhicule
     * @param string $sGammeVehicule   : Gamme vehicule
     * @param string $sPrixHTVehicule  : Prix vehicule HT
     * @param string $sPrixTTCVehicule : Prix vehicule TTC
     *
     * @return array $aSimulFinancement
     */
    public static function saveCalculationDisplay($sPays, $sLanguage, $sDevise, $sLCVD6, $sLabelVehicule, $sDescVehicule = '', $sGammeVehicule, $sPrixHTVehicule, $sPrixTTCVehicule)
    {
        $serviceParams = array(
            'country' => $sPays,
            'language' => $sLanguage,
            'financingMake' => 'AC',
            'currency' => $sDevise,
            'flowDate' => gmdate("Y-m-d\TH:i:s.uP"),
        );
        try {
            $service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_SIMULFIN', array());
            $idSession = $service->call('openSession', $serviceParams);
            if ($idSession) {
                $serviceParams = array(
                    'idSession' => $idSession,
                    'country' => $sPays,
                    'language' => $sLanguage,
                    'financingMake' => 'AC',
                    'currency' => $sDevise,
                    'flowDate' => gmdate("Y-m-d\TH:i:s.uP"),
                    'vehicleBrandCode' => 'CITROEN',
                    'vehicleBrandLabel' => 'CITROEN',
                    'vehicleType' => 'VN',
                    'vehicleIdentification' => $sLCVD6,
                    'vehicleModel' => $sLabelVehicule,
                    'vehicleDescription' => $sDescVehicule, //On ne sait pas où il faut la récupérer
                    'vehicleCategory' => $sGammeVehicule,
                    'vehicleEngine' => '', //On ne sait pas où il faut la récupérer
                    'vehiclePriceHT' => $sPrixHTVehicule,
                    'vehiclePriceTTC' => $sPrixTTCVehicule,
                    'clientType' => 'PART',
                    'financingSpecialFlag' => '0',
                );
                $service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_SIMULFIN', array());
                $aResponse = $service->call('saveCalculationDisplay', $serviceParams);

                return $aResponse;
            }
        } catch (\Exception $e) {
            // TODO à passer dans les logs
            //echo $e->getMessage();
        }
    }

    /**
     * Récupération du prix à crédit.
     *
     * @param string $sPays            : Code pays
     * @param string $sLanguage        : Code langue pays
     * @param string $sDevise          : Devise du pays
     * @param string $sLCVD6           : identifiant vehicule
     * @param string $sLabelVehicule   : Label vehicule
     * @param string $sDescVehicule    : Description du véhicule
     * @param string $sGammeVehicule   : Gamme vehicule
     * @param string $sPrixHTVehicule  : Prix vehicule HT
     * @param string $sPrixTTCVehicule : Prix vehicule TTC
     *
     * @return array $aCreditPrice
     */
    public static function getCreditPrice($sPays, $sLanguage, $sDevise, $sLCVD6, $sLabelVehicule, $sDescVehicule = '', $sGammeVehicule, $sPrixHTVehicule, $sPrixTTCVehicule)
    {
        $aCreditPrice = array();
        $aReturn = \Pelican_Cache::fetch("Citroen/FinancementSaveCalculationDisplay", array(
                $sPays,
                $sLanguage,
                $sDevise,
                $sLCVD6,
                $sLabelVehicule,
                $sDescVehicule,
                $sGammeVehicule,
                $sPrixHTVehicule,
                $sPrixTTCVehicule,
        ));
        if ($aReturn['APD'] && ($sPrixHTVehicule || $sPrixTTCVehicule)) {
            $aReturn['APD'] = str_replace("<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">", "", $aReturn['APD']);
            $aReturn['APD'] = preg_replace("/(<!DOCTYPE.*>)/U", '', $aReturn['APD']);
            libxml_use_internal_errors(true);
            $xmlAPD = simplexml_load_string("<document>".$aReturn['APD']."</document>");
            if ($xmlAPD) {
                $aCreditPrice['PRIX'] = (string) $xmlAPD->DisplayValue;
                $aCreditPrice['LEGAL_TEXT'] = str_replace(array('<text>', '</text>'), array('', ''), $aReturn['APDLegalText']);
                $aCreditPrice['LEGAL_TEXT_LAGARDE'] = str_replace(array('<text>', '</text>'), array('', ''), $aReturn['APDLegalTextLagarde']);
            }
        }

        return $aCreditPrice;
    }

    /**
     * Récupération du prix à crédit avec mentions légales.
     *
     * @param string $sPays            : Code pays
     * @param string $sLanguage        : Code langue pays
     * @param string $sDevise          : Devise du pays
     * @param string $sLCVD6           : identifiant vehicule
     * @param string $sLabelVehicule   : Label vehicule
     * @param string $sDescVehicule    : Description du véhicule
     * @param string $sGammeVehicule   : Gamme vehicule
     * @param string $sPrixHTVehicule  : Prix vehicule HT
     * @param string $sPrixTTCVehicule : Prix vehicule TTC
     *
     * @return array $aCreditPriceML
     */
    public static function getCreditPriceML($sPays, $sLanguage, $sDevise, $sLCVD6, $sLabelVehicule, $sDescVehicule = '', $sGammeVehicule, $sPrixHTVehicule, $sPrixTTCVehicule)
    {
        /* Initialisation des variables */
        $aCreditPriceML = array();
        $sHtml = '';
        /* Balises à réencoder pour ne pas quelles soient interprétées comme
         * des balises XML
         */
        $aHtmlEncode[] = 'span';
        $aHtmlEncode[] = 'font';
        $aHtmlEncode[] = 'a';
        /* Caractère de remplacement du < */
        $sReplaceOpen = '##ENCODE_OPEN##';

        /* Configuration de la sortie HTML */
        $aHTMLlegalMention['NOID']['START'] = '<p>';
        $aHTMLlegalMention['NOID']['END'] = '</p>';
        $aHTMLlegalMention['ID']['START'] = '<p><small>';
        $aHTMLlegalMention['ID']['END'] = '</small></p>';
        $aHTMLlegalMention['LEGAL']['START'] = '<div class="scroll"><p><small>';
        $aHTMLlegalMention['LEGAL']['END'] = '</small></p></div>';

        $aReturn = \Pelican_Cache::fetch("Citroen/FinancementSaveCalculationDisplay", array(
                $sPays,
                $sLanguage,
                $sDevise,
                $sLCVD6,
                $sLabelVehicule,
                $sDescVehicule,
                $sGammeVehicule,
                $sPrixHTVehicule,
                $sPrixTTCVehicule,
        ));
        if ($aReturn['DTL'] && ($sPrixHTVehicule || $sPrixTTCVehicule)) {
            $sDTL = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace(array('<![CDATA[', ']]'), array('', ''), $aReturn['DTL']));

            /* Balises à réencoder pour ne pas quelles soient interprétées comme
             * des balises XML
             */
            $sHtmlEncode = implode('|', $aHtmlEncode);
            $sDTL = preg_replace("/<(\/)?({$sHtmlEncode})/i", "{$sReplaceOpen}$1$2", $sDTL);
            /* Creation du tableau XML */
            $xmlDTL = simplexml_load_string("<document>".$sDTL."</document>");

            $aCreditPriceML['TITLE'] = (string) $xmlDTL->title;
            $sHtml = $aHTMLlegalMention['NOID']['START'].$aCreditPriceML['TITLE'].$aHTMLlegalMention['NOID']['END'];

            /* Parcours des noeuds pour le remplissage du tableau et de la variable
             * HTML pour l'affichage des données Back et Front
             */

            for ($i = 0; $i < count($xmlDTL->variable); $i++) {
                /* La suppression des espaces est obligatoire car quand une balise
                 * <id> est vide, il reste des espaces
                 */
                $id = (string) trim($xmlDTL->variable[$i]->id);
                /* Pour que le Tableau PHP soit correct on ajoute quand même une clé */
                if (empty($id)) {
                    $id = 'NOKEY_'.$i;
                }

                if (isset($xmlDTL->variable[$i]->label->i)) {
                    $label = (string) str_replace($sReplaceOpen, '<', $xmlDTL->variable[$i]->label->i);
                } else {
                    $label = (string) str_replace($sReplaceOpen, '<', $xmlDTL->variable[$i]->label);
                }

                $value = (string) str_replace($sReplaceOpen, '<', $xmlDTL->variable[$i]->value);
                $unit = (string) $xmlDTL->variable[$i]->unit;
                $DisplayValue = (string) str_replace($sReplaceOpen, '<', $xmlDTL->variable[$i]->DisplayValue);

                if ($label != '') {
                    $aCreditPriceML['VARIABLES'][$id]['LABEL'] = $label;
                }
                if ($value != '') {
                    $aCreditPriceML['VARIABLES'][$id]['VALUE'] = $value;
                }
                if ($unit != '') {
                    $aCreditPriceML['VARIABLES'][$id]['UNIT'] = $unit;
                }
                if ($DisplayValue != '') {
                    $aCreditPriceML['VARIABLES'][$id]['DISPLAY_VALUE'] = $DisplayValue;
                }

                /* Gestion de la sortie HTML */
                /* Gestion de la partie basse Texte Legal */
                if ($id === 'LegalText') {
                    $sHtml .= $aHTMLlegalMention['LEGAL']['START'].$label.$DisplayValue.$aHTMLlegalMention['LEGAL']['END'];
                    /* Affichage de la partie avec identifiant */
                } elseif (!empty($xmlDTL->variable[$i]->id)) {
                    $sHtml .= $aHTMLlegalMention['ID']['START'].$label.$DisplayValue.$aHTMLlegalMention['ID']['END'];
                    /* Affichage de la partie sans identifiant */
                } else {
                    $sHtml .= $aHTMLlegalMention['NOID']['START'].$label.$DisplayValue.$aHTMLlegalMention['NOID']['END'];
                }
            }
            $aCreditPriceML['HTML'] = $sHtml;
        }

        return $aCreditPriceML;
    }
}
