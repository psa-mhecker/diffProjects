<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150626110430 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('
        UPDATE psa_label_langue_site
        SET  LABEL_TRANSLATE = "Ajout maximum de 20 véhicules issus du référentiel modèle dans la rubrique Gestionnaire de véhvicule > Affichage des véhicules > Modèles du BO. Sur le mobile seul les 9 premiers véhicules seront affichés."
        WHERE LABEL_ID = "NDP_CARPICKER_VEHICULES_INFO"
          ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

    }
}
