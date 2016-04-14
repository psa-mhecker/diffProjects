<?php namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150601331720 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE IF EXISTS psa_services_connect');
        $this->addSql('DROP TABLE IF EXISTS psa_benefice');
        $this->addSql(
            "CREATE TABLE `psa_benefice` (
            `ID` INT(11) NOT NULL AUTO_INCREMENT,
            `LANGUE_ID` INT(11) NOT NULL,
            `SITE_ID` INT(11) NOT NULL,
            `LABEL` VARCHAR(45) NOT NULL,
            PRIMARY KEY (`ID`, `LANGUE_ID`, `SITE_ID`)
            )
            COLLATE='latin1_swedish_ci'ENGINE=InnoDB;"
        );
        $this->addSql(
            "CREATE TABLE `psa_services_connect` (
            `ID` INT(11) NOT NULL AUTO_INCREMENT,
            `LANGUE_ID` INT(11) NOT NULL,
            `SITE_ID` INT(11) NOT NULL,
            `LABEL` VARCHAR(45) NOT NULL,
            `DESCRIPTION` VARCHAR(100) NOT NULL,
            `URL` VARCHAR(255),
            `BENEFICES` VARCHAR(255) NOT NULL,
            `MENTIONS_LEGALES` VARCHAR(50),
            `VISUEL_APPLICATION` INT(11),
            `VISUEL_SELECTEUR` INT(11),
            `PRIX` INT(11) NOT NULL,
            `A_PARTIR_DE` VARCHAR(11) NOT NULL,
            `AUTRE` VARCHAR(30) NOT NULL,
            PRIMARY KEY (`ID`, `LANGUE_ID`, `SITE_ID`)
            )
            COLLATE='latin1_swedish_ci'ENGINE=InnoDB;"
        );
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                "DELETE FROM `".$table."` WHERE `LABEL_ID` IN
                (
                    'NDP_BENEFICE',
                    'NDP_SERVICE_CONNECTE',
                    'NDP_URL_FICHE_SERVICE',
                    'NDP_VISUEL_APPLICATION',
                    'NDP_VISUEL_SELECTEUR',
                    'NDP_OTHER',
                    'NDP_FICHE_SERVICES_CONNECTES',
                    'NDP_ONLY_TWO_DECIMAL_FROM'
                )
                "
            );
        }
        $this->addSql(
            "INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                    ('NDP_BENEFICE', NULL, 2, NULL, NULL, 1, NULL),
                    ('NDP_SERVICE_CONNECTE', NULL, 2, NULL, NULL, 1, NULL),
                    ('NDP_URL_FICHE_SERVICE', NULL, 2, NULL, NULL, 1, NULL),
                    ('NDP_VISUEL_APPLICATION', NULL, 2, NULL, NULL, 1, NULL),
                    ('NDP_VISUEL_SELECTEUR', NULL, 2, NULL, NULL, 1, NULL),
                    ('NDP_OTHER', NULL, 2, NULL, NULL, 1, NULL),
                    ('NDP_FICHE_SERVICES_CONNECTES', NULL, 2, NULL, NULL, 1, NULL),
                    ('NDP_ONLY_TWO_DECIMAL_FROM', NULL, 2, NULL, NULL, 1, NULL)
                    "
        );
        $this->addSql(
            "INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                    ('NDP_BENEFICE', 1, 1, 'Bénéfice'),
                    ('NDP_SERVICE_CONNECTE', 1, 1, 'Service connecté'),
                    ('NDP_URL_FICHE_SERVICE', 1, 1, 'URL de la fiche service'),
                    ('NDP_VISUEL_APPLICATION', 1, 1, 'Visuel application'),
                    ('NDP_VISUEL_SELECTEUR', 1, 1, 'Visuel sélecteur'),
                    ('NDP_OTHER', 1, 1, 'Autre'),
                    ('NDP_FICHE_SERVICES_CONNECTES', 1, 1, 'NDP - Fiche services connectés'),
                    ('NDP_ONLY_TWO_DECIMAL_FROM', 1, 1, 'Le champ \"à partir de\" prend au maximum 2 décimales.')
                    "
        );
        $this->addSql("INSERT INTO `psa_page_type` (`PAGE_TYPE_ID`, `PAGE_TYPE_LABEL`, `PAGE_TYPE_CODE`) VALUES (34, 'NDP - Fiche services connectés', 'G29')");
        $this->addSql("INSERT INTO `psa_template_page` (`TEMPLATE_PAGE_ID`, `SITE_ID`, `PAGE_TYPE_ID`, `TEMPLATE_PAGE_LABEL`) VALUES (379, 2, 34, 'NDP_FICHE_SERVICES_CONNECTES')");
        $this->addSql("INSERT INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`, `AREA_ID`, `TEMPLATE_PAGE_AREA_ORDER`, `LARGEUR`) VALUES (379, 150, 1, 4)");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM psa_template_page_area WHERE TEMPLATE_PAGE_ID=379 ");
        $this->addSql("DELETE FROM psa_template_page WHERE TEMPLATE_PAGE_ID=379 ");
        $this->addSql("DELETE FROM psa_page_type WHERE PAGE_TYPE_ID=34 ");
        $this->addSql('DROP TABLE IF EXISTS psa_services_connect');
        $this->addSql('DROP TABLE IF EXISTS psa_benefice');
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                "DELETE FROM `".$table."` WHERE `LABEL_ID` IN
                (
                    'NDP_BENEFICE',
                    'NDP_SERVICE_CONNECTE',
                    'NDP_URL_FICHE_SERVICE',
                    'NDP_VISUEL_APPLICATION',
                    'NDP_VISUEL_SELECTEUR',
                    'NDP_OTHER',
                    'NDP_FICHE_SERVICES_CONNECTES',
                    'NDP_ONLY_TWO_DECIMAL_FROM'
                )
                "
            );
        }
    }
}
