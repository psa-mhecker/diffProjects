<?php
namespace Citroen\GammeFinition;

/*
 * Fichier de Citroen_Gamme : langues
 *
 * Classe de gestion des import des diverses informations des
 * gammes dans la base de données à partir d'informations remontées
 * par les WebServices PSA
 *
 * @package Citroen
 * @subpackage Gamme
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since 16/07/2013
 */

/* Initialisation des constantes utilisées dans l'objet */
/* Classes héritantes de la classe Gamme */
define('gamme_class_modele', 'Modele');
define('gamme_class_vehiculeGamme', 'VehiculeGamme');
define('gamme_class_critereSelection', 'CritereSelection');
define('gamme_class_prixFinitionVersion', 'PrixFinitionVersion');
define('gamme_class_equipementDisponible', 'EquipementDisponible');
define('gamme_class_finitions', 'Finitions');
define('gamme_class_equipementStandard', 'EquipementStandard');
define('gamme_class_equipementOption', 'EquipementOption');
define('gamme_class_energieMoteur', 'EnergieMoteur');
define('gamme_class_caracteristiqueMoteur', 'CaracteristiqueMoteur');
define('gamme_class_caracteristiqueTechnique', 'CaracteristiqueTechnique');
define('gamme_class_caracteristiqueDetailMoteur', 'CaracteristiqueDetailMoteur');
define('gamme_class_gamme_CouleurFinition', 'CouleurFinition');

/* Constantes liées au paramétrage de l'import des données */
/* Séparateur entre deux colonnes */
define('gamme_csv_column_separator', ';');
/* Expression régulière permettant de séparer deux exports */
define('gamme_csv_regex_title_import', "/^\*{2,2}\s.+\s\*{2,2}$/i");
/* Colonne contenant le nom de la colonne de l'export PSA contenant le nom de table*/
define('gamme_csv_dataclass_column', 'DataClass');
/* Définition de l'allocation mémoire PHP pour l'import des données */
define('gamme_csv_memory_limit', '1200M');
/* Définition du temps de traitement pour l'import des données */
define('gamme_csv_max_execution_time', '0');

/* Constantes des champs de base de données utilisés dans le WebService et la
 * base de données de PHP Factory
 */
