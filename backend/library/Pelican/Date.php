<?php
/**
 * PHP Date Class.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */

/**
 /**
 * PHP Date.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 *
 * @version 1
 */
class Pelican_Date
{
    public $date;

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function __construct()
    {
        $this->date = $this->getToday();
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $date   __DESC__
     * @param __TYPE__ $format (option) __DESC__
     *
     * @return __TYPE__
     */
    public function setDate($date, $format = 'd/m/Y')
    {
        switch ($format) {
            case 'd/m/Y': {
                        $temp = explode("/", $date);
                        $day = $temp[0];
                        $month = $temp[1];
                        $year = $temp[2];
                    break;
                }
            case 'Y-m-d': {
                    $temp = explode("-", $date);
                    $day = $temp[2];
                    $month = $temp[1];
                    $year = $temp[0];
                    break;
                }
            }
        if ($day || $month || $year) {
            $this->date = mktime(0, 0, 0, $month, $day, $year);
        }
    }

        /**
         * __DESC__.
         *
         * Caractères pour le paramètre format  Description Exemple de valeurs retournées
         * d Jour du mois, sur deux chiffres (avec un zéro initial) 01 à 31
         * D Jour de la semaine, en trois lettres (et en anglais) Mon à Sun
         * j Jour du mois sans les zéros initiaux 1 à 31
         * l ('L' minuscule) Jour de la semaine, textuel, version longue, en anglais Sunday à Saturday
         * N Représentation numérique ISO-8601 du jour de la semaine (ajouté en PHP
         * 5.1.0) 1 (pour Lundi) à 7 (pour Dimanche)
         * S Suffixe ordinal d'un nombre pour le jour du mois, en anglais, sur deux
         * lettres st, nd, rd ou th. Fonctionne bien avec j
         * w Jour de la semaine au format numérique 0 (pour dimanche) à 6 (pour samedi)
         * z Jour de l'année 0 à 366
         * Semaine --- ---
         * W Numéro de semaine dans l'année ISO-8601, les semaines commencent le lundi (ajouté en PHP 4.1.0) Exemple : 42 (la 42ème semaine de l'année)
         * Mois --- ---
         * F Mois, textuel, version longue; en anglais, comme January ou December January à December
         * m Mois au format numérique, avec zéros initiaux 01 à 12
         * M Mois, en trois lettres, en anglais Jan à Dec
         * n Mois sans les zéros initiaux 1 à 12
         * t Nombre de jours dans le mois 28 à 31
         * Année --- ---
         * L Est ce que l'année est bissextile 1 si bissextile, 0 sinon.
         * o L'année ISO-8601. C'est la même valeur que Y, excepté que si le numéro de la semaine ISO (W) appartient à l'année précédente ou suivante, cette année sera utilisé à la place. (ajouté en PHP 5.1.0) Exemples : 1999 ou 2003
         * Y Année sur 4 chiffres Exemples : 1999 ou 2003
         * y Année sur 2 chiffres Exemples : 99 ou 03
         * Heure --- ---
         * a Ante meridiem et Post meridiem en minuscules am ou pm
         * A Ante meridiem et Post meridiem en majuscules AM ou PM
         * B Heure Internet Swatch 000 à 999
         * g Heure, au format 12h, sans les zéros initiaux 1 à 12
         * G Heure, au format 24h, sans les zéros initiaux 0 à 23
         * h Heure, au format 12h, avec les zéros initiaux 01 à 12
         * H Heure, au format 24h, avec les zéros initiaux 00 à 23
         * i Minutes avec les zéros initiaux 00 à 59
         * s Secondes, avec zéros initiaux 00 à 59
         * u Millisecondes (ajouté en PHP 5.2.2) Exemple : 54321
         * Fuseau horaire --- ---
         * e L'identifiant du fuseau horaire (ajouté en PHP 5.1.0) Exemples : UTC, GMT,
         * Atlantic/Azores
         * I (i majuscule) L'heure d'été est activée ou pas 1 si oui, 0 sinon.
         * O Différence d'heures avec l'heure de Greenwich (GMT), exprimée en heures
         * Exemple : +0200
         * P Différence avec l'heure Greenwich (GMT) avec un deux-points entre les heures
         * et les minutes (ajouté dans PHP 5.1.3) Exemple : +02:00
         * T Abréviation du fuseau horaire Exemples : EST, MDT ...
         * Z Décalage horaire en secondes. Le décalage des zones à l'ouest de la
         * Pelican_Index_Frontoffice_Zone UTC est négative, et à l'est, il est positif.
         * -43200 à 50400
         * Date et Heure complète --- ---
         * c Date au format ISO 8601 (ajouté en PHP 5) 2004-02-12T15:19:21+00:00
         * r Format de date » RFC 2822 Exemple : Thu, 21 Dec 2000 16:01:07 +0200
         * U Secondes depuis l'époque Unix (1er Janvier 1970, 0h00 00s GMT) Voir aussi
         * time()
         * Pelican_Db_Mysql "Y-m-d H:i:s"
         * français "d/m/Y H:i:s"
         *
         * @access public
         *
         * @param string $date Au format mktime
         * @param __TYPE__ $format __DESC__
         *
         * @return __TYPE__
         */
        public function getDate($date, $format)
        {
            if ($format) {
                $return = date($format, $date);
            } else {
                $return = $date;
            }

            return $return;
        }

        /**
         * __DESC__.
         *
         * @access public
         *
         * @param string $add (option) __DESC__
         *
         * @return __TYPE__
         */
        public function getDayAdd($add = 0)
        {
            $date = mktime(0, 0, 0, date("m", (!$this->date ? mktime() : $this->date)), date("d", (!$this->date ? mktime() : $this->date)) + ((int) $add), date("Y", (!$this->date ? mktime() : $this->date)));

            return $date;
        }

        /**
         * __DESC__.
         *
         * @access public
         *
         * @param string $add (option) __DESC__
         *
         * @return __TYPE__
         */
        public function getMonthAdd($add = 0)
        {
            $date = mktime(0, 0, 0, date("m", (!$this->date ? mktime() : $this->date)) + ((int) $add), date("d", (!$this->date ? mktime() : $this->date)), date("Y", (!$this->date ? mktime() : $this->date)));

            return $date;
        }

        /**
         * __DESC__.
         *
         * @access public
         *
         * @param string $add (option) __DESC__
         *
         * @return __TYPE__
         */
        public function getYearAdd($add = 0)
        {
            $date = mktime(0, 0, 0, date("m", (!$this->date ? mktime() : $this->date)), date("d", (!$this->date ? mktime() : $this->date)), date("Y", (!$this->date ? mktime() : $this->date)) + ((int) $add));

            /*
             * @access public
             * @var __TYPE__ __DESC__
             */
            return $date;
        }

        /**
         * __DESC__.
         *
         * @access public
         *
         * @param string $format (option) __DESC__
         *
         * @return __TYPE__
         */
        public function getTomorrow($format = "")
        {
            $date = $this->getDayAdd(1);

            return $this->getDate($date, $format);
        }

        /**
         * __DESC__.
         *
         * @access public
         *
         * @param string $format (option) __DESC__
         *
         * @return __TYPE__
         */
        public function getToday($format = "")
        {
            $date = $this->getDayAdd(0);

            return $this->getDate($date, $format);
        }

        /**
         * __DESC__.
         *
         * @access public
         *
         * @param string $format (option) __DESC__
         *
         * @return __TYPE__
         */
        public function getYesterday($format = "")
        {
            $date = $this->getDayAdd(-1);

            return $this->getDate($date, $format);
        }

        /**
         * __DESC__.
         *
         * @access public
         *
         * @param string $format (option) __DESC__
         *
         * @return __TYPE__
         */
        public function getPreviousMonth($format = "")
        {
            $date = $this->getMonthAdd(-1);

            return $this->getDate($date, $format);
        }

        /**
         * __DESC__.
         *
         * @access public
         *
         * @param string $format (option) __DESC__
         *
         * @return __TYPE__
         */
        public function getNextMonth($format = "")
        {
            $date = $this->getMonthAdd(+1);

            return $this->getDate($date, $format);
        }

        /**
         * __DESC__.
         *
         * @access public
         *
         * @param string $format (option) __DESC__
         *
         * @return __TYPE__
         */
        public function getNextYear($format = "")
        {
            $date = $this->getYearAdd(+1);

            return $this->getDate($date, $format);
        }

        /**
         * __DESC__.
         *
         * @access public
         *
         * @param string $format (option) __DESC__
         *
         * @return __TYPE__
         */
        public function getPreviousYear($format = "")
        {
            $date = $this->getYearAdd(+1);

            return $this->getDate($date, $format);
        }

        /**
         * __DESC__.
         *
         * @access public
         *
         * @param __TYPE__ $day __DESC__
         *
         * @return __TYPE__
         */
        public function getDayName($day)
        {
            $dayNamesLong = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");

            return $dayNamesLong[$day];
        }

        /**
         * __DESC__.
         *
         * @access public
         *
         * @param __TYPE__ $month __DESC__
         * @param bool $short (option) __DESC__
         *
         * @return __TYPE__
         */
        public function getMonthName($month, $short = false)
        {
            $monthNamesLong = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
            $return = $monthNamesLong[$month];
            if ($short) {
                $return = substr($return, 0, 3);
            }

            return $return;
        }

        /**
         * __DESC__.
         *
         * @access public
         *
         * @param string $diff (option) __DESC__
         *
         * @return __TYPE__
         */
        public function getDiffName($diff = "")
        {
            $diffNames[-2] = "Avant-hier";
            $diffNames[-1] = "Hier";
            $diffNames[0] = "Aujourd'hui";
            $diffNames[1] = "Demain";
            $diffNames[2] = "Après-demain";
            $return = $diffNames[$diff];
            if ($short) {
                $return = substr($return, 0, 3);
            }

            return $return;
        }

        /**
         * __DESC__.
         *
         * @access public
         *
         * @param __TYPE__ $date __DESC__
         * @param bool $short (option) __DESC__
         *
         * @return __TYPE__
         */
        public function getLabelDay($date, $short = false)
        {
            $weekday = $this->getDate($date, "w");
            $day = $this->getDate($date, "d");
            $month = $this->getDate($date, "m");
            $year = $this->getDate($date, "Y");
            $dayName = $this->getDayName($weekday);
            $monthName = $this->getMonthName($month, $short);

            return $dayName." ".$day." ".$monthName;
        }

        /**
         * __DESC__.
         *
         * @access public
         *
         * @param __TYPE__ $date __DESC__
         * @param string $diff (option) __DESC__
         *
         * @return __TYPE__
         */
        public function getLabel($date, $diff = "")
        {
            $weekday = $this->getDate($date, "w");
            $dayName = $this->getDayName($weekday);
            if ($this->getDiffName($diff)) {
                $dayName = $this->getDiffName($diff);
            }

            return $dayName;
        }

        /**
         * __DESC__.
         *
         * @access public
         *
         * @return __TYPE__
         */
        public function diffDayFromNow()
        {
            $diff = $this->date - mktime(0, 0, 0, date("m"), date("d"), date("Y"));

            return (($diff / 86400));
        }

        /**
         * __DESC__.
         *
         * @access public
         *
         * @param __TYPE__ $date __DESC__
         *
         * @return __TYPE__
         */
        public function translateDate($date)
        {
            $eng_words = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
            $french_words = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
            $date_str = str_replace($eng_words, $french_words, $date);

            return $date_str;
        }
}
