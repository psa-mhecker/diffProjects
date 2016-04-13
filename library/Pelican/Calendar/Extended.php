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
	include_once("Calendar.php");
	 
	class Pelican_Calendar_Extended extends Pelican_Calendar {
		/**
		* Constructor for the Pelican_Calendar class
		*
		* @return Pelican_Calendar
		*/
		var $LongDayName = false;
		 
		function Pelican_Calendar_Extended() {
		}
		/**
		*
		*
		* @return un tableau avec les X prochains jours
		*/
		/* emplois de nom de mois complet */
		function SetLongMonthName ($val = false) {
			$this->LongMonthName = $val;
		}
		/* emplois de nom de jour complet */
		function SetLongDayName ($val = false) {
			$this->LongDayName = $val;
		}
		function getNextXdayFromNow($NbrDay = 1) {
			$dateD = date("d-m-y");
			$aDate = explode('-', $dateD);
			$this->XnetDay = parent::getNextXday($aDate[0], $aDate[1], $aDate[2], $NbrDay) ;
			return $this->XnetDay;
		}
		/**
		*
		*
		* @return assigne a XnetDay un tableau de valeur
		*/
		function SetNextXdayFromNow($NbrDay = 1) {
			$dateD = date("d-m-y");
			$aDate = explode('-', $dateD);
			$this->XnetDay = parent::getNextXday($aDate[0], $aDate[1], $aDate[2], $NbrDay) ;
		}
		 
		function SetMonth($m, $y) {
			$s = "";
			 
			$a = parent::adjustDate($m, $y);
			/*if($a[0]<10)
			$month = '0'.$a[0];
			else
			$month = $a[0]; */
			$month = $a[0];
			$year = $a[1];
			 
			$daysInMonth = parent::getDaysInMonth($month, $year);
			$date = getdate(mktime(12, 0, 0, $month, 1, $year));
			$first = $date["wday"];
			$amonthName = parent::getMonthNames();
			$monthName = $amonthName[$month - 1];
			$prev = parent::adjustDate($month - 1, $year);
			$next = parent::adjustDate($month + 1, $year);
			$this->startDay = parent::getstartDay();
			$d = $this->startDay + 1 - $first;
			while ($d > 1) {
				$d -= 7;
			}
			if ($this->LongDayName) {
				$this->dayNames = parent::getDayNamesLong();
			} else {
				$this->dayNames = parent::getDayNames();
			}
			 
			// liste des jours
			$today = getdate(time());
			$s .= $this->win;
			$s .= '<div class="CallRow">';
			$s .= '<div class="CalCellHeader">'.strtolower($this->dayNames[($this->startDay)%7]).'</div>';
			$s .= '<div class="CalCellHeader">'.strtolower($this->dayNames[($this->startDay+1)%7]).'</div>';
			$s .= '<div class="CalCellHeader">'.strtolower($this->dayNames[($this->startDay+2)%7]).'</div>';
			$s .= '<div class="CalCellHeader">'.strtolower($this->dayNames[($this->startDay+3)%7]).'</div>';
			$s .= '<div class="CalCellHeader">'.strtolower($this->dayNames[($this->startDay+4)%7]).'</div>';
			$s .= '<div class="CalCellHeader">'.strtolower($this->dayNames[($this->startDay+5)%7]).'</div>';
			$s .= '<div class="CalCellHeader">'.strtolower($this->dayNames[($this->startDay+6)%7]).'</div>';
			$s .= "</div>";
			 
			// calendrier
			while ($d <= $daysInMonth) {
				 
				$sHead = '';
				$sDay = '';
				 
				for ($i = 0; $i < 7; $i++) {
					$cellDate = mktime(0, 0, 0, $month, $d, $year);
					 
					//entete cellule
					if ($d > 0 && $d <= $daysInMonth) {
						if ($d == $today["mday"] && $month == $today["mon"] && $year == $today["year"]) {
							$sHead .= '<div class="CalCellHeadToday">'.parent::formatDay2($d).'&nbsp;</div>';
						} elseif($cellDate < mktime()) {
							$sHead .= '<div class="CalCellHeadClosed">'.parent::formatDay2($d).'&nbsp;</div>';
						} else {
							$sHead .= '<div class="CalCellHead">'.parent::formatDay2($d).'&nbsp;</div>';
						}
					} else {
						$sHead .= '<div class="CalCellHeadClosed">&nbsp;</div>';
					}
					//cellule du jour
					if ($d == $today["mday"] && $month == $today["mon"] && $year == $today["year"]) {
						$sDay .= '<div class="CalCellToday" onclick="ShowDial(\'day'.parent::formatDay2($d).$month.$year.'\',\'add\', \'\');" id="day'.parent::formatDay2($d).$month.$year.'">&nbsp;</div>';
					} elseif($cellDate < mktime() || $d > $daysInMonth) {
						$sDay .= '<div class="CalCellClosed">&nbsp;</div>';
					} else {
						$sDay .= '<div class="CalCell" onclick="ShowDial(\'day'.parent::formatDay2($d).$month.$year.'\',\'add\', \'\');" id="day'.parent::formatDay2($d).$month.$year.'">&nbsp;</div>';
					}
					$d++;
				}
				$s .= '<div class="CallRow">';
				$s .= $sHead;
				$s .= '</div>';
				$s .= $sDay;
				$s .= '</div>';
			}
			 
			 
			return $s;
			 
			 
		}
		 
		function GetCalendarHTML () {
			if ($this->XnetDay) {
				foreach($this->XnetDay as $Myday) {
					echo('<div id="'.$Myday["formatD"].'" class="CalCell"><div class="CalCellHead">'.$Myday["formatD2"].'</div></div>');
					 
				}
				 
			} else {
				return false;
				 
			}
		}
		 
		function GetCalendarHTMLArray () {
			if ($this->XnetDay) {
				$this->aHTML = array();
				foreach($this->XnetDay as $Myday) {
					$this->div = '<div id="day'.$Myday["formatDC"].'" class="CalCell" onclick="ShowDial(\'day'.$Myday["formatDC"].'\',\'add\');"><div class="CalCellHead">'.$Myday["formatD2"].'</div></div></a>';
					array_push($this->aHTML, $this->div);
				}
				return $this->aHTML;
			} else {
				return false;
				 
			}
		}
		function SetWindow () {
			$this->win = '<div id="winLayer" class="monLayer">&nbsp;</div>';
		}
	}
?>