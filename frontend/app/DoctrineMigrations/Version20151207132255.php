<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151207132255 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('REPLACE INTO `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES
            ("NDP_ERROR_VIDEO_STREAMLIKE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_BROWSER_UNCONFORMABLE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_CLICK_HERE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_IGNORE", NULL, 2, NULL, NULL, NULL, 1)
        ');

        $this->addSql('REPLACE INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
            ("NDP_ERROR_VIDEO_STREAMLIKE", 1, "echec de chargement du média", ""),
            ("NDP_BROWSER_UNCONFORMABLE", 1, "Vous utilisez une version de navigateur non compatible avec le site Peugeot. Pour une meilleure expérience vous pouvez faire une montée de version.", ""),
            ("NDP_CLICK_HERE", 1, "Cliquez ici", ""),
            ("NDP_IGNORE", 1, "Ignorez", "")
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->downTranslations(array(
            'NDP_ERROR_VIDEO_STREAMLIKE',
            'NDP_BROWSER_UNCONFORMABLE',
            'NDP_CLICK_HERE',
            'NDP_IGNORE',
        ));
    }
}
