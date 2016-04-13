<?php
/**
	* Fichier de configuration de la médiathèque
	*
	* @package Pelican
	* @subpackage config
	*/

/**
 *
 * @ignore
 *
 */

/**
 * Répertoire temporaire de décompression des flash (version 6)
 */
Pelican::$config ["FW_MEDIA_FLASH_TMP_ROOT"] = "/tmp";

// Nombre d'éléments dans la liste
Pelican::$config ["FW_MEDIA_LIST_LIMIT"] = "12";

// nombre limite de dossiers
Pelican::$config ["FW_MEDIA_FOLDER_LIMIT"] = "900";

// constantes de définition de la prévisualisation des médias
Pelican::$config ["FW_MEDIA_PREVIEW_LIMIT"] = "152";
Pelican::$config ["FW_MEDIA_PREVIEW_STOP_LIST"] = array ("_work/emoticons" );

// Autorisations d'ajout ou de suppression
Pelican::$config ["FW_MEDIA_ALLOW_ADD"] = true;
Pelican::$config ["FW_MEDIA_ALLOW_DEL"] = true;

// id du format de Pelican_Media_Thumbnail
Pelican::$config ["IMG_FORMAT_THUMBNAIL"] = 1;
//Media directory root
Pelican::$config ["MEDIA_DIRECTORY_ROOT"] = array('162');
//Media directory accessible a tous
Pelican::$config ["MEDIA_DIRECTORY_ALLCOUNTRIES"] = array('ALL'=>'8449,211,209,9342,193,421,7032,1391,182,181,264,200,2074,9342,289,162',
														  'ROOT'=>'162');
/**
 * Triggers de gestion des Pelican_Media
 */
// Pelican::$config["FW_MEDIA_TRIGGER"]["research"] =
// array("doc","htm","html","pdf","ppt","rtf","txt","xls");
// Pelican::$config["FW_MEDIA_TRIGGER"]["rsync"] = array("IP1", "IP2");
// Pelican::$config["FW_MEDIA_TRIGGER"]["image_format"] = array(1,2,3);
// Pelican::$config["FW_MEDIA_RSYNC_CMD"] = "rsync -aI --delete %SRC%
// %HOST%:%DEST%";

// constantes de définition des roles des champs de bdd pour la table des médias
Pelican::$config ["FW_MEDIA_TABLE_NAME"] = "#pref#_media";
Pelican::$config ["FW_MEDIA_FIELD_ID"] = "MEDIA_ID"; // Obligatoire
Pelican::$config ["FW_MEDIA_FIELD_LIB"] = "MEDIA_TITLE"; // Obligatoire
Pelican::$config ["FW_MEDIA_FIELD_FILE"] = "MEDIA_TITLE"; // Obligatoire
Pelican::$config ["FW_MEDIA_FIELD_PATH"] = "MEDIA_PATH"; // Obligatoire
Pelican::$config ["FW_MEDIA_FIELD_TYPE"] = "MEDIA_TYPE_ID"; // Obligatoire
Pelican::$config ["FW_MEDIA_FIELD_CREATION_DATE"] = "MEDIA_CREATION_DATE"; // Optionnel
                                                                          // :
                                                                          // prendra
                                                                          // la
                                                                          // date
                                                                          // physique
                                                                          // sinon
Pelican::$config ["FW_MEDIA_FIELD_DEBUT_DATE"] = "MEDIA_DEBUT_DATE"; // Date de
                                                                    // fin des
                                                                    // droits
Pelican::$config ["FW_MEDIA_FIELD_FIN_DATE"] = "MEDIA_FIN_DATE"; // Date de fin
                                                                // des droits
Pelican::$config ["FW_MEDIA_FIELD_EXPIRATION_DATE"] = ""; // Date d'expiration
Pelican::$config ["FW_MEDIA_FIELD_WIDTH"] = "MEDIA_WIDTH"; // Optionnel : prendra
                                                          // la largeur réelle
                                                          // sinon
Pelican::$config ["FW_MEDIA_FIELD_HEIGHT"] = "MEDIA_HEIGHT"; // Optionnel :
                                                            // prendra la
                                                            // hauteur réelle
                                                            // sinon
Pelican::$config ["FW_MEDIA_FIELD_WEIGHT"] = "MEDIA_WEIGHT"; // Optionnel :
                                                            // prendra le poids
                                                            // réel sinon
