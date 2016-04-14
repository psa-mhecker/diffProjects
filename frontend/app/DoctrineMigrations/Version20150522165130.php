<?php namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150522165130 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE psa_ws_gdg_model_silhouette_upselling (ID INT AUTO_INCREMENT NOT NULL, FINISHING_CODE VARCHAR(8) NOT NULL, FINISHING_LABEL VARCHAR(255) NOT NULL, UPSELLING TINYINT(1) DEFAULT \'1\', MODEL_SILHOUETTE_ID INT DEFAULT NULL, FINISHING_REFERENCE INT DEFAULT NULL, INDEX IDX_23B42BB5EBE647F0 (MODEL_SILHOUETTE_ID), INDEX IDX_23B42BB5127F2945 (FINISHING_REFERENCE), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE psa_ws_gdg_model_silhouette (ID INT AUTO_INCREMENT NOT NULL, GENDER VARCHAR(255) NOT NULL, LCDV6 VARCHAR(16) NOT NULL, GROUPING_CODE VARCHAR(255) NOT NULL, COMMERCIAL_LABEL VARCHAR(255) NOT NULL, SHOW_FINISHING INT DEFAULT 0, NEW_COMMERCIAL_STRIP TINYINT(1) DEFAULT \'0\' NOT NULL, SPECIAL_OFFER_COMMERCIAL_STRIP TINYINT(1) DEFAULT \'0\' NOT NULL, SPECIAL_SERIES_COMMERCIAL_STRIP TINYINT(1) DEFAULT \'0\' NOT NULL, SHOW_IN_CONFIG TINYINT(1) DEFAULT \'0\' NOT NULL, STOCK_WEBSTORE TINYINT(1) DEFAULT \'0\', LANGUE_ID INT DEFAULT NULL, SITE_ID INT DEFAULT NULL, INDEX IDX_9FAC456B5622E2C2 (LANGUE_ID), INDEX IDX_9FAC456BF1B5AEBC (SITE_ID), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling ADD CONSTRAINT FK_23B42BB5EBE647F0 FOREIGN KEY (MODEL_SILHOUETTE_ID) REFERENCES psa_ws_gdg_model_silhouette (ID)');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling ADD CONSTRAINT FK_23B42BB5127F2945 FOREIGN KEY (FINISHING_REFERENCE) REFERENCES psa_ws_gdg_model_silhouette_upselling (ID)');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette ADD CONSTRAINT FK_9FAC456B5622E2C2 FOREIGN KEY (LANGUE_ID) REFERENCES psa_language (LANGUE_ID)');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette ADD CONSTRAINT FK_9FAC456BF1B5AEBC FOREIGN KEY (SITE_ID) REFERENCES psa_site (SITE_ID)');


        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_STRIP', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_MSG_STRIP', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_CONFIG', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_GRP_SILH_MSG_CONFIG', NULL, 2, NULL, NULL, 1, NULL),                
                ('NDP_STOCK_WEBSTORE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_GRP_SILH_MSG_STOCK_WEBSTORE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_PRESENTATION_SHOW_FINISHING', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_SHOW_FINISHING', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_PER_GROUPING_SILOUHETTE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_PER_SILOUHETTE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_GRP_SILH_MSG_UPSELLING', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_FINISHING_CODE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_FINISHING_LABEL', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_FINISHING_REFERENCE', NULL, 2, NULL, NULL, 1, NULL)
                ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_STRIP', 1, 1, 'Languettes'),
                ('NDP_MSG_STRIP', 1, 1, 'Activation des languettes commerciales sur le Car Selector pour ce modèle/regroupement de silhouettes.'),
                ('NDP_CONFIG', 1, 1, 'Configurateur'),
                ('NDP_GRP_SILH_MSG_CONFIG', 1, 1, 'Il est nécessaire d’activer le Configurateur dans les Sites et webservices PSA afin de l’activer par modèle / regroupement de silhouettes.'),
                ('NDP_STOCK_WEBSTORE', 1, 1, 'Stocks webStore'),
                ('NDP_GRP_SILH_MSG_STOCK_WEBSTORE', 1, 1, 'Il est nécessaire d’activer webStore dans les Sites et webservices PSA afin d’activer les stocks pour ce modèle / regroupement de silhouettes.'),
                ('NDP_PRESENTATION_SHOW_FINISHING', 1, 1, 'Présentation et affichage des finitions'),
                ('NDP_SHOW_FINISHING', 1, 1, 'Affichage des finitions'),
                ('NDP_PER_GROUPING_SILOUHETTE', 1, 1, 'Par regroupement de silhouette'),
                ('NDP_PER_SILOUHETTE', 1, 1, 'Par silhouette'),
                ('NDP_GRP_SILH_MSG_UPSELLING', 1, 1, 'L\'activation/désactivation de la montée en gamme pour une finition surcharge les paramétrages affectés pour le segment de finitions et tous modèles.'),
                ('NDP_FINISHING_CODE', 1, 1, 'Code de la finition'),
                ('NDP_FINISHING_LABEL', 1, 1, 'Finition'),
                ('NDP_FINISHING_REFERENCE', 1, 1, 'Finition de référence')
                ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE psa_ws_gdg_model_silhouette_upselling');
        $this->addSql('DROP TABLE psa_ws_gdg_model_silhouette');

        $tables = array('psa_label', 'psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                "DELETE FROM `".$table."`  WHERE  `LABEL_ID` IN
                 (
                 'NDP_STRIP',
                'NDP_MSG_STRIP',
                'NDP_CONFIG',
                'NDP_GRP_SILH_MSG_CONFIG',                
                'NDP_STOCK_WEBSTORE',
                'NDP_MSG_GRP_SILH_WEBSTORE',
                'NDP_PRESENTATION_SHOW_FINISHING',
                'NDP_SHOW_FINISHING',
                'NDP_PER_GROUPING_SILOUHETTE',
                'NDP_PER_SILOUHETTE',
                'NDP_MSG_GRP_SILH_UPSELLING',
                'NDP_FINISHING_CODE',
                'NDP_FINISHING_LABEL',
                'NDP_FINISHING_REFERENCE'
                 )
                "
            );
        }
    }
}
