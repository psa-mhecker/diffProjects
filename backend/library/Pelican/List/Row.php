<?php
/**
 * Cette classe est utilisée pour créer les définitions des colonnes des tables
 * générées par la classe List.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 * @since 15/05/2003
 */
class Pelican_List_Row
{
    /**
     * Libellé de l'entête de la colonne.
     *
     * @access public
     *
     * @var string
     */
    public $sHeaderLabel;

    /**
     * Champ de valeur associé à la colonne.
     *
     * @access public
     *
     * @var string
     */
    public $sColumnField;

    /**
     * Largeur (relative) de la colonne.
     *
     * @access public
     *
     * @var string
     */
    public $sColumnWidth;

    /**
     * Attribut ALIGN de la colonne.
     *
     * @access public
     *
     * @var string
     */
    public $sColumnAlign;

    /**
     * Formattage de la colonne.
     *
     * @access public
     *
     * @var string
     */
    public $sColumnFormat;

    /**
     * Attribut CLASS de la colonne.
     *
     * @access public
     *
     * @var string
     */
    public $sHeaderClass;

    /**
     * Champ servant à effectuer le tri sur la colonne.
     *
     * @access public
     *
     * @var string
     */
    public $sColumnOrderField;

    /**
     * Type de la colonne "image", "input", "multi", "combo".
     *
     * @access public
     *
     * @var string
     */
    public $sColumnType;

    /**
     * Tableau d'attributs des éléments de la colonne.
     *
     * @access public
     *
     * @var mixed
     */
    public $aColumnAttributes;

    /**
     * Affichage ou non d'une somme sur les valeurs (si elles sont numériques) de la
     * colonne
     * si $bColumnn vaut -1, on ne prend pas en compte les valeurs negatives.
     *
     * @access public
     *
     * @var bool
     */
    public $bColumnSum;

    /**
     * Champ contenant un texte à afficher en bulle d'aide.
     *
     * @access public
     *
     * @var bool
     */
    public $sTooltip;

    /**
     * Tableau d'expressions du type "CHAMP=VALEUR" ou "CHAMP!=VALEUR" qui doivent
     * être remplies (si le paramètre est défini) pour afficher un contenu dans la
     * cellule.
     *
     * @access public
     *
     * @var mixed
     */
    public $aShow;

    /**
     * Colspan de l'entête. Une colonne avec un colspan > 1 ne sera pas prise ne
     * compte dans l'affichage de la liste (= C'est une entête pour les colonnes
     * en-dessous).
     *
     * @access public
     *
     * @var int
     */
    public $iColSpan;

    /**
     * Rowspan de l'entête.
     *
     * @access public
     *
     * @var int
     */
    public $iRowSpan;

    /**
     * Ordre d'affichage de la colonne.
     *
     * @access public
     *
     * @var int
     */
    public $iNumColumn;

    /**
     * Action du click sur la valeur d'une cellule.
     *
     * @access public
     *
     * @var string
     */
    public $onClick;

    /**
     * Constructeur : définition des paramètres des colonnes du tableau.
     *
     * @access public
     *
     * @param string $header     Libellé de la colonne
     * @param string $field      Champ du tableau de valeurs à utiliser pour le
     *                           remplissage des cellules
     * @param string $width      (option) Largeur (relative) de la colonne
     * @param string $align      (option) Attribut ALIGN de chaque cellule
     * @param string $format     (option) Attribut de formattage de la valeur de la
     *                           cellule (cf setFormat) : effectue des formattage pour
     *                           "%","j->h","h->j","email","number", sinon est concaténé avec la valeur de la
     *                           cellule (exemple : " ")
     * @param string $class      (option) Attribut CLASS de l'entête de colonne
     * @param string $order      (option) Champ du tableau sur lequel effectuer un tri
     *                           (s'il n'est pas défini, le tri ne sera pas actif sur cette colonne)
     * @param string $type       (option) = "text" Type de contrôle de formulaire
     * @param mixed  $attributes (option) Attributs de colonne
     * @param mixed  $aShow      (option) Tableau d'expressions du type "CHAMP=VALEUR" ou
     *                           "CHAMP!=VALEUR" qui doivent être remplies (si le paramètre est défini) pour
     *                           afficher un contenu dans la cellule
     * @param bool   $sum        (option) Affichage ou non en bas de tableau de la somme de la
     *                           colonne si les données sont numériques
     * @param string $tooltip    (option) Champ contenant un texte à afficher en bulle
     *                           d'aide
     * @param int    $iColSpan   (option) Colspan de l'entête. Une colonne avec un colspan
     *                           > 1 ne sera pas prise ne compte dans l'affichage de la liste (= C'est une
     *                           entête pour les colonnes en-dessous).
     * @param int    $iRowSpan   (option) Rowspan de l'entête
     * @param string $iNum       (option) __DESC__
     * @param string $onClick    (option) __DESC__
     *
     * @return Pelican_List_Row
     */
    public function Pelican_List_Row($header, $field, $width = "", $align = "", $format = "", $class = "", $order = "", $type = "text", $attributes = "", $aShow = "", $sum = false, $tooltip = "", $iColSpan = 1, $iRowSpan = 1, $iNum = "", $onClick = "")
    {
        $this->sHeaderLabel = $header;
        $this->sColumnField = $field;
        $this->sColumnWidth = $width;
        $this->sColumnAlign = $align;
        $this->sColumnFormat = $format;
        $this->sHeaderClass = $class;
        $this->sColumnOrderField = $order;
        $this->sColumnType = $type;
        $this->aColumnAttributes = $attributes;
        $this->aShow = $aShow;
        $this->bColumnSum = $sum;
        $this->sTooltip = $tooltip;
        $this->iColSpan = $iColSpan;
        $this->iRowSpan = $iRowSpan;
        $this->onClick = $onClick;
        if (isset($iNum)) {
            $this->iNumColumn = $iNum;
        }
    }
}
