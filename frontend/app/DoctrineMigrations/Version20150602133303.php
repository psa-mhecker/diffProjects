<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150602133303 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO psa_template (TEMPLATE_ID, TEMPLATE_TYPE_ID, TEMPLATE_GROUP_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_COMPLEMENT, PLUGIN_ID) VALUES
            (71, 3, 2, 'NDP_CNT_FAQ', 'Cms_Content_Ndp_Pc36Faq', NULL, NULL, '')
            ");

        $this->addSql("INSERT INTO psa_content_type (CONTENT_TYPE_ID, TEMPLATE_ID, CONTENT_TYPE_LABEL, CONTENT_TYPE_COMPLEMENT, CONTENT_TYPE_ADMINISTRATION, CONTENT_TYPE_PAGE, CONTENT_TYPE_DEFAULT, CONTENT_TYPE_PLUGIN) VALUES
            (6, 71, 'NDP_CNT_FAQ', NULL, 0, NULL, NULL, 0)
            ");

        $this->addSql("INSERT INTO psa_content_type_site (CONTENT_TYPE_ID, SITE_ID, CONTENT_TYPE_SITE_EMISSION, CONTENT_TYPE_SITE_RECEPTION, CONTENT_ALERTE, CONTENT_ALERTE_URL) VALUES
            (6, 2, NULL, NULL, NULL, NULL)
            ");

        $this->addSql("INSERT INTO psa_user_role (USER_LOGIN, ROLE_ID, CONTENT_TYPE_ID, SITE_ID) VALUES
            ('admin', 7, 6, 2)
            ");

        $this->addSql("ALTER TABLE psa_content_category ADD  CONTENT_CATEGORY_CODE VARCHAR( 100 ) NULL");

        $this->addSql(
          "INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                  ('NDP_QUESTION', NULL, 2, NULL, NULL, 1, NULL),
                  ('NDP_FAQ_CHECKBOX_MOST_ASKED_QUESTION', NULL, 2, NULL, NULL, 1, NULL),
                  ('NDP_ANSWER', NULL, 2, NULL, NULL, 1, NULL),
                  ('NDP_DISPLAY_SATISFACTION_QUESTION', NULL, 2, NULL, NULL, 1, NULL)
                  "
        );

        $this->addSql(
          "INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                  ('NDP_QUESTION', 1, 1, 'Question'),
                  ('NDP_FAQ_CHECKBOX_MOST_ASKED_QUESTION', 1, 1, 'Remonter la question dans la rubrique « Question les plus posés »'),
                  ('NDP_ANSWER', 1, 1, 'Réponse'),
                  ('NDP_DISPLAY_SATISFACTION_QUESTION', 1, 1, 'Afficher la question de satisfaction')
                  "
        );

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
       $this->addSql('DELETE FROM psa_user_role WHERE CONTENT_TYPE_ID=6');
       $this->addSql('DELETE FROM psa_content_type_site WHERE CONTENT_TYPE_ID=6');
       $this->addSql('DELETE FROM psa_content_type WHERE CONTENT_TYPE_ID=6');
       $this->addSql('DELETE FROM psa_template WHERE TEMPLATE_ID =71');
       $this->addSql('ALTER TABLE psa_content_category DROP CONTENT_CATEGORY_CODE');


        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_QUESTION",
                "NDP_FAQ_CHECKBOX_MOST_ASKED_QUESTION",
                "NDP_ANSWER",
                "NDP_DISPLAY_SATISFACTION_QUESTION"
                )
            ');
        }
    }
}
