<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150831162823 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE `psa_label_langue` SET `LABEL_TRANSLATE` = "avec le moteur %motorName% <br> disponible en  %motorNumber% motorisations." WHERE `LABEL_ID` = "NDP_SEVERAL_MOTOR_AVAILABLE" ');
        $this->addSql('UPDATE `psa_label_langue` SET `LABEL_TRANSLATE` = "avec le moteur %motorName%" WHERE `LABEL_ID` = "NDP_ONE_MOTOR_AVAILABLE" ');

        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ("NDP_DEPLOY_ALL", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_CLOSE_ALL", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_SHOW_DIFF", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_COMPARATOR_ERROR_LOAD", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_COMPARATOR_TABLE_TITLE", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_PRINT", NULL, 2, NULL, NULL, NULL, 1)'
        );

        $this->addSql('INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
               ("NDP_DEPLOY_ALL", 1,  "Tout déplier",""),
               ("NDP_CLOSE_ALL", 1,  "Tout refermer",""),
               ("NDP_SHOW_DIFF", 1,  "Uniquement les différences",""),
               ("NDP_COMPARATOR_ERROR_LOAD", 1,  "Nous rencontrons un problème de chargement. Veuillez réessayer plus tard. Si le problème persiste, n\'hésitez pas à nous contacter",""),
               ("NDP_COMPARATOR_TABLE_TITLE", 1,  "Tableau comparatif",""),
               ("NDP_PRINT", 1,  "Imprimer","")
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
                    "NDP_DEPLOY_ALL",
                    "NDP_CLOSE_ALL",
                    "NDP_SHOW_DIFF",
                    "NDP_COMPARATOR_ERROR_LOAD",
                    "NDP_COMPARATOR_TABLE_TITLE",
                    "NDP_PRINT"
                )'
            );
        }
    }
}
