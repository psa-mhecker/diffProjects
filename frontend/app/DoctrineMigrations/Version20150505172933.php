<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150505172933 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $listeTradToDel = array(
            'COMPARER_AVEC_UN_AUTRE_MODELE_CITROEN',
            'GAMME_LIGNE_C_FO',
            'GAMME_LIGNE_DS_FO',
            'GAMME_VEHICULE_UTILITAIRE_FO'
            );
        foreach($listeTradToDel as $label_id){
            $this->addSql('DELETE FROM psa_label where LABEL_ID = "'.$label_id.'"');
            $this->addSql('DELETE FROM psa_label_langue_site where LABEL_ID = "'.$label_id.'"');
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
