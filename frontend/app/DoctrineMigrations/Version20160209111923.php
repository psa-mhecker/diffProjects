<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160209111923 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE `psa_cta` ADD `TITLE_MOBILE` VARCHAR(255) NULL AFTER`TITLE`');
        $this->upTranslations(
            array(
                'NDP_LABEL_CTA_MOBILE_FO' => array(
                    'expression' => 'Libellé CTA FO mobile',
                    'bo' => 1,
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
        $this->addSql('ALTER TABLE `psa_cta` DROP `TITLE_MOBILE`');
        $this->downTranslations(array(
            'NDP_LABEL_CTA_MOBILE_FO',
        ));
    }
}