define('gamme_csv_culture_field_PHPfact', 'CULTURE');
define('gamme_csv_culture_field_WS', 'Culture');
define('gamme_csv_label_field_PHPfact', 'LIBELLE');
define('gamme_csv_label_field_WS', 'Libellé');
define('gamme_csv_lcdv4_field_PHPfact', 'LCDV4');
define('gamme_csv_lcdv4_field_WS', 'LCDV4');
define('gamme_csv_lcdv6_field_PHPfact', 'LCDV6');
define('gamme_csv_lcdv6_field_WS', 'LCDV6');
define('gamme_csv_gamme_field_PHPfact', 'GAMME');
define('gamme_csv_gamme_field_WS', 'Gamme');
define('gamme_csv_modelLabel_field_PHPfact', 'MODEL_LABEL');
define('gamme_csv_modelLabel_field_WS', 'ModelLabel');
define('gamme_csv_bodyLabel_field_PHPfact', 'BODY_LABEL');
define('gamme_csv_bodyLabel_field_WS', 'BodyLabel');
define('gamme_csv_bodyCode_field_PHPfact', 'BODY_CODE');
define('gamme_csv_bodyCode_field_WS', 'BodyCode');
define('gamme_csv_modelBodyLabel_field_PHPfact', 'MODEL_BODY_LABEL');
define('gamme_csv_modelBodyLabel_field_WS', 'ModelBodyLabel');
define('gamme_csv_critOrder_field_PHPfact', 'CRIT_ORDER');
define('gamme_csv_critOrder_field_WS', 'Order');
define('gamme_csv_critBodyCode_field_PHPfact', 'CRIT_BODY_CODE');
define('gamme_csv_critBodyCode_field_WS', 'CritBodyCode');
define('gamme_csv_critBodyLabel_field_PHPfact', 'CRIT_BODY_LABEL');
define('gamme_csv_critBodyLabel_field_WS', 'CritBodyLabel');
define('gamme_csv_seats_field_PHPfact', 'SEATS');
define('gamme_csv_seats_field_WS', 'Seats');
define('gamme_csv_critTrCode_field_PHPfact', 'CRIT_TR_CODE');
define('gamme_csv_critTrCode_field_WS', 'CritTrCode');
define('gamme_csv_critTrLabel_field_PHPfact', 'CRIT_TR_LABEL');
define('gamme_csv_critTrLabel_field_WS', 'CritTrLabel');
define('gamme_csv_critMixedConsumptionMin_field_PHPfact', 'CRIT_MIXEDCONSUMPTION_MIN');
define('gamme_csv_critMixedConsumptionMin_field_WS', 'CritMixedConsumptionMin');
define('gamme_csv_critCO2RateMin_field_PHPfact', 'CRIT_CO2_RATE_MIN');
define('gamme_csv_critCO2RateMin_field_WS', 'CritCO2RateMin');
define('gamme_csv_critExteriorLengthMin_field_PHPfact', 'CRIT_EXTERIOR_LENGTH_MIN');
define('gamme_csv_critExteriorLengthMin_field_WS', 'CritExteriorLengthMin');
define('gamme_csv_critPriceMin_field_PHPfact', 'CRIT_PRICE_MIN');
define('gamme_csv_critPriceMin_field_WS', 'CritPriceMin');
define('gamme_csv_grCommercialNameCode_field_PHPfact', 'GR_COMMERCIAL_NAME_CODE');
define('gamme_csv_grCommercialNameCode_field_WS', 'GrCommercialNameCode');
define('gamme_csv_engineCode_field_PHPfact', 'ENGINE_CODE');
define('gamme_csv_engineCode_field_WS', 'EngineCode');
define('gamme_csv_transmissionCode_field_PHPfact', 'TRANSMISSION_CODE');
define('gamme_csv_transmissionCode_field_WS', 'TransmissionCode');
define('gamme_csv_label2_field_PHPfact', 'LABEL');
define('gamme_csv_label2_field_WS', 'Label');
define('gamme_csv_priceDisplay_field_PHPfact', 'PRICE_DISPLAY');
define('gamme_csv_priceDisplay_field_WS', 'PriceDisplay');
define('gamme_csv_priceNumeric_field_PHPfact', 'PRICE_NUMERIC');
define('gamme_csv_priceNumeric_field_WS', 'PriceNumeric');
define('gamme_csv_categoryName_field_PHPfact', 'CATEGORY_NAME');
define('gamme_csv_categoryName_field_WS', 'CategoryName');
define('gamme_csv_equipementName_field_PHPfact', 'EQUIPEMENT_NAME');
define('gamme_csv_equipementName_field_WS', 'EquipementName');
define('gamme_csv_lcdvCode_field_PHPfact', 'LCDV_CODE');
define('gamme_csv_lcdvCode_field_WS', 'LcdvCode');
define('gamme_csv_disponibility_field_PHPfact', 'DISPONIBILITY');
define('gamme_csv_disponibility_field_WS', 'Disponibility');
define('gamme_csv_finitionLabel_field_PHPfact', 'FINITION_LABEL');
define('gamme_csv_finitionLabel_field_WS', 'FinitionLabel');
define('gamme_csv_finitionCode_field_PHPfact', 'FINITION_CODE');
define('gamme_csv_finitionCode_field_WS', 'FinitionCode');
define('gamme_csv_primaryDisplayPrice_field_PHPfact', 'PRIMARY_DISPLAY_PRICE');
define('gamme_csv_primaryDisplayPrice_field_WS', 'PrimaryDisplayPrice');
define('gamme_csv_previousFinitionCode_field_PHPfact', 'PREVIOUS_FINITION_CODE');
define('gamme_csv_previousFinitionCode_field_WS', 'PreviousFinitionCode');
define('gamme_csv_v3DLcdv_field_PHPfact', 'V3D_LCDV');
define('gamme_csv_v3DLcdv_field_WS', 'V3DLcdv');
define('gamme_csv_v3DExterior_field_PHPfact', 'V3D_EXTERIOR');
define('gamme_csv_v3DExterior_field_WS', 'V3DExterior');
define('gamme_csv_v3DInterior_field_PHPfact', 'V3D_INTERIOR');
define('gamme_csv_v3DInterior_field_WS', 'V3DInterior');
define('gamme_csv_categorieCode_field_PHPfact', 'CATEGORIE_CODE');
define('gamme_csv_categorieCode_field_WS', 'CategorieCode');
define('gamme_csv_categorieLabel_field_PHPfact', 'CATEGORIE_LABEL');
define('gamme_csv_categorieLabel_field_WS', 'CategorieLabel');
define('gamme_csv_categorieRank_field_PHPfact', 'CATEGORIE_RANK');
define('gamme_csv_categorieRank_field_WS', 'CategorieRank');
define('gamme_csv_equipementCode_field_PHPfact', 'EQUIPEMENT_CODE');
define('gamme_csv_equipementCode_field_WS', 'EquipementCode');
define('gamme_csv_equipementLibelle_field_PHPfact', 'EQUIPEMENT_LABEL');
define('gamme_csv_equipementLibelle_field_WS', 'EquipementLibelle');
define('gamme_csv_energyCategory_field_PHPfact', 'ENERGY_CATEGORY');
define('gamme_csv_energyCategory_field_WS', 'EnergyCategory');
define('gamme_csv_transmissionLabel_field_PHPfact', 'TRANSMISSION_LABEL');
define('gamme_csv_transmissionLabel_field_WS', 'TransmissionLabel');
define('gamme_csv_description_field_PHPfact', 'DESCRIPTION');
define('gamme_csv_description_field_WS', 'Description');
define('gamme_csv_pictoURL_field_PHPfact', 'PICTO_URL');
define('gamme_csv_pictoURL_field_WS', 'PictoURL');
define('gamme_csv_engineDescription_field_PHPfact', 'ENGINE_DESCRIPTION');
define('gamme_csv_engineDescription_field_WS', 'EngineDescription');
define('gamme_csv_engineLabel_field_PHPfact', 'ENGINE_LABEL');
define('gamme_csv_engineLabel_field_WS', 'EngineLabel');
define('gamme_csv_referenceLcdv_field_PHPfact', 'REFERENCE_LCDV');
define('gamme_csv_referenceLcdv_field_WS', 'ReferenceLcdv');
define('gamme_csv_isEcoLabel_field_PHPfact', 'IS_ECO_LABEL');
define('gamme_csv_isEcoLabel_field_WS', 'IsEcoLabel');
define('gamme_csv_rank_field_PHPfact', 'RANK');
define('gamme_csv_rank_field_WS', 'Rank');
define('gamme_csv_name_field_PHPfact', 'NAME');
define('gamme_csv_name_field_WS', 'Name');
define('gamme_csv_value_field_PHPfact', 'VALUE');
define('gamme_csv_value_field_WS', 'Value');
define('gamme_csv_caractKey_field_PHPfact', 'CARACT_KEY');
define('gamme_csv_caractKey_field_WS', 'Key');
define('gamme_csv_code_field_PHPfact', 'CODE');
define('gamme_csv_code_field_WS', 'Code');
define('gamme_csv_bodyColorCode_field_PHPfact', 'BODY_COLOR_CODE');
define('gamme_csv_bodyColorCode_field_WS', 'BodyColorCode');
define('gamme_csv_bodyColorLabel_field_PHPfact', 'BODY_COLOR_LABEL');
define('gamme_csv_bodyColorLabel_field_WS', 'BodyColorLabel');
define('gamme_csv_bodyColorOrder_field_PHPfact', 'BODY_COLOR_ORDER');
define('gamme_csv_bodyColorOrder_field_WS', 'BodyColorOrder');
define('gamme_csv_bodyColorPictoURL_field_PHPfact', 'BODY_COLOR_PICTO_URL');
define('gamme_csv_bodyColorPictoURL_field_WS', 'BodyColorPictoURL');
define('gamme_csv_roofColorCode_field_PHPfact', 'ROOF_COLOR_CODE');
define('gamme_csv_roofColorCode_field_WS', 'RoofColorCode');
define('gamme_csv_roofColorLabel_field_PHPfact', 'ROOF_COLOR_LABEL');
define('gamme_csv_roofColorLabel_field_WS', 'RoofColorLabel');
define('gamme_csv_roofColorOrder_field_PHPfact', 'ROOF_COLOR_ORDER');
define('gamme_csv_roofColorOrder_field_WS', 'RoofColorOrder');
define('gamme_csv_roofColorPictoURL_field_PHPfact', 'ROOF_COLOR_PICTO_URL');
define('gamme_csv_roofColorPictoURL_field_WS', 'RoofColorPictoURL');
define('gamme_csv_v3DCode_field_PHPfact', 'V3D_CODE');
define('gamme_csv_v3DCode_field_WS', 'V3DCode');

