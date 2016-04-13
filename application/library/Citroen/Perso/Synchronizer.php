<?php
/**
 * Synchronisation des éléments multi de la perso
 *
 * La synchronisation modifie automatiquement les données dans les profils de personnalisation
 * lors de l'enregistrement des pages en backoffice, en utilisant les données génériques.
 * Les données sont synchronisées à l'intérieur de l'objet (attribut persoData, accessible en lecture seule).
 *
 * Cette classe est la partie serveur de la synchronisation, elle fonctionne de pair avec l'objet javascript
 * MultiMetadataManager qui collecte les informations sur les multi génériques ajoutés et supprimés.
 * Les éléments multi génériques sont identifiés par le champ MULTI_HASH (hash sha1), et le lien entre un
 * élément multi synchronisé et son multi générique est stocké dans l'attribut (JSON) _sync.
 *
 * Exemple :
 * $persoData = Synchronizer::unserialize($page['PAGE_PERSO']);
 * $sync = new Synchronizer($persoData, $_POST);
 * $sync->sync('PUSH_OUTILS_MAJEUR', 1);
 * $page['PAGE_PERSO'] = Synchronizer::serialize($sync->persoData);
 *
 * Synchronizer embarque un mode debug (verbose) qui affiche des données au cours du processus de synchronisation.
 *
 * @since 2015-04-30
 * @author Vincent Paré <vincent.pare@businessdecision.com>
 */

namespace Citroen\Perso;

use \Citroen_View_Helper_Global;
use \Backoffice_Form_Helper;
use \Exception;

class Synchronizer
{
    protected $persoData;
    protected $values;
    protected $postData;
    public $verbose;
    
    /**
     * Codes des exceptions lancées par Synchronizer
     */
    const EX_UNREADABLE_PERSODATA  = 1000;
    const EX_CANT_GRAB_GENERICDATA = 1001;
    const EX_MULTIPLE_3M_MATCHES   = 1002;
    
    /**
     * Charge les données dans l'objet Synchronizer
     *
     * @param array $persoData Données de la popin perso
     * @param array $values Données génériques (généralement Pelican_Db::$values)
     * @param array $postData Contenu brut de $_POST (pour lecture des collections MultiMetadataManager)
     * @param bool $verbose Permet d'activer le mode debug
     */
    public function __construct($persoData, $values, $postData, $verbose = false)
    {
        if (empty($persoData) || !is_array($persoData)) {
            throw new Exception("Unreadable persoData", self::EX_UNREADABLE_PERSODATA);
        }
        $this->persoData = $persoData;
        $this->values = $values;
        $this->postData = $postData;
        $this->verbose = $verbose;
        
        if ($this->verbose) {
            echo '<h2>new '.__CLASS__.'</h2>';
            self::debugUI($this);
        }
    }
    
    /**
     * Accès en lecture seule aux attributs internes
     *
     * @param string $attrName Nom de l'attribut
     * @return mixed Valeur de l'attribut
     */
    public function __get($attrName)
    {
        return $this->$attrName;
    }
    
    /**
     * Sérialisation des données perso
     *
     * @param array $persoData Objet personnalisation
     * @return string
     */
    public static function serialize($persoData)
    {
        $persoData = Citroen_View_Helper_Global::arrHtmlEncode($persoData);
        $serializedPersoData = json_encode($persoData);
        return $serializedPersoData;
    }
    
    /**
     * Lecture des données perso depuis le champ en base de données
     *
     * @param string $serializedPersoData Objet personnalisation serializé
     * @return array
     */
    public static function unserialize($serializedPersoData)
    {
        $persoData = json_decode($serializedPersoData, true);
        $persoData = Citroen_View_Helper_Global::arrHtmlDecode($persoData);
        return $persoData;
    }
    
