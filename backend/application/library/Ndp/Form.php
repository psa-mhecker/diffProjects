<?php
/**
 * Gestion des formulaires de saisie avec contrôles de saisie centralisée.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */
require_once 'Pelican/Form.php';
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['TEMPLATE_CACHE_ROOT'].'/Service/Streamlike.php';
include_once Pelican::$config['APPLICATION_VIEW_HELPERS'].'/Tooltip.php';
use Itkg\Utils\FormatHelper;

class Ndp_Form extends Pelican_Form
{
    const BACKEND_PICTOS = '/design/backend/images/silk/';
    const VERTICAL = 'vertical';
    const OTHER_NUMERIC = 'otherNumeric';
    const JS = '/js/Ndp/Form/';

    // niveau de profondeur du pour la creation d'un HMVC
    private static $level = -1;

    /**
     * un acces à la vue courante afin d'atteindre le header.
     */
    protected $currentView;

    /**
     * Affiche un commentaire en gras dans le formulaire.
     *
     *
     * @param string $comment commentaire a afficher
     * @param array  $options
     *                        Array   infoBulle ( 'isIcon' => true, 'message' => t($message))
     *                        Array   labels ( key => label, value => picto)
     *                        boolean noBold => false
     *                        string  idForLabel
     *
     * @return string
     */
    public function createComment($comment, $options = [])
    {
        $colSpan = 2;
        $addonInfoBulle = '';
        $moreTd = '';
        $infoBulle = $options['infoBulle'];
        if (!isset($options['noBold'])) {
            $options['noBold'] = false;
        }
        if (is_array($infoBulle) && !empty($infoBulle['isIcon'])) {
            $addonInfoBulle = Backoffice_Tooltip_Helper::help($infoBulle['message']);
        }
        if (!empty($options['labels'])) {
            $colSpan = 1;
            $moreTd = self::testAndSetPictoFoxCreateHeader($options['labels'], $colSpan);
        }
        $pattern = '<b>%s %s</b>';
        if (!empty($options['noBold'])) {
            $pattern = '%s %s';
        }
        $lib = sprintf($pattern, $comment, $addonInfoBulle);
        $idForLabel = '';
        if (isset($options['idForLabel'])) {
            $idForLabel = "id='".$options['idForLabel']."'";
        }
        $return = '<tr><td colspan="'.$colSpan.'" class="formlib" '.$idForLabel.'>'.$lib.'</td>'.$moreTd.'</tr>';
        if (self::VERTICAL === $this->_sFormDisposition) {
            $return = '<tr><td>'.$lib.'</td>'.$moreTd.'</tr>';
        }

        return $this->output($return);
    }

    /**
     * Affiche un commentaire en gras dans le formulaire.
     *
     *
     * @param string $header  Le header a afficher
     * @param int    $level   le niveau du header
     * @param array  $options
     *                        Array infoBulle ( 'isIcon' => true, 'message' => t($message))
     *                        Array labels ( key => label, value => picto)
     *
     * @return string
     */
    public function createHeader($header, $level = 3, $options = [])
    {
        $colSpan = 2;
        $addonInfoBulle = '';
        $moreTd = '';
        $infoBulle = $options['infoBulle'];
        if (is_array($infoBulle) && !empty($infoBulle['isIcon'])) {
            $addonInfoBulle = Backoffice_Tooltip_Helper::help($infoBulle['message']);
        }
        if (!empty($options['labels'])) {
            $colSpan = 1;
            $moreTd = self::testAndSetPictoFoxCreateHeader($options['labels'], $colSpan);
        }
        $html = "<h$level>$header $addonInfoBulle</h$level>";
        $return = '<tr><td colspan="'.$colSpan.'">'.$html.'</td>'.$moreTd.'</tr>';
        if (self::VERTICAL === $this->_sFormDisposition) {
            $return = '<tr><td>'.$html.'</td>'.$moreTd.'</tr>';
        }

        return $this->output($return);
    }

    /**
     * Génère un checkbox pour définir la stratégie d'affichage Web / Mobile
     * WARNING si la methode n'est pas appelee, par defaut les champs ZONE_WEB et ZONE_MOBILE sont a true en BDD.
     *
     * exemple d'argument :
     *  $aField = array(
     *      'MOBILE' => array(
     *      'DISPLAY' => false,
     *      'NAME' => $controller->multi.'ZONE_MOBILE',
     *      'VALUE' => '0'
     *      ),
     *      'WEB' => array(
     *      'DISPLAY' => true,
     *      'NAME' => $controller->multi.'ZONE_WEB',
     *      'VALUE' => $fieldValueWeb
     *      )
     *  );
     *
     * @param array $aField
     *
     * @return string
     */
    public function createCheckboxAffichage($aField = array())
    {
        $return = '';

        if ($aField['WEB']['NAME']) {
            if ($aField['WEB']['DISPLAY'] === true) {
                $return .= $this->createCheckBoxFromList($aField['WEB']['NAME'], t('AFFICHAGE_WEB'), array(1 => ''), $aField['WEB']['VALUE'], false, $aField['WEB']['READONLY'], 'h', false, $aField['WEB']['FIELDDISABLED']);
            } else {
                $return .= $this->createHidden($aField['WEB']['NAME'], $aField['WEB']['VALUE']);
            }
        }
        if ($aField['MOBILE']['NAME']) {
            if ($aField['MOBILE']['DISPLAY'] === true) {
                $return .= $this->createCheckBoxFromList($aField['MOBILE']['NAME'], t('AFFICHAGE_MOB'), array(1 => ''), $aField['MOBILE']['VALUE'], false, $aField['MOBILE']['READONLY'], 'h', false, $aField['MOBILE']['FIELDDISABLED']);
            } else {
                $return .= $this->createHidden($aField['MOBILE']['NAME'], $aField['MOBILE']['VALUE']);
            }
        }

        return $return;
    }

    /**
     * internal function to target field for CTA.
     *
     * @param string $multi
     * @param string $value
     * @param bool $readOnly
     * @param mixed $targets
     * @param bool $needed
     *
     * @return string
     */
    public function createCtaTarget($multi, $value, $readOnly, $targets = null, $needed = true)
    {
        $return = '';

        if (null === $targets) {
            $targets = array(
                '_self' => t('NDP_SELF'),
                '_blank' => t('NDP_BLANK'),
                '_popin' => t('NDP_POPIN'),
            );
        }

        if (is_string($targets)) {
            $return .= $this->createHidden($multi.'[TARGET]', $targets);
        }
        if (is_array($targets) && !empty($targets)) {
            $options = ['infoBull' => ['isIcon' => true, 'message' => Ndp_Cta::getToolTipMessage($targets)]];
            $return .= $this->createRadioFromList($multi.'[TARGET]', t('NDP_MODE_OUVERTURE'), $targets, $value, $needed, $readOnly, 'h', null, null, null, $options);
        }

        return $return;
    }

    /**
     * internal function to style field for CTA.
     *
     * @param string $multi
     * @param string $value
     * @param bool $readOnly
     * @param mixed $styles
     * @param bool $needed
     * @param string $type
     * 
     * @return string
     */
    public function createCtaStyle($multi, $value, $readOnly, $styles = null, $needed = true, $type = '')
    {
        $return = '';

        if (null === $styles) {
            $styles = array(
                'style_niveau1' => t('NDP_STYLE_NIVEAU1'),
                'style_niveau2' => t('NDP_STYLE_NIVEAU2'),
                'style_niveau3' => t('NDP_STYLE_NIVEAU3'),
                'style_niveau4' => t('NDP_STYLE_NIVEAU4'),
                'style_niveau5' => t('NDP_STYLE_NIVEAU5'),
            );
        }
        if (is_string($styles)) {
            $return .= $this->createHidden($multi.'[STYLE]', $styles, true);
        }
        if (is_array($styles) && !empty($styles)) {
            $js = Cms_Page_Ndp::addJsContainerComboLD($multi.$type);
            $options = ['infoBull' => ['isIcon' => true, 'message' => t('NDP_CHOOSE_CTA')]];
            $return .= $this->createComboFromList($multi.'[STYLE]', t('STYLE'), $styles, $value, $needed, $readOnly, 1, false, '', false, false, $js, $options);
        }

        return $return;
    }

    /**
     * create a group of field prefixed with $multi[NEW_CTA] to manage creation of CTA.
     *
     * @param string $multi the pelican form prefix for the field group
     * @param array $values array of values
     *                         <code>
     *                         $values = array( 'TITLE'=>'','ACTION'=>'','TARGET'=>'','STYLE'=>'');
     *                         </code>
     * @param bool $readOnly only display values not fields
     * @param mixed $targets list of targets, null use default values,string force value, empty array disable TARGET field
     *                         <code>
     *                         $values = array( 'self'=>'', 'blank=>'');
     *                         </code>
     * @param mixed $styles list of style, null use default values,string force value, empty array disable STYLE field
     *                         <code>
     *                         $values = array( 'dark_blue'=>'', 'light_blue=>'', grey=>'');
     *                         </code>
     *
     * @param bool $needed
     * @return string
     */
    public function createCtaRef($multi, $values, $readOnly = false, $targets = null, $styles = null, $needed = true)
    {
        $sql = 'select ID,TITLE_BO from #pref#_cta WHERE IS_REF=1 AND SITE_ID='.$_SESSION[APP]['SITE_ID'].' AND LANGUE_ID='.$_SESSION[APP]['LANGUE_ID'];
        $return = $this->createComboFromSql(null, $multi.'[SELECT_CTA][CTA_ID]', t('CTA'), $sql, $values['CTA_ID'], $needed, $readOnly, 1, false);
        $return .= $this->createCtaTarget($multi.'[SELECT_CTA]', $values['TARGET'], $readOnly, $targets, $needed);
        $return .= $this->createCtaStyle($multi.'[SELECT_CTA]', $values['STYLE'], $readOnly, $styles, $needed);

        return $return;
    }

    /**
     * create a group of field prefixed with $multi[SELECT_CTA] to select CTA form the referential.
     *
     * @param string $multi    the pelican form prefix for the field group
     * @param array  $values   array of values
     *                         <code>
     *                         $values = array( 'ID'=>'','TARGET'=>'','STYLE'=>'');
     *                         </code>
     * @param bool   $readOnly only display values not fields
     * @param mixed  $targets  list of targets, null use default values,string force value,  empty array disable TARGET field
     *                         <code>
     *                         $values = array( 'self'=>'', 'blank=>'');
     *                         </code>
     * @param mixed  $styles   list of style, null use default values,string force value, empty array disable STYLE field
     *                         <code>
     *                         $values = array( 'dark_blue'=>'', 'light_blue=>'', grey=>'');
     *                         </code>
     *
     * @return string
     */
    public function createCtaNotRef($multi, $values, $readOnly = false, $targets = null, $styles = null)
    {
        $return = '';
        if (isset($values['CTA_ID'])) {
            $return .= $this->createHidden($multi.'[NEW_CTA][CTA_ID]', $values['CTA_ID']);
        }
        $return .= $this->createInput($multi.'[NEW_CTA][TITLE]', t('LABEL'), 255, '', false, $values['TITLE'], $readOnly, 100);
        $return .= $this->createInput($multi.'[NEW_CTA][ACTION]', t('CTA_ACTION'), 255, 'internallink', false, $values['ACTION'], $readOnly, 100);
        $return .= $this->createCtaTarget($multi.'[NEW_CTA]', $values['TARGET'], $readOnly, $targets);
        $return .= $this->createCtaStyle($multi.'[NEW_CTA]', $values['STYLE'], $readOnly, $styles);

        return $this->output($return);
    }

