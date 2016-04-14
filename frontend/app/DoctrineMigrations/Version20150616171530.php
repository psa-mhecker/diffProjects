<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150616171530 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //alter table user_customer_permission drop primary key;
        $this->addSql("ALTER TABLE psa_model CHANGE id ID INT AUTO_INCREMENT NOT NULL");
        $this->addSql('ALTER TABLE psa_model ADD UNIQUE INDEX UNIQ_F5DB39BF68469EFD (LCDV4)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE psa_model CHANGE ID id INT NOT NULL");
        $this->addSql('DROP INDEX uniq_f5db39bf68469efd ON psa_model');
    }
}