    /**
     * Synchronise les éléments multi de la perso avec les données génériques
     *
     * @param string $multiName Nom du multi à synchroniser (ex: PUSH_OUTILS_MAJEUR)
     * @param int $multiMax Nombre d'éléments maximum du multi (si null, aucune limite)
     */
    public function sync($multiName, $multiMax = null)
    {
        // Debug
        if ($this->verbose) {
            $randomBgColor = function ($colorSeed) use ($multiName) {
                $seed = crc32($multiName.$colorSeed);
                mt_srand($seed);
                return str_pad(dechex(mt_rand(180, 220)), 2, '0', STR_PAD_LEFT);
            };
            $backgroundColor = $randomBgColor('R').$randomBgColor('G').$randomBgColor('B');
            echo '<div style="background-color:#'.$backgroundColor.'; padding:10px; margin:10px 0;">'
                .'<h3>'.$multiName.'</h3>'
                .'<pre>'.__METHOD__."\n".'sync AV</pre>';
            self::debugUI($this->persoData);
        }
        
        // Récupération données génériques
        try {
            $genericData = $this->extractGenericData($multiName);
        } catch (Exception $ex) {
            if ($this->verbose) {
                trigger_error("add/update cannot be performed without generic data", E_USER_WARNING);
            }
            $genericData = null;
        }
        
        // Parcours de tous les onglets perso à la recherche des multi à synchroniser
        foreach ($this->persoData as $profileKey => $profileVal) {
            // Filtre : l'onglet de profil doit contenir un multi de type $multiName
            if (!isset($profileVal[$multiName]) || !is_array($profileVal[$multiName])) {
                continue;
            }
            
            // Nettoyage des données perso du multi (suppression éléments vides)
            $profileVal[$multiName] = self::cleanMultiCollection($profileVal[$multiName], $multiName);
            
            // Synchronisation
            $profileVal[$multiName] = $this->delete($profileVal[$multiName], $multiName);
            if (isset($genericData)) {
                $profileVal[$multiName] = $this->add($profileVal[$multiName], $multiName, $genericData, $multiMax);
                $profileVal[$multiName] = $this->update($profileVal[$multiName], $genericData);
            }
            
            // Tri des multi par champ order
            usort($profileVal[$multiName], array('self', 'multiCompare'));
            
            // Enregistrement des nouvelles données du multi (après traitements ajout/suppression + synchro)
            $this->persoData[$profileKey][$multiName] = $profileVal[$multiName];
        }
        
        if ($this->verbose) {
            echo '<pre>'.__METHOD__."\n".'sync AP</pre>';
            self::debugUI($this->persoData);
            echo '</div>';
        }
    }
    
    /**
     * Retourne un array contenant les éléments multi génériques du multi $multiName, indexé par le MULTI_HASH
     *
     * @param string $multiName Nom du multi
     * @return array Liste des éléments multi génériques
     */
    protected function extractGenericData($multiName)
    {
        // Récupération des valeurs génériques pour ce multi push
        $genericDataRaw = Backoffice_Form_Helper::standaloneReadMulti($this->values, $multiName);
        if (empty($genericDataRaw) || !is_array($genericDataRaw)) {
            throw new Exception("Can't grab generic data from ".$multiName, self::EX_CANT_GRAB_GENERICDATA);
        }
        
        // Indexation des valeurs génériques par MULTI_HASH
        $genericData = array();
        foreach ($genericDataRaw as $key => $val) {
            if (empty($val['MULTI_HASH'])) {
                continue;
            }
            $genericData[$val['MULTI_HASH']] = $val;
        }
        
        return $genericData;
    }
    
    /**
     * Nettoie une collection d'éléments multi, en supprimant les éléments parasites
     * qui ne comportent que des champs cachés
     *
     * @param array $multiCollection Tableau d'éléments multi
     * @param string $multiName Nom du multi
     * @return array $multiCollection nettoyée
     */
    protected static function cleanMultiCollection($multiCollection, $multiName)
    {
        foreach ($multiCollection as $multiKey => $multiVal) {
            unset($multiVal[$multiName]);
            unset($multiVal['MULTI_HASH']);
            unset($multiVal['']);
            if (empty($multiVal)) {
                unset($multiCollection[$multiKey]);
            }
        }
        return $multiCollection;
    }
    
    /**
     * Suppression des éléments multi perso faisant référence à des éléments multi génériques supprimés
     *
     * @param array $multiCollection Tableau d'éléments multi à traiter
     * @param string $multiName Nom du multi à traiter
     * @return array $multiCollection modifié
     */
    protected function delete($multiCollection, $multiName)
    {
        try {
            $deletedMultiHashes = $this->extractMetadataCollection('deleted_multi_index', $multiName);
        } catch (Exception $ex) {
            trigger_error($ex->getMessage(), E_USER_WARNING);
            return $multiCollection;
        }
        
        if (!empty($deletedMultiHashes)) {
            foreach ($multiCollection as $multiKey => $multiVal) {
                // Si le multi $multiVal est synchronisé sur un multi supprimé, on supprime $multiVal
                if (in_array($multiVal['_sync'], $deletedMultiHashes)) {
                    unset($multiCollection[$multiKey]);
                }
            }
        }
        return $multiCollection;
    }
    
    /**
     * Ajout des nouveaux éléments multi génériques dans les données perso
     *
     * @param array $multiCollection Tableau d'éléments multi à traiter
     * @param string $multiName Nom du multi à traiter
     * @param array $genericData Données génériques (retourné par self::extractGenericData)
     * @param int $multiMax Nombre d'éléments maximum du multi (si null, aucune limite)
     * @return array $multiCollection modifié
     */
    protected function add($multiCollection, $multiName, $genericData, $multiMax = null)
    {
        // Liste des identifiants des nouveaux éléments multi génériques
        try {
            $addedMultiHashes = $this->extractMetadataCollection('added_multi_index', $multiName);
        } catch (Exception $ex) {
            trigger_error($ex->getMessage(), E_USER_WARNING);
            return $multiCollection;
        }
        
        if (empty($addedMultiHashes)) {
            return $multiCollection;
        }
        
        // Extraction des nouveaux éléments génériques
        $genericDataNewElements = array();
        foreach ($genericData as $multiKey => $multiVal) {
            if (in_array($multiVal['MULTI_HASH'], $addedMultiHashes)) {
                $genericDataNewElements[$multiKey] = $multiVal;
            }
        }
        
        // Insertion des nouveaux éléments multi dans la perso
        foreach ($genericDataNewElements as $genericMultiKey => $genericMultiVal) {
            // Vérification du nombre de multi perso (pour ne pas dépasser la limite)
            if (isset($multiMax) && count($multiCollection) >= $multiMax) {
                continue;
            }
            $genericMultiVal['_sync'] = $genericMultiVal['MULTI_HASH'];
            $multiCollection[] = $genericMultiVal;
        }
        return $multiCollection;
    }
    
