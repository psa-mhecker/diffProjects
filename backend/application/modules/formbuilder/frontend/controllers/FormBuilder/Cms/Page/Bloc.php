<?php
include Pelican::$config["PLUGIN_ROOT"].'/formbuilder/library/FormBuilder.php';

class FormBuilder_Cms_Page_Bloc_Controller extends Pelican_Controller_Front
{
    public function indexAction()
    {
        $data = $this->getParams();
        $form = Pelican_Cache::fetch("FormBuilder", array(
            $data['ZONE_TEXTE'],
        ), "", "formbuilder");
        $formDef = array();
        if (! empty($form)) {
            $formDef = json_decode($form['FORMBUILDER_STRUCTURE'], true);
        }
        $this->assign('css', $form['FORMBUILDER_CSS']);
        $this->assign('data', $data);
        $this->assign('formid', "formbuilder".$data['ZONE_TEMPLATE_ID']);
        $this->assign('FORMBUILDER_ID', $data['ZONE_TEXTE']);
        $this->assign('form', $formDef);
        // captcha
        if (strpos($form['FORMBUILDER_STRUCTURE'], '"type":"captcha"') != 0) {
            require_once Pelican::$config['PLUGIN_ROOT'].'/formbuilder/library/recaptchalib.php';
            $publickey = Pelican::$config['FORMBUILDER']['CAPTCHA']['PUBLIC_KEY'];
            $this->assign('captcha', recaptcha_get_html($publickey), false);
        }
        // language
        $aLangue = Pelican_Cache::fetch('Language');
        $langCode = strtolower($aLangue[$form['LANGUE_ID']]['LANGUE_CODE']);
        $this->assign('lang', $langCode);

        $head = $this->getView()->getHead();
        $head->setCss(Pelican_Plugin::getMediaPath('formbuilder').'css/formbuilder.css');
        $head->setCss(Pelican_Plugin::getMediaPath('formbuilder').'css/validationEngine.jquery.css');
        $head->setJs(Pelican_Plugin::getMediaPath('formbuilder').'js/jquery.validationEngine.js');
        $head->setJs(Pelican_Plugin::getMediaPath('formbuilder').'js/other.validation.js');
        $head->setJs(Pelican_Plugin::getMediaPath('formbuilder').'js/languages/jquery.validationEngine-'.$langCode.'.js');
        $head->setJs(Pelican_Plugin::getMediaPath('formbuilder').'js/languages/other.validation-'.$langCode.'.js');

        $this->fetch();
    }

    public function saveAction()
    {
        $oConnection = Pelican_Db::getInstance();

        if (! empty($_POST['FORMBUILDER_ID'])) {
            $form = Pelican_Cache::fetch("FormBuilder", array(
                $_POST['FORMBUILDER_ID'],
            ), "", "formbuilder");
            $formDef = array();
            $formVar = array();
            if (! empty($form)) {
                $formDef = json_decode($form['FORMBUILDER_STRUCTURE'], true);
                if (! empty($formDef)) {
                    $formVar = array_keys(json_decode($form['FORMBUILDER_FIELDS'], true));
                }
            }

            $continue = true;

            if (strpos($form['FORMBUILDER_STRUCTURE'], '"type":"captcha"') != 0) {
                require_once Pelican::$config['PLUGIN_ROOT'].'/formbuilder/library/recaptchalib.php';
                $privatekey = Pelican::$config['FORMBUILDER']['CAPTCHA']['PRIVATE_KEY'];
                $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
                if (! $resp->is_valid) {
                    // What happens when the CAPTCHA was entered incorrectly
                    $this->setResponse(json_encode("The reCAPTCHA wasn't entered correctly. Go back and try it again."."(reCAPTCHA said: ".$resp->error.")"));
                    $continue = false;
                }
            }

            if ($continue) {
                $values = $_POST;
                $var = array();
                if (is_array($formVar)) {
                    foreach ($formVar as $field) {
                        $var['%'.$field.'%'] = $values[$field];
                    }
                }

                switch ($form['FORMBUILDER_MODE']) {
                    case 'database':
                        {
                            $aBind[':FORMBUILDER_ID'] = $values['FORMBUILDER_ID'];
                            Pelican_Db::$values['FORMBUILDER_ID'] = $values['FORMBUILDER_ID'];
                            Pelican_Db::$values['FORMBUILDER_VALUE_STRUCTURE'] = $oConnection->queryItem('SELECT FORMBUILDER_STRUCTURE from #pref#_formbuilder where FORMBUILDER_ID=:FORMBUILDER_ID', $aBind);
                            Pelican_Db::$values['FORMBUILDER_VALUE_ID'] = - 2;
                            Pelican_Db::$values['FORMBUILDER_VALUE_DATA'] = json_encode($values);
                            $oConnection->insertQuery('#pref#_formbuilder_value');
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
                                                    $data[] = $field['title'].' : '.$values[$field['name']][0];
                                                }
                                                break;
                                            }
                                        case 'checkbox':
                                            {
                                                if (is_array($values[$field['name']])) {
                                                    $data[] = $field['title'].' : '.implode(' / ', $values[$field['name']]);
                                                }
                                                break;
                                            }
                                        case 'email':
                                            if (empty($mailField)) {
                                                $mailField = $field['name'];
                                            }
                                        default:
                                            {
                                                $data[] = $field['title'].' : '.$values[$field['name']];
                                                break;
                                            }
                                    }
                                }
                            }
                            $body = implode("<br />", $data);
                            $transport = null;
                            if (! empty($form['FORMBUILDER_SMTP_HOST'])) {
                                $config = array(
                                    'auth' => 'login',
                                    'username' => $form['FORMBUILDER_SMTP_USER'],
                                    'password' => $form['FORMBUILDER_SMTP_PWD'],
                                );

                                $transport = new Zend_Mail_Transport_Smtp($form['FORMBUILDER_SMTP_HOST'], $config);
                            } else {
                                $transport = $transport = new Zend_Mail_Transport_Sendmail('-f'.$form['FORMBUILDER_MAIL_EXP']);
                            }

