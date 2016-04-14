<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150817114205 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
//

        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ("NDP_SEE_PHONE", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_RETURN_LIST_POINTS_OF_SALES", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_CONTACT", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_SERVICE", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_VISIT_WEBSITE", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_CONTACT_CARD", NULL, 2, NULL, NULL, NULL, 1)
              '
        );

        $this->addSql('INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
               ("NDP_SEE_PHONE", 1,  "JE VEUX","Voir le numéro"),
               ("NDP_RETURN_LIST_POINTS_OF_SALES", 1,  "Retour aux points de vente",""),
               ("NDP_CONTACT", 1,  "Contact",""),
               ("NDP_SERVICE", 1,  "Service",""),
               ("NDP_VISIT_WEBSITE", 1,  "Visitez le site",""),
               ("NDP_CONTACT_CARD", 1,  "fiche contact (vcf)","")

               '
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

        $tables = array('psa_label', 'psa_label_langue');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE `LABEL_ID` IN
                (
                    "NDP_SEE_PHONE",
                    "NDP_RETURN_LIST_POINTS_OF_SALES",
                    "NDP_CONTACT",
                    "NDP_SERVICE",
                    "NDP_CONTACT_CARD",
                    "NDP_VISIT_WEBSITE"
                )'
            );
        }

    }
}
