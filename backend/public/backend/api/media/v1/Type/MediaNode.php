<?php
/**
 * Modèle de représentation des dossiers de la médiathèque
 *
 * @author Vincent Paré <vincent.pare@businessdecision.com>
 */

namespace MediaApi\v1\Type;

class MediaNode
{
    public $ID;
    public $Label;
    public $Path;
    public $ParentID;
    public $MediaNode = array();
    
    /**
     * Mapping entre les colonnes de la table psa_media_directory et les attributs de la classe
     */
    public static $dbMapping = array(
        'MEDIA_DIRECTORY_ID' => 'ID',
        'MEDIA_DIRECTORY_LABEL' => 'Label',
        'MEDIA_DIRECTORY_PATH' => 'Path',
        'MEDIA_DIRECTORY_PARENT_ID' => 'ParentID',
    );
    
    /**
     * Créé un objet depuis un tuple psa_media_directory
     *
     * @param array $data Tuple psa_media_directory
     */
    public function __construct($data)
    {
        $this->load($data);
    }
    
    /**
     * Charge les attributs avec un typle de la base de données
     *
     * @param array $data Tuple psa_media_directory
     */
    public function load($data)
    {
        foreach ($data as $key => $val) {
            if (isset(self::$dbMapping[$key])) {
                $this->{self::$dbMapping[$key]} = $val;
            }
        }
    }
}
