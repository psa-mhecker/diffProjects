<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150612093924 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
   $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_LEVEL', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_ADD_FORM_CTA_LD', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_ADD_CTA_LISTE_DEROULANTE', NULL, 2, NULL, NULL, 1, NULL)");
  
   $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_LEVEL', 1, 1, 'Niveau'),
            ('NDP_ADD_FORM_CTA_LD', 1, 1, 'Ajouter un CTA dans le niveau' ),
            ('NDP_ADD_CTA_LISTE_DEROULANTE',  1, 1, 'Ajouter un CTA liste déroulante')");
  
    
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
  $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN (
            "NDP_LEVEL",
            "NDP_ADD_FORM_CTA_LD",
            "NDP_ADD_CTA_LISTE_DEROULANTE"
         )');
        }
    }
}