define('gamme_csv_site_id_field_PHPfact', 'SITE_ID');
define('gamme_csv_langue_id_field_PHPfact', 'LANGUE_ID');

/* Préfixe des méthodes permettant de retravailler des champs du CSV PSA */
define('gamme_modify_field_prefix', 'modifyField_');

/**
 * Objet permettant de récupérer les informations des gammes PSA.
 * Ces informations sont récupérées par l'objet en prenant un fichier
 * CSV issus d'appels à différents WebServices PSA.
 */
class Gamme
{
    /** Initialisation des propriétés de l'objet */
    /* Tableau des différentes tables à instancier */
    private $aGammeObjects = array();
    /* Chemin du fichier CSV d'export des données PSA */
    private $sWSExportFilePath = '';
    /* Tableau des propriétés (colonnes) utilisées par export */
    public $aWSProperties;
    /* Tableau des données par export */
    public $aWSData;
    /* Tableau des langues par codes */
    public $aLanguages;
    /* Tableau des données de site par code pays */
    public $aSites;

    /**
     * Constructeur de l'objet.
     */
    public function __construct()
    {
        /* Initialisation des objets métiers à utiliser
         * pour l'import des données provenant des WS
         */
        $this->setGammeObjects();

        /* Initialisation du chemin du CSV */
        $this->setWSExportFilePath();
    }

    /**
     * Méthode privée permettant de générée le tableau de
     * correspondance entre la "DataClass" présente dans le
     * fichiers d'export et les tables PHPFactory.
     */
    private function setGammeObjects()
    {
        $this->aGammeObjects[] = gamme_class_modele;
        $this->aGammeObjects[] = gamme_class_vehiculeGamme;
        $this->aGammeObjects[] = gamme_class_critereSelection;
        $this->aGammeObjects[] = gamme_class_prixFinitionVersion;
        $this->aGammeObjects[] = gamme_class_equipementDisponible;
        $this->aGammeObjects[] = gamme_class_finitions;
        $this->aGammeObjects[] = gamme_class_equipementStandard;
        $this->aGammeObjects[] = gamme_class_equipementOption;
        $this->aGammeObjects[] = gamme_class_energieMoteur;
        $this->aGammeObjects[] = gamme_class_caracteristiqueMoteur;
        $this->aGammeObjects[] = gamme_class_caracteristiqueTechnique;
        $this->aGammeObjects[] = gamme_class_caracteristiqueDetailMoteur;
        $this->aGammeObjects[] = gamme_class_gamme_CouleurFinition;
    }

    /**
     * Méthode privée créant le tableau de toutes les langues de l'application.
     *
     * @param mixter $aResult null Si le cache de langues ne remonte pas d'information
     *                        array sinon
     */
    private function setGammeLanguages()
    {
        /* Initialisation des variables */
        $this->aLanguages = null;

        $aLanguage = \Pelican_Translate::getLanguageCode();
        if (is_array($aLanguage)) {
            $this->aLanguages = $aLanguage;
        }
    }

