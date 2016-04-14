<?php
/**
 * __DESC__.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */

/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */
require_once 'Pelican/Media.php';

class Ndp_Media extends Pelican_Media
{
    const RATIO_CARRE = "carre";
    /**
     * Fonction de generation de la jointure SQL pour l'utilisation du média + récupération des contenus.
     *
     * @param array
     *
     * @return array
     *         string jointure
     *         string and
     */
    public static function getMediaSqlJointure($table)
    {
        // nombre de table pour la jointure = nombre total de table - la table principale
        $nb_tables = sizeof($table) - 1;
        $jointure  = "";
        $and       = "";
        //liste table de jointure
        for ($i = 0; $i < $nb_tables; $i++) {
            if ($table[$i] == null || empty($table[$i])) {
                break;
            }
            if ($table[$i]["key"] != "" && $table[$i]["table"] != "") {
                $jointure .= ", ".$table[$i]["table"]." ";
                $and      .= $table["principale"].".".$table[$i]["key"]." = ".$table[$i]["table"].".".$table[$i]["key"]." and ";
            }
        }


        return array('jointure' => $jointure, 'and' => $and);
    }

    /**
     *
     * @param array $contents
     *
     * @return array
     */
    public static function getContentsIdFromArray($contents)
    {
        $ids = [];
        foreach ($contents as $key => $content) {
            $ids[] = $content['content_id'];
        }
        
        return $ids;
    }
    
    /**
     * Fonction de contrôle d'utilisation du média + récupération des contenus.
     *
     * @param int $id Identifiant du Pelican_Media
     *
     * @return array
     */
    public static function getMediaUsageContent($id)
    {
        $retour = [];

        //boucle sur les contenus
        if (is_array(Pelican::$config["FW_MEDIA_USAGE_CONTENT"])) {
            $oConnection = Pelican_Db::getInstance();

            foreach (Pelican::$config["FW_MEDIA_USAGE_CONTENT"] as $table) {
                $content= [];
                if (isset($table["principale"])) {

                    $and = self::getMediaSqlJointure($table)['and'];
                    $jointure = self::getMediaSqlJointure($table)['jointure'];

                    //début de la requete
                    $query = "SELECT content_type_id, content_title,
                                ".$table["principale"].".content_id,
                                group_concat(DISTINCT  ".$table["principale"].".content_version SEPARATOR ', ') as versions, site_id ";
                    $query .= " from ".$table["principale"];
                    $query .= $jointure;
                    $query .= "where ";
                    $query .= $and;
                    $query .= "".$table["principale"].".media_id = :ID ";
                    $query .= " group by ".$table["principale"].".content_id";

                    $content = $oConnection->queryTab($query, array(":ID" => $id));
                }
                //merge des résultats contenus
                $retour = array_merge($retour, $content);
            }
            $contentsIds = join(',', self::getContentsIdFromArray($retour));
            $query = "
                SELECT #pref#_content.content_type_id, #pref#_content_version.content_title, #pref#_content_version.content_id, group_concat( #pref#_content_version.content_version SEPARATOR ', ') AS versions, site_id
                FROM #pref#_content_version, #pref#_content
                WHERE #pref#_content_version.content_id = #pref#_content.content_id
                AND (
                #pref#_content_version.MEDIA_ID8    = :ID
                OR #pref#_content_version.MEDIA_ID7 = :ID
                OR #pref#_content_version.MEDIA_ID6 = :ID
                OR #pref#_content_version.MEDIA_ID5 = :ID
                OR #pref#_content_version.MEDIA_ID4 = :ID
                OR #pref#_content_version.MEDIA_ID3 = :ID
                OR #pref#_content_version.MEDIA_ID2 = :ID
                OR #pref#_content_version.MEDIA_ID  = :ID
                )";
            if (!empty($contentsIds)) {
                $query .= " AND #pref#_content.content_id NOT IN (".$contentsIds.")";
            }
            $query .= " GROUP BY #pref#_content_version.content_id ";
            $content = $oConnection->queryTab($query, array(":ID" => $id));
            $retour = array_merge($retour, $content);
        }

        return $retour;
    }

