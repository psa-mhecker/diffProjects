# Doctrine Migration File Generated on 2015-05-22 13:11:55

# Version 20150427175214
INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_GRAND_VISUEL_MOBILE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_MSG_VISUEL_CTA_IMP', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_MSG_CTA_ABS', NULL, 2, NULL, NULL, 1, NULL)
                ;
INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_GRAND_VISUEL_MOBILE', 1, 1, 'Format mobile grand visuel'),
                ('NDP_MSG_VISUEL_CTA_IMP', 1, 1, 'Veuillez vérifier que les CTA importés du référentiel comportent bien un visuel.'),
                ('NDP_MSG_CTA_ABS', 1, 1, 'Si un visuel est absent dans 1 des 3 CTA alors la tranche s\'affichera sans vignette.')
            ;
INSERT INTO migration_versions (version) VALUES ('20150427175214');
# Doctrine Migration File Generated on 2015-05-22 13:11:58

# Version 20150428110436
INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_FORMAT_WEB', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_DROIT', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_AVEC_VISUELS', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_SANS_VISUEL', NULL, 2, NULL, NULL, 1, NULL)
                ;
INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_FORMAT_WEB', 1, 1, 'Format web'),
                ('NDP_DROIT', 1, 1, 'Droit'),
                ('NDP_SANS_VISUEL', 1, 1, 'Sans visuel'),
                ('NDP_AVEC_VISUELS', 1, 1, 'Avec visuel (bandeau)')
            ;
UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Ajouter un toggle' WHERE LABEL_ID ='NDP_ADD_TOGGLE' AND SITE_ID = 1 AND LANGUE_ID = 1;
UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Grand visuel mobile' WHERE LABEL_ID ='NDP_GRAND_VISUEL_MOBILE' AND SITE_ID = 1 AND LANGUE_ID = 1;
INSERT INTO migration_versions (version) VALUES ('20150428110436');
# Doctrine Migration File Generated on 2015-05-22 13:12:01

# Version 20150428113722
UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Texte' WHERE LABEL_ID ='NDP_TEXTE' AND SITE_ID = 1 AND LANGUE_ID = 1;
INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_AFFICHAGE_VISUEL', NULL, 2, NULL, NULL, 1, NULL)
                
                ;
INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_AFFICHAGE_VISUEL', 1, 1, 'Affichage visuel')          
            ;
UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Taille visuel' WHERE LABEL_ID ='NDP_TAILLE_VISUEL' AND SITE_ID = 1 AND LANGUE_ID = 1;
INSERT INTO migration_versions (version) VALUES ('20150428113722');
# Doctrine Migration File Generated on 2015-05-22 13:12:05

# Version 20150428134530
UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Tranche' WHERE LABEL_ID ='NDP_TITRE_DE_LA_TRANCHE' AND SITE_ID = 1 AND LANGUE_ID = 1;
INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_TEXTE_SUR', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_ERROR_LABEL', NULL, 2, NULL, NULL, 1, NULL)
                ;
INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_TEXTE_SUR', 1, 1, 'Texte sur'),          
                ('NDP_ERROR_LABEL', 1, 1, 'Libellé Erreur')          
                ;
INSERT INTO migration_versions (version) VALUES ('20150428134530');
# Doctrine Migration File Generated on 2015-05-22 13:12:08

# Version 20150428142153
UPDATE psa_template SET TEMPLATE_TYPE_ID = 1, TEMPLATE_LABEL = 'NDP_REFERENTIEL_CTA' WHERE  TEMPLATE_ID =332;
INSERT INTO psa_template (TEMPLATE_ID, TEMPLATE_TYPE_ID, TEMPLATE_GROUP_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_COMPLEMENT, PLUGIN_ID) VALUES
            (75, 1, 5, 'NDP_REF_DEALERLOC_DEVENIRAGENT', 'Ndp_DealerLocDevenirAgent', '', NULL, '')
            ;
INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
            (80, NULL, 62, 0, NULL, NULL, 'NDP_REF_DEALERLOC', NULL, NULL),
            (81, 75, 80, 0, NULL, NULL, 'NDP_REF_DEALERLOC_DEVENIRAGENT', NULL, NULL)
            ;
INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES
            (80, 2),
            (81, 2)
            ;
INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES
            (2, 80, NULL),
            (2, 81, NULL)
            ;
INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_REF_DEALERLOC', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_REF_DEALERLOC_DEVENIRAGENT', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_DEVENIRAGENT_IMPORT_KO', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_DEVENIRAGENT_CSV_KO', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_DEVENIRAGENT_UTF8_KO', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_DEVENIRAGENT_NBFIELD_KO', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_DEVENIRAGENT_HEADER_KO', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_DEVENIRAGENT_IMPORT_OK', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_DEVENIRAGENT_ROW_ADDED', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_DEVENIRAGENT_LIAISON_421119', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_DEVENIRAGENT_LIAISON_421122', NULL, 2, NULL, NULL, 1, NULL)
               ;
INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_REF_DEALERLOC', 1, 1, 'Dealer Locator'),
            ('NDP_REF_DEALERLOC_DEVENIRAGENT', 1, 1, 'Devenir agent'),
            ('NDP_DEVENIRAGENT_IMPORT_KO', 1, 1, 'Résultat de l\'import : le fichier n\'a pas été importé, voici les erreurs'),
            ('NDP_DEVENIRAGENT_CSV_KO', 1, 1, 'Le fichier n\'est pas au format csv'),
            ('NDP_DEVENIRAGENT_UTF8_KO', 1, 1, 'Le fichier n\'est pas encodé en UTF-8'),
            ('NDP_DEVENIRAGENT_NBFIELD_KO', 1, 1, 'Le fichier ne comprend pas le nombre de colonne attendu'),
            ('NDP_DEVENIRAGENT_HEADER_KO', 1, 1, 'Le fichier ne comprend pas la ligne d\'entête'),
            ('NDP_DEVENIRAGENT_IMPORT_OK', 1, 1, 'Résultat de l\'import : le fichier a été importé avec succès'),
            ('NDP_DEVENIRAGENT_ROW_ADDED', 1, 1, 'ligne(s) créée(s)'),
            ('NDP_DEVENIRAGENT_LIAISON_421119', 1, 1, 'Affaire à vendre'),
            ('NDP_DEVENIRAGENT_LIAISON_421122', 1, 1, 'Agent')
            ;
