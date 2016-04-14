<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150827113527 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('REPLACE INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
               ("NDP_SEE_PHONE", 1,  "Voir le numéro", "")

               '
        );

        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ("NDP_CONTACT_US", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_PROMOTIONS_OF_POINT_OF_SALE", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_ITINARY", NULL, 2, NULL, NULL, NULL, 1)'
        );
        $this->addSql('INSERT INTO `psa_label_langue`  (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
               ("NDP_CONTACT_US", 1,   "Contactez-nous", ""),
               ("NDP_PROMOTIONS_OF_POINT_OF_SALE", 1, "promotions du point de vente", ""),
               ("NDP_ITINARY", 1,  "Itinéraire", "")'
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
                "NDP_CONTACT_US",
                "NDP_PROMOTIONS_OF_POINT_OF_SALE",
                "NDP_ITINARY"
                )
        ');
        }
    }
}
