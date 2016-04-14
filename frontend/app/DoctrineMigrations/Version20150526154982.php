<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150526154982 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {       
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_MSG_CTA_SHOWROOM_DISPLAY_CONDITION', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_CTA_CONFIGURER', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_AVIS_CLIENT_REEVOO', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_ONGLET_DESKTOP', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_CTA_WEB_CALL_BACK', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_CTA_CLICK_TO_CALL', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_ADD_CTA_ONGLET', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_ONGLET', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_CONTENU_ONGLET', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_LABEL_ONGLET', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_MSG_CTA_SHOWROOM_DISPLAY_CONDITION', 1, 1, 'Cette tranche est affichée sur toutes les pages du showroom excepté sur la Welcome Page'),
                ('NDP_CTA_CONFIGURER', 1, 1, 'CTA Configurer'),
                ('NDP_AVIS_CLIENT_REEVOO', 1, 1, 'Avis clients Reevoo'),
                ('NDP_ONGLET_DESKTOP', 1, 1, 'Onglets desktop'),
                ('NDP_CTA_WEB_CALL_BACK', 1, 1, 'CTA Web Call back'),
                ('NDP_CTA_CLICK_TO_CALL', 1, 1, 'CTA Click to call'),
                ('NDP_ADD_CTA_ONGLET', 1, 1, 'Ajouter un CTA onglet'),
                ('NDP_ONGLET', 1, 1, 'Onglet'),
                ('NDP_CONTENU_ONGLET', 1, 1, 'Contenu onglet'),
                ('NDP_LABEL_ONGLET', 1, 1, 'Libellé onglet')
            ");
    }

    
    
    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                "DELETE FROM `".$table."`  WHERE  `LABEL_ID` IN
                 (
                 'NDP_MSG_CTA_SHOWROOM_DISPLAY_CONDITION',
                 'NDP_CTA_CONFIGURER',
                 'NDP_AVIS_CLIENT_REEVOO',
                 'NDP_ONGLET_DESKTOP',
                 'NDP_CTA_WEB_CALL_BACK',
                 'NDP_CTA_CLICK_TO_CALL',
                 'NDP_ADD_CTA_ONGLET',
                 'NDP_ONGLET',
                 'NDP_CONTENU_ONGLET',
                 'NDP_LABEL_ONGLET'
                 )
                "
            );
        }
    }
}
