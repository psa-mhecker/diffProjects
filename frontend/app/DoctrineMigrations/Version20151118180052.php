<?php

namespace Application\Migrations;

use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151118180052 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->upTranslations(
            array(
                'NDP_LIMITED_SERIE' => array(
                    'expression' => 'Série limitée',
                    'bo'=>1
                )
            )
        );
        $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_site ADD LIMITED_SERIES_COMMERCIAL_STRIP TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->downTranslations(array('NDP_LIMITED_SERIE'));
       $this->addSql('ALTER TABLE psa_ws_gdg_model_silhouette_site DROP LIMITED_SERIES_COMMERCIAL_STRIP');
    }
}