    /**
     * Génère un champ de sélection de Contenu editorial.
     *
     * 2 modes possibles : sélection simple (iSize =1) ou sélection multiple
     * <code>
     * $aSelectedValues = array("1"=>"test1","2"=>"test2");
     * $oForm->createContentFromList("Contenu", "Contenu", $aSelectedValues, true,
     * false, 5);
     * </code>
     *
     *
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param mixed $aSelectedValues (option) Tableau des valeurs sélectionnées
     *                                  (id=>lib)
     * @param bool $bRequired (option) Champ obligatoire : false par défaut
     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
     *                                  (créé un input hidden) : false par défaut
     * @param string $iSize (option) 
     * @param int|string $iWidth (option) Largeur du contrôle : 200 par défaut
     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
     *                                  : false par défaut
     * @param bool $bSingle (option) Génère un nom de champ sans[] : false par
     *                                  défaut
     * @param string $sContentType (option) Appliquer un filtre sur le type de contenu
     *                                  passé en paramètre (ce paramètre peut être un ensemble d'id séparés par
     *                                  des )
     * @param bool $bEnableOrder (option) Affichage des fonctions de tri de la liste :
     *                                  false par défaut
     * @param string $siteExterne (option) 
     * @param string $contentCode2 (option) Permet de filtrer les contenu par la valeur de CONTENT_CODE2
     * @return string
     */
    public function createContentFromList($strName, $strLib, $aSelectedValues = '', $bRequired = false, $bReadOnly = false, $iSize = '5', $iWidth = 200, $bFormOnly = false, $bSingle = false, $sContentType = '', $bEnableOrder = false, $siteExterne = '', $contentCode2 = '')
    {
        $this->_aIncludes['list'] = $this->_aIncludes['list'] || !$bReadOnly;
        $strTmp = '';
        if (empty($aSelectedValues)) {
            $aSelectedValues = array();
        }
        if (!is_array($aSelectedValues)) {
            $aSelectedValues = array($aSelectedValues);
        }

        if ($bReadOnly) {
            while ($ligne = each($aSelectedValues)) {
                $this->countInputName($strName.($bSingle ? '' : '[]'));
                $strTmp .= '<input type="hidden"  name="'.$strName.($bSingle ? '' : '[]').'" value="'.str_replace('"', '&quot;', $ligne['key']).'" />';
            }
            // Génération du couple libellé/champ
            foreach ($aSelectedValues as $key => $ligne) {
                $strTmp .= ''.$ligne.Pelican_Html::br();
            }
            if (!$bFormOnly) {
                $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, 'top', '', $this->_sFormDisposition);
            }
        } else {
            // Valeurs choisies
            $this->countInputName($strName);
            $strTmp .= '<table cellpadding="0" cellspacing="0" border="0" align="left" summary=\"Contenu\">';
            $strTmp .= '<tr>';
            $strTmp .= '<td>';
            $aOption = array();
            foreach ($aSelectedValues as $key => $value) {
                if ($value) {
                    $aOption[] = Pelican_Html::option(array('value' => $key), $value);
                }
            }
            $strTmp .= Pelican_Html::select(array('id' => $strName, 'name' => $strName.($bSingle ? '' : '[]'),
                'size' => (($iSize < 4 && $bEnableOrder) ? 4 : $iSize), 'multiple' => 'multiple',
                'ondblclick' => 'assocDel(this, false);', 'style' => 'width:'.$iWidth.'px;', ), implode('', $aOption));
            if ($bEnableOrder) {
                $strTmp .= '<td class="'.$this->strStyleVal.'">';
                $strTmp .= '<img src="'.$this->_sLibPath.$this->_sLibForm.'/images/top.gif" width="13" height="15" ';
                $strTmp .= 'onClick="MoveTop(document.'.$this->sFormName.".elements['".$strName.($bSingle ? '' : '[]')."']);\"\">".Pelican_Html::br();
                $strTmp .= '<img src="'.$this->_sLibPath.$this->_sLibForm.'/images/up.gif" width="13" height="15" ';
                $strTmp .= 'onClick="MoveUp(document.'.$this->sFormName.".elements['".$strName.($bSingle ? '' : '[]')."']);\"\">".Pelican_Html::br();
                $strTmp .= '<img src="'.$this->_sLibPath.$this->_sLibForm.'/images/down.gif" width="13" height="15" ';
                $strTmp .= 'onClick="MoveDown(document.'.$this->sFormName.".elements['".$strName.($bSingle ? '' : '[]')."']);\"\">".Pelican_Html::br();
                $strTmp .= '<img src="'.$this->_sLibPath.$this->_sLibForm.'/images/bottom.gif" width="13" height="15" ';
                $strTmp .= ' onClick="MoveBottom(document.'.$this->sFormName.".elements['".$strName.($bSingle ? '' : '[]')."']);\"\">";
                $strTmp .= '</td>';
                $this->_aIncludes['ordered_list'] = true;
            }
            $strTmp .= '</tr></table>';
            // Recherche activée (par champ input ou par combo ($arForeignKey doit être renseigné)
            $this->_aIncludes['popup'] = true;
            $action = "\"searchContent('".$this->_sLibPath.$this->_sLibForm."/', 'document.".$this->sFormName."', '".$strName."', '".(((int) $iSize == 1) ? 'single' : 'multi')."', '".$sContentType."','".$siteExterne."','".base64_encode(session_id())."');\"";
            if (!empty($contentCode2)) {
                $action = "\"searchContentFiltered('".$this->_sLibPath.$this->_sLibForm."/', 'document.".$this->sFormName."', '".$strName."', '".(((int) $iSize == 1) ? 'single' : 'multi')."', '".$sContentType."','".$siteExterne."','".base64_encode(session_id())."','','".$contentCode2."');\"";
            }
            $strTmp .= '<input type="button" class="button" name="bSearch'.$strName.'" value="'.t('FORM_BUTTON_SEARCH').'" onclick='.$action.' />';
            $strTmp .= Pelican_Html::nbsp().'<input type="button" class="button" value="'.t('FORM_BUTTON_FILE_DELETE').'"';
            $strTmp .= " onclick=\"assocDel(document.getElementById('".$strName."'), false";
            if ($bEnableOrder) {
                $strTmp .= ', true';
            }
            $strTmp .= ');" >';
            if (!$bFormOnly) {
                $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, 'top', '', $this->_sFormDisposition);
            }
            // Lien vers popup de gestion de la table de référence
            // Génération de la fonction js de vérification.
            if ($bRequired) {
                $this->_sJS .= 'o = obj.elements["'.$strName.($bSingle ? '' : '[]').'"];'."\n";
                $this->_sJS .= 'if ( o.length == 0 && !$("#'.$strName.($bSingle ? '' : '[]').'").parents("tbody").hasClass("isNotRequired") ) {'."\n";
                $this->_sJS .= 'alert("'.t('FORM_MSG_VALUE_CHOOSE').' \\"'.strip_tags(str_replace('"', '\\"', $strLib)).'\\"");'."\n";
                $this->_sJS .= "fwFocus(o);\n";
                $this->_sJS .= "return false;\n";
                $this->_sJS .= "}\n";
            }
            $this->_sJS .= 'selectAll(document.'.$this->sFormName.'.elements["'.$strName.($bSingle ? '' : '[]').'"]);'."\n";
        }

        return $this->output($strTmp);
    }

    /**
     * Génère un champ input de type Text.
     *
     *
     * @example Création d'un champ input présentant le nom d'un utilisateur :
     *
     * @param string $strName    Nom du champ
     * @param string $strLib     Libellé du champ
     * @param string $iMaxLength (option) Nb de caractères maximum : 255 par défaut
     * @param string $strControl (option) Type de contrôle js utilisé : numerique ou
     *                           number, float, flottant, real ou reel, telephone, mail, date
     * @param bool   $bRequired  (option) Champ obligatoire : false par défaut
     * @param string $strValue   (option) Valeur du champ
     * @param bool   $bReadOnly  (option) Affiche uniquement la valeur et pas le champ
     *                           (créé un input hidden) : false par défaut
     * @param string $iSize      (option) Taille d'affichage du champ : 10 par défaut
     * @param bool   $bFormOnly  (option) Génération du champ uniquement, sans libellé
     *                           : false par défaut
     * @param string $strEvent   (option) Handler d'événements sur le champ : "" par
     *                           défaut
     * @param string $strType    (option) Type de l'input ("text" par défaut)
     * @param array  $aSuggest   (option) 
     * @param bool   $multiple   (option) 
     * @param string $infoBull   (option) champs permettant d'afficher une info-bulle
     *                           De type Array si l'info-bulle est une icone :
     *                           'isIcon' => Boolean
     *                           'message'  => String
     * @param array  $options    (option) permet d'ajouter du texte ou autre après l'input
     *                           'message'  => String,
     *                           'data-template' => String
     *
     * @return string
     */
    public function createInput($strName, $strLib, $iMaxLength = '255', $strControl = '', $bRequired = false, $strValue = '', $bReadOnly = false, $iSize = '10', $bFormOnly = false, $strEvent = '', $strType = 'text', $aSuggest = array(), $multiple = false, $infoBull = '', $options = array())
    {
        /* initialisation */
        $strValue = str_replace('"', '&quot;', $strValue);
        if (empty($iSize) || $iSize >= 71) {
            $iSize = 70;
        }
        $params = [];
        if (isset($options['attributes']) && is_array($options['attributes'])) {
            $params = $options['attributes'];
        }
        $iconInfoBull = false;
        if (is_array($infoBull)) {
            if (!empty($infoBull['isIcon'])) {
                $iconInfoBull = $infoBull['message'];
                $infoBull = false;
            }
        }

        if (!is_array($options)) {
            $tmp = $options;
            $options = array();
            $options['message'] = $tmp;
        }

        $add = '';
        $strMessage = '';
        if ($bReadOnly) {
            $val = $strValue;
            if (!$bFormOnly && $strValue) {
                if ($strType == 'file') {
                    $val = Pelican_Html::button(array('style' => 'cursor:pointer;',
                        'onclick' => "window.open('".$strValue."');", ), 'Télécharger');
                } else {
                    switch ($strControl) {
                        case 'color':
                            $add = Pelican_Html::nbsp().Pelican_Html::span(array(
                                    'id' => 'color'.$strName,
                                    'style' => 'border: 1px solid;background-color: '.$strValue.';', ), Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp());
                            break;
                        case 'mail':

                            $add = Pelican_Html::nbsp().Pelican_Html::a(array('href' => 'mailto:'.$strValue), Pelican_Html::img(array(
                                    'src' => $this->_sLibPath.$this->_sLibForm.'/images/mail.gif',
                                    'alt' => '', 'border' => '0', 'align' => 'bottom', )));
                            break;
                    }
                }
            }
            $add .= $this->createHidden($strName, str_replace('"', '&quot;', $strValue));
            $strTmp = $val.$add;
        } else {
            if (!$this->_sDefaultFocus) {
                $this->_sDefaultFocus = $strName;
            }
            $strTmp = '';
            if (!empty($params['disabled'])) {
                $strTmp .=  $this->createHidden($strName, str_replace('"', '&quot;', $strValue));
                $strName .= '_disabled';
            }

            /* Ajouts */
            if (!empty($aSuggest)) {
                $this->_aIncludes['suggest'] = true;
                $params['autocomplete'] = 'off';
                if (!is_array($aSuggest)) {
                    $aSuggest = array($aSuggest);
                }
                $this->suggest[$strName] = $aSuggest;
                $add = Pelican_Html::nbsp().Pelican_Html::img(array(
                        'src' => $this->_sLibPath.$this->_sLibForm.'/images/combo.gif',
                        'alt' => '', 'border' => '0', 'onclick' => "showSuggest('".$strName."');",
                        'class' => 'combo_suggest', ));
            }
            $class = 'text';
            switch ($strControl) {
                case 'numerique':
                case 'number':
                case 'positive-number':
                case 'flottant':
                case 'float':
                case 'real':
                case 'reel':
                    $params['style'] = 'text-align:right;';
                    break;
                case 'mail':
                    if ($strValue) {
                        $add .= Pelican_Html_Form::imgComment($this->_sLibPath.$this->_sLibForm.'/images/mail.gif', 'mailto:'.$strValue);
                    }
                    break;
                case 'internallink':
                    $this->_aIncludes['popup'] = true;
                    $add .= Pelican_Html_Form::imgComment($this->_sLibPath.$this->_sLibForm.'/images/internal_link.gif', '', 'return popupInternalLink(document.'.$this->sFormName."['".$strName."'])", t('EDITOR_INTERNAL'));
                    break;

                case 'date':
                    $params['placeholder'] = 'dd/mm/yyyy';
                    // for all date format same class
                case 'shortdate':
                case 'calendar':
                    $class .= ' datepicker';
                    break;
                case 'date_edition':
                    $add .= Pelican_Html_Form::comment('('.t('DATE_FORMAT_LABEL_EDITION').')');
                    break;
                case 'heure':
                    $add .= Pelican_Html_Form::comment('('.t('HOUR_FORMAT_LABEL').')');
                    $params['placeholder'] = 'hh:mm';
                    break;
                case 'color':
                    $class .= ' colors';
                    $this->_aIncludes['color'] = true;
                    $this->setJquery('minicolors');
                    break;
                case 'text':
                    if (!isset($options['message'])) {
                        $options['message'] = $iMaxLength.t('NDP_MAX_CAR');
                    }
                break;
            }
            $params['type'] = $strType;
            $params['class'] = $class;
            $params['name'] = $strName;
            $params['id'] = $strName;
            $params['size'] = $iSize;
            $params['maxlength'] = $iMaxLength;
            $params['value'] = $strValue;
            if ($multiple) {
                $params['multiple'] = 1;
                $params['name'] = $strName.'[]';
            }
            if ($this->bVirtualKeyboard && $strType == 'text') {
                $params['onfocus'] = 'activeInput = this;PopupVirtualKeyboard.attachInput(this);';
                $this->_InputVK[] = $strName;
            }
            $this->countInputName($strName);

            $strTmp .= Pelican_Html::input($params);

            if ($multiple) {
                $strTmp .= '<br />'.t('POPUP_MEDIA_LABEL_NEW_FILE_COMMENT');
            }
            $strTmp = Pelican_Html_Form::addInputEvent($strTmp, $strEvent);

            if ($options['message'] != '') {
                $add .= ' '.$options['message'];
            }
            $strTmp .= $add;

            // Génération de la fonction js de vérification.
            if ($bRequired || ($strControl != '' && $strControl != 'color' && $strControl != 'internallink')) {
                $this->_aIncludes['text'] = true;
                $this->_sJS .= 'var o = obj.elements["'.$strName."\"];\n";
                $this->_sJS .= "if (typeof obj['".$strName."'] != 'undefined' && obj['".$strName."'] !=null ){ ";
                $this->_sJS .= 'if ( ';
                if (!$bRequired) {
                    // Si le champ n'est pas requis, ne faire la vérification que si le champ n'est pas vide.
                    $this->_sJS .= "!isBlank(obj['".$strName."'].value) && ";
                }

                if ($strControl != '' && $strControl != 'color' && $strControl != 'internallink') {
                    if ($bRequired) {
                        // Si le champ est  requis,  faire la vérification que  le champ n'est pas vide et pas caché .
                        $this->_sJS .= " !$(o).parents(\"tbody\").hasClass(\"isNotRequired\") && (isBlank(obj['".$strName."'].value) ||";
                    }
                    switch ($strControl) {
                        case 'alphanum':
                            $this->_sJS .= "!isAlphaNum(obj['".$strName."'].value)";
                            $this->_aIncludes['text'] = true;
                            $strMessage = t('FORM_MSG_ALPHANUM');
                            break;
                        case 'numerique':
                        case self::OTHER_NUMERIC:
                            $this->_sJS .= "!isNumeric(obj['".$strName."'].value)";
                            $this->_aIncludes['num'] = true;
                            $strMessage = t('FORM_MSG_REAL');
                            break;
                        case 'number':
                            $this->_sJS .= "!isNumeric(obj['".$strName."'].value)";
                            $this->_aIncludes['num'] = true;
                            $strMessage = t('FORM_MSG_NUMBER');
                            break;
                        case 'positive-number':
                            $this->_sJS .= "obj['".$strName."'].value <= 0";
                            $this->_aIncludes['num'] = true;
                            $strMessage = t('FORM_MSG_NUMBER_POSITIVE');
                            break;
                        case 'float':
                        case 'flottant':
                        case 'real':
                        case 'reel':
                            $this->_sJS .= "!isFloat(obj['".$strName."'].value)";
                            $this->_aIncludes['num'] = true;
                            $strMessage = t('FORM_MSG_REAL');
                            break;
                        case 'telephone':
                            $this->_sJS .= "!isTel(obj['".$strName."'].value)";
                            $this->_aIncludes['num'] = true;
                            $strMessage = t('FORM_MSG_TELEPHONE');
                            break;
                        case 'mail':
                            $this->_sJS .= "!isMail(obj['".$strName."'].value)";
                            $this->_aIncludes['text'] = true;
                            $strMessage = t('FORM_MSG_MAIL');
                            break;
                        case 'URL':
                            $this->_sJS .= "!isURL(obj['".$strName."'].value)";
                            $this->_aIncludes['text'] = true;
                            $strMessage = t('FORM_MSG_URL');
                            break;
                        case 'login':
                            $this->_sJS .= "!isLogin(obj['".$strName."'].value)";
                            $this->_aIncludes['text'] = true;
                            $strMessage = t('FORM_MSG_LOGIN');
                            break;
                        case 'dateNF':
                        case 'shortdate':
                        case 'date':
                        case 'calendar':
                            $this->_sJS .= "!isDate(obj['".$strName."'].value)";
                            $this->_aIncludes['date'] = true;
                            $strMessage = t('FORM_MSG_DATE');
                            break;
                        case 'date_edition':
                            $this->_sJS .= "!isDate_edition(obj['".$strName."'].value)";
                            $this->_aIncludes['date'] = true;
                            $strMessage = t('FORM_MSG_DATE_EDITION');
                            break;
                        case 'year':
                            $this->_sJS .= "!isNumeric(obj['".$strName."'].value && obj['".$strName."'].value.length == 4)";
                            $this->_aIncludes['num'] = true;
                            $strMessage = t('FORM_MSG_YEAR');
                            break;
                        case 'heure':
                            $this->_sJS .= "!isHour(obj['".$strName."'].value)";
                            $this->_aIncludes['date'] = true;
                            $strMessage = t('FORM_MSG_HEURE');
                            break;
                        case 'text':
                            $this->_sJS .= ' false';
                            break;
                    }
                    if ($bRequired) {
                        // on ferme la parenthése pour les champs masqué isNotRequired
                        $this->_sJS .= ') ';
                    }
                } else {
                    $this->_aIncludes['text'] = true;
                    $this->_sJS .= 'isBlank(o.value)  && !$(o).parents("tbody").hasClass("isNotRequired")';
                    //$this->_sJS .= "isBlank(obj['" . $strName . "'].value)  && !$(\"#" . $strName . "\").parents(\"tbody\").hasClass(\"isNotRequired\") ";
                }
                $this->_sJS .= ") {\n";
                $controleJs = 'alert("'.t('FORM_MSG_VALUE_REQUIRE').' \\"'.(strip_tags(str_replace('"', '\\"', $strLib))).'\\"';
                if ($strControl != '' && $strControl != 'color' && $strControl != 'internallink') {
                    $controleJs .= ' '.t('FORM_MSG_WITH').' '.$strMessage;
                }
                if ($strControl === self::OTHER_NUMERIC) {
                    $controleJs = 'alert("'.t('NDP_MSG_PLEASE_CHOOSE').' '.$strMessage.' '.t('NDP_FOR').' \\"'.(strip_tags(str_replace('"', '\\"', $strLib))).'\\"';
                }
                $this->_sJS .= $controleJs;
                $this->_sJS .= ".\");\n";
                $this->_sJS .= "fwFocus(obj['".$strName."']);\n";
                $this->_sJS .= "return false;\n";
                $this->_sJS .= "}\n}\n";
            }
        }
        if ($infoBull) {
            $strLib = '<span title="'.$infoBull.'" style="cursor:help;">'.$strLib.'</span>';
        }
        if (!$bFormOnly) {
            $options = array();
            $options['for'] = $strName;
            if ($iconInfoBull) {
                $options['strLibInfoBull'] = Backoffice_Tooltip_Helper::help($iconInfoBull);
            }
            $strTmp = Pelican_Html_Form::get($strLib, $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, '', '', $this->_sFormDisposition, [], $options);

            if (isset($options['data-template'])) {
                $strTmp = str_replace('<input', "<input data-template='".$options['data-template']."' ", $strTmp);
            }
        }

        return $this->output($strTmp);
    }

    /**
     * Génère une association à partir de requêtes SQL.
     *
     *
     * @param int      $deprecated      
     * @param string   $strName         Nom du champ
     * @param string   $strLib          Libellé du champ
     * @param mixed    $strSQL          (option) Requête SQL des valeurs disponibles (id,lib) :
     *                                  "" par défaut
     * @param mixed    $strSQLValues    (option) Requête SQL des valeurs sélectionnées
     *                                  (liste des id) : "" par défaut
     * @param bool     $bRequired       (option) Champ obligatoire : false par défaut
     * @param bool     $bDeleteOnAdd    (option) Supprimer les valeurs de la liste source
     *                                  après ajout à la liste destination : true par défaut
     * @param bool     $bReadOnly       (option) Affiche uniquement la valeur et pas le champ
     *                                  (créé un input hidden) : false par défaut
     * @param mixed $iSize           (option) 
     * @param string   $iWidth          (option) Largeur du contrôle : 200 par défaut
     * @param bool     $bFormOnly       (option) Génération du champ uniquement, sans libellé
     *                                  : false par défaut
     * @param string   $arForeignKey    (option) Remplace la Pelican_Index_Frontoffice_Zone
     *                                  de recherche par une liste déroulante pour filtrer la sélection (nécessite
     *                                  bSearchEnabled à true) : 3 modes : - 1 : nom de table de référence de la clé
     *                                  étrangère (sans le préfixe) => la requête de liste et de recherche seront
     *                                  alors générique - 2 : array(nom de table de référence de la clé
     *                                  étrangère, SQL avec id et lib dans le select pour la liste déroulante) => la
     *                                  requête de recherche sera alors générique - 3 : array(nom de table de
     *                                  référence de la clé étrangère, SQL avec id et lib dans le select pour la
     *                                  liste déroulante, SQL avec id et lib dans le select pour la recherche et
     *                                  :RECHERCHE: dans la clause where)
     * @param array    $arSearchFields  (option) Liste des champs sur lesquels effectuer
     *                                  une recherche par like
     * @param mixed $aBind           (option) 
     * @param string   $strOrderColName (option) 
     * @param bool     $showAll         (option) 
     * @param int      $limit           (option) 
     * @param array    $options         (option) tableau d'options supplémentaire permettant de limiter le nombre de parametre à la methode
     *                                  'iconInfoBulle' => bool (default false) active l'infobulle icon
     *                                  'messageInfoBulle => label de l'infobulle
     *
     * @return string
     */
    public function createAssocFromSql($deprecated, $strName, $strLib, $strSQL = '', $strSQLValues = '', $bRequired = false, $bDeleteOnAdd = true, $bReadOnly = false, $iSize = '5', $iWidth = 200, $bFormOnly = false, $arForeignKey = '', $arSearchFields = '', $aBind = array(), $strOrderColName = '', $showAll = false, $bTrieAlphaValeurSelectionne = true, $limit = 0, $options = [])
    {
        $oConnection = Pelican_Db::getInstance();
        $bSearchEnabled = false;
        if ($arForeignKey || $arSearchFields) {
            $bSearchEnabled = true;
        }
        // available values
        $aDataValues = array();
        if ($strSQL) {
            if ($arSearchFields) {
                if (!is_array($arSearchFields)) {
                    $arSearchFields = array($arSearchFields);
                }
                $sFilter = '';
                while (list(, $val) = each($arSearchFields)) {
                    $sResearch = '';
                    if (strlen($sFilter) != 0) {
                        $sResearch .= ' OR ';
                    }
                    $sResearch .= 'UPPER('.$val.") like UPPER('%:RECHERCHE:%')";
                    $sFilter .= $sResearch;
                }
                $sFilter = '('.$sFilter.')';
                if (stristr($strSQL, 'where ') && !stristr($strSQL, 'union ')) {
                    $strSQL = preg_replace('/where /i', 'where '.$sFilter.' AND ', $strSQL);
                } elseif (stristr($strSQL, 'group by ')) {
                    $strSQL = preg_replace('/group by /i', 'where '.$sFilter.' group by ', $strSQL);
                } elseif (stristr($strSQL, 'order by ')) {
                    $strSQL = preg_replace('/order by /i', 'where '.$sFilter.' order by ', $strSQL);
                }
                if ($showAll) {
                    $this->_getValuesFromSQL(str_replace(':RECHERCHE:', '%', $strSQL), $aDataValues, $aBind);
                }
                $_SESSION['AssocFromSql_Search'][$this->sFormName.'_'.$strName] = $strSQL;
            } else {
                $this->_getValuesFromSQL($strSQL, $aDataValues, $aBind);
            }
        }
        // selected values
        if (is_array($strSQLValues)) {
            $aSelectedValues = $strSQLValues;
        } else {
            $aSelectedValues = array();
            if ($strSQLValues) {
                $result = $oConnection->queryTab($strSQLValues, $aBind);
                if (isset($result)) {
                    foreach ($result as $valeur) {
                        $keys = array_keys($valeur);
                        if (in_array(0, $keys) && in_array(1, $keys)) {
                            $keys = array(0, 1);
                        }
                        if ($arForeignKey || $arSearchFields) {
                            $aSelectedValues[$valeur[$keys[0]]] = $valeur[$keys[1]];
                        } else {
                            $aSelectedValues[count($aSelectedValues)] = $valeur[$keys[0]];
                        }
                    }
                }
            }
        }

        return $this->_createAssoc($oConnection, $strName, $strLib, $aDataValues, '', '', '', $aSelectedValues, $bRequired, $bDeleteOnAdd, false, $bSearchEnabled, $bReadOnly, $iSize, $iWidth, '', $bFormOnly, $arForeignKey, false, false, $strOrderColName, $showAll, $limit, $bTrieAlphaValeurSelectionne, $options);
    }

    /**
     * Génère une association à partir d'un tableau de valeurs.
     *
     *
     * @param Pelican_Db $oConnection     Objet connection à la base
     * @param string     $strName         Nom du champ
     * @param string     $strLib          Libellé du champ
     * @param mixed      $aDataValues     (option) Tableau de valeurs (id=>lib) : "" par
     *                                    défaut
     * @param mixed      $aSelectedValues (option) Tableau des valeurs sélectionnées
     *                                    (liste des id) : "" par défaut
     * @param bool       $bRequired       (option) Champ obligatoire : false par défaut
     * @param bool       $bDeleteOnAdd    (option) Supprimer les valeurs de la liste source
     *                                    après ajout à la liste destination : true par défaut
     * @param bool       $bReadOnly       (option) Affiche uniquement la valeur et pas le champ
     *                                    (créé un input hidden) : false par défaut
     * @param mixed   $iSize           (option) 
     * @param string     $iWidth          (option) Largeur du contrôle : 200 par défaut
     * @param bool       $bFormOnly       (option) Génération du champ uniquement, sans libellé
     *                                    : false par défaut
     * @param string     $arForeignKey    (option) Remplace la Pelican_Index_Frontoffice_Zone
     *                                    de recherche par une liste déroulante pour filtrer la sélection (nécessite
     *                                    bSearchEnabled à true) : 3 modes : - 1 : nom de table de référence de la clé
     *                                    étrangère (sans le préfixe) => la requête de liste et de recherche seront
     *                                    alors générique - 2 : array(nom de table de référence de la clé
     *                                    étrangère, SQL avec id et lib dans le select pour la liste déroulante) => la
     *                                    requête de recherche sera alors générique - 3 : array(nom de table de
     *                                    référence de la clé étrangère, SQL avec id et lib dans le select pour la
     *                                    liste déroulante, SQL avec id et lib dans le select pour la recherche et
     *                                    :RECHERCHE: dans la clause where)
     * @param string     $strOrderColName (option) 
     * @param array      $options         (option) tableau d'options supplémentaire permettant de limiter le nombre de parametre à la methode
     *                                    'delOnDlbClick' => bool (default true) ne supprime pas l'element selectionné lors d'un double click
     *
     * @return string
     */
    public function createAssocFromList($oConnection, $strName, $strLib, $aDataValues = '', $aSelectedValues = '', $bRequired = false, $bDeleteOnAdd = true, $bReadOnly = false, $iSize = '5', $iWidth = 200, $bFormOnly = false, $arForeignKey = '', $strOrderColName = '', $limit = 0, $bTrieAlphaValeurSelectionne = true, $options = [])
    {
        $bSearchEnabled = ($arForeignKey ? true : false);

        return $this->_createAssoc($oConnection, $strName, $strLib, $aDataValues, '', '', '', $aSelectedValues, $bRequired, $bDeleteOnAdd, false, $bSearchEnabled, $bReadOnly, $iSize, $iWidth, '', $bFormOnly, $arForeignKey, false, false, $strOrderColName, false, $limit, $bTrieAlphaValeurSelectionne, $options);
    }

    /**
     * Génère une association.
     *
     *
     * @param Pelican_Db $oConnection                 Objet connection à la base
     * @param string     $strName                     Nom du champ
     * @param string     $strLib                      Libellé du champ
     * @param mixed      $aDataValues                 Tableau de valeurs (id=>lib)
     * @param string     $strTableName                
     * @param string     $strRefTableName             (option) Nom de la table de jointure où trouver
     *                                                les valeurs sélectionnées : "" par défaut
     * @param string     $iID                         (option) Id auquel sont associées les valeurs
     *                                                sélectionnées : "" par défaut
     * @param mixed      $aSelectedValues             (option) Tableau des valeurs sélectionnées
     *                                                (liste des id) : "" par défaut
     * @param bool       $bRequired                   (option) Champ obligatoire : false par défaut
     * @param bool       $bDeleteOnAdd                (option) Supprimer les valeurs de la liste source
     *                                                après ajout à la liste destination : true par défaut
     * @param bool       $bEnableManagement           (option) Accès à la popup d'ajout dans la
     *                                                table de référence : true par défaut
     * @param bool       $bSearchEnabled              (option) La liste n'est pas remplie et un
     *                                                formulaire de recherche est ajouté : false par défaut
     * @param bool       $bReadOnly                   (option) Affiche uniquement la valeur et pas le champ
     *                                                (créé un input hidden) : false par défaut
     * @param mixed   $iSize                       (option) 
     * @param string     $iWidth                      (option) Largeur du contrôle : 200 par défaut
     * @param string     $strColRefTableName          (option) Nom de la colonne dans la table de
     *                                                référence correspondant à $iID : "CONTENU_ID" par défaut
     * @param bool       $bFormOnly                   (option) Génération du champ uniquement, sans libellé
     *                                                : false par défaut
     * @param string     $arForeignKey                (option) Remplace la Pelican_Index_Frontoffice_Zone
     *                                                de recherche par une liste déroulante pour filtrer la sélection (nécessite
     *                                                bSearchEnabled à true) : 3 modes : - 1 : nom de table de référence de la clé
     *                                                étrangère (sans le préfixe) => la requête de liste et de recherche seront
     *                                                alors générique - 2 : array(nom de table de référence de la clé
     *                                                étrangère, SQL avec id et lib dans le select pour la liste déroulante) => la
     *                                                requête de recherche sera alors générique - 3 : array(nom de table de
     *                                                référence de la clé étrangère, SQL avec id et lib dans le select pour la
     *                                                liste déroulante, SQL avec id et lib dans le select pour la recherche et
     *                                                :RECHERCHE: dans la clause where)
     * @param bool       $bSingle                     (option) Génère un nom de champ sans[] : false par
     *                                                défaut
     * @param bool       $alternateId                 (option) 
     * @param string     $strOrderColName             (option) 
     * @param bool       $showAll                     (option) 
     * @param bool       $bTrieAlphaValeurSelectionne (option) permet de faire un tri par ordre alphabétique
     * @param array      $options                     (option) tableau d'options supplémentaire permettant de limiter le nombre de parametre à la methode
     *                                                'delOnDlbClick' => bool (default true) ne supprime pas l'element selectionné lors d'un double click
     *                                                'showSource'    => bool (default true) affiche la liste source
     *                                                'iconInfoBulle' => bool (default false) active l'infobulle icon
     *                                                'messageInfoBulle => label de l'infobulle
     *
     * @return string
     */
    public function _createAssoc($oConnection, $strName, $strLib, $aDataValues, $strTableName, $strRefTableName = '', $iID = '', $aSelectedValues = '', $bRequired = false, $bDeleteOnAdd = true, $bEnableManagement = true, $bSearchEnabled = false, $bReadOnly = false, $iSize = '5', $iWidth = 200, $strColRefTableName = 'contenu_id', $bFormOnly = false, $arForeignKey = '', $bSingle = false, $alternateId = false, $strOrderColName = '', $showAll = false, $limit = 0, $bTrieAlphaValeurSelectionne = true, $options = [])
    {
        $strTmp = '';
        if (!$bReadOnly) {
            $this->_aIncludes['list'] = true;
        }
        if (!isset($options['delOnDlbClick'])) {
            $options['delOnDlbClick'] = true;
        }
        if (!isset($options['showSource'])) {
            $options['showSource'] = true;
        }
        if (!isset($options['iconInfoBulle'])) {
            $options['iconInfoBulle'] = false;
        }

        if ($bSearchEnabled) {
            // Charge uniquement les valeurs sélectionnées.
            if ($iID != '') {
                $aSelectedValues = array();
                $strSQL = 'select A.'.$strTableName.$this->_sTableSuffixeId.' as "id", A.'.$strTableName.$this->_sTableSuffixeLabel.' as "lib"';
                $strSQL .= ' from '.$this->_sTablePrefix.$strTableName.' A, '.$strRefTableName.' B';
                if ($alternateId) {
                    $child = $strName;
                } else {
                    $child = $strTableName.$this->_sTableSuffixeId;
                }
                $strSQL .= ' where A.'.$strTableName.$this->_sTableSuffixeId.' = B.'.$child;
                $strSQL .= ' and B.'.$strColRefTableName.' = '.$iID;
                $strSQL .= ' order by ';
                if ($strOrderColName != '') {
                    $strSQL .= $strOrderColName;
                } else {
                    $strSQL .= 'Lib';
                }
                $oConnection->Query($strSQL);
                if ($oConnection->rows > 0) {
                    while ($ligne = each($oConnection->data['id'])) {
                        $aSelectedValues[$ligne['value']] = $oConnection->data['lib'][$ligne['key']];
                    }
                }
            } else {
                if (!isset($aSelectedValues)) {
                    $aSelectedValues = array();
                }
            }
        } else {
            if ($strTableName != '') {
                $aTmpSelectedValues = array();
                $this->_getValues($oConnection, $strTableName, $strRefTableName, $iID, $aDataValues, $aTmpSelectedValues, $strColRefTableName, $strOrderColName);
            }
            if ($aSelectedValues == '') {
                $aSelectedValues = $aTmpSelectedValues;
            }
            if (!is_array($aSelectedValues)) {
                if ($aSelectedValues != '') {
                    $aSelectedValues = array($aSelectedValues);
                } else {
                    $aSelectedValues = array();
                }
            }
        }
        if ($aSelectedValues == '') {
            $aSelectedValues = array();
        }
        if ($bReadOnly) {
            if (is_array($aSelectedValues)) {
                while ($ligne = each($aSelectedValues)) {
                    $this->countInputName($strName.($bSingle ? '' : '[]'));
                    $strTmp .= Pelican_Html::input(array('type' => 'hidden', 'name' => $strName.($bSingle ? '' : '[]'),
                        'value' => str_replace('"', '&quot;', $ligne['key']), ));
                }
            }
        }
        // Génération du couple libellé/champ
        if ($bReadOnly) {
            if ($bSearchEnabled) {
                foreach ($aSelectedValues as $ligne) {
                    $strTmp .= ''.$ligne.Pelican_Html::br();
                }
            } else {
                if (is_array($aSelectedValues)) {
                    foreach ($aSelectedValues as $ligne) {
                        $strTmp .= ''.$aDataValues[$ligne].Pelican_Html::br();
                    }
                }
            }
            if (!$bFormOnly) {
                $this->countInputName($strName.'_last_selected');
                $strTmp .= Pelican_Html::input(array('type' => 'hidden', 'name' => $strName.'_last_selected',
                    'id' => $strName.'_last_selected', ));
                $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, 'top', '', $this->_sFormDisposition);
            }
        } else {
            $strTmp .= '<table class="'.$this->sStyleVal.'" cellpadding="0" cellspacing="0" border="0" style="width:'.(2 * $iWidth + 30).'px;" summary="Associative">';
            if ($this->_bUseAssocLabel && $options['showSource']) {
                $strTmp .= Pelican_Html::tr(
                        Pelican_Html::td(array('class' => $this->sStyleVal), Pelican_Html::i(t('FORM_MSG_LIST_SELECTED')))
                        .($strOrderColName ? Pelican_Html::td(array('class' => $this->sStyleVal), Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp()) : '')
                        .Pelican_Html::td(array('class' => $this->sStyleVal), Pelican_Html::nbsp())
                        .Pelican_Html::td(array('class' => $this->sStyleVal), Pelican_Html::i(t('FORM_MSG_LIST_AVAILABLE')))
                    )."\n";
            }
            $strTmp .= '<tr>';
            // Valeurs choisies
            $this->countInputName($strName);
            $strTmp .= '<td class="'.$this->sStyleVal.'">';
            $aOption = array();
            if ($aSelectedValues) {
                if ($bSearchEnabled) {
                    while ($ligne = each($aSelectedValues)) {
                        $aOption[] = Pelican_Html::option(array('value' => $ligne['key']), $ligne['value']);
                    }
                } else {
                    if (is_array($aSelectedValues)) {
                        while ($ligne = each($aSelectedValues)) {
                            if ($aDataValues[$ligne['value']]) {
                                $aOption[] = Pelican_Html::option(array('value' => $ligne['value']), $aDataValues[$ligne['value']]);
                            }
                        }
                    }
                }
            }
            $strTmp .= Pelican_Html::select(array('id' => $strName, 'name' => $strName.($bSingle ? '' : '[]'),
                    'size' => $iSize, 'multiple' => 'multiple', 'ondblclick' => ($options['delOnDlbClick'] ? ('assocDel(this'.($bDeleteOnAdd ? ', true' : '').')') : ''),
                    'style' => 'width:'.$iWidth.'px;', ), implode('', $aOption)).'</td>';
            if ($strOrderColName != '') {
                $strTmp .= '<td class="'.$this->strStyleVal.'">';
                $strTmp .= '<img src="'.$this->_sLibPath.$this->_sLibForm.'/images/top.gif" width="13" height="15" ';
                $strTmp .= 'onClick="MoveTop(document.'.$this->sFormName.".elements['".$strName.($bSingle ? '' : '[]')."']);\"\">".Pelican_Html::br();
                $strTmp .= '<img src="'.$this->_sLibPath.$this->_sLibForm.'/images/up.gif" width="13" height="15" ';
                $strTmp .= 'onClick="MoveUp(document.'.$this->sFormName.".elements['".$strName.($bSingle ? '' : '[]')."']);\"\">".Pelican_Html::br();
                $strTmp .= '<img src="'.$this->_sLibPath.$this->_sLibForm.'/images/down.gif" width="13" height="15" ';
                $strTmp .= 'onClick="MoveDown(document.'.$this->sFormName.".elements['".$strName.($bSingle ? '' : '[]')."']);\"\">".Pelican_Html::br();
                $strTmp .= '<img src="'.$this->_sLibPath.$this->_sLibForm.'/images/bottom.gif" width="13" height="15" ';
                $strTmp .= ' onClick="MoveBottom(document.'.$this->sFormName.".elements['".$strName.($bSingle ? '' : '[]')."']);\"\">";
                $strTmp .= '</td>';
                $this->_aIncludes['ordered_list'] = true;
            }
            if ($options['showSource'] == true) {
                $strTmp .= '<td valign="middle" style="width:30px;" align="center">';
                $strTmp .= Pelican_Html::nbsp().'<a href="javascript://" onclick="assocAdd'.($bSingle ? 'Single' : '').'(document.'.$this->sFormName.'.src'.$strName;
                if ($bDeleteOnAdd) {
                    $strTmp .= ', true';
                } else {
                    $strTmp .= ', false';
                }
                if ($strOrderColName != '') {
                    $strTmp .= ', true';
                } else {
                    $strTmp .= ', false';
                }
                if ($limit) {
                    $strTmp .= ', '.$limit;
                } else {
                    $strTmp .= ', 0';
                }
                if ($bTrieAlphaValeurSelectionne) {
                    $strTmp .= ', true';
                } else {
                    $strTmp .= ', false';
                }
                $strTmp .= ');"><img src="'.$this->_sLibPath.$this->_sLibForm.'/images/left.gif" border="0" width="7" height="12" /></a>'.Pelican_Html::nbsp();
                $strTmp .= Pelican_Html::br();
                $strTmp .= Pelican_Html::nbsp().'<a href="javascript://" onclick="assocDel(document.'.$this->sFormName.".elements['".$strName.($bSingle ? '' : '[]')."']";
                if ($bDeleteOnAdd) {
                    $strTmp .= ', true';
                } else {
                    $strTmp .= ', false';
                }
                if ($strOrderColName != '') {
                    $strTmp .= ', true';
                }
                $strTmp .= ');"><img src="'.$this->_sLibPath.$this->_sLibForm.'/images/right.gif" border="0" width="7" height="12" /></a>'.Pelican_Html::nbsp();
                $strTmp .= '</td>';
                // Valeurs disponibles
                $strTmp .= '<td class="'.$this->sStyleVal.'">';
                // Recherche activée (par champ input ou par combo ($arForeignKey doit être renseigné)
                if ($bSearchEnabled) {
                    $this->_aIncludes['popup'] = true;
                    // cas de la recherche par combo
                    if ($strTableName || $arForeignKey) {
                        if ($arForeignKey) {
                            // Si c'est un tableau, on défini le champ de recherche, la requête de la combo et la requête de recherche (sans clause where)
                            if (is_array($arForeignKey)) {
                                $champForeign = $arForeignKey[0];
                                // Si le second paramètre du tableau n'a pas été initialisé, on le défini avec une expressino générique (à partir du nom de la table)
                                if (!$arForeignKey[1]) {
                                    $sqlForeign = 'select '.$champForeign.$this->_sTableSuffixeId.' "id",'.$champForeign.$this->_sTableSuffixeLabel.' "lib" from '.$this->_sTablePrefix.$champForeign.' order by lib';
                                } else {
                                    $sqlForeign = $arForeignKey[1];
                                }
                                $sqlSearch = $arForeignKey[2];
                            } else {
                                // sinon on prend juste le champ pour initialiser la procédure
                                $champForeign = $arForeignKey;
                                $sqlForeign = 'select '.$champForeign.$this->_sTableSuffixeId.' as "id",'.$champForeign.$this->_sTableSuffixeLabel.' "lib" from '.$this->_sTablePrefix.$champForeign.' order by lib';
                            }
                            // Si la requête de recherche n'a pas été initialisée, on la définit de façon générique
                            if (!$sqlSearch) {
                                $sqlSearch = 'select '.$strTableName.$this->_sTableSuffixeId.' "id", '.$strTableName.$this->_sTableSuffixeLabel.' "lib" from '.$this->_sTablePrefix.$strTableName;
                                $sqlSearch .= ' WHERE '.$champForeign.$this->_sTableSuffixeId." = ':RECHERCHE:'";
                                $sqlSearch .= ' order by lib';
                            }
                            // cas de la recherche par input
                        } else {
                            // Définition de la requête de recherche de façon générique pour la recherche par input
                            $sqlSearch = 'select '.$strTableName.$this->_sTableSuffixeId.' "id", '.$strTableName.$this->_sTableSuffixeLabel.' "lib" from '.$this->_sTablePrefix.$strTableName;
                            $sqlSearch .= ' WHERE '.$strTableName.$this->_sTableSuffixeLabel." LIKE ('%:RECHERCHE:%')";
                            $sqlSearch .= ' order by lib';
                        }
                        $action = "searchIndexation('".$this->_sLibPath.$this->_sLibForm."/', 'src".$strName."', '".$strTableName."', eval('this.form.iSearchVal".$strName.".value'),'".base64_encode($sqlSearch)."',".($showAll ? 1 : 0).');';
                    } else {
                        $action = "searchIndexation('".$this->_sLibPath.$this->_sLibForm."/', 'src".$strName."', '', eval('this.form.iSearchVal".$strName.".value'),'".base64_encode($this->sFormName.'_'.$strName)."',".($showAll ? 1 : 0).');';
                        //      $action = "\"searchIndexation('".$this->_sLibPath.$this->_sLibForm."/', 'src".$strName."', '".$strTableName."', eval('this.form.iSearchVal".$strName.".value'),'".base64_encode($sqlSearch)."');\"";
                    }
                    if ($arForeignKey) {
                        if (!$bReadOnly) {
                            $this->countInputName('iSearchVal'.$strName);
                            $aOption = array();
                            $result_Foreign = $oConnection->queryTab($sqlForeign);
                            $aOption[] = Pelican_Html::option(array('value' => ''), t('FORM_SELECT_CHOOSE'));
                            foreach ($result_Foreign as $ligne) {
                                $keys = array_keys($ligne);
                                if (in_array(0, $keys) && in_array(1, $keys)) {
                                    $keys = array(0, 1);
                                }
                                $aOption[] = Pelican_Html::option(array('value' => $ligne[$keys[0]]), $ligne[$keys[1]]);
                            }
                            $strTmp .= Pelican_Html::select(array('name' => 'iSearchVal'.$strName,
                                    'id' => 'iSearchVal'.$strName,
                                    'size' => '1', 'style' => 'width:'.$iWidth.'px;',
                                    'onchange' => $action, ), implode('', $aOption))."\n";
                        }
                    } else {
                        $this->countInputName('iSearchVal'.$strName);
                        $strTmp .= Pelican_Html::input(array('type' => 'text', 'name' => 'iSearchVal'.$strName,
                            'size' => '14', 'onkeydown' => "submitIndexation('".$this->_sLibPath.$this->_sLibForm."/', '".($strTableName ? $strTableName."','".base64_encode($sqlSearch) : "','".base64_encode($this->sFormName.'_'.$strName))."')", ));
                        $this->countInputName('bSearch'.$strName);
                        $strTmp .= '<input type="button" class="button" name="bSearch'.$strName.'" value="'.t('FORM_BUTTON_SEARCH').'" onclick="'.$action.'" />'.Pelican_Html::br();
                    }
                }

                $this->countInputName('src'.$strName);
                if ($bSearchEnabled) {
                    $size = $iSize - 1;
                } else {
                    $size = $iSize;
                }
                $aOption = array();
                if (!$bSearchEnabled || ($bSearchEnabled && $showAll && $aDataValues)) {
                    //reset($aDataValues);
                    if (is_array($aDataValues)) {
                        reset($aDataValues);
                        while ($ligne = each($aDataValues)) {
                            if (!$bDeleteOnAdd || !in_array($ligne['key'], $aSelectedValues)) {
                                $aOption[] = Pelican_Html::option(array('value' => ((substr($ligne['key'], 0, 7) == 'delete_' ? '' : $ligne['key']))), $ligne['value']);
                            }
                        }
                    }
                }
                if ($options['iconInfoBulle'] && $options['messageInfoBulle2']) {
                    $strTmp .= Backoffice_Tooltip_Helper::help($options['messageInfoBulle2']);
                }
                $strTmp .= Pelican_Html::select(array('id' => 'src'.$strName, 'name' => 'src'.$strName,
                    'size' => $size,
                    'multiple' => 'multiple', 'ondblclick' => 'assocAdd'.($bSingle ? 'Single' : '').'(this, '.($bDeleteOnAdd ? 'true' : 'false').($strOrderColName ? ', true' : ', false').($limit ? ', '.$limit : ', 0').($bTrieAlphaValeurSelectionne ? ', true' : ', false').')',
                    'style' => 'width:'.$iWidth.'px;', ), implode('', $aOption));
                // Lien vers popup de gestion de la table de référence

                if ($bEnableManagement) {
                    $this->_aIncludes['popup'] = true;
                    $strTmp .= '<td class="'.$this->sStyleVal.'">';
                    $strTmp .= "<a href=\"javascript://\" onclick=\"addRef('".$this->_sLibPath.$this->_sLibForm."/', 'document.".$this->sFormName."','".$strName."', '".$strTableName."', ";
                    if ($bDeleteOnAdd) {
                        $strTmp .= '1';
                    } else {
                        $strTmp .= '0';
                    }
                    $strTmp .= ');">'.t('FORM_BUTTON_ADD_VALUE').'</a>';
                    $strTmp .= '</td>';
                }
            }
            $strTmp .= "</tr>\n</table>\n";
            if (!$bFormOnly) {
                $this->countInputName($strName.'_last_selected');
                $strTmp .= '<input type="hidden"  name="'.$strName.'_last_selected" id="'.$strName.'_last_selected" />';
                $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, 'top', '', $this->_sFormDisposition);
                if ($options['iconInfoBulle']) {
                    $addon = '';
                    if ($bRequired) {
                        $addon = ' *';
                    }
                    $strLibInfoBull = Pelican_Text::htmlentities($strLib).$addon.' '.Backoffice_Tooltip_Helper::help($options['messageInfoBulle']);
                    $strTmp = str_replace(Pelican_Text::htmlentities($strLib).$addon, $strLibInfoBull, $strTmp);
                }
            }
            // Génération de la fonction js de vérification.
            if ($bRequired) {
                $this->_sJS .= 'o = obj.elements["'.$strName.($bSingle ? '' : '[]')."\"];\n";
                $this->_sJS .= "if ( o.length == 0 && !$(o).parents(\"tbody\").hasClass(\"isNotRequired\")) {\n";
                $this->_sJS .= 'alert("'.t('FORM_MSG_VALUE_CHOOSE').' \\"'.strip_tags(str_replace('"', '\\"', $strLib)).'\\"");'."\n";
                $this->_sJS .= "fwFocus(o);\n";
                $this->_sJS .= "return false;\n";
                $this->_sJS .= "}\n";
            }

            // si la verif est activer (CheckBox mobile/ web)
            // helper backend getFormAffichage
            if (Pelican::$config['VERIF_JS'] == 1) {
                $this->_sJS .= "}\n";
                Pelican::$config['VERIF_JS'] = 0;
            }

            $this->_sJS .= 'selectAll(document.'.$this->sFormName.'.elements["'.$strName.($bSingle ? '' : '[]')."\"]);\n";
        }

        return $this->output($strTmp);
    }

    /**
     * Génère une Pelican_Index_Frontoffice_Zone de saisie texte.
     *
     * Il est possible de passer un tableau en tant que valeur
     * => les données seront séparées par un retour chariot
     * => il faut ensuite utiliser la fonction splitTextArea pour retrouver un tableau
     * de données à la Soumission du formulaire
     *
     *
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param bool $bRequired (option) Champ obligatoire : false par défaut
     * @param string $strValue (option) Valeur du champ : "" par défaut
     * @param string $iMaxLength (option) Nb de caractères maximum : "" par défaut
     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
     *                                   (créé un input hidden) : false par défaut
     * @param int|string $iRows (option) Nombre de lignes : 5 par défaut
     * @param int|string $iCols (option) Nombre de colonnes : 30 par défaut
     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
     *                                   : false par défaut
     * @param string $wrap (option) Paramètre wrap du textarea
     * @param bool $bcountchars (option) Affiche le comptage des caractères tapés
     * @param string $strEvent (option)
     * @param string $infoBull (option) champs permettant d'afficher une info-bulle
     *                                   De type Array si l'info-bulle est une icone :
     *                                   'isIcon' => Boolean
     *                                   'message'  => String
     * @param array|string $addCheckformOption (option) tableau pour ajout de controle
     *                                   $addCheckformOption['json'] : vérifie qu'il s'agit d'un json valide
     * @return string
     */
    public function createTextArea($strName, $strLib, $bRequired = false, $strValue = '', $iMaxLength = '', $bReadOnly = false, $iRows = 5, $iCols = 30, $bFormOnly = false, $wrap = '', $bcountchars = true, $strEvent = '', $infoBull = '', $addCheckformOption = array())
    {
        // Génération du couple libellé/champ
        $strTmp = '';
        if (is_array($strValue)) {
            $strValue = implode("\r\n", $strValue);
        }
        $iconInfoBull = false;
        if (is_array($infoBull)) {
            if (!empty($infoBull['isIcon'])) {
                $iconInfoBull = $infoBull['message'];
                $infoBull = false;
            }
        }
        if ($infoBull) {
            $strLib = ('<span title="'.$infoBull.'" style="cursor:help;">'.$strLib.'</span>');
        }
        if ($bReadOnly) {
            $strTmp .= nl2br(Pelican_Text::htmlentities($strValue));
            if (!$bFormOnly) {
                $strTmp = Pelican_Html_Form::get($strLib, $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, 'top', '', $this->_sFormDisposition);
            }
            $strTmp .= $this->createHidden($strName, str_replace('"', '&quot;', $strValue));
        } else {
            if (!$this->_sDefaultFocus) {
                $this->_sDefaultFocus = $strName;
            }
            if ($wrap) {
                $txtWrap = ' wrap="'.$wrap.'"';
            } else {
                $txtWrap = '';
            }
            $this->countInputName($strName);
            $strTmp .= '<textarea id="'.$strName.'" name="'.$strName.'" rows="'.$iRows.'" cols="'.$iCols.'"'.$txtWrap;
            if ($strEvent) {
                $strTmp .= ' '.$strEvent;
            }
            if ($this->bVirtualKeyboard) {
                $strTmp .= ' onfocus="activeInput = this;PopupVirtualKeyboard.attachInput(this);"';
                $this->_InputVK[] = $strName;
            }
            if ($bcountchars) {
                $strTmp .= ' onkeyup="countchars(this,'.($iMaxLength ? $iMaxLength : 0).');"';
            }
            $strTmp .= '>'.$strValue.'</textarea>';
            if ($bcountchars) {
                $this->_aIncludes['text'] = true;
                $strTmp .= '<div class="countchars" style="width:'.($iCols * 6).'px;" id="cnt_'.$strName.'_div">'.strlen($strValue);
                if ($iMaxLength) {
                    $strTmp .= '/'.$iMaxLength.' '.t('CHARACTER').'s</div>';
                } else {
                    $strTmp .= ' '.t('CHARACTER').(strlen($strValue) > 1 ? 's' : '').'</div>';
                }
            }
            if (!$bFormOnly) {
                $strTmp = Pelican_Html_Form::get($strLib, $strTmp, $bRequired,
                    $bReadOnly, $this->sStyleLib, $this->sStyleVal, 'top',
                    '', $this->_sFormDisposition);
                if ($iconInfoBull) {
                    $addon = '';
                    if ($bRequired) {
                        $addon = ' *';
                    }
                    $strLibInfoBull = Pelican_Text::htmlentities($strLib).$addon.' '.Backoffice_Tooltip_Helper::help($iconInfoBull);
                    $strTmp = str_replace($strLib.$addon, $strLibInfoBull, $strTmp);
                }
            }
            // Génération de la fonction js de vérification.
            $this->_sJS .= 'var o = obj.elements["'.$strName."\"];\n";
            if ($bRequired) {
                $this->_aIncludes['text'] = true;
                $this->_sJS .= "if ( isBlank(o.value)  && !$(o).parents(\"tbody\").hasClass(\"isNotRequired\") ) {\n";
                $this->_sJS .= 'alert("'.t('FORM_MSG_VALUE_REQUIRE').' \\"'.strip_tags(str_replace('"', '\\"', $strLib)).'\\".");'."\n";
                $this->_sJS .= "fwFocus(o);\n";
                $this->_sJS .= "return false;\n";
                $this->_sJS .= "}\n";
            }
            if ($iMaxLength != '') {
                $this->_aIncludes['text'] = true;
                $this->_sJS .= "if ( obj['".$strName."'].value.length > ".$iMaxLength." ) {\n";
                $this->_sJS .= 'alert("'.t('FORM_MSG_LIMIT_1').' '.$iMaxLength.' '.t('FORM_MSG_LIMIT_2').' \\'.'"'.strip_tags(str_replace('"', '\\"', $strLib)).'\\'.'".");'."\n";
                $this->_sJS .= "fwFocus(obj['".$strName."']);\n";
                $this->_sJS .= "return false;\n";
                $this->_sJS .= "}\n";
            }
            if ($addCheckformOption['json'] === true) {
                $this->_aIncludes['text'] = true;
                $this->_sJS .= "if (!isJsonString(o.value) && !isBlank(o.value)) {\n";
                $this->_sJS .= 'alert("'.t('FORM_MSG_FORMAT').' \\"'.strip_tags(str_replace('"', '\\"', $strLib)).'\\".");'."\n";
                $this->_sJS .= "fwFocus(obj['".$strName."']);\n";
                $this->_sJS .= "return false;\n";
                $this->_sJS .= "}\n";
            }
        }

        return $this->output($strTmp);
    }

    /**
     * Génère un contrôle de type liste.
     *
     *
     * @param string     $strName               Nom du champ
     * @param string     $strLib                Libellé du champ
     * @param mixed      $dataValues            Tableau de valeurs (id=>lib)
     * @param mixed      $aCheckedValues        Liste des valeurs cochées (liste des id)
     * @param bool       $required              Champ obligatoire
     * @param bool|array $options               Affiche uniquement la valeur et pas le champ (créé un input hidden)
     * @param string     $cOrientation          Orientation h=horizontal, v=vertical
     * @param string     $strType
     * @param bool       $bFormOnly             (option) Génération du champ uniquement, sans libellé false par défaut
     * @param string     $strEvent              (option) Handler d'événements sur le champ : "" par défaut
     * @param string     $sInfoBull             (option) champs permettant d'afficher une info-bulle
     * @param array      $listOfPictosForLabels (option) array de picto pour les libellés
     *
     * @return string
     */
    public function _createBox($strName, $strLib, $dataValues, $aCheckedValues, $required, $options, $cOrientation, $strType, $bFormOnly = false, $strEvent = '', $sInfoBull = '', $listOfPictosForLabels = null)
    {
        $iconInfoBull = false;
        if (is_array($options) && isset($options['infoBull']) && !empty($options['infoBull'])) {
            $infoBull = $options['infoBull'];
            if (!empty($infoBull['isIcon'])) {
                $iconInfoBull = $infoBull['message'];
            }
        }
        $readOnly = (is_array($options)) ? $options['readOnly'] : $options;
        $disabled = (is_array($options)) ? $options['disabled'] : false;

        $strTmp = '';
        if ($sInfoBull) {
            $strLib = "<span title='".$sInfoBull."' style='cursor:help;'>".$strLib.'</span>';
        }
        if (!is_array($aCheckedValues)) {
            $aCheckedValues = array(1 => $aCheckedValues);
        }
        if (!is_array($dataValues)) {
            $dataValues = array(1 => $dataValues);
        }
        $strFieldName = $strName;
        if (($strType == 'checkbox') && (count($dataValues) > 1)) {
            $strFieldName .= '[]';
        }
        // Génération du couple libellé/champ
        if ($readOnly || $disabled) {
            if ($aCheckedValues == '') {
                $strTmp .= $this->createHidden($strFieldName, '0');
            } else {
                while ($ligne = each($aCheckedValues)) {
                    $strTmp .= $this->createHidden($strFieldName, $ligne['value'], true);
                }
            }
        }
        if (is_array($dataValues)) {
            if ($readOnly) {
                while ($ligne = each($dataValues)) {
                    if (in_array($ligne['key'], $aCheckedValues)) {
                        if ($ligne['value'] == '') {
                            $strTmp .= ' '.t('FORM_MSG_YES').' ';
                        } else {
                            $strTmp .= $ligne['value'].' ';
                        }
                        if ($cOrientation == 'v') {
                            $strTmp .= Pelican_Html::br();
                        }
                    }
                }
            } else {
                if (!$disabled) {
                    $this->countInputName($strFieldName);
                }
                while ($ligne = each($dataValues)) {
                    $params = array();
                    $params['type'] = $strType;
                    $params['name'] = $strFieldName;
                    $params['id'] = $strFieldName;
                    $params['value'] = str_replace('"', '&quot;', $ligne['key']);
                    if ($disabled) {
                        $params['disabled'] = 'disabled';
                    }
                    if (in_array($ligne['key'], $aCheckedValues)) {
                        $params['checked'] = 'checked';
                    }
                    $strTmp .= Pelican_Html_Form::addInputEvent(Pelican_Html::input($params), $strEvent);
                    $strTmp .= Pelican_Html::nbsp().$ligne['value'];
                    if ($cOrientation == 'v') {
                        $strTmp .= Pelican_Html::br();
                    }
                }
            }
        }

        if (!$bFormOnly) {
            $strTmp = Pelican_Html_Form::get($strLib, $strTmp, $required, $readOnly, $this->sStyleLib, $this->sStyleVal, 'top', '', $this->_sFormDisposition);
            if ($iconInfoBull) {
                $addon = '';
                if ($required) {
                    $addon = ' *';
                }
                $strLibInfoBull = Pelican_Text::htmlentities($strLib).$addon.' '.Backoffice_Tooltip_Helper::help($iconInfoBull);
                $strTmp = str_replace($strLib.$addon, $strLibInfoBull, $strTmp);
            }
            $strTmp = $this->testAndSetPictoFoxCreateBox($strTmp, $listOfPictosForLabels, $dataValues);
        }

        $this->_createBoxJs($readOnly, $strType, $strName, $strLib, $dataValues, $required);

        return $this->output($strTmp);
    }

    /**
     * @param bool   $readOnly
     * @param string $strType
     * @param string $strName
     * @param string $strLib
     * @param array  $dataValues
     * @param bool   $required
     */
    protected function _createBoxJs($readOnly, $strType, $strName, $strLib, $dataValues, $required)
    {
        // Génération de la fonction js de vérification.
        if (!$readOnly && is_array($dataValues) && $required) {
            if (($strType == 'checkbox') && (count($dataValues) > 1)) {
                $this->_sJS .= 'o = obj.elements["'.$strName."[]\"];\n";
            } else {
                $this->_sJS .= 'o = obj.elements["'.$strName."\"];\n";
            }
            $this->_sJS .= "if(typeof o != 'undefined'){ \n";
            if (count($dataValues) > 1) {
                $this->_sJS .= "bChecked = false;\n";
                $this->_sJS .= "for (i=0; i < o.length; i++)\n";
                $this->_sJS .= "if ( o[i].checked )\n";
                $this->_sJS .= "bChecked = true;\n";
                $this->_sJS .= "if (!bChecked  && !$(o).parents(\"tbody\").hasClass(\"isNotRequired\") ) {\n";
            } else {
                $this->_sJS .= "if (!o.checked  && !$(o).parents(\"tbody\").hasClass(\"isNotRequired\")  ) {\n";
            }
            $this->_sJS .= 'alert("'.t('FORM_MSG_VALUE_CHOOSE').' \\"'.strip_tags(str_replace('"', '\\"', $strLib)).'\\".");'."\n";
            $this->_sJS .= "return false;\n";
            $this->_sJS .= "}}\n";
        }
    }

    /**
     * Génère des checkbox à partir d'une série de valeurs.
     *
     *
     * @param string $strName        Nom du champ
     * @param string $strLib         Libellé du champ
     * @param mixed  $aDataValues    (option) Tableau de valeurs (id=>lib) : "" par
     *                               défaut
     * @param mixed  $aCheckedValues (option) Liste des valeurs cochées (liste des id)
     *                               : "" par défaut
     * @param bool   $bRequired      (option) Champ obligatoire : false par défaut
     * @param bool   $bReadOnly      (option) Affiche uniquement la valeur et pas le champ
     *                               (créé un input hidden) : false par défaut
     * @param string $cOrientation   (option) Orientation h=horizontal, v=vertical : "h"
     *                               par défaut
     * @param bool   $bFormOnly      (option) 
     * @param string $strEvent       (option) 
     * @param string $sInfoBull      (option) champs permettant d'afficher une info-bulle
     *
     * @return string
     */
    public function createCheckBoxFromList($strName, $strLib, $aDataValues = '', $aCheckedValues = '', $bRequired = false, $bReadOnly = false, $cOrientation = 'h', $bFormOnly = false, $strEvent = '', $sInfoBull = '')
    {
        return $this->_createBox($strName, $strLib, $aDataValues, $aCheckedValues, $bRequired, $bReadOnly, $cOrientation, 'checkbox', $bFormOnly, $strEvent, $sInfoBull);
    }

    /**
     * Génère un contrôle de type combo.
     *
     *
     * @param string   $strName           Nom du champ
     * @param string   $strLib            Libellé du champ
     * @param mixed    $aDataValues       Tableau de valeurs (id=>lib)
     * @param mixed    $aSelectedValues   Tableau des valeurs sélectionnées (liste des
     *                                    id)
     * @param bool     $bRequired         Champ obligatoire
     * @param array    $options           bool (readOnly) /
     *                                    array(
     *                                    'readOnly' => bool,
     *                                    'infoBull' => array(
     *                                    'isIcon' => bool ,
     *                                    'message' => string
     *                                    )
     * @param mixed $iSize             
     * @param bool     $bMultiple         Sélection multiple
     * @param string   $iWidth            Largeur du contrôle
     * @param bool     $bChoisissez       Affiche le message "->Choisissez" en début de liste
     * @param bool     $bEnableManagement (option) Accès à la popup d'ajout dans la
     *                                    table de référence : false par défaut
     * @param bool     $bFormOnly         (option) Génération du champ uniquement, sans libellé
     *                                    : false par défaut
     * @param string   $strTableName      (option) Nom de la table pour les valeurs sans
     *                                    $this->_sTablePrefix : "" par défaut
     * @param string   $strEvent          (option) événement et fonction javascript "" par
     *                                    défaut. ex : onChange="javascript:functionAExecuter();"
     * @param string   $sSearchQueryName  (option) Nom de la variable de session
     *                                    contenant la requête pour filtrer la combo Dans ce cas, une
     *                                    Pelican_Index_Frontoffice_Zone de saisie avec bouton de recherche s'affiche à
     *                                    droite.
     * @param bool     $bDelManagement    (option) 
     * @param bool     $bUpdManagement    (option) 
     * @param string   $sInfoBull         (option) champs permettant d'afficher une info-bulle
     *
     * @return string
     */
    public function _createCombo($strName, $strLib, $aDataValues, $aSelectedValues, $bRequired, $options, $iSize, $bMultiple, $iWidth, $bChoisissez, $bEnableManagement = false, $bFormOnly = false, $strTableName = '', $strEvent = '', $sSearchQueryName = '', $bDelManagement = false, $bUpdManagement = false, $sInfoBull = '')
    {
        $iconInfoBull = false;
        $showAll = false;
        if (is_array($options) && isset($options['infoBull']) && !empty($options['infoBull'])) {
            $infoBull = $options['infoBull'];
            if (!empty($infoBull['isIcon'])) {
                $iconInfoBull = $infoBull['message'];
                $infoBull = false;
            }
        }
        $bReadOnly = (is_array($options)) ? $options['readOnly'] : $options;

        $strTmp = '';
        if ($sInfoBull) {
            $strLib = "<span title='".$sInfoBull."' style='cursor:help;'>".$strLib.'</span>';
        }
        if (!is_array($aSelectedValues)) {
            $aSelectedValues = array($aSelectedValues);
        }
        $strFieldName = $strName;
        if ($bMultiple) {
            $strFieldName .= '[]';
        }
        if ($bReadOnly) {
            $this->countInputName($strFieldName);
            while ($ligne = each($aSelectedValues)) {
                $params = array();
                $params[type] = 'hidden';
                $params[name] = $strFieldName.($bMultiple ? '[]' : '');
                $params[value] = str_replace('"', '&quot;', $ligne['value']);
                $strTmp .= Pelican_Html::input($params);
            }
        }
        // Génération du couple libellé/champ
        if (!$bReadOnly) {
            $this->countInputName($strFieldName);
            $params = array();
            $params['name'] = $strFieldName;
            $params['id'] = $strFieldName;

            /* add extra data like 'data-name, data-value, ...' */
            if(isset( $options['attribute'])){
                self::insertAttributeForField($options['attribute'], $params);
            }

            $params['size'] = ($iSize ? $iSize : '1');
            if ($bMultiple) {
                $params['multiple'] = 'multiple';
            }
            if ($iWidth) {
                $params['style'] = 'width:'.$iWidth.'px;';
            }
            if ($bChoisissez && !$bMultiple) {
                if ($bChoisissez === true) {
                    $aOptions[] = Pelican_Html::option(array('value' => ''), t('FORM_SELECT_CHOOSE'));
                } else {
                    $aOptions[] = Pelican_Html::option(array('value' => ''), $bChoisissez);
                }
            }
            if (is_array($aDataValues)) {
                //
                foreach ($aDataValues as $key => $value) {
                    if (is_array($value)) {
                        foreach ($value as $key2 => $value2) {
                            $selected = '';
                            if (in_array($key2, $aSelectedValues)) {
                                $selected = 'selected';
                            }
                            $aOptions2[$key][] = Pelican_Html::option(array('value' => $key2,
                                'selected' => $selected, ), $value2);
                        }
                    } else {
                        $selected = '';
                        if (in_array($key, $aSelectedValues)) {
                            $selected = 'selected';
                        }
                        $aOptions[] = Pelican_Html::option(array('value' => $key, 'selected' => $selected), $value);
                    }
                }
                if (isset($aOptions2)) {
                    foreach ($aOptions2 as $group => $options) {
                        $aOptions[] = Pelican_Html::optgroup(array('label' => Pelican_Text::htmlentities($group)), implode('', $options));
                    }
                }
            }
            $strTmp .= Pelican_Html_Form::addInputEvent(Pelican_Html::select($params, @implode('', $aOptions)), $strEvent, 'select');
            if ($sSearchQueryName) {
                // Elements pour filtre de la combo
                $strTmp .= Pelican_Html::input(array('type' => 'text', name => 'iSearchVal'.$strName,
                    'size' => '14', 'onkeyDown' => "submitIndexation('".$this->_sLibPath.$this->_sLibForm."/', '','".base64_encode($sSearchQueryName)."', true, ".($bChoisissez ? 'true' : 'false').');', ));
                $strTmp .= Pelican_Html::input(array('type' => 'button', 'class' => 'button',
                        'name' => 'bSearch'.$strName,
                        'value' => t('FORM_BUTTON_SEARCH'), 'onclick' => "searchIndexation('".$this->_sLibPath.$this->_sLibForm."/', '".$strName."', '', eval('this.form.iSearchVal".$strName.".value'),'".base64_encode($this->sFormName.'_'.$strName)."',".($showAll ? 1 : 0).');', )).Pelican_Html::br();
            }
        } else {
            if (is_array($aDataValues)) {
                foreach ($aDataValues as $key1 => $group) {
                    if (is_array($group)) {
                        foreach ($group as $key => $value) {
                            if (in_array($key, $aSelectedValues)) {
                                $strTmp .= $value.Pelican_Html::br();
                            }
                        }
                    } else {
                        if (in_array($key1, $aSelectedValues)) {
                            $strTmp .= $group.Pelican_Html::br();
                        }
                    }
                }
            }
        }
        // Lien vers popup de gestion de la table de référence
        if ($bEnableManagement && !$bReadOnly) {
            $this->_aIncludes['popup'] = true;
            $this->_aIncludes['list'] = true;
            $strTmp .= ' '.Pelican_Html::a(array('href' => 'javascript://', 'onclick' => "addRef('".$this->_sLibPath.$this->_sLibForm."/', 'document.".$this->sFormName."','".$strName."', '".$strTableName."', 1, true, 'add');"), t('FORM_BUTTON_ADD_VALUE'));
            if ($bUpdManagement && !$bReadOnly) {
                $strTmp .= Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp();
                $strTmp .= ' '.Pelican_Html::a(array('href' => 'javascript://', 'onclick' => "addRef('".$this->_sLibPath.$this->_sLibForm."/', 'document.".$this->sFormName."','".$strName."', '".$strTableName."', 1, true, 'upd');"), 'Update a value');
            }
            if ($bDelManagement && !$bReadOnly) {
                $strTmp .= Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp();
                $strTmp .= ' '.Pelican_Html::a(array('href' => 'javascript://', 'onclick' => "addRef('".$this->_sLibPath.$this->_sLibForm."/', 'document.".$this->sFormName."','".$strName."', '".$strTableName."', 1, true, 'del');"), 'Del a value');
            }
        }
        if (!$bFormOnly) {
            $strTmp = Pelican_Html_Form::get($strLib, $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, ((!$bChoisissez || $bMultiple) && !$bReadOnly ? 'top' : ''), '', $this->_sFormDisposition);
            if ($iconInfoBull) {
                $addon = '';
                if ($bRequired) {
                    $addon = ' *';
                }
                $strLibInfoBull = Pelican_Text::htmlentities($strLib).$addon.' '.Backoffice_Tooltip_Helper::help($iconInfoBull);
                $strTmp = preg_replace('/'.htmlentities($strLib).addcslashes($addon, '*').'/', $strLibInfoBull, $strTmp, 1);
            }
        }
        // Génération de la fonction js de vérification.
        if (!$bReadOnly && $bRequired) {
            if ($bMultiple) {
                $this->_sJS .= 'var o = obj.elements["'.$strName."[]\"];\n";
            } else {
                $this->_sJS .= 'var o = obj.elements["'.$strName."\"];\n";
            }
            $this->_sJS .= "if ( (o.selectedIndex == 0) && (o.options[o.selectedIndex].value == \"\") && !$(o).parents(\"tbody\").hasClass(\"isNotRequired\")  ) {\n";
            $this->_sJS .= 'alert("'.t('FORM_MSG_VALUE_CHOOSE').' \\"'.strip_tags(str_replace('"', '\\"', $strLib)).'\\".");'."\n";
            $this->_sJS .= "fwFocus(o);\n";
            $this->_sJS .= "return false;\n";
            $this->_sJS .= "}\n";
        }

        return $this->output($strTmp);
    }

    /**
     * this function helps to add extra data into form field
     *
     * @param $extraAttributeToInsert
     * @param $params
     */
    private function insertAttributeForField($extraAttributeToInsert, &$params ){

        foreach($extraAttributeToInsert as $key => $value){
            $params[$key] = $value;
        }

    }

    /**
     * Génère une combo à partir d'une requête SQL.
     *
     *
     * @param Pelican_Db $oConnection Objet connection à la base
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param mixed $strSQL (option) Requête SQL (id,lib)
     * @param string $aSelectedValues (option)
     * @param bool $bRequired (option) Champ obligatoire : false par défaut
     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
     *                                    (créé un input hidden)
     * @param mixed $iSize (option)
     * @param bool $bMultiple (option) Sélection multiple : false par défaut
     * @param string $iWidth (option) Largeur du contrôle : "" par défaut
     * @param bool $bChoisissez (option) Affiche le message "->Choisissez" en début
     *                                    de liste : true par défaut
     * @param bool $bFormOnly (option) Affiche uniquement les éléments du formulaire
     * @param string $strEvent (option)
     * @param array|string $arSearchFields (option) Liste des champs sur lesquels effectuer
     *                                    une recherche par like 0 : nom complet du champ id dont la(les) valeur(s)
     *                                    sélectionnée(s) est(sont) dans $aSelectedValues suivants : champ(s) sur
     *                                    le(s)quel(s) doit s'effectuer la recherche Dans ce cas, la combo ne contient que
     *                                    la (les) valeur(s) sélectionnée(s) et une Pelican_Index_Frontoffice_Zone de
     *                                    saisie avec bouton de recherche s'affiche à droite.
     * @param mixed $aBind (option)
     *
     * @param string $sInfoBull (option) champs permettant d'afficher une info-bulle
     * @return string
     */
    public function createComboFromSql($oConnection, $strName, $strLib, $strSQL = '', $aSelectedValues = '', $bRequired = false, $bReadOnly = false, $iSize = '1', $bMultiple = false, $iWidth = '', $bChoisissez = true, $bFormOnly = false, $strEvent = '', $arSearchFields = '', $aBind = array(), $sInfoBull = '')
    {
        $aDataValues = array();
        if (is_array($arSearchFields)) {
            $sFilter = '';
            if (!is_array($aSelectedValues)) {
                $aSelectedValues = array($aSelectedValues);
            }
            while (list(, $val) = each($aSelectedValues)) {
                if (strlen($sFilter) != 0) {
                    $sFilter .= ',';
                }
                $sFilter .= "'".str_replace("'", "''", $val)."'";
            }
            reset($aSelectedValues);
            $sFilter = $arSearchFields[0].' IN ('.$sFilter.')';
            if (stristr($strSQL, 'where ')) {
                $sFilter = preg_replace('/where /i', 'where '.$sFilter.' AND ', $strSQL);
            } elseif (stristr($strSQL, 'group by ')) {
                $sFilter = preg_replace('/group by /i', 'where '.$sFilter.' group by ', $strSQL);
            } elseif (stristr($strSQL, 'order by ')) {
                $sFilter = preg_replace('/order by /i', 'where '.$sFilter.' order by ', $strSQL);
            }
            $this->_getValuesFromSQL($sFilter, $aDataValues, $aBind);
            $sFilter = '';
            while (list(, $val) = each($arSearchFields)) {
                if (strlen($sFilter) != 0) {
                    $sFilter .= ' OR ';
                }
                $sFilter .= 'UPPER('.$val.") like UPPER('%:RECHERCHE:%')";
            }
            $sFilter = '('.$sFilter.')';
            if (stristr($strSQL, 'where ')) {
                $strSQL = preg_replace('/where /i', 'where '.$sFilter.' AND ', $strSQL);
            } elseif (stristr($strSQL, 'group by ')) {
                $strSQL = preg_replace('/group by /i', 'where '.$sFilter.' group by ', $strSQL);
            } elseif (stristr($strSQL, 'order by ')) {
                $strSQL = preg_replace('/order by /i', 'where '.$sFilter.' order by ', $strSQL);
            }
            $_SESSION['AssocFromSql_Search'][$this->sFormName.'_'.$strName] = $strSQL;
            $arSearchFields = $this->sFormName.'_'.$strName;
        } else {
            $this->_getValuesFromSQL($strSQL, $aDataValues, $aBind);
        }

        return $this->_createCombo($strName, $strLib, $aDataValues, $aSelectedValues, $bRequired, $bReadOnly, $iSize, $bMultiple, $iWidth, $bChoisissez, false, $bFormOnly, '', $strEvent, $arSearchFields, false, false, $sInfoBull);
    }

    /**
     * Affiche un couple Libellé/Valeur, non modifiable.
     *
     * Suppression de l'htmlentities sur $strLib par rapport a Pelican_Form
     *
     *
     * @param string $strLib Libellé du champ
     * @param string $strValue Valeur du champ
     * @param bool $bToggle (option)
     * @param string $strLib2 (option)
     *
     * @param array $options
     * @return string
     */
    public function createLabel($strLib, $strValue, $bToggle = false, $strLib2 = '', $options = array())
    {
        // Génération du couple libellé/champ
        $strTmp = '';

        $sStyleLib = $this->sStyleLib;
        $sStyleVal = $this->sStyleVal;

        if (!empty($options['class_lib'])) {
            $sStyleLib .= sprintf('%s %s', $this->sStyleLib, $options['class_lib']);
        }

        if (!empty($options['class_value'])) {
            $sStyleVal .= sprintf('%s %s', $this->sStyleVal, $options['class_value']);
        }

        if ($bToggle) {
            $id = 'lbl'.$strLib;
            $strTmp .= $this->showSeparator('formsep', false);
            $lib = Pelican_Html::img(array('id' => 'Toggle'.$id, 'src' => $this->_sLibPath.'/images/toggle_close.gif',
                'alt' => '', 'width' => 14, 'height' => 12, 'border' => 0, 'style' => 'cursor:pointer;',
                'onclick' => "showHideModule('".$id."')", 'align' => 'right', ));
            $lib .= $strLib;
            $strTmp .= Pelican_Html_Form::get($lib, Pelican_Html::nbsp().$strLib2, false, false, $this->sStyleLib, $this->sStyleVal, '', '', $this->_sFormDisposition);
            $strTmp .= Pelican_Html_Form::get(Pelican_Html::nbsp(), $strValue, false, false, $this->sStyleLib, $this->sStyleVal, 'top', '', $this->_sFormDisposition, array('id' => 'DivToggle'.$id, 'style' => 'display:none;'));
        } else {
            $strTmp = Pelican_Html_Form::get($strLib, $strValue, false, false, $sStyleLib, $sStyleVal, 'top', '', $this->_sFormDisposition);
        }

        return $this->output($strTmp);
    }


    /**
     * Appel à une mediathèque ou à une popup d'upload (gestion de fichiersde type
     * "image", "file" ou "flash" avec gestion ou non en base de données et
     * génération ou non de vignettes à la volée.
     *
     *
     * @param string $strName   Nom du champ
     * @param string $strLib    Libellé du champ
     * @param bool   $bRequired (option) Champ obligatoire : false par défaut
     * @param string $strValue  (option) Valeur du champ : "" par défaut
     * @param bool   $bReadOnly (option) Affiche uniquement la valeur et pas le champ
     *                          (créé un input hidden) : false par défaut
     * @param bool   $bFormOnly (option) Génération du champ uniquement, sans libellé
     *                          : false par défaut
     * @param string $ratio     nom du ratio
     *                          : false par défaut
     *                          : si c'est un tableau array(ratio, crop) , affiche un bouton de crop en plus,
     * @param array  $crops
     * @param array  $options
     *
     * @return string
     *                De type Array  :
     *                'isIcon' => Boolean
     *                'message'  => String
     */
    public function createNewImage($strName, $strLib, $bRequired = false, $strValue = '', $bReadOnly = false, $bFormOnly = false, $ratio, array $crops,  $options = [])
    {
        // Génération du couple libellé/champ
        $mediaHelper = new FormatHelper();
        $strTmp ='';
        if (!$bReadOnly) {
            $strTmp .= '<table cellpadding="0" cellspacing="0" border="0">';
            $strTmp .= '<tr>';
            $strTmp .= '<td width="2" id="div'.$strName.'" nowrap="nowrap">';
        }
        $strTmp .= $this->createHidden($strName, $strValue, true);

        $iconInfoBull = false;
        if (is_array($options)) {
            if (!empty($options['isIcon'])) {
                $iconInfoBull = $options['message'];
            }
        }

        // Récupération de la prévisualisation (vignette si l'option est choisie dans la config) et du chemin du fichier s'il existe
        $strPathValue = Pelican_Media::getMediaPath($strValue);

        // Nom du fichier
        $strFile = basename($strPathValue);
        // Chemin escapé
        $escapePath = str_replace($strFile, rawurlencode($strFile), $strPathValue);
        $_sThumbnailAbsPath = $escapePath;

        // hauteur max d'affichage
        $height = ' height="'.$this->_iHeightThumbnail.'"';
        $url = $this->_sUploadHttpPath.$escapePath;
        $dataOrigin = 'data-original="'.$url.'"';
        // Pour une image, on affiche une prévisualisation

        if (!$bReadOnly && !empty($crops)) {
            foreach ($crops as $labelCrop => $crop) {
                $cropId = $crop;
                $cropFormat = Pelican_Cache::fetch('Media/MediaFormat',
                    array(
                        'MEDIA_FORMAT_LABEL' => $crop,
                    )
                );
                if (isset($cropFormat['MEDIA_FORMAT_ID'])) {
                    $cropId = $cropFormat['MEDIA_FORMAT_ID'];
                }
                $attr = [];
                $url = $this->_sUploadHttpPath.Pelican_Media::getFileNameMediaFormat($_sThumbnailAbsPath, $cropId).'?autocrop=1&t='.time();
                $linkMedia = '';
                $display = 'none';
                if (!empty($strValue)) {
                    $attr['src'] = $url;
                    $attr['alt'] = $strFile;
                    $attr['height'] = $this->_iHeightThumbnail;
                    $linkMedia = Pelican_Html::img($attr);
                    $display = 'block';
                }
                $strTmp .= '<div class="crop-container" data-crop="'.$cropId.'" style="display:'.$display.'" >';
                $strTmp .= '<span>'.$labelCrop.'<br /><em>'. $mediaHelper->getFormatDimension($cropFormat).'</em></span>';
                $strTmp .= '<a id="imgdiv'.$strName.'-'.$cropId.'" '.$dataOrigin.' href="'.$url.'" target="_blank">'.$linkMedia.'</a>'.Pelican_Html::nbsp().Pelican_Html::nbsp();
                $strTmp .=  $this->addCropButton($cropId, $this->_sUploadHttpPath.$_sThumbnailAbsPath, 'imgdiv'.$strName.'-'.$cropId);
                $strTmp .= '</div>';
            }
        } else {
            $linkMedia = '<img src="'.$this->_sUploadHttpPath.$_sThumbnailAbsPath.'" style="border : 1px solid #CCCCCC" alt="'.str_replace(' ', Pelican_Html::nbsp(), $strFile).'" '.$height.' />';
            $strTmp .= '<a id="imgdiv'.$strName.'" '.$dataOrigin.' href="'.$url.'" target="_blank">'.$linkMedia.'</a>'.Pelican_Html::nbsp().Pelican_Html::nbsp();
        }

        $format = Pelican_Cache::fetch('Media/MediaFormat',
            array(
                'MEDIA_FORMAT_LABEL' => $ratio,
            )
        );

        if (!$bReadOnly) {
            $this->_aIncludes['popup'] = true;
            $strTmp .= '</td>';
            $strTmp .= '<td style="vertical-align:top;"><input type="button" class="button" value="'.t('FORM_BUTTON_ADD').'"';

            //cas ou le ratio est saisi alors on appel
            $strTmp .= " onclick=\"popupImageCrop(this.form.elements['".$strName."'], 'div".$strName."', ";
            $strTmp .= "'".str_replace('/', '\/', $this->_sUploadHttpPath.'/')."'";
            //ajout du paramètre avec la valeur du ratio
            if ($ratio && isset($format['MEDIA_FORMAT_ID'])) {
                $strTmp .= ",'".$format['MEDIA_FORMAT_ID']."'";
            }
            $strTmp .= ');" />';

            $strTmp .= Pelican_Html::nbsp().'<input type="button" class="button" value="'.t('FORM_BUTTON_FILE_DELETE').'"';
            $strTmp .= " onclick=\"if(confirm('".t('FORM_MSG_CONFIRM_DEL')."')) {this.form.elements['".$strName."'].value=''; $('#div".$strName." .crop-container ').hide() = '';}\" />";

            $strTmp .= '<br />'.$mediaHelper->getFormatInformation($format);

            $strTmp .= '</td>';
            $strTmp .= "</tr>\n";
            $strTmp .= "</table>\n";
            // Génération de la fonction js de vérification.
            if ($bRequired) {
                $this->_aIncludes['text'] = true;
                $escapedName = str_replace('[', '\\\[', $strName);
                $escapedName = str_replace(']', '\\\]', $escapedName);
                $this->_sJS .= 'if (isNaN(parseInt($("#'.$escapedName.'").val())) && !$("#'.$escapedName.'").parents("tbody").hasClass("isNotRequired")) {'."\n";
                $this->_sJS .= 'alert("'.t('FORM_MSG_VALUE_FILE').' \\"'.strip_tags(str_replace('"', '\\"', $strLib)).'\\".");'."\n";
                $this->_sJS .= "return false;\n";
                $this->_sJS .= "}\n";
            }
        }
        if (!$bFormOnly) {
            $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, 'top', '', $this->_sFormDisposition);
            if ($iconInfoBull) {
                $addon = '';
                if ($bRequired) {
                    $addon = ' *';
                }
                $strLibInfoBull = Pelican_Text::htmlentities($strLib).$addon.' '.Backoffice_Tooltip_Helper::help($iconInfoBull);
                $strTmp = preg_replace('/'.htmlentities($strLib).addcslashes($addon, '*').'/', $strLibInfoBull, $strTmp, 1);
            }
            if (isset($options['data-template'])) {
                $strTmp = str_replace('<input', "<input data-template='".$options['data-template']."' ", $strTmp);
            }
        }

        return $this->output($strTmp);
    }

    /**
     * Appel à une mediathèque ou à une popup d'upload (gestion de fichiersde type
     * "image", "file" ou "flash" avec gestion ou non en base de données et
     * génération ou non de vignettes à la volée.
     *
     *
     * @param string     $strName           Nom du champ
     * @param string     $strLib            Libellé du champ
     * @param bool       $bRequired         (option) Champ obligatoire : false par défaut
     * @param string     $strType           (option) Type de fichier (image, file ou flash) :
     *                                      "image" par défaut
     * @param string     $strSubFolder      (option) Sous-répertoire de départ (chemin
     *                                      relatif par rapport au répertoire d'upload) : "" par défaut
     * @param string     $strValue          (option) Valeur du champ : "" par défaut
     * @param bool       $bReadOnly         (option) Affiche uniquement la valeur et pas le champ
     *                                      (créé un input hidden) : false par défaut
     * @param bool       $bLibrary          (option) Utilisation de la Pelican_Media library (true)
     *                                      ou d'une popup d'upload (false) : true par défaut
     * @param bool       $bFormOnly         (option) Génération du champ uniquement, sans libellé
     *                                      : false par défaut
     * @param bool|float $ratio             (option) valeur du ratio
     *                                      : false par défaut
     *                                      : si c'est un tableau array(ratio, crop) , affiche un bouton de crop en plus,
     * @param string     $ratioHelp         : texte indiquant des précisions sur le format attendu (ex: dimensions d'une image)
     * @param bool       $generiquePerso
     * @param string     $strValueGenerique
     * @param array      $infoBull
     *
     * @return string
     *
     */
    public function createMedia($strName, $strLib, $bRequired = false, $strType = 'image', $strSubFolder = '', $strValue = '', $bReadOnly = false, $bLibrary = true, $bFormOnly = false, $ratio = false, $ratioHelp = null, $generiquePerso = false, $strValueGenerique = '', $infoBull = [])
    {
        $aAllowedExtensions = Pelican_Media::getAllowedExtensions();
        $mediaHelper = new FormatHelper();
        // Génération du couple libellé/champ
        $strTmp = $this->createHidden($strName, $strValue);
        if (!$bReadOnly) {
            $strTmp .= '<table cellpadding="0" cellspacing="0" border="0">';
            $strTmp .= '<tr>';
            $strTmp .= '<td width="2" id="div'.$strName.'" nowrap="nowrap">';
        }

        $iconInfoBull = false;
        if (is_array($infoBull)) {
            if (!empty($infoBull['isIcon'])) {
                $iconInfoBull = $infoBull['message'];
                $infoBull = false;
            }
        }
        $crop = '';
        if (is_array($ratio)) {
            $crop = $ratio[1];
            $ratio = $ratio[0];
        }

        // Récupération de la prévisualisation (vignette si l'option est choisie dans la config) et du chemin du fichier s'il existe
        $strPathValue = $strValue;

        $iYoutubeId = '';
        $streamlikeId = '';
        if (($strType == 'video' || $strType == 'streamlike' || $strType == 'youtube') && $strValue) {
            $media = Pelican_Cache::fetch('Media/Detail', array($strValue));
            if ($media['YOUTUBE_ID'] != '') {
                //$strType = 'youtube';
                $iYoutubeId = $media['YOUTUBE_ID'];
            } elseif ($media['MEDIA_TYPE_ID'] == 'streamlike') {
                $streamlikeId = $media['MEDIA_REMOTE_ID'];
            }
        }

        if ($strType == 'youtube' || ($strType == 'video' && $iYoutubeId != '')) {
            if ($iYoutubeId) {
                try {
                    $details = Pelican_Cache::fetch('Service/Youtube', array('id', $iYoutubeId, date('M-d-Y', mktime())));
                } catch (\fkooman\OAuth\Client\Exception\ClientConfigException $ex) {
                    $GLOBALS['flash_message'][] = array('message' => t('ERROR_YOUTUBE_OAUTH_CONFIG'),
                        'type' => 'error',);
                }
            }
            $_sThumbnailAbsPath = $details['path'];
            $strFile = $details['path'];
        } elseif ($strType == 'streamlike' || ($strType == 'video' && $streamlikeId != '')) {
            if ($streamlikeId) {
                $streamlikeConfig = Service_Streamlike::getStreamlikeConfig($_SESSION[APP]['SITE_ID']);
                $cachetime = Service_Streamlike::getStreamlikeCachetime($streamlikeConfig['STREAMLIKE_CACHETIME']);

                $details = Pelican_Cache::fetch('Service/Streamlike', array(
                    'id',
                    null,
                    $streamlikeId,
                    null,
                    null,
                    $cachetime,
                ));
            }
            $strFile = $details['url'];
        } elseif (Pelican::$config['FW_MEDIA_TABLE_NAME'] && $strValue) {
            $strPathValue = Pelican_Media::getMediaPath($strValue);
        }
        if ($strPathValue) {
            // Nom du fichier
            $strFile = basename($strPathValue);
            // Infos du fichier
            $aPathInfo = pathinfo($strFile);
            // Chemin escapé
            $escapePath = str_replace($strFile, rawurlencode($strFile), $strPathValue);
            $_sThumbnailAbsPath = $escapePath;
        }
        // hauteur max d'affichage
        $height = ' height="'.$this->_iHeightThumbnail.'"';
        // type défini à partir du nom de fichier existant
        $strTypePrecis = $strType;
        if (isset($aPathInfo)) {
            if (isset($aAllowedExtensions['image'][$aPathInfo['extension']])) {
                $strTypePrecis = 'image';
            }
        }
        $url = $this->_sUploadHttpPath.$escapePath;
        $dataOrigin = '';
        if (isset($strFile)) {
            // Pour une image, on affiche une prévisualisation
            if ($strTypePrecis == 'image') {
                $linkMedia = '<img src="'.$this->_sUploadHttpPath.$_sThumbnailAbsPath.'" style="border : 1px solid #CCCCCC" alt="'.str_replace(' ', Pelican_Html::nbsp(), $strFile).'" '.$height.' />';
                if (!empty($crop)) {
                    $linkMedia = '<img src="'.$this->_sUploadHttpPath.Pelican_Media::getFileNameMediaFormat($_sThumbnailAbsPath, $crop).'?autocrop=1" style="border : 1px solid #CCCCCC" alt="'.str_replace(' ', Pelican_Html::nbsp(), $strFile).'" '.$height.' />';
                    $dataOrigin = 'data-original="'.$url.'"';
                    $url = $this->_sUploadHttpPath.Pelican_Media::getFileNameMediaFormat($_sThumbnailAbsPath, $crop).'?autocrop=1';
                }
            } elseif ($strTypePrecis == 'youtube' || ($strType == 'video' && $iYoutubeId != '')) {
                $linkMedia = '<img src="'.$details['path'].'" style="border : 1px solid #CCCCCC" alt="'.str_replace(' ', Pelican_Html::nbsp(), $strFile).'" '.$height.' />';
                $url = $details['url'];
            } elseif ($strTypePrecis == 'streamlike' || ($strType == 'video' && $streamlikeId != '')) {
                $linkMedia = '<img src="'.$details['path'].'" style="border : 1px solid #CCCCCC" alt="'.str_replace(' ', Pelican_Html::nbsp(), $strFile).'" '.$height.' />';
                $url = $details['url'];
            } else {
                $linkMedia = str_replace(' ', Pelican_Html::nbsp(), $strFile);
            }

            $strTmp .= '<a id="imgdiv'.$strName.'" '.$dataOrigin.' href="'.$url.'" target="_blank">'.$linkMedia.'</a>'.Pelican_Html::nbsp().Pelican_Html::nbsp();
        }

        $format = array();
        if ($ratio) {
            $format = Pelican_Cache::fetch('Media/MediaFormat',
                array(
                    'MEDIA_FORMAT_LABEL' => $ratio,
                )
            );
        }

        if (!$bReadOnly) {
            $this->_aIncludes['popup'] = true;
            $strTmp .= '</td>';
            if (is_array($strType)) {
                $strTmp .= '<td>';
                foreach ($strType as $type) {
                    //C'est ici que sont crées les boutons add
                    $strTmp .= '<input type="button" class="button" value="'.t('FORM_BUTTON_ADD').' '.($type == 'image' ? 'une ' : 'un ').$type.'"';

                    //cas ou le ratio est saisi alors on appel
                    if (!$ratio) {
                        $strTmp .= " onclick=\"popupMedia(this, '".$type."', '".$this->_sLibPath.Pelican::$config['LIB_MEDIA']."/', this.form.elements['".$strName."'], 'div".$strName."', '";
                    } else {
                        $strTmp .= " onclick=\"popupMediaRatio('".$type."', '".$this->_sLibPath.Pelican::$config['LIB_MEDIA']."/', this.form.elements['".$strName."'], 'div".$strName."', '";
                    }

                    if ($strSubFolder != '') {
                        $strTmp .= $strSubFolder;
                    }
                    $strTmp .= "','".str_replace('/', '\/', $this->_sUploadHttpPath.'/')."',''";
                    if ($bLibrary) {
                        $strTmp .= ',true';
                    }

                    //ajout du paramètre avec la valeur du ratio
                    if ($ratio) {
                        if ($format['MEDIA_FORMAT_ID']) {
                            $strTmp .= ",'".$format['MEDIA_FORMAT_ID']."'";
                        }
                    }
                    $strTmp .= ');" />&nbsp;';
                }
            } else {
                $strTmp .= '<td style="vertical-align:top;"><input type="button" class="button" value="'.t('FORM_BUTTON_ADD').'"';

                //cas ou le ratio est saisi alors on appel
                if (!$ratio) {
                    $strTmp .= " onclick=\"popupMedia(this, '".$strType."', '".$this->_sLibPath.Pelican::$config['LIB_MEDIA']."/', this.form.elements['".$strName."'], 'div".$strName."', '";
                } else {
                    $strTmp .= " onclick=\"popupMediaRatio('".$strType."', '".$this->_sLibPath.Pelican::$config['LIB_MEDIA']."/', this.form.elements['".$strName."'], 'div".$strName."', '";
                }
                if ($strSubFolder != '') {
                    $strTmp .= $strSubFolder;
                }
                $strTmp .= "','".str_replace('/', '\/', $this->_sUploadHttpPath.'/')."',''";
                if ($bLibrary) {
                    $strTmp .= ',true';
                }

                //ajout du paramètre avec la valeur du ratio
                if ($ratio && isset($format['MEDIA_FORMAT_ID'])) {
                    $strTmp .= ",'".$format['MEDIA_FORMAT_ID']."'";
                }
                $strTmp .= ');" />';
            }
            $strTmp .= Pelican_Html::nbsp().'<input type="button" class="button" value="'.t('FORM_BUTTON_FILE_DELETE').'"';
            $strTmp .= " onclick=\"if(confirm('".t('FORM_MSG_CONFIRM_DEL')."')) {this.form.elements['".$strName."'].value=''; document.getElementById('div".$strName."').innerHTML = '';}\" />";
            if ($strType == 'image' && !empty($crop)) {
                $strTmp .=  $this->addCropButton($crop, $this->_sUploadHttpPath.$_sThumbnailAbsPath, 'imgdiv'.$strName);
            }

            if ($generiquePerso) {
                $strTmp .= '<input type="checkbox" value="1"  name="'.$strName.'_GENERIQUE" id="'.$strName.'_GENERIQUE" '.($strValueGenerique == 1 ? 'checked' : '').'/>'.Pelican_Html::nbsp().'<label>'.t('VISUELS_PREFERRED_PRODUCT').'</label>';
            }

            //ratio attendu
            if ($ratio) {
                $strTmp .= $mediaHelper->getFormatInformation($format);
            }

            $strTmp .= '</td>';
            $strTmp .= "</tr>\n";
            $strTmp .= "</table>\n";
            // Génération de la fonction js de vérification.
            if ($bRequired) {
                $this->_aIncludes['text'] = true;
                if ($generiquePerso) {
                    $this->_sJS .= "if ( isBlank(obj['".$strName."'].value) &&  !obj['".$strName."_GENERIQUE'].checked ) {\n";
                } else {
                    $escapedName = str_replace('[', '\\\[', $strName);
                    $escapedName = str_replace(']', '\\\]', $escapedName);
                    $this->_sJS .= 'if ( isBlank(document.getElementById("div'.$strName.'").innerHTML) && !$("#div'.$escapedName."\").parents(\"tbody\").hasClass(\"isNotRequired\")) {\n";
                }
                $this->_sJS .= 'alert("'.t('FORM_MSG_VALUE_FILE').' \\"'.strip_tags(str_replace('"', '\\"', $strLib)).'\\".");'."\n";
                $this->_sJS .= "return false;\n";
                $this->_sJS .= "}\n";
            }
        }
        if (!$bFormOnly) {
            $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, 'top', '', $this->_sFormDisposition);
            if ($iconInfoBull) {
                $addon = '';
                if ($bRequired) {
                    $addon = ' *';
                }
                $strLibInfoBull = Pelican_Text::htmlentities($strLib).$addon.' '.Backoffice_Tooltip_Helper::help($iconInfoBull);
                $strTmp = preg_replace('/'.htmlentities($strLib).addcslashes($addon, '*').'/', $strLibInfoBull, $strTmp, 1);
            }
        }

        return $this->output($strTmp);
    }

    protected function addCropButton($ratio, $image, $id)
    {
        $attr = [
            'href' => 'javascript:void(0)',
            'data-path' => Pelican::$config['MEDIA_LIB_PATH'],
            'data-format' => $ratio,
            'data-src' => $image,
            'data-caller-id' => $id,
            'class' => 'js-media-editor',
        ];

        $code = '<a '.Pelican_Html::_attr($attr).' >
                    <img src="/library/Pelican/Media/public/images/tool.gif" alt="Modifier l\'image" border="0" align="middle">
               </a>';

        return $code;
    }

    /**
     * Manipulation des données issues d'un POST pour créer une entrée associée à
     * chaque instance de l'objet multiple.
     *
     * Un tableau de type Array(PREFIXE_CHAMP1_1, PREFIXE_CHAMP1_2, PREFIXE_CHAMP2_2)
     * crée un tableaau du type Array(1=>CHAMP1,2=>(CHAMP1, CHAMP2))
     *
     *
     * @param string $strName    Identifiant de l'objet défini dans le createMulti
     * @param string $strPrefixe (option) Préfixe urilisé pour les nom de champs de
     *                           l'objet multiple : "multi" par défaut
     */
    public static function readMulti($strName, $strPrefixe = 'multi')
    {

        if ($strPrefixe) {
            if (isset($_POST['count_multi'.(Pelican_Db::$values['page'] - 1).'_'.$strPrefixe])) {
                Pelican_Db::$values['count_'.$strPrefixe] = $_POST['count_multi'.(Pelican_Db::$values['page'] - 1).'_'.$strPrefixe];
            }
            if (isset($_POST['count_multiZone'.Pelican_Db::$values['AREA_ID'].'_'.(Pelican_Db::$values['DB_INDEX']).'_'.$strPrefixe])) {
                Pelican_Db::$values['count_'.$strPrefixe] = $_POST['count_multiZone'.Pelican_Db::$values['AREA_ID'].'_'.(Pelican_Db::$values['DB_INDEX']).'_'.$strPrefixe];
            }
        }
        $DELETE = array();
        $longueur = strlen($strPrefixe);
        $count = (Pelican_Db::$values['count_'.$strName] + 1);

	$supp = 0;
	for ($j = 0; $j < $count + $supp; ++$j) {
	    if (isset(Pelican_Db::$values[$strPrefixe.$j.'_multi_display']) && !Pelican_Db::$values[$strPrefixe.$j.'_multi_display']) {
		++$supp;
	    }
        }
	foreach (Pelican_Db::$values as $key => $value) {
	    if (substr($key, 0, $longueur) == $strPrefixe) {
		for ($j = 0; $j < $count + $supp; ++$j) {
		    if (substr($key, 0, ($longueur + strlen($j) + 1)) == $strPrefixe.$j.'_') {
		        $field = str_replace($strPrefixe.$j.'_', '', $key);
		        // gestion des noms de champs commencant par un [
		        if (empty($field) && is_array($value)) {
		           Pelican_Db::$values[$strName][$j] = array_merge(Pelican_Db::$values[$strName][$j], $value);
		        }
		        if ($field == 'multi_display' && !$value) {
		            $DELETE[$j] = true;
		            unset(Pelican_Db::$values[$strName][$j]);
		        }
		        if (!valueExists($DELETE, $j) && !empty($field)) {
		            Pelican_Db::$values[$strName][$j][$field] = $value;
		        }
		    }
		    if (!valueExists($DELETE, $j) && Pelican_Db::$values['increment_'.$strName] != '') {
		        Pelican_Db::$values[$strName][$j][Pelican_Db::$values['increment_'.$strName]] = ($j + 1);
		    }
                }
	    }
        }
        
    }

    /**
     * mise à true de la variable _bUseMulti.
     */
    public function setMulti()
    {
        $this->_bUseMulti = true;
    }

    /**
     * retourne la valeur de la variable _bUseMulti.
     */
    public function getUseMulti()
    {
        return $this->_bUseMulti;
    }

    /**
     * .
     *
     *
     * @param string   $strName         Nom du champ
     * @param string   $strLib          Libellé du champ
     * @param bool     $bRequired       (option) Champ obligatoire : false par défaut
     * @param string   $googleKey       (option) 
     * @param string   $strAddressValue (option) 
     * @param string   $strLatValue     (option) 
     * @param string   $strLongValue    (option) 
     * @param bool     $bReadOnly       (option) Affiche uniquement la valeur et pas le champ
     *                                  (créé un input hidden) : false par défaut
     * @param bool     $bFormOnly       (option) Génération du champ uniquement, sans libellé
     *                                  : false par défaut
     * @param string   $strEvent        (option) Handler d'événements sur le champ : "" par
     *                                  défaut
     * @param mixed $width           (option) 
     * @param mixed $height          (option) 
     *
     * @return string
     */
    public function createMapPremium($strName, $strLib, $bRequired = false, $googleKey = '', $strAddressValue = '', $strLatValue = '', $strLongValue = '', $bReadOnly = false, $bFormOnly = false, $strEvent = '', $width = '470', $height = '200')
    {
        $directOutput = $this->bDirectOutput;
        $this->bDirectOutput = false;
        if ($googleKey) {
            //$strTmp .= Pelican_Html::script(array(src => "http://maps.google.com/maps?file=api&amp;v=3&amp;sensor=true&amp;key=" . $googleKey));
            $strTmp = Pelican_Html::script(array('src' => 'https://maps.googleapis.com/maps/api/js?client='.$googleKey.'&amp;sensor=true&amp;libraries=places'));
            $strTmp .= $this->createHidden($strName, null);
            $strTmp .= $this->createHidden($strName.'_ADDRESS_HIDDEN', $strAddressValue);
            $strTmp .= $this->createHidden($strName.'_ADDRESS_HIDDEN_LAT', $strLatValue);
            $strTmp .= $this->createHidden($strName.'_ADDRESS_HIDDEN_LONG', $strLongValue);
            $strTmp .= Pelican_Html::label('Latitude'.($bRequired && !$bReadOnly ? ' '.REQUIRED : '').' : ').$this->createInput($strName.'_LATITUDE', 'Latitude', 20, 'float', $bRequired, $strLatValue, $bReadOnly, 20, true, $strEvent);
            $strTmp .= Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp();
            $strTmp .= Pelican_Html::label('Longitude'.($bRequired && !$bReadOnly ? ' '.REQUIRED : '').' : ').$this->createInput($strName.'_LONGITUDE', 'Longitude', 20, 'float', $bRequired, $strLongValue, $bReadOnly, 20, true, $strEvent);
            $strTmp .= Pelican_Html::br();
            $divMap = Pelican_Html::div(array('id' => $strName.'_MAP', 'style' => 'width:'.$width.'px;height: '.$height.'px;'));
            $divSearch = $this->createInput($strName.'_ADDRESS', $strLib, 255, '', '', $strAddressValue, false, 35, true, $strEvent);
            $divSearch .= Pelican_Html::nbsp().$this->createbutton($strName.'_ADDRESS_BTN_FIND', t('FORM_BUTTON_SEARCH'), '');
            $divSearch .= Pelican_Html::nbsp().$this->createbutton($strName.'_ADDRESS_BTN_REST', 'Réinitialiser', 'javascript:void( null ); return false', true);
            //$divSearch .= Pelican_Html::nbsp().$this->createbutton( $strName . "_MYLOC", "Ma localisation", "javascript:void( null ); return false", true);
            $divSearch = Pelican_Html::div(array('style' => 'text-align:center;'), $divSearch);
            $strTmp .= Pelican_Html::div(array('style' => 'width:'.$width.'px;height: '.($height + 25).'px;border:#ccc 2px solid;background-color:#eee;margin-top:5px;'), $divMap.$divSearch);
        } else {
            $strTmp = Pelican_Html::div(array('class' => 'erreur', 'style' => 'widht:70%'), 'Veuillez insérer la clé Google fournie par le site  '.Pelican_Html::a(array(
                    'href' => 'http://code.google.com/intl/fr-FR/apis/maps/signup.html', ), 'Google Maps API'));
            $this->createHidden($strName.'_LATITUDE', $strLatValue);
            $this->createHidden($strName.'_LONGITUDE', $strLongValue);
        }
        if (!$bFormOnly) {
            $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, false, $bReadOnly, $this->sStyleLib, $this->sStyleVal, '', '', $this->_sFormDisposition);
        }
        $this->map[$strName] = $strName;
        $this->_aIncludes['mapv3'] = true;
        $this->bDirectOutput = $directOutput;

        return $this->output($strTmp);
    }

    /**
     * @return string
     */
    private function getLevelMultiHmvc()
    {
        $level = '';
        if (self::$level > 0) {
            $level = 'Niveau'.self::$level.'_';
        }

        return $level;
    }

    /**
     * Création d'un objet Multiple : répétition à volonté d'un bout de formulaire
     * avec ses contrôles de saisie.
     *
     * ATTENTION : inclure xt_mozilla_fonctions en tout premier (avant tout autre js)
     * pour pouvoir utiliser cette méthode avec Mozilla
     *
     *
     * @param string   $strName          Nom du champ
     * @param string   $strLib           Libellé du bouton ajouter - Peut etre de type Array :
     *                                   'multiTitle'       => String
     *                                   'multiAddButton'   => String
     *                                   'oneStrongLine'    => Boolean
     * @param array|string $call             
     * @param mixed    $tabValues        Tableau de données (de type queryTab)
     * @param string   $incrementField   Nom du champ servant à incrémenter les
     *                                   instances de l'objet
     * @param bool     $bReadOnly        (option) Affiche uniquement les valeurs et pas les
     *                                   champs : false par défaut
     * @param array|int  $intMinMaxIterations (option) Nombre maximum d'itérations autorisé :
     *                                   "" par défaut
     * @param bool     $bAllowDeletion   (option) Suppression d'instance autorisée ou non :
     *                                   true par défaut
     * @param bool     $bAllowAdd        (option) Ajout d'instance autorisé ou non : true par
     *                                   défaut
     * @param string   $strPrefixe       (option) Préfixe des noms de champ : "multi" par
     *                                   défaut
     * @param string   $line             (option) Nom du tableau de données utilisé par le
     *                                   formulaire parent : "values" par défaut
     * @param string   $strCss           (option) Classe CSS à utiliser : "multi" par défaut
     * @param string $sColspan         (option) 
     * @param string   $sButtonAddMulti  (option) Libellé du boutton ajouter du multi
     * @param string   $complement       (option) 
     * @param array    $options          (option) tableau de parametrage du hmvc pour limiter le nombre d'arguments à la fonction
     *                                   'noDragNDrop'       => BOOL
     *                                   'showNumberLabel'   => BOOL
     *                                   'numberLabel'       => string
     *                                   'forceValues'       => array
     *                                   'noSeparator'       => bool
     *
     * @return string
     */
    public function createMultiHmvc($strName, $strLib, $call, $tabValues, $incrementField, $bReadOnly = false, $intMinMaxIterations = null, $bAllowDeletion = true, $bAllowAdd = true, $strPrefixe = 'multi', $line = 'values', $strCss = 'multi', $sColspan = '2', $sButtonAddMulti = '', $complement = '', $isLabel = true, $options = [])
    {
        // Nécessite $multi, $values
        // ATTENTION : ajouter aux noms des champs
        // on annule temporairement le direct output s'il est défini
        // affichage d'un séparateur
        // affichage du bouton pour les ajouts multiples
        // $limit=limitFormTable("120", "520", false);
        // souvent utilisé : $readO

        ++self::$level;

        $level = $this->getLevelMultiHmvc();

        $strPrefixe .= empty($level) ? $level : '';

        $strTmp = '';
        $compteur = -1;
        $noDragNDrop = false;
        $oForm = &$this;
        $readO = $bReadOnly;
        $bDirectOutput = $oForm->bDirectOutput;
        $pageId = $_SESSION[APP]['PAGE_ID'];
        $pageVersion = $_SESSION[APP]['PAGE_VERSION'];
        $oForm->bDirectOutput = false;

        $intMaxIterations = $intMinMaxIterations;
        $intMinIterations = 0;
        if (!isset($options['showNumberLabel'])) {
            $options['showNumberLabel'] = true;
        }
        if (isset($options['noDragNDrop'])) {
            $noDragNDrop = $options['noDragNDrop'];
        }
        if (is_array($intMinMaxIterations)) {
            $intMaxIterations = $intMinMaxIterations[1];
            $intMinIterations = $intMinMaxIterations[0];

            $intNbValueMissing = $intMinIterations - count($tabValues);
            if ($intNbValueMissing > 0) {
                for ($i = 0; $i < $intNbValueMissing; ++$i) {
                    $tabValues[] = array();
                }
            }
            if ($intMaxIterations == 1) {
                $noDragNDrop = true;
            }
        }

        // ajout du controller
        if (!empty($call['path']) && file_exists($call['path'])) {
            include_once $call['path'];
        }
        //Decoupe avec les _
        $strCut = explode('_', $strName);
        if (!isset($options['noSeparator']) || (isset($options['noSeparator']) && $options['noSeparator'] != true)) {
            $strTmp .= $this->showSeparator('formsep', true, $sColspan);
        }
        $label = t(end($strCut));
        $oneStrongLine = false;
        if (is_array($strLib)) {
            if ($strLib['oneStrongLine']) {
                $oneStrongLine = $strLib['oneStrongLine'];
            }
            $label = $strLib['multiTitle'];
            $strLib = $strLib['multiAddButton'];
        }
        if ($isLabel) {
            $tmpLabel = $this->createLabel($label, '');
            if ($oneStrongLine) {
                $tmpLabel = $this->createComment($label);
            }
            $strTmp .= $tmpLabel;
        }
        $strName .= !empty($level) ? '_'.$level : '';

        $strTmp .= '<tr><td id="'.$strName.'_td" colspan="'.$sColspan.'" width="100%">';

        //Sauvegarde des vérification js existante
        $saveJS = $this->_sJS;
        $this->_sJS = '';

        //Sauvegarde du name de chaque Multi
        if ($strName != '') {
            $this->_aMultiTrackNames[] = $strName;
        }

        // sauvegarde les hiddens existant et vide le tableau
        $saveHidden = $this->form_class_hidden;
        $this->form_class_hidden = array();

        // Construction de subForm qui va servir au clone
        $strTmp .= '<table id="'.$strName.'_subForm" class="'.$strName.'_subForm multi" style="display:none">';
        $strTmp .= $this->headMultiHmvc($strName.'__CPT'.$level.'__', '__CPT1'.$level.'__', $readO, '', $options);
        $tmpValues = [];
        $tmpValues['PAGE_ID'] = $pageId;
        $tmpValues['PAGE_VERSION'] = $pageVersion;
        // diffusion des valeurs jusqu'au contenu du hmvc
        if (isset($options['forceValues'])) {
            $tmpValues = array_merge($tmpValues, $options['forceValues']);
        }

        $strTmp .= call_user_func_array(array($call['class'], $call['method']), array($oForm, $tmpValues,
            $bReadOnly, $strName.'__CPT'.$level.'___', ));

        //Ajout du footer (contient le bouton supprimer)
        if ($bAllowDeletion) {
            $strTmp .= $this->footerMultiHmvc($strName.'__CPT'.$level.'__', '__CPT1'.$level.'__', $readO, $options);
        }
        $strTmp .= $this->putHidden();
        $strTmp .= '</table>';

        // Remise en place des hidden
        $this->form_class_hidden = $saveHidden;

        // Remise en place des vérifications JS existante
        $strTmp .= $this->createHidden($strName.'_subFormJS', $this->_sJS, true, false, '', false);
        $this->_sJS = $saveJS;
        if (is_array($tabValues) && !empty($tabValues)) {
            foreach ($tabValues as $line) {
                ++$compteur;

                $strCss2 = 'background-color=#FAEADA;';
                $color = '#FAEADA';
                if (self::$level > 0) {
                    $strCss2 = 'background-color=#FAFAFA; width:85%;margin:0 auto;padding: 15px;';
                    $color = '#FAFAFA';
                }
                if ($compteur % 2) {
                    $strCss2 = 'background-color=#F9FDF3;';
                    $color = '#F9FDF3';
                    if (self::$level > 0) {
                        $strCss2 = 'background-color=#E6E6E6; width:85%;margin:0 auto;padding: 15px;';
                        $color = '#E6E6E6';
                    }
                }

                //drag and drop au cas ou
                //$strTmp .= "<table draggable=\"true\" ondragstart=\"moveMulti('" . $strName . "')\" id=\"" . $strName . $compteur . "_subForm\"  bgcolor=\"" . $color . "\"cellspacing=\"0\" cellpadding=\"0\" style='" . $strCss2 . "' class=\"" . $strName . "_subForm " . $strCss . "\" width=\"100%\">";
                $strTmp .= '<table id="'.$strName.$compteur.'_subForm"  bgcolor="'.$color."\" cellspacing=\"0\" cellpadding=\"0\" style='".$strCss2."' class=\"".$strName.'_subForm multi '.$strCss.'" width="100%">';

                $footerReadO = $readO;
                // n'affiche pas le bouton "supprimer" si le multi est obligatoire
                if ($compteur < $intMinIterations) {
                    $footerReadO = true;
                }
                $strTmp .= $this->headMultiHmvc($strName.$compteur, $compteur, $readO, $line, $options);
                // encadrement du js
                $this->_sJS .= "if (document.getElementById('".$strPrefixe.$compteur."_multi_display') != 'undefined' && document.getElementById('".$strPrefixe.$compteur."_multi_display')!= null) {\n if (document.getElementById('".$strPrefixe.$compteur."_multi_display').value) {\n";
                // retro compatibite
                //hmvc
                //Ajout du compteur dans $line -> $values
                $line['CPT_POS_MULTI'] = $compteur;
                // diffusion des valeurs jusqu'au contenu du hmvc
                if (isset($options['forceValues'])) {
                    $line = array_merge($line, $options['forceValues']);
                }
                if (!isset($line['PAGE_VERSION']) || !isset($line['PAGE_ID'])) {
                    $line = array_merge($line, $tmpValues);
                }
                $strTmp .= call_user_func_array(array($call['class'], $call['method']), array($oForm,
                    $line, $bReadOnly, $strName.$compteur.'_', $compteur, ));

                //Ajout du footer (contient le bouton supprimer)
                if ($bAllowDeletion) {
                    $strTmp .= $this->footerMultiHmvc($strName.$compteur, $compteur, $footerReadO, $options);
                }
                // fin du js
                $this->_sJS .= "}\n}\n";
                $strTmp .= "</table>\n";
            }
        }

        $strTmp .= $this->createHidden('increment_'.$strName, $incrementField);
        $strTmp .= $this->createHidden('count_'.$strName, count($tabValues));
        $strTmp .= $this->createHidden('nombre_'.$strName, count($tabValues));

        // Gestion du minimum
        if ($intMinIterations) {
            //Fix of count value
            $this->_sJS .= "var count = eval($('#count_".$strName."').val() || 0);";
            $this->_sJS .= ' if (count < '.$intMinIterations." && !$('#".$strName."_td').parents(\"tbody\").hasClass(\"isNotRequired\")) {\n";
            $strMessage = t('NDP_MIN_ITERATION').$intMinIterations.' '.(strip_tags(str_replace('"', '\\'.'"', $strLib)));
            $this->_sJS .= '    alert("'.$strMessage."\");\n";
            $this->_sJS .= "    return false;\n";
            $this->_sJS .= "}\n";
        }
        if ($intMaxIterations) {
            $strTmp .= $this->createHidden('max_'.$strName, $intMaxIterations);
        }

        $strTmp .= "</td></tr>\n";
        $lib = Pelican_Html::span(array('id' => $strName));
        if (!$bReadOnly && $bAllowAdd) {
            $style = 'min-width:200px; max-width: 300px;';

            $onClick = "addClone('".$strName."','".$intMaxIterations."','".$level."',".($options['showNumberLabel'] ? "true ,'".$options['numberLabel']."'" : "false,''").');';
            $onClick .= "var count = eval($('#count_".$strName."').val() || 0); if(count>1){ $('.deplace_".$strName."').show();}";
            if (is_array($bAllowAdd) && $bAllowAdd[0] == false) {
                $style = 'display: none;';
                $onClick = '';
            }
            $lib = Pelican_Html::input(array('name' => $strName, 'id' => $strName,
                'type' => 'button', 'class' => 'buttonmulti',
                'value' => ($sButtonAddMulti ? $sButtonAddMulti : Pelican_Text::htmlentities($strLib)),
                'style' => $style, 'onclick' => $onClick, ));
            $lib .= (!empty($options['maxCta'])) ? '<p> ('.$options['maxCta'].' '.t('NDP_MAX').')</p>' : '';
        }
        $strTmp .= Pelican_Html_Form::get($lib, '', false, false, $this->sStyleLib, $this->sStyleVal, '', 'center', $this->_sFormDisposition);
        //Set du JS de drag'n'drop
        if ($noDragNDrop === false) {
            $strTmp .= self::setDragDrop($strName, $options);
        }

        $this->_aIncludes['multi'] = true;
        // il faut inclure tous les js pour les contrôles de saisie des champs ajoutés à la volée (on ne peut pas savoir ce dont on va avoir besoin à l'avance)
        $this->_aIncludes['num'] = true;
        $this->_aIncludes['text'] = true;
        $this->_aIncludes['date'] = true;
        $this->_aIncludes['list'] = true;
        $this->_aIncludes['popup'] = true;
        $this->_aIncludes['crosstab'] = true;
        $this->_bUseMulti = true;
        $this->bDirectOutput = $bDirectOutput;

        --self::$level;

        return $this->output($strTmp);
    }

    /**
     * .
     *
     *
     * @param mixed $multi    
     * @param mixed $compteur 
     * @param mixed $readO    
     * @param array    $values   Description
     * @param array    $options  (option) tableau de parametrage du hmvc pour limiter le nombre d'arguments à la fonction
     *                           'showNumberLabel'   => BOOL
     *                           'numberLabel'       => string
     *
     * @return mixed
     */
    public function headMultiHmvc($multi, $compteur, $readO, $values = array(), $options = [])
    {
        $return = '';
        if (!isset($readO)) {
            $readO = false;
        }
        $compteur = is_int($compteur) ? ($compteur + 1) : $compteur;
        $label = 'n°';
        if (!empty($options['numberLabel'])) {
            $label = $options['numberLabel'];
        }
        if ($options['showNumberLabel'] !== false) {
            $label = $label.' '.$compteur;
            $return .= $this->createLabel($label, '');
        }

        //Drag'n'Drop
        $return .= $this->createHidden($multi.'_PAGE_ZONE_MULTI_ORDER', $compteur, true, false, 'order');
        $return .= $this->createHidden($multi.'_multi_display', '1', true);

        return $return;
    }

    public function footerMultiHmvc($multi, $compteur, $readO, $options = [])
    {
        $return = '';
        if (!$readO) {
            $onClick = "var count = getCount('".$multi."') - 1;  delClone('".$multi."', ".$compteur.','.($options['showNumberLabel'] ? "true, '".$options['numberLabel']."'" : "false,''").');';
            $multiName = $this->_aMultiTrackNames[sizeof($this->_aMultiTrackNames) - 1];
            $onClick .= " if(count<2){ $('.deplace_".$multiName."').hide();}";

            $lib = Pelican_Html::input(
                array(
                    'name' => $multiName,
                    'type' => 'button',
                    'class' => 'buttonmulti',
                    'value' => t('FORM_BUTTON_FILE_DELETE'),
                    'style' => 'float:right;width:200px;',
                    'onclick' => $onClick,
                )
            );

            $return = Pelican_Html_Form::get('', $lib, false, false, $this->sStyleLib, $this->sStyleVal, '', 'right', $this->_sFormDisposition);
        }

        return $return;
    }

    /**
     * .
     *
     *
     * @param string $ID_MULTI
     * @param array  $options  (option) tableau de parametrage du hmvc pour limiter le nombre d'arguments à la fonction
     *                         'showNumberLabel'   => BOOL
     *
     * @return string
     */
    public function setDragDrop($ID_MULTI, $options = [])
    {
        //Create JS script showing buttons to allow Drag'n'Drop
        $return = '
            <script language="javascript">
                var colorImpair = "#faeada";
                var colorPair = "#f9fdf3";

                $(document).ready(function() {
                    buttonAdd = $("#'.$ID_MULTI.'");
                    if (buttonAdd.length){
                        var count = eval($("#count_'.$ID_MULTI.'").val() || 0);
                        buttonAdd.parent().parent().closest("tr").find("td.formval").html("<input style=\'float:right; width:200px;\' type=\'button\'  class=\'deplace_'.$ID_MULTI.' buttonmulti\' value=\''.t('DEPLACER').'\' >");
                        if(count>1){
                            $(".deplace_'.$ID_MULTI.'").show();
                        }
                        $(".deplace_'.$ID_MULTI.'").live("click", function() {
                            $(".deplace_'.$ID_MULTI.'").addClass("fixe_'.$ID_MULTI.'");
                            $(".deplace_'.$ID_MULTI.'").val("'.t('FIXER').'");
                            $(".deplace_'.$ID_MULTI.'").removeClass("deplace_'.$ID_MULTI.'");
                            $("table .'.$ID_MULTI.'_subForm").parent().sortable({
                                stop: function(event, ui) {
                                    $(".'.$ID_MULTI.'_subForm").each(function() {
                                        var index = parseInt($(this).parent().children().not(":hidden").index(this)) + 1;
                                        $(this).find(".order").val(index);
            ';
        if ($options['showNumberLabel'] !== false) {
            $label = 'n°';
            if (!empty($options['numberLabel'])) {
                $label = $options['numberLabel'];
            }
            $return .= '$(this).find(".formlib").filter(":first").html("'.$label.'" + index);';
        }
        $return .= '
                                        if (index % 2 == 0) {
                                            $(this).css("background-color", colorPair);
                                        } else {
                                            $(this).css("background-color", colorImpair);
                                        }
                                    }).trigger("sortable:drop");
                                }
                            });
                        });
                        $(".fixe_'.$ID_MULTI.'").live("click", function() {
                            $(".fixe_'.$ID_MULTI.'").addClass("deplace_'.$ID_MULTI.'");
                            $(".deplace_'.$ID_MULTI.'").removeClass("fixe_'.$ID_MULTI.'");
                            $(".deplace_'.$ID_MULTI.'").val("'.t('DEPLACER').'");
                            $(".'.$ID_MULTI.'_subForm").each(function() {
                                $("table .'.$ID_MULTI.'_subForm").parent().sortable("destroy");
                            });
                        });
                    }
                    var count = eval($("#count_'.$ID_MULTI.'").val() || 0);
                    if (count < 2) {
                        //si on a 1 ou 0 bloc, on cache le bouton
                        $(".deplace_'.$ID_MULTI.'").hide();
                    }
                });
            </script>
            ';

        return $return;
    }

    /**
     * Génère un champ de type Hidden, surchage avec une class.
     *
     * ATTENTION : si le champ a déjà été créé avant, la commande est ignorée
     *
     *
     * @param string $strName Nom du champ
     * @param string $strValue (option) Valeur du champ : "" par défaut
     * @param bool|string $bGetHTML (option) Récupération du retour de la fonction
     *                          (utilisation interne) : false par défaut
     * @param bool $bMultiple (option) Rajoute de "[]" pour les input multiples :
     *                          true par défaut
     * @param string $class Class du champ
     *
     * @param bool $enabled
     * @return string
     */
    public function createHidden($strName, $strValue = '', $bGetHTML = false, $bMultiple = false, $class = '', $enabled = true)
    {
        $strTmp = '<input type="hidden"';
        if ($class != '') {
            $strTmp .= ' class="'.str_replace('"', '&quot;', $class).'"';
        }
        if (!$enabled) {
            $strTmp .= ' disabled="disabled" ';
        }
        if (!$bMultiple) {
            $strTmp .= ' id="'.$strName.'"';
            $this->countInputName($strName);
        }
        $strTmp .= ' name="'.$strName.($bMultiple ? '[]' : '').'"';
        if ($strValue != '') {
            $strTmp .= ' value="'.str_replace('"', '&quot;', $strValue).'"';
        }
        $strTmp .= " />\n";
        if (!$bGetHTML) {
            $this->form_class_hidden[$strName] = $strTmp;
        } else {
            return $strTmp;
        }
    }

    /**
     * Génère un bouton.
     *
     *
     * @param string $strName Nom du champ
     * @param string $strLib (option) Libellé du champ : "" par défaut
     * @param string $strFunction (option) Fonction js à exécuter quand clic du
     *                            bouton : "" par défaut
     * @param bolean|bool $bDisable (option) Bolean indiquant si le bouton à generer est
     *                            desactiver ou
     *
     * @param string $classCss
     * @param string $moreAttr
     *
     * @return string
     */
    public function createButton($strName, $strLib = '', $strFunction = '', $bDisable = false, $classCss = '', $moreAttr = '')
    {
        $this->countInputName($strName);
        $strTmp = '<input '.$moreAttr." class=\"button $classCss\" type=\"button\" name=\"".$strName.'" id="'.$strName.'"';
        $strTmp .= ' value="'.Pelican_Text::htmlentities($strLib).'"';
        $strTmp .= ' onclick="';
        if ($strFunction == 'close') {
            $strTmp .= 'javascript:self.close();';
        } else {
            $strTmp .= $strFunction;
        }
        $strTmp .= '" ';
        if ($bDisable) {
            $strTmp .= ' disabled';
        }
        $strTmp .= ' />';

        return $this->output($strTmp);
    }

    /**
     * Génère un éditeur DHTML.
     *
     *
     * @param string $strName      Nom du champ
     * @param string $strLib       Libellé du champ
     * @param bool   $required     (option) Champ obligatoire : false par défaut
     * @param string $strValue     (option) Valeur du champ : "" par défaut
     * @param bool   $readOnly     (option) Affiche uniquement la valeur et pas le champ
     *                             (créé un input hidden) : false par défaut
     * @param bool   $popup        (option)
     * @param string $strSubFolder (option) Répertoire racine de la médiathèque
     *                             appelée du miniword
     * @param int    $width        (option)
     * @param int    $height       (option)
     * @param mixed  $limitedConf  (option) Identifiant du filtre à appliquer à la
     *                             confiration de l'éditeur (dans /application/configs/editor.ini.php, $_LIMITED)
     * @param array  $options
     *                             available keys:
     *                             message: adds a help message beneath the text editor
     *                             maxCharacterNumber: adds a counter
     *
     *@return string
     */
    public function createEditor($strName, $strLib, $required = false, $strValue = '', $readOnly = false, $popup = true, $strSubFolder = '', $width = null, $height = null, $limitedConf = '', $options = array())
    {
        if (empty($width) || $width >= 500) {
            $width = 500;
        }

        return parent::createEditor($strName, $strLib, $required, $strValue, $readOnly, $popup, $strSubFolder, $width, $height, $limitedConf, $options);
    }

    /**
     * @param string $selected
     * @param mixed  $value
     * @param string $type
     *
     * @return string
     */
    public static function addHeadContainer($selected, $value, $type)
    {
        $display = 'display:none;';
        $class = ' isNotRequired';
        if ($selected == $value) {
            $display = '';
            $class = '';
        }

        return sprintf('<tbody id="%s_%s" style="%s" class="%s %s_%s %s"><tr><td colspan="2"><table class="form">', $type, $selected, $display, $type, $type, $selected, $class);
    }

    /**
     * @return string
     */
    public static function addFootContainer()
    {
        $footContainer = '</table>';
        $footContainer .= '</td></tr>';
        $footContainer .= '</tbody>';

        return $footContainer;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public static function addJsContainerRadio($type)
    {
        $js = 'onclick="
                    $(\'.'.$type.'\').hide();
                    var selectedRadio =   $(this).val();

                    $(\'.'.$type.'_\' + selectedRadio).show();
                    $(\'.'.$type.'\').addClass(\'isNotRequired\');
                    $(\'.'.$type.'_\' + selectedRadio + \'\').removeClass(\'isNotRequired\');
                "';

        return $js;
    }

    public function createTitle($title, $class = '', $align = '', $valign = 'h', $colspan = '2', $addon = '')
    {
        $title = Pelican_Html_Form::tr(Pelican_Html_Form::td(array('class' => $class.' form_title',
            'style' => 'padding-top:15px;', 'valign' => $valign, 'align' => $align,
            'colspan' => $colspan, ), Pelican_Html::h1($title.$addon)));

        return $this->output($title);
    }

    public function createDescription($text, $class = '', $align = '', $valign = 'h', $colspan = '2', $addon = '')
    {
        $desc = Pelican_Html_Form::tr(Pelican_Html_Form::td(array('class' => $class,
            'style' => 'padding:15px;', 'valign' => $valign, 'align' => $align,
            'colspan' => $colspan, ), Pelican_Html::p(Pelican_Html::b($text.$addon))));

        return $this->output($desc);
    }

    /**
     * @param Pelican_View $view
     *
     * @return Ndp_Form
     */
    public function setView($view)
    {
        $this->currentView = $view;

        return $this;
    }

    /**
     * @return string
     */
    private function initTinymce()
    {
        $strTmp = '';
        if ($this->tinymce && $this->aEditor) {
            $this->showEditor = true;
            $strTmp .= Pelican_Html::script(array('src' => Pelican::$config['LIB_PATH'].$this->sLibOther.'/tiny_mce/tiny_mce.js'));
            $ed = $this->getTiny();
            $strTmp .= Pelican_Html::script($ed);
        }

        return $strTmp;
    }

    /**
     * @return string
     */
    private function initVirtualKeyboard()
    {
        $strTmp = '';
        if ($this->bVirtualKeyboard && $this->_InputVK && empty($_GET['readO'])) {
            $strTmp .= $this->createVirtualKeyboard($this->_InputVK[0], true);
        }

        return $strTmp;
    }

    private function initSuggest()
    {
        $strTmp = '';
        if ($this->suggest) {
            $strTmp .= '<div style="top: 45px; left: 243px; width:202px;" id="search_suggest"></div>';
            $strTmp .= '<link rel="STYLESHEET" type="text/css" href="'.$this->_sLibPath.$this->_sLibForm.'/css/suggest.css">';
            foreach ($this->suggest as $name => $val) {
                $this->endJS .= "buildSearch('".$name."',Array('".implode("','", str_replace("'", "\\'", $val))."'));\n";
            }
        }

        return $strTmp;
    }

    /**
     * @param string $_sJSPath
     *
     * @return string
     */
    private function importJS($_sJSPath)
    {
        $head = $this->currentView->getHead();
        $strTmp = '';

        while ($ligne = each($this->_aIncludes)) {
            if ($ligne['value']) {
                switch ($ligne['key']) {
                    case 'num':
                        $head->setJs($_sJSPath.'xt_num_controls.js');
                        break;
                    case 'text':
                        $head->setJs($_sJSPath.'xt_text_controls.js');
                        break;
                    case 'color':
                        $strTmp .= '<script type="text/javascript">
                                  $(document).ready( function() {
                                    $(".colors").miniColors({});
                                  });
                          </script>';
                        break;
                    case 'date':
                        $head->setJs($_sJSPath.'xt_date_controls.js');
                        $strTmp .= "<script type=\"text/javascript\">
                          \$(function() {
                            \$(\".datepicker\").datepicker({
                              showOn: 'button',
                              buttonImage: '".$this->_sLibPath.$this->_sLibForm."/images/cal.gif',
                              buttonImageOnly: true,
                              changeMonth: true,
                  changeYear: true,
                  duration: 'fast',
                  showAnim: 'fadeIn',
                  appendText: '&nbsp;".Pelican_Html_Form::comment('('.t('DATE_FORMAT_LABEL').')')."',
                  autoSize: true
                            });
                          });
                          </script>";
                        $strTmp .= "<script type=\"text/javascript\">var dateLanguageFormat='".t('DATE_FORMAT_DB')."';</script>\n";
                        break;
                    case 'list':
                        $head->setJs($_sJSPath.'xt_list_fonctions.js');
                        break;
                    case 'ordered_list':
                        $head->setJs($_sJSPath.'xt_ordered_list_fonctions.js');
                        break;
                    case 'popup':
                        $head->setJs($_sJSPath.'xt_popup_fonctions.js');
                        break;
                    case 'crosstab':
                        $head->setJs($_sJSPath.'xt_crosstab_fonctions.js');
                        break;
                    case 'multi':
                        $head->setJs('/js/Ndp/Form/xt_multi_fonctions.js');
                        break;
                    case 'sub':
                        $head->setJs($_sJSPath.'xt_sub_fonctions.js');
                        break;
                    case 'suggest':
                        $head->setJs($_sJSPath.'xt_suggest_fonctions.js');
                        break;
                    case 'virtualkeyboard':
                        $head->setJs('/library/External/tiny_mce/plugins/Jsvk/jscripts/vk_popup.js');
                        break;
                    case 'map':
                        $head->setJs($_sJSPath.'xt_map_fonctions.js');
                        break;
                    case 'mapv3':
                        $head->setJs($_sJSPath.'xt_map_fonctions_v3.js');
                        break;
                    default:
                        break;
                }
            }
        }

        return $strTmp;
    }

    /**
     *
     */
    private function initMap()
    {
        if ($this->map) {
            foreach ($this->map as $name => $val) {
                $initMap[] = "mapControl('".$name."');";
            }
            $this->endJS .= "
                if ( window.addEventListener ) {
  window.addEventListener('load', function(){ ".implode("\n", $initMap)." }, false);
} else {
  if ( window.attachEvent ) {
    window.attachEvent('onload', function(){ ".implode("\n", $initMap).' } );
  }
}
';
        }
    }

    /**
     * @param string $_sJSPath
     *
     * @return string
     */
    public function close($_sJSPath = self::JS)
    {
        $head = false;
        if ($this->currentView) {
            $head = $this->currentView->getHead();
        }
        if (!$head) {
            // Portabilité
            return parent::close($_sJSPath);
        }
        $strTmp = $this->putHidden();

        if (is_array($this->_aMultiTrackNames) && count($this->_aMultiTrackNames) > 0) {
            $sMultiTrackNames = implode(',', $this->_aMultiTrackNames);
            $strTmp .= $this->createHidden('TRACK_MULTINAMES', $sMultiTrackNames);
        }

        $this->endJS = '';
        $strTmp .= "</form>\n";
        /*         * *** init de tinyMCE ************** */
        $strTmp .= $this->initTinymce();
        /*         * *** fin init tinyMCE ************* */

        /* virtual keyboard */
        $strTmp .= $this->initVirtualKeyboard();

        /* suggest */
        $strTmp .= $this->initSuggest();

        $head->setJs($_sJSPath.'ajax.js');
        $strTmp .= $this->importJS($_sJSPath);

        $strTmp .= '<script type="text/javascript">';
        $strTmp .= $this->getCheckFunctions();
        // Mise en place du blockage de soumission multiple
        $strTmp .= "function blockSubmit(){\nreturn false;\n}\n";
        $strTmp .= $this->getDefaultFocus();
        if ($this->displayTab) {
            $strTmp .= $this->getJsTab();
        }
        $this->initMap();
        $strTmp .= $this->endJS."</script>\n";
        $this->controlDuplicateInputName();
        if (Pelican::$config['HMVC']) {
            $head->setJs(Pelican::$config['LIB_PATH'].Pelican::$config['LIB_FORM'].'/js/hmvc.js');
        }

        return $this->output($strTmp);
    }

    protected function getCheckFunctions()
    {
        $strTmp = '';
        // on crée une function de vérification des multi par defaut qui sera surcharger par les multis
        if ($this->_bUseMulti) {
            $strTmp .= 'var '.$this->sCheckFunction."_multi=new Function(\"obj\",\"return true\");\n";
        }
        $strTmp .= "var activeInput;\n";
        // creation de la fonction de check
        $strTmp .= 'function '.$this->sCheckFunction." (obj) {\n";
        //on inclu le js de verification des différent champs
        $strTmp .= $this->_sJS;
        // resultat de la validation par defaut si on est arrivé jusque la c'est que tous les champs simple sont OK
        $strTmp .= 'var result = true;';
        // on passe par la verification des multi ensuite
        if ($this->_bUseMulti) {
            $strTmp .= 'result = '.$this->sCheckFunction."_multi(obj);\n";
        }

        $strTmp .= 'if(result) {';
        // une fois tous les test passé on remplace la fonction de vérif par une autre chargé de block la soumission multiple
        if ($this->bBlockSubmit) {
            $strTmp .= $this->sCheckFunction." = blockSubmit;\n";
        }
        // on désactive tous les champs des zones masqé
        $strTmp .= '   $("tbody.isNotRequired *").attr("disabled",true);';
        $strTmp .= '}';
        //on retourne le résultat
        $strTmp .= "return result\n";
        $strTmp .= "}\n";

        return $strTmp;
    }

    protected function getDefaultFocus()
    {

        // Gestion du focus
        $strTmp = "function fwFocus(obj){\nobj.focus();\n}\n";
        // Mise en place du focus par défaut
        if ($this->_sDefaultFocus && $_SERVER['SCRIPT_NAME'] != $this->_sLibPath.$this->_sLibForm.'/popup_multi.php') {
            $strTmp .= 'if (document.'.$this->sFormName.'["'.$this->_sDefaultFocus."\"].style.display != \"none\") {\n";
            $strTmp .= 'if (document.'.$this->sFormName.'["'.$this->_sDefaultFocus."\"].disabled) {\n";
            $strTmp .= 'fwFocus(document.'.$this->sFormName.'["'.$this->_sDefaultFocus."\"]);\n";
            $strTmp .= "}\n";
            $strTmp .= "}\n";
        }

        return $strTmp;
    }

    /**
     * Génère des radio à partir d'une série de valeurs.
     *
     *
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param mixed $aDataValues (option) Tableau de valeurs (id=>lib) : "" par défaut
     * @param string $aValue (option) Valeur cochée : "" par défaut
     * @param bool $bRequired (option) Champ obligatoire : false par défaut
     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param string $cOrientation (option) Orientation h=horizontal, v=vertical : "h"
     * @param bool $bFormOnly (option) 
     * @param string $strEvent (option) 
     * @param array $listOfPictosForLabels (option) array de picto pour les libellés
     * @param array $options
     *
     * @return string
     */
    public function createRadioFromList($strName, $strLib, $aDataValues = '', $aValue = '', $bRequired = false, $bReadOnly = false, $cOrientation = 'h', $bFormOnly = false, $strEvent = '', $listOfPictosForLabels = null, $options = array())
    {
        $options['readOnly'] = $bReadOnly;

        return $this->_createBox($strName, $strLib, $aDataValues, $aValue, $bRequired, $options, $cOrientation, 'radio', $bFormOnly, $strEvent, '', $listOfPictosForLabels);
    }

    /**
     * Méthode pour tester et attribuer des pictos aux labels d'un checkbox / radio.
     *
     * @param string $form
     * @param array  $listOfPictosForLabels
     * @param array  $listOfLabels
     *
     * @return string
     */
    public function testAndSetPictoFoxCreateBox($form, $listOfPictosForLabels, $listOfLabels)
    {
        if (is_array($listOfPictosForLabels) && is_array($listOfLabels)) {
            foreach ($listOfLabels as $keyOfLabel => $labelValue) {
                //configuration du picto
                $picto = ' '.Pelican_Html::img(
                        array(
                            'border' => '0',
                            'src' => Pelican::$config['MEDIA_HTTP'].self::BACKEND_PICTOS.$listOfPictosForLabels[$keyOfLabel],
                            'class' => '',
                            'title' => $labelValue,
                            'style' => 'vertical-align: middle;',
                        )
                    );
                $radioLabelWithPicto = Pelican_Text::htmlentities($labelValue).$picto;
                //on remplace le label par le label + picto
                $form = str_replace($labelValue, $radioLabelWithPicto, $form);
            }
        }

        return $form;
    }

    /**
     * Méthode pour tester et attribuer des pictos et labels sur un createHeader.
     *
     * @param array $listOfPictosForLabels
     * @param int   $colSpan
     *
     * @return string
     */
    public function testAndSetPictoFoxCreateHeader($listOfPictosForLabels = [], $colSpan = 1)
    {
        $td = '<td colspan="'.$colSpan.'">';
        if (self::VERTICAL === $this->_sFormDisposition) {
            $td = '<td>';
        }
        foreach ($listOfPictosForLabels as $labelValue => $pictoName) {
            //configuration du picto
            $picto = ' '.Pelican_Html::img(
                    array(
                        'border' => '0',
                        'src' => Pelican::$config['MEDIA_HTTP'].self::BACKEND_PICTOS.$pictoName,
                        'class' => '',
                        'title' => $labelValue,
                        'style' => 'vertical-align: middle; max-height:100px',
                    )
                );
            $td .= $labelValue.$picto;
        }

        return $td;
    }
    public function createComboFromList($strName, $strLib, $aDataValues = '', $aSelectedValues = '', $bRequired = false, $bReadOnly = false, $iSize = '1', $bMultiple = false, $iWidth = '', $bChoisissez = true, $bFormOnly = false, $strEvent = '', $options)
    {
        $options['readOnly'] = $bReadOnly;

        return $this->_createCombo($strName, $strLib, $aDataValues, $aSelectedValues, $bRequired, $options, $iSize, $bMultiple, $iWidth, $bChoisissez, false, $bFormOnly, '', $strEvent);
    }
}
