<?php
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Acl
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */
require_once 'Pelican/Acl/Interface.php';
require_once 'Pelican/Exception/Error.php';
require_once 'Pelican/Exception/UnsupportedParameter.php';
require_once 'Pelican/Exception/NullParameter.php';
require_once 'Pelican/Acl/Exception.php';
// require_once ("Zend/Acl.php");
// require_once ('Zend/Acl/Role.php');
// require_once ('Zend/Acl/Resource.php');

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Acl
 * @author Raphael
 */
class Pelican_Acl implements Pelican_Acl_Interface
{

    /**
     *
     * @var unknown_type
     */
    const UNSUPPORTED_ROLE = 'role must be instanceof Zend_Acl_Role_Interface';

    /**
     *
     * @var unknown_type
     */
    const UNSUPPORTED_RESOURCE = 'resource must be instanceof Zend_Acl_Resource_Interface';

    /**
     * __DESC__
     *
     * @access private
     * @var __TYPE__
     */
    private $adapter;

    /**
     * __DESC__
     *
     * @access private
     * @var __TYPE__
     */
    private $resources;

    /**
     * __DESC__
     *
     * @access private
     * @var __TYPE__
     */
    private $roles;

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function __construct ()
    {
        $this->adapter = new Zend_Acl();
    }

