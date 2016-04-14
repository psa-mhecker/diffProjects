<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150619071757 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // Correction de l'encodage des textes de la migration Version20150612141634
        $this->addSql('UPDATE psa_label_langue SET LABEL_TRANSLATE="Indiquez une ville ou un code postal" WHERE LABEL_ID="NDP_PF44_INDIQUEZ_UNE_VILLE_OU_CODE_POSTAL"');
        $this->addSql('UPDATE psa_label_langue SET LABEL_TRANSLATE="Autour de moi..." WHERE LABEL_ID="NDP_PF44_AUTOUR_DE_MOI"');
        $this->addSql('UPDATE psa_label_langue SET LABEL_TRANSLATE="Voir la fiche détaillée" WHERE LABEL_ID="NDP_PF44_VOIR_LA_FICHE_DETAILLEE"');
        $this->addSql('UPDATE psa_label_langue SET LABEL_TRANSLATE="Nous rencontrons un problème de chargement, Veuillez réessayer plus tard. Si le problème persiste, n'."'".'hésitez pas à nous contacter" WHERE LABEL_ID="NDP_PF44_PROBLEME_DE_CHARGEMENT" OR LABEL_ID="NDP_PROBLEME_DE_CHARGEMENT_AJAX"');
        $this->addSql('UPDATE psa_label_langue SET LABEL_TRANSLATE="résultats trouvés" WHERE LABEL_ID="NDP_PF44_RESULTATS_TROUVES"');
        $this->addSql('UPDATE psa_label_langue SET LABEL_TRANSLATE="Aucun résultat ne correspond à votre recherche" WHERE LABEL_ID="NDP_PF44_AUCUN_RESULTAT"');
        $this->addSql('UPDATE psa_label_langue SET LABEL_TRANSLATE="Google map" WHERE LABEL_ID="NDP_PF44_GOOGLE_MAP"');
        $this->addSql('UPDATE psa_label_langue SET LABEL_TRANSLATE="Contactez" WHERE LABEL_ID="NDP_PF44_CONTACTEZ"');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