    /**
     * Méthode privée créant le tableau de toutes les langues de l'application.
     *
     * @param mixter $aResult null Si le cache de langues ne remonte pas d'information
     *                        array sinon
     */
    private function setGammeSites()
    {
        /* Initialisation des variables */
        $this->aSites = null;

        $aSites = \Pelican_Cache::fetch('SiteBySiteCode', array(null, \Pelican_Cache::getTimeStep(DAY)));
        if (is_array($aSites)) {
            $this->aSites = $aSites;
        }
    }

    /**
     * Méthode publique permettant l'insertion de chaque données d'objet en base
     * à partir de l'export CSV fourni par PSA.
     */
    public function importAllCSVData()
    {
        /* Le fichier CSV à importer étant lourd, le traitement nécéssite une
         * augmentation de la taille de mémoire allouée au script
         */
        ini_set('memory_limit', gamme_csv_memory_limit);
        ini_set('max_execution_time', gamme_csv_max_execution_time);
        error_log('Start process');

        /* netttoyage des tables */
        $this->cleanTables();

        /* Initialisation du tableau de langues */
        $this->setGammeLanguages();

        /* Initialisation du tableau de sites */
        $this->setGammeSites();

        if (is_array($this->aSites) && !empty($this->aSites)) {
            foreach ($this->aSites as $sites) {
                /* récuperation des langues des sites */
                $aLangues = \Pelican_Cache::fetch('Frontend/Citroen/SiteLangues', array($sites['SITE_ID']));

                if (is_array($aLangues) && !empty($aLangues)) {
                    foreach ($aLangues as $langue) {
                        $TypeVehicules = array('VP', 'VU');

                        foreach ($TypeVehicules as $type) {

                            /* Initialisation du chemin du CSV */
                            $this->setWSExportFilePath($sites['SITE_CODE_PAYS'], $langue['LANGUE_CODE'], $type);
                            //var_dump($this->sWSExportFilePath);

                            error_log('Culture '.$langue['LANGUE_CODE'].'-'.$sites['SITE_CODE_PAYS'].'_'.$type);
                            error_log('====================================');

                            /* Import des données du CSV dans un tableau global */
                            $this->importCSVDataToObject();

                            /* Si des objets sont déclarés dans setGammeObjects et qu'il y a des données
                             * dans la tableau global
                             */
                            if (is_array($this->aGammeObjects) && !empty($this->aGammeObjects) &&
                                    is_array($this->aWSData) && !empty($this->aWSData) &&
                                    is_array($this->aWSProperties) && !empty($this->aWSProperties)
                               ) {

                                /* Insertion des données de chaque objet */
                                foreach ($this->aGammeObjects as $sGammeClassName) {
                                    error_log($sGammeClassName);
                                    $oTableObject = $this->getGammeObject($sGammeClassName);
                                    $this->importDataToBdd($oTableObject);
                                    $this->unsetGammeObject($oTableObject);
                                }
                            }
                        }
                    }
                }
            }
        }

        $cacheFwRootBackup = \Pelican::$config['CACHE_FW_ROOT'];
        \Pelican::$config['CACHE_FW_ROOT'] = rtrim(\Pelican::$config['CACHE_FW_ROOT'], '/ ').'/../';
        \Pelican_Cache::clean("Citroen/GammePrixFinitionVersion");
        \Pelican_Cache::clean("Citroen/GammeVehiculeGamme");
        \Pelican::$config['CACHE_FW_ROOT'] = $cacheFwRootBackup;
        error_log('Cached cleaned');

        error_log('End process');
    }

    /**
     * Methode pour vider les tables liées à l'import CFG.
     *
     * @params
     */
    public function cleanTables()
    {
        /* Récupération de l'instance de base de données */
        $oConnection = \Pelican_Db::getInstance();

        if (is_array($this->aGammeObjects) && !empty($this->aGammeObjects)) {

            /* Insertion des données de chaque objet */
            foreach ($this->aGammeObjects as $sGammeClassName) {
                $oTableObject = $this->getGammeObject($sGammeClassName);

                error_log("nettoyage de {$oTableObject->getPHPFactoryTableName()}");
                //echo "Vidage de {$oTableObject->getPHPFactoryTableName()}\n";

                /* Vidage de la table où sont insérées les données */
                $oConnection->query("TRUNCATE TABLE {$oTableObject->getPHPFactoryTableName()}");
            }
        }
    }

    /**
     * Méthode publique permettant de retourner les informations à insérer en base
     * à partir du tableau de données créé par le CSV.
     *
     * @param string $sClassName Nom de la classe à utiliser pour l'insertion
     *                           en base
     *
     * @return array $aDataToImport  Tableau de données formatté à insérer en
     *               base
     */
    public function getGammeObject($sClassName)
    {
        /* Inclusion de l'objet de traitement de la table donnée */
        $sCompleteClassName = __NAMESPACE__.'\\'.$sClassName;

        return new $sCompleteClassName();
    }

    /**
     * Unset de l'objet précédemment créé.
     *
     * @param type $oTableObject
     */
    public function unsetGammeObject($oTableObject)
    {
        /* Inclusion de l'objet de traitement de la table donnée */
        unset($oTableObject);
    }

