<?php
	/**
	* PHP Pelican_Calendar Class
	*
	* @package Pelican
	* @subpackage Date
	*/
	 
	/**
	* PHP Pelican_Calendar Class
	*
	* @package Pelican
	* @subpackage Date
	* @author David Wilkinson <davidw@cascade.org.uk>
	* @since 200
	* @version 1.4
	* @link http://www.cascade.org.uk/software/php/calendar/
	*/
	class Pelican_Calendar {
		/**
		* Constructor for the Pelican_Calendar class
		*
		* @return Pelican_Calendar
		*/
		function Pelican_Calendar() {
		}
		 
		/**
		* Get the array of strings used to label the days of the week.
		*
		* This array contains seven elements, one for each day of the week. The first entry in this array represents Sunday.
		* @return unknown
		*/
		function getDayNames() {
			return $this->dayNames;
		}
		 
		/**
		* DESC
		*
		* @return unknown
		*/
		function getDayNamesLong() {
			return $this->dayNamesLong;
		}
		/**
		* Set the array of strings used to label the days of the week.
		*
		* This array must contain seven elements, one for each day of the week. The first entry in this array represents Sunday.
		* @return void
		* @param unknown $names
		* @desc Entrez la description ici...
		*/
		function setDayNames($names) {
			$this->dayNames = $names;
		}
		 
		/**
		* DESC
		*
		* @return void
		* @param unknown $names
		*/
		function setDayNamesLong($names) {
			$this->dayNamesLong = $names;
		}
		/**
		* Get the array of strings used to label the months of the year.
		*
		* This array contains twelve elements, one for each month of the year. The first entry in this array represents January.
		* @return unknown
		*/
		function getMonthNames() {
			return $this->monthNames;
		}
		 
		/**
		* Set the array of strings used to label the months of the year.
		*
		* This array must contain twelve elements, one for each month of the year. The first entry in this array represents January.
		* @return void
		* @param unknown $names
		*/
		function setMonthNames($names) {
			$this->monthNames = $names;
		}
		 
		/**
		* Gets the start day of the week.
		*
		* This is the day that appears in the first column of the calendar. Sunday = 0.
		* @return unknown
		*/
		function getStartDay() {
			return $this->startDay;
		}
		 
		/*
		/**
		* Sets the start day of the week.
		*
		* This is the day that appears in the first column of the calendar. Sunday = 0.
		* @return void
		* @param unknown $day
		*/
		function setStartDay($day) {
			$this->startDay = $day;
		}
		 
		/**
		* Gets the start month of the year.
		*
		* This is the month that appears first in the year view. January = 1.
		* @return unknown
		*/
		function getStartMonth() {
			return $this->startMonth;
		}
		 
		/**
		* Sets the start month of the year.
		*
		* This is the month that appears first in the year view. January = 1.
		* @return void
		* @param unknown $month
		*/
		function setStartMonth($month) {
			$this->startMonth = $month;
		}
		 
		/**
		* Return the URL to link to in order to display a Pelican_Calendar for a given month/year.
		*
		* You must override this method if you want to activate the "forward" and "back"
		* feature of the calendar.
		*
		* Note: If you return an empty string from this function, no navigation link will
		* be displayed. This is the default behaviour.
		*
		* If the Pelican_Calendar is being displayed in "year" view, $month will be set to zero.
		* @return string $link
		* @param int $month
		* @param int $year
		*
		*/
		/*function getCalendarLink($month, $year) {
		return "";
		}*/
		function getCalendarLink($month, $year) {
			 
			if (strpos($this->link, '?') != false)
			$link = $this->link.'&month='.$month.'&year='.$year;
			else
				$link = $this->link.'?month='.$month.'&year='.$year;
			 
			return $link;
		}
		 
		/**
		* Set the link used to navigate by the months and th directory to the images.
		*
		* @param string $link
		* @param string $imgDirectory
		*/
		function setCalendarLink($link, $imgDirectory) {
			$this->link = $link;
			$this->imgDirectory = $imgDirectory;
		}
		 
		/**
		* Return the URL to link to  for a given date.
		*
		* You must override this method if you want to activate the date linking feature of the calendar.
		*
		* Note: If you return an empty string from this function, no navigation link will
		* be displayed. This is the default behaviour.
		* @return unknown
		* @param unknown $day
		* @param unknown $month
		* @param unknown $year
		*/
		function getDateLink($day, $month, $year) {
			return "";
		}
		 
		 
		/**
		* Return the Pelican_Html for the current month
		*
		* @return unknown
		*/
		function getCurrentMonthView() {
			$d = getdate(time());
			return $this->getMonthView($d["mon"], $d["year"]);
		}
		 
		 
		/**
		* Return the Pelican_Html for the current year
		*
		* @return unknown
		*/
		function getCurrentYearView() {
			$d = getdate(time());
			return $this->getYearView($d["year"]);
		}
		 
		 
		/**
		* Return the Pelican_Html for a specified month
		*
		* @return unknown
		* @param unknown $month
		* @param unknown $year
		*/
		function getMonthView($month, $year) {
			return $this->getMonthHTML($month, $year);
		}
		 
		 
		/**
		* Return the Pelican_Html for a specified year
		*
		* @return unknown
		* @param unknown $year
		*/
		function getYearView($year) {
			return $this->getYearHTML($year);
		}
		 
		/**
		* Calculate the number of days in a month, taking into account leap years.
		*
		* @return unknown
		* @param unknown $month
		* @param unknown $year
		* @private
		*/
		function getDaysInMonth($month, $year) {
			if ($month < 1 || $month > 12) {
				return 0;
			}
			 
			$d = $this->daysInMonth[$month - 1];
			 
			if ($month == 2) {
				// Check for leap year
				// Forget the 4000 rule, I doubt I'll be around then...
				 
				if ($year%4 == 0) {
					if ($year%100 == 0) {
						if ($year%400 == 0) {
							$d = 29;
						}
					} else {
						$d = 29;
					}
				}
			}
			 
			return $d;
		}
		 
		/**
		* DESC
		*
		* @return unknown
		* @param unknown $d
		* @param unknown $m
		* @param unknown $y
		* @param unknown $nbrday
		* @private
		*/
		function getNextXday($d, $m, $y, $nbrday = 1) {
			$s = "";
			$d = $this->formatDay($d);
			$a = $this->adjustDate($m, $y);
			$month = $a[0];
			$year = $a[1];
			 
			//$daysInMonth = $this->getDaysInMonth($month, $year);
			$listday = array();
			for($k = 0; $k < $nbrday; $k++) {
				$date = getdate(mktime(12, 0, 0, $month, $d+$k, $year));
				$mois = $this->formatMonth($date["mon"]);
				$monthName = $this->monthNames[$date["mon"] - 1];
				$nomsJ = $this->getDayNames();
				$nomsJL = $this->getDayNamesLong();
				$nomJour = $nomsJ[$date["wday"]];
				$nomJourL = $nomsJL[$date["wday"]];
				$annee = $date["year"];
				$annee = substr($annee, 2, 2);
				$dayFormat2 = $this->formatDay2($date["mday"]);
				$formatD = $dayFormat2."-".$mois."-".$annee;
				$formatDC = $dayFormat2.$mois.$annee;
				$formatD2 = $nomJourL." ".$dayFormat2;
				$listday[] = array("formatD" => $formatD, "formatD2" => $formatD2, "jour" => $date["mday"], "jour2" => $dayFormat2, "mois" => $mois, "jm" => $dayFormat2."/".$mois, "nmois" => $monthName, "nomJ" => $nomJour, "nomJL" => $nomJourL, "annee" => $annee, "annee4" => $date["year"]);
			}
			return $listday;
		}
		 
		/**
		* DESC
		*
		* @return unknown
		* @param unknown $mois
		* @private
		*/
		function formatMonth($mois) {
			if ($mois < 10) {
				return "0".$mois;
			} else {
				return $mois;
			}
		}
		 
		/**
		* Pour enlever les 0
		*
		* @return unknown
		* @param unknown $day
		* @private
		*/
		function formatDay($day) {
			if ($day < 10) {
				return str_replace("0", '', $day);
			} else {
				return $day;
			}
		}
		 
		/**
		* Pour rajouter les 0
		*
		* @return unknown
		* @param unknown $day
		* @private
		*/
		function formatDay2($day) {
			if ($day < 10) {
				return "0".$day;
			} else {
				return $day;
			}
		}
		 
		/**
		* DESC
		*
		* @return unknown
		* @param unknown $d
		* @param unknown $m
		* @param unknown $y
		* @private
		*/
		function getFrenchDate($d, $m, $y, $full = false) {
			$d = $this->formatDay($d);
			$a = $this->adjustDate($m, $y);
			$month = $a[0];
			$year = $a[1];
			$date = getdate(mktime(12, 0, 0, $month, $d+$k, $y));
			if ($full) {
				$monthName = $this->monthNamesLong[$date["mon"] - 1];
			} else {
				$monthName = $this->monthNames[$date["mon"] - 1];
			}
			$nomsJL = $this->getDayNamesLong();
			$nomJourL = $nomsJL[$date["wday"]];
			$annee = $date["year"];
			$datefr = $nomJourL." ".$d." ".$monthName." ".$annee;
			return $datefr;
		}
		 
		/**
		* DESC
		*
		* @return unknown
		* @param unknown $d
		* @param unknown $m
		* @param unknown $y
		* @private
		*/
		function getFrenchMonth($m , $full = false) {
			if ($full) {
				$monthName = $this->monthNamesLong[$m - 1];
			} else {
				$monthName = $this->monthNames[$m - 1];
			}
			return $monthName;
		}
		 
		/**
		* Generate the Pelican_Html for a given month
		*
		* @return unknown
		* @param unknown $m
		* @param unknown $y
		* @param unknown $showYear
		* @private
		*/
		function getMonthHTML($m, $y, $showYear = 1) {
			$s = "";
			 
			$a = $this->adjustDate($m, $y);
			$month = $a[0];
			$year = $a[1];
			 
			$daysInMonth = $this->getDaysInMonth($month, $year);
			$date = getdate(mktime(12, 0, 0, $month, 1, $year));
			 
			$first = $date["wday"];
			$monthName = $this->monthNames[$month - 1];
			 
			$prev = $this->adjustDate($month - 1, $year);
			$next = $this->adjustDate($month + 1, $year);
			if ($prev[0] < 10) $prev[0] = '0'.$prev[0];
			if ($next[0] < 10) $next[0] = '0'.$next[0];
			 
			if ($showYear == 1) {
				$prevMonth = $this->getCalendarLink($prev[0], $prev[1]);
				$nextMonth = $this->getCalendarLink($next[0], $next[1]);
			} else {
				$prevMonth = "";
				$nextMonth = "";
			}
			 
			$header = $monthName . (($showYear > 0) ? "&nbsp;" . $year : "");
			 
			$s .= "<table class=\"calendar\">\n";
			$s .= "<tr>\n";
			$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">" . (($prevMonth == "") ? "&nbsp;" : "<a href=\"$prevMonth\"><img src=\"".$this->imgDirectory."/previous.gif\" border=\"0\"></a>") . "</td>\n";
			$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\" colspan=\"5\">$header</td>\n";
			$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">" . (($nextMonth == "") ? "&nbsp;" : "<a href=\"$nextMonth\"><img src=\"".$this->imgDirectory."/next.gif\" border=\"0\"></a>") . "</td>\n";
			$s .= "</tr>\n";
			 
			$s .= "<tr>\n";
			$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">".$this->dayNames[($this->startDay)%7]."</td>\n";
			$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">".$this->dayNames[($this->startDay+1)%7]."</td>\n";
			$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">".$this->dayNames[($this->startDay+2)%7]."</td>\n";
			$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">".$this->dayNames[($this->startDay+3)%7]."</td>\n";
			$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">".$this->dayNames[($this->startDay+4)%7]."</td>\n";
			$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">".$this->dayNames[($this->startDay+5)%7]."</td>\n";
			$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">".$this->dayNames[($this->startDay+6)%7]."</td>\n";
			$s .= "</tr>\n";
			 
			// We need to work out what date to start at so that the first appears in the correct column
			$d = $this->startDay + 1 - $first;
			while ($d > 1) {
				$d -= 7;
			}
			 
			// Make sure we know when today is, so that we can use a different CSS style
			$today = getdate(time());
			 
			while ($d <= $daysInMonth) {
				$s .= "<tr>\n";
				 
				for ($i = 0; $i < 7; $i++) {
					if ($year == $today["year"] && $month == $today["mon"] && $d == $today["mday"]) {
						$class = "calendarToday";
					} elseif($i > 4) {
						$class = "calendarWE";
					} else {
						$class = "calendar";
					}
					$s .= "<td class=\"$class\" align=\"right\" valign=\"top\">";
					if ($d > 0 && $d <= $daysInMonth) {
						$link = $this->getDateLink($d, $month, $year);
						$s .= (($link == "") ? $d : "<a href=\"$link\">$d</a>");
					} else {
						$s .= "&nbsp;";
					}
					$s .= "</td>\n";
					$d++;
				}
				$s .= "</tr>\n";
			}
			 
			$s .= "</table>\n";
			 
			return $s;
		}
		 
		 
		/**
		* Generate the Pelican_Html for a given year
		*
		* @return unknown
		* @param unknown $year
		* @private
		*/
		function getYearHTML($year) {
			$s = "";
			$prev = $this->getCalendarLink(0, $year - 1);
			$next = $this->getCalendarLink(0, $year + 1);
			 
			$s .= "<table class=\"calendar\" border=\"0\">\n";
			$s .= "<tr>";
			$s .= "<td align=\"center\" valign=\"top\" align=\"left\">".(($prev == "") ? "&nbsp;" : "<a href=\"$prev\">&lt;&lt;</a>")."</td>\n";
			$s .= "<td class=\"calendarHeader\" valign=\"top\" align=\"center\">".(($this->startMonth > 1) ? $year." - ".($year + 1) : $year) ."</td>\n";
			$s .= "<td align=\"center\" valign=\"top\" align=\"right\">".(($next == "") ? "&nbsp;" : "<a href=\"$next\">&gt;&gt;</a>")."</td>\n";
			$s .= "</tr>\n";
			$s .= "<tr>";
			$s .= "<td class=\"calendar\" valign=\"top\">".$this->getMonthHTML(0 + $this->startMonth, $year, 0) ."</td>\n";
			$s .= "<td class=\"calendar\" valign=\"top\">".$this->getMonthHTML(1 + $this->startMonth, $year, 0) ."</td>\n";
			$s .= "<td class=\"calendar\" valign=\"top\">".$this->getMonthHTML(2 + $this->startMonth, $year, 0) ."</td>\n";
			$s .= "</tr>\n";
			$s .= "<tr>\n";
			$s .= "<td class=\"calendar\" valign=\"top\">".$this->getMonthHTML(3 + $this->startMonth, $year, 0) ."</td>\n";
			$s .= "<td class=\"calendar\" valign=\"top\">".$this->getMonthHTML(4 + $this->startMonth, $year, 0) ."</td>\n";
			$s .= "<td class=\"calendar\" valign=\"top\">".$this->getMonthHTML(5 + $this->startMonth, $year, 0) ."</td>\n";
			$s .= "</tr>\n";
			$s .= "<tr>\n";
			$s .= "<td class=\"calendar\" valign=\"top\">".$this->getMonthHTML(6 + $this->startMonth, $year, 0) ."</td>\n";
			$s .= "<td class=\"calendar\" valign=\"top\">".$this->getMonthHTML(7 + $this->startMonth, $year, 0) ."</td>\n";
			$s .= "<td class=\"calendar\" valign=\"top\">".$this->getMonthHTML(8 + $this->startMonth, $year, 0) ."</td>\n";
			$s .= "</tr>\n";
			$s .= "<tr>\n";
			$s .= "<td class=\"calendar\" valign=\"top\">".$this->getMonthHTML(9 + $this->startMonth, $year, 0) ."</td>\n";
			$s .= "<td class=\"calendar\" valign=\"top\">".$this->getMonthHTML(10 + $this->startMonth, $year, 0) ."</td>\n";
			$s .= "<td class=\"calendar\" valign=\"top\">".$this->getMonthHTML(11 + $this->startMonth, $year, 0) ."</td>\n";
			$s .= "</tr>\n";
			$s .= "</table>\n";
			 
			return $s;
		}
		 
		/**
		* Adjust dates to allow months > 12 and < 0.
		*
		* Just adjust the years appropriately.
		* e.g. Month 14 of the year 2001 is actually month 2 of year 2002.
		* @return unknown
		* @param unknown $month
		* @param unknown $year
		* @private
		*/
		function adjustDate($month, $year) {
			$a = array();
			$a[0] = $month;
			$a[1] = $year;
			 
			while ($a[0] > 12) {
				$a[0] -= 12;
				$a[1]++;
			}
			 
			while ($a[0] <= 0) {
				$a[0] += 12;
				$a[1]--;
			}
			 
			return $a;
		}
		 
		/*
		* The start day of the week.
		* This is the day that appears in the first column of the calendar. Sunday = 0.
		*/
		var $startDay = 0;
		 
		/*
		* The start month of the year.
		* This is the month that appears in the first slot of the Pelican_Calendar in the year view. January = 1.
		*/
		var $startMonth = 1;
		 
		/*
		* The labels to display for the days of the week.
		* The first entry in this array represents Sunday.
		*/
		var $dayNames = array("D", "L", "M", "M", "J", "V", "S");
		 
		var $dayNamesLong = array("DIMANCHE", "LUNDI", "MARDI", "MERCREDI", "JEUDI", "VENDREDI", "SAMEDI");
		 
		/*
		* The labels to display for the months of the year.
		* The first entry in this array represents January.
		*/
		var $monthNames = array("Jan", "Fév", "Mar", "Avr", "Mai", "Juin",
			"Juil", "Aout", "Sept", "Oct", "Nov", "Dec");
		var $monthNamesLong = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
			"Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre");
		/*
		* The number of days in each month.
		* You're unlikely to want to change this...
		* The first entry in this array represents January.
		*/
		var $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	}
?>