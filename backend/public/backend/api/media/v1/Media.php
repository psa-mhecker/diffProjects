<?php
/**
 * Media API - webservice de lecture de la médiathèque PHPFactory
 *
 * @author Vincent Paré <vincent.pare@businessdecision.com>
 * @version 1.1.0
 */

namespace MediaApi\v1;

use int;
use string;
use Luracast\Restler\RestException;

class Media
{
    /**
     * Retourne la structure des dossiers de la médiathèque
     *
     * @param string $country Code pays du site {@from path}
     * @param int $folder ID du dossier de la médiathèque
     * @param int $depth Profondeur maximale de la hiérarchie (0 = pas de limite)
     * @access protected
     */
    public function hierarchy($country, $folder = null, $depth = 1)
    {
        $dbh = Util::getDbh();
        
        // Récupération de la racine de la médiathèque
        $mediaRoot = $this->restler->mediaModel->getMediaRoot();
        
        // Récupération des dossiers de la médiathèque
        $mediaIndex = $this->restler->mediaModel->getHierarchy($country, $mediaRoot);
        
        // Distribution des dossiers dans leur parent
        foreach ($mediaIndex as $key => $val) {
            if (empty($val->ParentID)) {
                continue;
            }
            $mediaIndex[$val->ParentID]->MediaNode[] = $val;
        }
        
        // Sélection de la racine
        $tree = $mediaIndex[$mediaRoot->ID];
        if ($folder) {
            if (!isset($mediaIndex[$folder])) {
                throw new RestException(404, "No directory #" . $folder);
            }
            $tree = $mediaIndex[$folder];
        }
        
        // Limitation profondeur de l'arborescence
        if ($depth !== 0) {
            Util::depthLimit($tree, 1, $depth);
        }
        
        // Custom XML format
        if (is_a($this->restler->responseFormat, 'Luracast\\Restler\\Format\\XmlFormat')) {
            $format = new Format\MediaXmlFormat();
            $format->restler = $this->restler;
            $this->restler->responseFormat = $format;
            Format\MediaXmlFormat::$rootName = 'Hierarchy';
            return array('MediaNode' => $tree);
        }
        
        return array('Hierarchy' => array('MediaNode' => $tree));
    }
    
    /**
     * Liste le contenu d'un dossier de la médiathèque
     *
     * @param int $folder ID du dossier de la médiathèque {@from query}
     * @param string $type Type de media {@from query}
     * @param int $lang ID de la langue des champs Alt & Title {@from query}
     * @access protected
     */
    public function mediaList($folder, $type, $lang = null)
    {
        $dbh = Util::getDbh();
        
        // Lecture types
        $types = explode(';', $type);
        
        // Sélection des données
        $result = $this->restler->mediaModel->getFolderContent($folder, $types, $lang);
        
        if (empty($result)) {
            throw new RestException(404, "No media");
        }
        
        // Conversion des tuples en objets
        $mediaList = array();
        foreach ($result as $key => $val) {
            $mediaList[] = new Type\MediaShortInfo($val);
        }
        
        // Custom XML format
        if (is_a($this->restler->responseFormat, 'Luracast\\Restler\\Format\\XmlFormat')) {
            $format = new Format\MediaXmlFormat();
            $format->restler = $this->restler;
            $this->restler->responseFormat = $format;
            Format\MediaXmlFormat::$rootName = 'MediaList';
            return array('Media' => $mediaList);
        }
        
        return array('MediaList' => array('Media' => $mediaList));
    }
    
    /**
     * Retourne les informations sur un média
     *
     * @param int $id ID du média
     * @param int $lang ID de la langue des champs Alt & Title {@from query}
     * @access protected
     */
    public function mediaInfos($id, $lang = null)
    {
        $result = $this->restler->mediaModel->getMediaInfo($id, $lang);
        
        if (empty($result)) {
            throw new RestException(404, "No media");
        }
        
        $mediaInfo = new Type\MediaInfo($result[0]);
        
        // Custom XML format
        if (is_a($this->restler->responseFormat, 'Luracast\\Restler\\Format\\XmlFormat')) {
            $format = new Format\MediaXmlFormat();
            $format->restler = $this->restler;
            $this->restler->responseFormat = $format;
            Format\MediaXmlFormat::$rootName = 'MediaInfo';
            return array('Media' => $mediaInfo);
        }
        
        return array('MediaInfo' => array('Media' => array($mediaInfo)));
    }
}
