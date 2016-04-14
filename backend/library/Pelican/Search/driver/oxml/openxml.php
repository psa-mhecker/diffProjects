<?php

class OpenXMLException extends Exception
{
    private $source;

    public function __construct($message, $source)
    {
        parent::__construct($message);
        $this->source = $source;
    }

    public function __toString()
    {
        return 'Une exception '.__CLASS__.' a été déclenchée par la méthode '.$this->source.'. Cause : '.$this->message;
    }
}

class OpenXMLFatalException extends Exception
{
    private $source;

    public function __construct($message, $source)
    {
        parent::__construct($message);
        $this->source = $source;
    }

    public function __toString()
    {
        return 'Une exception fatale '.__CLASS__.' a été déclenchée par la méthode '.$this->source.'. Cause : '.$this->message;
    }
}

class OpenXMLDocumentFactory
{
    public static function openDocument($fileName)
    {
        $zip = new ZipArchive();
        if ($zip->open($fileName) !== true) {
            throw new OpenXMLFatalException('Impossible d\'ouvrir le fichier '.$fileName, __METHOD__);
        }
        // On recherche le Content Type de la partie principale du document
        $type = OpenXMLDocument::getMainPartContentType($zip);

        $content = OpenXMLDocument::getContent($zip, $type);

        $zip->close();
        // On instancie et on retourne la classe concrète de document correspondant au type de contenu
        return $content;
    }
}

abstract class OpenXMLDocument
{
    private $zip;

    // Core properties (communes à tous les types de documents Office)
    private $creator;
    private $subject;
    private $keywords;
    private $description;
    private $date_created;
    private $date_modified;
    private $last_writer;
    private $revision;

    const RELATIONSHIPS_NS = 'http://schemas.openxmlformats.org/package/2006/relationships';
    const CONTENT_TYPES_NS = 'http://schemas.openxmlformats.org/package/2006/content-types';
    const CORE_PROPERTIES_NS = 'http://schemas.openxmlformats.org/package/2006/metadata/core-properties';
    const DUBLIN_CORE_NS = 'http://purl.org/dc/elements/1.1/';
    const DUBLIN_CORE_TERMS_NS = 'http://purl.org/dc/terms/';

    const EXTENDED_PROPERTIES_REL = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties';
    const CORE_PROPERTIES_REL = 'http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties';
    const OFFICE_DOCUMENT_ROOT_REL = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument';

    const SLIDE_DOCUMENT_ROOT_REL = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/slide';

    const WORD_DOCUMENT_CONTENT_TYPE = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml';
    const EXCEL_WORKBOOK_CONTENT_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml';
    const POWERPOINT_WORKBOOK_CONTENT_TYPE = 'application/vnd.openxmlformats-officedocument.presentationml.presentation.main+xml';

    const ROOT_PARTNAME = '/';