Pelican::$config ["FW_MEDIA_FIELD_ALT"] = "MEDIA_ALT"; // Optionnel : Alt de
                                                      // l'image
Pelican::$config ["FW_MEDIA_FIELD_MD5"] = "MEDIA_MD5"; // Optionnel :
                                                      // codification md5

// Tableau des champs de formulaire
                                                      // array(libellé, type de
                                                      // champ, obligatoire ou
                                                      // non)
Pelican::$config ["FW_MEDIA_FIELD"] = array (
    "MEDIA_ID" => array ("", "hidden" ),
    "MEDIA_DIRECTORY_ID" => array ("", "hidden" ),
    "MEDIA_TYPE_ID" => array ("", "hidden" ),
    "MEDIA_WIDTH" => array ("", "hidden" ),
    "MEDIA_HEIGHT" => array ("", "hidden" ),
    "MEDIA_WEIGHT" => array ("", "hidden" ),
    "MEDIA_MD5" => array ("", "hidden" ),
    "MEDIA_PATH" => array ("Chemin", "hidden" ),
    "MEDIA_TITLE" => array (t ( 'TITRE' ), "text", true ),
    "MEDIA_ALT" => array (t ( 'LEGENDE' ), "text" ),
    "MEDIA_DIFFUSED" => array (t("Mutualiser"), "hidden" ),
    "MEDIA_CREDIT" => array (t("Copyright"), "text" ),
    "MEDIA_COMMENT" => array (t ( 'COMMENTAIRES' ), "textarea" ),
    "MEDIA_ID_REFERENT" => array (t('MEDIA_REFERENT'), "media")
);
// "MEDIA_COMMENT" => array("Commentaires", "editor")

// constantes de définition des roles des champs de bdd pour la table des
// extensions de médias
Pelican::$config ["FW_MEDIA_EXTENSION_TABLE_NAME"] = "#pref#_media_extension";
Pelican::$config ["FW_MEDIA_EXTENSION_FIELD_MEDIA_EXTENSION_VISIBLE"] = "MEDIA_EXTENSION_VISIBLE"; // Obligatoire
Pelican::$config ["FW_MEDIA_EXTENSION_FIELD_MEDIA_TYPE_ID"] = "MEDIA_TYPE_ID"; // Obligatoire

// constantes de définition des roles des champs de bdd pour la table des types
                                                                              // de
                                                                              // médias
Pelican::$config ["FW_MEDIA_TYPE_TABLE_NAME"] = "#pref#_media_type";
Pelican::$config ["FW_MEDIA_TYPE_FIELD_MEDIA_TYPE_ID"] = "MEDIA_TYPE_ID"; // Obligatoire

// constantes de définition des roles des champs de bdd pour la table des
                                                                         // catégories
Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"] = "#pref#_media_directory";
Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] = "MEDIA_DIRECTORY_ID"; // Obligatoire
Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"] = "MEDIA_DIRECTORY_PATH"; // Obligatoire
Pelican::$config ["FW_MEDIA_FIELD_FOLDER_NAME"] = "MEDIA_DIRECTORY_LABEL"; // Obligatoire
Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PARENT"] = "MEDIA_DIRECTORY_PARENT_ID";
Pelican::$config ["FW_MEDIA_FOLDER_BATCH"] = array ("MEDIA_DEBUT_DATE", "MEDIA_FIN_DATE", "MEDIA_CREDIT" );

// constantes de définition des roles des champs de bdd pour la table des
// formats de média
Pelican::$config ["FW_MEDIA_FORMAT_TABLE_NAME"] = "#pref#_media_format";
Pelican::$config ["FW_MEDIA_FORMAT_FIELD_ID"] = "MEDIA_FORMAT_ID"; // Obligatoire
Pelican::$config ["FW_MEDIA_FORMAT_FIELD_LABEL"] = "MEDIA_FORMAT_LABEL"; // Obligatoire
Pelican::$config ["FW_MEDIA_FORMAT_FIELD_WIDTH"] = "MEDIA_FORMAT_WIDTH"; // Obligatoire
Pelican::$config ["FW_MEDIA_FORMAT_FIELD_HEIGHT"] = "MEDIA_FORMAT_HEIGHT"; // Obligatoire

