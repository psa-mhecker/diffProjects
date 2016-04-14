<?php

/**
 * Classe de gestion d'un utilisateur de l'a plateforme
 *
 * @package    Pelican
 * @subpackage User
 * @copyright  Copyright (c) 2001-2012 Business&Decision
 * (http://www.businessdecision.com)
 * @license    http://www.interakting.com/license/phpfactory
 * @link       http://www.interakting.com
 */

/**
 * Classe de gestion d'un utilisateur de la plateforme
 *
 * @package    Pelican
 * @subpackage User
 * @author     Khadidja Messaoudi <khadidja.messaoudi@businessdecision.com>
 */
class Psa_User_Backoffice extends Pelican_User_Backoffice
{

    protected $_login;

    private $_password;

    /**
     * Construtor of the Psa_User_Backoffice class
     *
     * @param string $login
     * @param string $password
     */
    public function __construct($login, $password)
    {
        $this->_login = $login;
        $this->_password = $password;
    }

    public function login($id, $password, $bIsLdap = false)
    {
        $auth = Pelican_Auth::getInstance();
        $authAdapter = new Psa_Auth_Adapter_Db_Basic();
        $authAdapter->setIdentity($id);
        $authAdapter->setCredential($password);
        $authAdapter->setIsLdap($bIsLdap);
        $authAdapter->setLdapField('IS_LDAP');
        $authAdapter->setConfig(array(
            '#pref#_user',
            'USER_LOGIN',
            'USER_PASSWORD',
            'MD5(?)'
        ));

        // Pour gestion du stockage des données d'authentification en session
        $authStorage = new Pelican_Auth_Storage_Session(APP, $this->session_label);
        $auth->setStorage($authStorage);
        $result = $auth->authenticate($authAdapter);
        $this->setData($_SESSION[APP][$this->session_label]);
        $this->getRights();
        return ($result);
    }

    /**
     * Cherche existence d'un utilisateur
     *
     * @access public
     *
     * @param string $login
     *            Login
     * @param string $password
     *            Mot de passe
     *
     * @return $bReturn bool true s'il existe
     */
    public function checkUser($login, $password)
    {
        $auth = Pelican_Auth::getInstance();
        $authAdapter = new Pelican_Auth_Adapter_Db_Basic();
        $authAdapter->setIdentity($login);
        $authAdapter->setCredential($password);
        $authAdapter->setConfig(array(
            '#pref#_user',
            'USER_LOGIN',
            'USER_PASSWORD',
            'MD5(?)'
        ));
        // Pour gestion du stockage des donn?es d'authentification en session
        $result = $auth->authenticate($authAdapter);

        return !empty($result);
    }

