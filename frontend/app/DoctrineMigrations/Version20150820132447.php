<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150820132447 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ("NDP_ACCESSORY_GENERIC_LEGAL_MENTION", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_REFERENCE_LABEL", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_SEE_OUR_STORE", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_DISCOVER", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_BUY_ONLINE", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_ERROR_ACCESSORY", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_ERROR_CTA_ACCESSORY", NULL, 2, NULL, NULL, NULL, 1)
              '
        );
        $this->addSql('INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
               ("NDP_ACCESSORY_GENERIC_LEGAL_MENTION", 1,  "Prix public conseillé TTC (hors pose). \n Visuels non contractuels.",""),
               ("NDP_BUY_ONLINE", 1,  "Acheter en ligne",""),
               ("NDP_DISCOVER", 1,  "Découvrir",""),
               ("NDP_REFERENCE_LABEL", 1,  "Référence : ",""),
               ("NDP_SEE_OUR_STORE", 1,  "Accédez à notre boutique de produits dérivés",""),
               ("NDP_ERROR_ACCESSORY", 1,  "LIBELLE D\'ERREUR A TRADUIRE CORRECTEMENT - NDP_ERROR_ACCESSORY",""),
               ("NDP_ERROR_CTA_ACCESSORY", 1,  "LIBELLE D\'ERREUR CTA A TRADUIRE CORRECTEMENT - NDP_ERROR_CTA_ACCESSORY","")

               '
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
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE `LABEL_ID` IN
                (
                    "NDP_ACCESSORY_GENERIC_LEGAL_MENTION",
                    "NDP_DISCOVER",
                    "NDP_BUY_ONLINE",
                    "NDP_REFERENCE_LABEL",
                    "NDP_SEE_OUR_STORE",
                    "NDP_ERROR_ACCESSORY",
                    "NDP_ERROR_CTA_ACCESSORY"
                )'
            );
        }
    }
}
