<?php
/**
 * Fichier de PrixFinitionVersion.
 *
 * Classe de gestion des versions (1 véhicule + 1 finitions + 1 moteur)
 * à partir d'informations remontées par les WebServices PSA
 *
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 *
 * @since 16/07/2013
 */

namespace Citroen\GammeFinition;

/**
 * Classe de gestion des versions (1 véhicule + 1 finitions + 1 moteur).
 */
class PrixFinitionVersion extends Gamme
{
    /* Nom de la table PHPFactory à utiliser pour y intégrer les données */
    private $sPHPFactoryTableName = '#pref#_ws_prix_finition_version';

    /* Nom de la dataclass correspondante dans le CSV fourni */
    private $sWSDataclassName = 'PFV';

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
        $this->aColumnsImportMatching[gamme_csv_engineCode_field_WS]        = gamme_csv_engineCode_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_transmissionCode_field_WS]  = gamme_csv_transmissionCode_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_lcdvCode_field_WS]          = gamme_csv_lcdvCode_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_label2_field_WS]            = gamme_csv_label2_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_priceDisplay_field_WS]      = gamme_csv_priceDisplay_field_PHPfact;
        $this->aColumnsImportMatching[gamme_csv_priceNumeric_field_WS]      = gamme_csv_priceNumeric_field_PHPfact;
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
