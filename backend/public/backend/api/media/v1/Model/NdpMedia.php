<?php
/**
 * Accès aux médias NDP
 *
 * @author Vincent Paré <vincent.pare@businessdecision.com>
 */

namespace MediaApi\v1\Model;

use MediaApi\v1\Util;

class NdpMedia extends Media implements MediaInterface
{
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
        $stmt = $dbh->prepare("
        SELECT
            m.MEDIA_ID,
            m.MEDIA_TYPE_ID,
            m.MEDIA_WIDTH,
            m.MEDIA_HEIGHT,
            m.MEDIA_WEIGHT,
            m.MEDIA_PATH,
            mat.TITLE AS MEDIA_TITLE,
            mat.ALT AS MEDIA_ALT,
            m.YOUTUBE_ID
        FROM ".APP_PREFIXE."media m
        LEFT JOIN ".APP_PREFIXE."media_alt_translation mat ON mat.MEDIA_ID = m.MEDIA_ID AND mat.LANGUE_ID = :lang
        WHERE m.MEDIA_DIRECTORY_ID = :folder AND m.MEDIA_TYPE_ID IN (" . $sqlTypeCond . ")
        ORDER BY m.MEDIA_CREATION_DATE DESC, m.MEDIA_ID DESC");
        $stmt->execute(array('folder' => $folder, 'lang' => $langueId));
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
        
        $stmt = $dbh->prepare("
        SELECT
            m.MEDIA_ID,
            m.MEDIA_TYPE_ID,
            m.MEDIA_WIDTH,
            m.MEDIA_HEIGHT,
            m.MEDIA_WEIGHT,
            m.MEDIA_PATH,
            mat.TITLE AS 'MEDIA_TITLE',
            mat.ALT AS 'MEDIA_ALT',
            m.YOUTUBE_ID,
            m.MEDIA_CREATION_DATE,
            m.MEDIA_DEBUT_DATE,
            m.MEDIA_FIN_DATE,
            m.MEDIA_AUTHOR,
            m.MEDIA_CREDIT,
            m.MEDIA_COMMENT
        FROM ".APP_PREFIXE."media m
        LEFT JOIN ".APP_PREFIXE."media_alt_translation mat ON mat.MEDIA_ID = m.MEDIA_ID AND mat.LANGUE_ID = :lang
        WHERE m.MEDIA_ID = :id");
        $stmt->execute(array('id' => $id, 'lang' => $langueId));
        $result = $stmt->fetchAll();
        
        return $result;
    }
}
