<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150717145228 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE IF EXISTS psa_appli_mobile');
        $this->addSql('CREATE TABLE psa_appli_mobile (APPMOBILE_ID INT NOT NULL AUTO_INCREMENT, APPMOBILE_LABEL_BO VARCHAR(255) NOT NULL, APPMOBILE_LABEL VARCHAR(255) NOT NULL, MEDIA_ID INT NULL, APPMOBILE_URL_VISUEL VARCHAR(255) DEFAULT NULL, APPMOBILE_MODE_OUVERTURE VARCHAR(255) NOT NULL, APPMOBILE_URL_GOOGLEPLAY VARCHAR(255) DEFAULT NULL, APPMOBILE_URL_APPLESTORE VARCHAR(255) DEFAULT NULL, APPMOBILE_URL_WINDOWS VARCHAR(255) DEFAULT NULL, APPMOBILE_TEXTE VARCHAR(255) DEFAULT NULL, LANGUE_ID INT NOT NULL, SITE_ID INT NOT NULL, INDEX IDX_8D97E1B35622E2C2 (LANGUE_ID), INDEX IDX_8D97E1B3F1B5AEBC (SITE_ID), PRIMARY KEY(APPMOBILE_ID, LANGUE_ID, SITE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_appli_mobile ADD CONSTRAINT FK_8D97E1B35622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_appli_mobile ADD CONSTRAINT FK_8D97E1B3F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_LABEL_BO', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_LABEL_FO', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_URL_VISUEL', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_URL_GOOGLE_PLAY', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_URL_APPLE_STORE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_URL_WINDOWS', NULL, 2, NULL, NULL, 1, NULL)
            ");
        
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_LABEL_BO', 1, 1, 'Libellé BO'),
            ('NDP_LABEL_FO', 1, 1, 'Libellé FO'),
            ('NDP_URL_VISUEL', 1, 1, 'URL Visuel'),
            ('NDP_URL_GOOGLE_PLAY', 1, 1, 'Url Google Play'),
            ('NDP_URL_APPLE_STORE', 1, 1, 'Url Apple Store'),
            ('NDP_URL_WINDOWS', 1, 1, 'Url Windows')
           ");


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE psa_appli_mobile');
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_LABEL_BO",
                "NDP_LABEL_FO",
                "NDP_URL_VISUEL",
                "NDP_URL_GOOGLE_PLAY",
                "NDP_URL_APPLE_STORE",
                "NDP_URL_WINDOWS"
                )
            ');
        }
    }
}
