<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150527151340 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE psa_template SET TEMPLATE_LABEL = "NDP_REF_RESEAUX_SOCIAUX_GPE", TEMPLATE_PATH="Ndp_GroupesReseauxSociaux"  WHERE TEMPLATE_ID=304');
        $this->addSql('UPDATE psa_template SET TEMPLATE_LABEL = "NDP_REF_RESEAUX_SOCIAUX_PARAM", TEMPLATE_PATH="Ndp_ReseauSocial"  WHERE TEMPLATE_ID=297');


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
