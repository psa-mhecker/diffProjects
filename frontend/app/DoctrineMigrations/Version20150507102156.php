<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150507102156 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_TITLE_CAT_GAMME', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_TITLE2_CAT_GAMME', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_CAT_USES_IN_RBCS', NULL, 2, NULL, NULL, 1, NULL),          
                ('NDP_LABEL', NULL, 2, NULL, NULL, 1, NULL), 
                ('NDP_CAT_VEHICULE', NULL, 2, NULL, NULL, 1, NULL),                
                ('NDP_PICTO_CAT', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_LABEL_CENTRAL_LIST', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_LABEL_LOCAL_LIST', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_LABEL_CENTRAL', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_LABEL_LOCAL', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_UTILISEES_SUR_LA_PAGE_SEO_GAMME', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_UTILISEES_SUR_LE_CAR_SELECTOR', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_CRITERES_MARKETING', NULL, 2, NULL, NULL, 1, NULL)
                ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_TITLE_CAT_GAMME', 1, 1, 'Catégories de véhicules créées en central'),
                ('NDP_TITLE2_CAT_GAMME', 1, 1, 'Catégories de véhicules surchargées en local'),
                ('NDP_CAT_USES_IN_RBCS', 1, 1, 'Ces catégories sont utilisées dans la Range Bar et le Car Selector.'),          
                ('NDP_LABEL', 1, 1, 'Libellé'),
                ('NDP_PICTO_CAT', 1, 1, 'Picto de la catégorie'),
                ('NDP_CAT_VEHICULE', 1, 1, 'Catégories de véhicules'),                
                ('NDP_LABEL_CENTRAL_LIST', 1, 1, 'Catégories de véhicules créées en central'),
                ('NDP_LABEL_LOCAL_LIST', 1, 1, 'Libellé de la catégorie'),
                ('NDP_LABEL_CENTRAL', 1, 1, 'Libellé central'),
                ('NDP_LABEL_LOCAL', 1, 1, 'Libellé local'),
                ('NDP_UTILISEES_SUR_LA_PAGE_SEO_GAMME', 1, 1, 'Utilisées sur la page SEO gamme'),         
                ('NDP_UTILISEES_SUR_LE_CAR_SELECTOR', 1, 1, 'Utilisées sur le Car Selector'),
                ('NDP_CRITERES_MARKETING', 1, 1, 'Critères marketing')
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
                 'NDP_TITLE_CAT_GAMME',
                 'NDP_TITLE2_CAT_GAMME',
                 'NDP_CAT_USES_IN_RBCS',
                 'NDP_LABEL',
                 'NDP_CAT_VEHICULE',
                 'NDP_PICTO_CAT',
                 'NDP_LABEL_CENTRAL_LIST',
                 'NDP_LABEL_CENTRAL',
                 'NDP_LABEL_LOCAL_LIST',
                 'NDP_LABEL_LOCAL',
                 'NDP_UTILISEES_SUR_LA_PAGE_SEO_GAMME',
                 'NDP_UTILISEES_SUR_LE_CAR_SELECTOR',
                 'NDP_CRITERES_MARKETING'
                 )
                "
                );
        }
    }
}
