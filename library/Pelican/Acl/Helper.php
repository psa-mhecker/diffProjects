<?php
    /**
    * __DESC__
    *
    * @package Pelican
    * @subpackage Acl
    * @author __AUTHOR__
    */
    require_once 'Pelican/Exception/Error.php';
    require_once 'Pelican.php';
    require_once 'Pelican/Acl/Rules.php';

    /**
    * __DESC__
    *
    * @package Pelican
    * @subpackage Acl
    * @author __AUTHOR__
    */
    class Pelican_Acl_Helper
    {
        private static $instance = null;

        const LOAD_RESOURCE_ERROR = 'Erreur de chargement des resources';

        const NOT_IMPLEMENTED = 'L\'objet acl n\'implemente pas l\'interface Pelican_Acl';

        /**
        * @access protected
        * @var __TYPE__ __DESC__
        */
        protected $acl;

        /**
        * __DESC__
        *
        * @access private
        * @return __TYPE__
        */
        private function __construct ()
        {
            if (file_exists($this->getFileStore()))
                $this->unserialize();
        }

        /**
        * __DESC__
        *
        * @access public
        * @param __TYPE__ $rules __DESC__
        * @return __TYPE__
        */
        public function load (PelicanRules $rules)
        {
            $this->acl = Pelican::getService('Acl');
            if ($this->getACL() != null && $this->getACL() instanceof Pelican_Acl) {
                $this->loadRole($rules);
                $this->loadResource($rules);
                $this->allows($rules);
            } else
            throw new ErrorException(Pelican_Acl_Helper::NOT_IMPLEMENTED);
        }

        //chargement des resources

        /**
        * __DESC__
        *
        * @access protected
        * @param __TYPE__ $rules __DESC__
        * @return __TYPE__
        */
        protected function loadResource (PelicanRules $rules)
        {
            try {
                $c = new ReflectionClass($rules->getResourceClassName());
                $constants = $c->getConstants();
                if ($constants != null && is_array($constants)) {
                    foreach ($constants as $const)
                    $this->getACL()->add($const);
                } else
                throw new ErrorException(Pelican_Acl_Helper::LOAD_RESOURCE_ERROR);
            } catch (Pelican_Exception $pe) {
                throw $pe;
            } catch (Exception $e) {
                throw new ErrorException($e->getMessage());
            }
        }

        //chargement des droits

        /**
        * __DESC__
        *
        * @access protected
        * @param __TYPE__ $rules __DESC__
        * @return __TYPE__
        */
        protected function loadRole (PelicanRules $rules)
        {
            try {
                $method = new ReflectionMethod($rules->getRolesClassName(), 'getRoles');
                $roles = $method->invoke(null);
                //ajout hierarchique des droits
                if (is_array($roles) && count($roles) > 0) {
                    foreach ($roles as $role)
                    $this->getACL()->addRole($role[0], $role[1]);
                }
            } catch (Pelican_Exception $pe) {
                throw $pe;
            } catch (Exception $e) {
                throw new ErrorException($e->getMessage());
            }
        }

        /**
        * __DESC__
        *
        * @access protected
        * @param __TYPE__ $rules __DESC__
        * @return __TYPE__
        */
        protected function allows (PelicanRules $rules)
        {
            if ($rules != null && is_array($rules->getRules())) {
                foreach ($rules->getRules() as $resource => $value) {
                    foreach ($value as $role)
                    $this->getACL()->allow($role, $resource);
                }
            }
        }

        /**
        * __DESC__
        *
        * @access public
        * @return __TYPE__
        */
        public function getACL ()
        {
            return $this->acl;
        }

        /**
        * __DESC__
        *
        * @static
        * @access public
        * @return __TYPE__
        */
        public static function instance ()
        {
            if (Pelican_Acl_Helper::$instance == null)
                Pelican_Acl_Helper::$instance = new Pelican_Acl_Helper();

            /**
            * @static
            * @access private
            * @var __TYPE__ __DESC__
            */

            return Pelican_Acl_Helper::$instance;
        }

        /**
        * __DESC__
        *
        * @access private
        * @return __TYPE__
        */
        private function getFileStore ()
        {
            return Pelican::$config['PELICAN_ROOT'] . '/Acl/acl.store';
        }

        /**
        * __DESC__
        *
        * @access public
        * @return __TYPE__
        */
        public function serialize ()
        {
            $s = serialize($this->getACL());
            $fp = fopen($this->getFileStore(), 'a');
            fwrite($fp, $s);
            fclose($fp);
        }

        /**
        * __DESC__
        *
        * @access public
        * @return __TYPE__
        */
        public function unserialize ()
        {
            $s = implode("", @file($this->getFileStore()));
            $this->ACL = unserialize($s);
        }
    }
