<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151208120618 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("DELETE from `psa_user_role` where site_id not in (select site_id from `psa_site`)");
        $this->addSql("ALTER TABLE `psa_user_role`
                        ADD CONSTRAINT `FK_USER_ROLE_12` FOREIGN KEY (`SITE_ID`) REFERENCES `psa_site` (`SITE_ID`)
                        ON DELETE CASCADE");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("ALTER TABLE `psa_user_role` DROP FOREIGN KEY FK_USER_ROLE_12");
    }
}
