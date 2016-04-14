<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150729112717 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
         $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_MSG_CHOOSE_MODEL', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MSG_FULLFILL_OPTION', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_FULLFILL_COMPATIBILITY', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_CAPABLE_CONNECTED_SERVICE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_SERIE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_NON_DISPO', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_OPTION', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REG_FINITION_SERVICE', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
           ('NDP_MSG_CHOOSE_MODEL', 1, 1, 'Veuillez renseigner un Modèle.'),
           ('NDP_MSG_FULLFILL_OPTION', 1, 1, 'Veuillez renseigner les détails de compatabilité.'),
           ('NDP_FULLFILL_COMPATIBILITY', 1, 1, 'Remplir les détails de compatibilité'),
           ('NDP_CAPABLE_CONNECTED_SERVICE', 1, 1, 'Services connectés compatibles au modèle'),
           ('NDP_SERIE', 1, 1, 'Série'),
           ('NDP_NON_DISPO', 1, 1, 'Non dispo'),
           ('NDP_OPTION', 1, 1, 'Option'),
           ('NDP_REG_FINITION_SERVICE', 1, 1, 'Reg. Finition \ Services')
            ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                    "NDP_MSG_CHOOSE_MODEL", "NDP_MSG_FULLFILL_OPTION", "NDP_FULLFILL_COMPATIBILITY", "NDP_CAPABLE_CONNECTED_SERVICE",
                    "NDP_NON_DISPO", "NDP_SERIE", "NDP_OPTION", "NDP_REG_FINITION_SERVICE"
                )'
            );
        }

    }
}
