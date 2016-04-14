<?php
/**
 * Profiling interne.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */
define('PROFILE_FORMAT_TIME', "%.4f sec.");

/**
 * Profiling interne.
 *
 * @author __AUTHOR__
 */
class Pelican_Profiler
{
    /**
     * Collected benchmarks.
     *
     * @static
     * @access public
     *
     * @var __TYPE__
     */
    public static $marks = array();

    /**
     * __DESC__.
     *
     * @static
     * @access private
     *
     * @var __TYPE__
     */
    public static $_cumul = array();

    /**
     * __DESC__.
     *
     * @static
     * @access protected
     *
     * @var __TYPE__
     */
    protected static $_control = array();

    /**
     * __DESC__.
     *
     * @static
     * @access protected
     *
     * @var __TYPE__
     */
    protected static $_count = array();

    /**
     * Starts a new benchmark and returns a unique token.
     *
     * @static
     * @access public
     *
     * @param string $name  Group name
     * @param string $group (option) benchmark name
     *
     * @return string
     */
    public static function start($name, $group = 'html')
    {
        if (!isset(self::$_count[$group.'::'.$name])) {
            self::$_count[$group.'::'.$name] = 0;
        }
        self::$_count[$group.'::'.$name]++;
        self::$marks[$group][$name.'::'.self::$_count[$group.'::'.$name]]['start'] = array('time' => microtime(true), 'memory' => memory_get_usage());
        //self::$_control[$group][$name] = $_count;
        return true;
    }

