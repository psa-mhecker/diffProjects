<?php

use Citroen\Perso\Profile;

/**
 * Classe permettant de gérer le Pelican_Cache d'objets php.
 *
 * @author Khadidja MESSAOUDI  <khadidja.messaoudi@businessdecision.com>
 *
 * @since 18/12/2013
 */
class Citroen_Cache extends Pelican_Cache
{
    public static function fetchProfiling()
    {
        $args = func_get_args();

        $perso = array_shift($args);

        self::$perso = '';
        /* PERSO */
        $flagUser = (!empty($_SESSION[APP]['FLAGS_USER'])) ? $_SESSION[APP]['FLAGS_USER'] : array();
        $profileUser = (!empty($_SESSION[APP]['PROFILES_USER'])) ? $_SESSION[APP]['PROFILES_USER'] : array();
        $products = parent::fetch("Frontend/Citroen/Perso/Lcdv6ByProduct", array(
                    $_SESSION[APP]['SITE_ID'],
        ));
        $arr    = \Citroen_View_Helper_Global::arrHtmlDecode(\Citroen_View_Helper_Global::objectsIntoArray(json_decode($perso)));

        // On configure les priorités des indicateurs et des profils pour la perso
        $arr    =   Citroen_Cache::setPrioriteIndicateursAndProfils($arr);

        if (is_array($arr) && count($arr) > 0) {
            foreach ($arr as $elmt) {
                // Vérification si la tranche est publié.
                if (Citroen_Cache::isPubliePerso($elmt)) {
                    $sProductsId =  $elmt['PRODUCT_ID'];
                    if (is_array($elmt['PRODUCT_ID']) && count($elmt['PRODUCT_ID']) >1) {
                        $sProductsId =  implode(":", $elmt['PRODUCT_ID']);
                    } elseif (is_array($elmt['PRODUCT_ID'])) {
                        $sProductsId =  $elmt['PRODUCT_ID'][0];
                    }
                    $sKey = ($elmt['INDICATEUR_ID'] && $elmt['PRODUCT_ID']) ? $elmt['PROFILE_ID'].'_'.$elmt['INDICATEUR_ID'].'_'.$sProductsId : $elmt['PROFILE_ID'];
                    if (!isset($profiles[$sKey])) {
                        $profiles[$sKey] = $elmt;
                    }
                }
            }
        }

        $profileMatch = false;
        $profilMatching = array();
        $profilId = '';
        if (is_array($profiles) && count($profiles) > 0) {
            foreach ($profiles as $key => $profile) {
                $explodeKey = array();
                $field = '';
                if (strpos($key, '_') !== false) {
                    $explodeKey = explode('_', $key);
                    switch ($explodeKey[1]) {
                        case 13 :
                            $field = $flagUser['preferred_product'];
                            break;
                        case 7 :
                            $field = $flagUser['product_owned'];
                            break;
                        case 11 :
                            $field = $flagUser['current_product'];
                            break;
                        case 12 :
                            $field = $flagUser['product_best_score'];
                            break;
                        case 14 :
                            $field = $flagUser['recent_product'];
                            break;
                    }
                }

                $aProducts =  explode(":", $explodeKey[2]);
                if (
                        in_array($key, $profileUser, true) ||
                        (
                        !empty($explodeKey) &&
                        in_array($explodeKey[0], $profileUser) &&
                        in_array($field, $aProducts, true)
                        )
                ) {
                    $profileMatch = true;
                    $profilMatching = $profile;
                    $profilId = $key;
                    break;
                }
            }
        }

        $params = $args[1];

        if ($profilMatching && $profileMatch) {
            // zones sans filtre web/mobile
            if (in_array($profilMatching['MULTI_NAME'], Pelican::$config['PERSO_SANS_WB'])) {
                $params[] = $profilId;
                self::$perso = $profilMatching;
            } else {
                if (Pelican_Controller::isMobile()) {
                    if (isset($profilMatching['ZONE_MOBILE']) && $profilMatching['ZONE_MOBILE'] == 1) {
                        $params[] = $profilId;
                        self::$perso = $profilMatching;
                    }
                } else {
                    if (isset($profilMatching['ZONE_WEB']) && $profilMatching['ZONE_WEB'] == 1) {
                        $params[] = $profilId;
                        self::$perso = $profilMatching;
                    }
                }
            }
        }
        $varValues = call_user_func(array('parent', 'fetch'), $args[0], $params);

        return $varValues;
    }

