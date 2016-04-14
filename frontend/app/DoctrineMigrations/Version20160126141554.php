<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160126141554 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE  `psa_site` ADD  `STREAMLIKE_DEFAULT_COVER` INT NULL DEFAULT NULL AFTER `STREAMLIKE_CACHETIME`");
        $this->upTranslations(
            array(
                "STREAMLIKE_DEFAULT_COVER" =>array(
                    'expression' => 'Image par défaut',
                    'bo'=>1,
                ),
            )
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE `psa_site` DROP `STREAMLIKE_DEFAULT_COVER`");
        $this->downTranslations(array(
            'STREAMLIKE_DEFAULT_COVER',
        ));
    }
}
