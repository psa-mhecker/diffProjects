<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150715133231 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE psa_segmentation_finition_site (LABEL_LOCAL VARCHAR(255) NOT NULL, ENABLE_UPSELLING TINYINT(1) DEFAULT NULL, MARKETING_CRITERION VARCHAR(255) DEFAULT NULL, CLIENTELE_DESIGN VARCHAR(255) DEFAULT NULL, ORDER_TYPE INT DEFAULT NULL, ID INT NOT NULL, LANGUE_ID INT NOT NULL, SITE_ID INT NOT NULL, INDEX IDX_5906659B11D3633A (ID), INDEX IDX_5906659B5622E2C2 (LANGUE_ID), INDEX IDX_5906659BF1B5AEBC (SITE_ID), PRIMARY KEY(ID, LANGUE_ID, SITE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_segmentation_finition (ID INT AUTO_INCREMENT NOT NULL, CODE VARCHAR(255) NOT NULL, `LABEL` VARCHAR(255) NOT NULL, ORDER_TYPE INT DEFAULT NULL, PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_segmentation_finition_site ADD CONSTRAINT FK_5906659B11D3633A FOREIGN KEY (ID) REFERENCES psa_segmentation_finition (ID) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_segmentation_finition_site ADD CONSTRAINT FK_5906659B5622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_segmentation_finition_site ADD CONSTRAINT FK_5906659BF1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('INSERT INTO psa_segmentation_finition VALUES (1, "PART", "Véhicules particuliers", 1)');
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_LABEL_SEGMENT', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MSG_SEG_WITHOUT_FIN', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MSG_ENABLING_UPSELLING', NULL, 2, NULL, NULL, 1, NULL),
            ('NDM_MSG_PREVIOUS_CONF_REPLACE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_ENABLING_UPSELLING', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MARKETING_CRITERION', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_CLIENTELE_DESIGN', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_GROUPING_CODE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_COMMERCIAL_LABEL', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_SETTED', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_LABEL_SEGMENT', 1, 1, 'Libellé du segment'),
            ('NDP_MSG_SEG_WITHOUT_FIN', 1, 1, 'Les finitions qui ne sont pas affectées à un segment apparaîtront dans « Véhicules particuliers »'),
            ('NDP_MSG_ENABLING_UPSELLING', 1, 1, 'L’activation/désactivation de la montée en gamme pour un segment de finitions activera/désactivera la montée en gamme sur l’ensemble des finitions ce segment. '),
            ('NDM_MSG_PREVIOUS_CONF_REPLACE', 1, 1, 'Les paramétrages précédents seront remplacés. '),
            ('NDP_ENABLING_UPSELLING', 1, 1, 'Activer la montée en gamme'),
            ('NDP_MARKETING_CRITERION', 1, 1, 'Critères marketing'),
            ('NDP_CLIENTELE_DESIGN', 1, 1, 'Clientèles de conception'),
            ('NDP_GROUPING_CODE', 1, 1, 'Code regroupement de silhouettes'),
            ('NDP_COMMERCIAL_LABEL', 1, 1, 'Libellé commercial'),
            ('NDP_SETTED', 1, 1, 'Paramétré')
            ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE psa_segmentation_finition_site');
        $this->addSql('DROP TABLE psa_segmentation_finition');
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                    "NDP_LABEL_SEGMENT", "NDP_MSG_SEG_WITHOUT_FIN", "NDP_MSG_ENABLING_UPSELLING", "NDM_MSG_PREVIOUS_CONF_REPLACE",
                    "NDP_ENABLING_UPSELLING", "NDP_MARKETING_CRITERION", "NDP_CLIENTELE_DESIGN", "NDP_SETTED", "NDP_GROUPING_CODE",
                    "NDP_COMMERCIAL_LABEL"
                )'
            );
        }

    }
}
