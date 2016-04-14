<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150910095937 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ("NDP_DISCOVER_THE", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_ACCORDING_FINISHING ", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_SERIES ", NULL, 2, NULL, NULL, NULL, 1),
              ("NDP_OPTIONAL ", NULL, 2, NULL, NULL, NULL, 1)
              '
        );
        // activé trad FO pour NDP_FINISHING
        $this->addSql('UPDATE psa_label SET LABEL_FO=1 WHERE  LABEL_ID= "NDP_FINISHING"');
        //
        $this->addSql('INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
               ("NDP_DISCOVER_THE", 1,  "Découvrir la ",""),
               ("NDP_ACCORDING_FINISHING ", 1,  "Selon finition",""),
               ("NDP_SERIES ", 1,  "de série",""),
               ("NDP_OPTIONAL ", 1,  "en option",""),
               ("NDP_FINISHING", 1,  "Finitions ","")
               '
        );

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label', 'psa_label_langue');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE `LABEL_ID` IN
                (
                    "NDP_DISCOVER_THE",
                    "NDP_ACCORDING_FINISHING",
                    "NDP_SERIES",
                    "NDP_OPTIONAL"
                )'
            );
        }
        //NDP_FINISHING utilisé en BO DONC on l'efface pas de psa_label
        $this->addSql(
            'DELETE FROM `psa_label_langue`  WHERE `LABEL_ID` IN
                (
                    "NDP_FINISHING"
                )'
        );
        $this->addSql('UPDATE psa_label SET LABEL_FO=null WHERE  LABEL_ID="NDP_FINISHING"');
    }
}