// Tables contenant des liaisons avec les Pelican_Media
Pelican::$config ["FW_MEDIA_USAGE"] = array (
    "#pref#_media_usage",
    "#pref#_content_version_media",
    "#pref#_page_zone",
    "#pref#_page_zone_media",
    "#pref#_paragraph_media",
    "#pref#_page_version",
    "#pref#_page_version_media",
    "#pref#_content_version",
    "#pref#_content_zone",
    "view_content_version",
    "#pref#_page_multi",
    "#pref#_page_multi_zone",
    "#pref#_page_multi_zone_media",
    "#pref#_page_multi_zone_multi",
    "#pref#_page_multi_zone_multi",
    "#pref#_page_zone_multi"
);

// Table de contenu contenant des liaisons avec les Pelican_Media
Pelican::$config ["FW_MEDIA_USAGE_CONTENT"] = array (
        array("principale"=>"#pref#_content_version_media",
            array("table"=>"#pref#_content_version", "key"=>"content_id"),
            array("table"=>"#pref#_content", "key"=>"content_id")
        ),
        array("principale"=>"#pref#_paragraph_media",
            array("table"=>"#pref#_content_version", "key"=>"content_id"),
            array("table"=>"#pref#_content", "key"=>"content_id")
        ),
        array("principale"=>"#pref#_content_zone",
            array("table"=>"#pref#_content_version", "key"=>"content_id"),
            array("table"=>"#pref#_content", "key"=>"content_id")
        ),
		 array("principale"=>"#pref#_content_zone_multi",
            array("table"=>"#pref#_content_version", "key"=>"content_id"),
            array("table"=>"#pref#_content", "key"=>"content_id")
        )
    );

//Table de rubrique/page contenant des liaisons avec les Pelican_Media
Pelican::$config ["FW_MEDIA_USAGE_RUBRIQUE"] = array (
        array(
            "principale"=>"#pref#_page_zone",
            array("table"=>"#pref#_page_version", "key"=>"page_id"),
            array("table"=>"#pref#_page", "key"=>"page_id")
        ),
        array("principale"=>"#pref#_page_zone_media",
            array("table"=>"#pref#_page_version", "key"=>"page_id"),
            array("table"=>"#pref#_page", "key"=>"page_id")
        ),
        array("principale"=>"#pref#_page_version",
            array("table"=>"#pref#_page", "key"=>"page_id")
        ),
        array("principale"=>"#pref#_page_version_media",
            array("table"=>"#pref#_page_version", "key"=>"page_id"),
            array("table"=>"#pref#_page", "key"=>"page_id")
        ),
        array("principale"=>"#pref#_page_multi",
            array("table"=>"#pref#_page_version", "key"=>"page_id"),
            array("table"=>"#pref#_page", "key"=>"page_id")
        ),
        array("principale"=>"#pref#_page_multi_zone",
            array("table"=>"#pref#_page_version", "key"=>"page_id"),
            array("table"=>"#pref#_page", "key"=>"page_id")
        ), 
        array("principale"=>"#pref#_page_multi_zone", "media"=>"media_id2",
            array("table"=>"#pref#_page_version", "key"=>"page_id"),
            array("table"=>"#pref#_page", "key"=>"page_id")
        ), 
        array("principale"=>"#pref#_page_multi_zone", "media"=>"media_id3",
            array("table"=>"#pref#_page_version", "key"=>"page_id"),
            array("table"=>"#pref#_page", "key"=>"page_id")
        ), 
        array("principale"=>"#pref#_page_multi_zone", "media"=>"media_id4",
            array("table"=>"#pref#_page_version", "key"=>"page_id"),
            array("table"=>"#pref#_page", "key"=>"page_id")
        ), 
        array("principale"=>"#pref#_page_multi_zone", "media"=>"media_id5",
            array("table"=>"#pref#_page_version", "key"=>"page_id"),
            array("table"=>"#pref#_page", "key"=>"page_id")
        ), 
        array("principale"=>"#pref#_page_multi_zone", "media"=>"media_id6",
            array("table"=>"#pref#_page_version", "key"=>"page_id"),
            array("table"=>"#pref#_page", "key"=>"page_id")
        ), 
        array("principale"=>"#pref#_page_multi_zone", "media"=>"media_id7",
            array("table"=>"#pref#_page_version", "key"=>"page_id"),
            array("table"=>"#pref#_page", "key"=>"page_id")
        ), 
        array("principale"=>"#pref#_page_multi_zone", "media"=>"media_id8",
            array("table"=>"#pref#_page_version", "key"=>"page_id"),
            array("table"=>"#pref#_page", "key"=>"page_id")
        ), 
        array("principale"=>"#pref#_page_multi_zone", "media"=>"media_id9",
            array("table"=>"#pref#_page_version", "key"=>"page_id"),
            array("table"=>"#pref#_page", "key"=>"page_id")
        ),
        array("principale"=>"#pref#_page_multi_zone_media",
            array("table"=>"#pref#_page_version", "key"=>"page_id"),
            array("table"=>"#pref#_page", "key"=>"page_id")
        ),
        array("principale"=>"#pref#_page_multi_zone_multi",
            array("table"=>"#pref#_page_version", "key"=>"page_id"),
            array("table"=>"#pref#_page", "key"=>"page_id")
        ),
        array("principale"=>"#pref#_page_zone_multi",
            array("table"=>"#pref#_page_version", "key"=>"page_id"),
            array("table"=>"#pref#_page", "key"=>"page_id")
        )
    );

