<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150526145713 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // cas administration depuis site d'admin
        $this->addSql("UPDATE psa_directory SET TEMPLATE_COMPLEMENT = 'admin' WHERE DIRECTORY_ID =30");
        // administration de site pays point sur le même template
        $this->addSql('UPDATE psa_directory SET TEMPLATE_ID = 18 WHERE DIRECTORY_ID =183');
        // suppression de l'ancien template
        $this->addSql('DELETE FROM psa_template WHERE TEMPLATE_ID = 291');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
