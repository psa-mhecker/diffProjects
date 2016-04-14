<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151021143553 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
            ('NDP_CACHE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_CACHE_SPECIAL_VALUES', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_NO_CACHE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MAXIMUM_CACHE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_DEFAULT_CACHE', NULL, 2, NULL, NULL, 1, NULL)
        ");
        $this->addSql("INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
            ('NDP_CACHE', 1, 1, 'Cache en secondes'),
            ('NDP_CACHE_SPECIAL_VALUES', 1, 1, 'Valeurs spéciales du cache'),
            ('NDP_NO_CACHE', 1, 1, 'Pas de cache'),
            ('NDP_MAXIMUM_CACHE', 1, 1, 'Durée de cache maximum'),
            ('NDP_DEFAULT_CACHE', 1, 1, 'Valeur par defaut du cache définit dans les paramètres globaux du projet')
        ");

        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_CACHE_TIME` = -2;");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_CACHE",
                "NDP_CACHE_SPECIAL_VALUES",
                "NDP_NO_CACHE",
                "NDP_MAXIMUM_CACHE",
                "NDP_DEFAULT_CACHE"
                )
            ');
        }

        $this->addSql("UPDATE `psa_zone_template` SET `ZONE_CACHE_TIME` = 60;");
    }
}
