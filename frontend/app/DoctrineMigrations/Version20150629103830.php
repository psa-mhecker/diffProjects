<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150629103830 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        //Mise a jour des nom des templates avec des constantes propre
        $this->addSql('UPDATE psa_template_page set  TEMPLATE_PAGE_LABEL = "NDP_TP_HOMEPAGE" WHERE  TEMPLATE_PAGE_LABEL = "NDP - Homepage"');
        $this->addSql('UPDATE psa_template_page set  TEMPLATE_PAGE_LABEL = "NDP_TP_DEALER_LOCATOR" WHERE  TEMPLATE_PAGE_LABEL = "NDP - Dealer locator"');
        $this->addSql('UPDATE psa_template_page set  TEMPLATE_PAGE_LABEL = "NDP_TP_FAQ" WHERE  TEMPLATE_PAGE_LABEL = "NDP - FAQ"');
        $this->addSql('UPDATE psa_template_page set  TEMPLATE_PAGE_LABEL = "NDP_TP_MASTER_PAGE" WHERE  TEMPLATE_PAGE_LABEL = "NDP - Master page"');
        $this->addSql('UPDATE psa_template_page set  TEMPLATE_PAGE_LABEL = "NDP_TP_PLAN_DU_SITE" WHERE  TEMPLATE_PAGE_LABEL = "NDP - Plan du site"');
        $this->addSql('UPDATE psa_template_page set  TEMPLATE_PAGE_LABEL = "NDP_TP_PREHOME_CHOIX_LANGUE" WHERE  TEMPLATE_PAGE_LABEL = "NDP - Pré-home choix de la langue"');
        $this->addSql('UPDATE psa_template_page set  TEMPLATE_PAGE_LABEL = "NDP_TP_CAR_SELECTOR" WHERE  TEMPLATE_PAGE_LABEL = "NDP - Car Selector"');
        $this->addSql('UPDATE psa_template_page set  TEMPLATE_PAGE_LABEL = "NDP_TP_404" WHERE  TEMPLATE_PAGE_LABEL = "NDP - 404"');

        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            // Mise a jour des trads existante
            $this->addSql('UPDATE `'.$table.'` SET LABEL_ID= "NDP_TP_HOMEPAGE" WHERE LABEL_ID = "NDP - Homepage"');
            $this->addSql('UPDATE `'.$table.'` SET LABEL_ID= "NDP_TP_DEALER_LOCATOR" WHERE LABEL_ID = "NDP - Dealer locator"');
            $this->addSql('UPDATE `'.$table.'` SET LABEL_ID= "NDP_TP_FAQ" WHERE LABEL_ID = "NDP - FAQ"');
            $this->addSql('UPDATE `'.$table.'` SET LABEL_ID= "NDP_TP_MASTER_PAGE" WHERE LABEL_ID = "NDP - Master page"');
            $this->addSql('UPDATE `'.$table.'` SET LABEL_ID= "NDP_TP_PLAN_DU_SITE" WHERE LABEL_ID = "NDP - Plan du site"');
            $this->addSql('UPDATE `'.$table.'` SET LABEL_ID= "NDP_TP_PREHOME_CHOIX_LANGUE" WHERE LABEL_ID = "NDP - Pré-home choix de la langue"');
            $this->addSql('UPDATE `'.$table.'` SET LABEL_ID= "NDP_TP_404" WHERE LABEL_ID = "NDP - 404"');
        }
        // renommage de la tranche pc33
        $this->addSql('UPDATE psa_label_langue_site SET LABEL_TRANSLATE= "PC33 Slideshow_ratio cinemascope or 16/9 _ content" WHERE LABEL_ID = "NDP_PC33_OFFRE_PLUS"');

        // Ajout de la trad pour le car selector
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_TP_CAR_SELECTOR", NULL, 2, NULL, NULL, 1, NULL)
            '
        );
        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ("NDP_TP_CAR_SELECTOR", 1, 1, "Car Selector")
            '
        );
        // placer la pc18 en premier de la zone dynamique
        $this->addSql('UPDATE `psa_zone_template` SET ZONE_TEMPLATE_ORDER = 4 WHERE ZONE_TEMPLATE_ID = 4446');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                    "NDP_TP_CAR_SELECTOR"
                )'
            );
        }
    }
}
