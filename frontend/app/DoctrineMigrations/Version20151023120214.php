<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151023120214 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
    // mise a jour de l'encodate des tables
    // pas d'impact sur cette table on ne stock que des int (id)
    $this->addSql('ALTER TABLE psa_accessoires CONVERT TO CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    // impact possible
    $this->addSql('ALTER TABLE psa_accessoires_site CONVERT TO CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    // conversion des champs de la table
    $this->addSql('ALTER TABLE psa_accessoires_site MODIFY CTA_ERREUR_ACTION VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_accessoires_site MODIFY CTA_ERREUR_TITLE VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_accessoires_site MODIFY CTA_ERREUR_STYLE VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_accessoires_site MODIFY CTA_ERREUR_TARGET VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    // impact possible
    $this->addSql('ALTER TABLE psa_finishing_badge CONVERT TO CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    // conversion des champs de la table
    $this->addSql('ALTER TABLE psa_finishing_badge MODIFY LABEL  VARCHAR (50) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_finishing_badge MODIFY BADGE_URL VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    // pas d'impact la table est mis a jour par le WS
    $this->addSql('ALTER TABLE psa_finishing_site CONVERT TO CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    // conversion des champs de la table
    $this->addSql('ALTER TABLE psa_finishing_site MODIFY CODE  VARCHAR (8) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_finishing_site MODIFY FINITION VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_finishing_site MODIFY VERSIONS_CRITERION VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_finishing_site MODIFY CUSTOMER_TYPE VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    // impact possible
    $this->addSql('ALTER TABLE psa_media_alt_translation CONVERT TO CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    // conversion des champs de la table
    $this->addSql('ALTER TABLE psa_media_alt_translation MODIFY TITLE  VARCHAR (255) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_media_alt_translation MODIFY ALT VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci');

    // impact possible mais l'import du fichier devrai regler le pb
    $this->addSql('ALTER TABLE psa_pdv_deveniragent CONVERT TO CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    // conversion des champs de la table
    $this->addSql('ALTER TABLE psa_pdv_deveniragent MODIFY PDV_DEVENIRAGENT_NAME  VARCHAR (255) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_pdv_deveniragent MODIFY PDV_DEVENIRAGENT_DESC VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_pdv_deveniragent MODIFY PDV_DEVENIRAGENT_ADDRESS1 VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_pdv_deveniragent MODIFY PDV_DEVENIRAGENT_ADDRESS2 VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_pdv_deveniragent MODIFY PDV_DEVENIRAGENT_ZIPCODE VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_pdv_deveniragent MODIFY PDV_DEVENIRAGENT_CITY VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_pdv_deveniragent MODIFY PDV_DEVENIRAGENT_COUNTRY VARCHAR(2) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_pdv_deveniragent MODIFY PDV_DEVENIRAGENT_EMAIL VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_pdv_deveniragent MODIFY PDV_DEVENIRAGENT_TEL1 VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_pdv_deveniragent MODIFY PDV_DEVENIRAGENT_TEL2 VARCHAR (20) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_pdv_deveniragent MODIFY PDV_DEVENIRAGENT_FAX VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    $this->addSql('ALTER TABLE psa_pdv_deveniragent MODIFY PDV_DEVENIRAGENT_RRDI VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    // impact possible
    $this->addSql('ALTER TABLE psa_site_national_param CONVERT TO CHARACTER SET utf8 COLLATE utf8_swedish_ci');
    // conversion des champs de la table
    $this->addSql('ALTER TABLE psa_site_national_param MODIFY NATIONAL_PARAMS  LONGTEXT CHARACTER SET utf8 COLLATE utf8_swedish_ci');



    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
