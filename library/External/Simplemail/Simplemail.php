<?php
/**
 * Gestion d'envoi des mails
 *
 * @package Pelican
 * @subpackage __SUBPACKAGE__
 * @author __AUTHOR__
 */
/**
 * Classe permettant de gérer l'envoi de mails
 *
 * @package Pelican
 * @subpackage __SUBPACKAGE__
 * @author Tetsuo
 * @copyright License GPL
 * @since 16/04/2003
 * @version 1.0
 */
class Simplemail
{

    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    var $recipient;

    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    var $subject;

    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    var $hfrom;

    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    var $from;

    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    var $headers;

    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    var $hbcc;

    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    var $hcc;

    /**
     * Le texte du corp du mail en ASCII
     *
     * @access public
     * @var string
     */
    var $text;

    /**
     * Le corps du mail en Pelican_Html
     *
     * @access public
     * @var string
     */
    var $html;

    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    var $attachement;

    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    var $htmlattachement;

    /**
     * C'est la chaine qui contient toute les erreurs recensées lors de la compositon
     * du mail
     *
     * @access public
     * @var string
     */
    var $error_log;

    /**
     * Constructeur des instances de mail. Il initialise les variables de la classe.
     *
     * @access public
     * @return Simplemail
     */
    function Simplemail ()
    {
        $this->attachement = array();
        $this->htmlattachement = array();
    }

    /**
     * Vérification de l'adresse mail
     *
     * @access public
     * @param string $address L'adresse mail
     * @return bool
     */
    function checkaddress ($address)
    {
        if (preg_match('`^([[:alnum:]]([-_.]?[[:alnum:]])*@[[:alnum:]]([-_.]?[[:alnum:]])*\.([a-z]{2,4}))$`', $address)) {
            //   if (preg_match("`[0-9a-zA-Z\.\-_]+@[0-9a-zA-z\-]{3,}\.[a-z]{2,4}`" , $address ) ) {
            return true;
        } else {
            $this->error_log .= "l'adresse $address est invalide\n";
            return false;
        }
    }

    /**
     * Vérification du nom de destinataire
     *
     * @access public
     * @param string $name Le nom du destinataire
     * @return bool
     */
    function checkname ($name)
    {
        if (preg_match("`[0-9a-zA-Z\.\-_ ]*`", $name)) {
            return true;
        } else {
            $this->error_log .= " le pseudo $name est invalide\n";
            return false;
        }
    }

    /**
     * Formattage du nom et de l'email
     *
     * @access public
     * @param string $address L'adresse mail
     * @param string $name Le nom du destinataire
     * @return string
     */
    function makenameplusaddress ($address, $name)
    {
        if (! $this->checkaddress($address))
            return false;
        if (! $this->checkname($name))
            return false;
        if (empty($name)) {
            return $address;
        } else {
            $tmp = $name . " <" . $address . ">";
            return $tmp;
        }
    }

    /**
     * Ajoute un destinataire ( TO: ). Renvoi true si l'adresse est valable. false
     * sinon.
     *
     * @access public
     * @param string $newrecipient L'adresse mail
     * @param string $name (option) Le nom du destinataire
     * @return bool
     */
    function addrecipient ($newrecipient, $name = '')
    {
        $tmp = $this->makenameplusaddress($newrecipient, $name);
        if (! $tmp) {
            $this->error_log .= " To: error\n";
            return false;
        }
        if (! empty($this->recipient))
            $this->recipient .= ",";
        $this->recipient .= $tmp;
        return true;
    }

    /**
     * Ajouter un destiantaire copie conforme ( Cc: ) si l'adresse est valide.
     *
     * @access public
     * @param string $bcc L'adresse mail
     * @param string $name (option) Le nom du destinataire
     * @return bool
     */
    function addbcc ($bcc, $name = '')
    {
        $tmp = $this->makenameplusaddress($bcc, $name);
        if (! $tmp) {
            $this->error_log .= " Bcc: error\n";
            return false;
        }
        if (! empty($this->hbcc))
            $this->hbcc .= ",";
        $this->hbcc .= $tmp;
        return true;
    }

