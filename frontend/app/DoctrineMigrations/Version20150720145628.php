<?php namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150720145628 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("INSERT INTO `psa_directory` (`DIRECTORY_ID`, `TEMPLATE_ID`, `DIRECTORY_PARENT_ID`, `DIRECTORY_ADMIN`, `TEMPLATE_COMPLEMENT`, `DIRECTORY_LEFT_LABEL`, `DIRECTORY_LABEL`, `DIRECTORY_ICON`, `DIRECTORY_DEFAULT`) VALUES
                (246, NULL, 62, 0, NULL, NULL, 'NDP_FORMS', NULL, NULL),
                (209, 311, 246, 0, NULL, NULL, 'FORM_TYPE', NULL, NULL),
                (210, 312, 246, 0, NULL, NULL, 'FORM_ID_FORM', NULL, NULL);");
        
        $this->addSql("INSERT INTO `psa_directory_site` (`SITE_ID`, `DIRECTORY_ID`) VALUES
        (2, 246),
        (2, 209),
        (2, 210);");

        $this->addSql("INSERT INTO `psa_profile_directory` (`PROFILE_ID`, `DIRECTORY_ID`, `PROFILE_DIRECTORY_ORDER`) VALUES
        (2, 246, 2088),
        (2, 209, 2089),
        (2, 210, 2090);");

        $this->addSql("UPDATE `psa_template` SET TEMPLATE_PATH='Ndp_Administration_Typeformulaire' WHERE `TEMPLATE_ID` = '311';");
        $this->addSql("UPDATE `psa_template` SET TEMPLATE_PATH='Ndp_Administration_Formulaire' WHERE `TEMPLATE_ID` = '312'");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM psa_profile_directory WHERE DIRECTORY_ID IN (209,210,246)");
        $this->addSql("DELETE FROM psa_directory_site WHERE DIRECTORY_ID IN (209,210,246)");
        $this->addSql("DELETE FROM psa_directory WHERE DIRECTORY_ID IN (209,210,246)");

        $this->addSql("UPDATE `psa_template` SET TEMPLATE_PATH='Citroen_Administration_Typeformulaire' WHERE `TEMPLATE_ID` = '311';");
        $this->addSql("UPDATE `psa_template` SET TEMPLATE_PATH='Citroen_Administration_Formulaire' WHERE `TEMPLATE_ID` = '312'");
    }
}