Pelican::$config ["FW_MEDIA_USAGE_ADMIN"] = array (
        array(
            "table"=>"#pref#_vehicule",
            "media" => array(
                'VEHICULE_MEDIA_ID_THUMBNAIL',
                'VEHICULE_MEDIA_ID_WEB1',
                'VEHICULE_MEDIA_ID_WEB2',
                'VEHICULE_MEDIA_ID_WEB3'
            ),
            "field" => array(
                'id' => 'vehicule_id',
                'lib' => 'vehicule_label'
            )
        ),
		 array(
            "table"=>"#pref#_vehicule_couleur",
            "media" => array(
                'VEHICULE_COULEUR_MEDIA_ID_PICTO',
                'VEHICULE_COULEUR_MEDIA_ID_BCKGRD_WEB1',
                'VEHICULE_COULEUR_MEDIA_ID_BCKGRD_WEB2',
                'VEHICULE_COULEUR_MEDIA_ID_BCKGRD_WEB3',
                'VEHICULE_COULEUR_MEDIA_ID_CAR_WEB1',
                'VEHICULE_COULEUR_MEDIA_ID_CAR_WEB2',
                'VEHICULE_COULEUR_MEDIA_ID_CAR_WEB3',
                'VEHICULE_COULEUR_MEDIA_ID_CAR_MOB1'
            ),
            "field" => array(
                'id' => 'vehicule_id'
            )
        )
);


Pelican::$config ["FW_MEDIA_NOT_USED_UPD"] = array (
	array("table"=>"#pref#_page_zone_multi", "key"=>"MEDIA_ID"),
	array("table"=>"#pref#_page_version", "key"=>"MEDIA_ID"),
	array("table"=>"#pref#_page_multi_zone_multi", "key"=>"MEDIA_ID"),
	array("table"=>"#pref#_page_multi_zone", "key"=>"MEDIA_ID"),
	array("table"=>"#pref#_content_version", "key"=>"MEDIA_ID"),
	array("table"=>"#pref#_content_version_media", "key"=>"MEDIA_ID"),
	array("table"=>"#pref#_page_zone_media", "key"=>"MEDIA_ID"),
	array("table"=>"#pref#_page_zone", "key"=>"MEDIA_ID"),
	array("table"=>"#pref#_content_zone", "key"=>"MEDIA_ID"),
	array("table"=>"#pref#_page_multi", "key"=>"MEDIA_ID"),
	array("table"=>"#pref#_content_zone_multi", "key"=>"MEDIA_ID")
);

// Gestion des vignettes
Pelican::$config ["IMG_WIDTH_THUMBNAIL"] = "60";
Pelican::$config ["IMG_HEIGHT_THUMBNAIL"] = "40";

