<?php
/**
 * Fichier de configuration de la sécurité
 *
 * @package Pelican
 * @subpackage config
 */

pelican_import ( 'Security' );

/** Configuration des filtrages de variables (GET, POST etc...) */

Pelican::$config ['security'] = array ('pid' => array ('type' => 'int', 'required' => false ), 'cid' => array ('type' => 'int', 'required' => false ), 'tpl' => array ('type' => 'int', 'required' => false ), 'rid' => array ('type' => 'int', 'required' => false ), 'tid' => array ('type' => 'int', 'required' => false ), 'sid' => array ('type' => 'int', 'required' => false ), 'lid' => array ('type' => 'int', 'required' => false ), 'langue' => array ('type' => 'int', 'required' => false ), 'preview' => array ('type' => 'int', 'required' => false ), 'recMot' => array ('required' => false, 'type' => 'string', 'function' => 'htmlspecialchars' ), 'screen_width' => array ('type' => 'int', 'required' => false ), 'screen_height' => array ('type' => 'int', 'required' => false ), 'recTheme' => array ('type' => 'int', 'required' => false ), 'recAv' => array ('type' => 'int', 'required' => false ), 'recChamp' => array ('type' => 'string', 'required' => false ), 'rechercheContentType' => array ('type' => 'int', 'required' => false ), 'popup_content' => array ('type' => 'int', 'required' => false ), 'TAG_TYPE_HTTP' => array ('type' => 'string', 'required' => false ), 'TAG_TYPE_HTTPS' => array ('type' => 'string', 'required' => false ), 'recDate1' => array ('type' => 'string', 'required' => false, 'function' => 'secureDate' ), 'recDate2' => array ('type' => 'string', 'required' => false, 'function' => 'secureDate' ), 'areaId' => array ('type' => 'int', 'required' => false ), 'idPage' => array ('type' => 'int', 'required' => false ), 'zoneOrder' => array ('type' => 'int', 'required' => false ), 'zoneTid' => array ('type' => 'int', 'required' => false ), 'ppid' => array ('type' => 'int', 'required' => false ) , 'form_page_pid' => array ('type' => 'int', 'required' => false ), 'typeFormulaire' => array ('type' => 'int', 'required' => false )  );
//Pelican::$config ['security'] = array ('pid' => array ('type' => 'int', 'required' => false ), 'cid' => array ('type' => 'int', 'required' => false ), 'tpl' => array ('type' => 'int', 'required' => false ), 'rid' => array ('type' => 'int', 'required' => false ), 'tid' => array ('type' => 'int', 'required' => false ), 'sid' => array ('type' => 'int', 'required' => false ), 'lid' => array ('type' => 'int', 'required' => false ), 'langue' => array ('type' => 'int', 'required' => false ), 'preview' => array ('type' => 'int', 'required' => false ), 'recMot' => array ('required' => false, 'type' => 'string', 'function' => 'htmlspecialchars' ), 'screen_width' => array ('type' => 'int', 'required' => false ), 'screen_height' => array ('type' => 'int', 'required' => false ), 'recTheme' => array ('type' => 'int', 'required' => false ), 'recAv' => array ('type' => 'int', 'required' => false ), 'recChamp' => array ('type' => 'string', 'required' => false ), 'rechercheContentType' => array ('type' => 'int', 'required' => false ), 'popup_content' => array ('type' => 'int', 'required' => false ), 'TAG_TYPE_HTTP' => array ('type' => 'string', 'required' => false ), 'TAG_TYPE_HTTPS' => array ('type' => 'string', 'required' => false ), 'recDate1' => array ('type' => 'string', 'required' => false, 'function' => 'secureDate' ), 'recDate2' => array ('type' => 'string', 'required' => false, 'function' => 'secureDate' ) );
Pelican::$config ['security'] ["RECAPTCHA"] ["PUBLICKEY"] = '6LeZDgkAAAAAALzpiWjSURSzAihn-gGZrvFb3ZUQ';
Pelican::$config ['security'] ["RECAPTCHA"] ["PRIVATEKEY"] = '6LeZDgkAAAAAACCNNy1IRmeGFBvESnwScX0t8sXg';

//Pelican::$config['server_configuration']['allow_url_fopen'] = 0;
Pelican::$config ['server_configuration'] ['always_populate_raw_post_data'] = 0;
Pelican::$config ['server_configuration'] ['auto_globals_jit'] = 1;

if (strtolower($_ENV['TYPE_ENVIRONNEMENT']) == 'vm') Pelican::$config['server_configuration']['error_reporting'] = E_ALL ^ E_WARNING ^ E_STRICT ^ E_NOTICE;

Pelican::$config ['server_configuration'] ['expose_php'] = 0;
Pelican::$config ['server_configuration'] ['log_errors'] = 1;
Pelican::$config ['server_configuration'] ['magic_quotes_gpc'] = 0;
Pelican::$config ['server_configuration'] ['magic_quotes_runtime'] = 0;
Pelican::$config ['server_configuration'] ['output_buffering'] = 4096;
Pelican::$config ['server_configuration'] ['register_argc_argv'] = 0;
Pelican::$config ['server_configuration'] ['register_globals'] = 0;
Pelican::$config ['server_configuration'] ['session.auto_start'] = 0;
Pelican::$config ['server_configuration'] ['session.cookie_lifetime'] = 7200;
Pelican::$config ['server_configuration'] ['session.gc_divisor'] = 10000;
Pelican::$config ['server_configuration'] ['session.gc_probability'] = 0;
Pelican::$config ['server_configuration'] ['session.use_cookies'] = 1;
Pelican::$config ['server_configuration'] ['session.use_only_cookies'] = "on";
Pelican::$config ['server_configuration'] ['session.use_trans_id'] = 0;
//Pelican::$config['server_configuration']['variables_order'] = "GPCS";

Pelican::$config ['server_extension'] = array ('translit', 'curl', 'json', 'mbstring', 'mcrypt', 'simplexml', 'stomp', 'memcache' );

/** Tests de sécurité de base */
Pelican_Security::base ( Pelican::$config ['security'] );
