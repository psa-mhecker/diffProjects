<?php
/**
 * Fichier de Pelican_Cache : Retour WS AOA.
 */
use Citroen\Accessoires;

class Frontend_Citroen_Accessoires_Accessories extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet � mettre en Pelican_Cache
     */
    public function getValue()
    {
        $sLanguageCode = $this->params[0];
        $sousUniversCode = $this->params[1];
        $sModelCode = $this->params[2];
        $sBodyStyleCode = $this->params[3];
        $clientId = $this->params[4];
        //$clientId = 'CSA01';
        $aResults = Accessoires::getAccessories($sLanguageCode, $sousUniversCode, $sModelCode, $sBodyStyleCode, $clientId);
        $aAccessories = array();
        $aTemp = array();
        $typeImage = Pelican::$config['BOUTIQUE_ACC'][$clientId]['IMAGE']; //__JFO optimisation

        if ($aResults['designation'] != '') {
            $aResults = array($aResults);
        }

        if (is_array($aResults) && count($aResults)>0) {
            foreach ($aResults as $key => $res) {
                $sFile = '';
                $iPrice = '';
                if ($key !== 'locale') {
                    if (is_array($res['files']) && count($res['files'])>0) {
                        foreach ($res['files'] as $file) {
                            if ((Pelican::$config['TYPE_ENVIRONNEMENT'] == "recette_projet" || Pelican::$config['TYPE_ENVIRONNEMENT'] == "dev" || Pelican::$config['TYPE_ENVIRONNEMENT'] == "preprod" || Pelican::$config['TYPE_ENVIRONNEMENT'] == "recette") && $sFile == '') {
                                $sFile = $file['relativePath'];
                            } else {
                                if ($file['type'] == $typeImage) {
                                    $sFile = str_replace('http://aoaccessoire.inetpsa.com/aoa00Pds/servlet', '/extimage.php?service=aoa&image=', $file['relativePath']);

                                    break;
                                }
                            }
                        }
                    }
                    if (is_array($res['pricing']) && count($res['pricing'])>0) {
                        foreach ($res['pricing'] as $price) {
                            if ($price['brand'] == "AC") {
                                $iPrice = number_format($price['pvpTTCWP'], 2, '.', ' ');
                                break;
                            }
                        }
                    }

                    $subUniverse = $res['universe']['subUniverses']['subUniverse']['code'];
                    $aTemp[$subUniverse][] = array(
                        'LABEL'    => $res['designation'],
                        'REF'    => $res['reference'],
                        'PRIX'    => $iPrice,
                        'IMAGE'    => $sFile,
                    );
                }
            }
        }
        foreach ($aTemp as $key => $tmp) {
            $aAccessories['COUNT'][$key] = count($tmp);
        }
        $aAccessories['CONTENTS'] = $aTemp;

        $this->value = $aAccessories;
    }
}