// Pelican_Media_ImageMagick
Pelican::$config ["IM_EXT"] = "jpg";
Pelican::$config ["IM_MIME"] = "image/jpeg";
// Pelican::$config["IM_EXT"] = "gif";
// Pelican::$config["IM_MIME"] = "image/gif";
Pelican::$config ["TTF_FONT"] = array ("Arial" => array ("Windows" => "Arial", "Normal" => Pelican::$config ['LIB_ROOT'] . Pelican::$config ['LIB_MEDIA'] . "/ttf/ariblk.ttf", "Italic" => Pelican::$config ['LIB_ROOT'] . Pelican::$config ['LIB_MEDIA'] . "/ttf/ariali.ttf", "Bold" => Pelican::$config ['LIB_ROOT'] . Pelican::$config ['LIB_MEDIA'] . "/ttf/arialbd.ttf", "BoldItalic" => Pelican::$config ['LIB_ROOT'] . Pelican::$config ['LIB_MEDIA'] . "/ttf/arialbi.ttf" ), "AvantGarde-Book" => array ("Windows" => "AvantGarde Book", "Normal" => "AvantGarde-Book", "Italic" => "AvantGarde-BookOblique" ), "AvantGarde-Demi" => array ("Windows" => "AvantGarde Demi", "Normal" => "AvantGarde-Demi", "Italic" => "AvantGarde-DemiOblique" ), "Bookman-Demi" => array ("Windows" => "Bookman Demi", "Normal" => "Bookman-Demi", "Italic" => "Bookman-DemiItalic" ), "Bookman-Light" => array ("Windows" => "Bookman Light", "Normal" => "Bookman-Light", "Italic" => "Bookman-LightItalic" ), "Courrier" => array ("Windows" => "Courrier", "Normal" => "Courier", "Italic" => "Courier-Oblique", "Bold" => "Courier-Bold", "BoldItalic" => "Courier-BoldOblique" ), "Georgia" => array ("Windows" => "Georgia", "Normal" => Pelican::$config ['LIB_ROOT'] . Pelican::$config ['LIB_MEDIA'] . "/ttf/georgia.ttf", "Italic" => Pelican::$config ['LIB_ROOT'] . Pelican::$config ['LIB_MEDIA'] . "/ttf/georgiai.ttf", "Bold" => Pelican::$config ['LIB_ROOT'] . Pelican::$config ['LIB_MEDIA'] . "/ttf/georgiab.ttf", "BoldItalic" => Pelican::$config ['LIB_ROOT'] . Pelican::$config ['LIB_MEDIA'] . "/ttf/georgiaz.ttf" ), "Helvetica" => array ("Windows" => "Helvetica", "Normal" => "Helvetica", "Italic" => "Helvetica-Oblique", "Bold" => "Helvetica-Bold", "BoldItalic" => "Helvetica-BoldOblique" ), "Helvetica-Narrow" => array ("Windows" => "Helvetica Narrow", "Normal" => "Helvetica-Narrow", "Italic" => "Helvetica-Narrow-Oblique", "Bold" => "Helvetica-Narrow-Bold", "BoldItalic" => "Helvetica-Narrow-BoldOblique" ), "NewCenturySchlbk" => array ("Windows" => "New Century Schlbk", "Normal" => "NewCenturySchlbk-Roman", "Italic" => "NewCenturySchlbk-Italic", "Bold" => "NewCenturySchlbk-Bold", "BoldItalic" => "NewCenturySchlbk-BoldItalic" ), "Palatino" => array ("Windows" => "Palatino", "Normal" => "Palatino-Roman", "Italic" => "Palatino-Italic", "Bold" => "Palatino-Bold", "BoldItalic" => "Palatino-BoldItalic" ), "Symbol" => array ("Windows" => "Symbol", "Normal" => "Symbol" ), "Tahoma" => array ("Windows" => "Tahoma", "Normal" => Pelican::$config ['LIB_ROOT'] . Pelican::$config ['LIB_MEDIA'] . "/ttf/tahoma.ttf" ), "Times" => array ("Windows" => "Times", "Normal" => "Times-Roman", "Italic" => "Times-Italic", "Bold" => "Times-Bold", "BoldItalic" => "Times-BoldItalic" ), "Verdana" => array ("Windows" => "Verdana", "Normal" => Pelican::$config ['LIB_ROOT'] . Pelican::$config ['LIB_MEDIA'] . "/ttf/verdanaz.ttf", "Italic" => Pelican::$config ['LIB_ROOT'] . Pelican::$config ['LIB_MEDIA'] . "/ttf/verdanai.ttf", "Bold" => Pelican::$config ['LIB_ROOT'] . Pelican::$config ['LIB_MEDIA'] . "/ttf/verdanab.ttf" ), "Amazone" => array ("Windows" => "Amazone", "Normal" => Pelican::$config ['LIB_ROOT'] . Pelican::$config ['LIB_MEDIA'] . "/ttf/amazone.ttf" ) );
ksort ( Pelican::$config ["TTF_FONT"] );

