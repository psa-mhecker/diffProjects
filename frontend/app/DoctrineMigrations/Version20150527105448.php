<?php namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150527105448 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE  psa_label_langue_site SET LABEL_TRANSLATE="Cartographie",LABEL_ID="MAP" WHERE LABEL_ID="map" AND LANGUE_ID=1');
        $this->addSql("UPDATE  psa_label SET LABEL_ID='MAP' WHERE LABEL_ID='map' ");
        $this->addSql("UPDATE  psa_label_langue_site SET LABEL_TRANSLATE='Services tiers' WHERE LABEL_ID='SERVICES'");
        $this->addSql('UPDATE  psa_label_langue_site SET LABEL_TRANSLATE="Services tiers" WHERE LABEL_ID="SERVICES" AND LANGUE_ID=1');
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_STREAMLIKE ', NULL, 2, NULL, NULL, 1, NULL),
                ('ENABLE_STREAMLIKE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_SERVICE_MAP_GOOGLE_CONSUMER_KEY', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_SERVICE_MAP_GOOGLE_CLIENT_ID', NULL, 2, NULL, NULL, 1, NULL)
                ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_STREAMLIKE', 1, 1, 'Streamlike'),
                 ('ENABLE_STREAMLIKE', 1, 1, 'Activer le service Streamlike'),
                 ('NDP_SERVICE_MAP_GOOGLE_CONSUMER_KEY', 1, 1, 'Google Private Key'),
                ('NDP_SERVICE_MAP_GOOGLE_CLIENT_ID', 1, 1, 'Google Client ID')");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE  psa_label_langue_site SET LABEL_TRANSLATE="Map",LABEL_ID="map" WHERE LABEL_ID="MAP" AND LANGUE_ID=1');
        $this->addSql("UPDATE  psa_label SET LABEL_ID='map' WHERE LABEL_ID='MAP' ");
        $this->addSql("UPDATE  psa_label_langue_site SET LABEL_TRANSLATE='Services' WHERE LABEL_ID='SERVICES'");
        $this->addSql('UPDATE  psa_label_langue_site SET LABEL_TRANSLATE="Service" WHERE LABEL_ID="SERVICES" AND LANGUE_ID=1');
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                "DELETE FROM `".$table."`  WHERE  `LABEL_ID` IN
                 (
                 'NDP_STREAMLIKE',
                 'ENABLE_STREAMLIKE',
                 'NDP_SERVICE_MAP_GOOGLE_CONSUMER_KEY',
                 'NDP_SERVICE_MAP_GOOGLE_CLIENT_ID'
           )");
        }
    }
}
