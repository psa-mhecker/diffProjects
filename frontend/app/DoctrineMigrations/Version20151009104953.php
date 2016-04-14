<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151009104953 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $labels = array('NDP_STREAMLIKE','NDP_CNT_ACTU','NDP_CNT_CAMPAGNE','NDP_CNT_ENGAGEMENT','NDP_CNT_FAQ','NDP_CNT_FORM','NDP_CNT_PDV','NDP_CNT_PROMOTION','NDP_CNT_SLIDESHOW','NDP_HT','NDP_TTC','NDP_MONTHLY','NDP_CASH','GTM_ID','PAYS_CIBLE ','NDP_ACCESORIES_PARAMS','NDP_ACCESORIES_VISU');
        foreach ($labels as $label) {
            $this->addSql("REPLACE INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                        ('$label', NULL, 2, NULL, NULL, 1, NULL)
            ");
        }
        
        $this->addSql("REPLACE INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
              ('NDP_STREAMLIKE', 1, 1, 'Streamlike'),
              ('NDP_STREAMLIKE', 2, 1, 'Streamlike'),
              ('NDP_CNT_ACTU', 1, 1, 'Contenu Actualités'),
              ('NDP_CNT_ACTU', 2, 1, 'News content'),
              ('NDP_CNT_CAMPAGNE', 1, 1, 'Contenu Campagne'),
              ('NDP_CNT_CAMPAGNE', 2, 1, 'Campaign content'),
              ('NDP_CNT_ENGAGEMENT', 1, 1, 'Contenu Engagement'),
              ('NDP_CNT_ENGAGEMENT', 2, 1, 'Commitment content'),
              ('NDP_CNT_FAQ', 1, 1, 'Contenu FAQ'),
              ('NDP_CNT_FAQ', 2, 1, 'FAQ Content'),
              ('NDP_CNT_FORM', 1, 1, 'Contenu Formulaire'),
              ('NDP_CNT_FORM', 2, 1, 'Form content'),
              ('NDP_CNT_PDV', 1, 1, 'Contenu PDV'),
              ('NDP_CNT_PDV', 2, 1, 'PDV content'),
              ('NDP_CNT_PROMOTION', 1, 1, 'Contenu Promotion'),
              ('NDP_CNT_PROMOTION', 2, 1, 'Promotion content'),
              ('NDP_CNT_SLIDESHOW', 1, 1, 'Contenu Slideshow'),
              ('NDP_CNT_SLIDESHOW', 2, 1, 'Slideshow content'),
              ('NDP_HT', 1, 1, 'HT'),
              ('NDP_HT', 2, 1, 'excl TAX'),
              ('NDP_TTC', 1, 1, 'TTC'),
              ('NDP_TTC', 2, 1, 'incl TAX'),
              ('NDP_MONTHLY', 1, 1, 'Mensuel'),
              ('NDP_MONTHLY', 2, 1, 'Monthly'),
              ('NDP_CASH', 1, 1, 'Comptant'),
              ('NDP_CASH', 2, 1, 'Cash'),
              ('GTM_ID', 1, 1, 'GTM ID'),
              ('GTM_ID', 2, 1, 'GTM ID'),
              ('PAYS_CIBLE', 1, 1, 'Pays cible'),
              ('PAYS_CIBLE', 2, 1, 'Target country'),
              ('NDP_ACCESORIES_PARAMS', 1, 1, 'Paramètres Accessoires'),
              ('NDP_ACCESORIES_PARAMS', 2, 1, 'Settings Accessories'),
              ('NDP_ACCESORIES_VISU', 1, 1, 'Visuels Accessoires'),
              ('NDP_ACCESORIES_VISU', 2, 1, 'Visuals Accessories')              
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
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                 "NDP_STREAMLIKE","NDP_CNT_ACTU","NDP_CNT_CAMPAGNE","NDP_CNT_ENGAGEMENT","NDP_CNT_FAQ","NDP_CNT_FORM","NDP_CNT_PDV","NDP_CNT_PROMOTION","NDP_CNT_SLIDESHOW","NDP_HT","NDP_TTC","NDP_MONTHLY","NDP_CASH","GTM_ID","PAYS_CIBLE ","NDP_ACCESORIES_PARAMS","NDP_ACCESORIES_VISU"
                )
            ');
        }
    }
}
