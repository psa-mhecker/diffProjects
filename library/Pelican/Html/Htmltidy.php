<?php
/**
 * Classe de gestion de Tidy soit via PECL (PHP 4 ou 5) soit en ligne de commande
 *
 * Cette classe est utilisable sous windows et linux mais nécessite
 * l'installation du binaire tidy
 *
 * @package Pelican
 * @subpackage Html
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @since 15/03/2006
 * @link http://www.interakting.com
 */

/** utilisation du module PECL s'il existe */
define('USE_PECL', 0);

/** Niveaux d'accessibilité */
global $errorLevel, $warningLevel;
$errorLevel['1.1.1.1'] = 1;
$errorLevel['1.1.10.1'] = 9;
$errorLevel['1.1.12.1'] = 1;
$errorLevel['1.1.4.1'] = 1;
$errorLevel['1.1.5.1'] = 1;
$errorLevel['1.1.6.1'] = 1;
$errorLevel['1.1.6.2'] = 1;
$errorLevel['1.1.6.3'] = 1;
$errorLevel['1.1.6.4'] = 1;
$errorLevel['1.1.6.5'] = 1;
$errorLevel['1.1.6.6'] = 1;
$errorLevel['1.1.9.1'] = 1;
$errorLevel['1.2.1.1'] = 1;
$errorLevel['1.4.1.1'] = 1;
$errorLevel['10.2.1.1'] = 2;
$errorLevel['10.2.1.2'] = 2;
$errorLevel['10.4.1.1'] = 3;
$errorLevel['10.4.1.2'] = 3;
$errorLevel['10.4.1.3'] = 3;
$errorLevel['11.2.1.10'] = 2;
$errorLevel['11.2.1.2'] = 2;
$errorLevel['11.2.1.3'] = 2;
$errorLevel['11.2.1.4'] = 2;
$errorLevel['11.2.1.5'] = 2;
$errorLevel['11.2.1.6'] = 2;
$errorLevel['11.2.1.7'] = 2;
$errorLevel['11.2.1.8'] = 2;
$errorLevel['11.2.1.9'] = 2;
$errorLevel['12.1.1.1'] = 1;
$errorLevel['12.1.1.2'] = 1;
$errorLevel['12.1.1.3'] = 1;
$errorLevel['1artisteer/2.4.1.1'] = 2;
$errorLevel['1artisteer/2.4.1.2'] = 2;
$errorLevel['1artisteer/2.4.1.3'] = 2;
$errorLevel['13.1.1.1'] = 2;
$errorLevel['13.1.1.2'] = 2;
$errorLevel['13.1.1.3'] = 2;
$errorLevel['13.1.1.4'] = 2;
$errorLevel['13.1.1.5'] = 2;
$errorLevel['13.1.1.6'] = 2;
$errorLevel['13.10.1.1'] = 3;
$errorLevel['13.2.1.1'] = 2;
$errorLevel['13.2.1.2'] = 2;
$errorLevel['13.2.1.3'] = 2;
$errorLevel['3.2.1.1'] = 2;
$errorLevel['3.5.1.1'] = 2;
$errorLevel['4.1.1.1'] = 1;
$errorLevel['4.3.1.1'] = 3;
$errorLevel['4.3.1.2'] = 3;
$errorLevel['5.1.2.1'] = 1;
$errorLevel['5.1.2.2'] = 1;
$errorLevel['5.1.2.3'] = 1;
$errorLevel['5.5.1.1'] = 3;
$errorLevel['5.5.1.2'] = 3;
$errorLevel['5.5.1.3'] = 3;
$errorLevel['5.5.1.6'] = 3;
$errorLevel['5.5.2.1'] = 2;
$errorLevel['6.2.1.1'] = 1;
$errorLevel['6.5.1.1'] = 2;
$errorLevel['6.5.1.2'] = 2;
$errorLevel['6.5.1.3'] = 2;
$errorLevel['6.5.1.4'] = 2;
$errorLevel['9.3.1.1'] = 2;
$errorLevel['9.3.1.2'] = 2;
$errorLevel['9.3.1.3'] = 2;
$errorLevel['9.3.1.4'] = 2;
$errorLevel['9.3.1.5'] = 2;
$errorLevel['9.3.1.6'] = 2;
$warningLevel['1.1.1.10'] = 1;
$warningLevel['1.1.1.11'] = 1;
$warningLevel['1.1.1.12'] = 1;
$warningLevel['1.1.1.2'] = 1;
$warningLevel['1.1.1.3'] = 1;
$warningLevel['1.1.1.4'] = 1;
$warningLevel['1.1.2.1'] = 1;
$warningLevel['1.1.2.2'] = 1;
$warningLevel['1.1.2.3'] = 1;
$warningLevel['1.1.3.1'] = 1;
$warningLevel['1.1.8.1'] = 1;
$warningLevel['1.5.1.1'] = 3;
$warningLevel['10.1.1.1'] = 2;
$warningLevel['10.1.1.2'] = 2;
$warningLevel['11.2.1.1'] = 2;
$warningLevel['2.1.1.1'] = 1;
$warningLevel['2.1.1.2'] = 1;
$warningLevel['2.1.1.3'] = 1;
$warningLevel['2.1.1.4'] = 1;
$warningLevel['2.1.1.5'] = 1;
$warningLevel['2.2.1.1'] = 3;
$warningLevel['2.2.1.2'] = 3;
$warningLevel['2.2.1.3'] = 3;
$warningLevel['2.2.1.4'] = 3;
$warningLevel['3.3.1.1'] = 2;
$warningLevel['3.5.2.1'] = 2;
$warningLevel['3.5.2.2'] = 2;
$warningLevel['3.5.2.3'] = 2;
$warningLevel['3.6.1.1'] = 2;
$warningLevel['3.6.1.2'] = 2;
$warningLevel['3.6.1.4'] = 2;
$warningLevel['5.2.1.1'] = 1;
$warningLevel['5.2.1.2'] = 1;
$warningLevel['5.3.1.1'] = 2;
$warningLevel['5.4.1.1'] = 2;
$warningLevel['5.6.1.1'] = 3;
$warningLevel['5.6.1.2'] = 3;
$warningLevel['5.6.1.3'] = 3;
$warningLevel['6.1.1.1'] = 1;
$warningLevel['6.1.1.2'] = 1;
$warningLevel['6.1.1.3'] = 1;
$warningLevel['6.2.2.1'] = 1;
$warningLevel['6.2.2.2'] = 1;
$warningLevel['6.2.2.3'] = 1;
$warningLevel['6.3.1.1'] = 1;
$warningLevel['6.3.1.2'] = 1;
$warningLevel['6.3.1.3'] = 1;
$warningLevel['6.3.1.4'] = 1;
$warningLevel['7.1.1.1'] = 1;
$warningLevel['7.1.1.2'] = 1;
$warningLevel['7.1.1.3'] = 1;
$warningLevel['7.1.1.4'] = 1;
$warningLevel['7.1.1.5'] = 1;
$warningLevel['7.2.1.1'] = 2;
$warningLevel['7.4.1.1'] = 2;
$warningLevel['7.5.1.1'] = 2;
$warningLevel['8.1.1.1'] = 1;
$warningLevel['8.1.1.2'] = 1;
$warningLevel['8.1.1.3'] = 1;
$warningLevel['8.1.1.4'] = 1;
$warningLevel['9.1.1.1'] = 1;

