<?php
/**
 * Classe de gestion de la sécurité des pages
 *
 * Méthodes à utiliser pour garantir la sécurisation des sites
 * - Configuration serveur de production :
 * <code>Pelican_Security::checkConfig();</code>
 * - Contrôle des variables GET ou POST (typage des données, échappement des
 * textes (anti XSS))
 * <code>Pelican_Security::checkInput(&$_GET, Pelican::$config['security'], true);
 * Pelican_Security::checkInput(&$_POST, Pelican::$config['security'], false);</code>
 * - Contrôle du User Agent pour éviter l'interception de session
 * <code>Pelican_Security::checkUseragent();</code>
 *
 * @package    Pelican
 * @subpackage Security
 * @copyright  Copyright (c) 2001-2012 Business&Decision
 * @license    http://www.interakting.com/license/phpfactory
 * @link       http://www.interakting.com
 * @update     05/04/2009 échappement des POST et contrôle d'extension des fichiers
 */

/**
 * terme utilisé pour le hashage
 */
define('SECURITY_SALT', 'ousermare_setepenre');

/**
 * Classe de gestion de la sécurité des pages
 *
 * @package    Pelican
 * @subpackage Security
 * @author     Raphaël Carles <rcarles@businessdecision.fr>
 * @since      06/03/2006
 * @version    1.0
 */
class Pelican_Security
{

    /**
     * Contrôle standards à faire sur chaque page du site
     *
     * @access public
     *
     * @param mixed $aSecurity
     *            (option) Liste des typages de paramètres ("param"=>
     *            array('required'=>false, 'type'=>'string', 'function'=>'htmlspecialchars'))
     *
     * @return void
     */
    public static function base($aSecurity = array())
    {
        if (Pelican_Security::checkLFI()) {
            Pelican_Security::checkConfig();
            // Pelican_Security::checkUseragent();
            if ($_GET) {
                Pelican_Security::checkInput($_GET, $aSecurity, "escapeFull");
            }
            if ($_POST && !Pelican::$config["BACK_OFFICE"]) {
                Pelican_Security::checkInput($_POST, $aSecurity, "escapeXSS");
            }
            if ($_COOKIE) {
                Pelican_Security::checkInput($_COOKIE, $aSecurity, "escapeFull");
            }
            if ($_FILES) {
                Pelican_Security::checkInput($_FILES, $aSecurity, "upload");
            }
        } else {

            /**
             * tentative de hacking => erreur 500
             */
            header("HTTP/1.1 500 Internal Server Error");
            header("Status: 500");
            $msg = "Tentative de Hacking : ".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $msg .= "\n";
            $msg .= print_r($_REQUEST, true);
            error_log($msg, 0);
            error_log($msg, 1, Pelican::$config['SECURITY_ALERT_EMAIL_TO']);
            echo('......');
            die();
        }
        // Pelican_Security::logUrlParams(false);
    }

