<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version20150608151650
 * @package Application\Migrations
 */
class Version20150608151650 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $changeKeyTrad = array(
            'NDP_MIG_SHOWROOM_TYPE_VN_PUBLISHED' => 'NDP_MIG_URL_TYPE_VN_PUBLISHED',
            'NDP_MIG_SHOWROOM_TYPE_CONCEPT_PUBLISHED' => 'NDP_MIG_URL_TYPE_CONCEPT_PUBLISHED',
            'NDP_MIG_SHOWROOM_TYPE_TECHNO_PUBLISHED' => 'NDP_MIG_URL_TYPE_TECHNO_PUBLISHED',
            'NDP_MIG_SHOWROOM_TYPE_NOT_PUBLISHED' => 'NDP_MIG_URL_TYPE_NOT_PUBLISHED',
            'NDP_MIG_SHOWROOM_TYPE' => 'NDP_MIG_URL_TYPE'
        );

        foreach ($changeKeyTrad as $newKey => $oldKey) {
            $this->addSql('UPDATE psa_label SET LABEL_ID = "'.$oldKey.'" WHERE LABEL_ID="'.$newKey.'"');
            $this->addSql('UPDATE psa_label_langue_site SET LABEL_ID = "'.$oldKey.'" WHERE LABEL_ID="'.$newKey.'"');
        }

        // Traductions for migration, IHM migration is locked
        $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_MIG_LOCK_BY_USER', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MIG_LOCK_MIG_AVERAGE_TIME', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MIG_LOCK_UNLOCK_MSG', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MIG_LOCK_UNLOCK_BTN', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MIG_LOCK_TITLE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MIG_RESULT_TITLE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MIG_URL_MISSING', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_MIG_UNLOCK_CONFIRMATION', NULL, 2, NULL, NULL, 1, NULL)
            ");

        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
            ('NDP_MIG_LOCK_BY_USER', 1, 1, 'Attention, une reprise de contenu a été déclenché sur votre pays le ##paramDate## à ##paramHour##  par l’utilisateur « ##paramUser## »'),
            ('NDP_MIG_LOCK_MIG_AVERAGE_TIME', 1, 1, 'Pour information le temps moyen d’une migration : ##paramAvgTime##'),
            ('NDP_MIG_LOCK_UNLOCK_MSG', 1, 1, 'Veuillez attendre la fin du traitement avant de lancer une autre reprise, ou cliquez ici pour déverrouiller cette fonctionnalité (déconseillé).'),
            ('NDP_MIG_LOCK_UNLOCK_BTN', 1, 1, 'Déverouiller la migration de données Showroom'),
            ('NDP_MIG_LOCK_TITLE', 1, 1, 'Migration bloquée'),
            ('NDP_MIG_RESULT_TITLE', 1, 1, 'Résultat de la migration'),
            ('NDP_MIG_URL_MISSING', 1, 1, 'Merci de remplir au moins une url showroom d\'une langue'),
            ('NDP_MIG_UNLOCK_CONFIRMATION', 1, 1, 'La migration a été débloqué. vous pouvez lancer une nouvelle migration.')
            ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql('DELETE FROM '.$table.'  WHERE  LABEL_ID IN
                (
                "NDP_MIG_LOCK_BY_USER",
                "NDP_MIG_LOCK_MIG_AVERAGE_TIME",
                "NDP_MIG_LOCK_UNLOCK_MSG",
                "NDP_MIG_LOCK_UNLOCK_BTN",
                "NDP_MIG_LOCK_TITLE",
                "NDP_MIG_RESULT_TITLE",
                "NDP_MIG_URL_MISSING",
                "NDP_MIG_UNLOCK_CONFIRMATION"
                )
            ');
        }

        $changeKeyTrad = array(
            'NDP_MIG_URL_TYPE_VN_PUBLISHED' => 'NDP_MIG_SHOWROOM_TYPE_VN_PUBLISHED',
            'NDP_MIG_URL_TYPE_CONCEPT_PUBLISHED' => 'NDP_MIG_SHOWROOM_TYPE_CONCEPT_PUBLISHED',
            'NDP_MIG_URL_TYPE_TECHNO_PUBLISHED' => 'NDP_MIG_SHOWROOM_TYPE_TECHNO_PUBLISHED',
            'NDP_MIG_URL_TYPE_NOT_PUBLISHED' => 'NDP_MIG_SHOWROOM_TYPE_NOT_PUBLISHED',
            'NDP_MIG_URL_TYPE' => 'NDP_MIG_SHOWROOM_TYPE'
        );

        foreach ($changeKeyTrad as $newKey => $oldKey) {
            $this->addSql('UPDATE psa_label SET LABEL_ID = "'.$oldKey.'" WHERE LABEL_ID="'.$newKey.'"');
            $this->addSql('UPDATE psa_label_langue_site SET LABEL_ID = "'.$oldKey.'" WHERE LABEL_ID="'.$newKey.'"');
        }
    }
}
