<?php
/**
 * Main class file
 *
 * @package PhpSecInfo
 * @author Ed Finkler <coj@funkatron.com>
 */

/**
 * The default language setting if none is set/retrievable
 *
 */
define ('PHPSECINFO_LANG_DEFAULT', 'en');

/**
 * a general version string to differentiate releases
 *
 */
define ('PHPSECINFO_VERSION', '0.1.1a');

/**
 * a YYYYMMDD date string to indicate "build" date
 *
 */
define ('PHPSECINFO_BUILD', '20061030');

/**
 * This is the main class for the phpsecinfo system.  It's responsible for
 * dynamically loading tests, running those tests, and generating the results
 * output
 *
 * Example:
 * <code>
 * <?php require_once('PhpSecInfo/PhpSecInfo.php'); ?>
 * <?php phpsecinfo(); ?>
 * </code>
 *
 * If you want to capture the output, or just grab the test results and display them
 * in your own way, you'll need to do slightly more work.
 *
 * Example:
 * <code>
 * require_once('PhpSecInfo/PhpSecInfo.php');
 * // instantiate the class
 * $psi = new PhpSecInfo();
 *
 * // load and run all tests
 * $psi->loadAndRun();
 *
 * // grab the results as a multidimensional array
 * $results = $psi->getResultsAsArray();
 * echo "<pre>"; echo print_r($results, true); echo "</pre>";
 *
 * // grab the standard results output as a string
 * $html = $psi->getOutput();
 *
 * // send it to the browser
 * echo $html;
 * </code>
 *
 *
 * The procedural function "phpsecinfo" is defined below this class.
 * @see phpsecinfo()
 *
 * @author Ed Finkler <coj@funkatron.com>
 *
 * v0.1.1a
 * - fix bug in phpsecinfo() where debugging code was left in release.  ugh.
 * - modified test result output to include text version of result type.  Color-only results
 *   don't work in text-based browsers or cases where browser is overriding styles.
 *
 * v0.1.1
 * - Added PhpSecInfo::getOutput(), PhpSecInfo::loadAndRun() and PhpSecInfo::getResultsAsArray() methods
 * - Modified PhpSecInfo::runTests() to fix undefined offsent notices
 * - Modified PhpSecInfo_Test::setMessageForResult() to fix undefined offset notices
 * - Modified PhpSecInfo_Test_Curl_File_Support to skip if PHP version is < 5 (detection of file protocol support relies on PHP5 version of curl_version)
 *
 * v0.1
 * - Initial public release
 *
 */
class PhpSecInfo
{

    /**
     * An array of tests to run
     *
     * @var array PhpSecInfo_Test
     */
    public $tests_to_run = array();

    /**
     * An array of results.  Each result is an associative array:
     * <code>
     * $result['result'] = PHPSECINFO_TEST_RESULT_NOTICE;
     * $result['message'] = "a string describing the test results and what they mean";
     * </code>
     *
     * @var array
     */
    public $test_results = array();


    /**
     * An array of tests that were not run
     *
     * <code>
     * $result['result'] = PHPSECINFO_TEST_RESULT_NOTRUN;
     * $result['message'] = "a string explaining why the test was not run";
     * </code>
     *
     * @var array
     */
    public $tests_not_run = array();


    /**
     * The language code used.  Defaults to PHPSECINFO_LANG_DEFAULT, which
     * is 'en'
     *
     * @var string
     * @see PHPSECINFO_LANG_DEFAULT
     */
    public $language = PHPSECINFO_LANG_DEFAULT;


    /**
     * An array of integers recording the number of test results in each category.  Categories can include
     * some or all of the PHPSECINFO_TEST_* constants.  Constants are the keys, # of results are the values.
     *
     * @var array
     */
    public $result_counts = array();


    /**
     * The number of tests that have been run
     *
     * @var integer
     */
    public $num_tests_run = 0;


    /**
     * Constructor
     *
     * @return PhpSecInfo
     */
    public function PhpSecInfo()
    {
    }


    /**
     * recurses through the Test subdir and includes classes in each test group subdir,
     * then builds an array of classnames for the tests that will be run
     *
     */
    public function loadTests()
    {
        $test_root = dir(dirname(__FILE__).DIRECTORY_SEPARATOR.'Test');

        //echo "<pre>"; echo print_r($test_root, true); echo "</pre>";

        while (false !== ($entry = $test_root->read())) {
            if ( is_dir($test_root->path.DIRECTORY_SEPARATOR.$entry) && !preg_match('|^\.(.*)$|', $entry) ) {
                $test_dirs[] = $entry;
            }
        }
        //echo "<pre>"; echo print_r($test_dirs, true); echo "</pre>";

        // include_once all files in each test dir
        foreach ($test_dirs as $test_dir) {
            $this_dir = dir($test_root->path.DIRECTORY_SEPARATOR.$test_dir);

            while (false !== ($entry = $this_dir->read())) {
                if (!is_dir($this_dir->path.DIRECTORY_SEPARATOR.$entry)) {
                    include_once $this_dir->path.DIRECTORY_SEPARATOR.$entry;
                    $classNames[] = "PhpSecInfo_Test_".$test_dir."_".basename($entry, '.php');
                }
            }

        }

        $this->tests_to_run =& $classNames;
    }


