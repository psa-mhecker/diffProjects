<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160224133019 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // on supprime les ordre des contenu lié a des pages qui n'existe plus
        $this->addSql('DELETE po.* FROM psa_page_order po LEFT JOIN psa_page p ON (p.PAGE_ID = po.PAGE_ID AND po.LANGUE_ID=p.LANGUE_ID) WHERE p.PAGE_ID IS NULL');
	// clean content
	$this->addSql("delete from psa_page_multi_zone WHERE CONCAT(PAGE_ID,'-',LANGUE_ID) IN(select DISTINCT CONCAT(p.PAGE_ID,'-',p.LANGUE_ID) FROM psa_page p LEFT JOIN psa_page_version pv ON p.PAGE_ID = pv.PAGE_ID WHERE pv.PAGE_ID IS NULL)");
        //on ajoute les clef de contrainte pour le delete cascade
	$this->addSql('ALTER TABLE `psa_page_order` DROP  FOREIGN KEY `FK_PAGE_ORDER_11`');
        $this->addSql('ALTER TABLE psa_page_order ADD CONSTRAINT FK_1479D775B4EDB1E5622E2C2 FOREIGN KEY (PAGE_ID, LANGUE_ID) REFERENCES psa_page (PAGE_ID, LANGUE_ID) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_1479D775B4EDB1E5622E2C2 ON psa_page_order (PAGE_ID, LANGUE_ID)');
        // on supprime les pages qui n'ont pas de version

        $this->addSql('DELETE p.* FROM psa_page p LEFT JOIN psa_page_version pv ON p.PAGE_ID = pv.PAGE_ID WHERE pv.PAGE_ID IS NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

        $this->addSql('ALTER TABLE psa_page_order DROP FOREIGN KEY FK_1479D775B4EDB1E5622E2C2');
        $this->addSql('DROP INDEX IDX_1479D775B4EDB1E5622E2C2 ON psa_page_order');

    }
}
