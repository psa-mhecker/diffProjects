<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150427175214 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_GRAND_VISUEL_MOBILE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_MSG_VISUEL_CTA_IMP', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_MSG_CTA_ABS', NULL, 2, NULL, NULL, 1, NULL)
                ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_GRAND_VISUEL_MOBILE', 1, 1, 'Format mobile grand visuel'),
                ('NDP_MSG_VISUEL_CTA_IMP', 1, 1, 'Veuillez vérifier que les CTA importés du référentiel comportent bien un visuel.'),
                ('NDP_MSG_CTA_ABS', 1, 1, 'Si un visuel est absent dans 1 des 3 CTA alors la tranche s\'affichera sans vignette.')
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
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
             (
                "NDP_GRAND_VISUEL_MOBILE", "NDP_MSG_VISUEL_CTA_IMP",
                "NDP_MSG_CTA_ABS"
             )
        ');
        }
    }
}