    public function getCreator()
    {
        return $this->creator;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCreationDate()
    {
        return $this->date_created;
    }

    public function getLastModificationDate()
    {
        return $this->date_modified;
    }

    public function getLastWriter()
    {
        return $this->last_writer;
    }

    public function getRevision()
    {
        return $this->revision;
    }

    public function __construct($fileName)
    {
        $this->zip = new ZipArchive();
        if ($this->zip->open($fileName) !== true) {
            throw new OpenXMLFatalException('Impossible d\'ouvrir le fichier '.$fileName, __METHOD__);
        }
        try {
            $this->readCoreProperties();
        } catch (OpenXMLException $e) {
        }
    }

    public function __destruct()
    {
        $this->zip->close();
    }

    public static function getRelationTarget(ZipArchive $zip, $sourcePartName, $relationURI)
    {
        // Construction du nom du fichier de relations selon la norme OPC (Open Package Conventions)
        $relation_file = dirname($sourcePartName).'_rels/'.basename($sourcePartName).'.rels';
        // Normalisation du nom de fichier de relations : les \ renvoyés par dirname() si l'on travaille sur une plateforme Windows sont remplacés par des /
        $relation_file = str_replace('\\', '/', $relation_file);
        // On retire le / de tête, l'accès à un item zippé est toujours relatif à la racine de l'archive
        if ($relation_file[0] == '/') {
            $relation_file = substr($relation_file, 1);
        }
        $relations_xml = self::xml_getPart($zip, $relation_file);
        if (empty($relations_xml)) {
            throw new OpenXMLFatalException('Impossible de parser le fichier des relations', __METHOD__);
        }
        $relations_xml->registerXPathNamespace('rns', self::RELATIONSHIPS_NS);
        $relation_targets = $relations_xml->xpath("/rns:Relationships/rns:Relationship[@Type='$relationURI']/@Target");
        if (empty($relation_targets) or count($relation_targets) == 0) {
            throw new OpenXMLException('Impossible de localiser la cible de la relation '.$relationURI, __METHOD__);
        }

        return $relation_targets[0];
    }

    public static function getMainPartContentType(ZipArchive $zip)
    {
        $main_part = self::getRelationTarget($zip, self::ROOT_PARTNAME, self::OFFICE_DOCUMENT_ROOT_REL);
        $type = self::getContentType($zip, $main_part);

        return $type;
    }

    public static function getContentType(ZipArchive $zip, $partName)
    {
        $contents_xml = self::xml_getPart($zip, '[Content_Types].xml');
        $contents_xml->registerXPathNamespace('cns', self::CONTENT_TYPES_NS);
        $types = $contents_xml->xpath("/cns:Types/cns:Override[@PartName = '/$partName']/@ContentType");
        if (empty($types) or count($types) == 0) {
            // On n'a pas trouvé d'élément Override correspondant à la partie recherchée
            // On recherche donc parmi les types par défaut, celui correspondant à l'extension de la partie
            $extension = substr(strrchr($partName, '.'), 1);
            $types = $contents_xml->xpath("/cns:Types/cns:Default[@Extension = '$extension']/@ContentType");
            if (empty($types) or count($types) == 0) {
                throw new OpenXMLException('Impossible de déterminer le type de contenu de '.$partName, __METHOD__);
            } else {
                return $types[0];
            }
        } else {
            return $types[0];
        }
    }

    public static function getContent(ZipArchive $zip, $type)
    {
        $return = "";
        switch ($type) {
            case OpenXMLDocument::WORD_DOCUMENT_CONTENT_TYPE : {
                $contents[] = $zip->getFromName('word/document.xml');
                break;
            }
            case OpenXMLDocument::EXCEL_WORKBOOK_CONTENT_TYPE : {
                $return = $zip->getFromName('xl/sharedStrings.xml');
                break;
            }
            case OpenXMLDocument::POWERPOINT_WORKBOOK_CONTENT_TYPE : {
                $relations = simplexml_load_string($zip->getFromName("ppt/_rels/presentation.xml.rels"));
                foreach ($relations->Relationship as $rel) {
                    if ($rel["Type"] == self::SLIDE_DOCUMENT_ROOT_REL) {
                        $contents[] = $zip->getFromName("ppt/".dirname($rel["Target"])."/".basename($rel["Target"]));
                    }
                }
                break;
            }
        }
        if ($contents) {
            $return = implode(' ', $contents);
        }

        return $return;
    }

    public static function xml_getPart(ZipArchive $zip, $partName, $ns = null)
    {
        $part_content = $zip->getFromName($partName);
        if (empty($part_content)) {
            throw new OpenXMLFatalException('Impossible de lire la partie '.$partName, __METHOD__);
        }
        $xml = simplexml_load_string($part_content, null, null, $ns, false);
        if (empty($xml)) {
            throw new OpenXMLFatalException('Impossible de parser la partie '.$partName, __METHOD__);
        }

        return $xml;
    }

    protected function xml_getExtendedProperties()
    {
        $extendedPropertiesPartName = self::getRelationTarget($this->zip, self::ROOT_PARTNAME, self::EXTENDED_PROPERTIES_REL);

        return self::xml_getPart($this->zip, $extendedPropertiesPartName);
    }

    abstract public function readExtendedProperties();

    abstract public function readContent();

    private function readCoreProperties()
    {
        $corePropertiesPartName = self::getRelationTarget($this->zip, self::ROOT_PARTNAME, self::CORE_PROPERTIES_REL);
        $document = self::xml_getPart($this->zip, $corePropertiesPartName, self::CORE_PROPERTIES_NS);
        $this->keywords = $document->keywords;
        $this->last_writer = $document->lastModifiedBy;
        $this->revision = $document->revision;
        $dc_elements = $document->children(self::DUBLIN_CORE_NS);
        $this->creator = $dc_elements->creator;
        $dc_elements = $document->children(self::DUBLIN_CORE_TERMS_NS);
        $this->date_modified = $dc_elements->modified;
        $this->date_created = $dc_elements->created;
    }

    abstract public function getHTMLPreview();

    protected function getXSLTTransformedDocument($stylesheetName)
    {
        $xsl = new XSLTProcessor();

        $stylesheet = new DOMDocument();
        if ($stylesheet->load($stylesheetName) == false) {
            throw new OpenXMLFatalException('Impossible de charger la feuille de style '.$stylesheet, __METHOD__);
        }
        $xsl->importStyleSheet($stylesheet);

        $mainPartName = self::getRelationTarget($this->zip, self::ROOT_PARTNAME, self::OFFICE_DOCUMENT_ROOT_REL);
        $mainPartContent = $this->zip->getFromName($mainPartName);
        if (empty($mainPartContent)) {
            throw new OpenXMLFatalException('Impossible de lire la partie '.$partName, __METHOD__);
        }
        $document = new DOMDocument();
        if ($document->loadXML($mainPartContent) == false) {
            throw new OpenXMLFatalException('Impossible de charger la partie principale du document', __METHOD__);
        }

        return $xsl->transformToXML($document);
    }
}

class WordDocument extends OpenXMLDocument
{
    private $application;
    private $nb_paragraphs;
    private $nb_characters;
    private $nb_characters_with_spaces;
    private $nb_pages;
    private $nb_words;