    /**
     * Méthode publique d'import des données provenant du fichier CSV fourni
     * par PSA dans la base de données après formattage du tableau pour son
     * intégration en base.
     *
     * @param object $oTableObject Objet de Gamme ayant une table dans la base
     *                             de données
     */
    private function importDataToBdd($oTableObject)
    {
        /* Initialisation des variables */
        $aData = array();
        /* Ensemble de valeurs concaténées */
        $bConcatVersion = true;
        /* Récupération des données formattées pour l'insertion dans la base de
         * données
         */
        error_log("START {$oTableObject->getPHPFactoryTableName()} -> ".date('Ymd-His'));
        //echo "START {$oTableObject->getPHPFactoryTableName()} -> ".date('Ymd-His')."\n";
        $iStart = microtime(true);
        $aData = $this->getDataToInsert($oTableObject, $this->getWSProperties(), $this->getWSData());

        /* Récupération de l'instance de base de données */
        $oConnection = \Pelican_Db::getInstance();

        /*
         * Vidage de la table où sont insérées les données
         * déplacer dans cleanTables
         */
        //$oConnection->query("TRUNCATE TABLE {$oTableObject->getPHPFactoryTableName()}");

        /* Insertion des données dans la table */
        if (is_array($aData) && !empty($aData)) {
            $i = 0;
            $iNbLines = count($aData);
            $sSqlInsert = '';
            $bCommit = false;
            foreach ($aData as $aOneLine) {
                $bCommit = false;
                \Pelican_Db::$values = $aOneLine;
                //error_log(serialize($aOneLine));
                if ($bConcatVersion === false) {
                    $oConnection->updateTable(\Pelican::$config['DATABASE_INSERT'], $oTableObject->getPHPFactoryTableName(), '', array(), array(), false);
                } else {
                    /* Récupération de la requête en chaîne de caractère sans l'exécuter*/
                    $aTmp = $oConnection->updateTable(\Pelican::$config['DATABASE_INSERT'], $oTableObject->getPHPFactoryTableName(), '', array(), array(), true);
                    if (empty($sSqlInsert)) {
                        $sSqlInsert .= $aTmp[0];
                    } else {
                        $sSqlInsert .= ','.preg_replace('/^INSERT.*VALUES/i', '', $aTmp[0]);
                    }
                    unset($aTmp);
                }
                $i++;
                if ($i % 50 === 0) {
                    $bCommit = true;
                }

                if ($i % 1000 === 0 && $bConcatVersion === true) {
                    $oConnection->query($sSqlInsert);
                    error_log("insertion {$sDataClassName} -> {$i}/{$iNbLines} lignes");
                    //echo "insertion {$sDataClassName} -> {$i}/{$iNbLines} lignes\n";
                    unset($sSqlInsert);
                }

                /* Commit tous les 100 enregistrements */
                if ($bCommit === true  && $bConcatVersion === false) {
                    $oConnection->commit();
                    $sDataClassName = $oTableObject->getWSDataclassName();
                    error_log("commit {$sDataClassName} -> {$i}/{$iNbLines}");
                    unset($sSqlInsert);
                }
            }
        }
        if (!empty($sSqlInsert) && $bConcatVersion === true) {
            $oConnection->query($sSqlInsert);
            unset($sSqlInsert);
        }
        $oConnection->commit();
        $iEnd = microtime(true);
        $iTime = $iEnd - $iStart;
        error_log("END {$oTableObject->getPHPFactoryTableName()} -> ".date('Ymd-His')."-> $iTime");
        /* Suppression des tableaux pour un gain de mémoire */
        unset($aData);
        unset($iNbLines);
    }

    /**
     * Méthode privée instanciantiant la propriété sWSExportFilePath pour le chemin
     * d'accès au fichier CSV à importer.
     */
    private function setWSExportFilePath($pays = false, $langue = false, $typeVehicule = false)
    {
        if ($pays && $langue && $typeVehicule) {
            $this->sWSExportFilePath = \Pelican::$config['VAR_ROOT'].'/import/cpw_cfg_'.$langue.'-'.$pays.'_'.$typeVehicule.'.csv';
        } else {
            $this->sWSExportFilePath = \Pelican::$config['VAR_ROOT'].'/import/all.csv';
            //$this->sWSExportFilePath = \Pelican::$config['VAR_ROOT'] . '/import/cpw_cfg.csv';
        }
    }

    /**
     * Méthode publique retournant le chemin d'accès au fichier CSV à importer.
     *
     * @return string Chemin complet du fichier CSV à importer
     */
    public function getWSExportFilePath()
    {
        return $this->sWSExportFilePath;
    }

