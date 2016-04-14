<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150609112733 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // cas administration interface de site
        $this->addSql("UPDATE psa_template SET TEMPLATE_LABEL = 'NDP_REF_SITE' WHERE TEMPLATE_ID =18");
        // site admin
        $this->addSql("UPDATE psa_directory  SET DIRECTORY_LABEL = 'NDP_REF_SITE'  WHERE DIRECTORY_ID =30");
        // site pays
        $this->addSql("UPDATE psa_directory  SET DIRECTORY_LABEL = 'NDP_REF_SITE'  WHERE DIRECTORY_ID =183");

        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_REF_SITE', NULL, 2, NULL, NULL, 1, NULL)");
       $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_REF_SITE', 1, 1, 'Interface de site')");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN ("NDP_REF_SITE")');
        }

    }
}
