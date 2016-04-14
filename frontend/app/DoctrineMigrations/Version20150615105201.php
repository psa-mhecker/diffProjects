<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150615105201 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // Pc38
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
        ("NDP_HOWEVER_YOU_CAN", NULL, 2, NULL, NULL, NULL, 1),
        ("NDP_SEARCH_ON_SITE", NULL, 2, NULL, NULL, NULL, 1),
        ("NDP_ERROR_404" ,NULL, 2, NULL, NULL, NULL, 1)
        ');

          $this->addSql('INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
        ("NDP_HOWEVER_YOU_CAN", 1, 1,"Cependant vous pouvez"),
        ("NDP_RESEARCH_ON_SITE", 1, 1,"Rechercher sur le site"),
        ("NDP_ERROR_404" , 1, 1,"ERREUR 404")
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                 ("NDP_HOWEVER_YOU_CAN",
                 "NDP_SEARCH_ON_SITE",
                 "NDP_ERROR_404"
                 )
           ' );
        }

    }
}