/**
 * Classe de gestion de Tidy soit via PECL (PHP 4 ou 5) soit en ligne de commande
 *
 * @package Pelican
 * @subpackage Html
 * @author __AUTHOR__
 */
class Pelican_Html_Tidy {
    
    /**
     * Constructeur
     *
     * @access public
     * @param __TYPE__ $options __DESC__
     * @return Pelican_Html_Tidy
     */
    function Pelican_Html_Tidy($options) {
        $this->options = $options;
        if (extension_loaded('tidy') && USE_PECL) {
            
            /** si l'extension PECL existe */
            if (class_exists('tidy')) {
                $this->tidy = new tidy;
            }
        } else {
            
            /** sinon on fait appel au binaire */
            if (!Pelican::$config["BIN_LOCAL_CONF"]) {
                if (Pelican::$config["ENV"]["REMOTE"]["OS"]["win"]) {
                    $os = "windows/tidy.exe";
                } else {
                    $os = "linux/tidy";
                }
                $this->tidy = dirname(__FILE__) . "/bin/" . $os;
            } else {
                $this->tidy = Pelican::$config["BIN_LOCAL_CONF"] . "tidy";
            }
            $this->temp = "/tmp";
        }
    }
    
    /**
     * Nettoyage du code Pelican_Html via 3 méthodes possibles : ligne de commande,
     * PECL PHP4 ou PECL PHP 5
     *
     * @access public
     * @param string $html Code Pelican_Html à traiter
     * @return __TYPE__
     */
    function clean($html) {
        if (extension_loaded('tidy') && USE_PECL) {
            if (class_exists('tidy')) {
                $this->tidy->parseString($html, $this->options);
                $this->tidy->cleanRepair();
                $this->html = strtr($this->tidy->body(), array("<body>\r\n" => "", "\r\n</body>" => ""));
                $this->html = strtr($this->html, array("<body>" => "", "</body>" => ""));
                $this->errors = $this->tidy->errorBuffer;
                $this->version = "PECL5";
            } else {
                $tidy = tidy_parse_string($html);
                foreach($this->options as $opt => $val) {
                    tidy_setopt($opt, $val);
                }
                tidy_clean_repair();
                tidy_diagnose();
                $this->html = str_replace("\n", " ", tidy_get_output());
                $this->html = strtr($this->html, array("<br />  " => "<br />", "<p> " => "<p>"));
                $this->errors = tidy_get_error_buffer();
                $this->version = "PECL4";
            }
        } else {
            $options = "-asxhtml " . $this->getCommandOptions($this->options);
            $file = tempnam($this->temp, 'tidy-php-tmp');
            $dest = tempnam($this->temp, 'tidy-php-dest');
            $errfile = tempnam($this->temp, 'tidy-php-err');
            $fp = fopen($file, "w");
            fwrite($fp, $html);
            fclose($fp);
            $cmd = $this->tidy . " --error-file " . $errfile . " " . $options . " " . $file . " > " . $dest;
            Pelican::runCommand($cmd);
            $this->html = implode("", file($dest));
            $this->errors = implode("", file($errfile));
            unlink($file);
            unlink($errfile);
            unlink($dest);
            $this->version = "CMD";
        }
        if (!$this->options['indent']) {
            $this->html = str_replace(">\n", ">", $this->html);
            $this->html = str_replace(">\r", ">", $this->html);
        }
        if ($this->options['word-2000']) {
            $this->cleanWord();
        }
    }
    