DELETE FROM psa_pdv_deveniragent;
INSERT INTO psa_pdv_deveniragent (SITE_ID, PDV_DEVENIRAGENT_ID, PDV_DEVENIRAGENT_NAME, PDV_DEVENIRAGENT_DESC, PDV_DEVENIRAGENT_ADDRESS1, PDV_DEVENIRAGENT_ADDRESS2, PDV_DEVENIRAGENT_ZIPCODE, PDV_DEVENIRAGENT_CITY, PDV_DEVENIRAGENT_COUNTRY, PDV_DEVENIRAGENT_EMAIL, PDV_DEVENIRAGENT_TEL1, PDV_DEVENIRAGENT_TEL2, PDV_DEVENIRAGENT_FAX, PDV_DEVENIRAGENT_RRDI, PDV_DEVENIRAGENT_LAT, PDV_DEVENIRAGENT_LNG, PDV_DEVENIRAGENT_LIAISON_ID) VALUES
            (2, 13076, 'GARAGE TRAISNEL YVES', 'M.TRAISNEL YVES', '38 RUE AMIRAL TOURVILLE', NULL, '50230', 'AGON COUTAINVILLE', NULL, NULL, '233470855', NULL, NULL, '1', 49.0479378, -1.5966246, 421122),
            (2, 13077, 'Philippe BOUDET - ALLEVARD', 'Philippe BOUDET', NULL, NULL, '38580', 'ALLEVARD', NULL, 'philippe.boudet@peugeot.com', '617951500', NULL, NULL, '2', 45.394486, 6.075192, 421119),
            (2, 13078, 'GARAGE TROQUIER', 'TROQUIER HUBERT', 'ROUTE DE LA TRANCHE', NULL, '85750', 'ANGLES', NULL, NULL, '251975227', NULL, NULL, '3', 46.399676, -1.4065048, 421119),
            (2, 13079, 'Philippe BOUDET - ANTIBES', 'Philippe BOUDET', NULL, NULL, '6600', 'ANTIBES', NULL, 'philippe.boudet@peugeot.com', '617951500', NULL, NULL, '4', 33.5107864, -117.7328708, 421119),
            (2, 13080, 'GARAGE DALLARA', 'DALLARA GILBERT', '145 AV DU GENERAL DE GAULLE', NULL, '30390', 'ARAMON', NULL, NULL, '466570015', NULL, NULL, '5', 43.8926696, 4.6854079, 421119),
            (2, 13081, 'Noël MESTRE - ATHIS MONS', 'Noël MESTRE', NULL, NULL, '91200', 'ATHIS MONS', NULL, 'noel.mestre@peugeot.com', '603999589', NULL, NULL, '6', 48.7092979, 2.38479, 421119),
            (2, 13082, 'Christian CHANET - AVESNES SUR HELPE', 'Christian CHANET', NULL, NULL, '59440', 'AVESNES SUR HELPE', NULL, 'christian.chanet@peugeot.com', '614568380', NULL, NULL, '7', 50.123589, 3.926864, 421119),
            (2, 13083, 'Philippe BOUDET - BASTIA', 'Philippe BOUDET', NULL, NULL, '20200', 'BASTIA', NULL, 'philippe.boudet@peugeot.com', '617951500', NULL, NULL, '8', 42.697283, 9.450881, 421119),
            (2, 13084, 'Marc DUCARNE - BAZAS', 'Marc DUCARNE', NULL, NULL, '33430', 'BAZAS', NULL, 'marc.ducarne@peugeot.com', '608751251', NULL, NULL, '9', 44.430624, -0.211464, 421119),
            (2, 13085, 'Jean-Luc MALO - BEAUCOUZE', 'Jean-Luc MALO', NULL, NULL, '49070', 'BEAUCOUZE', NULL, 'jeanluc.malo@peugeot.com', '607462784', NULL, NULL, '10', 47.4741829, -0.635502, 421119),
            (2, 13086, 'GARAGE STREF', 'STREF JACQUES', 'ROUTE DE BERNAY', NULL, '27170', 'BEAUMONT LE ROGER', NULL, NULL, '232452049', NULL, NULL, '11', 49.081506, 0.777465, 421119),
            (2, 13087, 'Philippe BOUDET - BELLEGARDE', 'Philippe BOUDET', NULL, NULL, '45270', 'BELLEGARDE', NULL, 'jeanluc.malo@peugeot.com', '607462784', NULL, NULL, '12', 47.988024, 2.442961, 421119),
            (2, 13089, 'ETS BROUSSE SARL', 'BROUSSE JEAN MARC', '30 AV DE MONTAUBAN', NULL, '31660', 'BESSIERES', NULL, NULL, '561840148', NULL, NULL, '14', 43.800464, 1.606142, 421119),
            (2, 13090, 'Philippe BOUDET - BEYNOST', 'Philippe BOUDET', NULL, NULL, '1700', 'BEYNOST', NULL, 'philippe.boudet@peugeot.com', '617951500', NULL, NULL, '15', 45.839812, 5.001999, 421119),
            (2, 13091, 'Marc DUCARNE - BLASIMON', 'Marc DUCARNE', NULL, NULL, '33540', 'BLASIMON', NULL, 'marc.ducarne@peugeot.com', '608751251', NULL, NULL, '16', 44.748676, -0.075308, 421119),
            (2, 13092, 'Jean-Luc MALO - BONCHAMP LES LAVAL', 'Jean-Luc MALO', NULL, NULL, '53960', 'BONCHAMP LES LAVAL', NULL, 'jeanluc.malo@peugeot.com', '607462784', NULL, NULL, '17', 48.073661, -0.700079, 421119),
            (2, 13093, 'Philippe BOUDET - BOURG DE PEAGE', 'Philippe BOUDET', NULL, NULL, '26300', 'BOURG DE PEAGE', NULL, 'philippe.boudet@peugeot.com', '617951500', NULL, NULL, '18', 45.040419, 5.050948, 421119),
            (2, 13094, 'GARAGE DE LA PEPINIERE', 'MARTIN JOEL', '634 ROUTE DE BRIONNE', NULL, '27520', 'BOURGTHEROULDE', NULL, NULL, '235876083', NULL, NULL, '19', 49.299662, 0.873662, 421119),
            (2, 13095, 'Christian CHANET - BOUZONVILLE', 'Christian CHANET', NULL, NULL, '57320', 'BOUZONVILLE', NULL, 'christian.chanet@peugeot.com', '614568380', NULL, NULL, '20', 49.290564, 6.536161, 421119),
            (2, 13096, 'Philippe BOUDET - BRIENON SUR ARMANCON', 'Philippe BOUDET', NULL, NULL, '89210', 'BRIENON SUR ARMANCON', NULL, 'philippe.boudet@peugeot.com', '617951500', NULL, NULL, '21', 47.991832, 3.617134, 421119),
            (2, 13097, 'Philippe BOUDET - BRULLIOLES', 'Philippe BOUDET', NULL, NULL, '69690', 'BRULLIOLES', NULL, 'philippe.boudet@peugeot.com', '617951500', NULL, NULL, '22', 45.762049, 4.499322, 421119),
            (2, 13098, 'Philippe BOUDET - CASTAGNIERS', 'Philippe BOUDET', NULL, NULL, '6670', 'CASTAGNIERS', NULL, 'philippe.boudet@peugeot.com', '617951500', NULL, NULL, '23', 43.789745, 7.228893, 421119),
            (2, 13099, 'GARAGE ARZENTON BERNARD', 'M. ARZENTON BERNARD', '47 AV DE LA LIBERATION', NULL, '47700', 'CASTELJALOUX', NULL, NULL, '553939540', NULL, NULL, '24', 44.313746, 0.088354, 421119)
            ;
