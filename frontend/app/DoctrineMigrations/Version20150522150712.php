<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150522150712 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_MSG_IFRAME_DISPLAY_CONDITION', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_IFRAME_WEB', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_IFRAME_MOBILE', NULL, 2, NULL, NULL, 1, NULL)
            ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_MSG_IFRAME_DISPLAY_CONDITION', 1, 1, 'L’iFrame ne s’affichera que pour les devices pour lesquels une URL a été renseignée.'),
                ('NDP_IFRAME_WEB', 1, 1, 'iFrame web'),
                ('NDP_IFRAME_MOBILE', 1, 1, 'iFrame mobile')
            ");
    }

    
    
    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                "DELETE FROM `".$table."`  WHERE  `LABEL_ID` IN
                 (
                 'NDP_MSG_IFRAME_DISPLAY_CONDITION',
                 'NDP_IFRAME_WEB',
                 'NDP_IFRAME_MOBILE'
                 )
                "
            );
        }
    }
}
