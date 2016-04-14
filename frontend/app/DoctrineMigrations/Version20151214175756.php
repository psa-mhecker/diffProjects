<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151214175756 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
          DELETE FROM `psa_zone_template` WHERE `ZONE_TEMPLATE_LABEL` IN
                ("NDP_PC18_CONTENU_GRAND_VISUEL",
                "NDP_PC2_CONTENU_TEXTE_RICHE",
                "NDP_PC33_OFFRE_PLUS",
                "NDP_PC58_CONTACT",
                "PC2",
                "NDP - PC18 Contenu Grand visuel");
        ');

        $this->addSql('
          DELETE FROM `psa_zone` WHERE `ZONE_LABEL` IN
                ("NDP_PC18_CONTENU_GRAND_VISUEL",
                "NDP_PC2_CONTENU_TEXTE_RICHE",
                "NDP_PC33_OFFRE_PLUS",
                "NDP_PC58_CONTACT");
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