    /**
     * Suppression des syntaxes issues de MS WORD non nettoyée par tidy
     *
     * @access public
     * @return __TYPE__
     */
    function cleanWord() {
        if ($this->html) {
            $this->html = str_replace("class=\"MsoNormal\"", "", $this->html);
            $this->html = str_replace("<o:p>", "", $this->html);
            $this->html = str_replace("</o:p>", "", $this->html);
        }
    }
    
    /**
     * Affichage des erreurs issues de tidy
     *
     * @access public
     * @param int $accessibilityLevel (option) Niveau d'accessibilité attendu
     * @return __TYPE__
     */
    function showErrors($accessibilityLevel = 1) {
        //preg_match_all("/line (.*) column (.*) - (Warning:|Error:|Access:)(.*)/i", $this->errors, $matches);
        preg_match_all('/^(?:line (\d+) column (\d+) - )?(\S+): (?:\[((?:\d+\.?){4})]:)?(.*?)$/m', $this->errors, $matches);
        if ($matches) {
            $aHTML = explode("\n", $input);
            for ($i = 0;$i < count($matches[0]);$i++) {
                if ($matches[3][$i] != 'Info') {
                    $tab = strtoupper(str_replace(":", "", $matches[3][$i]));
                    $gravite = 0;
                    if ($matches[4][$i]) {
                        $access = $this->controlAccessibility($matches[4][$i], $accessibilityLevel);
                        if (!$access) {
                            $gravite = 1;
                        }
                    } else {
                        $gravite = $this->controlErrors($matches[5][$i]);
                    }
                    if ($gravite) {
                        $errors[$tab . " " . $gravite][$matches[1][$i] . "." . $matches[2][$i]] = array("ligne" => $matches[1][$i], "colonne" => $matches[2][$i], "accessibility" => $matches[4][$i], "message" => $matches[5][$i], "ligne" => $aHTML[$matches[1][$i] - 1]);
                        $errors2[$tab . " " . $gravite][$matches[1][$i] . "." . $matches[2][$i]] = Pelican_Html::tr(Pelican_Html::td($matches[1][$i]) . Pelican_Html::td($matches[2][$i]) . Pelican_Html::td(Pelican_Text::htmlentities($matches[4][$i])) . Pelican_Html::td(Pelican_Text::htmlentities($matches[5][$i])) . Pelican_Html::td(Pelican_Text::htmlentities($aHTML[$matches[1][$i] - 1])));
                    }
                }
            }
        }
        $return = "";
        if ($errors2) {
            @ksort($errors2);
            foreach($errors2 as $key => $values) {
                @ksort($values);
                if ($values) {
                    $return.= Pelican_Html::h2($key);
                    $return.= Pelican_Html::table(implode("", $values));
                }
            }
        }
        $return = Pelican_Html::div(array(id => "checkHTML", style => "position:absolute;top:1500px;left:0px;width:100%"), $return);
        echo $return;
    }
    