Pelican::$config ["FONT_SITE"] [0] = array ("width" => 190, "height" => 39, "font" => "industria.ttf", "color" => "'#FF500B'", "back" => "white", "pointsize" => 40, "pointsize_small" => 23, "pointsize_small2" => 26 );

// cache navigateur
Pelican::$config['IMAGE_CACHE_DURATION'] = 1 ;

Pelican::$config['ALLOWED_EXTENSTION_MEDIA'] = array(
    "image" => array(
        "libelle" => t('IMAGES'),
        "png" => "Image PNG",
        "gif" => "Image GIF",
        "jpg" => "Image JPEG",
        "jpeg" => "Image JPEG",
        "bmp" => "Image Bitmap"),
	"list-image" => array(
        "libelle" => t('IMAGE_LIST'),
        "png" => "Image PNG",
        "gif" => "Image GIF",
        "jpg" => "Image JPEG",
        "jpeg" => "Image JPEG",
        "bmp" => "Image Bitmap"),
    "file" => array(
        "libelle" => t('FICHIERS'),
        "ai" => "Adobe Illustrator",
        "eps" => "Postcript",
        "pdf" => "Fichier Adobe Acrobat",
        "rtf" => "Fichier RTF",
        "doc" => "Fichier Microsoft Word",
        "docx" => "Fichier Microsoft Word 2007",
        "xls" => "Fichier Microsoft Excel",
        "xlsx" => "Fichier Microsoft Excel 2007",
        "zip" => "Fichier ZIP",
        "ppt" => "Fichier Microsoft PowerPoint",
        "pptx" => "Fichier Microsoft PowerPoint 2007"),
    "flash" => array(
        "libelle" => t('FLASH'),
        "swf" => "Animation Flash"),
    "video" => array(
        "libelle" => t('VIDEOS'),
        "mp4" => "Vidéo mp4",
        "webm" => "Vidéo WebM",
        /*"zip" => "Vidéo Flash externe",
        "flv" => "Vidéo Flash", 
        "xml" => "Vidéo Flash interne",
        "mp3" => "MP3",
        "f4v" => "Video media file"*/
     ),
    "youtube" => array(
        "libelle" => t("YOUTUBE")/*,
        "flv" => "Vidéo Flash"*/),
	"list-youtube" => array(
        "libelle" => t("YOUTUBE-LIST")
		)
    );

//Parametre pour configurer les options d'imagemagick dans la classe: Pelican_Cache_Media                                            
Pelican::$config['IMAGE_MAGICK']['OPT']['QUALITY']  =   '90';
Pelican::$config['IMAGE_MAGICK']['OPT']['TYPE']     =   'TrueColor';
//Pelican::$config['IMAGE_MAGICK']['OPT']['UNSHARP']  =   '0x0.1';

//taille max des medias
Pelican::$config['MAX_SIZE']['IMAGE']  =   358400 ; /*en octet*/  //350ko   
Pelican::$config['MAX_SIZE']['IMAGE-GIF']  =   550000 ; /*en octet*/  //350ko  
Pelican::$config['MAX_SIZE']['FILE']   =   10485760 ; /*en octet*/  //10Mo   
Pelican::$config['MAX_SIZE']['FLASH']  =   5242880 ; /*en octet*/  //5MO   
Pelican::$config['MAX_SIZE']['VIDEO']  =   10485760 ; /*en octet*/  //10Mo     

//max size label media
Pelican::$config['MAX_SIZE_LABEL']['IMAGE']  =   '350ko / 550ko pour GIF' ; 
Pelican::$config['MAX_SIZE_LABEL']['IMAGE-GIF']  =   '550ko' ; 
Pelican::$config['MAX_SIZE_LABEL']['LIST-IMAGE']  =   '350ko' ;
Pelican::$config['MAX_SIZE_LABEL']['FILE']   =   '10Mo' ;  
Pelican::$config['MAX_SIZE_LABEL']['FLASH']  =   '5Mo' ; 
Pelican::$config['MAX_SIZE_LABEL']['VIDEO']  =   '10Mo' ;                                              
?>