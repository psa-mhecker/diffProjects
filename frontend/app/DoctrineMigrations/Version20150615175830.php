<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150615175830 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        // on insert ou on met a jour le template
        $this->addSql("INSERT INTO `psa_template_page` (`TEMPLATE_PAGE_ID`, `SITE_ID`, `PAGE_TYPE_ID`, `TEMPLATE_PAGE_LABEL`, `TEMPLATE_PAGE_GENERAL`) VALUES (376,2,15,'NDP_PF53_ENGINES',NULL)  ON DUPLICATE KEY UPDATE TEMPLATE_PAGE_LABEL='NDP_PF53_ENGINES'");
        // si on met a jour on va effacer toute les bloc et les zone associé a ce template
        $this->addSql('DELETE FROM psa_zone_template WHERE TEMPLATE_PAGE_ID = 376 ');
        $this->addSql('DELETE FROM psa_template_page_area WHERE TEMPLATE_PAGE_ID = 376 ');
        // on ajoute la zone
        $this->addSql("INSERT INTO `psa_template_page_area` (`TEMPLATE_PAGE_ID`, `AREA_ID`, `TEMPLATE_PAGE_AREA_ORDER`, `LIGNE`, `COLONNE`, `LARGEUR`, `HAUTEUR`, `IS_DROPPABLE`) VALUES (376,148,1,1,1,4,1,0)");
        // on ajout le bloc
        $this->addSql("INSERT INTO `psa_zone_template` (`ZONE_TEMPLATE_ID`, `ZONE_TEMPLATE_LABEL`, `TEMPLATE_PAGE_ID`, `AREA_ID`, `ZONE_ID`, `ZONE_TEMPLATE_ORDER`, `ZONE_TEMPLATE_MOBILE_ORDER`, `ZONE_TEMPLATE_TABLET_ORDER`, `ZONE_TEMPLATE_TV_ORDER`, `ZONE_CACHE_TIME`) VALUES (4437,'NDP_PF53_ENGINES',376,148,817,1,1,NULL,NULL,30);");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DELETE FROM psa_zone_template WHERE TEMPLATE_PAGE_ID = 376 ');
        $this->addSql('DELETE FROM psa_template_page_area WHERE TEMPLATE_PAGE_ID = 376 ');
        $this->addSql('DELETE FROM psa_template_page WHERE TEMPLATE_PAGE_ID = 376 ');
    }
}
