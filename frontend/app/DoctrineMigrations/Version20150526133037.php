<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150526133037 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE
                         psa_liste_webservices
                       ADD proxy_host VARCHAR(255) DEFAULT NULL,
                       ADD proxy_port VARCHAR(255) DEFAULT NULL,
                       ADD proxy_login VARCHAR(255) DEFAULT NULL,
                       ADD proxy_password VARCHAR(255) DEFAULT NULL,
                       ADD auth_login VARCHAR(255) DEFAULT NULL,
                       ADD auth_password VARCHAR(255) DEFAULT NULL
                       ');
    }


    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE psa_liste_webservices DROP proxy_host, DROP proxy_port, DROP proxy_login, DROP proxy_password, DROP auth_login, DROP auth_password');

    }
}
