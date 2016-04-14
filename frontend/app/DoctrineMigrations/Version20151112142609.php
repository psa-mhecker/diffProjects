<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151112142609 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("REPLACE INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
              ('NDP_BACK_TOP_PAGE', null, 2, null, null, null, 1)
        ");

        $this->addSql("REPLACE INTO psa_label_langue (LABEL_ID, LANGUE_ID, LABEL_TRANSLATE, LABEL_PATH) VALUES
              ('NDP_BACK_TOP_PAGE', 1, 'Retour en haut de la page', null),
              ('NDP_BACK_TOP_PAGE', 2, 'Back to top page', null)
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM psa_label WHERE LABEL_ID = 'NDP_BACK_TOP_PAGE' AND  LABEL_FO = 1");
        $this->addSql("DELETE FROM psa_label_langue WHERE LABEL_ID = 'NDP_BACK_TOP_PAGE'");
    }
}
