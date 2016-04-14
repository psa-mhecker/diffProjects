<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150408161010 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs


        $this->addSql("delete FROM psa_contenu_recommande");
        $this->addSql("delete FROM psa_content_zone_multi");
        $this->addSql("delete FROM psa_content_version_media");
        $this->addSql("delete FROM psa_content_version");
        $this->addSql("delete FROM psa_content");
        $this->addSql("delete FROM psa_content_category_category");
        $this->addSql("delete FROM psa_content_category");
        $this->addSql("delete FROM psa_user_role where content_type_id > 5");
        $this->addSql("delete FROM psa_content_type_site");
        $this->addSql("delete FROM psa_content_type where content_type_id > 5");


        $this->addSql("UPDATE  psa_content_type SET CONTENT_TYPE_LABEL = 'NDP_CNT_ENGAGEMENT', TEMPLATE_ID= 72 WHERE CONTENT_TYPE_ID =2");
        $this->addSql("UPDATE  psa_content_type SET CONTENT_TYPE_LABEL = 'NDP_CNT_SLIDESHOW', TEMPLATE_ID= 73  WHERE CONTENT_TYPE_ID =3");
        $this->addSql("UPDATE  psa_content_type SET CONTENT_TYPE_LABEL = 'NDP_CNT_PDV', TEMPLATE_ID= 70  WHERE CONTENT_TYPE_ID =4");
        $this->addSql("UPDATE  psa_content_type SET CONTENT_TYPE_LABEL = 'NDP_CNT_ACTU', TEMPLATE_ID= 74  WHERE CONTENT_TYPE_ID =5");

        $this->addSql("INSERT INTO psa_content_type_site (CONTENT_TYPE_ID, SITE_ID, CONTENT_TYPE_SITE_EMISSION, CONTENT_TYPE_SITE_RECEPTION, CONTENT_ALERTE, CONTENT_ALERTE_URL) VALUES
            ('2', '2', NULL, NULL, NULL, NULL),
            ('3', '2', NULL, NULL, NULL, NULL),
            ('4', '2', NULL, NULL, NULL, NULL),
            ('5', '2', NULL, NULL, NULL, NULL)
        ");

        $this->addSql("delete from psa_template where template_group_id = 2 and template_id not in (70, 72, 73, 74)");

        $this->addSql("UPDATE psa_template SET TEMPLATE_LABEL = 'NDP_CNT_PDV', TEMPLATE_PATH= 'Cms_Content_Ndp_Pc47Pointdevente', TEMPLATE_PATH_FO='' WHERE TEMPLATE_ID =70");
        $this->addSql("UPDATE psa_template SET TEMPLATE_LABEL = 'NDP_CNT_ENGAGEMENT', TEMPLATE_PATH= 'Cms_Content_Ndp_Pt19Engagements', TEMPLATE_PATH_FO='' WHERE TEMPLATE_ID =72");
        $this->addSql("UPDATE psa_template SET TEMPLATE_LABEL = 'NDP_CNT_SLIDESHOW', TEMPLATE_PATH= 'Cms_Content_Ndp_Pc33Slideshow', TEMPLATE_PATH_FO='' WHERE TEMPLATE_ID =73");
        $this->addSql("UPDATE psa_template SET TEMPLATE_LABEL = 'NDP_CNT_ACTU', TEMPLATE_PATH= 'Cms_Content_Ndp_Pc42Actualite', TEMPLATE_PATH_FO='' WHERE TEMPLATE_ID =74");

        $this->addSql("delete from psa_user_role where USER_LOGIN = 'admin' and SITE_ID = 2 and CONTENT_TYPE_ID in (2, 3, 4, 5)");

        $this->addSql("INSERT INTO psa_user_role (USER_LOGIN, ROLE_ID, CONTENT_TYPE_ID, SITE_ID) VALUES
            ('admin', 7, 2, 2),
            ('admin', 7, 3, 2),
            ('admin', 7, 4, 2),
            ('admin', 7, 5, 2)
            ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
