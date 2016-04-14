<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151009155100 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE psa_segmentation_finition_site DROP FOREIGN KEY FK_5906659BFEB7F4BA');
        $this->addSql('ALTER TABLE psa_segmentation_finition_site ADD CONSTRAINT FK_5906659BFEB7F4BA FOREIGN KEY (ID_CENTRAL) REFERENCES psa_segmentation_finition (ID) ON DELETE CASCADE');
        

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE psa_segmentation_finition_site DROP FOREIGN KEY FK_5906659BFEB7F4BA');
        $this->addSql('ALTER TABLE psa_segmentation_finition_site ADD CONSTRAINT FK_5906659BFEB7F4BA FOREIGN KEY (ID_CENTRAL) REFERENCES psa_segmentation_finition (ID)');
    }
}
