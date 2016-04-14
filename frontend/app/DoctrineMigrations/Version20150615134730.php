<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150615134730 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_GENDER', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_SLOGAN', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_FINISHING_ORDER', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_LCDV4', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_MSG_SLOGAN', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_MSG_OVERRIDE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_TITLE_SLOGAN', NULL, 2, NULL, NULL, 1, NULL)

                ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_GENDER', 1, 1, 'Genre'),
                ('NDP_SLOGAN', 1, 1, 'Accroche'),
                ('NDP_FINISHING_ORDER', 1, 1, 'Ordre de finition'),
                ('NDP_LCDV4', 1, 1, 'LCDV4'),
                ('NDP_MSG_SLOGAN', 1, 1, 'La phrase d’accroche du modèle est affichée sur la Range Bar.'),
                ('NDP_MSG_OVERRIDE', 1, 1, 'Surcharge du paramétrage pour tous les modèles'),
                ('NDP_TITLE_SLOGAN', 1, 1, 'Phrase d’accroche du modèle')
                ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN (
                "NDP_GENDER",
                "NDP_SLOGAN",
                "NDP_FINISHING_ORDER",
                "NDP_MSG_SLOGAN",
                "NDP_MSG_OVERRIDE",
                "NDP_TITLE_SLOGAN",
                "NDP_LCDV4"
             )');
        }
    }
}