    /**
     * This runs the tests in the tests_to_run array
     *
     */
    public function runTests()
    {
        // initialize a bunch of arrays
        $this->test_results  = array();
        $this->result_counts = array();
        $this->result_counts[PHPSECINFO_TEST_RESULT_NOTRUN] = 0;
        $this->num_tests_run = 0;

        /**
         * @var PhpSecInfo_Test
         */
        foreach ($this->tests_to_run as $testClass) {
            $test = new $testClass();

            if ($test->isTestable()) {
                $test->test();
                $rs = array('result' => $test->getResult(), 'message' => $test->getMessage());
                $this->test_results[$test->getTestGroup()][$test->getTestName()] = $rs;

                // initialize if not yet set
                if (!isset ($this->result_counts[$rs['result']]) ) {
                    $this->result_counts[$rs['result']] = 0;
                }

                $this->result_counts[$rs['result']]++;
                $this->num_tests_run++;
            } else {
                $rs = array('result' => $test->getResult(), 'message' => $test->getMessage());
                $this->result_counts[PHPSECINFO_TEST_RESULT_NOTRUN]++;
                $this->tests_not_run[$test->getTestGroup()."::".$test->getTestName()] = $rs;
            }
        }
    }


    /**
     * This is the main output method.  The look and feel mimics phpinfo()
     *
     */
    public function renderOutput()
    {
        /**
         * We need to use PhpSecInfo_Test::getBooleanIniValue() below
         * @see PhpSecInfo_Test::getBooleanIniValue()
         */
        require_once( dirname(__FILE__).DIRECTORY_SEPARATOR.'Test'.DIRECTORY_SEPARATOR.'Test.php');


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html><head>
<style type="text/css">
body {background-color: #ffffff; color: #000000;}
body, td, th, h1, h2 {font-family: sans-serif;}
pre {margin: 0px; font-family: monospace;}
a:link {color: #000099; text-decoration: none; background-color: #ffffff;}
a:hover {text-decoration: underline;}
table {border-collapse: collapse;}
.center {text-align: center;}
.center table { margin-left: auto; margin-right: auto; text-align: left;}
.center th { text-align: center !important; }
td, th { border: 1px solid #000000; font-size: 75%; vertical-align: baseline;}
h1 {font-size: 150%;}
h2 {font-size: 125%;}
.p {text-align: left;}
.e {background-color: #ccccff; font-weight: bold; color: #000000;}
.h {background-color: #9999cc; font-weight: bold; color: #000000;}
.v {background-color: #cccccc; color: #000000;}
.vr {background-color: #cccccc; text-align: right; color: #000000;}
img {float: right; border: 0px;}
hr {width: 600px; background-color: #cccccc; border: 0px; height: 1px; color: #000000;}

.v-ok {background-color:#009900;color:#ffffff;}
.v-notice {background-color:orange;color:#000000;}
.v-warn {background-color:#990000;color:#ffffff;}
.v-notrun {background-color:#cccccc;color:#000000;}
.v-error {background-color:#F6AE15;color:#000000;font-weight:bold;}

}
</style>
<title>phpsecinfo()</title></head>
<body><div class="center">
<table border="0" cellpadding="3" width="600">
<tr class="h"><td>
<h1 class="p">
<?php if ( PhpSecInfo_Test::getBooleanIniValue('expose_php') ) : ?>
<a href="http://www.php.net/"><img border="0" src="<?php echo '?=' . php_logo_guid() ?>" alt="PHP Logo" /></a>
<?php endif; ?>
PHP Environment Pelican_Security Info
</h1>
<h2 class="p">Version <?php echo PHPSECINFO_VERSION ?>; build <?php echo PHPSECINFO_BUILD ?></h2>
</td></tr>
</table>
<br />
        <?php
        foreach ($this->test_results as $group_name=>$group_results) {
            $this->_outputRenderTable($group_name, $group_results);
        }

        $this->_outputRenderNotRunTable();

        $this->_outputRenderStatsTable();

            ?>

</div></body></html>
        <?php
    }


    /**
     * This is a helper method that makes it easy to output tables of test results
     * for a given test group
     *
     * @param string $group_name
     * @param array  $group_results
     */
    public function _outputRenderTable($group_name, $group_results)
    {
        // exit out if $group_results was empty or not an array.  This sorta seems a little hacky...
        if (!is_array($group_results) || sizeof($group_results) < 1) {
            return false;
        }

        ksort($group_results);

        ?>
        <h2><?php echo htmlspecialchars($group_name, ENT_QUOTES) ?></h2>

        <table border="0" cellpadding="3" width="600">
        <tr class='h'>
            <th>Test</th>
            <th>Result</th>
        </tr>
        <?php foreach ($group_results as $test_name=>$test_results): ?>

        <tr>
            <td class="e"><?php echo htmlspecialchars($test_name, ENT_QUOTES) ?></td>
            <td class="<?php echo $this->_outputGetCssClassFromResult($test_results['result']) ?>">
                <?php if ($group_name != 'Test Results Summary'): ?>
                    <strong><?php echo $this->_outputGetResultTypeFromCode($test_results['result']) ?></strong><br />
                <?php endif; ?>
                <?php echo $test_results['message'] ?>
            </td>
        </tr>

        <?php endforeach; ?>
        </table><br />

        <?php

        return true;
    }



    /**
     * This outputs a table containing a summary of the test results (counts and % in each result type)
     *
     * @see PHPSecInfo::_outputRenderTable()
     * @see PHPSecInfo::_outputGetResultTypeFromCode()
     */
    public function _outputRenderStatsTable()
    {
        foreach ($this->result_counts as $code=>$val) {
            if ($code != PHPSECINFO_TEST_RESULT_NOTRUN) {
                $percentage = round($val/$this->num_tests_run * 100,2);

                $stats[$this->_outputGetResultTypeFromCode($code)] = array( 'count' => $val,
                'result' => $code,
                'message' => "$val out of {$this->num_tests_run} ($percentage%)");
            }
        }

        $this->_outputRenderTable('Test Results Summary', $stats);

    }



    /**
     * This outputs a table containing a summary or test that were not executed, and the reasons why they were skipped
     *
     * @see PHPSecInfo::_outputRenderTable()
     */
    public function _outputRenderNotRunTable()
    {
        $this->_outputRenderTable('Tests Not Run', $this->tests_not_run);

    }




    /**
     * This is a helper function that returns a CSS class corresponding to
     * the result code the test returned.  This allows us to color-code
     * results
     *
     * @param  integer $code
     * @return string
     */
    public function _outputGetCssClassFromResult($code)
    {
        switch ($code) {
            case PHPSECINFO_TEST_RESULT_OK:
                return 'v-ok';
                break;

            case PHPSECINFO_TEST_RESULT_NOTICE:
                return 'v-notice';
                break;

            case PHPSECINFO_TEST_RESULT_WARN:
                return 'v-warn';
                break;

            case PHPSECINFO_TEST_RESULT_NOTRUN:
                return 'v-notrun';
                break;

            case PHPSECINFO_TEST_RESULT_ERROR:
                return 'v-error';
                break;

            default:
                return 'v-notrun';
                break;
        }

    }



    /**
     * This is a helper function that returns a label string corresponding to
     * the result code the test returned.  This is mainly used for the Test
     * Results Summary table.
     *
     * @see PHPSecInfo::_outputRenderStatsTable()
     * @param  integer $code
     * @return string
     */
    public function _outputGetResultTypeFromCode($code)
    {
        switch ($code) {
            case PHPSECINFO_TEST_RESULT_OK:
                return 'Pass';
                break;

            case PHPSECINFO_TEST_RESULT_NOTICE:
                return 'Notice';
                break;

            case PHPSECINFO_TEST_RESULT_WARN:
                return 'Warning';
                break;

            case PHPSECINFO_TEST_RESULT_NOTRUN:
                return 'Not Run';
                break;

            case PHPSECINFO_TEST_RESULT_ERROR:
                return 'Error';
                break;

            default:
                return 'Invalid Result Code';
                break;
        }

    }


    /**
     * Loads and runs all the tests
     *
     * As loading, then running, is a pretty common process, this saves a extra method call
     *
     * @since 0.1.1
     *
     */
    public function loadAndRun()
    {
        $this->loadTests();
        $this->runTests();
    }


    /**
     * returns an associative array of test data.  Four keys are set:
     * - test_results  (array)
     * - tests_not_run (array)
     * - result_counts (array)
     * - num_tests_run (integer)
     *
     * note that this must be called after tests are loaded and run
     *
     * @since 0.1.1
     * @return array
     */
    public function getResultsAsArray()
    {
        $results = array();

        $results['test_results'] = $this->test_results;
        $results['tests_not_run'] = $this->tests_not_run;
        $results['result_counts'] = $this->result_counts;
        $results['num_tests_run'] = $this->num_tests_run;

        return $results;
    }

    /**
     * returns the standard output as a string instead of echoing it to the browser
     *
     * note that this must be called after tests are loaded and run
     *
     * @since 0.1.1
     *
     * @return string
     */
    public function getOutput()
    {
        ob_start();
        $this->renderOutput();
        $output = ob_get_clean();

        return $output;
    }

}

/**
 * A globally-available function that runs the tests and creates the result page
 *
 */
function phpsecinfo()
{
    $psi =& new PhpSecInfo();
    $psi->loadAndRun();
    $psi->renderOutput();
}
