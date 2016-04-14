<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150420125029 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("delete from psa_zone_template where ZONE_TEMPLATE_ID in (4112, 4126, 4149, 4153, 4156, 4211, 4213, 4217, 4218, 4219, 4220, 4222, 4223, 4224, 4225, 4227, 4228, 4232, 4233, 4238, 4240, 4241, 4242, 4243, 4244)");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
