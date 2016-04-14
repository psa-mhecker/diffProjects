<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150710110945 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE psa_zone_template SET ZONE_TEMPLATE_ORDER = 5 WHERE ZONE_TEMPLATE_ID = 4292');
        $this->addSql('UPDATE psa_zone_template SET ZONE_TEMPLATE_ORDER = 6 WHERE ZONE_TEMPLATE_ID = 4428');


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE psa_zone_template SET ZONE_TEMPLATE_ORDER = 6 WHERE ZONE_TEMPLATE_ID = 4292');
        $this->addSql('UPDATE psa_zone_template SET ZONE_TEMPLATE_ORDER = 5 WHERE ZONE_TEMPLATE_ID = 4428');

    }
}
