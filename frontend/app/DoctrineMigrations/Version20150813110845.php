<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150813110845 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE psa_reseau_social ADD TWITTER_CONSUMMER_KEY VARCHAR(255) DEFAULT NULL, ADD TWITTER_CONSUMMER_SECRET VARCHAR(255) DEFAULT NULL, ADD TWITTER_ACCESS_TOKEN VARCHAR(255) DEFAULT NULL, ADD TWITTER_ACCESS_TOKEN_SECRET VARCHAR(255) DEFAULT NULL, ADD APP_ID VARCHAR(255) DEFAULT NULL, ADD APP_ID_SECRET VARCHAR(255) DEFAULT NULL, ADD YOUTUBE_API_KEY VARCHAR(255) DEFAULT NULL, CHANGE RESEAU_SOCIAL_ID RESEAU_SOCIAL_ID INT NOT NULL');
        $this->addSql('ALTER TABLE psa_reseau_social CHANGE RESEAU_SOCIAL_ID RESEAU_SOCIAL_ID INT( 11 ) NOT NULL AUTO_INCREMENT');
        $this->addSql('INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                ("NDP_BO_IMAGE_PAR_DEFAUT", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_BO_PICTO", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_CONSUMMER_KEY", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_CONSUMMER_SECRET", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_ACCESS_TOKEN", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_ACCESS_TOKEN_SECRET", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_APP_ID", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_APP_ID_SECRET", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_API_KEY", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_POSTED", NULL, 2, NULL, NULL, 1, 1),
                ("NDP_POSTED_SECOND", NULL, 2, NULL, NULL, NULL, 1),
                ("NDP_POSTED_MINUTE", NULL, 2, NULL, NULL, NULL, 1),
                ("NDP_POSTED_HOUR", NULL, 2, NULL, NULL, NULL, 1),
                ("NDP_POSTED_DAY", NULL, 2, NULL, NULL, NULL, 1),
                ("NDP_POSTED_SECONDS", NULL, 2, NULL, NULL, NULL, 1),
                ("NDP_POSTED_MINUTES", NULL, 2, NULL, NULL, NULL, 1),
                ("NDP_POSTED_HOURS", NULL, 2, NULL, NULL, NULL, 1),
                ("NDP_POSTED_DAYS", NULL, 2, NULL, NULL, NULL, 1)
                ');
        $this->addSql('INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                ("NDP_BO_IMAGE_PAR_DEFAUT", 1, 1, "Image par défaut"),
                ("NDP_BO_PICTO", 1, 1, "Picto"),
                ("NDP_CONSUMMER_KEY", 1, 1, "Twitter Consummer Key"),
                ("NDP_CONSUMMER_SECRET", 1, 1, "Twitter Consummer Secret"),
                ("NDP_ACCESS_TOKEN", 1, 1, "Twitter Access Token"),
                ("NDP_ACCESS_TOKEN_SECRET", 1, 1, "Twitter Access Token Secret"),
                ("NDP_APP_ID", 1, 1, "Client Id"),
                ("NDP_APP_ID_SECRET", 1, 1, "Client Secret"),
                ("NDP_API_KEY", 1, 1, "Youtube API Key")
        ');
        $this->addSql("INSERT INTO psa_label_langue (LABEL_ID, LANGUE_ID, LABEL_TRANSLATE, LABEL_PATH) VALUES
                ('NDP_POSTED', 1, 'Posté il y a', ''),
                ('NDP_POSTED_SECOND', 1, 'seconde', ''),
                ('NDP_POSTED_SECONDS', 1, 'secondes', ''),
                ('NDP_POSTED_MINUTE', 1, 'minute', ''),
                ('NDP_POSTED_MINUTES', 1, 'minutes', ''),
                ('NDP_POSTED_HOUR', 1, 'heure', ''),
                ('NDP_POSTED_HOURS', 1, 'heures', ''),
                ('NDP_POSTED_DAY', 1, 'jour', ''),
                ('NDP_POSTED_DAYS', 1, 'jours', '')
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE psa_reseau_social DROP TWITTER_CONSUMMER_KEY, DROP TWITTER_CONSUMMER_SECRET, DROP TWITTER_ACCESS_TOKEN, DROP TWITTER_ACCESS_TOKEN_SECRET, DROP APP_ID, DROP APP_ID_SECRET, DROP YOUTUBE_API_KEY, CHANGE RESEAU_SOCIAL_ID RESEAU_SOCIAL_ID INT AUTO_INCREMENT NOT NULL');
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
             (
             "NDP_BO_PICTO",
             "NDP_BO_IMAGE_PAR_DEFAUT",
             "NDP_CONSUMMER_KEY",
             "NDP_CONSUMMER_SECRET",
             "NDP_ACCESS_TOKEN",
             "NDP_ACCESS_TOKEN_SECRET",
             "NDP_APP_ID",
             "NDP_APP_ID_SECRET",
             "NDP_API_KEY",
             "NDP_POSTED",
             "NDP_POSTED_SECOND",
             "NDP_POSTED_SECONDS",
             "NDP_POSTED_HOUR",
             "NDP_POSTED_HOURS",
             "NDP_POSTED_MINUTE",
             "NDP_POSTED_MINUTES",
             "NDP_POSTED_DAY",
             "NDP_POSTED_DAYS"
             )
        ');
        }
        $tables = array('psa_label_langue');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
             (
             "NDP_POSTED",
             "NDP_POSTED_SECOND",
             "NDP_POSTED_SECONDS",
             "NDP_POSTED_MINUTE",
             "NDP_POSTED_MINUTES",
             "NDP_POSTED_HOUR",
             "NDP_POSTED_HOURS",
             "NDP_POSTED_DAY",
             "NDP_POSTED_DAYS"
             )
        ');
        }
        $this->addSql('ALTER TABLE psa_reseau_social CHANGE RESEAU_SOCIAL_ID RESEAU_SOCIAL_ID INT( 11 ) NOT NULL');
    }
}
