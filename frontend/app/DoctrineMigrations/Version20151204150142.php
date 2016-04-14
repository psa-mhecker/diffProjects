<?php

namespace Application\Migrations;

use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151204150142 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE psa_language ADD `LANGUE_DIRECTION` varchar(3) DEFAULT 'ltr' ");
        $this->addSql("UPDATE `psa_language` SET `LANGUE_DIRECTION`='rtl' WHERE `LANGUE_ID`='5'; ");
        $this->addSql("REPLACE `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES ('NDP_INFOBULLE_ICON_I', NULL, 2, NULL, NULL, NULL, 1);");
        $this->addSql("REPLACE `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`) VALUES ('NDP_INFOBULLE_ICON_I', 1, 'i')");
        $this->addSql("
        REPLACE `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES
        ('NDP_INFOBULLE_ICON_I', 1, 2, 'i'),
        ('NDP_INFOBULLE_ICON_I', 39, 2, 'b')
        ;");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE psa_language DROP COLUMN `LANGUE_DIRECTION`");
        $this->addSql("DELETE FROM psa_label WHERE `LABEL_ID` = 'NDP_INFOBULLE_ICON_I';");
        $this->addSql("DELETE FROM psa_label_langue WHERE `LABEL_ID` = 'NDP_INFOBULLE_ICON_I';");
        $this->addSql("DELETE FROM psa_label_langue_site WHERE `LABEL_ID` = 'NDP_INFOBULLE_ICON_I';");

    }
}
