<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150513162520 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->down($schema);
        $this->addSql("INSERT INTO psa_site_webservice (site_id, ws_id, status) VALUES
            (2,1, 0),
            (2,2, 0),
            (2,3, 0),
            (2,4, 0),
            (2,5, 0),
            (2,6, 0),
            (2,7, 0),
            (2,8, 0)
            ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('TRUNCATE psa_site_webservice');

    }
}
