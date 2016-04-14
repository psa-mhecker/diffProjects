<?php

namespace Application\Migrations;

use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151106115104 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->replaceTranslations(
            array('NDP_LA_DATE_SELECTIONNEE_DOIT_ETRE_SUPERIEURE_OU_EGALE_A_LA_DATE_DU_JOUR' => ['expression'=>'Attention, La date d\'affichage du decompte doit être supérieure ou égale à la date du jour, voulez vous quand même continuer', 'bo'=>1,'languageId'=>1])
        );

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

    }
}
