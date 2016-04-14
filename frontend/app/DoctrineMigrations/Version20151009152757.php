<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151009152757 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM psa_ws_gdg_model_silhouette_upselling");
        $this->addSql("DELETE FROM psa_ws_gdg_model_silhouette_site");
        $this->addSql("DELETE FROM psa_ws_gdg_model_silhouette_angle");
        $this->addSql("DELETE FROM psa_model_view_angle");
        $this->addSql("DELETE FROM psa_ws_gdg_model_silhouette");
        

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