INSERT INTO migration_versions (version) VALUES ('20150428142153');
# Doctrine Migration File Generated on 2015-05-22 13:12:11

# Version 20150428162821
INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_MEDIA_SHOWROOM', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_MEDIA_SHOWROOM_INFO', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_VERSION_WEB', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_COLUMN_1_4', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_COLUMN_3_4', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_MODELCAR', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_ANCRE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_VERSION_MOBILE', NULL, 2, NULL, NULL, 1, NULL)
                ;
INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_MEDIA_SHOWROOM', 1, 1, 'Média des pages du showroom'),
                ('NDP_VERSION_MOBILE', 1, 1, 'Version mobile'),
                ('NDP_VERSION_WEB', 1, 1, 'Version web'),
                ('NDP_COLUMN_1_4', 1, 1, 'Colonne 1/4'),
                ('NDP_COLUMN_3_4', 1, 1, 'Colonne 3/4'),
                ('NDP_ANCRE', 1, 1, 'Ancres (Cette tranche est à remplir en fin de construction de page) '),
                ('NDP_MODELCAR', 1, 1, 'Modèle de véhicules'),
                ('NDP_MEDIA_SHOWROOM_INFO', 1, 1,  'Veuillez cocher les média que vous souhaitez afficher dans le mur média')          
                ;
INSERT INTO migration_versions (version) VALUES ('20150428162821');
# Doctrine Migration File Generated on 2015-05-22 13:12:15

# Version 20150430163425
INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_VIGNETTE_VIDEO', NULL, 2, NULL, NULL, 1, NULL),          
                ('NDP_COLOR_TITLE_SUBTITLE', NULL, 2, NULL, NULL, 1, NULL), 
                ('NDP_POS_TITLE_SUBTITLE_CTA', NULL, 2, NULL, NULL, 1, NULL),                
                ('NDP_SHOW_COLONNE', NULL, 2, NULL, NULL, 1, NULL),                
                ('NDP_SHOW_BLOC', NULL, 2, NULL, NULL, 1, NULL),
                ('LISTE_DEROULANTE_CTA', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_LIEN_INT_EXT', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_SLIDESHOW_POPIN', NULL, 2, NULL, NULL, 1, NULL)
                ;
INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_VIGNETTE_VIDEO', 1, 1, 'Vignette vidéo'),          
                ('NDP_COLOR_TITLE_SUBTITLE', 1, 1, 'Couleur titre + sous-titre'),
                ('NDP_POS_TITLE_SUBTITLE_CTA', 1, 1, 'Positionnement titre + Sous-titre + CTA'),                
                ('NDP_SHOW_COLONNE', 1, 1, 'Afficher la colonne'),                
                ('NDP_SHOW_BLOC', 1, 1, 'Afficher le bloc'),                
                ('LISTE_DEROULANTE_CTA', 1, 1, 'Liste déroulante de CTA'),            
                ('NDP_LIEN_INT_EXT', 1, 1, 'Lien internet/externe'),         
                ('NDP_SLIDESHOW_POPIN', 1, 1, 'Slideshow (pop-in)')         
                ;
UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Nombre de tranches correspondant à l\'onglet' WHERE LABEL_ID ='NDP_NB_ZONE' AND SITE_ID = 1 AND LANGUE_ID = 1;
UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Type d\'affichage' WHERE LABEL_ID ='NDP_TYPE_AFFICHAGE' AND SITE_ID = 1 AND LANGUE_ID = 1;
INSERT INTO migration_versions (version) VALUES ('20150430163425');
# Doctrine Migration File Generated on 2015-05-22 13:12:18

# Version 20150430175944
DELETE FROM psa_directory_site WHERE DIRECTORY_ID IN (63, 64, 65, 193, 194, 203, 206, 208, 209, 210, 211,223, 231, 232);
DELETE FROM psa_profile_directory WHERE DIRECTORY_ID IN (63, 64, 65, 193, 194, 203, 206, 208, 209, 210, 211,223, 231, 232);
DELETE FROM psa_directory WHERE DIRECTORY_ID IN (63, 64, 65, 193, 194, 203, 206, 208, 209, 210, 211,223, 231, 232);
INSERT INTO psa_template (TEMPLATE_ID, TEMPLATE_TYPE_ID, TEMPLATE_GROUP_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_COMPLEMENT, PLUGIN_ID) VALUES
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
          ;
INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
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
           ;
INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES
            (82, 2), (83, 2), (84, 2), (85, 2), (86, 2), (87, 2), (88, 2), (89, 2),
            (90, 2), (91, 2), (92, 2), (93, 2), (94, 2), (95, 2), (96, 2), (97, 2), (98, 2), (99, 2),
            (100, 2), (101, 2), (102, 2), (103, 2), (104, 2), (105, 2), (106, 2), (107, 2), (108, 2), (109, 2),
            (110, 2), (111, 2), (112, 2), (113, 2), (114, 2), (115, 2), (116, 2)
           ;
DELETE FROM psa_profile_directory WHERE PROFILE_ID = 2;
INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES
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
        ;
UPDATE psa_directory SET DIRECTORY_PARENT_ID = 82, DIRECTORY_LABEL = 'NDP_REF_RESEAUX_SOCIAUX_GPE' WHERE  DIRECTORY_ID =202;
UPDATE psa_directory SET DIRECTORY_PARENT_ID = 82, DIRECTORY_LABEL = 'NDP_REF_RESEAUX_SOCIAUX_PARAM' WHERE  DIRECTORY_ID =192;
UPDATE psa_directory SET DIRECTORY_LABEL = 'NDP_REFERENTIEL_CTA' WHERE  DIRECTORY_ID =233;
INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
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
            ;
INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
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
            ;
INSERT INTO migration_versions (version) VALUES ('20150430175944');
# Doctrine Migration File Generated on 2015-05-22 13:12:21

# Version 20150504100735
DELETE FROM psa_page_zone_multi WHERE ZONE_TEMPLATE_ID in (SELECT pzt.ZONE_TEMPLATE_ID FROM `psa_zone_template` pzt WHERE ZONE_ID = 801);;
DELETE FROM psa_page_zone WHERE ZONE_TEMPLATE_ID in (SELECT pzt.ZONE_TEMPLATE_ID FROM `psa_zone_template` pzt WHERE ZONE_ID = 801);;
DELETE FROM psa_page_multi_zone_multi WHERE AREA_ID in (SELECT pzt.AREA_ID FROM `psa_zone_template` pzt WHERE ZONE_ID = 801);;
DELETE FROM psa_page_multi_zone WHERE AREA_ID in (SELECT pz.AREA_ID FROM `psa_zone_template` pz WHERE ZONE_ID = 801);;
DELETE FROM psa_zone_template  WHERE ZONE_ID = 801;;
INSERT INTO psa_zone_template (ZONE_TEMPLATE_ID, ZONE_TEMPLATE_LABEL, TEMPLATE_PAGE_ID, AREA_ID, ZONE_ID, ZONE_TEMPLATE_ORDER, ZONE_TEMPLATE_MOBILE_ORDER, ZONE_TEMPLATE_TABLET_ORDER, ZONE_TEMPLATE_TV_ORDER, ZONE_CACHE_TIME) VALUES ('9', 'NDP_PT3_JE_VEUX', '150', '122', '801', '9', NULL, NULL, NULL, '30');;
INSERT INTO migration_versions (version) VALUES ('20150504100735');
# Doctrine Migration File Generated on 2015-05-22 13:12:24

