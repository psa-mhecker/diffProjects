<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150716153902 extends AbstractMigration
{
    /**
     * @param Schema $schema                  	
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_CONNECTED_SERVICES', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_CONNECTED_SERVICES_MAX_LIMIT', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MSG_CONNECTED_SERVICES_WITH_VISUAL_ONLY', NULL, 2 , NULL, NULL, 1, NULL)
            ");
       $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_CONNECTED_SERVICES', 1, 1, 'Services connectés'),
            ('NDP_CONNECTED_SERVICES_MAX_LIMIT', 1, 1, 'Maximum de services connectés'),
            ('NDP_MSG_CONNECTED_SERVICES_WITH_VISUAL_ONLY', 1, 1, 'Seuls les services connectés qui ont un visuel application sont affichés dans la liste.')
            ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN ("NDP_CONNECTED_SERVICES","NDP_CONNECTED_SERVICES_MAX_LIMIT", "NDP_MSG_CONNECTED_SERVICES_WITH_VISUAL_ONLY")');
        }
    }
}
