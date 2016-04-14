<?php
/**
 * Informations sur un média
 *
 * @author Vincent Paré <vincent.pare@businessdecision.com>
 */

namespace MediaApi\v1\Type;

class MediaShortInfo
{
    public $ID;
    public $Type;
    public $Width;
    public $Height;
    public $Size;
    public $Path;
    public $Title;
    public $Alt;
    public $Url;
    public $ThumbnailUrl;
    public $YoutubeID;
    
    /**
     * Données brutes de la base de données
     */
    protected $tuple;
    
    /**
     * Mapping entre les colonnes de la table psa_media et les attributs de la classe
     */
    public static $dbMapping = array(
        'MEDIA_ID' => 'ID',
        'MEDIA_TYPE_ID' => 'Type',
        'MEDIA_WIDTH' => 'Width',
        'MEDIA_HEIGHT' => 'Height',
        'MEDIA_WEIGHT' => 'Size',
        'MEDIA_PATH' => 'Path',
        'MEDIA_TITLE' => 'Title',
        'MEDIA_ALT' => 'Alt',
        'YOUTUBE_ID' => 'YoutubeID',
    );
    
    /**
     * Créé un objet depuis un tuple psa_media
     *
     * @param array $data Tuple psa_media
     */
    public function __construct($data)
    {
        // Stockage des données brutes
        $this->tuple = $data;
        
        // Chargement des données brutes
        $this->load($data);
        
        // Champs calculés
        $this->Url = "http://" . \Pelican::$config['HTTP_MEDIA'] . $data['MEDIA_PATH'];
        $this->ThumbnailUrl = $this->Url;
    }
    
    /**
     * Charge les attributs avec un typle de la base de données
     *
     * @param array $data Tuple psa_media
     */
    public function load($data)
    {
        $dbMapping = array_merge(self::$dbMapping, static::$dbMapping);
        foreach ($data as $key => $val) {
            if (isset($dbMapping[$key])) {
                $this->{$dbMapping[$key]} = $val;
            }
        }
    }
}
