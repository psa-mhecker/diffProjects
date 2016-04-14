<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150212135936 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE psa_faq_rubrique_content DROP FOREIGN KEY psa_faq_rubrique_content_ibfk_3');
        $this->addSql('ALTER TABLE psa_vehicule_couleur DROP FOREIGN KEY psa_vehicule_couleur_ibfk_1');
        $this->addSql('ALTER TABLE psa_vehicule_criteres DROP FOREIGN KEY psa_vehicule_criteres_ibfk_1');
        $this->addSql('DROP TABLE psa_categ_vehicule');
        $this->addSql('DROP TABLE psa_faq_rubrique');
        $this->addSql('DROP TABLE psa_faq_rubrique_content');
        $this->addSql('DROP TABLE psa_perso_indicateur');
        $this->addSql('DROP TABLE psa_perso_product');
        $this->addSql('DROP TABLE psa_perso_product_media');
        $this->addSql('DROP TABLE psa_perso_product_page');
        $this->addSql('DROP TABLE psa_perso_product_term');
        $this->addSql('DROP TABLE psa_perso_profile');
        $this->addSql('DROP TABLE psa_site_personnalisation');
        $this->addSql('DROP TABLE psa_vehicule');
        $this->addSql('DROP TABLE psa_vehicule_couleur');
        $this->addSql('DROP TABLE psa_vehicule_couleur_auto');
        $this->addSql('DROP TABLE psa_vehicule_criteres');
        $this->addSql('DROP TABLE psa_ws_caracteristique_detail_moteur');
        $this->addSql('DROP TABLE psa_ws_caracteristique_moteur');
        $this->addSql('DROP TABLE psa_ws_caracteristique_technique');
        $this->addSql('DROP TABLE psa_ws_couleur_finition');
        $this->addSql('DROP TABLE psa_ws_critere_selection');
        $this->addSql('DROP TABLE psa_ws_energie_moteur');
        $this->addSql('DROP TABLE psa_ws_equipement_disponible');
        $this->addSql('DROP TABLE psa_ws_equipement_option');
        $this->addSql('DROP TABLE psa_ws_equipement_standard');
        $this->addSql('DROP TABLE psa_ws_finitions');
        $this->addSql('DROP TABLE psa_ws_modele');
        $this->addSql('DROP TABLE psa_ws_prix_finition_version');
        $this->addSql('DROP TABLE psa_ws_services_pdv');
        $this->addSql('DROP TABLE psa_ws_vehicule_gamme');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE psa_categ_vehicule (CATEG_VEHICULE_ID INT AUTO_INCREMENT NOT NULL, SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, CATEG_VEHICULE_LABEL VARCHAR(255) NOT NULL, CATEG_VEHICULE_ORDER INT DEFAULT NULL, PRIMARY KEY(CATEG_VEHICULE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_faq_rubrique (FAQ_RUBRIQUE_ID INT AUTO_INCREMENT NOT NULL, LANGUE_ID INT NOT NULL, SITE_ID INT DEFAULT NULL, FAQ_RUBRIQUE_LABEL VARCHAR(255) DEFAULT NULL, FAQ_RUBRIQUE_PICTO INT DEFAULT NULL, INDEX psa_faq_rubrique_ibfk_1_idx (LANGUE_ID), INDEX psa_faq_rubrique_ibfk_2_idx (SITE_ID), INDEX psa_faq_rubrique_ibfk_3_idx (FAQ_RUBRIQUE_PICTO), PRIMARY KEY(FAQ_RUBRIQUE_ID, LANGUE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_faq_rubrique_content (FAQ_RUBRIQUE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, CONTENT_ID INT NOT NULL, FAQ_RUBRIQUE_CONTENT_ORDER INT DEFAULT NULL, INDEX psa_faq_rubrique_ibfk_1_idx (FAQ_RUBRIQUE_ID), INDEX psa_faq_rubrique_ibfk_2_idx (LANGUE_ID), INDEX psa_faq_rubrique_ibfk_3_idx (CONTENT_ID), PRIMARY KEY(FAQ_RUBRIQUE_ID, LANGUE_ID, CONTENT_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_perso_indicateur (INDICATEUR_ID INT AUTO_INCREMENT NOT NULL, INDICATEUR_LABEL VARCHAR(255) NOT NULL, PRIMARY KEY(INDICATEUR_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_perso_product (PRODUCT_ID INT AUTO_INCREMENT NOT NULL, SITE_ID INT NOT NULL, PRODUCT_LABEL VARCHAR(255) DEFAULT NULL, VEHICULE_ID INT DEFAULT NULL, INDEX FK_PRODUCT_SITE (SITE_ID), INDEX FK_PRODUCT_VEHICULE (VEHICULE_ID), PRIMARY KEY(PRODUCT_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_perso_product_media (PRODUCT_ID INT NOT NULL, MEDIA_ID INT NOT NULL, PRODUCT_MEDIA_TYPE VARCHAR(255) NOT NULL, ORDER_MEDIA INT NOT NULL, INDEX FK_PRODUCT_MEDIA_MEDIA (MEDIA_ID), PRIMARY KEY(PRODUCT_ID, MEDIA_ID, PRODUCT_MEDIA_TYPE)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_perso_product_page (PRODUCT_PAGE_ID INT AUTO_INCREMENT NOT NULL, SITE_ID INT NOT NULL, PRODUCT_PAGE_URL VARCHAR(255) DEFAULT NULL, PRODUCT_PAGE_SCORE DOUBLE PRECISION NOT NULL, PRODUCT_ID INT NOT NULL, PRODUCT_PAGE_AJAX INT DEFAULT NULL, INDEX FK_PRODUCT_PAGE_SITE (SITE_ID), INDEX FK_PRODUCT_PAGE_PRODUCT (PRODUCT_ID), PRIMARY KEY(PRODUCT_PAGE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_perso_product_term (PRODUCT_TERM_ID INT AUTO_INCREMENT NOT NULL, SITE_ID INT NOT NULL, PRODUCT_TERM_LABEL VARCHAR(255) NOT NULL, PRODUCT_ID INT DEFAULT NULL, PRODUCT_TERM_PRO TINYINT(1) DEFAULT NULL, INDEX FK_PRODUCT_TERM_SITE (SITE_ID), INDEX FK_PRODUCT_TERM_PRODUCT (PRODUCT_ID), PRIMARY KEY(PRODUCT_TERM_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_perso_profile (PROFILE_ID INT AUTO_INCREMENT NOT NULL, PROFILE_LABEL VARCHAR(255) NOT NULL, PROFILE_I18N_KEY VARCHAR(255) DEFAULT NULL COMMENT \'Clé de traduction du nom du profil\', UNIQUE INDEX PROFILE_I18N_KEY (PROFILE_I18N_KEY), PRIMARY KEY(PROFILE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_site_personnalisation (SITE_ID INT NOT NULL, ZONE_ID INT NOT NULL, INDEX FK_SITE_PERSO_ZONE (ZONE_ID), PRIMARY KEY(SITE_ID, ZONE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_vehicule (VEHICULE_ID INT AUTO_INCREMENT NOT NULL, SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, VEHICULE_LABEL VARCHAR(255) DEFAULT NULL, VEHICULE_CATEG_LABEL VARCHAR(255) NOT NULL, VEHICULE_LCDV6_CONFIG VARCHAR(6) DEFAULT NULL, VEHICULE_GAMME_CONFIG VARCHAR(2) DEFAULT NULL, VEHICULE_LCDV6_MANUAL VARCHAR(6) DEFAULT NULL, VEHICULE_GAMME_MANUAL VARCHAR(2) DEFAULT NULL, VEHICULE_GAMME_LABEL VARCHAR(255) DEFAULT NULL, VEHICULE_MEDIA_ID_THUMBNAIL INT DEFAULT NULL, VEHICULE_MEDIA_ID_WEB1 INT DEFAULT NULL, VEHICULE_MEDIA_ID_WEB2 INT DEFAULT NULL, VEHICULE_MEDIA_ID_WEB3 INT DEFAULT NULL, VEHICULE_MEDIA_ID_MOB INT DEFAULT NULL, VEHICULE_DISPLAY_CASH_PRICE INT DEFAULT NULL, VEHICULE_DISPLAY_CREDIT_PRICE INT DEFAULT NULL, VEHICULE_CASH_PRICE VARCHAR(255) DEFAULT NULL, VEHICULE_CASH_PRICE_TYPE VARCHAR(50) DEFAULT NULL, VEHICULE_CASH_PRICE_LEGAL_MENTION LONGTEXT DEFAULT NULL, VEHICULE_USE_FINANCIAL_SIMULATOR INT DEFAULT NULL, VEHICULE_CREDIT_PRICE_NEXT_RENT VARCHAR(255) DEFAULT NULL, VEHICULE_CREDIT_PRICE_NEXT_RENT_LEGAL_MENTION LONGTEXT DEFAULT NULL, VEHICULE_CREDIT_PRICE_FIRST_RENT VARCHAR(255) DEFAULT NULL, VEHICULE_CREDIT_PRICE_FIRST_RENT_LEGAL_MENTION LONGTEXT DEFAULT NULL, VEHICULE_FILTER_TYPE1 INT DEFAULT NULL, VEHICULE_FILTER_TYPE2 INT DEFAULT NULL, VEHICULE_FILTER_TYPE3 INT DEFAULT NULL, CODE_REGROUPEMENT_SILHOUETTE VARCHAR(255) DEFAULT NULL, INDEX SITE_ID (SITE_ID), INDEX LANGUE_ID (LANGUE_ID), INDEX VEHICULE_MEDIA_ID_THUMBNAIL (VEHICULE_MEDIA_ID_THUMBNAIL, VEHICULE_MEDIA_ID_WEB1, VEHICULE_MEDIA_ID_WEB2, VEHICULE_MEDIA_ID_WEB3), INDEX VEHICULE_MEDIA_ID_WEB1 (VEHICULE_MEDIA_ID_WEB1), INDEX VEHICULE_MEDIA_ID_WEB2 (VEHICULE_MEDIA_ID_WEB2), INDEX VEHICULE_MEDIA_ID_WEB3 (VEHICULE_MEDIA_ID_WEB3), INDEX VEHICULE_FILTER_TYPE1 (VEHICULE_FILTER_TYPE1), INDEX VEHICULE_FILTER_TYPE2 (VEHICULE_FILTER_TYPE2), INDEX VEHICULE_FILTER_TYPE3 (VEHICULE_FILTER_TYPE3), INDEX IDX_E79CB2F49CBB3E9F (VEHICULE_MEDIA_ID_THUMBNAIL), PRIMARY KEY(VEHICULE_ID, SITE_ID, LANGUE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_vehicule_couleur (VEHICULE_ID INT NOT NULL, SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, VEHICULE_COULEUR_ORDER INT NOT NULL, VEHICULE_COULEUR_LABEL VARCHAR(100) DEFAULT NULL, VEHICULE_COULEUR_MEDIA_ID_PICTO INT DEFAULT NULL, VEHICULE_COULEUR_CODE VARCHAR(50) DEFAULT NULL, VEHICULE_COULEUR_LCDV6 VARCHAR(16) DEFAULT NULL, VEHICULE_COULEUR_MEDIA_ID_BCKGRD_WEB1 INT DEFAULT NULL, VEHICULE_COULEUR_MEDIA_ID_BCKGRD_WEB2 INT DEFAULT NULL, VEHICULE_COULEUR_MEDIA_ID_BCKGRD_WEB3 INT DEFAULT NULL, VEHICULE_COULEUR_MEDIA_ID_CAR_WEB1 INT DEFAULT NULL, VEHICULE_COULEUR_MEDIA_ID_CAR_WEB2 INT DEFAULT NULL, VEHICULE_COULEUR_MEDIA_ID_CAR_WEB3 INT DEFAULT NULL, VEHICULE_COULEUR_MEDIA_ID_CAR_MOB1 INT DEFAULT NULL, INDEX psa_ws_vehicule_couleur_ibfk_1_idx (VEHICULE_ID), INDEX psa_ws_vehicule_couleur_ibfk_2_idx (SITE_ID), INDEX psa_ws_vehicule_couleur_ibfk_3_idx (LANGUE_ID), PRIMARY KEY(VEHICULE_ID, SITE_ID, LANGUE_ID, VEHICULE_COULEUR_ORDER)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_vehicule_couleur_auto (VEHICULE_ID INT NOT NULL, SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, VEHICULE_COULEUR_ORDER INT NOT NULL, VEHICULE_LINK_BCKGRD_1 VARCHAR(255) DEFAULT NULL, VEHICULE_LINK_BCKGRD_2 VARCHAR(255) DEFAULT NULL, VEHICULE_LINK_BCKGRD_3 VARCHAR(255) DEFAULT NULL, VEHICULE_LINK_TEINTE VARCHAR(255) DEFAULT NULL, VEHICULE_LINK_ANGLE_1 VARCHAR(255) DEFAULT NULL, VEHICULE_LINK_ANGLE_2 VARCHAR(255) DEFAULT NULL, VEHICULE_LINK_ANGLE_3 VARCHAR(255) DEFAULT NULL, VEHICULE_LINK_ANGLE_MOBILE VARCHAR(255) DEFAULT NULL, PRIMARY KEY(VEHICULE_ID, SITE_ID, LANGUE_ID, VEHICULE_COULEUR_ORDER)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_vehicule_criteres (VEHICULE_ID INT NOT NULL, SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, CRITERE_ID INT NOT NULL, INDEX psa_vehicule_criteres_ibfk_1_idx (VEHICULE_ID), INDEX psa_vehicule_criteres_ibfk_4_idx (CRITERE_ID), INDEX psa_vehicule_criteres_ibfk_3_idx (LANGUE_ID), INDEX psa_vehicule_criteres_ibfk_2_idx (SITE_ID), PRIMARY KEY(VEHICULE_ID, LANGUE_ID, SITE_ID, CRITERE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_ws_caracteristique_detail_moteur (SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, CULTURE VARCHAR(255) NOT NULL, GAMME VARCHAR(50) NOT NULL, LCDV6 VARCHAR(50) NOT NULL, ENGINE_CODE VARCHAR(50) NOT NULL, REFERENCE_LCDV VARCHAR(50) NOT NULL, CARACT_KEY VARCHAR(50) NOT NULL, `LABEL` VARCHAR(255) NOT NULL, VALUE VARCHAR(255) DEFAULT NULL, INDEX SITE_ID (SITE_ID), INDEX LANGUE_ID (LANGUE_ID), INDEX LCDV6 (LCDV6), PRIMARY KEY(CULTURE, GAMME, ENGINE_CODE, REFERENCE_LCDV, CARACT_KEY, `LABEL`)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_ws_caracteristique_moteur (SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, CULTURE VARCHAR(255) NOT NULL, GAMME VARCHAR(50) NOT NULL, LCDV6 VARCHAR(50) NOT NULL, ENGINE_CODE VARCHAR(50) NOT NULL, ENGINE_LABEL VARCHAR(255) DEFAULT NULL, ENGINE_DESCRIPTION LONGTEXT DEFAULT NULL, ENERGY_CATEGORY VARCHAR(50) DEFAULT NULL, REFERENCE_LCDV VARCHAR(50) NOT NULL, IS_ECO_LABEL VARCHAR(20) DEFAULT NULL, TRANSMISSION_CODE VARCHAR(255) DEFAULT NULL, TRANSMISSION_LABEL VARCHAR(20) DEFAULT NULL, INDEX SITE_ID (SITE_ID), INDEX LANGUE_ID (LANGUE_ID), INDEX REFERENCE_LCDV (REFERENCE_LCDV), PRIMARY KEY(CULTURE, GAMME, LCDV6, ENGINE_CODE, REFERENCE_LCDV)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_ws_caracteristique_technique (SITE_ID INT DEFAULT NULL, LANGUE_ID INT DEFAULT NULL, CULTURE VARCHAR(255) NOT NULL, GAMME VARCHAR(50) NOT NULL, LCDV6 VARCHAR(50) NOT NULL, CATEGORY_NAME VARCHAR(255) DEFAULT NULL, LCDV_CODE VARCHAR(50) NOT NULL, RANK INT NOT NULL, NAME VARCHAR(255) NOT NULL, VALUE VARCHAR(255) DEFAULT NULL, INDEX SITE_ID (SITE_ID), INDEX LANGUE_ID (LANGUE_ID), INDEX LCDV6 (LCDV6, LCDV_CODE, RANK), PRIMARY KEY(CULTURE, GAMME, LCDV6, LCDV_CODE, RANK, NAME)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_ws_couleur_finition (SITE_ID INT DEFAULT NULL, LANGUE_ID INT DEFAULT NULL, CULTURE VARCHAR(255) NOT NULL, GAMME VARCHAR(50) NOT NULL, LCDV6 VARCHAR(50) NOT NULL, GR_COMMERCIAL_NAME_CODE VARCHAR(50) NOT NULL, CODE VARCHAR(50) NOT NULL, `LABEL` VARCHAR(255) DEFAULT NULL, BODY_COLOR_CODE VARCHAR(50) DEFAULT NULL, BODY_COLOR_LABEL VARCHAR(255) DEFAULT NULL, BODY_COLOR_ORDER INT DEFAULT NULL, BODY_COLOR_PICTO_URL LONGTEXT DEFAULT NULL, ROOF_COLOR_CODE VARCHAR(50) DEFAULT NULL, ROOF_COLOR_LABEL VARCHAR(255) DEFAULT NULL, ROOF_COLOR_ORDER INT DEFAULT NULL, ROOF_COLOR_PICTO_URL LONGTEXT DEFAULT NULL, V3D_CODE VARCHAR(50) DEFAULT NULL, INDEX SITE_ID (SITE_ID), INDEX LANGUE_ID (LANGUE_ID), PRIMARY KEY(CULTURE, GAMME, LCDV6, GR_COMMERCIAL_NAME_CODE, CODE)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_ws_critere_selection (SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, CULTURE VARCHAR(255) NOT NULL, GAMME VARCHAR(50) NOT NULL, LCDV6 VARCHAR(50) NOT NULL, CRIT_ORDER INT NOT NULL, MODEL_LABEL VARCHAR(255) DEFAULT NULL, CRIT_BODY_CODE VARCHAR(20) DEFAULT NULL, CRIT_BODY_LABEL VARCHAR(255) DEFAULT NULL, SEATS INT DEFAULT NULL, CRIT_TR_CODE VARCHAR(50) DEFAULT NULL, CRIT_TR_LABEL VARCHAR(255) DEFAULT NULL, CRIT_MIXEDCONSUMPTION_MIN DOUBLE PRECISION DEFAULT NULL, CRIT_CO2_RATE_MIN INT DEFAULT NULL, CRIT_EXTERIOR_LENGTH_MIN INT DEFAULT NULL, CRIT_PRICE_MIN INT DEFAULT NULL, INDEX SITE_ID (SITE_ID), INDEX LANGUE_ID (LANGUE_ID), PRIMARY KEY(CULTURE, GAMME, LCDV6, CRIT_ORDER)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_ws_energie_moteur (SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, CULTURE VARCHAR(255) NOT NULL, GAMME VARCHAR(50) NOT NULL, LCDV6 VARCHAR(50) NOT NULL, ENERGY_CATEGORY VARCHAR(50) NOT NULL, ENGINE_CODE VARCHAR(50) NOT NULL, TRANSMISSION_CODE VARCHAR(50) NOT NULL, TRANSMISSION_LABEL VARCHAR(50) DEFAULT NULL, `LABEL` VARCHAR(255) DEFAULT NULL, DESCRIPTION VARCHAR(255) DEFAULT NULL, PICTO_URL LONGTEXT DEFAULT NULL, INDEX SITE_ID (SITE_ID), INDEX LANGUE_ID (LANGUE_ID), PRIMARY KEY(CULTURE, GAMME, LCDV6, ENERGY_CATEGORY, ENGINE_CODE, TRANSMISSION_CODE)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_ws_equipement_disponible (SITE_ID INT DEFAULT NULL, LANGUE_ID INT DEFAULT NULL, CULTURE VARCHAR(255) NOT NULL, GAMME VARCHAR(50) NOT NULL, LCDV6 VARCHAR(50) NOT NULL, CATEGORY_NAME VARCHAR(255) NOT NULL, EQUIPEMENT_NAME VARCHAR(255) NOT NULL, LCDV_CODE VARCHAR(50) NOT NULL, GR_COMMERCIAL_NAME_CODE VARCHAR(50) NOT NULL, DISPONIBILITY VARCHAR(255) DEFAULT NULL, INDEX LANGUE_ID (LANGUE_ID), INDEX SITE_ID (SITE_ID), INDEX LCDV6 (LCDV6, LCDV_CODE, GR_COMMERCIAL_NAME_CODE), PRIMARY KEY(CULTURE, GAMME, LCDV6, CATEGORY_NAME, EQUIPEMENT_NAME, LCDV_CODE)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_ws_equipement_option (SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, CULTURE VARCHAR(255) NOT NULL, GAMME VARCHAR(50) NOT NULL, LCDV6 VARCHAR(50) NOT NULL, FINITION_CODE VARCHAR(50) NOT NULL, FINITION_LABEL VARCHAR(255) DEFAULT NULL, CATEGORIE_CODE INT NOT NULL, CATEGORIE_LABEL VARCHAR(50) DEFAULT NULL, CATEGORIE_RANK INT NOT NULL, EQUIPEMENT_CODE VARCHAR(50) NOT NULL, EQUIPEMENT_LABEL VARCHAR(255) DEFAULT NULL, INDEX SITE_ID (SITE_ID), INDEX LANGUE_ID (LANGUE_ID), PRIMARY KEY(CULTURE, GAMME, LCDV6, FINITION_CODE, CATEGORIE_CODE, CATEGORIE_RANK, EQUIPEMENT_CODE)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_ws_equipement_standard (SITE_ID INT DEFAULT NULL, LANGUE_ID INT DEFAULT NULL, CULTURE VARCHAR(255) NOT NULL, GAMME VARCHAR(50) NOT NULL, LCDV6 VARCHAR(50) NOT NULL, FINITION_CODE VARCHAR(50) NOT NULL, FINITION_LABEL VARCHAR(255) DEFAULT NULL, CATEGORIE_CODE INT NOT NULL, CATEGORIE_LABEL VARCHAR(50) DEFAULT NULL, CATEGORIE_RANK INT NOT NULL, EQUIPEMENT_CODE VARCHAR(50) NOT NULL, EQUIPEMENT_LABEL VARCHAR(255) DEFAULT NULL, INDEX SITE_ID (SITE_ID), INDEX LANGUE_ID (LANGUE_ID), PRIMARY KEY(CULTURE, GAMME, LCDV6, FINITION_CODE, CATEGORIE_CODE, CATEGORIE_RANK, EQUIPEMENT_CODE)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_ws_finitions (SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, CULTURE VARCHAR(255) NOT NULL, GAMME VARCHAR(50) NOT NULL, LCDV6 VARCHAR(50) NOT NULL, CATEGORY_NAME VARCHAR(255) DEFAULT NULL, FINITION_LABEL VARCHAR(255) DEFAULT NULL, FINITION_CODE VARCHAR(50) NOT NULL, PRIMARY_DISPLAY_PRICE VARCHAR(50) DEFAULT NULL, PREVIOUS_FINITION_CODE VARCHAR(50) DEFAULT NULL, V3D_LCDV VARCHAR(50) NOT NULL, V3D_EXTERIOR VARCHAR(50) DEFAULT NULL, V3D_INTERIOR VARCHAR(50) DEFAULT NULL, INDEX SITE_ID (SITE_ID), INDEX LANGUE_ID (LANGUE_ID), INDEX LCDV6 (LCDV6, FINITION_CODE), PRIMARY KEY(CULTURE, GAMME, LCDV6, FINITION_CODE, V3D_LCDV)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_ws_modele (SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, LIBELLE VARCHAR(255) DEFAULT NULL, LCDV4 VARCHAR(50) NOT NULL, CULTURE VARCHAR(255) NOT NULL, GAMME VARCHAR(50) NOT NULL, INDEX SITE_ID (SITE_ID), INDEX LANGUE_ID (LANGUE_ID), PRIMARY KEY(LCDV4, CULTURE, GAMME)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_ws_prix_finition_version (SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, CULTURE VARCHAR(255) NOT NULL, GAMME VARCHAR(50) NOT NULL, LCDV6 VARCHAR(50) NOT NULL, GR_COMMERCIAL_NAME_CODE VARCHAR(50) NOT NULL, ENGINE_CODE VARCHAR(50) NOT NULL, TRANSMISSION_CODE VARCHAR(50) NOT NULL, LCDV_CODE VARCHAR(50) NOT NULL, `LABEL` VARCHAR(255) DEFAULT NULL, PRICE_DISPLAY VARCHAR(50) DEFAULT NULL, PRICE_NUMERIC INT DEFAULT NULL, INDEX SITE_ID (SITE_ID), INDEX LANGUE_ID (LANGUE_ID), INDEX LCDV6 (LCDV6, GR_COMMERCIAL_NAME_CODE, ENGINE_CODE, TRANSMISSION_CODE), PRIMARY KEY(CULTURE, GAMME, LCDV_CODE)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_ws_services_pdv (CODE_ID INT AUTO_INCREMENT NOT NULL, SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, CODE_SERVICE VARCHAR(255) NOT NULL, LABEL_SERVICE VARCHAR(255) NOT NULL, TYPE_SERVICE VARCHAR(255) NOT NULL, ORDER_SERVICE INT DEFAULT NULL, ACTIF_SERVICE INT DEFAULT 0 NOT NULL, UNIQUE INDEX CODE_ID (CODE_ID), INDEX LABEL_SERVICE (LABEL_SERVICE), PRIMARY KEY(SITE_ID, LANGUE_ID, CODE_SERVICE)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_ws_vehicule_gamme (SITE_ID INT NOT NULL, LANGUE_ID INT NOT NULL, CULTURE VARCHAR(255) NOT NULL, GAMME VARCHAR(50) NOT NULL, LCDV4 VARCHAR(50) NOT NULL, LCDV6 VARCHAR(50) NOT NULL, MODEL_LABEL VARCHAR(255) DEFAULT NULL, BODY_LABEL VARCHAR(255) DEFAULT NULL, BODY_CODE VARCHAR(255) NOT NULL, MODEL_BODY_LABEL VARCHAR(255) DEFAULT NULL, INDEX SITE_ID (SITE_ID, LANGUE_ID), INDEX LANGUE_ID (LANGUE_ID), INDEX LCDV4 (LCDV4, BODY_CODE), INDEX IDX_9D483C49F1B5AEBC (SITE_ID), PRIMARY KEY(CULTURE, GAMME, LCDV6)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_faq_rubrique_content ADD CONSTRAINT psa_faq_rubrique_content_ibfk_3 FOREIGN KEY (FAQ_RUBRIQUE_ID) REFERENCES psa_faq_rubrique (FAQ_RUBRIQUE_ID) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE psa_vehicule_couleur ADD CONSTRAINT psa_vehicule_couleur_ibfk_1 FOREIGN KEY (VEHICULE_ID) REFERENCES psa_vehicule (VEHICULE_ID) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE psa_vehicule_criteres ADD CONSTRAINT psa_vehicule_criteres_ibfk_1 FOREIGN KEY (VEHICULE_ID) REFERENCES psa_vehicule (VEHICULE_ID) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
