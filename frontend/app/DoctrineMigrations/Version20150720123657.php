<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150720123657 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `psa_model_config` ADD `CTA_ERREUR` INT(1) NOT NULL,
                        ADD  `CTA_ERREUR_ID` INT(11) NULL,
                        ADD  `CTA_ERREUR_ACTION` VARCHAR(255) NULL,
                        ADD  `CTA_ERREUR_STYLE` VARCHAR(255) NULL,
                        ADD  `CTA_ERREUR_TARGET` VARCHAR(255) NULL");
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_MSG_CTA_ERROR', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_MSG_CTA_ERROR', 1, 1, 'CTA du message d’erreur (finitions et motorisations)')
            ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `psa_model_config`
                        DROP `CTA_ERREUR`,
                        DROP `CTA_ERREUR_ID`,
                        DROP `CTA_ERREUR_ACTION`,
                        DROP `CTA_ERREUR_STYLE`,
                        DROP `CTA_ERREUR_TARGET`");
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                    "NDP_MSG_CTA_ERROR"
                )'
            );
        }
    }
}
