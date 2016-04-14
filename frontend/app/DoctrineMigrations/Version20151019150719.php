<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151019150719 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE psa_type_couleur_site DROP FOREIGN KEY FK_46C6250E11D3633A');
        $this->addSql('ALTER TABLE psa_type_couleur_site ADD CONSTRAINT FK_46C6250E11D3633A FOREIGN KEY (ID) REFERENCES psa_type_couleur (ID) ON DELETE CASCADE');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE psa_type_couleur_site DROP FOREIGN KEY FK_46C6250E11D3633A');
        $this->addSql('ALTER TABLE psa_type_couleur_site ADD CONSTRAINT FK_46C6250E11D3633A FOREIGN KEY (ID) REFERENCES psa_type_couleur (ID)');
    }
}
