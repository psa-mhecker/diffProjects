<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150611123030 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
       // nettoyage traduction nom tranche
        $this->addSql('DELETE FROM  psa_label_langue_site WHERE LABEL_ID  like "%pc39%"');
        $this->addSql('DELETE FROM  psa_label WHERE LABEL_ID  like "%pc39%"');
        // insertion du vrai label
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_PC39_SLIDESHOW_OFFRE', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_PC39_SLIDESHOW_OFFRE', 1, 1, 'Slideshow_ratio 16/9 or medium rectangle_content')
            ");


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {


        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN (
            "NDP_PC39_SLIDESHOW_OFFRE"
            )');
        }

    }
}