    /**
     * Supprime un utilisateur
     *
     * @access public
     *
     * @param string $login
     *            Login
     */
    public function deleteUser($login)
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':LOGIN'] = $oConnection->strToBind($login);
        // 1 - Suppression du p?rim?tre d'intervention de l'utilisateur sur les pages et contenus
        $sqlDeleteInPage = "
        UPDATE
        #pref#_page
        SET
        PAGE_CREATION_USER = REPLACE(PAGE_CREATION_USER, '#".$login."#','')
        WHERE PAGE_CREATION_USER like '%#".$login."#%'
        ";
        $oConnection->query($sqlDeleteInPage);
        $sqlDeleteInContent = "
        UPDATE
        #pref#_content
        SET
        CONTENT_CREATION_USER = REPLACE(CONTENT_CREATION_USER, '#".$login."#','')
        WHERE
        CONTENT_ID in (SELECT #pref#_content_version.CONTENT_ID from #pref#_content_version)
        AND CONTENT_CREATION_USER like '%#".$login."#%'
        ";
        $oConnection->query($sqlDeleteInContent);
        //$sSQL = "delete FROM #pref#_user_zone_template WHERE user_id = :LOGIN";
        //$oConnection->query($sSQL, $aBind);
        //$sSQL = "delete FROM #pref#_user_page_zone WHERE user_id = :LOGIN";
        //$oConnection->query($sSQL, $aBind);
        $sSQL = "delete FROM #pref#_user_role WHERE user_login = :LOGIN";
        $oConnection->query($sSQL, $aBind);
        $sSQL = "delete FROM #pref#_user_profile WHERE user_login = :LOGIN";
        $oConnection->query($sSQL, $aBind);
        $sSQL = "delete FROM #pref#_user WHERE user_login = :LOGIN";
        $oConnection->query($sSQL, $aBind);
    }

    /**
     * Cr?e un utilisateur
     *
     * @access public
     *
     * @param array  $aParams
     * @param string $password
     *            password
     */
    public function createUser($aParams)
    {
        $aParams['rights'] = $this->initMultipleRights($aParams['rights']);

        $oConnection = Pelican_Db::getInstance();
        $aBind[":USER_LOGIN"] = $oConnection->strTobind($aParams['login']);
        $aBind[":USER_PASSWORD"] = $oConnection->strTobind(md5($aParams['password']));
        $aBind[":USER_NAME"] = $oConnection->strTobind($aParams['name']);
        $aBind[":USER_EMAIL"] = $oConnection->strTobind($aParams['email']);
        $aBind[":USER_ENABLED"] = 1;
        $aBind[":USER_INFOS"] = '';
        $aBind[":USER_FULL"] = ($aParams['admin'] == true) ? 1 : 0;
        $aBind[":IS_LDAP"] = (int)$aParams['is_ldap'];
        // $aBind[":USER_FULL"] = 1;
        $aBind[":SITE_ID"] = 1;

        $sSQL = 'INSERT INTO #pref#_user (USER_LOGIN, USER_PASSWORD, USER_NAME, USER_ENABLED, USER_EMAIL, USER_INFOS, USER_FULL, SITE_ID,IS_LDAP) values (:USER_LOGIN, :USER_PASSWORD, :USER_NAME, :USER_ENABLED, :USER_EMAIL, :USER_INFOS, :USER_FULL, :SITE_ID,:IS_LDAP)';
        $oConnection->query($sSQL, $aBind);
        $aContentType = self::_getContentTypeBySite();
        $aSite = self::_getSites();
        $aProfile = self::_getProfilesBySites();
        unset($_SESSION['LDAP']);
        if ($aParams['admin'] == true) {
            $_SESSION['LDAP']['ADMIN'] = true;
            $aBind[':ROLE_ID'] = 7;
            // Gestion des r?les
            if (is_array($aSite) && count($aSite) > 0) {
                foreach ($aSite as $site_id) {
                    $aBind[':SITE_ID'] = $site_id;
                    if (is_array($aContentType) && count($aContentType) > 0) {
                        foreach ($aContentType as $contentType) {
                            $aBind[':CONTENT_TYPE_ID'] = $contentType;
                            $sSQL = 'INSERT INTO #pref#_user_role (USER_LOGIN, ROLE_ID, CONTENT_TYPE_ID, SITE_ID) values (:USER_LOGIN, :ROLE_ID, :CONTENT_TYPE_ID, :SITE_ID)';
                            $oConnection->query($sSQL, $aBind);
                        }
                    }
                }
            }
            // Gestion des profils
            if (is_array($aSite) && count($aSite) > 0) {
                foreach ($aSite as $site) {
                    if (is_array($aProfile[$site]) && count($aProfile[$site]) > 0) {
                        foreach ($aProfile[$site] as $profil_id => $profil_label) {
                            $aBind[':PROFILE_ID'] = $profil_id;
                            $sSQL = 'INSERT INTO #pref#_user_profile (USER_LOGIN, PROFILE_ID) values (:USER_LOGIN, :PROFILE_ID)';
                            $oConnection->query($sSQL, $aBind);
                        }
                    }
                }
            }
            // Gestion des pages/Contenus
            // Maj du champ PAGE_CREATION_USER pour la rubrique
            $this->aBind[':PAGE_ID'] = $page['PAGE_ID'];
            $sqlSavePage = "
            UPDATE
            #pref#_page
            SET
            PAGE_CREATION_USER = ".$oConnection->getConcatClause(array(
                    "PAGE_CREATION_USER",
                    "'#".$aParams['login']."#'"
                ))."";
            $oConnection->query($sqlSavePage, $this->aBind);

            // Maj du champ CONTENT_CREATION_USER pour les contenus de la rubrique
            $sqlSaveContent = "
            UPDATE
            #pref#_content
            SET
            CONTENT_CREATION_USER = ".$oConnection->getConcatClause(array(
                    "CONTENT_CREATION_USER",
                    "'#".$aParams['login']."#'"
                ))."";
            $oConnection->query($sqlSaveContent, $this->aBind);
        } else {
            /**
             * Gestion des r?les / profils / droits par m?tier
             */
            $aRights = self::_getRights($aParams['rights']);

            if (is_array($aRights['PROFILES']) && count($aRights['PROFILES']) > 0) {
                foreach ($aRights['PROFILES'] as $pays => $profiles) {
                    $aBind[':SITE_ID'] = $pays;
                    $aBind[':ROLE_ID'] = $aRights['ROLE'][$pays];
                    // Gestion des r?les
                    if (is_array($aContentType) && count($aContentType) > 0) {
                        foreach ($aContentType as $contentType) {
                            $aBind[':CONTENT_TYPE_ID'] = $contentType;
                            $sSQL = 'INSERT INTO #pref#_user_role (USER_LOGIN, ROLE_ID, CONTENT_TYPE_ID, SITE_ID) values (:USER_LOGIN, :ROLE_ID, :CONTENT_TYPE_ID, :SITE_ID)';
                            $oConnection->query($sSQL, $aBind);
                        }
                    }
                    // Gestion des profils
                    $aBind[':SITE_ID'] = $pays;
                    $aBind[':PROFILE_ID'] = $profiles;
                    $sSQL = 'INSERT INTO #pref#_user_profile (USER_LOGIN, PROFILE_ID) values (:USER_LOGIN, :PROFILE_ID)';
                    $oConnection->query($sSQL, $aBind);
                    // Gestion des droits par m?tier
                    $where = '';
                    if ($aRights['METIER'][$pays][$profiles]) {
                        $where .= ' AND (PAGE_METIER IN (';
                        for ($i = 0; $i < count($aRights['METIER'][$pays][$profiles]); $i++) {
                            $aBind[':METIER_ID'.$i] = $aRights['METIER'][$pays][$profiles][$i];
                            if ($i != 0) {
                                $where .= ', ';
                            }
                            $where .= ' :METIER_ID'.$i;
                        }
                        $where .= ') OR PAGE_METIER IS NULL)';
                    }
                    $sSQL = 'SELECT PAGE_ID FROM #pref#_page WHERE SITE_ID = :SITE_ID'.$where;

                    $aPage = $oConnection->queryTab($sSQL, $aBind);

                    if (is_array($aPage) && count($aPage) > 0) {
                        $list = array();
                        foreach ($aPage as $key => $page) {
                            $list[] = $page['PAGE_ID'];
                        }

                        // Maj du champ PAGE_CREATION_USER pour la rubrique
                        $this->aBind[':PAGE_ID'] = $page['PAGE_ID'];
                        $sqlSavePage = "
                        UPDATE
                        #pref#_page
                        SET
                        PAGE_CREATION_USER = ".$oConnection->getConcatClause(array(
                                "PAGE_CREATION_USER",
                                "'#".$aParams['login']."#'"
                            ))."
                        WHERE
                        PAGE_ID in (".implode(',', $list).")";
                        $oConnection->query($sqlSavePage, $this->aBind);

                        // Maj du champ CONTENT_CREATION_USER pour les contenus de la rubrique
                        $sqlSaveContent = "
                        UPDATE
                        #pref#_content
                        SET
                        CONTENT_CREATION_USER = ".$oConnection->getConcatClause(array(
                                "CONTENT_CREATION_USER",
                                "'#".$aParams['login']."#'"
                            ))."
                        WHERE
                        CONTENT_ID in (SELECT #pref#_content_version.CONTENT_ID from #pref#_content_version where #pref#_content_version.PAGE_ID in (".implode(',',
                                $list)."))
                        ";
                        $oConnection->query($sqlSaveContent, $this->aBind);
                    }
                    $oConnection->commit();
                }
            }
        }
        $oConnection->commit();
    }

    private function initMultipleRights($rights = array())
    {
        $return = $rights;

        $excludeAdminSite = true;
        if (!empty($rights['strongest']['%%'])) {
            if ($rights['strongest']['%%'] == 'ADMINISTRATEUR') {
                $excludeAdminSite = false;
            }
        }
        $oConnection = Pelican_Db::getInstance();
        foreach ($return['strongest'] as $country => $profile) {

            $sSQL = "SELECT * from #pref#_site_code where SITE_CODE_LDAP like '".$country."' ".($excludeAdminSite ? " and SITE_ID != 1" : "");
            $aRes = $oConnection->queryTab($sSQL);
            $aSite = array();
            if (is_array($aRes) && count($aRes) > 0) {
                unset($return['strongest'][$country]);
                unset($return['all'][$country]);
                foreach ($aRes as $res) {
                    $return['strongest'][$res['SITE_CODE_LDAP']] = $profile;
                    $return['all'][$res['SITE_CODE_LDAP']][$profile] = array($profile => $profile);
                }
            }
        }

        return $return;
    }

    /**
     * Retourne tous les sites et leur code pays
     *
     * @access private
     * @return array $aSite
     */
    private static function _getSitesByCode()
    {
        $oConnection = Pelican_Db::getInstance();
        $sSQL = "SELECT * from #pref#_site_code";
        $aRes = $oConnection->queryTab($sSQL);
        $aSite = array();
        if (is_array($aRes) && count($aRes) > 0) {
            foreach ($aRes as $res) {
                $aSite[$res['SITE_CODE_LDAP']] = $res['SITE_ID'];
            }
        }

        return $aSite;
    }

    /**
     * Retourne tous les sites
     *
     * @access private
     * @return array $aSite
     */
    private static function _getSites()
    {
        $oConnection = Pelican_Db::getInstance();
        $sSQL = "SELECT * from #pref#_site";
        $aRes = $oConnection->queryTab($sSQL);
        $aSite = array();
        if (is_array($aRes) && count($aRes) > 0) {
            foreach ($aRes as $res) {
                $aSite[] = $res['SITE_ID'];
            }
        }

        return $aSite;
    }

    /**
     * Retourne tous les metiers
     *
     * @access private
     * @return array $aMetier
     */
    private static function _getMetier()
    {
        $oConnection = Pelican_Db::getInstance();
        $sSQL = "SELECT * from #pref#_metier";
        $aRes = $oConnection->queryTab($sSQL);
        $aMetier = array();
        if (is_array($aRes) && count($aRes) > 0) {
            foreach ($aRes as $res) {
                $aMetier[$res['METIER_LABEL']] = $res['METIER_ID'];
            }
        }

        return $aMetier;
    }

    /**
     * Retourne tous les profiles par site
     *
     * @access private
     * @return array $aProfiles
     */
    private static function _getProfilesBySites()
    {
        $oConnection = Pelican_Db::getInstance();
        $sSQL = "SELECT * from #pref#_profile";
        $aRes = $oConnection->queryTab($sSQL);
        $aProfiles = array();
        if (is_array($aRes) && count($aRes) > 0) {
            foreach ($aRes as $res) {
                $aProfiles[$res['SITE_ID']][$res['PROFILE_ID']] = $res['PROFILE_LABEL'];
            }
        }

        return $aProfiles;
    }

    /**
     * Retourne tous les roles
     *
     * @access private
     * @return array $aRoles
     */
    private static function _getRoles()
    {
        $oConnection = Pelican_Db::getInstance();
        $sSQL = "SELECT * from #pref#_role";
        $aRes = $oConnection->queryTab($sSQL);
        $aRoles = array();
        if (is_array($aRes) && count($aRes) > 0) {
            foreach ($aRes as $res) {
                $aRoles[$res['ROLE_LABEL']] = $res['ROLE_ID'];
            }
        }

        return $aRoles;
    }

    /**
     * Retourne tous les content type par site
     *
     * @access private
     * @return array $aContentType
     */
    private static function _getContentTypeBySite()
    {
        $oConnection = Pelican_Db::getInstance();
        $sSQL = "select * from #pref#_content_type";
        $aRes = $oConnection->queryTab($sSQL);
        $aContentType = array();
        if (is_array($aRes) && count($aRes) > 0) {
            foreach ($aRes as $res) {
                $aContentType[] = $res['CONTENT_TYPE_ID'];
            }
        }

        return $aContentType;
    }

    /**
     * Retourne tous les droits utilisateurs c?t? cppv2
     *
     * @access private
     * @return array $aRights
     */
    private static function _getRights($aLdapRights)
    {
        $aSite = self::_getSitesByCode();
        $aProfiles = self::_getProfilesBySites();
        $aMetier = self::_getMetier();
        $aRole = self::_getRoles();
        $aRights = array();

        if (is_array($aLdapRights['all']) && count($aLdapRights['all']) > 0) {
            // On boucle sur tous les pays pour r?cup?rer leur profil

            foreach ($aLdapRights['all'] as $pays => $aProfil) {
                if (is_array($aProfil) && count($aProfil) > 0) {
                    // Pour chaque profil, on r?cup?re les droits associ?s (importateur, webmaster, contributeur, administrateur)
                    $aTempMetier = array();
                    foreach ($aProfil as $right) {
                        if ($aSite[$pays]) {
                            $iProfil = 0;
                            $iMetier = 0;
                            $profil_ldap = key($right);
                            if (is_array($aProfiles[$aSite[$pays]]) && count($aProfiles[$aSite[$pays]]) > 0) {
                                // On boucle sur tous les profiles disponibles sur cppv2 pour le site courant
                                foreach ($aProfiles[$aSite[$pays]] as $profil_id => $profil_cppv2) {
                                    // S'il existe un profil cppv2 correspondant au profil ldap on r?cup?re son id

                                    if (strtoupper($profil_cppv2) == strtoupper($profil_ldap) && $aLdapRights['strongest'][$pays] == strtoupper($profil_cppv2)) {
                                        $_SESSION['PROFIL_ID_STRONGEST'] = $profil_id;
                                        break;
                                    }
                                }
                            }
                            // S'il existe un profil cppv2 correspondant on alimente le tableau des profils avec sa valeur
                            if ($profil_id != 0) {
                                $aRights['PROFILES'][$aSite[$pays]] = $_SESSION['PROFIL_ID_STRONGEST'];

                                // Si un m?tier particulier est d?fini pour un profil on le r?cup?re sinon on consid?re qu'il a le droit d'acc?der ? tous les m?tiers
                                if ($right[key($right)] != "") {
                                    $iMetier = $aMetier[$right[key($right)]];
                                }
                                if ($aRole[$aLdapRights['strongest'][$pays]]) {
                                    $aRights['ROLE'][$aSite[$pays]] = $aRole[$aLdapRights['strongest'][$pays]];
                                }

                                if ($iMetier != 0 && !in_array($iMetier, $aTempMetier)) {
                                    $_SESSION['LDAP'][$aSite[$pays]][$_SESSION['PROFIL_ID_STRONGEST']][] = $iMetier;
                                    $aRights['METIER'][$aSite[$pays]][$_SESSION['PROFIL_ID_STRONGEST']][] = $iMetier;
                                    $aTempMetier[] = $iMetier;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $aRights;
    }

    public static function isProfileTranslator()
    {
        return Pelican::$config['PROFILE']['TRANSLATOR'] == $_SESSION[APP]["navigation"]["site"][$_SESSION[APP]["PROFILE_ID"]."_".$_SESSION[APP]["SITE_ID"]] ["profile_name"];
    }

    public static function isProfileReadonly()
    {
        return Pelican::$config['PROFILE']['READONLY'] == $_SESSION[APP]["navigation"]["site"][$_SESSION[APP]["PROFILE_ID"]."_".$_SESSION[APP]["SITE_ID"]] ["profile_name"];
    }
}