# Version 20150504145151
INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_3_4', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_1_4', NULL, 2, NULL, NULL, 1, NULL)
                ;
INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_3_4', 1, 1, '3/4 - 1/4'),
                ('NDP_1_4', 1, 1, '1/4 - 3/4')
                ;
INSERT INTO migration_versions (version) VALUES ('20150504145151');
# Doctrine Migration File Generated on 2015-05-22 13:12:28

# Version 20150504160055
UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Colonne 1' WHERE LABEL_ID ='NDP_COLONNE1' AND SITE_ID = 1 AND LANGUE_ID = 1;
UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Colonne 2' WHERE LABEL_ID ='NDP_COLONNE2' AND SITE_ID = 1 AND LANGUE_ID = 1;
UPDATE psa_label_langue_site SET LABEL_TRANSLATE = 'Colonne 3' WHERE LABEL_ID ='NDP_COLONNE3' AND SITE_ID = 1 AND LANGUE_ID = 1;
INSERT INTO migration_versions (version) VALUES ('20150504160055');
# Doctrine Migration File Generated on 2015-05-22 13:12:31

# Version 20150504164059
DELETE FROM `psa_label`  WHERE  `LABEL_ID` IN
             (
             "NDP_COLONNE4"
             )
        ;
DELETE FROM `psa_label_langue_site`  WHERE  `LABEL_ID` IN
             (
             "NDP_COLONNE4"
             )
        ;
INSERT INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
                ("NDP_COLONNE4", NULL, 2, NULL, NULL, 1, NULL)
                ;
INSERT INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
                 ("NDP_COLONNE4", 1, 1, "Colonne 4")
                 ;
INSERT INTO migration_versions (version) VALUES ('20150504164059');
# Doctrine Migration File Generated on 2015-05-22 13:12:34

