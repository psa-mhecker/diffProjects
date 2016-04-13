<?php
/**
 * Created by PhpStorm.
 * User: kmessaoudi
 * Date: 18/02/14
 * Time: 10:56
 */

include("config.php");

Pelican::$config["GABARIT_DYNAMIQUE"] = array(245, 222, 255, 257, 236, 252, 259, 281, 269, 213);
Pelican::$config["TRANCHE_DYNAMIQUE"] = array(
    671, /* Texte Riche */
    612, /* 1 colonne */
    603, /* 1 colonne + picto */
    604, /* 2 colonnes */
    607, /* 2 colonnes mixtes */
    605, /* 3 colonnes */
    610, /* 4 colonnes et plus */
    633, /* iframe */
    659, /* Disponible sur */
    660, /* Accessoires */
    672, /* Outils */
    651, /* Drag n drop */
    664, /* Mur media */
    661, /* Véhicules neufs */
    657, /* Slideshow */
    653, /* Sticky bar*/
    652, /* Onglet */
    609, /* Contenu 2 colonnes + téléphone */
    666, /* Recherche point de vente */
    658, /* Finitions */
    668, /* Equipements et caractéristiques techniques */
    637, /* Contenu texte 2/3 + CTA 1/3 */
    650, /* Edito */
    656, /* Contenu texte seul */
    699, /* Accordéon */
    688, /* Mixte 2 colonnes enrichies */
    687, /* Contenu 2 colonnes texte sans visuel */
    692, /* Mosaique */
    691, /* Mosaique Interactive */
    682, /* Verbatim */
    690, /* 2 colonnes avec visuel + texte */
    730, /* Simulateur de financement */
    694, /* Contrat de services */
    697, /* Citroen sur mobile et tablette */
    684, /* Offre plus */
    738, /* Autres réseaux */
    696, /* Remontée réseaux sociaux */
    740, /* Formulaires */
    733, /* Outils aide financement */
    693, /* Account manager */
);
Pelican::$config["TRANCHE_DYNAMIQUE_LABEL"] = array(
    671 => 'Texte Riche',
    612 => '1 colonne',
    603=> '1 colonne + picto',
    604 => '2 colonnes',
    607 => '2 colonnes mixtes',
    605 => '3 colonnes',
    610 => '4 colonnes et plus',
    633 => 'iframe',
    659 => 'Disponible sur',
    660 => 'Accessoires',
    672 => 'Outils',
    651 => 'Drag n drop',
    664 => 'Mur media',
    661 => 'Véhicules neufs',
    657 => 'Slideshow',
    653 => 'Sticky bar',
    652 => 'Onglet',
    609 => 'Contenu 2 colonnes + téléphone',
    666 => 'Recherche point de vente',
    658 => 'Finitions',
    668 => 'Equipements et caractéristiques techniques',
    637 => 'Contenu texte 2/3 + CTA 1/3',
    650 => 'Edito',
    656 => 'Contenu texte seul',
    699 => 'Accordéon',
    688 => 'Mixte 2 colonnes enrichies',
    687 => 'Contenu 2 colonnes texte sans visuel',
    692 => 'Mosaique',
    691 => 'Mosaique Interactive',
    682 => 'Verbatim',
    690 => '2 colonnes avec visuel + texte',
    730 => 'Simulateur de financement',
    694 => 'Contrat de services',
    697 => 'Citroen sur mobile et tablette',
    684 => 'Offre plus',
    738 => 'Autres réseaux',
    696 => 'Remontée réseaux sociaux',
    740 => 'Formulaires',
    733 => 'Outils aide financement',
    693 => 'Account manager'
);

$oConnection = Pelican_Db::getInstance();
if(is_array(Pelican::$config["GABARIT_DYNAMIQUE"]) && count(Pelican::$config["GABARIT_DYNAMIQUE"])>0){
    foreach(Pelican::$config["GABARIT_DYNAMIQUE"] as $gabarit){
        $aBind[':TEMPLATE_PAGE_ID'] = $gabarit;
        $aBind[':AREA_DYNAMIQUE'] = 150;
        $sSQL = "
            SELECT
                ZONE_ID
            FROM
                #pref#_zone_template
            where
                AREA_ID = :AREA_DYNAMIQUE
            and
                TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID
        ";
        $results = $oConnection->queryTab($sSQL, $aBind);
        $sSQL = "
            SELECT
                max(ZONE_TEMPLATE_ORDER)
            FROM
                #pref#_zone_template
            where
                AREA_ID = :AREA_DYNAMIQUE
            and
                TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID
        ";
        $order = $oConnection->queryItem($sSQL, $aBind);
        $zoneTemplates = array();
        if(is_array($results) && count($results)>0){
            foreach($results as $result){
                $zoneTemplates[] = $result['ZONE_ID'];
            }
        }
        if(is_array(Pelican::$config["TRANCHE_DYNAMIQUE"]) && count(Pelican::$config["TRANCHE_DYNAMIQUE"])>0){
            foreach(Pelican::$config["TRANCHE_DYNAMIQUE"] as $tranche){
                if(!in_array($tranche, $zoneTemplates)){
                   $order = $order+1;
                   Pelican_Db::$values  = array();
                   Pelican_Db::$values['ZONE_TEMPLATE_ID']  = -2;
                   Pelican_Db::$values['ZONE_TEMPLATE_LABEL']  = Pelican::$config["TRANCHE_DYNAMIQUE_LABEL"][$tranche];
                   Pelican_Db::$values['TEMPLATE_PAGE_ID']  = $gabarit;
                   Pelican_Db::$values['AREA_ID']  = 150;
                   Pelican_Db::$values['ZONE_ID']  = $tranche;
                   Pelican_Db::$values['ZONE_TEMPLATE_ORDER']  = $order;
                   $oConnection->updateTable(Pelican::$config['DATABASE_INSERT'], "#pref#_zone_template");
                }
            }
        }
    }
}

