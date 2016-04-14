<?php
/**
 * __DESC__.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */

/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */
class Pelican_Mapper
{
    /**
     * @access private
     *
     * @var __TYPE__ __DESC__
     */
    private $_map;

    /**
     * @access private
     *
     * @var __TYPE__ __DESC__
     */
    private $_result;

    /**
     * @access private
     *
     * @var __TYPE__ __DESC__
     */
    private $_preFilter;

    /**
     * @access private
     *
     * @var __TYPE__ __DESC__
     */
    private $_postFilter;

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $map __DESC__
     *
     * @return __TYPE__
     */
    public function __construct($map)
    {
        $this->_map = $map;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $func __DESC__
     *
     * @return __TYPE__
     */
    public function setPostFilter($func)
    {
        $this->_postFilter = $func;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $func __DESC__
     *
     * @return __TYPE__
     */
    public function setPreFilter($func)
    {
        $this->_preFilter = $func;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $data __DESC__
     *
     * @return __TYPE__
     */
    public function map($data)
    {
        // Pre Filter
        if (isset($this->_preFilter)) {
            try {
                $data = call_user_func($this->_preFilter, $data);
            } catch (Exception $e) {
                error_log('library/Pelican/Mapper.php : ERREUR Pelican_Mapper prefilter !');
                // Aucune transformation sur les donnees si ca plante !
            }
        }
        // Mapping
        if (!isset($this->_map)) {
            return $data;
        }
        $this->_result = array();
        $authorizedKeys = array_keys($this->_map);
        $this->params = array();
        if (is_array($data)) {
            $cpt = 0;
            foreach ($data as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $akey => $value) {
                        if (in_array($akey, $authorizedKeys)) {
                            // Cas normal : le champs doit être mappé vers 1 champs destination
                            if (!is_array($this->_map[$akey])) {
                                $this->_result[$cpt][$this->_map[$akey]] = $value;
                                // Cas spécial : Le champs doit être mappé vers plusieurs champs
                            } else {
                                foreach ($this->_map[$akey] as $finalKey) {
                                    $this->_result[$cpt][$finalKey] = $value;
                                }
                            }
                        }
                    }
                } else {
                    if (in_array($key, $authorizedKeys)) {
                        $this->_result[$this->_map[$key]] = $val;
                    }
                }
                $cpt++;
            }
        }
        // Post Filter
        if (isset($this->_postFilter) && function_exists($this->_postFilter)) {
            try {
                $this->_result = call_user_func($this->_postFilter, $this->_result);
            } catch (Exception $e) {
                error_log('library/Pelican/Mapper.php : ERREUR Pelican_Mapper postfilter !');
                // Aucune transformation sur les donnees si ca plante !
            }
        }

        return $this->_result;
    }
}