    /**
     * Fonction de contrôle d'utilisation des medias des rubrique.
     *
     *
     * @param int $id Identifiant du Pelican_Media
     *
     * @return array
     */
    public static function getMediaUsageRubrique($id)
    {
        $retour = [];
        //boucle sur les rubriques
        if (is_array(Pelican::$config["FW_MEDIA_USAGE_RUBRIQUE"])) {
            $oConnection = Pelican_Db::getInstance();
            foreach (Pelican::$config["FW_MEDIA_USAGE_RUBRIQUE"] as $table) {
                $rubrique = array();
                if (isset($table["principale"])) {

                    $and = self::getMediaSqlJointure($table)['and'];
                    $jointure = self::getMediaSqlJointure($table)['jointure'];
                    
                    $media = 'media_id';
                    if ($table['media']) {
                        $media = $table['media'];
                    }

                    //début de la requete
                    $query = "SELECT site_id, page_title, page_clear_url,
                                ".$table["principale"].".page_id,
                                group_concat(DISTINCT  ".$table["principale"].".page_version SEPARATOR ', ') as versions ";
                    $query .= " from ".$table["principale"];
                    $query .= $jointure;
                    $query .= " where ";
                    $query .= $and;
                    $query .= "".$table["principale"].".".$media." = :ID ";
                    $query .= " group by ".$table["principale"].".page_id";
                    $rubrique = $oConnection->queryTab($query, array(":ID" => $id));
                }
                //merge des résultats rubrique
                $retour = array_merge($retour, $rubrique);
            }
        }

        return $retour;
    }

      /**
     * Fonction de contrôle d'utilisation des médias utilisés par l'administrateur.
     *
     *
     * @param int $id Identifiant du Pelican_Media
     *
     * @return array
     */
    public static function getMediaUsageAdmin($id)
    {
        $retour = [];

        if (is_array(Pelican::$config["FW_MEDIA_USAGE_ADMIN"])) {
            $oConnection = Pelican_Db::getInstance();
            foreach (Pelican::$config["FW_MEDIA_USAGE_ADMIN"] as $table) {
                $admin = array();
                if (isset($table["table"])) {

                    //début de la requete
                    $query = "SELECT ";
                    if (is_array($table['field']) && !empty($table['field'])) {
                        $aSelect = array("'".$table["table"]."' as tableName");
                        foreach ($table['field'] as $alias => $field) {
                            $aSelect[] = $field.' as '.$alias;
                        }
                        $query .= implode($aSelect, ', ');
                    }
                    $query .= " from ".$table["table"];
                    $query .= " where ";
                    if (is_array($table['media']) && !empty($table['media'])) {
                        $where = array();
                        foreach ($table['media'] as $media) {
                            $where[] = $media.' = :ID';
                        }
                        $query .= implode($where, ' OR ');
                    }

                    $admin = $oConnection->queryTab($query, array(":ID" => $id));
                }
                //merge des résultats rubrique
                $retour = array_merge($retour, $admin);
            }
        }

        return $retour;
    }

    /**
     * Fonction de contrôle d'utilisation du média + récupération des contenus/rubriques.
     *
     * @param int $id Identifiant du Pelican_Media
     * @param bool $disabled
     *
     * @return array
     */
    public static function checkMediaUsageDetail($id, $disabled = false)
    {
        $return = false;

        if (!$disabled  &&  Pelican::$config["FW_MEDIA_TABLE_NAME"] && Pelican::$config["FW_MEDIA_USAGE"] && !empty($id)) {

            $content  = self::getMediaUsageContent($id);
            $rubrique = self::getMediaUsageRubrique($id);
            $admin    = self::getMediaUsageAdmin($id);

            $return   = array($content, $rubrique, $admin);
        }

        return $return;
    }



}
