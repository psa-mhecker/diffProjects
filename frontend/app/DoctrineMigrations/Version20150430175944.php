<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150430175944 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        $this->addSql('DELETE FROM psa_directory_site WHERE DIRECTORY_ID IN (63, 64, 65, 193, 194, 203, 206, 208, 209, 210, 211,223, 231, 232)');

        $this->addSql('DELETE FROM psa_profile_directory WHERE DIRECTORY_ID IN (63, 64, 65, 193, 194, 203, 206, 208, 209, 210, 211,223, 231, 232)');

        $this->addSql('DELETE FROM psa_directory WHERE DIRECTORY_ID IN (63, 64, 65, 193, 194, 203, 206, 208, 209, 210, 211,223, 231, 232)');

        $this->addSql("INSERT INTO psa_template (TEMPLATE_ID, TEMPLATE_TYPE_ID, TEMPLATE_GROUP_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_COMPLEMENT, PLUGIN_ID) VALUES
            (76, 1, 5, 'NDP_REF_APPLI_MOBILE', 'Ndp_AppliMobile', '', NULL, ''),
            (77, 1, 5, 'NDP_REF_BENEFICE', 'Ndp_ServConnAppliMobile', '', NULL, ''),
            (78, 1, 5, 'NDP_REF_SERVICE_CONNECTE', 'Ndp_ServConn', '', NULL, ''),
            (79, 1, 5, 'NDP_REF_SERVICE_CONNECTE_FINITION', 'Ndp_ServConnFinition', '', NULL, ''),
            (80, 1, 5, 'NDP_REF_APPLI_CONNECT_APP', 'Ndp_AppliConnectApps', '', NULL, ''),
            (81, 1, 5, 'NDP_REF_DEALERLOC_SERVICEPDV', 'Ndp_DealerLocServicePdv', '', NULL, ''),
            (82, 1, 5, 'NDP_REF_CAT_PRESTA_APV', 'Ndp_CatPrestaAPV', '', NULL, ''),
            (83, 1, 5, 'NDP_REF_PRESTA_APV', 'Ndp_PrestaAPV', '', NULL, ''),
            (84, 1, 5, 'NDP_REF_REL_CAT_PRESTA_APV', 'Ndp_RelCatPrestaAPV', '', NULL, ''),
            (85, 1, 5, 'NDP_REF_GESTION_GAMME', 'Ndp_GestionGamme', '', NULL, ''),
            (86, 1, 5, 'NDP_REF_CENTRAL_CAT_GAMME', 'Ndp_CatGammeCentral', '', NULL, ''),
            (87, 1, 5, 'NDP_REF_CENTRAL_SEGM_FINITION', 'Ndp_SegmFinitionCentral', '', NULL, ''),
            (88, 1, 5, 'NDP_REF_CENTRAL_FINITION_COULEUR', 'Ndp_FinitionCouleurCentral', '', NULL, ''),
            (89, 1, 5, 'NDP_REF_CENTRAL_FINITION_BAGDE', 'Ndp_FinitionBadgeCentral', '', NULL, ''),
            (90, 1, 5, 'NDP_REF_CENTRAL_ANGLE_VUE_MODEL', 'Ndp_AngleVueModelCentral', '', NULL, ''),
            (91, 1, 5, 'NDP_REF_CENTRAL_ANGLE_VUE_MODEL_SILH', 'Ndp_AngleVueModelSilhCentral', '', NULL, ''),
            (92, 1, 5, 'NDP_REF_CENTRAL_TYPE_COULEUR', 'Ndp_TypeCouleurCentral', '', NULL, ''),
            (93, 1, 5, 'NDP_REF_CAT_GAMME', 'Ndp_CatGamme', '', NULL, ''),
            (94, 1, 5, 'NDP_REF_CAT_PRIORISATION', 'Ndp_CatPriorisation', '', NULL, ''),
            (95, 1, 5, 'NDP_REF_SEGM_FINITION', 'Ndp_SegmFinition', '', NULL, ''),
            (96, 1, 5, 'NDP_REF_MODELE_TOUS', 'Ndp_ModeleTous', '', NULL, ''),
            (97, 1, 5, 'NDP_REF_MODELE', 'Ndp_Modele', '', NULL, ''),
            (98, 1, 5, 'NDP_REF_MODELE_RGPMT_SILH', 'Ndp_ModeleRegroupementSilh', '', NULL, ''),
            (99, 1, 5, 'NDP_REF_FINITION', 'Ndp_Finition', '', NULL, ''),
            (100, 1, 5, 'NDP_REF_TYPE_COULEUR', 'Ndp_TypeCouleur', '', NULL, '')
          ");

        $this->addSql("INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
            (82, NULL, 62, 0, NULL, NULL, 'NDP_REF_RESEAUX_SOCIAUX', NULL, NULL),
            (83, 76, 62, 0, NULL, NULL, 'NDP_REF_APPLI_MOBILE', NULL, NULL),
            (84, NULL, 62, 0, NULL, NULL, 'NDP_REF_SERVICES_CONNECTES', NULL, NULL),
            (85, 77, 84, 0, NULL, NULL, 'NDP_REF_BENEFICE', NULL, NULL),
            (86, 78, 84, 0, NULL, NULL, 'NDP_REF_SERVICE_CONNECTE', NULL, NULL),
            (87, 79, 84, 0, NULL, NULL, 'NDP_REF_SERVICE_CONNECTE_FINITION', NULL, NULL),
            (88, 80, 84, 0, NULL, NULL, 'NDP_REF_APPLI_CONNECT_APPS', NULL, NULL),
            (89, 81, 80, 0, NULL, NULL, 'NDP_REF_DEALERLOC_SERVICEPDV', NULL, NULL),
            (90, NULL, 62, 0, NULL, NULL, 'NDP_REF_DSP', NULL, NULL),
            (91, 82, 90, 0, NULL, NULL, 'NDP_REF_CAT_PRESTA_APV', NULL, NULL),
            (92, 83, 90, 0, NULL, NULL, 'NDP_REF_PRESTA_APV', NULL, NULL),
            (93, 84, 90, 0, NULL, NULL, 'NDP_REF_REL_CAT_PRESTA_APV', NULL, NULL),
            (94, NULL, 1, 0, NULL, NULL, 'NDP_REF_VEHICLE', NULL, NULL),
            (95, 85, 94, 0, NULL, NULL, 'NDP_REF_GESTIONGAMME', NULL, NULL),
            (96, NULL, 94, 0, NULL, NULL, 'NDP_REF_CENTRAL', NULL, NULL),
            (97, NULL, 96, 0, NULL, NULL, 'NDP_REF_CENTRAL_SEGM_GAMME', NULL, NULL),
            (98, 86, 97, 0, NULL, NULL, 'NDP_REF_CENTRAL_CAT_GAMME', NULL, NULL),
            (99, 87, 97, 0, NULL, NULL, 'NDP_REF_CENTRAL_SEGM_FINITION', NULL, NULL),
            (100, NULL, 96, 0, NULL, NULL, 'NDP_REF_CENTRAL_AFF_VEHICLE', NULL, NULL),
            (101, 88, 100, 0, NULL, NULL, 'NDP_REF_CENTRAL_FINITION_COULEUR', NULL, NULL),
            (102, 89, 100, 0, NULL, NULL, 'NDP_REF_CENTRAL_FINITION_BAGDE', NULL, NULL),
            (103, NULL, 100, 0, NULL, NULL, 'NDP_REF_CENTRAL_ANGLE_VUE', NULL, NULL),
            (104, 90, 103, 0, NULL, NULL, 'NDP_REF_CENTRAL_ANGLE_VUE_MODEL', NULL, NULL),
            (105, 91, 103, 0, NULL, NULL, 'NDP_REF_CENTRAL_ANGLE_VUE_MODEL_SILH', NULL, NULL),
            (106, 92, 100, 0, NULL, NULL, 'NDP_REF_CENTRAL_TYPE_COULEUR', NULL, NULL),
            (107, NULL, 94, 0, NULL, NULL, 'NDP_REF_SEGM_GAMME', NULL, NULL),
            (108, 93, 107, 0, NULL, NULL, 'NDP_REF_CAT_GAMME', NULL, NULL),
            (109, 94, 107, 0, NULL, NULL, 'NDP_REF_CAT_PRIORISATION', NULL, NULL),
            (110, 95, 107, 0, NULL, NULL, 'NDP_REF_SEGM_FINITION', NULL, NULL),
            (111, NULL, 94, 0, NULL, NULL, 'NDP_REF_AFF_VEHICLE', NULL, NULL),
            (112, 96, 111, 0, NULL, NULL, 'NDP_REF_MODELE_TOUS', NULL, NULL),
            (113, 97, 111, 0, NULL, NULL, 'NDP_REF_MODELE', NULL, NULL),
            (114, 98, 111, 0, NULL, NULL, 'NDP_REF_MODELE_RGPMT_SILH', NULL, NULL),
            (115, 99, 111, 0, NULL, NULL, 'NDP_REF_FINITION', NULL, NULL),
            (116, 100, 111, 0, NULL, NULL, 'NDP_REF_TYPE_COULEUR', NULL, NULL)
           ");

        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES
            (82, 2), (83, 2), (84, 2), (85, 2), (86, 2), (87, 2), (88, 2), (89, 2),
            (90, 2), (91, 2), (92, 2), (93, 2), (94, 2), (95, 2), (96, 2), (97, 2), (98, 2), (99, 2),
            (100, 2), (101, 2), (102, 2), (103, 2), (104, 2), (105, 2), (106, 2), (107, 2), (108, 2), (109, 2),
            (110, 2), (111, 2), (112, 2), (113, 2), (114, 2), (115, 2), (116, 2)
           ");

        $this->addSql('DELETE FROM psa_profile_directory WHERE PROFILE_ID = 2');

        $this->addSql('INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES
            (2, 1, 2007), (2, 4, 2015), (2, 27, 2001), (2, 28, 2006), (2, 35, 2003), (2, 36, 2004), (2, 42, 2002),
            (2, 43, 2005), (2, 62, 2019), (2, 76, 2043), (2, 80, 2030), (2, 81, 2031), (2, 82, 2020), (2, 83, 2024),
            (2, 84, 2025), (2, 85, 2026), (2, 86, 2027), (2, 87, 2028), (2, 88, 2029), (2, 89, 2032), (2, 90, 2033),
            (2, 91, 2034), (2, 92, 2035), (2, 93, 2036), (2, 94, 2044), (2, 95, 2045), (2, 96, 2046), (2, 97, 2047),
            (2, 98, 2048), (2, 99, 2049), (2, 100, 2050), (2, 101, 2051), (2, 102, 2052), (2, 103, 2053), (2, 104, 2054),
            (2, 105, 2055), (2, 106, 2056), (2, 107, 2057), (2, 108, 2058), (2, 109, 2059), (2, 110, 2060), (2, 111, 2061),
            (2, 112, 2062), (2, 113, 2063), (2, 114, 2064), (2, 115, 2065), (2, 116, 2066), (2, 182, 2016), (2, 183, 2017),
            (2, 185, 2008), (2, 186, 2012), (2, 188, 2013), (2, 189, 2009), (2, 190, 2010), (2, 191, 2011), (2, 192, 2022),
            (2, 198, 2037), (2, 199, 2038), (2, 200, 2039), (2, 201, 2040), (2, 202, 2021), (2, 204, 2041), (2, 205, 2014),
            (2, 212, 2018), (2, 221, 2042), (2, 233, 2023)
        ');

        $this->addSql("UPDATE psa_directory SET DIRECTORY_PARENT_ID = 82, DIRECTORY_LABEL = 'NDP_REF_RESEAUX_SOCIAUX_GPE' WHERE  DIRECTORY_ID =202");
        $this->addSql("UPDATE psa_directory SET DIRECTORY_PARENT_ID = 82, DIRECTORY_LABEL = 'NDP_REF_RESEAUX_SOCIAUX_PARAM' WHERE  DIRECTORY_ID =192");
        $this->addSql("UPDATE psa_directory SET DIRECTORY_LABEL = 'NDP_REFERENTIEL_CTA' WHERE  DIRECTORY_ID =233");

        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_REF_RESEAUX_SOCIAUX', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_APPLI_MOBILE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_SERVICES_CONNECTES', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_BENEFICE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_SERVICE_CONNECTE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_SERVICE_CONNECTE_FINITION', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_APPLI_CONNECT_APPS', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_DEALERLOC_SERVICEPDV', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_DSP', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_CAT_PRESTA_APV', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_PRESTA_APV', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_REL_CAT_PRESTA_APV', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_VEHICLE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_GESTIONGAMME', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_CENTRAL', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_CENTRAL_SEGM_GAMME', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_CENTRAL_CAT_GAMME', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_CENTRAL_SEGM_FINITION', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_CENTRAL_AFF_VEHICLE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_CENTRAL_FINITION_COULEUR', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_CENTRAL_FINITION_BAGDE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_CENTRAL_ANGLE_VUE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_CENTRAL_ANGLE_VUE_MODEL', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_CENTRAL_ANGLE_VUE_MODEL_SILH', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_CENTRAL_TYPE_COULEUR', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_SEGM_GAMME', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_CAT_GAMME', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_CAT_PRIORISATION', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_SEGM_FINITION', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_AFF_VEHICLE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_MODELE_TOUS', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_MODELE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_MODELE_RGPMT_SILH', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_FINITION', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_TYPE_COULEUR', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_RESEAUX_SOCIAUX_GPE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_REF_RESEAUX_SOCIAUX_PARAM', NULL, 2, NULL, NULL, 1, NULL)
            ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_REF_RESEAUX_SOCIAUX', 1, 1, 'Réseaux sociaux'),
            ('NDP_REF_APPLI_MOBILE', 1, 1, 'Applications mobile'),
            ('NDP_REF_SERVICES_CONNECTES', 1, 1, 'Services connectés'),
            ('NDP_REF_BENEFICE', 1, 1, 'Bénéfices'),
            ('NDP_REF_SERVICE_CONNECTE', 1, 1, 'Services connectés'),
            ('NDP_REF_SERVICE_CONNECTE_FINITION', 1, 1, 'Services connectés / finitions'),
            ('NDP_REF_APPLI_CONNECT_APPS', 1, 1, 'Applications Connect Apps'),
            ('NDP_REF_DEALERLOC_SERVICEPDV', 1, 1, 'Services PDV'),
            ('NDP_REF_DSP', 1, 1, 'DSP'),
            ('NDP_REF_CAT_PRESTA_APV', 1, 1, 'Catégorie prestations APV'),
            ('NDP_REF_PRESTA_APV', 1, 1, 'Prestations APV'),
            ('NDP_REF_REL_CAT_PRESTA_APV', 1, 1, 'Prestations APV catégories'),
            ('NDP_REF_VEHICLE', 1, 1, 'Véhicules'),
            ('NDP_REF_GESTIONGAMME', 1, 1, 'Gestion de la gamme'),
            ('NDP_REF_CENTRAL', 1, 1, 'Central'),
            ('NDP_REF_CENTRAL_SEGM_GAMME', 1, 1, 'Segmentation de la gamme'),
            ('NDP_REF_CENTRAL_CAT_GAMME', 1, 1, 'Catégorisation de la gamme'),
            ('NDP_REF_CENTRAL_SEGM_FINITION', 1, 1, 'Segmentation des finitions'),
            ('NDP_REF_CENTRAL_AFF_VEHICLE', 1, 1, ' Affichage des véhicules'),
            ('NDP_REF_CENTRAL_FINITION_COULEUR', 1, 1, 'Couleurs des finitions'),
            ('NDP_REF_CENTRAL_FINITION_BAGDE', 1, 1, 'Badges des finitions'),
            ('NDP_REF_CENTRAL_ANGLE_VUE', 1, 1, 'Angles de vue des visuels'),
            ('NDP_REF_CENTRAL_ANGLE_VUE_MODEL', 1, 1, 'Modèles'),
            ('NDP_REF_CENTRAL_ANGLE_VUE_MODEL_SILH', 1, 1, 'Modèles / silhouettes'),
            ('NDP_REF_CENTRAL_TYPE_COULEUR', 1, 1, 'Types de couleurs'),
            ('NDP_REF_SEGM_GAMME', 1, 1, 'Segmentation de la gamme'),
            ('NDP_REF_CAT_GAMME', 1, 1, 'Catégorisation de la gamme'),
            ('NDP_REF_CAT_PRIORISATION', 1, 1, 'Priorisation des catégories'),
            ('NDP_REF_SEGM_FINITION', 1, 1, 'Segmentation des finitions'),
            ('NDP_REF_AFF_VEHICLE', 1, 1, 'Affichage des véhicules'),
            ('NDP_REF_MODELE_TOUS', 1, 1, 'Tous modèles'),
            ('NDP_REF_MODELE', 1, 1, 'Modèles '),
            ('NDP_REF_MODELE_RGPMT_SILH', 1, 1, 'Modèles / Regroupement de silhouettes'),
            ('NDP_REF_FINITION', 1, 1, 'Finitions'),
            ('NDP_REF_TYPE_COULEUR', 1, 1, 'Types de couleurs'),
            ('NDP_REF_RESEAUX_SOCIAUX_GPE', 1, 1, 'Groupe de réseaux sociaux'),
            ('NDP_REF_RESEAUX_SOCIAUX_PARAM', 1, 1, 'Paramètres des réseaux sociaux')
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