    /**
     * Méthode de parcours du fichier CSV et insertion des données dans des
     * propriétés de l'objets : aWSProperties et aWSData.
     */
    public function importCSVDataToObject()
    {
        /* Initialisation des variables */
        /* Propriétés de tous les exports */
        $aProperties = array();
        /* Données de tous les exports */
        $aData  = array();
        error_log('debut lecture csv');
        /* Si le fichier est lisible on le parcours et que des lignes d'export on été trouvées */
        if (self::isReadableExport($this->sWSExportFilePath) === true) {
            /* Ouverture du fichier CSV en lecture en utilisant SplFileObject */
            $oCsv = self::getSplFileObjectExport($this->sWSExportFilePath, gamme_csv_column_separator);

            /* Parcours ligne par ligne du fichier */
            while (!$oCsv->eof()) {
                /* Récupération de la ligne */
                $aLine = $oCsv->fgetcsv();
                if ($this->isExportTable($aLine) === true) {
                    /* Données de la ligne courante du fichier csv */
                    $aCurrent = array();
                    /* Propriétés de l'export courant */
                    $aCurrentProperties = array();
                    /* Nombre total de colonnes de l'export CSV en cours */
                    $iNbTotalColumns = 0;
                    /* Nombre réel de colonnes de l'export CSV en cours
                     * (colonne ou des données sont inscrites)  */
                    $iNbUsedColumns = 0;
                    /* Identifiant de la colonne contenant le nom de la table
                     * de l'export en cours */
                    $iDataclassKey = 0;
                    /* Nom de la table de l'export en cours */
                    $sWSDataclassName = '';

                    /* La ligne suivante du titre sera la définition des colonnes du fichier */
                    $aCurrent = $oCsv->fgetcsv();

                    if (is_array($aCurrent) && !empty($aCurrent)) {
                        /* Nombre total de colonne */
                        $iNbTotalColumns = count($aCurrent);
                        /* La dernière colonne est toujours vide, elle ne devrait pas contenir de ; */
                        $iNbUsedColumns = $iNbTotalColumns-1;
                        /* Si des données sont présentes dans le tableau on parcours celui-ci */
                        if ($iNbUsedColumns > 0) {
                            /* Récupération des propriétés des données */
                            $aCurrentProperties = array_slice($aCurrent, 0, $iNbUsedColumns);
                            /* Récupération de la clé contenant le nom de la table
                             * de l'export en cours */
                            $iDataclassKey = self::getCsvDataclassKey($aCurrentProperties);

                            /* Initialisation du parcours */
                            $bContinueParsing = false;
                            /* Si l'identifiant de la colonne a été trouvé on récupère les données*/
                            if ($iDataclassKey !== null) {
                                $bContinueParsing = true;
                            }
                            /* Après récupération des données de propriétés on parcours les données */
                            $aCurrent = $oCsv->fgetcsv();

                            /* Si l'identifiant de la colonne a été trouvé on récupère les données*/
                            while ($bContinueParsing) {
                                if (is_array($aCurrent) && count($aCurrent) === $iNbTotalColumns) {
                                    if (empty($sWSDataclassName)) {
                                        /* Récupération du nom de la table du WS */
                                        $sWSDataclassName = $aCurrent[$iDataclassKey];
                                        /* Création du tableau de toutes les propriétés */
                                        $aProperties[$sWSDataclassName] = $aCurrentProperties;
                                    }

                                    /* Tableau des données de tous les exports du fichier CSV
                                     * Le Tableau aura pour clé le nom de sa colonne au lieu des
                                     * identifiants de colonnes
                                     */
                                    $aData[$sWSDataclassName][] = array_combine($aCurrentProperties, array_slice($aCurrent, 0, $iNbUsedColumns));

                                    /* Passage à la ligne suivante */
                                    $aCurrent = $oCsv->fgetcsv();
                                } else {
                                    $bContinueParsing = false;
                                }
                            }
                        }
                    }
                }
            }
        }
        /* Instanciation de la propriété aWSProperties */
        $this->setWSProperties($aProperties);
        /* Instanciation de la propriété aWSData */
        $this->setWSData($aData);
        /* Suppression des tableaux pour un gain de mémoire */
        unset($aData);
        unset($aProperties);
        unset($aCurrentProperties);

        error_log('fin lecture csv ');
    }

    /**
     * Méthode publique permettant de vérifier que la ligne (tableau) passée en
     * paramètre est un title d'une table exportée $aWSProperties.
     *
     * @param array $aProperties Tableau des colonnes des exports CSV
     */
    private function setWSProperties($aProperties)
    {
        if (is_array($aProperties)) {
            $this->aWSProperties = $aProperties;
        }
    }

    /**
     * Méthode publique permettant de vérifier que la ligne (tableau) passée en
     * paramètre est un title d'une table exportée $aWSData.
     *
     * @param array $aWSData Tableau des données des exports CSV
     */
    private function setWSData($aData)
    {
        if (is_array($aData)) {
            $this->aWSData = $aData;
        }
    }

    /**
     * Méthode publique permettant de vérifier que la ligne (tableau) passée en
     * paramètre est un title d'une table exportée $aWSProperties.
     *
     * @return array $aProperties Tableau des colonnes des exports CSV
     */
    public function getWSProperties()
    {
        return $this->aWSProperties;
    }

    /**
     * Méthode publique permettant de vérifier que la ligne (tableau) passée en
     * paramètre est un title d'une table exportée $aWSData.
     *
     * @return array $aWSData Tableau des données des exports CSV
     */
    public function getWSData()
    {
        return $this->aWSData;
    }

    /**
     * Méthode publique d'instancier la propriété.
     *
     * @param array $aLine Ligne découpé en tableau du fichier d'export CSV
     *
     * @return boolean true si la ligne est reconnue comme un début d'export
     *                 false sinon
     */
    public function isExportTable($aLine)
    {
        /* Initialisation des variables */
        $bIsExportTable = false;
        if (is_array($aLine) && count($aLine) == 1) {
            /* Recherche du title en utilisant un expression régulière définie */
            $mExportFound = preg_match(gamme_csv_regex_title_import, $aLine[0]);
            if ($mExportFound === 1) {
                $bIsExportTable = true;
            }
        }

        return $bIsExportTable;
    }

    /**
     * Méthode de parcours du fichier CSV.
     *
     * @param string[] $aProperties Nom des colonnes de l'export CSV
     *
     * @return mixed Null si aucune clé n'est trouvé, Int si la colonne contenant
     *               le nom de colonne "dataclass" a été trouvé
     */
    public static function getCsvDataclassKey($aProperties)
    {
        /* Initialisation des variables */
        $iDataclassKey = null;

        /* Parcours du tableau des noms des colonnes pour trouver où se trouve
         * Dataclass
         */
        if (is_array($aProperties) && !empty($aProperties)) {
            foreach ($aProperties as $iPropertyKey => $sPropertyName) {
                if ($sPropertyName === gamme_csv_dataclass_column) {
                    $iDataclassKey = $iPropertyKey;
                }
            }
        }

        return $iDataclassKey;
    }

