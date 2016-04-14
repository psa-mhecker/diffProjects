<?php
/**
 * Accès aux médias
 *
 * @author Vincent Paré <vincent.pare@businessdecision.com>
 */

namespace MediaApi\v1\Model;

use MediaApi\v1\Util;
use MediaApi\v1\Type;
use Luracast\Restler\RestException;

class Media implements MediaInterface
{
    /**
     * Retourne le dossier racine de la médiathèque
     *
     * @return MediaNode
     */
    public function getMediaRoot()
    {
        $dbh = Util::getDbh();
        
        $stmt = "SELECT md.* FROM ".APP_PREFIXE."media_directory md WHERE md.MEDIA_DIRECTORY_PARENT_ID IS NULL";
        $result = $dbh->query($stmt)->fetchAll();
        $mediaRoot = new Type\MediaNode($result[0]);
        
        return $mediaRoot;
    }
    
    /**
     * Retourne la structure des dossiers de la médiathèque du pays $country
     *
     * @param string $country Code pays du site cible
     * @param MediaNode $mediaRoot Dossier racine de la médiathèque
     * @return array
     */
    public function getHierarchy($country, $mediaRoot)
    {
        $dbh = Util::getDbh();
        
        // Récupération du pays demandé
        $stmt = $dbh->prepare("SELECT s.* FROM ".APP_PREFIXE."site s "
            ."INNER JOIN ".APP_PREFIXE."site_code sc ON sc.SITE_ID = s.SITE_ID AND sc.SITE_CODE_PAYS LIKE :country");
        $stmt->execute(array('country' => $country));
        $result = $stmt->fetchAll();
        $site = isset($result[0]) ? $result[0] : null;
        if (empty($site)) {
            throw new RestException(404, "No country");
        }
        
        // Récupération des dossiers de la médiathèque
        $mediaIndex = array($mediaRoot->ID => $mediaRoot);
        $result = $dbh->query("SELECT md.* FROM ".APP_PREFIXE."media_directory md
            WHERE md.SITE_ID = " . $dbh->quote($site['SITE_ID']) . "
            ORDER BY md.MEDIA_DIRECTORY_LABEL ASC, md.MEDIA_DIRECTORY_ID ASC");
        while ($row = $result->fetch()) {
            $id = intval($row['MEDIA_DIRECTORY_ID']);
            $mediaIndex[$id] = new Type\MediaNode($row);
        }
        
        return $mediaIndex;
    }
    
    /**
     * Retourne la liste des médias contenus dans le dossier $folderId de la médiathèque
     *
     * @param int $folder ID du dossier de la médiathèque
     * @param array $types Filtre sur le type de média
     * @param int $langueId ID de la langue des champs Alt & Title
     * @return array
     */
    public function getFolderContent($folder, $types, $langueId = null)
    {
        $dbh = Util::getDbh();
        
        $types = array_map(array($dbh, 'quote'), $types);
        $sqlTypeCond = implode(', ', $types);
        $stmt = $dbh->prepare("SELECT
            m.MEDIA_ID,
            m.MEDIA_TYPE_ID,
            m.MEDIA_WIDTH,
            m.MEDIA_HEIGHT,
            m.MEDIA_WEIGHT,
            m.MEDIA_PATH,
            m.MEDIA_TITLE,
            m.MEDIA_ALT,
            m.YOUTUBE_ID
        FROM ".APP_PREFIXE."media m
        WHERE m.MEDIA_DIRECTORY_ID = :folder AND m.MEDIA_TYPE_ID IN (" . $sqlTypeCond . ")
        ORDER BY m.MEDIA_CREATION_DATE DESC, m.MEDIA_ID DESC");
        $stmt->execute(array('folder' => $folder));
        $result = $stmt->fetchAll();
        
        return $result;
    }
    
    /**
     * Retourne les informations sur un média
     *
     * @param int $id ID du media
     * @param int $langueId ID de la langue des champs Alt & Title
     * @return array
     */
    public function getMediaInfo($id, $langueId = null)
    {
        $dbh = Util::getDbh();
        
        $stmt = $dbh->prepare("SELECT * FROM ".APP_PREFIXE."media m WHERE m.MEDIA_ID = :id");
        $stmt->execute(array('id' => $id));
        $result = $stmt->fetchAll();
        
        return $result;
    }
}
