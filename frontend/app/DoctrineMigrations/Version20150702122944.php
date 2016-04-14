<?php namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150702122944 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        
        $this->down($schema); 
        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_GLOBAL_PAGE_NOT_PUBLISHED", NULL, 2, NULL, NULL, 1, NULL),
            ("ATTENTION_AUTRE_NAVIGATEUR_OUVERT_SUR_AUTRE_SITE", NULL, 2, NULL, NULL, 1, NULL),
            ("VEULLIEZ_RAFRAICHIR_PAGE", NULL, 2, NULL, NULL, 1, NULL)'
        );
        $this->addSql('INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ("NDP_GLOBAL_PAGE_NOT_PUBLISHED", 1, 1, "Vous devez tout d\'abord configurer et publier une page globale."),
            ("NDP_GLOBAL_PAGE_NOT_PUBLISHED", 2, 1, "First, You must pusblish a global page."),
            ("VEULLIEZ_RAFRAICHIR_PAGE", 1, 1, "Vous devez rafraichir la page."),
            ("VEULLIEZ_RAFRAICHIR_PAGE", 2, 1, "You need to refresh this page."),
            ("ATTENTION_AUTRE_NAVIGATEUR_OUVERT_SUR_AUTRE_SITE", 1, 1, "Attention votre navigateur est ouvert également sur une autre interface d\'administration"),
            ("ATTENTION_AUTRE_NAVIGATEUR_OUVERT_SUR_AUTRE_SITE", 2, 1, "Be carful , you web browser is opened on another bakend interface")'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE  `LABEL_ID` IN
                (
                    "NDP_GLOBAL_PAGE_NOT_PUBLISHED",
                    "VEULLIEZ_RAFRAICHIR_PAGE",
                    "ATTENTION_AUTRE_NAVIGATEUR_OUVERT_SUR_AUTRE_SITE"
                )'
            );
        }
    }
}
