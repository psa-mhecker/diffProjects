<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151218171916 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE psa_filter_after_sale_services (ID INT AUTO_INCREMENT NOT NULL, `LABEL` VARCHAR(50) NOT NULL, LANGUE_ID INT DEFAULT NULL, SITE_ID INT DEFAULT NULL, FILTER_ORDER INT NOT NULL DEFAULT 0,INDEX IDX_EC4438315622E2C2 (LANGUE_ID), INDEX IDX_EC443831F1B5AEBC (SITE_ID), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_after_sale_services (ID INT AUTO_INCREMENT NOT NULL, `LABEL` VARCHAR(255) NOT NULL, TYPE_LABEL_LINK INT NOT NULL, LABEL_LINK VARCHAR(255) NOT NULL, URL VARCHAR(255) NOT NULL, COLUMN_NUMBER INT NOT NULL, LEGAL_NOTICE VARCHAR(10) NOT NULL, PRICE_POSITION INT NOT NULL, TYPE_LABEL_PRICE INT NOT NULL, PRICE_LABEL VARCHAR(255) NOT NULL, PRICE DOUBLE PRECISION NOT NULL, DESCRIPTION VARCHAR(255) NOT NULL, TYPE_LABEL_PRICE2 INT, PRICE_LABEL2 VARCHAR(255), PRICE2 DOUBLE, DESCRIPTION2 VARCHAR(255), LANGUE_ID INT NOT NULL, SITE_ID INT NOT NULL, MEDIA_ID INT NOT NULL, MEDIA_ID2 INT NOT NULL, INDEX IDX_660241E45622E2C2 (LANGUE_ID), INDEX IDX_660241E4F1B5AEBC (SITE_ID), INDEX IDX_660241E414E107D9 (MEDIA_ID), INDEX IDX_660241E4E5CE357A (MEDIA_ID2), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_filter_after_sale_services ADD CONSTRAINT FK_EC4438315622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_filter_after_sale_services ADD CONSTRAINT FK_EC443831F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_after_sale_services ADD CONSTRAINT FK_660241E45622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_after_sale_services ADD CONSTRAINT FK_660241E4F1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');
        $this->addSql('ALTER TABLE psa_after_sale_services ADD CONSTRAINT FK_660241E414E107D9 FOREIGN KEY (MEDIA_ID) REFERENCES psa_media (MEDIA_ID)');
        $this->addSql('ALTER TABLE psa_after_sale_services ADD CONSTRAINT FK_660241E4E5CE357A FOREIGN KEY (MEDIA_ID2) REFERENCES psa_media (MEDIA_ID)');
        $this->addSql('ALTER TABLE psa_site ADD FILTER_AFTER_SALE_SERVICE INT NOT NULL DEFAULT 0');

        // referentiel filtre préstation après vente et préstation après vente
        $this->addSql("INSERT INTO psa_template (TEMPLATE_ID, TEMPLATE_TYPE_ID, TEMPLATE_GROUP_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_COMPLEMENT, PLUGIN_ID) VALUES
            (367, 1, 5, 'NDP_REF_TEMPLATE_FILTER_AFTER_SALE_SERVICES', 'Ndp_FilterAfterSaleServices', '', NULL, ''),
            (368, 1, 5, 'NDP_REF_TEMPLATE_AFTER_SALE_SERVICES', 'Ndp_AfterSaleServices', '', NULL, '')
        ");

        $this->addSql("INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
            (268, NULL, 62, 0, NULL, NULL, 'NDP_REF_AFTER_SALE_SERVICES', NULL, NULL),
            (269, 367, 268, 0, NULL, NULL, 'NDP_REF_FILTER_AFTER_SALE_SERVICES', NULL, NULL),
            (270, 368, 268, 0, NULL, NULL, 'NDP_REF_AFTER_SALE_SERVICES', NULL, NULL)
        ");

        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES
            (268, 2),
            (269, 2),
            (270, 2)
        ");

        $this->addSql("INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES
            (2, 268, NULL),
            (2, 269, NULL),
            (2, 270, NULL),
            (3, 268, NULL),
            (3, 269, NULL),
            (3, 270, NULL)
        ");

        $this->upTranslations(
            array(
                'NDP_REF_TEMPLATE_FILTER_AFTER_SALE_SERVICES' => array(
                    'expression' => 'Filtre prestation après vente',
                    'bo'=>1
                ),
                'NDP_REF_TEMPLATE_AFTER_SALE_SERVICES' => array(
                    'expression' => 'Prestation après vente',
                    'bo'=>1
                ),
                'NDP_REF_FILTER_AFTER_SALE_SERVICES' => array(
                    'expression' => 'Filtre prestation après vente',
                    'bo'=>1
                ),
                'NDP_REF_AFTER_SALE_SERVICES' => array(
                    'expression' => 'Prestation après vente',
                    'bo'=>1
                ),
                'NDP_MIN_AND_MAX_FILTER' => array(
                    'expression' => 'Minimum 2 filtres, maximum 6 filtres. Suppression impossible à moins de 2 filtres.',
                    'bo'=>1
                ),
                'NDP_NO_FILTER_NO_SERVICES' => array(
                    'expression' => 'Les filtres ne s\'afficheront pas si aucune prestation ne leur est rattachée',
                    'bo'=>1
                ),
                'NDP_NO_FILTER' => array(
                    'expression' => 'je ne souhaite pas créer de filtre',
                    'bo'=>1
                ),
                'NDP_FILTER_AFTER_SALE_SERVICE_LABEL' => array(
                    'expression' => 'Libellé filtre APV',
                    'bo'=>1
                ),
                'NDP_SETTING_APV' => array(
                    'expression' => 'Paramétrage de la prestation APV',
                    'bo'=>1
                ),
                'NDP_SERVICE_LABEL' => array(
                    'expression' => 'Libellé prestation',
                    'bo'=>1
                ),
                'NDP_VISUAL_DESKTOP_THUMBNAIL' => array(
                    'expression' => 'Visuel vignette desktop',
                    'bo'=>1
                ),
                'NDP_VISUAL_MOBILE_THUMBNAIL' => array(
                    'expression' => 'Visuel vignette mobile',
                    'bo'=>1
                ),
                'NDP_SHOW_DETAIL' => array(
                    'expression' => 'Voir le détail',
                    'bo'=>1
                ),
                'NDP_THUMBNAIL_LINK_LABEL' => array(
                    'expression' => 'Libellé du lien vignette',
                    'bo'=>1
                ),
                'NDP_FIELD_OTHER_LABEL' => array(
                    'expression' => 'Autre libellé',
                    'bo'=>1
                ),
                'NDP_URL' => array(
                    'expression' => 'URL',
                    'bo'=>1
                ),
                'NDP_LEGAL_NOTICE_NUMBER' => array(
                    'expression' => 'N° Mentions légales',
                    'bo'=>1
                ),
                'NDP_ON_COLUMN' => array(
                    'expression' => 'Nombre de colonnes',
                    'bo'=>1
                ),
                'NDP_PRICE_POSITION' => array(
                    'expression' => 'Position du libellé du prix',
                    'bo'=>1
                ),
                'NDP_DISPLAY_PRICE_LABEL' => array(
                    'expression' => 'Affichage libellé prix',
                    'bo'=>1
                ),
                'NDP_FROM_BO' => array(
                    'expression' => 'A partir de',
                    'bo'=>1
                ),
                'NDP_PRICE_LABEL' => array(
                    'expression' => 'Libellé',
                    'bo'=>1
                ),
                'NDP_PRICE_BO' => array(
                    'expression' => 'Prix',
                    'bo'=>1
                ),
                'NDP_DESCRIPTION_BULLET_LIST' => array(
                    'expression' => 'Description (liste à puces)',
                    'bo'=>1
                ),
                'NDP_MSG_TOOLTIP_RECOMMENDATION_SERVICES' => array(
                    'expression' => ' Ajout d’une puce en FO au clic sur Entrée. Préconisation : 4 puces max / 2 lignes max par puce',
                    'bo'=>1
                ),
                'NDP_SETTING_FILTER_AND_APV' => array(
                    'expression' => 'Paramétrage des filtres d’appartenance d’une prestation APV',
                    'bo'=>1
                ),
                'NDP_PRICE_LEFT' => array(
                    'expression' => 'à gauche du prix',
                    'bo'=>1
                ),
                'NDP_PRICE_RIGHT' => array(
                    'expression' => 'à droite du prix',
                    'bo'=>1
                ),
            )
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->downTranslations(array(
            'NDP_REF_TEMPLATE_FILTER_AFTER_SALE_SERVICES',
            'NDP_REF_TEMPLATE_AFTER_SALE_SERVICES',
            'NDP_REF_FILTER_AFTER_SALE_SERVICES',
            'NDP_REF_AFTER_SALE_SERVICES',
            'NDP_MIN_AND_MAX_FILTER',
            'NDP_NO_FILTER_NO_SERVICES',
            'NDP_NO_FILTER',
            'NDP_FILTER_AFTER_SALE_SERVICE_LABEL',
            'NDP_SETTING_APV',
            'NDP_SERVICE_LABEL',
            'NDP_VISUAL_DESKTOP_THUMBNAIL',
            'NDP_VISUAL_MOBILE_THUMBNAIL',
            'NDP_SHOW_DETAIL',
            'NDP_THUMBNAIL_LINK_LABEL',
            'NDP_FIELD_OTHER_LABEL',
            'NDP_URL',
            'NDP_LEGAL_NOTICE_NUMBER',
            'NDP_ON_COLUMN',
            'NDP_PRICE_POSITION',
            'NDP_DISPLAY_PRICE_LABEL',
            'NDP_FROM_BO',
            'NDP_PRICE_LABEL',
            'NDP_PRICE_BO',
            'NDP_DESCRIPTION_BULLET_LIST',
            'NDP_MSG_TOOLTIP_RECOMMENDATION_SERVICES',
            'NDP_SETTING_FILTER_AND_APV',
            'NDP_PRICE_LEFT',
            'NDP_PRICE_RIGHT',
        ));

        $tables = array('psa_profile_directory', 'psa_directory_site', 'psa_directory');
        $ids = array(270, 269, 268);

        foreach ($tables as $table) {
            foreach ($ids as $id) {
                $this->addSql('DELETE FROM '.$table.' WHERE `DIRECTORY_ID` = '.$id);
            }
        }

        $this->addSql('DELETE FROM psa_template WHERE `TEMPLATE_ID` = 367');
        $this->addSql('DELETE FROM psa_template WHERE `TEMPLATE_ID` = 368');

        $this->addSql('DROP TABLE psa_filter_after_sale_services');
        $this->addSql('DROP TABLE psa_after_sale_services');
        $this->addSql('ALTER TABLE psa_site DROP COLUMN FILTER_AFTER_SALE_SERVICE');
    }
}
