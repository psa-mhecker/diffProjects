<?php
/**
 * Skeleton Test class file for ` group.
 *
 * @author Ed Finkler <coj@funkatron.com>
 */

/**
 * require the main PhpSecInfo class.
 */
require_once dirname(__FILE__).'/Test.php';

/**
 * This is a skeleton class for PhpSecInfo "Curl" tests.
 */
class PhpSecInfo_Test_Curl extends PhpSecInfo_Test
{
    /**
     * This value is used to group test results together.
     *
     * For example, all tests related to the Pelican_Db_Mysql lib should be grouped under "mysql."
     *
     * @var string
     */
    public $test_group = 'Curl';

    /**
     * "Curl" tests should only be run if the curl extension is installed.  We can check
     * for this by seeing if the function curl_init() is defined.
     *
     * @return boolean
     */
    public function isTestable()
    {
        if (function_exists('curl_init')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set the messages for Curl tests.
     */
    public function _setMessages()
    {
        parent::_setMessages();

        $this->setMessageForResult(PHPSECINFO_TEST_RESULT_NOTRUN, 'en', "CURL support is not enabled in your PHP install");
    }
}
