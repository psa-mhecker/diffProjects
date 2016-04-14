<?php
/**
 * Interface du modèle d'accès aux données des médias
 *
 * @author Vincent Paré <vincent.pare@businessdecision.com>
 */

namespace MediaApi\v1\Model;

interface MediaInterface
{
    /**
     * Retourne le dossier racine de la médiathèque
     *
     * @return MediaNode
     */
    public function getMediaRoot();
    
    /**
     * Retourne la structure des dossiers de la médiathèque du pays $country
     *
     * @param string $country Code pays du site cible
     * @param MediaNode $mediaRoot Dossier racine de la médiathèque
     * @return array
     */
    public function getHierarchy($country, $mediaRoot);
    
    /**
     * Retourne la liste des médias contenus dans le dossier $folderId de la médiathèque
     *
     * @param int $folder ID du dossier de la médiathèque
     * @param array $types Filtre sur le type de média
     * @param int $langueId ID de la langue des champs Alt & Title
     * @return array
     */
    public function getFolderContent($folder, $types, $langueId = null);
    
    /**
     * Retourne les informations sur un média
     *
     * @param int $id ID du media
     * @param int $langueId ID de la langue des champs Alt & Title
     * @return array
     */
    public function getMediaInfo($id, $langueId = null);
}
