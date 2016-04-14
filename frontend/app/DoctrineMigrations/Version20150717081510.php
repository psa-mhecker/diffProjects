<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150717081510 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(
            'UPDATE psa_label SET LABEL_FO = 1 WHERE LABEL_ID IN (
                "NDP_MY_PEUGEOT"
            )'
        );

        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_ALREADY_REGISTERED", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_SIGN_IN", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PT22_NEW_MEMBER", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PT22_SIGN_UP_WEB", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PT22_SIGN_UP_MOBILE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PT22_HEADER_TITLE", NULL, 2, NULL, NULL, NULL, 1)
            '
        );

        $this->addSql('INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
            ("NDP_MY_PEUGEOT", 1, "MYPEUGEOT", ""),
            ("NDP_ALREADY_REGISTERED", 1, "Déjà inscrit ?", ""),
            ("NDP_SIGN_IN", 1, "Me connecter", ""),
            ("NDP_PT22_NEW_MEMBER", 1, "Nouveau membre ?", ""),
            ("NDP_PT22_SIGN_UP_WEB", 1, "Créer un compte", ""),
            ("NDP_PT22_SIGN_UP_MOBILE", 1, "Télécharger l’application", ""),
            ("NDP_PT22_HEADER_TITLE", 1, "Et retrouvez aussi l\'application My Peugeot depuis votre smartphone", "")
            '
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql(
            'UPDATE psa_label SET LABEL_FO = NULL WHERE LABEL_ID IN (
                    "NDP_MY_PEUGEOT"
                )'
        );

        $this->addSql(
            'DELETE FROM `psa_label_langue`  WHERE `LABEL_ID` IN
                (
                    "NDP_MY_PEUGEOT"
                )'
        );

        $tables = array('psa_label', 'psa_label_langue');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE `LABEL_ID` IN
                (
                    "NDP_ALREADY_REGISTERED",
                    "NDP_SIGN_IN",
                    "NDP_PT22_NEW_MEMBER",
                    "NDP_PT22_SIGN_UP_WEB",
                    "NDP_PT22_SIGN_UP_MOBILE",
                    "NDP_PT22_HEADER_TITLE"
                )'
            );
        }
    }
}
