<?php
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/BoForms.php');

class BoForms_Cms_Page_Bloc_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        $data = $this->getParams();
        $form = Pelican_Cache::fetch("BoForms", array(
            $data['ZONE_TEXTE']
        ), "", "boforms");
        $formDef = array();
        if (! empty($form)) {
            $formDef = json_decode($form['BOFORMS_STRUCTURE'], true);
        }
        $this->assign('css', $form['BOFORMS_CSS']);
        $this->assign('data', $data);
        $this->assign('formid', "boforms" . $data['ZONE_TEMPLATE_ID']);
        $this->assign('BOFORMS_ID', $data['ZONE_TEXTE']);
        $this->assign('form', $formDef);
        // captcha
        if (strpos($form['BOFORMS_STRUCTURE'], '"type":"captcha"') != 0) {
            require_once (Pelican::$config['PLUGIN_ROOT'] . '/boforms/library/recaptchalib.php');
            $publickey = Pelican::$config['BOFORMS']['CAPTCHA']['PUBLIC_KEY'];
            $this->assign('captcha', recaptcha_get_html($publickey), false);
        }
        // language
        $aLangue = Pelican_Cache::fetch('Language');
        $langCode = strtolower($aLangue[$form['LANGUE_ID']]['LANGUE_CODE']);
        $this->assign('lang', $langCode);

        $head = $this->getView()->getHead();
        $head->setCss(Pelican_Plugin::getMediaPath('boforms') . 'css/boforms.css');
        $head->setCss(Pelican_Plugin::getMediaPath('boforms') . 'css/validationEngine.jquery.css');
        $head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/jquery.validationEngine.js');
        $head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/other.validation.js');
        $head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/languages/jquery.validationEngine-' . $langCode . '.js');
        $head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/languages/other.validation-' . $langCode . '.js');

        $this->fetch();
    }

    public function saveAction()
    {
        $oConnection = Pelican_Db::getInstance();

        if (! empty($_POST['BOFORMS_ID'])) {

            $form = Pelican_Cache::fetch("BoForms", array(
                $_POST['BOFORMS_ID']
            ), "", "boforms");
            $formDef = array();
            $formVar = array();
            if (! empty($form)) {
                $formDef = json_decode($form['BOFORMS_STRUCTURE'], true);
                if (! empty($formDef)) {
                    $formVar = array_keys(json_decode($form['BOFORMS_FIELDS'], true));
                }
            }

            $continue = true;

            if (strpos($form['BOFORMS_STRUCTURE'], '"type":"captcha"') != 0) {
                require_once (Pelican::$config['PLUGIN_ROOT'] . '/boforms/library/recaptchalib.php');
                $privatekey = Pelican::$config['BOFORMS']['CAPTCHA']['PRIVATE_KEY'];
                $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
                if (! $resp->is_valid) {
                    // What happens when the CAPTCHA was entered incorrectly
                    $this->setResponse(json_encode("The reCAPTCHA wasn't entered correctly. Go back and try it again." . "(reCAPTCHA said: " . $resp->error . ")"));
                    $continue = false;
                }
            }

            if ($continue) {
                $values = $_POST;
                $var = array();
                if (is_array($formVar)) {
                    foreach ($formVar as $field) {
                        $var['%' . $field . '%'] = $values[$field];
                    }
                }

                switch ($form['BOFORMS_MODE']) {
                    case 'database':
                        {
                            $aBind[':BOFORMS_ID'] = $values['BOFORMS_ID'];
                            Pelican_Db::$values['BOFORMS_ID'] = $values['BOFORMS_ID'];
                            Pelican_Db::$values['BOFORMS_VALUE_STRUCTURE'] = $oConnection->queryItem('SELECT BOFORMS_STRUCTURE from #pref#_boforms where BOFORMS_ID=:BOFORMS_ID', $aBind);
                            Pelican_Db::$values['BOFORMS_VALUE_ID'] = - 2;
                            Pelican_Db::$values['BOFORMS_VALUE_DATA'] = json_encode($values);
                            $oConnection->insertQuery('#pref#_boforms_value');
                            $this->setResponse(json_encode('Vos données ont bien été enregistrées'));
                            break;
                        }
                    case 'mail':
                        {
                            $i = 0;
                            if (is_array($values)) {
                                foreach ($formDef['fields'] as $field) {
                                    $i ++;
                                    switch ($field['type']) {
                                        case 'submit':
                                        case 'section':
                                        case 'captcha':
                                            break;
                                        case 'radio':
                                        case 'select':
                                            {
                                                if (is_array($values[$field['name']])) {
                                                    $data[] = $field['title'] . ' : ' . $values[$field['name']][0];
                                                }
                                                break;
                                            }
                                        case 'checkbox':
                                            {
                                                if (is_array($values[$field['name']])) {
                                                    $data[] = $field['title'] . ' : ' . implode(' / ', $values[$field['name']]);
                                                }
                                                break;
                                            }
                                        case 'email':
                                            if (empty($mailField)) {
                                                $mailField = $field['name'];
                                            }
                                        default:
                                            {
                                                $data[] = $field['title'] . ' : ' . $values[$field['name']];
                                                break;
                                            }
                                    }
                                }
                            }
                            $body = implode("<br />", $data);
                            $transport = null;
                            if (! empty($form['BOFORMS_SMTP_HOST'])) {
                                $config = array(
                                    'auth' => 'login',
                                    'username' => $form['BOFORMS_SMTP_USER'],
                                    'password' => $form['BOFORMS_SMTP_PWD']
                                );

                                $transport = new Zend_Mail_Transport_Smtp($form['BOFORMS_SMTP_HOST'], $config);
                            } else {
                                $transport = $transport = new Zend_Mail_Transport_Sendmail('-f' . $form['BOFORMS_MAIL_EXP']);
                            }

                            $defMail = Pelican_Cache::fetch("BoForms/Mail", array(
                                $_POST['BOFORMS_ID']
                            ), "", "boforms");

                            foreach ($defMail as $valueMail) {
                                $mail = new Zend_Mail('UTF-8');
                                $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
                                switch ($valueMail['BOFORMS_MAIL_BODY_TYPE']) {
                                    case 'text':
                                        {
                                            $mail->setBodyText(strtr($valueMail['BOFORMS_MAIL_BODY_TEXT'], $var), null, Zend_Mime::ENCODING_7BIT);
                                            break;
                                        }
                                    case 'html':
                                        {
                                            $mail->setBodyHtml(str_replace('#MEDIA_HTTP#', Pelican::$config['MEDIA_HTTP'], strtr($valueMail['BOFORMS_MAIL_BODY_HTML'], $var)), null, Zend_Mime::ENCODING_7BIT);
                                            break;
                                        }
                                }
                                if ($valueMail['BOFORMS_MAIL_EXP'] == '%MAIL%' && ! empty($mailField)) {
                                    $valueMail['BOFORMS_MAIL_EXP'] = $values[$mailField];
                                }
                                $mail->setFrom($valueMail['BOFORMS_MAIL_EXP'], $valueMail['BOFORMS_MAIL_EXP']);
                                if ($valueMail['BOFORMS_MAIL_DEST'] == '%MAIL%' && ! empty($mailField)) {
                                    $valueMail['BOFORMS_MAIL_DEST'] = $values[$mailField];
                                }
                                if (!empty($valueMail['BOFORMS_MAIL_DEST']) && $valueMail['BOFORMS_MAIL_DEST'] != '%MAIL%') {
                                $mail->addTo($valueMail['BOFORMS_MAIL_DEST'], $valueMail['BOFORMS_MAIL_DEST']);
                                if (! empty($valueMail['BOFORMS_MAIL_CC'])) {
                                    $cc = explode(";", $valueMail['BOFORMS_MAIL_CC']);
                                    if (is_array($cc)) {
                                        foreach ($cc as $mailcc) {
                                            $mail->addCc(implode(';', explode("\n", trim($mailcc))));
                                        }
                                    }
                                }
                                if (! empty($valueMail['BOFORMS_MAIL_CCI'])) {
                                    $cci = explode(";", $valueMail['BOFORMS_MAIL_CCI']);
                                    if (is_array($cci)) {
                                        foreach ($cci as $mailcc) {
                                            $mail->addBcc(implode(';', explode("\n", trim($mailcc))));
                                        }
                                    }
                                }

                                // attachment
                                if (! empty($valueMail['ATTACHMENT'])) {
                                    foreach ($valueMail['ATTACHMENT'] as $file) {
                                        if (file_exists($file)) {
                                            $at = new Zend_Mime_Part(file_get_contents($file));
                                            $at->filename = basename($file);
                                            $at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
                                            $at->encoding = Zend_Mime::ENCODING_8BIT;
                                            $mail->addAttachment($at);
                                        }
                                    }
                                }

                                $mail->setSubject($valueMail['BOFORMS_MAIL_SUBJECT']);
                                $mail->send();
                                if ($mail->send($transport)) {
                                    if (! empty($formDef['success'])) {
                                        $this->setResponse(json_encode($formDef['success']));
                                    }
                                }
                                unset($mail);
                                } else {
                                    $this->setResponse(json_encode(t("BOFORMS_NO_EMAIL_DEFINED")));
                                }
                            }
                            break;
                        }
                }
            }
        } else {
            $this->setResponse(json_encode('Rien à enregistrer'));
        }
    }
}