    /**
     * Stops a benchmark.
     *
     * @static
     * @access public
     *
     * @param __TYPE__ $name  __DESC__
     * @param __TYPE__ $group (option) __DESC__
     */
    public static function stop($name, $group = 'html')
    {
        if (!isset(self::$_cumul[$group])) {
            self::$_cumul[$group] = 0;
        }
        if (!isset(self::$_count[$group.'::'.$name])) {
            self::$_count[$group.'::'.$name] = 0;
        }
        if (!isset(self::$marks[$group][$name.'::'.self::$_count[$group.'::'.$name]])) {
            self::$marks[$group][$name.'::'.self::$_count[$group.'::'.$name]] = array();
        }
        self::$marks[$group][$name.'::'.self::$_count[$group.'::'.$name]]['stop'] = array('time' => microtime(true), 'memory' => memory_get_usage());
        if (!isset(self::$marks[$group][$name.'::'.self::$_count[$group.'::'.$name]]['start'])) {
            self::$marks[$group][$name.'::'.self::$_count[$group.'::'.$name]]['start'] = array('time' => 0, 'memory' => 0);
        }
        self::$_cumul[$group] += self::$marks[$group][$name.'::'.self::$_count[$group.'::'.$name]]['stop']['time'] - self::$marks[$group][$name.'::'.self::$_count[$group.'::'.$name]]['start']['time'];
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param __TYPE__ $name  __DESC__
     * @param __TYPE__ $new   __DESC__
     * @param __TYPE__ $group (option) __DESC__
     *
     * @return __TYPE__
     */
    public static function rename($name, $new, $group = 'html')
    {
        if (!isset(self::$_count[$group.'::'.$new])) {
            self::$_count[$group.'::'.$new] = 0;
        }
        self::$_count[$group.'::'.$new]++;
        self::$marks[$group][$new.'::'.self::$_count[$group.'::'.$new]] = self::$marks[$group][$name.'::'.self::$_count[$group.'::'.$name]];
        self::delete($name, $group);
    }

    /**
     * Deletes a benchmark.
     *
     * @static
     * @access public
     *
     * @param __TYPE__ $name  __DESC__
     * @param __TYPE__ $group (option) __DESC__
     */
    public static function delete($name, $group = 'html')
    {
        // Remove the benchmark
        unset(self::$marks[$group][$name.'::'.self::$_count[$group.'::'.$name]]);
    }

    /**
     * Returns all the benchmark tokens by group and name as an array.
     *
     * @static
     * @access public
     *
     * @return array
     */
    public static function groups()
    {
        $groups = array_keys(self::$marks);

        return $groups;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param string   $group         (option) __DESC__
     * @param __TYPE__ $show          (option) __DESC__
     * @param bool     $outputComment (option) __DESC__
     *
     * @return __TYPE__
     */
    public static function summary($group = '', $show = array('time', 'memory', 'total', 'percent', 'order'), $outputComment = false)
    {
        $summary = array();
        if ($group) {
            $levels = explode('.', $group);
            $tmp = & $summary;
            $cumul = 0;
            $count = 0;
            /* foreach ($levels as $level) {
            $tmp = &$tmp[$level];
            }*/
            if (is_array(self::$marks[$group])) {
                foreach (self::$marks[$group] as $name => $val) {
                    $count++;
                    // Sort the tokens by the group and name
                    list($time, $clearTime, $memory) = self::total($name, $group);
                    $tmp[$count.' - '.self::cleanName($name) ] = $clearTime;
                    if (in_array('percent', $show)) {
                        $tmp[$count.' - '.self::cleanName($name) ] .= ' -> '.self::getPercent($time, self::$_cumul[$group]).' %';
                    }
                }
            }
            if (in_array('total', $show)) {
                $tmp['[global]'] = sprintf(PROFILE_FORMAT_TIME, self::$_cumul[$group]);
            }
            arsort($tmp);
        } else {
            foreach (self::$marks as $group => $val1) {
                $cumul[$group] = 0;
                $levels = explode('.', $group);
                $tmp = & $summary;
                foreach ($levels as $level) {
                    $tmp = & $tmp[$level];
                }
                foreach ($val1 as $name => $val2) {
                    // Sort the tokens by the group and name
                    list($time, $clearTime, $memory) = self::total($name, $group);
                    $tmp[self::cleanName($name) ] = $clearTime;
                    if (in_array('percent', $show)) {
                        $tmp[self::cleanName($name) ] .= ' -> '.self::getPercent($time, self::$_cumul[$group]).' %';
                    }
                    //$cumul[$group] += $time;
                }
                if (in_array('total', $show)) {
                    $tmp['[global]'] = sprintf(PROFILE_FORMAT_TIME, self::$_cumul[$group]);
                }
            }
        }
        /*       if ($outputComment) {
        $return = "\n<!-- " . $name . "\n";
        $return .= str_replace('/', ' -> ', str_replace(array('.php' , '/layout/' , 'Array'), array('' , '' , ''), print_r($aData, true)));
        $return .= "-->\n";
        echo str_replace("\n\n", "\n", $return);
        }*/
        return $summary;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param __TYPE__ $name __DESC__
     *
     * @return __TYPE__
     */
    public static function cleanName($name)
    {
        $return = $name;
        $return = str_replace(array('::1', '::'), array('', ' -> '), $name);

        return $return;
    }

    /**
     * Gets the total execution time and memory usage of a benchmark as a list.
     *
     * @static
     * @access public
     *
     * @param __TYPE__ $name  __DESC__
     * @param __TYPE__ $group (option) __DESC__
     *
     * @return array
     */
    public static function total($name, $group = 'html')
    {
        if (empty(self::$marks[$group][$name]['stop']['time'])) {
            // The benchmark n'a pas ete arrete
            self::$marks[$group][$name]['stop']['time'] = microtime(true);
            self::$marks[$group][$name]['stop']['memory'] = memory_get_usage();
        }
        $mark = self::$marks[$group][$name];
        $time = ($mark['stop']['time'] - $mark['start']['time']);

        return array($time, sprintf(PROFILE_FORMAT_TIME, ($time)), $mark['stop']['memory'] - $mark['start']['memory']);
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @return __TYPE__
     */
    public static function cumul()
    {
        return self::total('[global]::1');
    }

    /**
     * __DESC__.
     *
     * @access private
     *
     * @return __TYPE__
     */
    final private function __construct()
    {
    }

    /**
     * __DESC__.
     *
     * @access private
     *
     * @return __TYPE__
     */
    final private function __destruct()
    {
        Pelican_Profiler::stop('[global]');
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param __TYPE__ $value __DESC__
     * @param __TYPE__ $total __DESC__
     *
     * @return __TYPE__
     */
    public static function getPercent($value, $total)
    {
        if ($total > 0) {
            return round(($value / $total) * 10000) / 100;
        }
    }
}

/**
 * __DESC__.
 *
 * @return __TYPE__
 */
function getmicrotime()
{
    list($usec, $sec) = explode(" ", microtime());

    return ((float) $usec + (float) $sec);
}
Pelican_Profiler::start('[global]');
