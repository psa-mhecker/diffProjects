<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150825162023 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ("NDP_NOTICE_BOTH_TYPE", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_NOTICE_DEALER_TYPE", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_NOTICE_TECH_TYPE", NULL, 2, NULL, NULL, NULL, 1)'
        );
        $this->addSql('INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
               ("NDP_NOTICE_BOTH_TYPE", 1,  "Cet article nécessite l\'achat d\'accessoires complémentaires et l\'intervention d\'un technicien spécialisé. Consultez votre point de vente.",""),
               ("NDP_NOTICE_DEALER_TYPE", 1,  "Cet article nécessite l\'achat d\'accessoires complémentaires. Consultez votre point de vente.",""),
               ("NDP_NOTICE_TECH_TYPE", 1,  "Cet article nécessite l\'intervention d\'un technicien spécialisé. Consultez votre point de vente.","")'
        );

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label', 'psa_label_langue');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
             (
                "NDP_NOTICE_BOTH_TYPE",
                "NDP_NOTICE_DEALER_TYPE",
                "NDP_NOTICE_TECH_TYPE"
                )
        ');
        }
    }
}