    /**
     * Méthode statique permettant de vérifier sur le fichier d'export CSV
     * est présent et lisible.
     *
     * @param string $sFilePath Chemin d'accès aux fichier d'export CSV
     *
     * @return boolean Si le fichier est présent et lisible la méthode
     *                 renvoit true et false sinon
     */
    public static function isReadableExport($sFilePath)
    {
        /* Initialisation des variables */
        $bIsReadableFile = false;
        $sFilePath = (string) $sFilePath;

        /* Vérification de l'accès au fichier CSV */
        $oCSVFileInfo = new \SplFileInfo($sFilePath);
        if ($oCSVFileInfo->isReadable() && $oCSVFileInfo->getSize() > 0) {
            $bIsReadableFile = true;
        }

        return $bIsReadableFile;
    }

    /**
     * Méthode statique permettant renvoyer un objet SplFileObject de
     * l'export CSV.
     *
     * @param string $sFilePath Chemin d'accès aux fichier d'export CSV
     *
     * @return object Objet SplFileObject du fichier passé en paramètre de la
     *                méthode
     */
    public static function getSplFileObjectExport($sFilePath, $sColumnSep)
    {
        /* Initialisation des variables */
        $sFilePath = (string) $sFilePath;
        $sColumnSep = (string) $sColumnSep;

        /* Ouverture du fichier CSV en lecture */
        $oCsv = new \SplFileObject($sFilePath, 'r');
        /* Définition du support du CSV */
        $oCsv->setFlags(\SplFileObject::READ_CSV);
        /* Définition des caractères de séparation */
        $oCsv->setCsvControl($sColumnSep);

        return $oCsv;
    }

    /**
     * Méthode publique qui utilise l'objet représentant une table pour utiliser
     * sa table de correspondance et les traitements spécifiques qui lui sont
     * alloués. La méthode utilise donc un tableau formatté dont les données
     * proviennent du CSV d'export des WebService PSA. A partir de ce tableau
     * On crée un tableau qui ne va contenir que les données à insérér en base
     * de données. Cette méthode permet la prise en compte de méthode de traitement
     * sur un champ spécifique du tableau.
     *
     * @param object   $oTableObject
     * @param string[] $aWSAllProperties Tableau des noms de colonnes des exports
     * @param array[]  $aWSAllData       Tableau des données de tous les exports
     *
     * @return array[] $aDataToImport      Tableau contenant les données à
     *                 insérer en base pour l'export dans
     *                 une table
     */
    public function getDataToInsert($oTableObject, $aWSAllProperties, $aWSAllData)
    {
        /* Instanciation des données et propriétés provenant du WebService de l'objet */
        $oTableObject->setWSChild($aWSAllProperties, $aWSAllData);
        /* Récupération des données pour l'insertion dans la table correspondant à l'objet */
        $aWSChildData = $oTableObject->getWSChildData();
        /* Récupération du tableau de matching des colonnes entre celles
         * du WebService et celles utilisées pour les tables de
         * la base de données pour l'insertion dans la table correspondant à l'objet */
        $aColumnsImportMatching = $oTableObject->getColumnsImportMatching();

        $aDataToImport = array();

        if (is_array($aWSChildData) && !empty($aWSChildData) && is_array($aColumnsImportMatching) && !empty($aColumnsImportMatching)) {
            /* Pour chaque enregistrement du tableau on va potentiellement faire un traitement*/
            foreach ($aWSChildData as $aOneWSChildData) {
                foreach ($aOneWSChildData as $sKeyWSProperty => $sValue) {
                    /* Définit si une méthode présente dans l'objet doit effectuer
                     * un traitement sur la donnée
                     */
                    $bKeyMethodExist = false;
                    /* On vérifie sie le champ présent dans le WebService est à
                     * insérer en base de données
                     */
                    if (array_key_exists($sKeyWSProperty, $aColumnsImportMatching)) {
                        /* Vérification de la présence d'une méthode permettant
                         * de faire une traitement de la données du champs dans
                         * l'objet passé en paramètre
                         */
                        $sNameTestingMethod = gamme_modify_field_prefix.strtolower($aColumnsImportMatching[$sKeyWSProperty]);
                        $bKeyMethodExist = method_exists($oTableObject, $sNameTestingMethod);

                        /* Si une méthode de traitement de données existent
                         * on l'utilise sinon on reprend la données telle quelle
                         */
                        if ($bKeyMethodExist === true) {
                            $aImportLine[$aColumnsImportMatching[$sKeyWSProperty]] = $oTableObject->$sNameTestingMethod($sValue);
                        } else {
                            $aImportLine[$aColumnsImportMatching[$sKeyWSProperty]] = $sValue;
                        }

                        /* Ajout du l'identifiant de site et de langue pour
                         * chacun des enregistrements si le champ culture est présent
                         */
                        if ($sKeyWSProperty == gamme_csv_culture_field_WS) {
                            $aImportLine[gamme_csv_site_id_field_PHPfact] = self::getSiteIdByCulture($sValue, $this->aSites);
                            $aImportLine[gamme_csv_langue_id_field_PHPfact] = self::getLangueIdByCulture($sValue, $this->aLanguages);
                        }
                    }
                }

                /* Quelques règles de vérification avant import de la ligne */
                if (self::isValidImportedLine($aImportLine)) {
                    /* Création du tableau final */
                    $aDataToImport[] = $aImportLine;
                }
            }
        }
        /* Suppression des tableaux pour un gain de mémoire */
        unset($aWSAllProperties);
        unset($aWSAllData);

        return $aDataToImport;
    }

