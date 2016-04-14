<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150505154333 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE psa_type_couleur (ID INT AUTO_INCREMENT NOT NULL, CODE VARCHAR(2) NOT NULL, LABEL_CENTRAL VARCHAR(255) NOT NULL, PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_type_couleur_site (LABEL_LOCAL VARCHAR(255) NOT NULL, ORDER_TYPE INT DEFAULT 1, ID INT NOT NULL, LANGUE_ID INT NOT NULL, SITE_ID INT NOT NULL, INDEX IDX_46C6250E11D3633A (ID), INDEX IDX_46C6250E5622E2C2 (LANGUE_ID), INDEX IDX_46C6250EF1B5AEBC (SITE_ID), PRIMARY KEY(ID, LANGUE_ID, SITE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_type_couleur_site ADD CONSTRAINT FK_46C6250E11D3633A FOREIGN KEY (ID) REFERENCES psa_type_couleur (ID)');
        $this->addSql('ALTER TABLE psa_type_couleur_site ADD CONSTRAINT FK_46C6250E5622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_type_couleur_site ADD CONSTRAINT FK_46C6250EF1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');        
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE psa_type_couleur_site DROP FOREIGN KEY FK_46C6250E11D3633A');
        $this->addSql('DROP TABLE psa_type_couleur');
        $this->addSql('DROP TABLE psa_type_couleur_site');        
    }
}
