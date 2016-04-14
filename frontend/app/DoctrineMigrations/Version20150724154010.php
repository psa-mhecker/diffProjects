<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150724154010 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_MSG_ERREUR_NO_MODEL', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
           ('NDP_MSG_ERREUR_NO_MODEL', 1, 1, 'Veuillez renseigner un Modèle / Regroupement de silhouettes.')
            ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM psa_label WHERE LABEL_ID = 'NDP_MSG_ERREUR_NO_MODEL' ");
        $this->addSql("DELETE FROM psa_label_langue_site WHERE LABEL_ID = 'NDP_MSG_ERREUR_NO_MODEL'");
    }
}