                            $defMail = Pelican_Cache::fetch("FormBuilder/Mail", array(
                                $_POST['FORMBUILDER_ID'],
                            ), "", "formbuilder");

                            foreach ($defMail as $valueMail) {
                                $mail = new Zend_Mail('UTF-8');
                                $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
                                switch ($valueMail['FORMBUILDER_MAIL_BODY_TYPE']) {
                                    case 'text':
                                        {
                                            $mail->setBodyText(strtr($valueMail['FORMBUILDER_MAIL_BODY_TEXT'], $var), null, Zend_Mime::ENCODING_7BIT);
                                            break;
                                        }
                                    case 'html':
                                        {
                                            $mail->setBodyHtml(str_replace('#MEDIA_HTTP#', Pelican::$config['MEDIA_HTTP'], strtr($valueMail['FORMBUILDER_MAIL_BODY_HTML'], $var)), null, Zend_Mime::ENCODING_7BIT);
                                            break;
                                        }
                                }
                                if ($valueMail['FORMBUILDER_MAIL_EXP'] == '%MAIL%' && ! empty($mailField)) {
                                    $valueMail['FORMBUILDER_MAIL_EXP'] = $values[$mailField];
                                }
                                $mail->setFrom($valueMail['FORMBUILDER_MAIL_EXP'], $valueMail['FORMBUILDER_MAIL_EXP']);
                                if ($valueMail['FORMBUILDER_MAIL_DEST'] == '%MAIL%' && ! empty($mailField)) {
                                    $valueMail['FORMBUILDER_MAIL_DEST'] = $values[$mailField];
                                }
                                if (!empty($valueMail['FORMBUILDER_MAIL_DEST']) && $valueMail['FORMBUILDER_MAIL_DEST'] != '%MAIL%') {
                                    $mail->addTo($valueMail['FORMBUILDER_MAIL_DEST'], $valueMail['FORMBUILDER_MAIL_DEST']);
                                    if (! empty($valueMail['FORMBUILDER_MAIL_CC'])) {
                                        $cc = explode(";", $valueMail['FORMBUILDER_MAIL_CC']);
                                        if (is_array($cc)) {
                                            foreach ($cc as $mailcc) {
                                                $mail->addCc(implode(';', explode("\n", trim($mailcc))));
                                            }
                                        }
                                    }
                                    if (! empty($valueMail['FORMBUILDER_MAIL_CCI'])) {
                                        $cci = explode(";", $valueMail['FORMBUILDER_MAIL_CCI']);
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

                                    $mail->setSubject($valueMail['FORMBUILDER_MAIL_SUBJECT']);
                                    $mail->send();
                                    if ($mail->send($transport)) {
                                        if (! empty($formDef['success'])) {
                                            $this->setResponse(json_encode($formDef['success']));
                                        }
                                    }
                                    unset($mail);
                                } else {
                                    $this->setResponse(json_encode(t("FORMBUILDER_NO_EMAIL_DEFINED")));
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
