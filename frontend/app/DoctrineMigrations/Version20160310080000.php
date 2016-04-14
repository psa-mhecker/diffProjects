<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160310080000 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(
            'UPDATE psa_label SET LABEL_FO = 1 WHERE LABEL_ID IN (
                "NDP_LIMITED_SERIE"
            )'
        );

        $this->addSql('INSERT INTO psa_label_langue (LABEL_ID, LANGUE_ID, LABEL_TRANSLATE, LABEL_PATH) VALUES
            ("NDP_LIMITED_SERIE", 1, "Série limité", "")
            ;'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM psa_label_langue WHERE LABEL_ID="NDP_LIMITED_SERIE"');
        $this->addSql(
            'UPDATE psa_label SET LABEL_FO = 0 WHERE LABEL_ID IN (
                "NDP_LIMITED_SERIE"
            )'
        );
    }
}
