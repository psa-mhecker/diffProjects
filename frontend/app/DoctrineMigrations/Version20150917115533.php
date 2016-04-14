<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150917115533 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $tables = array('psa_categ_vehicule','psa_form_multi','psa_gtm','psa_label_langue_site','psa_liste_webservices','psa_perso_indicateur','psa_perso_product','psa_perso_product_media','psa_perso_product_page','psa_perso_product_term','psa_perso_profile','psa_perso_profile_page','psa_selection_vehicules','psa_site_personnalisation','psa_site_webservice','psa_vehicule_couleur_auto','psa_youtube','temp_label');
        foreach ($tables as $table) {
            $this->addSql('ALTER TABLE `'.$table.'`  ENGINE=INNODB');
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        
    }
}
