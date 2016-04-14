<?php

namespace Application\Migrations;

use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151106115106 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        $this->addSql(
            sprintf(
                "REPLACE INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES ('%s', %d, 1, '%s')",
                'NDP_LA_DATE_SELECTIONNEE_DOIT_ETRE_SUPERIEURE_OU_EGALE_A_LA_DATE_DU_JOUR',
                1,
                addslashes('Attention, la date d\'affichage du décompte doit être supérieure ou égale à la date du jour, souhaitez-vous néanmoins continuer ? ')
            )
        );

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

    }

}