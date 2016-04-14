<?php
/**
 * Fichier de CouleurFinition.
 *
 * Classe de gestion des import des couleurs des différentes finitions
 *  à partir d'informations remontées par les WebServices PSA
 *
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 *
 * @since 16/07/2013
 */

namespace Citroen\GammeFinition;

/**
 * Classe permettant l'introduction des données des modèles
 * issues d'un fichier CSV provenant des WebServices PSA.
 */
class CouleurFinition extends Gamme
{
    /* Nom de la table PHPFactory à utiliser pour y intégrer les données */
    private $sPHPFactoryTableName = '#pref#_ws_couleur_finition';

    /* Nom de la dataclass correspondante dans le CSV fourni */
    private $sWSDataclassName = 'COL';

    /* Tableau des colonnes de la table modèle */
    private $aColumnsImportMatching = array();

    /* Tableau des données provenant du WebService et liées à l'objet */
    private $aWSChildData = array();

    /* Tableau des propriétés provenant du WebService et liées à l'objet */
    private $aWSChildProperties = array();

    /**
     * Constructeur de l'objet.
     */
    public function __construct()
    {
        /* Initialisation du tableau de matching entre les noms
         * des tables fournies par PSA et les tables du SGBD
         * PHP factory
         */
        $this->setColumnsImportMatching();
    }

    /**
     * Méthode publique remontant ne nom de la Dataclass
     * de la classe.
     */
    public function getWSDataclassName()
    {
        return $this->sWSDataclassName;
    }

    /**
     * Méthode publique remontant ne nom de la table
     * PHPFactory utilisée pour la classe.
     */
    public function getPHPFactoryTableName()
    {
        return $this->sPHPFactoryTableName;
    }

    /**
     * Méthode privée permettant d'associer un nom de colonne
     * de la bdd PHPFactory à un nom de colonne du CSV
     * généré par les WebServices PSA.
     */
    public function setColumnsImportMatching()
    {
        $this->aColumnsImportMatching[gamme_csv_culture_field_WS]           = gamme_csv_culture_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_gamme_field_WS]             = gamme_csv_gamme_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_lcdv6_field_WS]             = gamme_csv_lcdv6_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_grCommercialNameCode_field_WS]  = gamme_csv_grCommercialNameCode_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_code_field_WS]              = gamme_csv_code_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_label2_field_WS]            = gamme_csv_label2_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_bodyColorCode_field_WS]     = gamme_csv_bodyColorCode_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_bodyColorLabel_field_WS]    = gamme_csv_bodyColorLabel_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_bodyColorOrder_field_WS]    = gamme_csv_bodyColorOrder_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_bodyColorPictoURL_field_WS] = gamme_csv_bodyColorPictoURL_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_roofColorCode_field_WS]     = gamme_csv_roofColorCode_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_roofColorLabel_field_WS]    = gamme_csv_roofColorLabel_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_roofColorOrder_field_WS]    = gamme_csv_roofColorOrder_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_roofColorPictoURL_field_WS] = gamme_csv_roofColorPictoURL_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_v3DCode_field_WS]           = gamme_csv_v3DCode_field_PHPfact;
    }

    /**
     * Méthode publique permettant de remonter le tableau associatif
     * des colonnes de la base de données et de celles du WebService.
     */
    public function getColumnsImportMatching()
    {
        return $this->aColumnsImportMatching;
    }

    /**
     * Méthode privée d'instanciation des données et propriétés provenant du WebService de l'objet.
     */
    public function setWSChild($aWSAllProperties, $aWSAllData)
    {
        /* Instanciation des propriétés propres à l'objet et provenant de l'export
         * de toutes les données du WebService
         */
        $this->setWSChildProperties($aWSAllProperties);
        /* Instanciation des données propres à l'objet et provenant de l'export
         * de toutes les données du WebService
         */
        $this->setWSChildData($aWSAllData);
    }

    /**
     * Méthode privée d'instanciation des données provenant du WebService de l'objet.
     */
    private function setWSChildData($aWSAllData)
    {
        if (is_array($aWSAllData) && array_key_exists($this->sWSDataclassName, $aWSAllData)) {
            $this->aWSChildData = $aWSAllData[$this->sWSDataclassName];
        }
    }

    /**
     * Méthode privée d'instanciation des propriétés provenant du WebService de l'objet.
     */
    private function setWSChildProperties($aWSAllProperties)
    {
        if (is_array($aWSAllProperties) && array_key_exists($this->sWSDataclassName, $aWSAllProperties)) {
            $this->aWSChildProperties = $aWSAllProperties[$this->sWSDataclassName];
        }
    }

    /**
     * Méthode public pour la récupération des données du WebService.
     */
    public function getWSChildData()
    {
        return $this->aWSChildData;
    }

    /**
     * Méthode public pour la récupération des Propriétés des données du WebService.
     */
    public function getWSChildProperties()
    {
        return $this->aWSChildProperties;
    }
}