    /**
     * Suppression ou non d'un message d'erreur et détemrination de son niveau de
     * gravité
     *
     * @access public
     * @param string $value Message d'erreur de tidy
     * @return int
     */
    function controlErrors($value) {
        $priority[0][] = "<img> discarding newline in URI reference";
        $priority[0][] = "<img> escaping malformed URI reference";
        $priority[0][] = "<style> isn't allowed in <div> elements";
        $priority[2][] = "trimming empty <p>";
        if ($value) {
            if (in_array(trim($value), $priority[0])) {
                return false;
            } else {
                for ($i = 1;$i < 5;$i++) {
                    if (is_array($priority[$i])) {
                        if (in_array(trim($value), $priority[$i])) {
                            return $i;
                        }
                    }
                }
                return 3;
            }
        } else {
            return false;
        }
    }
    
    /**
     * Test du code retour d'accessibilité retourné par tidy
     * et détermination de son niveau de gravité comparé à celui attendu
     *
     * @access public
     * @param string $id Code d'erreur d'accessibilité retourné par tidy
     * @param int $accessibilityLevel (option) Niveau d'accessibilité testé
     * @return bool
     */
    function controlAccessibility($id, $accessibilityLevel = 1) {
        global $errorLevel, $warningLevel;
        $return = false;
        if ($errorLevel[trim($id) ]) {
            if ($errorLevel[trim($id) ] > $accessibilityLevel) {
                $return = true;
            }
        } elseif ($warningLevel[trim($id) ]) {
            $return = true;
        }
        return $return;
    }
    
    /**
     * Définitions des option s de tidy les plus souvent utilisées
     *
     * @access public
     * @return mixed
     */
    function defaultOptions() {
        $options['quiet'] = '1';
        $options['force-output'] = '1';
        $options['show-warnings'] = 'false';
        $options['drop-proprietary-attributes'] = '1';
        $options['show-body-only'] = '1';
        $options['word-2000'] = '1';
        $options['indent'] = '0';
        $options['indent-attributes'] = '0';
        $options['indent-spaces'] = '0';
        $options['wrap'] = '0';
        $options['wrap-attributes'] = '0';
        $options['drop-proprietary-attributes'] = '0';
        $options['output-xhtml'] = '1';
        $options['accessibility-check'] = '0';
        $options['bare'] = '1';
        $options['enclose-block-text'] = '0';
        $options['enclose-text'] = '0';
        $options['fix-backslash'] = '1';
        $options['logical-emphasis'] = '1';
        $options['hide-comments'] = '1';
        //$options['clean'] = '1';
        //$options['join-styles'] = '1';
        return $options;
    }
    
    /**
     * Ecriture des options pour l'appel en mode ligne de commande (en l'absence de
     * l'extension PECL)
     *
     * @access public
     * @param mixed $opt __DESC__
     * @return string
     */
    function getCommandOptions($opt) {
        if ($opt) {
            foreach($opt as $key => $value) {
                $return[] = "--" . $key . " " . $value;
            }
            $return = implode(" ", $return);
        }
        return $return;
    }
}

/**
 * Mise en conformité du code Pelican_Html passé en POST
 *
 * @param string $html __DESC__
 * @return string
 */
function cleanHtmlInput($html) {
    if ($html) {
        $html = Pelican_Text::rawurldecode(stripslashes($html));
        $html = str_replace("href=\"null\"", "", $html);
        $html = str_replace("href=\"http://\"", "", $html);
        return $html;
    }
}
?>