# Version 20150505154333
CREATE TABLE psa_type_couleur (ID INT AUTO_INCREMENT NOT NULL, CODE VARCHAR(2) NOT NULL, LABEL_CENTRAL VARCHAR(255) NOT NULL, PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB;
CREATE TABLE psa_type_couleur_site (LABEL_LOCAL VARCHAR(255) NOT NULL, ORDER_TYPE INT DEFAULT 1, ID INT NOT NULL, LANGUE_ID INT NOT NULL, SITE_ID INT NOT NULL, INDEX IDX_46C6250E11D3633A (ID), INDEX IDX_46C6250E5622E2C2 (LANGUE_ID), INDEX IDX_46C6250EF1B5AEBC (SITE_ID), PRIMARY KEY(ID, LANGUE_ID, SITE_ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB;
ALTER TABLE psa_type_couleur_site ADD CONSTRAINT FK_46C6250E11D3633A FOREIGN KEY (ID) REFERENCES psa_type_couleur (ID);
ALTER TABLE psa_type_couleur_site ADD CONSTRAINT FK_46C6250E5622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID);
ALTER TABLE psa_type_couleur_site ADD CONSTRAINT FK_46C6250EF1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID);
INSERT INTO migration_versions (version) VALUES ('20150505154333');
# Doctrine Migration File Generated on 2015-05-22 13:12:37

# Version 20150505155039
INSERT INTO psa_template (TEMPLATE_ID, TEMPLATE_TYPE_ID, TEMPLATE_GROUP_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_COMPLEMENT, PLUGIN_ID) VALUES
             (101, 1, 5, 'NDP_REF_CARSELECTORFILTER', 'Ndp_CarSelectorFilter', '', NULL, '')
          ;
INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
            (117, 101, 4, 0, NULL, NULL, 'NDP_REF_CARSELECTORFILTER', NULL, NULL)
           ;
INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES
            (117, 2)
           ;
INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES
            (2, 117, 2067)
        ;
INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_REF_CARSELECTORFILTER', NULL, 2, NULL, NULL, 1, NULL);
INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_REF_CARSELECTORFILTER', 1, 1, 'Filtres Car selector');
INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ("NDP_FILTER_PRICE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_ENERGY", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_GEARBOX_TYPE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_CONSO", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_CLASS", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_SEAT_NB", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_LENGTH", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_WIDTH", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_HEIGHT", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_VOLUME", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_VOLUME_LVL0", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_VOLUME_LVL1", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_VOLUME_LVL2", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_FILTER_VOLUME_MAXVALUE", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_GAUGE_STEP", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_FILTER_CARSELECTOR", NULL, 2, NULL, NULL, 1, NULL),
                ("NDP_MSG_FILTER_CLASS", NULL, 2, NULL, NULL, 1, NULL)
               ;
INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ("NDP_FILTER_PRICE", 1, 1, "Prix"),
                ("NDP_FILTER_ENERGY", 1, 1, "Energie"),
                ("NDP_FILTER_GEARBOX_TYPE", 1, 1, "Types de boite de vitesse"),
                ("NDP_FILTER_CONSO", 1, 1, "Consommation"),
                ("NDP_FILTER_CLASS", 1, 1, "Classe énergétique"),
                ("NDP_FILTER_SEAT_NB", 1, 1, "Nombre de place"),
                ("NDP_FILTER_LENGTH", 1, 1, "Longueur"),
                ("NDP_FILTER_WIDTH", 1, 1, "Largeur"),
                ("NDP_FILTER_HEIGHT", 1, 1, "Hauteur"),
                ("NDP_FILTER_VOLUME", 1, 1, "Volume du coffre"),
                ("NDP_FILTER_VOLUME_LVL0", 1, 1, "Petit"),
                ("NDP_FILTER_VOLUME_LVL1", 1, 1, "Moyen"),
                ("NDP_FILTER_VOLUME_LVL2", 1, 1, "Grand"),
                ("NDP_FILTER_VOLUME_MAXVALUE", 1, 1, "Valeur volume max"),
                ("NDP_GAUGE_STEP", 1, 1, "Pas de la jauge"),
                ("NDP_MSG_FILTER_CARSELECTOR", 1, 1, "Les filtres doivent être paramétrés avant toute activation sur le car selector."),
                ("NDP_MSG_FILTER_CLASS", 1, 1, "Libellés des classes énergétiques (ex : < 150 g/km)")
        ;
CREATE TABLE psa_carselectorfilter (
              SITE_ID int(11) NOT NULL,
              PRICE_GAUGE float NOT NULL,
              CONSO_GAUGE float NOT NULL,
              LENGTH_GAUGE float NOT NULL,
              WIDTH_GAUGE float NOT NULL,
              HEIGHT_GAUGE float NOT NULL,
              VOLUME_LVL1 int(11) NOT NULL,
              VOLUME_LVL2 int(11) NOT NULL,
              CLASS_A_LABEL varchar(255) NOT NULL,
              CLASS_B_LABEL varchar(255) NOT NULL,
              CLASS_C_LABEL varchar(255) NOT NULL,
              CLASS_D_LABEL varchar(255) NOT NULL,
              CLASS_E_LABEL varchar(255) NOT NULL,
              CLASS_F_LABEL varchar(255) NOT NULL,
              CLASS_G_LABEL varchar(255) NOT NULL,
              PRIMARY KEY (SITE_ID)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ;
INSERT INTO migration_versions (version) VALUES ('20150505155039');
# Doctrine Migration File Generated on 2015-05-22 13:12:41

# Version 20150505172933
DELETE FROM psa_label where LABEL_ID = "COMPARER_AVEC_UN_AUTRE_MODELE_CITROEN";
DELETE FROM psa_label_langue_site where LABEL_ID = "COMPARER_AVEC_UN_AUTRE_MODELE_CITROEN";
DELETE FROM psa_label where LABEL_ID = "GAMME_LIGNE_C_FO";
DELETE FROM psa_label_langue_site where LABEL_ID = "GAMME_LIGNE_C_FO";
DELETE FROM psa_label where LABEL_ID = "GAMME_LIGNE_DS_FO";
DELETE FROM psa_label_langue_site where LABEL_ID = "GAMME_LIGNE_DS_FO";
DELETE FROM psa_label where LABEL_ID = "GAMME_VEHICULE_UTILITAIRE_FO";
DELETE FROM psa_label_langue_site where LABEL_ID = "GAMME_VEHICULE_UTILITAIRE_FO";
INSERT INTO migration_versions (version) VALUES ('20150505172933');
