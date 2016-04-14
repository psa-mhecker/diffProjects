<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150810091437 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("REPLACE INTO psa_label_langue (LABEL_ID, LANGUE_ID, LABEL_TRANSLATE, LABEL_PATH) VALUES
                    ('NDP_CHOOSE_LANGUAGE', 1, 'Choisissez une langue', ''),
                    ('NDP_CHOOSE_LANGUAGE', 2, 'Choose your language', ''),
                    ('NDP_CHOOSE_LANGUAGE', 4, 'Elige tu idioma', ''),
                    ('NDP_CHOOSE_LANGUAGE', 10, 'Wahlen sie ihre sprache', '')");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
