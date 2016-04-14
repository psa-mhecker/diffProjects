<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150618120132 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE IF EXISTS psa_application_connect_apps');
        $this->addSql(
            "CREATE TABLE `psa_application_connect_apps` (
            `ID` INT(11) NOT NULL AUTO_INCREMENT,
            `LANGUE_ID` INT(11) NOT NULL,
            `SITE_ID` INT(11) NOT NULL,
            `APPLICATION` VARCHAR(30) NOT NULL,
            `LABEL` VARCHAR(50) NOT NULL,
            `MEDIA_ID` INT(11) NOT NULL,
            `INTRODUCTION` VARCHAR(50) NOT NULL,
            `DESCRIPTION` VARCHAR(300) NOT NULL,
            `CARACTERISTIQUES` VARCHAR(780) NOT NULL,
            PRIMARY KEY (`ID`, `LANGUE_ID`, `SITE_ID`)
            )
            COLLATE='latin1_swedish_ci'ENGINE=InnoDB;"
        );
        $tables = array('psa_label', 'psa_label_langue_site');
       foreach ($tables as $table) {
            $this->addSql(
                "DELETE FROM `".$table."` WHERE `LABEL_ID` IN
                (
                    'NDP_APPLICATION',
                    'NDP_FULL_NAME',
                    'NDP_INTRODUCTION',
                    'NDP_SHORT_DESCRIPTION',
                    'NDP_MAIN_CARACTERISTIQUES',
                    'NDP_VISUEL_APPLICATION_CARRE'
                )
                "
            );
        }
        $this->addSql(
            "INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                    ('NDP_APPLICATION', NULL, 2, NULL, NULL, 1, NULL),
                    ('NDP_FULL_NAME', NULL, 2, NULL, NULL, 1, NULL),
                    ('NDP_INTRODUCTION', NULL, 2, NULL, NULL, 1, NULL),
                    ('NDP_MAIN_CARACTERISTIQUES', NULL, 2, NULL, NULL, 1, NULL),
                    ('NDP_SHORT_DESCRIPTION', NULL, 2, NULL, NULL, 1, NULL),
                    ('NDP_VISUEL_APPLICATION_CARRE', NULL, 2, NULL, NULL, 1, NULL)
                    "
        );
        $this->addSql(
            "INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                    ('NDP_APPLICATION', 1, 1, 'Application'),
                    ('NDP_FULL_NAME', 1, 1, 'Nom complet'),
                    ('NDP_INTRODUCTION', 1, 1, 'Introduction'),
                    ('NDP_MAIN_CARACTERISTIQUES', 1, 1, 'Caractéristiques principales'),
                    ('NDP_SHORT_DESCRIPTION', 1, 1, 'Description courte'),
                    ('NDP_VISUEL_APPLICATION_CARRE', 1, 1, 'Visuel application carré')
                    "
        );

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
       $this->addSql('DROP TABLE IF EXISTS psa_application_connect_apps');
       $tables = array('psa_label', 'psa_label_langue_site');
       foreach ($tables as $table) {
            $this->addSql(
                "DELETE FROM `".$table."` WHERE `LABEL_ID` IN
                (
                    'NDP_APPLICATION',
                    'NDP_FULL_NAME',
                    'NDP_INTRODUCTION',
                    'NDP_SHORT_DESCRIPTION',
                    'NDP_MAIN_CARACTERISTIQUES',
                    'NDP_VISUEL_APPLICATION_CARRE'
                )
                "
            );
        }
    }
}
