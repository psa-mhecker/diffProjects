<?php
namespace Citroen\Batch;

use Itkg\Batch;

/**
 * Classe Backup
 *
 * Batch Backup : Permet de mettre sauvearder la base par SITE_ID
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Backup extends Batch
{

    public function execute()
    {
        $backupRoot = \Pelican::$config['VAR_ROOT'] . '/backup/';
        
        $primaryAdd = array(
            'perso_product' => 'PRODUCT_ID'
        );
        
        $secondaryAdd = array(
            'SITE_ID' => array(
                'arbre_decisionnel',
                'barre_outils',
                'categ_vehicule',
                'contenu_recommande',
                'critere',
                'faq_rubrique',
                'form',
                'groupe_reseaux_sociaux',
                'groupe_reseaux_sociaux_rs',
                'perso_product',
                'perso_product_page',
                'perso_product_term',
                'reseau_social',
                'site_personnalisation',
                'site_webservice',
                'tag',
                'theme_actualites',
                'theme_technogie_gallerie',
                'vehicule',
                'vehicule_couleur',
                'vehicule_couleur_auto',
                'vehicule_criteres',
                'ws_caracteristique_detail_moteur',
                'ws_caracteristique_moteur',
                'ws_caracteristique_technique',
                'ws_couleur_finition',
                'ws_critere_selection',
                'ws_energie_moteur',
                'ws_equipement_disponible',
                'ws_equipement_option',
                'ws_equipement_standard',
                'ws_finitions',
                'ws_modele',
                'ws_prix_finition_version',
                'ws_services_pdv',
                'ws_vehicule_gamme',
                'youtube'
            ),
            'PAGE_ID' => array(
                'perso_profile_page'
            ),
            'CONTENT_ID' => array(
                'faq_rubrique_content'
            ),
            'PRODUCT_ID' => array(
                'perso_product_media'
            )
        );
        $oBackup = new \Pelican_Backup();
        $oBackup->backup($backupRoot, $primaryAdd, $secondaryAdd);
        echo 'Fin du backup';
    }
}