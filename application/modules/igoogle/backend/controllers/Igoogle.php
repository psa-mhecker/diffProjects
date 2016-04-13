<?php

    /**
    *
    * Controller Backend du gadget Igoogle
    * @author rcarles
    *
    */
    class Igoogle_Controller extends Pelican_Controller_Back
    {
        /**
        *
        * Tableau des fichiers de traduction associ�s au gadget
        * @var array
        */
        public static $translate;

        /**
        *
        * R�cup�ration des param�tres du gadget : thumbnail, meta-donn�es, pr�f�rences, langue
        * @param string $url
        * @param string $prefix
        * @param string $id
        */
        function getAction ($url, $prefix, $id)
        {
            /** appel du service REST */
            $parse = parse_url($url);
            $host = $parse['scheme'] . '://' . $parse['host'];

            $xml = Pelican_Cache::fetch("Igoogle", array(
            $url ), "", "igoogle");

            if (! substr_count($xml, 'Error 404')) {

                $oXml = simplexml_load_string($xml);

                /** traitement du XML */
                if ($oXml) {
                    $modulePrefs = $oXml->ModulePrefs;

                    /** langues */
                    if (is_array($modulePrefs->Locale) || is_object($modulePrefs->Locale)) {
                        foreach ($modulePrefs->Locale as $val) {
                            $lang = (string) $val['lang'];
                            $aLang[$lang] = (string) $val['messages'];
                            $aLang[$lang] = (! substr_count($aLang[$lang], 'http') ? $host . '/' : '') . $aLang[$lang];
                            if (substr_count($aLang[$lang], 'http://www.google.com') && ! substr_count($aLang[$lang], '/ig/modules')) {
                                $aLang[$lang] = str_replace('http://www.google.com', 'http://www.google.com/ig/modules', $aLang[$lang]);
                            }
                        }
                    }

                    self::$translate = Pelican_Cache::fetch("IgoogleLang", array(
                    ($aLang['fr'] ? $aLang['fr'] : $aLang['en'])
                    ), "", "igoogle");
                    self::$translate['__MSG_locale__'] = ($aLang['fr'] ? 'fr_ALL' : 'en_ALL');

                    /** Pelican_Media_Thumbnail */
                    $detail['title'] = Pelican_Html::h1((string) $modulePrefs['title']);
                    $detail['description'] = (string) $modulePrefs['description'];
                    $detail['author'] = (string) $modulePrefs['author'];
                    $detail['height'] = (string) $modulePrefs['height'];
                    $detail['scrolling'] = (string) $modulePrefs['scrolling'];
                    $screenshot = (string) $modulePrefs['screenshot'];
                    $thumbnail = (string) $modulePrefs['thumbnail'];

                    $image = ($screenshot ? $screenshot : $thumbnail);
                    if ($image) {
                        $return = Pelican_Html::fieldset(array(
                        style => 'float:left;margin-right:50px;' ), Pelican_Html::img(array(
                        src => (! substr_count($image, 'http') ? $host : '') . $image )));
                    }

                    $return .= Pelican_Html::fieldset(array(), Pelican_Html::legend('Informations') . implode('<br />', $detail));

                    /** user prefs */
                    $userPrefs = $oXml->UserPref;
                    if ($userPrefs) {
                        $i = 0;
                        foreach ($userPrefs as $pref) {
                            $aPref[$i]['name'] = (string) $pref['name'];
                            $aPref[$i]['datatype'] = (string) $pref['datatype'];
                            $aPref[$i]['default_value'] = (string) $pref['default_value'];
                            $aPref[$i]['display_name'] = (string) $pref['display_name'];
                            if ($pref->EnumValue) {
                                $defined = $_SESSION[APP]['plugin']['Igoogle'][$id]['up_' . $aPref[$i]['name']];
                                foreach ($pref->EnumValue as $enum) {
                                    $value = (string) $enum['value'];
                                    $display_value = (string) $enum['display_value'];
                                    $aPref[$i]['options'][] = Pelican_Html::option(array(
                                    value => $value ,
                                        selected => ($defined == $value ? 'selected' : '')
                                    ), ($display_value ? $display_value :
                                    $value));
                                }
                            }
                            $param[] = self::getInput($aPref[$i], $prefix, $id);

                            $i ++;
                        }
                    }
                    if ($param) {
                        $return .= Pelican_Html::fieldset(array(), Pelican_Html::legend('Paramètres') . implode('<br />', $param));
                    }
                }

                if ($return) {
                    $return = Pelican_Html::td(array(), $return);
                }
            } else {
                $return = '404';
            }

            /** assignation de la r�ponse Ajax */
            self::getRequest()->addResponseCommand('assign', array(
            'id' => $id . 'Igoogle' ,
                'attr' => 'innerHTML' ,
                'value' => self::getTranslation($return)
            ));

            unset($_SESSION[APP]['plugin']['Igoogle'][$id]);

        }

        /**
        *
        * Traduit l'ensemble de la r�ponse Ajax en fonction de la langue choisie
        * @param string $text
        */
        public static function getTranslation ($text)
        {
            if (self::$translate) {
                $return = strtr($text, self::$translate);
            } else {
                $return = $text;
            }

            return $return;
        }

        /**
        *
        * Cr�ation de l'input de saisie associ� aux pr�f�rences du gadget iGoogle
        * @param mixed $values
        * @param string $prefix
        * @param string $id
        */
        public static function getInput ($values, $prefix, $id)
        {
            $return = "";

            $defined = $_SESSION[APP]['plugin']['Igoogle'][$id]['up_' . $values['name']];

            switch ($values['datatype']) {
                case 'bool':
                {
                    $return = $values['display_name'] . Pelican_Html::nbsp() . Pelican_Html::input(array(
                    type => 'checkbox' ,
                        id => $prefix . $values['name'] ,
                        name => $prefix . $values['name'] ,
                        checked => ($values['default_value'] == 'true' || $defined ? 'checked' : '')
                    ));
                    break;
                }
                case 'enum':
                {
                    if ($values['options']) {
                        $options = implode('<br />', $values['options']);
                    }
                    $return = $values['display_name'] . Pelican_Html::nbsp() . Pelican_Html::select(array(
                    id => $prefix . $values['name'] ,
                        name => $prefix . $values['name'] ), $options);
                    break;
                }
            }

            return $return;
        }
    }
