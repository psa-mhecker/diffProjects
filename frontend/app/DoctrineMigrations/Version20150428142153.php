<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150428142153 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // FIX maj type directory template ref cta
        $this->addSql("UPDATE psa_template SET TEMPLATE_TYPE_ID = 1, TEMPLATE_LABEL = 'NDP_REFERENTIEL_CTA' WHERE  TEMPLATE_ID =332");
        // FIX maj type directory template ref cta (end)

        // referentiel devenir agent
        $this->addSql("INSERT INTO psa_template (TEMPLATE_ID, TEMPLATE_TYPE_ID, TEMPLATE_GROUP_ID, TEMPLATE_LABEL, TEMPLATE_PATH, TEMPLATE_PATH_FO, TEMPLATE_COMPLEMENT, PLUGIN_ID) VALUES
            (75, 1, 5, 'NDP_REF_DEALERLOC_DEVENIRAGENT', 'Ndp_DealerLocDevenirAgent', '', NULL, '')
            ");

        $this->addSql("INSERT INTO psa_directory (DIRECTORY_ID, TEMPLATE_ID, DIRECTORY_PARENT_ID, DIRECTORY_ADMIN, TEMPLATE_COMPLEMENT, DIRECTORY_LEFT_LABEL, DIRECTORY_LABEL, DIRECTORY_ICON, DIRECTORY_DEFAULT) VALUES
            (80, NULL, 62, 0, NULL, NULL, 'NDP_REF_DEALERLOC', NULL, NULL),
            (81, 75, 80, 0, NULL, NULL, 'NDP_REF_DEALERLOC_DEVENIRAGENT', NULL, NULL)
            ");

        $this->addSql("INSERT INTO psa_directory_site (DIRECTORY_ID, SITE_ID) VALUES
            (80, 2),
            (81, 2)
            ");

        $this->addSql("INSERT INTO psa_profile_directory (PROFILE_ID, DIRECTORY_ID, PROFILE_DIRECTORY_ORDER) VALUES
            (2, 80, NULL),
            (2, 81, NULL)
            ");

        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
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
               ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
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
            ");

        $this->addSql("DELETE FROM psa_pdv_deveniragent");

        // jeu de donnees test
        $this->addSql("INSERT INTO psa_pdv_deveniragent (SITE_ID, PDV_DEVENIRAGENT_ID, PDV_DEVENIRAGENT_NAME, PDV_DEVENIRAGENT_DESC, PDV_DEVENIRAGENT_ADDRESS1, PDV_DEVENIRAGENT_ADDRESS2, PDV_DEVENIRAGENT_ZIPCODE, PDV_DEVENIRAGENT_CITY, PDV_DEVENIRAGENT_COUNTRY, PDV_DEVENIRAGENT_EMAIL, PDV_DEVENIRAGENT_TEL1, PDV_DEVENIRAGENT_TEL2, PDV_DEVENIRAGENT_FAX, PDV_DEVENIRAGENT_RRDI, PDV_DEVENIRAGENT_LAT, PDV_DEVENIRAGENT_LNG, PDV_DEVENIRAGENT_LIAISON_ID) VALUES
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
            ");




    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.' WHERE  LABEL_ID IN
             (
             "NDP_REF_DEALERLOC", "NDP_REF_DEALERLOC_DEVENIRAGENT",
             "NDP_DEVENIRAGENT_IMPORT_KO","NDP_DEVENIRAGENT_CSV_KO","NDP_DEVENIRAGENT_UTF8_KO","NDP_DEVENIRAGENT_NBFIELD_KO",
             "NDP_DEVENIRAGENT_HEADER_KO","NDP_DEVENIRAGENT_IMPORT_OK","NDP_DEVENIRAGENT_ROW_ADDED",
             "NDP_DEVENIRAGENT_LIAISON_421119", "NDP_DEVENIRAGENT_LIAISON_421122"
             )
            ');

        }
        $this->addSql("DELETE FROM psa_pdv_deveniragent");

    }
}
