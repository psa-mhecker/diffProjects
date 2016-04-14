<?php
/**
 * __DESC__.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */
require_once pelican_path('Html.Form');
pelican_import('Text');

/**
 * __DESC__.
 *
 * @author Patrick Deroubaix <patrick.deroubaix@businessdecision.fr>
 *
 * @todo : readOnly , commentaire
 */
class Pelican_Taxonomy
{
    /**
     * __DESC__.
     *
     * @access public
     *
     * @var __TYPE__
     */
    public $sStyleLib = "formlib";

    /**
     * __DESC__.
     *
     * @access public
     *
     * @var __TYPE__
     */
    public $sStyleVal = "formval";

    /**
     * __DESC__.
     *
     * @access public
     *
     * @var __TYPE__
     */
    public $formDisposition = "horizontal";

    /**
     * __DESC__.
     *
     * @access public
     *
     * @var __TYPE__
     */
    public $groupeForm = false;

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function Pelican_Taxonomy()
    {
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $term __DESC__
     *
     * @return __TYPE__
     */
    public function saveTerms($term)
    {
        $oConnection = Pelican_Db::getInstance();
        Pelican_Db::$values = null;
        Pelican_Db::$values["form_action"] = "INS";
        Pelican_Db::$values["TERMS_ID"] = - 2;
        Pelican_Db::$values["TERMS_SLUG"] = Pelican_Text::cleanText($term, '_');
        Pelican_Db::$values["TERMS_SLUG"] = Pelican_Text::dropAccent(Pelican_Db::$values["TERMS_SLUG"]);
        Pelican_Db::$values["TERMS_NAME"] = $term;
        if (!empty(Pelican_Db::$values["TERMS_NAME"])) {
            $oConnection->updateTable(Pelican_Db::$values["form_action"], "#pref#_terms");

            return Pelican_Db::$values["TERMS_ID"];
        } else {
            return false;
        }
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $aInputName   __DESC__
     * @param __TYPE__ $objectId     __DESC__
     * @param __TYPE__ $objectTypeId __DESC__
     * @param string   $groupId      (option) __DESC__
     *
     * @return __TYPE__
     */
    public function saveTermsRelationships($aInputName, $objectId, $objectTypeId, $groupId = 0)
    {
        $oConnection = Pelican_Db::getInstance();
        $tmpForm = Pelican_Db::$values;
        /* suppression */
        $aBind[":OBJECT_ID"] = $objectId;
        $aBind[":OBJECT_TYPE_ID"] = $objectTypeId;
        $relationshipsDelete = "delete from #pref#_terms_relationships where OBJECT_ID=:OBJECT_ID";
        $oConnection->query($relationshipsDelete, $aBind);
        if (is_array($aInputName)) {
            foreach ($aInputName as $inputName) {
                $terms = explode(',', $tmpForm[$inputName]);
                Pelican_Db::$values["TERMS_GROUP_ID"] = "";
                if (is_array($terms)) {
                    $arrayTermToSave = array();
                    foreach ($terms as $term) {
                        $aBind[":TERMS_SLUG"] = $oConnection->strtobind(Pelican_Text::dropAccent(Pelican_Text::cleanText($term, '_')));
                        $existQuery = "select TERMS_ID from #pref#_terms where TERMS_SLUG=:TERMS_SLUG ";
                        $existResult = $oConnection->queryRow($existQuery, $aBind);
                        if (!$existResult) {
                            $termsId = $this->saveTerms($term);
                            if ($termsId) {
                                array_push($arrayTermToSave, $termsId);
                            }
                        } else {
                            array_push($arrayTermToSave, $existResult["TERMS_ID"]);
                        }
                    }
                }
                /* insertion */
                Pelican_Db::$values["OBJECT_ID"] = $objectId;
                Pelican_Db::$values["OBJECT_TYPE_ID"] = $objectTypeId;
                Pelican_Db::$values["form_action"] = "INS";
                $i = 0;
                /* insertion des terms déja associer a l'object */
                $aOldTerms = explode(',', $tmpForm[$inputName.'_SELECTED']);
                if (!$tmpForm[$inputName.'_BUNDLE_ID']) {
                    Pelican_Db::$values["TERMS_GROUP_ID"] = '0';
                } else {
                    Pelican_Db::$values["TERMS_GROUP_ID"] = $tmpForm[$inputName.'_BUNDLE_ID'];
                }
                if ($aOldTerms) {
                    foreach ($aOldTerms as $term) {
                        if ($term) {
                            Pelican_Db::$values["TERMS_ID"] = $term;
                            Pelican_Db::$values["TERMS_ORDER"] = $i;
                            $oConnection->updateTable(Pelican_Db::$values["form_action"], "#pref#_terms_relationships");
                            $i++;
                        }
                    }
                }
                if (!empty($arrayTermToSave)) {
                    $arrayTermToSave = array_diff($arrayTermToSave, $aOldTerms);
                    foreach ($arrayTermToSave as $term) {
                        Pelican_Db::$values["TERMS_ID"] = $term;
                        Pelican_Db::$values["TERMS_ORDER"] = $i;
                        $oConnection->updateTable(Pelican_Db::$values["form_action"], "#pref#_terms_relationships");
                        $i++;
                    }
                }
            }
        }
        Pelican_Db::$values = $tmpForm;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $inputName    __DESC__
     * @param __TYPE__ $objectId     __DESC__
     * @param __TYPE__ $objectTypeId __DESC__
     *
     * @return __TYPE__
     */
    public function saveTermsGroups($inputName, $objectId, $objectTypeId)
    {
        $oConnection = Pelican_Db::getInstance();
        $tmpForm = Pelican_Db::$values;
        /* suppression */
        $aBind[":OBJECT_ID"] = $objectId;
        $aBind[":OBJECT_TYPE_ID"] = $objectTypeId;
        $relationshipsDelete = "delete from #pref#_terms_group_rel where TERMS_GROUP_ID=:OBJECT_ID";
        $oConnection->query($relationshipsDelete, $aBind);
        $terms = explode(',', $tmpForm[$inputName]);
        if (is_array($terms)) {
            $arrayTermToSave = array();
            foreach ($terms as $term) {
                $aBind[":TERMS_SLUG"] = Pelican_Text::dropAccent(Pelican_Text::cleanText($term, '_'));
                $existQuery = "select TERMS_ID from #pref#_terms where TERMS_SLUG=':TERMS_SLUG' ";
                $existResult = $oConnection->queryRow($existQuery, $aBind);
                if (!$existResult) {
                    $termsId = $this->saveTerms($term);
                    if ($termsId) {
                        array_push($arrayTermToSave, $termsId);
                    }
                } else {
                    array_push($arrayTermToSave, $existResult["TERMS_ID"]);
                }
            }
        }
        /* insertion */
        Pelican_Db::$values["TERMS_GROUP_ID"] = $objectId;
        Pelican_Db::$values["form_action"] = "INS";
        $i = 0;
        /* insertion des terms déja associer a l'object */
        $aOldTerms = explode(',', $tmpForm[$inputName.'_SELECTED']);
        if ($aOldTerms) {
            foreach ($aOldTerms as $term) {
                if ($term) {
                    Pelican_Db::$values["TERMS_ID"] = $term;
                    $oConnection->updateTable(Pelican_Db::$values["form_action"], "#pref#_terms_group_rel");
                    $i++;
                }
            }
        }
        if (!empty($arrayTermToSave)) {
            $arrayTermToSave = array_diff($arrayTermToSave, $aOldTerms);
            foreach ($arrayTermToSave as $term) {
                Pelican_Db::$values["TERMS_ID"] = $term;
                $oConnection->updateTable(Pelican_Db::$values["form_action"], "#pref#_terms_group_rel");
                $i++;
            }
        }
        Pelican_Db::$values = $tmpForm;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $searchString __DESC__
     * @param string   $groupId      (option) __DESC__
     *
     * @return __TYPE__
     */
    public function getTermsCompletion($searchString, $groupId = null)
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[":searchString"] = $searchString;
        $strQuery = "select
        	t.terms_name,
        	t.terms_id
        	from
        	#pref#_terms t";
        if (!empty($groupId)) {
            $aBind[":GROUP_ID"] = $groupId;
            $strQuery .= " INNER JOIN pel_terms_group_rel tg ON ( t.terms_id = tg.terms_id
                    AND tg.TERMS_GROUP_ID = :GROUP_ID )";
        }
        $strQuery .= "  where terms_name like ':searchString%' ";
        $aResult = $oConnection->queryTab($strQuery, $aBind);

        return $aResult;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param string   $strName        __DESC__
     * @param string   $strLib         __DESC__
     * @param __TYPE__ $autocompleteDb __DESC__
     * @param __TYPE__ $objectId       __DESC__
     * @param __TYPE__ $objectTypeId   __DESC__
     * @param string   $groupId        (option) __DESC__
     * @param bool     $bReadOnly      (option) __DESC__
     * @param bool     $bRequired      (option) __DESC__
     *
     * @return __TYPE__
     */
    public function generateFormInput($strName = 'TAXONOMY', $strLib = "", $autocompleteDb = '/actions/taxonomy/taxonomy_db.php', $objectId, $objectTypeId, $groupId = null, $bReadOnly = false, $bRequired = false)
    {
        //http://devthought.com/wp-content/projects/jquery/textboxlist/Demo/
        //
        $strInputTag = '
         <script type="text/javascript">
            $(document).ready(function() {
                function formatItem(row) {
                        return row[0];
                 }
             function formatResult(row) {
                  return row[0].replace(/(<.+?>)/gi, \'\');
             }
            $("#'.$strName.'").autocomplete(\''.$autocompleteDb.'\',

            {

              formatItem: formatItem,
              formatResult: formatResult,
              width: 300,
              highlight: false,
              multiple: true,
              multipleSeparator: ","';
        if ($groupId) {
            $strInputTag .= '      ,extraParams: {
                           bundle: '.$groupId.'
                        },';
        }
        $strInputTag .= '        });
            $(".'.$strName.'ntdelbutton").bind("click", function(e){
                  var str = $(this).attr("id");
                  var aStr = str.split(\'-\');
                  var aValue = $(\'#'.$strName.'_SELECTED\').attr(\'value\').split(\',\');
                  var aNewValue = new Array;
                      for ( var i=0; i<aValue.length; ++i ){
                     if(aValue[i] != aStr[1]) {
                          aNewValue.push(aValue[i]);
                     }
              }
                  var strNewValue = aNewValue.join(\',\');
                  $(\'#'.$strName.'_SELECTED\').attr(\'value\',strNewValue);
                  $(this).parent().hide();
            });
          });
         </script>';
        $params['style'] = "";
        $params['type'] = "text";
        $params['class'] = "text";
        $params['name'] = $strName;
        $params['id'] = $strName;
        $params['size'] = 100;
        $params['maxlength'] = 500;
        $params['value'] = "";
        $strInputTag .= Pelican_Html::input($params);
        if ($objectId != - 2) {
            $aResult = $this->getTermForObject($objectId, $objectTypeId, $groupId, false);
            if ($aResult) {
                $strInputTag .= '<div class="tagchecklist">';
                $aSelectedTerms = array();
                foreach ($aResult as $row) {
                    $strInputTag .= '<span><a id="posttagchecknum-'.$row["TERMS_ID"].'" class="'.$strName.'ntdelbutton">X</a>'.$row["TERMS_NAME"].'</span>';
                    array_push($aSelectedTerms, $row["TERMS_ID"]);
                }
                $strInputTag .= '</div>';
                $strInputTag .= '<input type="hidden" id="'.$strName.'_SELECTED" name="'.$strName.'_SELECTED" value="'.implode(',', $aSelectedTerms).'">';
            }
        }
        $strInputTag .= '<input type="hidden" id="'.$strName.'_BUNDLE_ID" name="'.$strName.'_BUNDLE_ID" value="'.$groupId.'">';
        $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strInputTag, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "", "", $this->formDisposition);

        return $strTmp;
    }

    /**
     * Récupere la liste des tags pour un object avec fichier de Pelican_Cache ou pas.
     *
     * @access public
     *
     * @param int    $objectId     __DESC__
     * @param int    $objectTypeId __DESC__
     * @param string $groupId      (option) __DESC__
     * @param bolean $useCache     (option) [optional] utiliser false pour le BO
     *
     * @return Array
     */
    public function getTermForObject($objectId, $objectTypeId, $groupId = null, $useCache = true)
    {
        if ($useCache) {
            $aResult = Pelican_Cache::fetch("Taxonomy/Term", array($objectId, $objectTypeId, $groupId));

            return $aResult;
        } else {
            /*if ($objectTypeId == Pelican::$config['TAXONOMY_BUNDLE_ID']) {
            $oConnection = Pelican_Db::getInstance();
            $aBind[":TERMS_GROUP_ID"] = $objectId;
            $query = "select t.TERMS_ID,t.TERMS_NAME from #pref#_terms t,#pref#_terms_group_rel gr,
            #pref#_terms_group tg where t.TERMS_ID=gr.TERMS_ID
            and gr.TERMS_GROUP_ID=tg.TERMS_GROUP_ID
            and tg.TERMS_GROUP_ID=:TERMS_GROUP_ID";
            $aResult = $oConnection->queryTab($query, $aBind);
            return $aResult;
            } else {*/
            $oConnection = Pelican_Db::getInstance();
            $aBind[":OBJECT_ID"] = $objectId;
            $aBind[":OBJECT_TYPE_ID"] = $objectTypeId;
            $aBind[":TERMS_GROUP_ID"] = $groupId ? $groupId : 0;
            $query = "select t.TERMS_ID,t.TERMS_NAME from #pref#_terms t,#pref#_terms_relationships tr where tr.TERMS_ID=t.TERMS_ID
                          and tr.OBJECT_ID=:OBJECT_ID and OBJECT_TYPE_ID=:OBJECT_TYPE_ID AND tr.TERMS_GROUP_ID=:TERMS_GROUP_ID ";
            $aResult = $oConnection->queryTab($query, $aBind);

            return $aResult;
            //}
        }
    }
}
