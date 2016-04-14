<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160330164336 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->replaceTranslations(array(
            'NDP_MSG_EMAIL_FORM' => array(
                'expression' => "Pour récupérer l'e-mail de l'utilisateur, saisir le code suivant ##email## à l'endroit souhaité.",
                'LANGUE_ID' => '1'
            ),
        ));
        $this->replaceTranslations(array(
            'NDP_MSG_EMAIL_FORM' => array(
                'expression' => "To recover the user email, enter this ##email## code at the preferred place.",
                'LANGUE_ID' => '2'
            ),
        ));
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
