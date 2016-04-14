<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150910163801 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE `psa_label_langue_site`
                       SET `LABEL_TRANSLATE` = \'Veuillez renseigner les noms de domaine associés au site\'
                       WHERE `LABEL_ID` = \'FONCTIONNEMENT_DNS\' AND `LANGUE_ID` = 1 AND `SITE_ID` = 1;
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('UPDATE `psa_label_langue_site`
                       SET `LABEL_TRANSLATE` = \'A renseigner\'
                       WHERE `LABEL_ID` = \'FONCTIONNEMENT_DNS\' AND `LANGUE_ID` = 1 AND `SITE_ID` = 1;
        ');
    }
}