    /**
     * __DESC__
     *
     * @access private
     * @return __TYPE__
     */
    private function isOnError ()
    {
        if ($this->adapter == null)
            throw new ErrorException();
        return false;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $role
     *            __DESC__
     * @param string $parents
     *            (option) __DESC__
     * @return __TYPE__
     */
    public function addRole ($role, $parents = null)
    {
        // assert $this->adapter can't be null
        $this->isOnError();
        if ($role == null)
            throw new Pelican_Exception_NullParameter('role');
        try {
            $this->roles[] = $role;
            $this->adapter->addRole(new Zend_Acl_Role($role), $parents);
        } catch (Exception $e) {
            throw new Pelican_Acl_Exception($e->getMessage());
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $role
     *            __DESC__
     * @return __TYPE__
     */
    public function getRole ($role)
    {
        $this->isOnError();
        try {
            return $this->adapter->getRole($role);
        } catch (Exception $e) {
            throw new Pelican_Acl_Exception($e->getMessage());
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $role
     *            __DESC__
     * @return __TYPE__
     */
    public function hasRole ($role)
    {
        $this->isOnError();
        try {
            return $this->adapter->hasRole($role);
        } catch (Exception $e) {
            throw new Pelican_Acl_Exception($e->getMessage());
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $role
     *            __DESC__
     * @param __TYPE__ $inherit
     *            __DESC__
     * @param bool $onlyParents
     *            (option) __DESC__
     * @return __TYPE__
     */
    public function inheritsRole ($role, $inherit, $onlyParents = false)
    {
        $this->isOnError();
        try {
            return $this->adapter->inheritsRole($role, $inherit, $onlyParents);
        } catch (Exception $e) {
            throw new Pelican_Acl_Exception($e->getMessage());
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $role
     *            __DESC__
     * @return __TYPE__
     */
    public function removeRole ($role)
    {
        $this->isOnError();
        try {
            return $this->adapter->removeRole($role);
        } catch (Exception $e) {
            throw new Pelican_Acl_Exception($e->getMessage());
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function removeRoleAll ()
    {
        $this->isOnError();
        try {
            return $this->adapter->removeRoleAll();
        } catch (Exception $e) {
            throw new Pelican_Acl_Exception($e->getMessage());
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $resource
     *            __DESC__
     * @param string $parent
     *            (option) __DESC__
     * @return __TYPE__
     */
    public function add ($resource, $parent = null)
    {
        $this->isOnError();
        if ($resource == null)
            throw new Pelican_Exception_NullParameter('resource');
        try {
            $this->resources[] = $resource;
            if (! $resource instanceof Zend_Acl_Resource_Interface)
                $resource = new Zend_Acl_Resource($resource);
            $this->adapter->add($resource, $parent);
        } catch (Exception $e) {
            throw new Pelican_Acl_Exception($e->getMessage());
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $resource
     *            __DESC__
     * @return __TYPE__
     */
    public function get ($resource)
    {
        $this->isOnError();
        try {
            $this->adapter->get($resource);
        } catch (Exception $e) {
            throw new Pelican_Acl_Exception($e->getMessage());
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $resource
     *            __DESC__
     * @return __TYPE__
     */
    public function has ($resource)
    {
        $this->isOnError();
        if ($resource instanceof Zend_Acl_Resource_Interface) {
            try {
                return $this->adapter->has($resource, $parent);
            } catch (Exception $e) {
                throw new Pelican_Acl_Exception($e->getMessage());
            }
        } else
            throw new Pelican_Exception_UnsupportedParameter(Pelican_Acl::UNSUPPORTED_RESOURCE);
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $resource
     *            __DESC__
     * @param __TYPE__ $inherit
     *            __DESC__
     * @param bool $onlyParent
     *            (option) __DESC__
     * @return __TYPE__
     */
    public function inherits ($resource, $inherit, $onlyParent = false)
    {
        $this->isOnError();
        try {
            return $this->adapter->inherits($resource, $inherit, $onlyParent);
        } catch (Exception $e) {
            throw new Pelican_Acl_Exception($e->getMessage());
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $resource
     *            __DESC__
     * @return __TYPE__
     */
    public function remove ($resource)
    {
        $this->isOnError();
        try {
            return $this->adapter->remove($resource);
        } catch (Exception $e) {
            throw new Pelican_Acl_Exception($e->getMessage());
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function removeAll ()
    {
        $this->isOnError();
        try {
            return $this->adapter->removeAll();
        } catch (Exception $e) {
            throw new Pelican_Acl_Exception($e->getMessage());
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $roles
     *            (option) __DESC__
     * @param string $resources
     *            (option) __DESC__
     * @param string $privileges
     *            (option) __DESC__
     * @return __TYPE__
     */
    public function allow ($roles = null, $resources = null, $privileges = null)
    {
        $this->isOnError();
        try {
            return $this->adapter->allow($roles, $resources, $privileges);
        } catch (Exception $e) {
            throw new Pelican_Acl_Exception($e->getMessage());
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $roles
     *            (option) __DESC__
     * @param string $resources
     *            (option) __DESC__
     * @param string $privileges
     *            (option) __DESC__
     * @return __TYPE__
     */
    public function deny ($roles = null, $resources = null, $privileges = null)
    {
        $this->isOnError();
        try {
            return $this->adapter->deny($roles, $resources, $privileges);
        } catch (Exception $e) {
            throw new Pelican_Acl_Exception($e->getMessage());
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $roles
     *            (option) __DESC__
     * @param string $resources
     *            (option) __DESC__
     * @param string $privileges
     *            (option) __DESC__
     * @return __TYPE__
     */
    public function removeAllow ($roles = null, $resources = null, $privileges = null)
    {
        $this->isOnError();
        try {
            return $this->adapter->removeAllow($roles, $resources, $privileges);
        } catch (Exception $e) {
            throw new Pelican_Acl_Exception($e->getMessage());
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $roles
     *            (option) __DESC__
     * @param string $resources
     *            (option) __DESC__
     * @param string $privileges
     *            (option) __DESC__
     * @return __TYPE__
     */
    public function removeDeny ($roles = null, $resources = null, $privileges = null)
    {
        $this->isOnError();
        try {
            return $this->adapter->removeDeny($roles, $resources, $privileges);
        } catch (Exception $e) {
            throw new Pelican_Acl_Exception($e->getMessage());
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $operation
     *            __DESC__
     * @param __TYPE__ $type
     *            __DESC__
     * @param string $roles
     *            (option) __DESC__
     * @param string $resources
     *            (option) __DESC__
     * @param string $privileges
     *            (option) __DESC__
     * @return __TYPE__
     */
    public function setRule ($operation, $type, $roles = null, $resources = null, $privileges = null)
    {
        $this->isOnError();
        try {
            $this->adapter->setRule($operation, $type, $roles, $resources, $privileges);
        } catch (Exception $e) {
            throw new Pelican_Acl_Exception($e->getMessage());
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $role
     *            (option) __DESC__
     * @param string $resource
     *            (option) __DESC__
     * @param string $privilege
     *            (option) __DESC__
     * @return __TYPE__
     */
    public function isAllowed ($role = null, $resource = null, $privilege = null)
    {
        $this->isOnError();
        try {
            return $this->adapter->isAllowed($role, $resource, $privilege);
        } catch (Exception $e) {
            throw new Pelican_Acl_Exception($e->getMessage());
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function roles ()
    {
        return $this->roles;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function resources ()
    {
        return $this->resources;
    }
}
