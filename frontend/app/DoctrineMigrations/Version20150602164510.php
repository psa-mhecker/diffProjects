<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150602164510 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE  psa_content_category ADD  CONTENT_CATEGORY_ATTRIBUT INT NULL');
        $this->addSql(
          "INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                  ('NDP_ZONE_USED_TO', NULL, 2, NULL, NULL, 1, NULL),
                  ('NDP_FAQ_GLOBAL', NULL, 2, NULL, NULL, 1, NULL),
                  ('NDP_FAQ_FOCUS', NULL, 2, NULL, NULL, 1, NULL),
                  ('NDP_FAQ_RESPONSE_SATISFACTION_WHEN_NO_SELECTED', NULL, 2, NULL, NULL, 1, NULL),
                  ('NDP_CTA_FOOTER', NULL, 2, NULL, NULL, 1, NULL),
                  ('NDP_MSG_FOCUS_NEEDED', NULL, 2, NULL, NULL, 1, NULL)
                  "
        );

        $this->addSql(
          "INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                  ('NDP_ZONE_USED_TO', 1, 1, 'Tranche utilisée pour'),
                  ('NDP_FAQ_GLOBAL', 1, 1, 'FAQ générale'),
                  ('NDP_FAQ_FOCUS', 1, 1, 'Focus FAQ'),
                  ('NDP_FAQ_RESPONSE_SATISFACTION_WHEN_NO_SELECTED', 1, 1, 'Réponse de satisfaction si « NON » est coché'),
                  ('NDP_CTA_FOOTER', 1, 1, 'CTA bas de page'),
                  ('NDP_MSG_FOCUS_NEEDED', 1, 1, 'Vous devez rattacher au moins une catégorie au focus FAQ pour que le choix \'Focus FAQ\' soit disponible')

                  "
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql("DELETE FROM `".$table."` WHERE `LABEL_ID` IN (
            'NDP_ZONE_USED_TO',
            'NDP_FAQ_GLOBAL',
            'NDP_FAQ_FOCUS',
            'NDP_FAQ_RESPONSE_SATISFACTION_WHEN_NO_SELECTED',
            'NDP_CTA_FOOTER',
            'NDP_MSG_FOCUS_NEEDED')
            ");
        }

        $this->addSql('ALTER TABLE  psa_content_category drop  CONTENT_CATEGORY_ATTRIBUT');

    }
}
