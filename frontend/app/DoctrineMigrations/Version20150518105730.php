<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150518105730 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
                ('NDP_TELEPHONE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_LIBELLE_SOUS_TITRE', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_NEWSLETTER', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_NEWSLETTER_OU_TELEPHONES', NULL, 2, NULL, NULL, 1, NULL),
                ('NDP_VISUELS', NULL, 2, NULL, NULL, 1, NULL)
                ");
        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
                ('NDP_TELEPHONE', 1, 1, 'Téléphone'),
                ('NDP_LIBELLE_SOUS_TITRE', 1, 1, 'Libellé sous-titre '),
                ('NDP_NEWSLETTER', 1, 1, 'Newsletter'),
                ('NDP_NEWSLETTER_OU_TELEPHONES', 1, 1, 'Newsletter ou Téléphone(s)'),
                ('NDP_VISUELS', 1, 1, 'Visuels')
                ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                "DELETE FROM `".$table."`  WHERE  `LABEL_ID` IN
                 (
                 'NDP_TELEPHONE',
                 'NDP_LIBELLE_SOUS_TITRE',
                 'NDP_NEWSLETTER',
                 'NDP_NEWSLETTER_OU_TELEPHONES',
                 'NDP_VISUELS'
                 )
                "
            );
        }
    }
}
