<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150611120318 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE psa_carselectorfilter ADD PRICE_GAUGE_MONTHLY FLOAT NOT NULL');
        $this->addSql("UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Valeur max du volume' WHERE LABEL_ID ='NDP_FILTER_VOLUME_MAXVALUE' AND SITE_ID = 1 AND LANGUE_ID = 1");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE psa_carselectorfilter DROP PRICE_GAUGE_MONTHLY');
    }
}
