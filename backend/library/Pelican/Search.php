<?php
/**
 * Classe de gestion de l'indexation et de la sélection des résultats de
 * recherche.
 *
 * Intégration des documents pdf, doc, xls, ppt et rtf pour windows et linus
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */

/**
 * Classe de gestion de le recherche.
 *
 * @author Raphaël Carles <rcarles@businessdecision.fr>
 *
 * @since 20/03/2006
 *
 * @version 1.0
 */
class Pelican_Search
{
    /**
     * __DESC__.
     *
     * @access public
     * @public Array
     *
     * @param __TYPE__ $research_type (option) __DESC__
     *
     * @return Pelican_Search
     */
    public function Pelican_Search($research_type = "Lucene")
    {
        $this->researchType = 'Search_'.$research_type;
        require_once pelican_path('Search.'.$research_type);
        $class = pelican_classname('Search.'.$research_type);
        $this->instance = new $class();
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $id               __DESC__
     * @param __TYPE__ $site             __DESC__
     * @param __TYPE__ $langue           __DESC__
     * @param __TYPE__ $research_type    __DESC__
     * @param __TYPE__ $research_type_id __DESC__
     * @param __TYPE__ $date             __DESC__
     *
     * @return __TYPE__
     */
    public function indexationInit($id, $site, $langue, $research_type, $research_type_id, $date)
    {
        $this->values["RESEARCH_ID"] = $id;
        $this->values[$research_type."_ID"] = $id;
        $this->values['SITE_ID'] = $site;
        $this->values['LANGUE_ID'] = $langue;
        $this->values["RESEARCH_TYPE"] = $research_type;
        $this->values["RESEARCH_TYPE_ID"] = $research_type_id;
        $this->values["RESEARCH_DATE"] = $date;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $key        __DESC__
     * @param __TYPE__ $value      __DESC__
     * @param bool     $allowEmpty (option) __DESC__
     *
     * @return __TYPE__
     */
    public function addValue($key, $value, $allowEmpty = false)
    {
        if (($value && !$allowEmpty) || $allowEmpty) {
            $this->values[$key] = $value;
        }
    }

    /**
     * Comptages du résultat de recherche + pour les champs demandés en paramètres.
     *
     * @static __DESC__
     * @access public
     *
     * @param __TYPE__ $site    __DESC__
     * @param __TYPE__ $langue  __DESC__
     * @param string   $fields  Liste des champs du tableau de retour pour lesquels on
     *                          veut un comptage
     * @param mixed    $filters Clauses de filtrage du type
     *                          array("champ","valeur","type") où type peut être string, date, keyword,
     *                          integer
     *
     * @return __TYPE__
     */
    public function getStatistics($site, $langue, $fields, $filters)
    {
        $this->instance->getStatistics($site, $langue, $fields, $filters);
    }

    /**
     * Retourne le résultat d'une recherche.
     *
     * @static __DESC__
     * @access public
     *
     * @param int      $current_page    (option) __DESC__
     * @param __TYPE__ $nbResultPerPage (option) __DESC__
     * @param __TYPE__ $order           (option) __DESC__
     * @param __TYPE__ $fields          (option) __DESC__
     *
     * @return mixed
     */
    public function getResult($current_page = 1, $nbResultPerPage = 10, $order = "date", $fields = array())
    {
        $return = $this->instance->getResult($current_page, $nbResultPerPage, $order, $fields);

        return $return;
    }

    /**
     * Enter description here...
     *
     * @static
     *
     * @return unknown
     */

    /**
     * __DESC__.
     *
     * @access public
     * @public Array
     *
     * @param __TYPE__ $key   __DESC__
     * @param __TYPE__ $value __DESC__
     *
     * @return __TYPE__
     */
    public function addMultiValue($key, $value)
    {
        if ($value) {
            $this->values[$key] .= " ".$value;
            trim($this->values[$key]);
        }
    }

    /**
     * __DESC__.
     *
     * @access public
     * @public Array
     *
     * @param __TYPE__ $key     __DESC__
     * @param __TYPE__ $aValues __DESC__
     * @param __TYPE__ $default __DESC__
     *
     * @return __TYPE__
     */
    public function addDateValue($key, $aValues, $default)
    {
        $this->values[$key] = $default;
        if ($aValues && $this->researchParam[$key]) {
            if ($this->researchParam[$key]) {
                if (substr_count($this->researchParam[$key], "+")) {
                    $dateParams = explode("+", $this->researchParam[$key]);
                    $dateField = $dateParams[0];
                    $dateOperation = "+".$dateParams[1];
                } elseif (substr_count($this->researchParam[$key], "-")) {
                    $dateParams = explode("-", $this->researchParam[$key]);
                    $dateField = $dateParams[0];
                    $dateOperation = "-".$dateParams[1];
                } else {
                    $dateField = $this->researchParam[$key];
                }
                if ($aValues[$dateField]) {
                    $this->values[$key] = substr($aValues[$dateField], 0, 10);
                    $this->dateOperation[$key] = $dateOperation;
                }
            }
        }
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function deleteSearchRecord()
    {
        $DBVALUES_MONO = Pelican_Db::$values;
        $oConnection = Pelican_Db::getInstance();
        if ($this->values["RESEARCH_ID"]) {
            Pelican_Db::$values["RESEARCH_ID"] = $this->values["RESEARCH_ID"];
            Pelican_Db::$values['SITE_ID'] = $this->values['SITE_ID'];
            Pelican_Db::$values['LANGUE_ID'] = $this->values['LANGUE_ID'];
            Pelican_Db::$values["RESEARCH_TYPE"] = $this->values["RESEARCH_TYPE"];
            Pelican_Db::$values["RESEARCH_TYPE_ID"] = $this->values["RESEARCH_TYPE_ID"];
            $oConnection->updateTable("DEL", "#pref#_research", 'SITE_ID');
            Pelican_Db::$values = $DBVALUES_MONO;
        }
    }

    /**
     * Configuration de la recherche pour le type de contenu en cours.
     *
     * @access public
     * @public Array
     *
     * @return mixed
     */
    public function getParams()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind = array();
        $aBind[":SITE_ID"] = $this->values['SITE_ID'];
        $aBind[":RESEARCH_TYPE"] = $oConnection->strToBind($this->values["RESEARCH_TYPE"]);
        $aBind[":RESEARCH_TYPE_ID"] = $this->values["RESEARCH_TYPE_ID"];
        $sSQL = "SELECT * FROM #pref#_research_param_field WHERE SITE_ID=:SITE_ID AND RESEARCH_TYPE=:RESEARCH_TYPE AND RESEARCH_TYPE_ID=:RESEARCH_TYPE_ID";
        $research_param_field = $oConnection->queryTab($sSQL, $aBind);
        $sSQL = "SELECT * FROM #pref#_research_param WHERE SITE_ID=:SITE_ID AND RESEARCH_TYPE=:RESEARCH_TYPE AND RESEARCH_TYPE_ID=:RESEARCH_TYPE_ID";
        $research_param = $oConnection->queryRow($sSQL, $aBind);
        if ($research_param_field) {

            /*
             * * Prise en compte des zones à valeurs multiples
             */
            foreach ($research_param_field as $field) {
                if ($field["RESEARCH_MULTI"]) {
                    $multi = explode(",", $field["RESEARCH_MULTI"]);
                    foreach ($multi as $index) {
                        $searchParam[$index][] = $field["RESEARCH_FIELD"];
                    }
                } else {
                    $searchParam[""][] = $field["RESEARCH_FIELD"];
                }
            }
        }
        $this->researchParam = $research_param;
        $this->researchParamField = $searchParam;
    }

    /**
     * __DESC__.
     *
     * @access public
     * @public Array
     *
     * @param __TYPE__ $file __DESC__
     *
     * @return __TYPE__
     */
    public function getContentFromFile($file)
    {
        global $include;
        ini_set('memory_limit', '150M');
        $type = "";
        $return = "";
        if (file_exists($file)) {
            $info = pathinfo($file);
            $ext = trim(strtolower($info["extension"]));
            $type = $ext;
            if (in_array($type, array('pnm', 'pgm', 'pbm', 'ppm', 'pcx', 'tif', 'jpg', 'png', 'gif'))) {
                //$type = 'ocr';
            }
            if (in_array($type, array('html', 'htm', 'log', 'xml', 'csv', 'prn'))) {
                $type = 'txt';
            }
            if (in_array($type, array('xlsx', 'docx', 'pptx'))) {
                $type = 'oxml';
            }
            if (in_array($type, array('odt', 'ods', 'odp'))) {
                $type = 'odf';
            }
            if (in_array($type, array('eps', 'ai'))) {
                $type = 'ps';
            }
            if ($type) {
                $config = dirname(__FILE__).'/'.$type.'/config.php';
                if (file_exists($config)) {
                    if ($include[$type]) {
                        $linux = $include[$type]['linux'];
                        $windows = $include[$type]['windows'];
                        $call = $include[$type]['call'];
                        $extension = $include[$type]['extension'];
                        $output = $include[$type]['output'];
                        $options = $include[$type]['options'];
                        $utf8 = $include[$type]['utf8'];
                    } else {
                        include_once $config;
                        $include[$type]['linux'] = $linux;
                        $include[$type]['windows'] = $windows;
                        $include[$type]['call'] = $call;
                        $include[$type]['extension'] = $extension;
                        $include[$type]['output'] = $output;
                        $include[$type]['options'] = $options;
                        $include[$type]['utf8'] = $utf8;
                    }
                    $functionParse = "parseFile_".$type;
                    if (function_exists($functionParse)) {
                        $return = call_user_func($functionParse, $file);
                    } else {
                        if (Pelican::$config["ENV"]["REMOTE"]["OS"]["win"]) {
                            $cmd = dirname(__FILE__)."/".$type."/bin/windows/".$windows;
                        } else {
                            $cmd = $linux;
                        }
                        $bin = explode(' ', $cmd);
                        if (file_exists($bin[0])) {
                            $dest = tempnam("/tmp", $type.'-php-dest').$extension;
                            $cmd = $cmd." ".($options ? implode(" ", $options) : "")." \"".$file."\" ".$output." \"".$dest."\"";
                            if (Pelican::$config["ENV"]["REMOTE"]["OS"]["win"]) {
                                $cmd = str_replace("/", "\\", $cmd);
                            }
                            system($cmd);
                            //debug($cmd);
                            if (file_exists($dest)) {
                                $return = file_get_contents($dest);
                            }
                            @unlink($dest);
                        }
                    }
                    $functionPostClean = "postClean_".$type;
                    if (function_exists($functionPostClean)) {
                        $return = call_user_func($functionPostClean, $return);
                    }
                }
            }
        }
        if ($return) {
            if (Pelican::$config["CHARSET"] != "UTF-8" && mb_detect_encoding(strip_tags($return)) == 'UTF-8' && $type != 'pdf') { //$return = utf8_decode($return);
            }
            if ($clean) {
                $return = Pelican_Search::cleanSearch($return);
            }
            if ($reduce) {
                $return = Pelican_Search::reduceSearch($return);
            }
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     * @public Array
     *
     * @param __TYPE__ $url __DESC__
     *
     * @return __TYPE__
     */
    public function getContentFromUrl($url)
    {
        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     * @public Array
     *
     * @param __TYPE__ $file __DESC__
     *
     * @return __TYPE__
     */
    public function getContentFromXml($file)
    {
        if (file_exists($file)) {
            $content = implode("", file($file));
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     * @public Array
     *
     * @param __TYPE__ $sSQL __DESC__
     *
     * @return __TYPE__
     */
    public function getContentFromSql($sSQL)
    {
        $oConnection = Pelican_Db::getInstance();
        $result = $oConnection->queryTab($sSQL);

        return $result;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $site __DESC__
     * @param __TYPE__ $word __DESC__
     *
     * @return __TYPE__
     */
    public function logResearch($site, $word)
    {
        $oConnection = Pelican_Db::getInstance();
        Pelican_Db::$values['SITE_ID'] = $site;
        Pelican_Db::$values["RESEARCH_QUERY_STRING"] = strtolower(Pelican_Text::unhtmlentities($word));
        Pelican_Db::$values["RESEARCH_CLEAR_STRING"] = Pelican_Search::cleanSearch($word);
        Pelican_Db::$values["RESEARCH_COUNT"] = 1;
        Pelican_Db::$values["RESEARCH_DATE"] = date('d/m/Y', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
        $aBind = $oConnection->arrayToBind(Pelican_Db::$values);
        /*  $sString = "select count(*) from #pref#_research_log
        where
        SITE_ID = :SITE_ID
        AND RESEARCH_QUERY_STRING = :RESEARCH_QUERY_STRING
        AND RESEARCH_DATE = trunc(SYSDATE)";

        $exists = $oConnection->queryItem($sString,$aBind);*/
        $sString = "update #pref#_research_log
				set RESEARCH_COUNT = RESEARCH_COUNT+1
				where
				SITE_ID = :SITE_ID
				AND RESEARCH_QUERY_STRING = ':RESEARCH_QUERY_STRING'
				AND RESEARCH_DATE = ".$oConnection->dateStringToSql(date('d/m/Y'));
        $oConnection->query($sString, $aBind);
        if (!$oConnection->affectedRows) {
            $oConnection->insertQuery("#pref#_research_log");
        }
    }

    /**
     * __DESC__.
     *
     * @static __DESC__
     * @access public
     *
     * @return __TYPE__
     */
    public function getMediaType()
    {
        $MEDIA["file"] = 1;
        $MEDIA["image"] = 2;
        $MEDIA["flash"] = 3;

        return $MEDIA;
    }

    /**
     * __DESC__.
     *
     * @static __DESC__
     * @access public
     *
     * @param __TYPE__ $value __DESC__
     *
     * @return __TYPE__
     */
    public function cleanSearch($value)
    {
        if ($value) {
            $return = $value;
            $return = str_replace('<br>', ' ', $return);
            $return = str_replace('<br />', ' ', $return);
            $return = str_replace('<hr>', ' ', $return);
            $return = str_replace('<hr />', ' ', $return);
            $return = str_replace('</p>', ' ', $return);
            $return = Pelican_Text::unhtmlentities(strip_tags($return));
            //??// $return = Pelican_Text::cleanText(Pelican_Text::strtolower($return), ' ');
            $return = preg_replace('/( [a-z0-9] )/s', ' ', $return);
            $return = preg_replace('/( [a-z0-9] )/s', ' ', $return);
            $return = preg_replace('/([a-z0-9][a-z0-9])\.([a-z0-9\s])/s', '$1 ', $return); // retrait des points de fin de phrase
            $return = preg_replace('/( \. )/s', ' ', $return);
            $return = preg_replace('/ (au|aux|avec|ce|ces|cet|cette|dans|de|des|du|elle|elles|en|est|et|il|ils|la|le|les|ne|ni|nous|on|ou|que|qui|quoi|sa|se|ses|sur|tu|un|une|vous) /s', ' ', $return);
        }

        return trim($return);
    }

    /**
     * __DESC__.
     *
     * @static __DESC__
     * @access public
     *
     * @param __TYPE__ $content __DESC__
     *
     * @return __TYPE__
     */
    public function reduceSearch($content)
    {
        if ($content) {
            $tmp = explode(" ", $content);
            $return = implode(" ", array_unique($tmp));
        }

        return trim($return);
    }
}