    /**
     * Ajouter un destiantaire copie conforme ( Cc: ) si l'adresse est valide.
     *
     * @access public
     * @param string $cc L'adresse mail
     * @param string $name (option) Le nom du destinataire
     * @return bool
     */
    function addcc ($cc, $name = '')
    {
        $tmp = $this->makenameplusaddress($cc, $name);
        if (! $tmp) {
            $this->error_log .= " Cc: error\n";
            return false;
        }
        if (! empty($this->hcc))
            $this->hcc .= ",";
        $this->hcc .= $tmp;
        return true;
    }

    /**
     * Spécifier le sujet du mail ( Subject: ).
     *
     * @access public
     * @param string $subject Le sujet du mail
     * @return void
     */
    function addsubject ($subject)
    {
        if (! empty($subject))
            $this->subject = $subject;
    }

    /**
     * Spécifie l'expediteur ( From: ) si l'adresse est valide.
     *
     * @access public
     * @param string $from L'adresse mail
     * @param string $name (option) Le nom du destinataire ( facultatif )
     * @return bool
     */
    function addfrom ($from, $name = "")
    {
        $tmp = $this->makenameplusaddress($from, $name);
        if (! $tmp) {
            $this->error_log .= " From: error\n";
            return false;
        }
        $this->from = $from;
        $this->hfrom = $tmp;
        return true;
    }

    /**
     * Spécifie l'adresse de retour
     *
     * @access public
     * @param string $return Pour spécifier l'adresse de retour. ( Return-Path: ) si
     * l'adresse est valide.
     * @return bool
     */
    function addreturnpath ($return)
    {
        $tmp = $this->makenameplusaddress($return, '');
        if (! $tmp) {
            $this->error_log .= " Return-Path: error\n";
            return false;
        }
        $this->returnpath = $return;
        return true;
    }

    /**
     * Spécifier l'adresse de reponse. ( Reply-To: ) si l'adresse est valide.
     *
     * @access public
     * @param string $replyto Pour spécifier l'adresse de reponse. ( Reply-To: ) si
     * l'adresse est valide.
     * @return bool
     */
    function addreplyto ($replyto)
    {
        $tmp = $this->makenameplusaddress($replyto, '');
        if (! $tmp) {
            $this->error_log .= " Reply-To: error\n";
            return false;
        }
        $this->returnpath = $tmp;
        return true;
    }

    // les attachements
    /**
     * Ajouter un attachement ( fichier joint) dans le mail. Cette fonction ne permet
     * pas d'afficher l'attachement ds le contenu html.
     *
     * @access public
     * @param string $filename Le chemin jusqu'au fichier a inclure
     * @param __TYPE__ $contenttype (option) __DESC__
     * @param string $rename (option) __DESC__
     * @return void
     */
    function addattachement ($filename, $contenttype = 'application/octetstream', $rename = "")
    {
        if ($rename == "") {
            $rename = $filename;
        }
        array_push($this->attachement, array('filename' => $filename , 'contenttype' => $contenttype , 'rename' => $rename));
    }

    // les attachements Pelican_Html
    /**
     * Ajoute le fichier en piece jointe en vue de l'afficher dans le mail au format
     * Pelican_Html ( comme des image par exemple ). cid ( content-id ) represente le nom
     * auquel vous ferez reference dans votre contenu Pelican_Html (ex: &lt; img
     * src="cid:monimage1" &gt;) et content-type precise le type de document ( ex:
     * image/png ).
     *
     * @access public
     * @param string $filename Le chemin jusqu'au fichier a inclure
     * @param string $cid (option) Le content id, permet de faire reference a votre
     * fichier attaché ds les tags Pelican_Html
     * @param string $contenttype (option) Le type MIME du fichier attaché
     * @return void
     */
    function addhtmlattachement ($filename, $cid = '', $contenttype = '')
    {
        array_push($this->htmlattachement, array('filename' => $filename , 'cid' => $cid , 'contenttype' => $contenttype));
    }

