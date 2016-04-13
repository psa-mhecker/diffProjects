<?php
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Acl
 * @author __AUTHOR__
 */

interface Pelican_Rules
{

    public static function getRules ();

    public static function getResourceClassName ();

    public static function getRolesClassName ();
}

interface Pelican_Acl_Roles_Interface
{

    public static function getRoles ();
}
