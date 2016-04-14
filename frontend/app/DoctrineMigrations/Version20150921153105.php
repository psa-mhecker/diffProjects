<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150921153105 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ('NDP_PAGE_GENERALE', null, 2, null, null, 1, null)
              ");

        $this->addSql("REPLACE INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
              ('NDP_PAGE_GENERALE', 1, 1, 'Général'),
              ('NDP_PAGE_GENERALE', 2, 1, 'General'),
              ('FORM_MSG_LIST_SELECTED', 1, 1, 'Valeurs sélectionnées'),
              ('SELECT_CTA', 1, 1, 'Sélectionner un CTA'),
              ('PHONE', 1, 1, 'Téléphone'),
              ('CLEAR_URL_USED', 1, 1, 'L\'URL claire est déjà utilisée sur ##param0##'),
              ('CHANGE_CLEAR_URL', 1, 1, 'Voulez-vous mettre à jour l\'URL claire'),
              ('SEO', 1, 1, 'Référencement'),
              ('META_KEYWORDS', 1, 1, 'Mots-clés'),
              ('NDP_MODEL_GRP_SILH', 1, 1, 'Modèle/regroupement de silhouettes'),
              ('DISABLED', 1, 1, 'Désactivé'),
              ('NDP_ZONE_PUSH_MOBILE', 1, 1, 'Zone push application mobile'),
              ('NDP_MSG_PUSH_MOBILE_DISPLAY_CONDITION', 1, 1, 'La zone de push application mobile s\'affiche uniquement sur desktop.'),
              ('NDP_COLONNE', 1, 1, 'colonne'),
              ('NDP_COLONNES', 1, 1, 'colonnes')
              ");
        $this->addSql("UPDATE `psa-ndp`.`psa_page_version` SET `PAGE_TITLE_BO`='Général' WHERE PAGE_TITLE_BO LIKE '%PAGE_GENERAL%'");
        $this->addSql("UPDATE `psa-ndp`.`psa_label_langue_site` SET LABEL_TRANSLATE = REPLACE(LABEL_TRANSLATE, 'Url', 'URL')");
        $this->addSql("UPDATE `psa-ndp`.`psa_label_langue_site` SET LABEL_TRANSLATE = REPLACE(LABEL_TRANSLATE, 'url', 'URL')");
        $this->addSql("UPDATE `psa-ndp`.`psa_label_langue_site` SET LABEL_TRANSLATE = REPLACE(LABEL_TRANSLATE, 'html', 'HTML')");
        $this->addSql("UPDATE `psa-ndp`.`psa_label_langue_site` SET LABEL_TRANSLATE = REPLACE(LABEL_TRANSLATE, 'Html', 'HTML')");
        
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
                    "NDP_PAGE_GENERALE"
                )'
            );
        }
    }
}
