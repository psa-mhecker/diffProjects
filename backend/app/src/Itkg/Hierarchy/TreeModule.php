<?php

namespace Itkg\Hierarchy;

use Pelican_Html;

/**
 * Cette classe permet de définir les méthodes d'utilisation des couches de
 * présentation utilisées par la classe Pelican_Hierarchy_Tree.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 15/12/2003
 */
class TreeModule
{
    public $id;

    public $aCSS = array();

    public $aJS = array();

    public $aAdd = array();

    /**
     * @var array
     */
    public $aAddParams = array();

    public $fStart = '';

    public $fEnd = '';

    /**
     * @var string
     */
    public $fAddStart = '';

    /**
     * @var string
     */
    public $fEndStart = '';

    public $fAddEnd = '';

    public $idName = 'id';

    public $pidName = 'pid';

    public $requiredParam = '';

    /**
     * @var array
     */
    public $aPosition = array();

    public $iIncrCorrection = 0;
    /**
     * @var array
     */
    public $aConfig;

    /**
     * @param mixed $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * __DESC__.
     *
     *
     * @param mixed $sStart
     *                      Unknown
     */
    public function setStart($sStart)
    {
        $this->fStart = $sStart;
    }

    /**
     * __DESC__.
     *
     *
     * @return mixed
     */
    public function getStart()
    {
        return $this->fStart;
    }

    /**
     * __DESC__.
     *
     *
     * @param mixed $sEnd
     *                    Unknown
     */
    public function setEnd($sEnd)
    {
        $this->fEnd = $sEnd;
    }

    /**
     * __DESC__.
     *
     *
     * @return mixed
     */
    public function getEnd()
    {
        return $this->fEnd;
    }

    /**
     * __DESC__.
     *
     *
     * @param string $sAddStart
     * @param string $sAddEnd
     * @param array  $aAddParams
     */
    public function setAdd($sAddStart, $sAddEnd, $aAddParams)
    {
        $this->fAddStart = $sAddStart;
        $this->fAddEnd = $sAddEnd;
        $this->aAddParams = $aAddParams;
    }

    /**
     * __DESC__.
     *
     *
     * @param array $aAddParams
     */
    public function setParams(array $aAddParams)
    {
        $this->aAddParams = $aAddParams;
    }

    /**
     * __DESC__.
     *
     *
     * @param mixed $sId
     *                   Unknown
     */
    public function setParamId($sId)
    {
        $this->idName = $sId;
    }

    /**
     * __DESC__.
     *
     *
     * @param mixed $sPid
     *                    Unknown
     */
    public function setParamPid($sPid)
    {
        $this->pidName = $sPid;
    }

    /**
     * __DESC__.
     *
     *
     * @param string $idName
     * @param string $pidName
     * @param array  $aPosition
     */
    public function setIncrement($idName, $pidName, $aPosition)
    {
        $this->setParamId($idName);
        $this->setParamPid($pidName);
        $this->aPosition = $aPosition;
    }

    /**
     * __DESC__.
     *
     *
     * @param array $aValues
     *                       Unknown
     *
     * @return string
     */
    public function getAdd($aValues)
    {
        $return = $this->fAddStart;
        $vall = array();
        $values = array();
        foreach ($this->aAddParams as $param) {
            if ($param == $this->idName || $param == $this->pidName) {
                $temp = ($this->aPosition[$aValues[$param]] ? $this->aPosition[$aValues[$param]] : 0) + $this->iIncrCorrection;
                if ($param == $this->idName) {
                    $vall[$aValues[$param]] = ($this->aPosition[$aValues[$param]] ? $this->aPosition[$aValues[$param]] : 0) + $this->iIncrCorrection;
                }
                $values[] = $temp;
            } else {
                if (isset($aValues[$param])) {
                    $values[] = "'".str_replace("'", "\\'", $aValues[$param])."'";
                } else {
                    $values[] = "''";
                }
            }
        }
        $return .= implode(',', $values);
        $return .= $this->fAddEnd;
        $return = str_replace("'false'", 'false', $return);
        $return = str_replace("'true'", 'true', $return);
        $toReplace = ["\n" => '', "\r" => '', "\r\n" => ''];
        $return = trim(strtr($return, $toReplace));

        return $return;
    }

    /**
     * __DESC__.
     *
     *
     * @param mixed $aValues
     */
    public function add($aValues)
    {
        $this->aAdd[] = $this->getAdd($aValues);
    }

    /**
     * __DESC__.
     *
     *
     * @param string $cssPath
     */
    public function setCSS($cssPath)
    {
        $this->aCSS[] = $cssPath;
    }

    /**
     * @return string
     */
    public function getCSS()
    {
        $return = '';
        if (!empty($this->aCSS)) {
            foreach ($this->aCSS as $css) {
                $return .= '<link type="text/css" href="'.$css."\" rel=\"stylesheet\" />\n";
            }
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     *
     * @param string $aJS
     *                    Unknown
     */
    public function setJS($aJS)
    {
        $this->aJS[] = $aJS;
    }

    /**
     * __DESC__.
     *
     *
     * @return string
     */
    public function getJS()
    {
        $return = '';
        if (!empty($this->aJS)) {
            foreach ($this->aJS as $JS) {
                $return .= Pelican_Html::script(array(
                        'src' => $JS,
                    ))."\n";
            }
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     *
     * @param array $aConfig
     *                       Unknown
     */
    public function setConfig($aConfig)
    {
        $this->aConfig[] = $aConfig;
    }

    /**
     * __DESC__.
     *
     *
     * @return string
     */
    public function getConfig()
    {
        $return = '';
        if (isset($this->aConfig)) {
            foreach ($this->aConfig as $key => $value) {
                if (!is_numeric($value)) {
                    $value = '"'.$value.'"';
                }
                $return .= $key.'='.$value.";\n";
            }
        }

        return $return;
    }
}
