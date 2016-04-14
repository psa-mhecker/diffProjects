<?php
/**
 * Classe décrivant un Bean n'ayant qu'un attribut : un tableau de données
 * qui a un équivalent stocké en session.
 *
 * @author Gilles LENORMAND
 *
 * @since 2008-12-01
 */
require_once 'Zend/Exception.php';
require_once 'Pelican/Data.php';

/**
 * Classe décrivant un Bean n'ayant qu'un attribut : un tableau de données
 * qui a un équivalent stocké en session.
 *
 * @author Gilles LENORMAND
 *
 * @since 2008-12-01
 */
class Pelican_Session_Data extends Pelican_Data
{
    /**
     * Namespace de session où stocker les données.
     *
     * @access protected
     *
     * @var string
     */
    protected $_sessionNameSpace;

    /**
     * Member du namespace de session où stocker les données.
     *
     * @access protected
     *
     * @var string
     */
    protected $_sessionMember;

    /**
     * Constructeur.
     *
     * @access public
     *
     * @param $sessionNameSpace string
     *       	 __DESC__
     * @param $sessionMember string
     *       	 __DESC__
     * @param $aData array
     *       	 (option) __DESC__
     *
     * @return __TYPE__
     */
    public function __construct($sessionNameSpace, $sessionMember, $aData = "")
    {
        if (! empty($aData)) {
            parent::__construct($aData);
        }
        $this->_sessionMember = $sessionMember;
        $this->_sessionNameSpace = $sessionNameSpace;
        if (! empty($aData)) {
            $this->setSessionData();
        }
    }

    /**
     * Modifie le tableau de données équivalent en session.
     *
     * @access private
     *
     * @return __TYPE__
     */
    private function setSessionData()
    {
        $_SESSION [$this->_sessionNameSpace] [$this->_sessionMember] = $this->_aData;
    }

    /**
     * Modifie le tableau de données.
     *
     * @access public
     *
     * @param $aData array
     *       	 __DESC__
     *
     * @return __TYPE__
     */
    public function setData($aData)
    {
        parent::setData($aData);
        $this->setSessionData();
    }

    /**
     * Setter générique.
     *
     * @access public
     *
     * @param $dataField string
     *       	 __DESC__
     * @param $dataValue mixed
     *       	 __DESC__
     *
     * @return int
     */
    public function set($dataField, $dataValue)
    {
        $code = parent::set($dataField, $dataValue);
        if ($code != Pelican_Data::DATA_NOT_MODIFIED) {
            // si les données ont été modifiées ou initialisées, on les reporte
            // en session
            $_SESSION [$this->_sessionNameSpace] [$this->_sessionMember] [$dataField] = $dataValue;
        } else { // rien
        }
    }

    public function get($dataField)
    {
        if (isset($_SESSION [$this->_sessionNameSpace] [$this->_sessionMember] [$dataField])) {
            return $_SESSION [$this->_sessionNameSpace] [$this->_sessionMember] [$dataField];
        } else {
            return false;
        }
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function isEmpty()
    {
        return empty($_SESSION [$this->_sessionNameSpace] [$this->_sessionMember]);
    }
}
