<?php


namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160216081853 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO `psa_media_format` (`MEDIA_FORMAT_LABEL`, `MEDIA_FORMAT_KEEP_RATIO`, `MEDIA_FORMAT_HEIGHT`, `MEDIA_FORMAT_WIDTH`, `MEDIA_FORMAT_RATIO`, `MEDIA_FORMAT_BO`, `MEDIA_FORMAT_FO`) VALUES
            ('NDP_MIN_PC69', 1, 480, 854, 1, 1, 0)
        ");

        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_MIN_PC69' => array(
                    'expression' => 'PC69',
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
        $this->addSql('DELETE FROM psa_media_format WHERE MEDIA_FORMAT_LABEL IN("NDP_MIN_PC69")');
        // this down() migration is auto-generated, please modify it to your needs
        $this->downTranslations(array(
            'NDP_MIN_PC69',
        ));
    }
}
