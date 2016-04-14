<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160211095152 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('REPLACE INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
(\'NDP_POS_TITLE_SUBTITLE_CTA\', 1, 1, \'Positionnement titre + Sous-titre\')');

        $this->upTranslations(
            array(
                'NDP_DARK_BLUE' => array(
                    'expression' => 'Bleu nuit',
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
        $this->addSql('REPLACE INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
(\'NDP_POS_TITLE_SUBTITLE_CTA\', 1, 1, \'Positionnement titre + Sous-titre + CTA\')');

        $this->downTranslations(array(
            'NDP_DARK_BLUE',
        ));
    }
}