    /**
     * Méthode publique statique permettant de retrouver un identifiant de site
     * par rapport à un code "Culture" fourni par le fichier d'export CSV de PSA.
     *
     * @param string   $sCultureCode Code du Culture informant du pays et de
     *                               la langue
     * @param string[] $aSites       Tableau des sites dont les clés sont les
     *                               code pays des sites
     *
     * @return mixted $iSiteId        Si un identifiant de site a été trouvé
     *                le type est integer
     *                Si aucun identifiant n'a été trouvé,
     *                renvoit null
     */
    public static function getSiteIdByCulture($sCultureCode, $aSites)
    {
        /* Initialisation des variables */
        $sCultureCode = (string) $sCultureCode;
        $iSiteId = null;
        $sSiteCode = '';

        if (is_array($aSites) && !empty($aSites) && !empty($sCultureCode)) {
            /* Récupération du Code pays du site dans le champs "Culture"
             * c'est la chaîne de caractère présente après le underscore dans le
             * champ "culture"
             */
            $aCultureParams = explode('-', $sCultureCode);
            if (is_array($aCultureParams) && array_key_exists(1, $aCultureParams)) {
                $sSiteCode = strtoupper($aCultureParams[1]);
            }
            if (!empty($sSiteCode) && array_key_exists($sSiteCode, $aSites)) {
                $iSiteId = (int) $aSites[$sSiteCode]['SITE_ID'];
            }
        }

        return $iSiteId;
    }

    /**
     * Méthode publique statique permettant de retrouver un identifiant de langue
     * par rapport à un code "Culture" fourni par le fichier d'export CSV de PSA.
     *
     * @param string   $sCultureCode Code du Culture informant du pays et de
     *                               la langue
     * @param string[] $aLanguages   Tableau des langues dont les clés sont les
     *                               code de langue
     *
     * @return mixted $iLangueId      Si un identifiant de langue a été trouvé
     *                le type est integer
     *                Si aucun identifiant n'a été trouvé,
     *                renvoit null
     */
    public static function getLangueIdByCulture($sCultureCode, $aLanguages)
    {
        /* Initialisation des variables */
        $sCultureCode = (string) $sCultureCode;
        $iLangueId = null;

        if (is_array($aLanguages) && !empty($aLanguages) && !empty($sCultureCode)) {
            /* A SUPPRIMER UNE FOIS LES CODE LANGUE  MODIFIES*/
            /* Récupération du Code de langue dans le champs "Culture" */
            $sLanguageCode = strtolower(substr($sCultureCode, 0, 2));
            if (!empty($sLanguageCode) && array_key_exists($sLanguageCode, $aLanguages)) {
                $iLangueId = (int) $aLanguages[$sLanguageCode];
            }
        }

        return $iLangueId;
    }

    /**
     * Méthode publique statique permettant de vérifier que la ligne de données
     * à importer dans la table correspond bien à un langue et une pays existant
     * en base de données.
     *
     * @param array $aLine Ligne du tableau de données à importer
     *
     * @return boolean $bIsValidImportedLine   Renvoit true si la ligne peut être importée
     *                 false sinon
     */
    public static function isValidImportedLine($aLine)
    {
        /* Initialisation des variables */
        $bIsValidImportedLine = false;

        if (is_array($aLine) && !empty($aLine) &&
                array_key_exists(gamme_csv_culture_field_PHPfact, $aLine) && !empty($aLine[gamme_csv_culture_field_PHPfact]) &&
                array_key_exists(gamme_csv_site_id_field_PHPfact, $aLine) && !empty($aLine[gamme_csv_site_id_field_PHPfact]) &&
                array_key_exists(gamme_csv_langue_id_field_PHPfact, $aLine) && !empty($aLine[gamme_csv_langue_id_field_PHPfact])
        ) {
            $bIsValidImportedLine = true;
        }

        return $bIsValidImportedLine;
    }

//    public function getTableIndexes($sTableName)
//    {
//        /* Initialisation des variables */
//        $sTableName = (string)$sTableName;
//        $aTableIndexes = array();
//        $sPrimaryIndexKey = 'PRIMARY';
//        $sOtherIndexKey = 'OTHER';
//
//        /* Récupération de l'instance de base de données */
//        $oConnection = \Pelican_Db::getInstance();
//
//        $aTemp = $oConnection->queryTab("SHOW INDEX FROM {$sTableName}");
//
//        if ( is_array($aTemp) && !empty($aTemp) ){
//            foreach ($aTemp as $aOneIndex){
//                if ( $aOneIndex['Key_name'] === $sPrimaryIndexKey ){
//                    $aTableIndexes[$sPrimaryIndexKey][] = $aOneIndex;
//                }else{
//                    $aTableIndexes[$sOtherIndexKey][] = $aOneIndex;
//                }
//            }
//        }        var_dump($aTableIndexes);
//
//        return $aTableIndexes;
//
//    }
}