    public static function isPubliePerso($elmt)
    {
        if (isset($elmt['PROFIL_DATE_DEB']) && !empty($elmt['PROFIL_DATE_DEB']) && isset($elmt['PROFIL_DATE_FIN']) && !empty($elmt['PROFIL_DATE_FIN'])) {
            $oDateDuJour = new DateTime();
            $sDateDuJour = $oDateDuJour->format('Ymd');

            $sDateDeb = str_replace("/", "-", $elmt['PROFIL_DATE_DEB']);
            $oDateDeb = new DateTime($sDateDeb);
            $sDateDeb = $oDateDeb->format('Ymd');

            $sDateFin = str_replace("/", "-", $elmt['PROFIL_DATE_FIN']);
            $oDateFin = new DateTime($sDateFin);
            $sDateFin = $oDateFin->format('Ymd');

            if ($sDateDeb <= $sDateDuJour && $sDateFin >= $sDateDuJour) {
                return true;
            }
        }
        if (isset($elmt['PROFIL_DATE_DEB']) && !empty($elmt['PROFIL_DATE_DEB']) && empty($elmt['PROFIL_DATE_FIN'])) {
            $oDateDuJour = new DateTime();
            $sDateDuJour = $oDateDuJour->format('Ymd');

            $sDateDeb = str_replace("/", "-", $elmt['PROFIL_DATE_DEB']);
            $oDateDeb = new DateTime($sDateDeb);
            $sDateDeb = $oDateDeb->format('Ymd');

            if ($sDateDeb <= $sDateDuJour) {
                return true;
            }
        }
        if (!isset($elmt['PROFIL_DATE_DEB']) && !isset($elmt['PROFIL_DATE_FIN'])) {
            return true;
        }

        return false;
    }

    public static function setPrioriteIndicateursAndProfils($aDataPerso)
    {
        $aProfilsPriorite       = parent::fetch("Frontend/Citroen/Perso/Profils");
        $aIndicateursPriorite   = parent::fetch("Frontend/Citroen/Perso/Indicateurs");

        // récupération des priorités de surcharge des profils
        $aProfilsPrioriteSurchargeByProfilId =   array();
        foreach ($aProfilsPriorite as $key => $aProfilPriorite) {
            $aProfilsPrioriteSurchargeByProfilId[$aProfilPriorite['PROFILE_ID']] = $aProfilPriorite;
        }

        // récupération des priorités de surcharge des indicateurs
        $aIndicateursPrioriteSurchargeByIndicateurId =   array();
        foreach ($aIndicateursPriorite as $key => $aIndicateurPriorite) {
            $aIndicateursPrioriteSurchargeByIndicateurId[$aIndicateurPriorite['INDICATEUR_ID']] = $aIndicateurPriorite;
        }

        if (is_array($aDataPerso) && count($aDataPerso) > 0) {
            foreach ($aDataPerso as $key => $elmt) {
                if (!empty($elmt['PROFILE_ID'])) {
                    // Gestion des priorités de base
                    $prioriteProfil   =   2;
                    if (!empty($elmt['PROFILE_ID'])) {
                        $prioriteProfil   =   1;
                    }
                    $prioriteIndicateur   =   2;
                    if (!empty($elmt['INDICATEUR_ID'])) {
                        $prioriteIndicateur   =   1;
                    }
                    $prioriteProduit   =   2;
                    if (!empty($elmt['PRODUCT_ID'])) {
                        $prioriteProduit   =   1;
                    }
                    // gestion des priorités de surcharge si on retrouve plusieurs profils avec les memes priorités de base.
                    // Si pas de priorité de surcharge on set une priorité par défaut à 1000
                    if (empty($aProfilsPrioriteSurchargeByProfilId[$elmt['PROFILE_ID']]['PRIORITE'])) {
                        $profilSurchargePriotite =   1000;
                    } else {
                        $profilSurchargePriotite =   $aProfilsPrioriteSurchargeByProfilId[$elmt['PROFILE_ID']]['PRIORITE'];
                    }
                    if (!empty($elmt['INDICATEUR_ID'])) {
                        if (empty($aIndicateursPrioriteSurchargeByIndicateurId[$elmt['INDICATEUR_ID']]['PRIORITE'])) {
                            $indicateurSurchargePriotite =   1000;
                        } else {
                            $indicateurSurchargePriotite =   $aIndicateursPrioriteSurchargeByIndicateurId[$elmt['INDICATEUR_ID']]['PRIORITE'];
                        }
                    }
                    // Priorite de base
                    $priorite   =   $prioriteProfil.$prioriteIndicateur.$prioriteProduit;

                    // Concatenation priorité de base + priorite de surcharge
                    $aDataPerso[$key]['PRIORITE']  =   $priorite.$profilSurchargePriotite + $indicateurSurchargePriotite;
                }
            }
        }
        //On trie selon l'ordre des priorites
        uasort($aDataPerso, 'cmp');

        return $aDataPerso;
    }
}
function cmp($aDataPerso1, $aDataPerso2)
{
    if ($aDataPerso1['PRIORITE'] == $aDataPerso2['PRIORITE']) {
        return 0;
    }

    return ($aDataPerso1['PRIORITE'] < $aDataPerso2['PRIORITE']) ? -1 : 1;
}