    /**
     * Controle de base obligatoir sur les GET et REQUEST
     * pour eviter le LFI (Local File Inclusion) et le null byte
     *
     * @static
     *
     *
     *
     *
     * @access public
     * @return __TYPE__
     */
    public static function checkLFI()
    {

        /**
         * on traite GET, POST, COOKIES en meme temps
         */
        $serialize = serialize($_REQUEST);
        $request = strtolower($serialize.'_'.rawurldecode($serialize));
        $cleanRequest = $request;

        /**
         * Null byte characters
         */
        $cleanRequest = preg_replace('/\\\0/', '', $cleanRequest);
        $cleanRequest = preg_replace('/\\x00/', '', $cleanRequest);
        $cleanRequest = str_replace('%00', '', $cleanRequest);

        /**
         * File traversal
         */
        $cleanRequest = str_replace('../', '', $cleanRequest);
        $cleanRequest = str_replace('..\/', '', $cleanRequest);
        $cleanRequest = str_replace('..\\', '', $cleanRequest);

        /**
         * controles supplementaires
         */
        $control = array(
            'etc/passwd',
            'vcombined_log',
            'phpinfo()',
            '<?php',
            '.htaccess',
            '.bash_history',
            'error_log',
            'ssl_request_log',
            '/init.d/',
            'httpd.conf',
            'proc/self/environ',
            'php://',
            '/cmd',
            '\cmd',
            'config.php',
            'config.php'
        );
        $cleanRequest = str_replace($control, '', $cleanRequest);

        /**
         * complement sur les requetes GET
         */

        /*
         * $controlGet = array('absolute_path' , 'ad_click' , 'alert(' , 'alert%20' , ' and ' , 'basepath' , 'bash_history' , '.bash_history' , 'cgi-' , 'chmod(' , 'chmod%20' , '%20chmod' , 'chmod=' , 'chown%20' , 'chgrp%20' , 'chown(' , '/chown' , 'chgrp(' , 'chr(' , 'chr=' , 'chr%20' , '%20chr' , 'chunked' , 'cookie=' , 'cmd' , 'cmd=' , '%20cmd' , 'cmd%20' , '.conf' , 'configdir' , 'config.php' , 'cp%20' , '%20cp' , 'cp(' , 'diff%20' , 'dat?' , 'db_mysql.inc' , 'document.location' , 'document.cookie' , 'drop%20' , 'echr(' , '%20echr' , 'echr%20' , 'echr=' , '}else{' , '.eml' , 'esystem(' , 'esystem%20' , '.exe' , 'exploit' , 'file\://' , 'fopen' , 'fwrite' , '~ftp' , 'ftp:' , 'ftp.exe' , 'getenv' , '%20getenv' , 'getenv%20' , 'getenv(' , 'grep%20' , '_global' , 'global_' , 'global[' , 'http:' , '_globals' , 'globals_' , 'globals[' , 'grep(' , 'g\+\+' , 'halt%20' , '.history' , '?hl=' , '.htpasswd' , 'http_' , 'http-equiv' , 'http/1.' , 'http_php' , 'http_user_agent' , 'http_host' , '&icq' , 'if{' , 'if%20{' , 'img src' , 'img%20src' , '.inc.php' , '.inc' , 'insert%20into' , 'ISO-8859-1' , 'ISO-' , 'javascript\://' , '.jsp' , '.js' , 'kill%20' , 'kill(' , 'killall' , '%20like' , 'like%20' , 'locate%20' , 'locate(' , 'lsof%20' , 'mdir%20' , '%20mdir' , 'mdir(' , 'mcd%20' , 'motd%20' , 'mrd%20' , 'rm%20' , '%20mcd' , '%20mrd' , 'mcd(' , 'mrd(' , 'mcd=' , 'mod_gzip_status' , 'modules/' , 'mrd=' , 'mv%20' , 'nc.exe' , 'new_password' , 'nigga(' , '%20nigga' , 'nigga%20' , '~nobody' , 'org.apache' , '+outfile+' , '%20outfile%20' , ' outfile ' , 'outfile' , 'password=' , 'passwd%20' , '%20passwd' , 'passwd(' , 'phpadmin' , 'perl%20' , '/perl' , 'phpbb_root_path' , 'p0hh' , 'ping%20' , '.pl' , 'powerdown%20' , 'rm(' , '%20rm' , 'rmdir%20' , 'mv(' , 'rmdir(' , 'phpinfo()' , '<?php' , 'reboot%20' , '/robot.txt' , '~root' , 'root_path' , 'rush=' , '%20and%20' , '%20xorg%20' , '%20rush' , 'rush%20' , 'secure_site, ok' , 'select%20' , 'select from' , 'select%20from' , '_server' , 'server_' , 'server[' , 'server-info' , 'server-status' , 'servlet' , 'sql=' , '<script' , '<script>' , '</script' , 'script>' , '/script' , 'switch{' , 'switch%20{' , '.system' , 'system(' , 'telnet%20' , 'traceroute%20' , '.txt' , 'union%20' , '%20union' , 'union(' , 'union=' , 'vi(' , 'vi%20' , 'wget' , 'wget%20' , '%20wget' , 'wget(' , 'window.open' , 'wwwacl' , ' xor ' , 'xp_enumdsn' , 'xp_availablemedia' , 'xp_filelist' , 'xp_cmdshell' , '$_request' , '$_get' , '$request' , '$get' , '&aim' , '/etc/password' , '/etc/shadow' , '/etc/groups' , '/etc/gshadow' , '/bin/ps' , 'uname\x20-a' , '/usr/bin/id' , '/bin/echo' , '/bin/kill' , '/bin/' , '/chgrp' , '/usr/bin' , 'bin/python' , 'bin/tclsh' , 'bin/nasm' , '/usr/x11r6/bin/xterm' , '/bin/mail' , '/etc/passwd' , '/home/ftp' , '/home/www' , '/servlet/con' , '?>' , '.txt'); $check = str_replace($controlGet, '', $get); if ($controlGet != $check) { $cleanRequest = ''; }
         */

        return !strcmp($request, $cleanRequest);
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public static function checkConfig()
    {
        if (is_array(Pelican::$config['server_configuration'])) {
            foreach (Pelican::$config['server_configuration'] as $key => $value) {
                $val = ini_get($key);
                if ($val != $value) {
                    if (!empty($_GET['checkconfig'])) {
                        var_dump('problème de configuration serveur "'.$key.'" : "'.$value.'" attendu, "'.$val.'" identifié');
                    }
                    ini_set($key, $value);
                }
            }
        }
        if (!empty($_GET['checkconfig'])) {
            if (is_array(Pelican::$config['server_extension'])) {
                foreach (Pelican::$config['server_extension'] as $extension) {
                    if (!extension_loaded($extension)) {
                        var_dump('problème de configuration serveur : extension "'.$extension.'" manquante');
                    }
                }
            }
            die();
        }
    }

    /**
     * Contrôle de paramètre :
     *
     * - typage
     * - échappement
     * Si un paramètre est inconnu, on lui applique par principe htmlspecialchars si
     * le paramètre $securityFunction est renseigné
     * (ne pas utiliser sur les paramètres $_POST pour éviter les effets de bord)
     *
     * @access public
     *
     * @param mixed  $vars
     *            Tableau de paramètre à contrôler et à typer
     * @param mixed  $signatures
     *            Tablerau de paramétrage défini dans
     *            /application/configs/security.ini.php
     * @param bolean $securityFunction
     *            (option) Function d'echappement des paramètres
     *            inconnus
     *
     * @return void
     */
    public static function checkInput(&$vars, $signatures, $securityFunction = "")
    {
        if ($vars) {
            foreach ($vars as $key => $value) {
                $name = $key;
                if ($securityFunction == "upload") {
                    if (isset($vars[$name]['name'])) {
                        if (!is_array($vars[$name]['name'])) {
                            if (!Pelican_Security::checkExtension($vars[$name]['name'])) {
                                Pelican_Log::control('Upload non autorisé : '.$vars[$name]['name'], 'security');
                                $vars[$name]['tmp_name'] = '';
                                $vars[$name]['error'] = 'Not allowed';
                                $vars[$name]['size'] = '';
                            }
                        } else {
                            foreach ($vars[$name]['name'] as $key => $value) {
                                if (!Pelican_Security::checkExtension($value)) {
                                    Pelican_Log::control('Upload non autorisé : '.$value, 'security');
                                    $vars[$name]['tmp_name'][$key] = '';
                                    $vars[$name]['error'][$key] = 'Not allowed';
                                    $vars[$name]['size'][$key] = '';
                                }
                            }
                        }
                    }
                } else {
                    $sig = "";
                    if (isset($signatures[$name])) {
                        $sig = $signatures[$name];
                    }
                    if ($sig) {
                        if (!isset($vars[$name]) && isset($sig['required']) && $sig['required']) {
                            Pelican_Security::error('Le paramètre '.$name.' est absent');
                            exit();
                        }

                        /**
                         * typage de variable
                         */
                        if (isset($sig['type'])) {
                            settype($vars[$name], $sig['type']);
                        } else {
                            Pelican_Log::control('Variable non typée : '.$vars[$name], 'security');
                        }

                        /**
                         * application de fonction
                         */
                        if (isset($sig['function'])) {
                            $vars[$name] = call_user_func($sig['function'], $vars[$name]);
                        }
                    } else {
                        if ($securityFunction) {

                            /**
                             * on échappe en htmlspecialchars par principe les variables
                             */
                            $vars[$name] = Pelican_Security::escape($value, $securityFunction);
                        }
                    }
                }
            }
        }
    }

    /**
     * Fonction d'échappement des variables
     *
     * @access public
     *
     * @param mixed  $value
     *            Texte
     * @param string $function
     *            (option) Fonction d'échappement : en général
     *            htmlspecialchars pour le GET
     *
     * @return mixed
     */
    public static function escape($value, $function = "htmlspecialchars")
    {
        $return = $value;
        if ($value) {
            if (is_array($value)) {
                $return = array_map($function, $value);
            } else {
                $return = call_user_func($function, $value);
            }
        }

        return $return;
    }

    /**
     * Contrôle d'extension
     *
     * @access public
     *
     * @param string $file
     *            Nom de fichier
     *
     * @return bool
     */
    public static function checkExtension($file)
    {
        $pathinfo = pathinfo($file);

        return !in_array($pathinfo['extension'], array(
            'php',
            'sh',
            'bat',
            'exe',
            'com'
        ));
    }

    /**
     * Si un id de session est intercepté, le UserAgent d'origine n'est pas
     * forcément le même => on le test pour faire un contrôle
     *
     * S'il est différent, on recrée de façon transparente un autre session vide
     *
     * @access public
     * @return bool
     */
    public static function checkUseragent()
    {

        /**
         * Détournement de session
         */
        $string = $_SERVER['HTTP_USER_AGENT'];
        $string .= SECURITY_SALT;
        $fingerprint = md5($string);
        if (isset($_SESSION)) {
            if (!empty($_SESSION['HUA'])) {
                if ($_SESSION['HUA'] != $fingerprint) {

                    /**
                     * !!! si le user_agent ne correspond pas, on regénère un autre id de session de façon transparente
                     */
                    $old_sessid = session_id();
                    session_regenerate_id();
                    $new_sessid = session_id();
                    session_id($old_sessid);
                    session_destroy();
                    session_id($new_sessid);
                    session_start();
                    $_SESSION['HUA'] = $fingerprint;
                }
            } else {
                $_SESSION['HUA'] = $fingerprint;
            }
        }

        return true;
    }

    /**
     * Contrôle d'une valeur dans la session, sinon redirection vers la page d'erreur
     *
     * @access public
     *
     * @param string $value
     *            Valeur à tester
     * @param string $redirect
     *            (option) Page de redirection en cas d'échec
     * @param string $msg
     *            (option) Message
     *
     * @return void
     */
    public static function checkSessionValue($value, $redirect = "", $msg = "")
    {
        if (!$value) {
            if (basename($_SERVER['REQUEST_URI']) != basename($redirect)) {
                // if ($msg) {
                // echo $msg;
                // } else {
                header("Location: ".$redirect);
                // }
                exit();
            }
        }
    }

    /**
     * Nettoyage du code Pelican_Html :
     *
     * - retrait des commentaires
     * - maquage des emails
     *
     * @access public
     *
     * @param string $output
     *            Code Pelican_Html à traiter
     * @param bool   $compressOutput
     *            (option) Compresser la sortie
     * @param bool   $cleanComment
     *            (option) Supprimer les commentaires
     * @param bool   $encodeEmail
     *            (option) Encoder les emails
     *
     * @return string
     */
    public static function checkOutput($output, $compressOutput = true, $cleanComment = true, $encodeEmail = false)
    {
        $return = $output;
        if ($cleanComment) {
            $return = Pelican_Html_Util::dropComments($return);
        }
        if ($compressOutput) {
            $return = Pelican_Html_Util::compress($return);
        }
        if ($encodeEmail) {
            return Pelican_Html_Util::encodeAllEmail($return);
        }

        return $return;
    }

    /**
     * Headers adaptés pour éviter la mise en Pelican_Cache du navigateur => à
     * utiliser
     * pour les pages de login
     *
     * @access public
     * @return void
     */
    public static function secureHeader()
    {
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * Execution de commandes systèmes avec échappement des paramètres
     *
     * @access public
     *
     * @param string $command
     *            Fonction shellexec, system, exec, passthru, popen
     * @param string $params
     *            Ligne de commande
     *
     * @return void
     */
    public static function execSafeCommand($command, $params)
    {
        call_user_func($command, escapeshellcmd($params));
    }

    /**
     * Execution de commandes systèmes avec échappement des paramètres
     *
     * @access public
     * @param string $command Fonction shellexec, system, exec, passthru, popen
     * @param string $params Ligne de commande
     * @return void
     */
    public static function execSafeCommandArg($param)
    {
        $return = str_replace("'", "", escapeshellarg($param));
        $return = str_replace("\$", "", $return);
        $return = str_replace(";", "", $return);
        $return = str_replace(" ", "%20", $return);
        $return = str_replace("%09;", "", $return);
        $return = str_replace("\n", "", $return);
        $return = str_replace("\r", "", $return);
        $return = str_replace("\t", "", $return);
        return $return;
    }

    /**
     * Contrôle de l'unicité de l'email (pour éviter l'email injection)
     *
     * Si des termes supplémentaires sont trouvés, ils sont supprimés
     *
     * @access public
     *
     * @param string $email
     *            Email
     *
     * @return string
     */
    public static function checkDest($email)
    {
        $return = "";
        if ($email) {
            $email = str_replace(array(
                "%0A",
                ",",
                ";",
                "\n",
                "\r"
            ), "#", $email);
            $email = preg_replace('/\\\0/', '', $email);
            $email = preg_replace('/\\x00/', '', $email);
            $email = str_replace('%00', '', $email);
            $temp = explode("#", $email);
            $return = $temp[0];
        }

        return $return;
    }

    /**
     * ROT13 function - a function to encode and decode text strings
     * using the popular ROT13 method.
     *
     * Brett Burridge (brett@brettb.com)
     * The function is called using something like:
     * print ROT13("Hello World");
     * Note that ROT13 does not encrypt strings, so do not use it if Pelican_Security is an issue
     *
     * Enter description here...
     *
     * @access public
     *
     * @param string $rot13text
     *            Texte
     *
     * @return string
     */
    public static function rot13($rot13text)
    {
        $rot13text_rotated = "";
        for ($i = 0; $i <= strlen($rot13text); $i++) {
            $k = ord(substr($rot13text, $i, 1));
            if ($k >= 97 and $k <= 109) {
                $k = $k + 13;
            } elseif ($k >= 110 and $k <= 122) {
                $k = $k - 13;
            } elseif ($k >= 65 and $k <= 77) {
                $k = $k + 13;
            } elseif ($k >= 78 and $k <= 90) {
                $k = $k - 13;
            }
            $rot13text_rotated = $rot13text_rotated.Chr($k);
        }

        return $rot13text_rotated;
    }

    /**
     * Token dans un formulaire
     *
     * @access public
     * @return string
     */
    public static function inputToken()
    {
        $token = md5(uniqid(rand(), true));
        $_SESSION['token'] = $token;
        $_SESSION['token_time'] = time();
        echo Pelican_Html::input(array(
            type => "hidden",
            name => "token",
            value => $token
        ));
    }

    /**
     * Contrôle du Token placé dans le formulaire soumis
     *
     * @access public
     *
     * @param int $duration
     *            (option) Durée de validité du Token
     *
     * @return bool
     */
    public static function checkToken($duration = 300)
    {
        if (isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
            $token_age = time() - $_SESSION['token_time'];
            if ($token_age <= $duration) {
                return true;
            }
        }

        return false;
    }

    /**
     * Création d'un captcha dnas le formulaire et création de la variable de
     * contrôle en session
     *
     * @access public
     *
     * @param string   $label
     *            (option) Label
     * @param string   $type
     *            (option) Captcha type
     * @param __TYPE__ $options
     *            (option) __DESC__
     *
     * @return string
     */
    public static function inputCaptcha($label = "", $type = 'STANDARD', $options = array())
    {
        switch ($type) {
            case "RECAPTCHA":
                require_once 'Zend/Service/ReCaptcha.php';
                $recaptcha = new Zend_Service_ReCaptcha(Pelican::$config['security']["RECAPTCHA"]["PUBLICKEY"],
                    Pelican::$config['security']["RECAPTCHA"]["PRIVATEKEY"]);
                foreach ($options as $key => $value) {
                    $recaptcha->setOption($key, $value);
                }
                $captcha = $recaptcha->getHTML();

                return $captcha;
                break;
            case "STANDARD":
                $return = Pelican_Html::img(array(
                    width => 200,
                    height => 60,
                    alt => "Image de contrôle",
                    style => "float:right;vertical-align: middle;",
                    src => "/library/Pelican/Security/public/captcha/visual-captcha.php",
                    border => "1"
                ));
                $return .= $label;
                $return .= Pelican_Html::input(array(
                    type => "text",
                    name => "userdigit",
                    id => "userdigit",
                    size => 10
                ));

                return Pelican_Html::div(array(
                    style => "width:500px;"
                ), $return);
                break;
        }
    }

    /**
     * Contrôle du Captcha placé dans le formulaire soumis
     *
     * @access public
     *
     * @param string $type
     *            (option) Captcha type
     *
     * @return bool
     */
    public static function checkCaptcha($type = 'STANDARD')
    {
        switch ($type) {
            case "RECAPTCHA":
                if (!empty($_POST['recaptcha_challenge_field'])) {
                    if (!empty($_POST['recaptcha_response_field'])) {
                        require_once 'Zend/Service/ReCaptcha.php';
                        $recaptcha = new Zend_Service_ReCaptcha(Pelican::$config['security']["RECAPTCHA"]["PUBLICKEY"],
                            Pelican::$config['security']["RECAPTCHA"]["PRIVATEKEY"]);
                        $result = $recaptcha->verify($_POST['recaptcha_challenge_field'],
                            $_POST['recaptcha_response_field']);
                        if ($result->isValid()) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                    break;
                }
            case "STANDARD":
                if (isset($_POST['userdigit'])) {
                    $digit = trim($_SESSION["captcha"]);
                    $userdigit = trim($_POST['userdigit']);
                    if ($digit) {
                        if ($digit == $userdigit) {
                            unset($_SESSION["captcha"]);

                            return true;
                        } else {
                            return false;
                        }
                    }
                }

                return false;
                break;
        }
    }

    /**
     * Hashage de mot de passe
     *
     * @access public
     *
     * @param string $password
     *            Mot de passe
     *
     * @return string
     */
    public static function hashPassword($password)
    {
        $salt = SECURITY_SALT;

        return md5($salt.md5($password.$salt));
    }

    /**
     * Test des droits d'affichage du debug (soit en fonction de l'IP soit en fonction
     * du serveur)
     *
     * @access public
     *
     * @param string $IP
     *            (option)
     * @param string $Host
     *            (option)
     * @param string $Server
     *            (option)
     *
     * @return string
     */
    public static function checkDebug($IP = "", $Host = "", $Server = "")
    {
        $return = (Pelican::$config["DEBUG_SERVER_NAME"] == "*" || $_SERVER["REMOTE_ADDR"] == $IP || $_ENV["HOSTNAME"] == $Host || $_SERVER["SERVER_NAME"] == $Server || !$_ENV["HOSTNAME"] || $_GET["betd"] == "debug");

        return $return;
    }

    /**
     * Contrôle de l'IP par rapport à la plage d'IP passée en paramètres
     *
     * @access public
     *
     * @param string $plageIP
     *            Page d'IP
     *
     * @return string
     */
    public static function checkIP($plageIP)
    {
        $arPlageIP = explode(",", $plageIP);
        $remoteIP = ip2long($_SERVER["REMOTE_ADDR"]);
        $minIP = ip2long($arPlageIP[0]);
        $maxIP = ip2long($arPlageIP[1]);
        if ($remoteIP >= $minIP && $remoteIP <= $maxIP) {
            return true;
        }

        return false;
    }

    /**
     * Message d'erreur
     *
     * @access public
     *
     * @param string $msg
     *            Message
     *
     * @return string
     */
    public static function error($msg)
    {
        echo $msg;
    }

    /**
     * Log des paramètres passés en GET ou POST
     *
     * @access public
     *
     * @param bool $state
     *            (option) Activer ou non
     * @param bool $show
     *            (option) Afficher le résultat à l'écran
     *
     * @return void
     */
    public static function logUrlParams($state = false, $show = false)
    {
        $path = Pelican::$config['MEDIA_ROOT']."/urlparams.log";
        if ($state) {
            $conf = array_keys(Pelican::$config['security']);
            $old = array();
            $old = @unserialize(@implode("", @file($path)));
            $params = array_merge($old, array_keys($_REQUEST));
            $params = array_diff(array_unique($params), $conf);
            $fp = @fopen($path, "w");
            if (@fwrite($fp, serialize($params))) {
                @fclose($fp);
            }
        } elseif ($show) {
            debug(@unserialize(@implode("", @file($path))));
        }
    }

    /**
     * __DESC__
     *
     * @access public
     *
     * @param __TYPE__ $text
     *            __DESC__
     *
     * @return __TYPE__
     */
    public static function escapeFull($text)
    {
        if (is_array($text)) {
            $return = $text;
            foreach ($return as $key => $value) {
                $return[$key] = htmlspecialchars($value);
                $return[$key] = escapeXSS($value);
            }
        } else {
            $return = $text;
            $return = htmlspecialchars($return);
            $return = escapeXSS($return);
        }

        return $return;
    }

    /**
     * Echappement minimaliste pour le XSS
     *
     * @access public
     *
     * @param string $text
     *            Valeur à contrôler
     *
     * @return string
     */
    public static function escapeXSS($text)
    {
        $data = $text;
        if (is_array($data)) {
            foreach ($data as $key=>$value) {
                $data[$key] = self::escapeXSS($value);
            }
        } else {
            if ($data && !is_array($data)) {
                $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
                $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
                $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
                $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
                // Remove any attribute starting with "on" or xmlns
                $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
                // Remove javascript: and vbscript: protocols
                $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
                $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
                $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
                // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
                $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
                $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
                $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
                // Remove namespaced elements (we do not need them)
                $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
                do {
                    // Remove really unwanted tags
                    $old_data = $data;
                    $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
                } while ($old_data !== $data);
                //$return = str_replace(array("<script" , "script>"), array("&lt;" , "&gt;"), $return);

            }
        }
        return $data;
    }

    /**
     * @param $formName
     * @param string $sessionToken
     * @return string
     */
    public static function getCsrfToken($formName, $sessionToken = 'csrf_token')
    {
        if (function_exists("hash_algos") and in_array("sha512", hash_algos())) {
            $token = hash("sha512", mt_rand(0, mt_getrandmax()));
        } else {
            $token = ' ';
            for ($i = 0; $i < 128; ++$i) {
                $r = mt_rand(0, 35);
                if ($r < 26) {
                    $c = chr(ord('a') + $r);
                } else {
                    $c = chr(ord('0') + $r - 26);
                }
                $token .= $c;
            }
        }
        $_SESSION[APP][$sessionToken][$formName] = $token;
        return $token;
    }

    /**
     * @param string $sessionName
     * @param string $sessionToken
     * @param string $method
     * @return bool
     */
    public static function validateCsrfToken($sessionName = 'csrf_name', $sessionToken = 'csrf_token', $method = 'post')
    {
        $result = true;
        $var = ($method == 'post' ? $_POST : $_GET);
        if (!empty($var[$sessionName]) && !empty($var[$sessionToken])) {
            if (!empty($_SESSION[APP][$sessionToken][$var[$sessionName]])) {
                $token = $_SESSION[APP][$sessionToken][$var[$sessionName]];
                if ($token === $var[$sessionToken]) {
                    $result = true;
                } else {
                    $result = false;
                }
                unset($_SESSION[APP][$sessionToken][$var[$sessionName]]);
            }
        }
        return $result;
    }
}

/**
 * __DESC__
 *
 * @param string $value
 *            Date
 *
 * @return string
 */
function secureDate($value)
{
    if ($value) {
        $temp = explode("/", $value);
        if (count($temp) == 1) {
            $time = mktime(0, 0, 0, "01", "01", (int)$temp[0]);
        } elseif (count($temp) == 2) {
            $time = mktime(0, 0, 0, (int)$temp[0], "01", (int)$temp[1]);
        } elseif (count($temp) == 3) {
            $time = mktime(0, 0, 0, (int)$temp[1], (int)$temp[0], (int)$temp[2]);
        }
        $return = date("d/m/Y", $time);
    }

    return $return;
}

/**
 * __DESC__
 *
 * @param __TYPE__ $text
 *            __DESC__
 *
 * @return __TYPE__
 */
function escapeFull($text)
{
    $return = Pelican_Security::escapeFull($text);

    return $return;
}

/**
 * Echappement minimaliste pour le XSS
 *
 * @param string $text
 *            Valeur à contrôler
 *
 * @return string
 */
function escapeXSS($text)
{
    $return = Pelican_Security::escapeXSS($text);

    return $return;
}

/**
 * Echappement complet pour le XSS (performances à analyser)
 *
 * @param string $val
 *            Valeur à contrôler
 *
 * @return string
 */
function RemoveXSS($val)
{
    // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
    // this prevents some character re-spacing such as <java\0script>
    // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
    // straight replacements, the user should never need these since they're normal characters
    // this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A &#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        // ;? matches the ;, which is optional
        // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
        // &#x0040 @ search for the hex values
        $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
        // &#00064 @ 0{0,7} matches '0' zero to seven times
        $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
    }
    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = array(
        'javascript',
        'vbscript',
        'expression',
        'applet',
        'meta',
        'xml',
        'blink',
        'link',
        'style',
        'script',
        'embed',
        'object',
        'iframe',
        'frame',
        'frameset',
        'ilayer',
        'layer',
        'bgsound',
        'title',
        'base'
    );
    $ra2 = array(
        'onabort',
        'onactivate',
        'onafterprint',
        'onafterupdate',
        'onbeforeactivate',
        'onbeforecopy',
        'onbeforecut',
        'onbeforedeactivate',
        'onbeforeeditfocus',
        'onbeforepaste',
        'onbeforeprint',
        'onbeforeunload',
        'onbeforeupdate',
        'onblur',
        'onbounce',
        'oncellchange',
        'onchange',
        'onclick',
        'oncontextmenu',
        'oncontrolselect',
        'oncopy',
        'oncut',
        'ondataavailable',
        'ondatasetchanged',
        'ondatasetcomplete',
        'ondblclick',
        'ondeactivate',
        'ondrag',
        'ondragend',
        'ondragenter',
        'ondragleave',
        'ondragover',
        'ondragstart',
        'ondrop',
        'onerror',
        'onerrorupdate',
        'onfilterchange',
        'onfinish',
        'onfocus',
        'onfocusin',
        'onfocusout',
        'onhelp',
        'onkeydown',
        'onkeypress',
        'onkeyup',
        'onlayoutcomplete',
        'onload',
        'onlosecapture',
        'onmousedown',
        'onmouseenter',
        'onmouseleave',
        'onmousemove',
        'onmouseout',
        'onmouseover',
        'onmouseup',
        'onmousewheel',
        'onmove',
        'onmoveend',
        'onmovestart',
        'onpaste',
        'onpropertychange',
        'onreadystatechange',
        'onreset',
        'onresize',
        'onresizeend',
        'onresizestart',
        'onrowenter',
        'onrowexit',
        'onrowsdelete',
        'onrowsinserted',
        'onscroll',
        'onselect',
        'onselectionchange',
        'onselectstart',
        'onstart',
        'onstop',
        'onsubmit',
        'onunload'
    );
    $ra = array_merge($ra1, $ra2);
    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(&#0{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }

    return $val;
}
