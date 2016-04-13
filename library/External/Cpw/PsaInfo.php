<?php
/**
 * PSA SA
 *
 * LICENSE
 */



Class Cpw_PsaInfo
{
	/**
	 * return a string reprensenting the executing environnement
	 * 'DEV' : running a dev server in local (like in a WAMP server)
	 * 'INT' : running in a developement / CDD environnement
	 * 'REC' : running in a test (receipe) / CDD environnement
	 * 'PRE' : running in a preproduction environnement
	 * 'PRO' : running in production 
	 * 'nde' : not defined (check .htaccess)
	 * '???' : unknown environnement
	 * The running environnement is read from the Apache 'APPLICATION_ENV' variable 
	 * which is set in the .htaccess at the root level of the application
	 */
	public static function getRunningEnvironnement()	
	{
		$cur_env = @$_SERVER['APPLICATION_ENV'];
		if ('' === $cur_env)
			return 'nde';
		if (in_array($cur_env, array('DEV', 'INT', 'REC', 'PRE', 'PRO')))
			return $cur_env;
		return '???';
		
	}
}
