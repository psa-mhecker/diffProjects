<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160106152056 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE psa_after_sale_services_filters_relation DROP FOREIGN KEY FK_B6E6180158A30FBB5622E2C2F1B5AEBC');
        $this->addSql('ALTER TABLE psa_after_sale_services_filters_relation DROP FOREIGN KEY FK_B6E61801E50129B05622E2C2F1B5AEBC');
        $this->addSql('ALTER TABLE psa_after_sale_services_filters_relation ADD CONSTRAINT FK_B6E6180158A30FBB5622E2C2F1B5AEBC FOREIGN KEY (FILTERS_ID, LANGUE_ID, SITE_ID) REFERENCES psa_filter_after_sale_services (ID, LANGUE_ID, SITE_ID) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE psa_after_sale_services_filters_relation ADD CONSTRAINT FK_B6E61801E50129B05622E2C2F1B5AEBC FOREIGN KEY (AFTER_SALE_SERVICES_ID, LANGUE_ID, SITE_ID) REFERENCES psa_after_sale_services (ID, LANGUE_ID, SITE_ID) ON DELETE CASCADE');

        $this->upTranslations(
            array(
                'NDP_FILTER_ASSOCIATED_FOLLOWING_AFTER_SALE_SERVICES' => array(
                    'expression' => 'Ce filtre est associé aux prestations suivante: ',
                    'bo'=>1
                ),
                'NDP_AFTER_SALE_SERVICE_LABEL' => array(
                    'expression' => 'Libellé prestation après vente',
                    'bo'=>1
                ),
                'NDP_RELATION_FILTER_AFTER_SALE_SERVICE_LABEL' => array(
                    'expression' => 'Associé des filtres à la prestation',
                    'bo'=>1
                ),
                'NDP_ERROR_NO_FILTER' => array(
                    'expression' => 'Afin de créer vos prestations après vente, il faut au préalable créer vos filtres',
                    'bo'=>1
                ),
                'NDP_ASSOCIATED_FILTER' => array(
                    'expression' => 'Liste des filtres associés',
                    'bo'=>1
                ),
            )
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->downTranslations(array(
            'NDP_FILTER_ASSOCIATED_FOLLOWING_AFTER_SALE_SERVICES',
            'NDP_AFTER_SALE_SERVICE_LABEL',
            'NDP_RELATION_FILTER_AFTER_SALE_SERVICE_LABEL',
            'NDP_ERROR_NO_FILTER',
            'NDP_ASSOCIATED_FILTER',
        ));

        $this->addSql('ALTER TABLE psa_after_sale_services_filters_relation DROP FOREIGN KEY FK_B6E61801E50129B05622E2C2F1B5AEBC');
        $this->addSql('ALTER TABLE psa_after_sale_services_filters_relation DROP FOREIGN KEY FK_B6E6180158A30FBB5622E2C2F1B5AEBC');
        $this->addSql('ALTER TABLE psa_after_sale_services_filters_relation ADD CONSTRAINT FK_B6E61801E50129B05622E2C2F1B5AEBC FOREIGN KEY (AFTER_SALE_SERVICES_ID, LANGUE_ID, SITE_ID) REFERENCES psa_after_sale_services (ID, LANGUE_ID, SITE_ID)');
        $this->addSql('ALTER TABLE psa_after_sale_services_filters_relation ADD CONSTRAINT FK_B6E6180158A30FBB5622E2C2F1B5AEBC FOREIGN KEY (FILTERS_ID, LANGUE_ID, SITE_ID) REFERENCES psa_filter_after_sale_services (ID, LANGUE_ID, SITE_ID)');
    }
}
