<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * @author betd-mlamkee
 */
class Version20150708120625 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(
            'UPDATE psa_label SET LABEL_FO = 1 WHERE LABEL_ID IN (
                "NDP_YES",
                "NDP_NO",
                "CLICK_TO_CHAT"
            )'
        );

        $this->addSql('INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ("NDP_FAQ", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PC36_CHOOSE_A_RUBRIC", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PC36_HAS_THIS_ANSWER_BEEN_USEFUL_TO_YOU", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PC36_THANK_YOU_FOR_HELPING_US_TO_IMPROVE_OUR_SERVICES", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PC36_WE_ARE_SORRY_TO_GET_YOUR_ANSWER_YOU_CAN_CONTACT_US_BY", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_PC36_CLICKING_HERE", NULL, 2, NULL, NULL, NULL, 1),
            ("NDP_ASK_US_A_QUESTION", NULL, 2, NULL, NULL, NULL, 1)
            '
        );

        $this->addSql('INSERT INTO `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`, `LABEL_PATH`) VALUES
            ("NDP_YES", 1, "oui", ""),
            ("NDP_NO", 1, "non", ""),
            ("NDP_FAQ", 1, "F.A.Q.", ""),
            ("NDP_PC36_CHOOSE_A_RUBRIC", 1, "Sélectionnez une rubrique", ""),
            ("NDP_PC36_HAS_THIS_ANSWER_BEEN_USEFUL_TO_YOU", 1, "Cette réponse vous a-t-elle été utile ?", ""),
            ("NDP_PC36_THANK_YOU_FOR_HELPING_US_TO_IMPROVE_OUR_SERVICES", 1, "Merci de nous avoir aidé à améliorer nos services.", ""),
            ("NDP_PC36_WE_ARE_SORRY_TO_GET_YOUR_ANSWER_YOU_CAN_CONTACT_US_BY", 1, "Nous sommes désolés, afin d\'obtenir votre réponse vous pouvez nous contacter en", ""),
            ("NDP_PC36_CLICKING_HERE", 1, "cliquant ici", ""),
            ("NDP_ASK_US_A_QUESTION", 1, "Posez-nous une question", ""),
            ("CLICK_TO_CHAT", 1, "Click to chat", "")
            '
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql(
            'UPDATE psa_label SET LABEL_FO = NULL WHERE LABEL_ID IN (
                    "NDP_YES",
                    "NDP_NO",
                    "CLICK_TO_CHAT"
                )'
        );

        $this->addSql(
            'DELETE FROM `psa_label_langue`  WHERE `LABEL_ID` IN
                (
                    "NDP_YES",
                    "NDP_NO",
                    "CLICK_TO_CHAT"
                )'
        );

        $tables = array('psa_label', 'psa_label_langue');
        foreach ($tables as $table) {
            $this->addSql(
                'DELETE FROM `'.$table.'`  WHERE `LABEL_ID` IN
                (
                    "NDP_FAQ",
                    "NDP_PC36_CHOOSE_A_RUBRIC",
                    "NDP_PC36_HAS_THIS_ANSWER_BEEN_USEFUL_TO_YOU",
                    "NDP_PC36_THANK_YOU_FOR_HELPING_US_TO_IMPROVE_OUR_SERVICES",
                    "NDP_PC36_WE_ARE_SORRY_TO_GET_YOUR_ANSWER_YOU_CAN_CONTACT_US_BY",
                    "NDP_PC36_CLICKING_HERE",
                    "NDP_ASK_US_A_QUESTION"
                )'
            );
        }
    }
}