    /**
     * Envoie le mail composé
     *
     * @access public
     * @return bool
     */
    function sendmail ()
    {
        
        if (empty($this->recipient)) {
            $this->error_log .= "destinataire manquant\n";
            return false;
        }
        if (empty($this->subject)) {
            $this->error_log .= "sujet manquant\n";
            return false;
        }
        if (! empty($this->hfrom))
            $this->headers .= "From: " . $this->hfrom . "\n";
        if (! empty($this->returnpath))
            $this->headers .= "Return-Path: " . $this->returnpath . "\n";
        if (! empty($this->replyto))
            $this->headers .= "Reply-To: " . $this->replyto . "\n";
        $this->headers .= "MIME-Version: 1.0\n";
        if (! $this->html && $this->text) {
            $B1B = "----=_001";
            $this->headers .= "Content-Type: multipart/mixed;\n\t boundary=\"" . $B1B . "\"\n";
            //Messages start with text/html alternatives in OB
            $message = "This is a multi-part message in MIME format.\n";
            $message .= "\n--" . $B1B . "\n";
            $message .= "Content-Type: text/plain; charset=\"" . (Pelican::$config["CHARSET"] ? Pelican::$config["CHARSET"] : "ISO-8859-1") . "\"\n";
            $message .= "Content-Transfer-Encoding: 8bit\n\n";
            //$message .= "Content-Transfer-Encoding: quoted-printable\n\n";
            // plaintext goes here
            $message .= $this->text . "\n\n";
            if (! empty($this->attachement)) {
                foreach ($this->attachement as $AttmFile) {
                    $patharray = explode("/", $AttmFile['filename']);
                    $FileName = $patharray[count($patharray) - 1];
                    $message .= "\n--" . $B1B . "\n";
                    $message .= "Content-Type: " . $AttmFile['contenttype'] . ";\n name=\"" . $FileName . "\"\n";
                    $message .= "Content-Transfer-Encoding: base64\n";
                    $message .= "Content-Disposition: attachment;\n filename=\"" . $AttmFile['rename'] . "\"\n\n";
                    $fd = fopen($AttmFile['filename'], "rb");
                    $FileContent = fread($fd, filesize($AttmFile['filename']));
                    fclose($fd);
                    $FileContent = chunk_split(base64_encode($FileContent));
                    $message .= $FileContent;
                    $message .= "\n\n";
                }
            }
            //message ends
            $message .= "\n--" . $B1B . "--\n";
        } elseif ($this->html) {
            $B1B = "----=_001";
            $B2B = "----=_002";
            $B3B = "----=_003";
            if (! $this->text) {
                $this->text = "HTML only!";
            }
            $this->headers .= "Content-Type: multipart/mixed;\n\t boundary=\"" . $B1B . "\"\n";
            //Messages start with text/html alternatives in OB
            $message = "This is a multi-part message in MIME format.\n";
            $message .= "\n--" . $B1B . "\n";
            $message .= "Content-Type: multipart/related;\n\t boundary=\"" . $B2B . "\"\n\n";
            //plaintext section
            $message .= "\n--" . $B2B . "\n";
            $message .= "Content-Type: multipart/alternative;\n\t boundary=\"" . $B3B . "\"\n\n";
            //plaintext section
            $message .= "\n--" . $B3B . "\n";
            $message .= "Content-Type: text/plain; charset=\"" . (Pelican::$config["CHARSET"] ? Pelican::$config["CHARSET"] : "ISO-8859-1") . "\"\n";
            $message .= "Content-Transfer-Encoding: quoted-printable\n\n";
            // plaintext goes here
            $message .= $this->text . "\n\n";
            // Pelican_Html section
            $message .= "\n--" . $B3B . "\n";
            $message .= "Content-Type: text/html; charset=\"" . (Pelican::$config["CHARSET"] ? Pelican::$config["CHARSET"] : "ISO-8859-1") . "\"\n";
            $message .= "Content-Transfer-Encoding: base64\n\n";
            // Pelican_Html goes here
            $message .= chunk_split(base64_encode($this->html)) . "\n\n";
            // end of text
            $message .= "\n--" . $B3B . "--\n";
            // attachments Pelican_Html
            if (! empty($this->htmlattachement)) {
                foreach ($this->htmlattachement as $AttmFile) {
                    $patharray = explode("/", $AttmFile['filename']);
                    $FileName = $patharray[count($patharray) - 1];
                    $message .= "\n--" . $B2B . "\n";
                    $message .= "Content-Type: {$AttmFile['contenttype']};\n name=\"" . $FileName . "\"\n";
                    $message .= "Content-Transfer-Encoding: base64\n";
                    $message .= "Content-ID: <{$AttmFile['cid']}>\n";
                    $message .= "Content-Disposition: inline;\n filename=\"" . $FileName . "\"\n\n";
                    $fd = fopen($AttmFile['filename'], "rb");
                    $FileContent = fread($fd, filesize($AttmFile['filename']));
                    fclose($fd);
                    $FileContent = chunk_split(base64_encode($FileContent));
                    $message .= $FileContent;
                    $message .= "\n\n";
                }
            }
            //html ends
            $message .= "\n--" . $B2B . "--\n";
            if (! empty($this->attachement)) {
                foreach ($this->attachement as $AttmFile) {
                    $patharray = explode("/", $AttmFile['filename']);
                    $FileName = $patharray[count($patharray) - 1];
                    $message .= "\n--" . $B1B . "\n";
                    $message .= "Content-Type: " . $AttmFile['contenttype'] . ";\n name=\"" . $FileName . "\"\n";
                    $message .= "Content-Transfer-Encoding: base64\n";
                    $message .= "Content-Disposition: attachment;\n filename=\"" . $AttmFile['rename'] . "\"\n\n";
                    $fd = fopen($AttmFile['filename'], "rb");
                    $FileContent = fread($fd, filesize($AttmFile['filename']));
                    fclose($fd);
                    $FileContent = chunk_split(base64_encode($FileContent));
                    $message .= $FileContent;
                    $message .= "\n\n";
                }
            }
            //message ends
            $message .= "\n--" . $B1B . "--\n";
        }
        if (! empty($this->hcc))
            $this->headers .= "Cc: " . $this->hcc . "\n";
        if (! empty($this->hbcc))
            $this->headers .= "Bcc: " . $this->hbcc . "\n";
        $recipient = $this->recipient;
        $subject = $this->subject;
        if (@mail($recipient, $subject, $message, $this->headers, "-f " . $this->from)) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * @return integer
 * @param string $Email Email à vérifier
 * @desc Vérification de l'existence d'un serveur de mail (serveur MX) à l'écoute sur un nom de domaine donné
 */
function checkMailServer ($Email)
{
    $Return = array();
    if (! preg_match("/^[0-9a-z]([0-9a-z\-\.\_])*@[0-9a-z]([\-\.]?[0-9a-z])*([\.]{1}[a-z]{2,6})$/i", $Email)) {
        //RFC if (!preg_match("#^(\w¦\-¦\_¦\.)+\@((\w¦\-¦\_)+\.)+[a-zA-Z]{2,}$#i", $Email)) {
        return 2;
    }
    list ($Username, $Domain) = explode("@", $Email);
    if (checkdnsrr($Domain, "MX")) {
        getmxrr($Domain, $MXHost);
        $ConnectAddress = $MXHost[0];
    } else {
        $ConnectAddress = $Domain;
    }
    $Connect = fsockopen($ConnectAddress, 25);
    if (! $Connect) {
        return 0;
    }
    return 1;
}

/**
 * @return void
 * @param string $fromEmail   Email de l'expéditeur
 * @param string $fromName   Nom de l'expéditeur
 * @param mixed  $destinataire  Tableau des destinataires contenant "email","name","subject","message"
 * @desc Envoi d'email à partir d'un tableau
 */
function easyMail ($fromEmail, $fromName, $destinataire)
{
    foreach ($destinataire as $dest) {
        if ($dest["email"] != "") {
            $mail = new Simplemail();
            $mail->addfrom($fromEmail, $fromName);
            $mail->addrecipient($dest["email"], $dest["name"]);
            $mail->addsubject($dest["subject"]);
            $mail->html = str_replace("\'", "'", $dest["message"]);
            $mail->sendmail();
            if ($mail->error_log) {
                debug($mail->error_log);
            }
            unset($mail);
        }
    }
}
?>