<?php
/**
 * Informations détaillées sur un média
 *
 * @author Vincent Paré <vincent.pare@businessdecision.com>
 */

namespace MediaApi\v1\Type;

class MediaInfo extends MediaShortInfo
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
    
    public $CreationDate;
    public $StartDate;
    public $EndDate;
    public $Author;
    public $Credit;
    public $Comment;
    
    /**
     * Mapping entre les colonnes de la table psa_media et les attributs de la classe
     */
    public static $dbMapping = array(
        'MEDIA_CREATION_DATE' => 'CreationDate',
        'MEDIA_DEBUT_DATE' => 'StartDate',
        'MEDIA_FIN_DATE' => 'EndDate',
        'MEDIA_AUTHOR' => 'Author',
        'MEDIA_CREDIT' => 'Credit',
        'MEDIA_COMMENT' => 'Comment',
    );
}
