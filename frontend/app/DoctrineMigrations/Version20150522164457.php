<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150522164457 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_INFO_SLICE_WILL_SHOW_IN_POPIN', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_ASSOCIATED_SLICE', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_INFO_SLICE_WILL_SHOW_IN_POPIN', 1, 1, 'A compléter suite au paramétrage des tranches USP sur la page.\n Les tranches USP s\'afficheront en pop-in.'),
            ('NDP_ASSOCIATED_SLICE', 1, 1, 'Tranche associée')
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
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_INFO_SLICE_WILL_SHOW_IN_POPIN",
                "NDP_ASSOCIATED_SLICE"
                )
            ');
        }
    }
}