    /**
     * Mise à jour des éléments multi perso avec l'élément multi générique sur lequel ils sont synchronisés
     *
     * @param array $multiCollection Tableau d'éléments multi à traiter
     * @param array $genericData Données génériques (retourné par self::extractGenericData)
     * @return array $multiCollection modifié
     */
    protected function update($multiCollection, $genericData)
    {
        foreach ($multiCollection as $multiKey => $multiVal) {
            // Filtre : le multi doit être synchronisé (_sync != -2)
            if (!isset($multiVal['_sync']) || $multiVal['_sync'] == -2) {
                continue;
            }
            $genericMultiId = $multiVal['_sync'];
            
            // Filtre : le multi cible (générique) doit exister
            if (!isset($genericData[$genericMultiId])) {
                continue;
            }
            
            // Synchronisation des données de l'élément multi
            $genericMultiVal = $genericData[$genericMultiId];
            $multiCollection[$multiKey] = array_merge($multiVal, $genericMultiVal);
        }
        return $multiCollection;
    }
    
    /**
     * Extrait les données d'une collection MultiMetadataManager
     *
     * Le préfixe multi de la tranche n'étant pas transmis dans Pelican_Db::$values,
     * l'extraction est basée sur un match partiel du nom de la collection.
     * Cela fonctionne à condition qu'il n'y ait qu'un seul multi de chaque type dans
     * la page, donc ce workaround ne fonctionne pas sur des tranches de la zone dynamique.
     * Si le cas se produit, une exception est levée.
     *
     * @param string $collectionName Type de collection MultiMetadataManager (added_multi_index|deleted_multi_index)
     * @param string $multiName Nom du multi
     * @return array|null
     */
    protected function extractMetadataCollection($collectionName, $multiName)
    {
        if (!isset($this->postData[$collectionName])) {
            return null;
        }
        
        $collection = null;
        $pattern = '#^(multi\d+_)?'.preg_quote($multiName, '#').'$#';
        foreach ($this->postData[$collectionName] as $key => $val) {
            if (preg_match($pattern, $key)) {
                if (isset($collection)) {
                    $msg = "Multiple matches for ".$collectionName.".".$multiName;
                    throw new Exception($msg, self::EX_MULTIPLE_3M_MATCHES);
                    return null;
                }
                $collection = $val;
            }
        }
        return $collection;
    }
    
    /**
     * Affiche le debug d'une variable dans un <textarea>
     *
     * @param mixed $var Variable à debugger
     * @param array $option
     * @param bool $return Retourne le code HTML au leu de l'afficher si la valeur est "true"
     */
    public static function debugUI($var, $option = array(), $return = false)
    {
        $color = isset($option['color']) ? $option['color'] : 'white';
        $label = isset($option['label']) ? $option['label'] : null;
        $title = isset($option['title']) ? $option['title'] : null;
        $textareaOpenTag = sprintf(
            '<textarea %s %s onclick="this.select();" cols="30" rows="10">',
            'title="'.htmlspecialchars($title).'"',
            'style="background-color:'.$color.';"'
        );
        $display = ($label ? $label : '')
            .$textareaOpenTag
            .htmlspecialchars(print_r($var, true))
            .'</textarea>';
        if ($return) {
            return $display;
        } else {
            echo $display;
        }
    }
    
    /**
     * Callback de comparaison de 2 éléments multi sur leur champ ordre
     * À utiliser avec les fonctions de tri de tableau (http://php.net/manual/en/array.sorting.php)
     *
     * @param array $a Élément multi A
     * @param array $b Élément multi B
     */
    public static function multiCompare($a, $b)
    {
        $orderKey = 'PAGE_ZONE_MULTI_ORDER';
        $orderA = isset($a[$orderKey]) && is_numeric($a[$orderKey]) ? intval($a[$orderKey]) : null;
        $orderB = isset($b[$orderKey]) && is_numeric($b[$orderKey]) ? intval($b[$orderKey]) : null;
        
        // Les 2 multi ont le même ordre
        if ($orderA === $orderB) {
            return 0;
        }
        
        // Si un des 2 éléments n'a pas d'ordre, il est considéré comme supérieur
        if ($orderA === null) {
            return -1;
        }
        if ($orderB === null) {
            return 1;
        }
        
        return $orderA < $orderB ? -1 : 1;
    }
}
