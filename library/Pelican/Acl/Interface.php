<?php
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Acl
 * @author __AUTHOR__
 */
interface Pelican_Acl_Interface
{

    public function isAllowed ($role = null, $resource = null, $privilege = null);

    public function allow ($roles = null, $resources = null, $privileges = null);

    public function deny ($roles = null, $resources = null, $privileges = null);

    public function addRole ($role, $parents = null);

    public function add ($resource, $parent = null);

    public function roles ();

    public function resources ();
}