    public function __construct($fileName)
    {
        parent::__construct($fileName);
        try {
            $this->readExtendedProperties();
        } catch (OpenXMLException $e) {
        }
    }

    public function readExtendedProperties()
    {
        $properties = array();
        $document = parent::xml_getExtendedProperties();
        $this->application = $document->Application;
        $this->nb_paragraphs = $document->Paragraphs;
        $this->nb_characters = $document->Characters;
        $this->nb_characters_with_spaces = $document->CharactersWithSpaces;
        $this->nb_pages = $document->Pages;
        $this->nb_words = $document->Words;
    }

    public function getApplication()
    {
        return $this->application;
    }

    public function getNbOfParagraphs()
    {
        return $this->nb_paragraphs;
    }

    public function getNbOfCharacters()
    {
        return $this->nb_words;
    }

    public function getNbOfCharactersWithSpaces()
    {
        return $this->nb_characters_with_spaces;
    }

    public function getNbOfPages()
    {
        return $this->nb_pages;
    }

    public function getNbOfWords()
    {
        return $this->nb_words;
    }

    public function getHTMLPreview()
    {
        return parent::getXSLTTransformedDocument('preview-word.xslt');
    }

    public function readContent()
    {
    }
}

class ExcelWorkbook extends OpenXMLDocument
{
    public function __construct($zip)
    {
        parent::__construct($zip);
        try {
            $this->readExtendedProperties();
        } catch (OpenXMLException $e) {
        }
    }

    public function readExtendedProperties()
    {
        $properties = array();
        $document = parent::xml_getExtendedProperties();
        $this->application = $document->Application;
    }

    public function getApplication()
    {
        return $this->application;
    }

    public function getHTMLPreview()
    {
        echo '<p><i>Pas de preview pour les classeurs Excel.</i></p>';
    }

    public function readContent()
    {
    }
}

class PowerpointDocument extends OpenXMLDocument
{
    public function __construct($zip)
    {
        parent::__construct($zip);
        try {
            $this->readExtendedProperties();
        } catch (OpenXMLException $e) {
        }
    }

    public function readExtendedProperties()
    {
        $properties = array();
        $document = parent::xml_getExtendedProperties();
        $this->application = $document->Application;
    }

    public function getApplication()
    {
        return $this->application;
    }

    public function getHTMLPreview()
    {
        echo '<p><i>Pas de preview pour les classeurs Excel.</i></p>';
    }

    public function readContent()
    {
    }
}

//
//
// ppt/presentation.xml
