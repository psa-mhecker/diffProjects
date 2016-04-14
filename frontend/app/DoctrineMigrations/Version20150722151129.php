<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150722151129 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE IF EXISTS psa_ws_gdg_model_silhouette_upselling");
        $this->addSql('CREATE TABLE psa_ws_gdg_model_silhouette_upselling (ID INT AUTO_INCREMENT NOT NULL, LCDV16 VARCHAR(16) NOT NULL, FINISHING_CODE VARCHAR(8) NOT NULL, FINISHING_LABEL VARCHAR(255) NOT NULL, UPSELLING TINYINT(1) DEFAULT \'1\', BASE_PRICE DOUBLE PRECISION NOT NULL, MODEL_SILHOUETTE_ID INT DEFAULT NULL, FINISHING_REFERENCE INT DEFAULT NULL, UNIQUE INDEX UNIQ_23B42BB5A3AB466E (LCDV16), INDEX IDX_23B42BB5EBE647F0 (MODEL_SILHOUETTE_ID), INDEX IDX_23B42BB5127F2945 (FINISHING_REFERENCE), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling ADD CONSTRAINT FK_23B42BB5EBE647F0 FOREIGN KEY (MODEL_SILHOUETTE_ID) REFERENCES psa_ws_gdg_model_silhouette_site (ID)');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling ADD CONSTRAINT FK_23B42BB5127F2945 FOREIGN KEY (FINISHING_REFERENCE) REFERENCES psa_ws_gdg_model_silhouette_upselling (ID) ON DELETE CASCADE');
        
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_MSG_GRP_SILH_UPSELLING', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MSG_DEFAULT_FINISHING', NULL, 2, NULL, NULL, 1, NULL)
            ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_MSG_GRP_SILH_UPSELLING', 1, 1, 'L’activation/désactivation de la montée en gamme pour une finition surcharge les paramétrages affectés pour le segment de finitions et tous modèles.'),
            ('NDP_MSG_DEFAULT_FINISHING', 1, 1, 'La finition de référence par défaut est la finition directement inférieure en termes de prix.')
            ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE IF EXISTS psa_ws_gdg_model_silhouette_upselling");
        $this->addSql('CREATE TABLE psa_ws_gdg_model_silhouette_upselling (ID INT AUTO_INCREMENT NOT NULL, FINISHING_CODE VARCHAR(8) NOT NULL, FINISHING_LABEL VARCHAR(255) NOT NULL, UPSELLING TINYINT(1) DEFAULT \'1\', MODEL_SILHOUETTE_ID INT DEFAULT NULL, FINISHING_REFERENCE INT DEFAULT NULL, INDEX IDX_23B42BB5EBE647F0 (MODEL_SILHOUETTE_ID), INDEX IDX_23B42BB5127F2945 (FINISHING_REFERENCE), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling ADD CONSTRAINT FK_23B42BB5EBE647F0 FOREIGN KEY (MODEL_SILHOUETTE_ID) REFERENCES psa_ws_gdg_model_silhouette_site (ID) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_upselling ADD CONSTRAINT FK_23B42BB5127F2945 FOREIGN KEY (FINISHING_REFERENCE) REFERENCES psa_ws_gdg_model_silhouette_upselling (ID) ON DELETE CASCADE');

        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                "DELETE FROM `".$table."`  WHERE  `LABEL_ID` IN
                 (
                 'NDP_MSG_GRP_SILH_UPSELLING', 'NDP_MSG_DEFAULT_FINISHING'
                 )
                "
            );
        }
    }
}
