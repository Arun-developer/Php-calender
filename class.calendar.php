<?php
class PHPCalendar {
	private $weekDayName = array ("MON","TUE","WED","THU","FRI","SAT","SUN");
	private $currentDay = 0;
	private $currentMonth = 0;
	private $currentYear = 0;
	private $currentMonthStart = null;
	private $currentMonthDaysLength = null;	
	
	function __construct() {
		$this->currentYear = date ( "Y", time () );
		$this->currentMonth = date ( "m", time () );
		
		if (! empty ( $_POST ['year'] )) {
			$this->currentYear = $_POST ['year'];
		}
		if (! empty ( $_POST ['month'] )) {
			$this->currentMonth = $_POST ['month'];
		}
		$this->currentMonthStart = $this->currentYear . '-' . $this->currentMonth . '-01';
		
		$year = (int)$this->currentYear;
		$Month = (int)$this->currentMonth;
		$this->currentMonthDaysLength = cal_days_in_month(CAL_GREGORIAN,$Month,$year);
	
	}
	
	function getCalendarHTML() {
		$calendarHTML = '<div id="calendar-outer">'; 
		$calendarHTML .= '<div class="calendar-nav">' . $this->getCalendarNavigation() . '</div>'; 
		$calendarHTML .= '<ul class="week-name-title week_cls" >' . $this->getWeekDayName () . '</ul>';
		$calendarHTML .= '<ul class="week-day-cell">' . $this->getWeekDays () . '</ul>';		
		$calendarHTML .= '</div>';
		#echo'<pre>';print_r($calendarHTML);die;
		return $calendarHTML;
	}
	
	function getCalendarNavigation() {
		$year = (int)$this->currentYear;
		$Month = (int)$this->currentMonth - 1;
		$total_month =  array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec');
		$prevMonthYear = date ( 'm,Y', strtotime ( $this->currentMonthStart. ' -1 Month'  ) );
		$prevMonthYearArray = explode(",",$prevMonthYear);
		
		$nextMonthYear = date ( 'm,Y', strtotime ( $this->currentMonthStart . ' +1 Month'  ) );
		$nextMonthYearArray = explode(",",$nextMonthYear);
		
		$navigationHTML = '<div class="prev" data-prev-month="' . $prevMonthYearArray[0] . '" data-prev-year = "' . $prevMonthYearArray[1]. '"></div>'; 
		$navigationHTML .= '<span id="currentMonth">' . $total_month[$Month] . '</span>  - ';
		$navigationHTML .= '<span contenteditable="true" id="currentYear" style="margin-left:5px">'.	$year . '</span>';
		$navigationHTML .= '<div class="next" data-next-month="' . $nextMonthYearArray[0] . '" data-next-year = "' . $nextMonthYearArray[1]. '"></div>';
		return $navigationHTML;
	}
	
	function getWeekDayName() {
		$WeekDayName= '';		
		foreach ( $this->weekDayName as $dayname ) {			
			$WeekDayName.= '<li>' . $dayname . '</li>';
		}		
		return $WeekDayName;
	}
	
	function getWeekDays() { 
		$firstDayOfTheWeek = date ( 'N', strtotime ( $this->currentMonthStart ) );
		$year = (int)$this->currentYear;
		$Month = (int)$this->currentMonth;
		$d = cal_days_in_month(CAL_GREGORIAN,$Month,$year);
		$week_day = date("N", mktime(0,0,0,1,$Month,$year));
		$weeks = ceil(($d + $week_day) / 7);
		$weekLength = $weeks;
		if($weeks >= 6){
			$weekLength = 6;
		}
		$firstDayOfTheWeek = date ('N', strtotime ( $this->currentMonthStart ) );
		
		$weekDays = "";
		$weekLength = $this->getWeekLengthByMonth ();
		for($i = 0; $i < $weekLength; $i ++) {
			for($j = 1; $j <= 7; $j ++) {
				$cellIndex = $i * 7 + $j;
				$cellValue = null;
				if ($cellIndex == $firstDayOfTheWeek) {
					$this->currentDay = 1;
				}
				if (! empty ( $this->currentDay ) && $this->currentDay <= $this->currentMonthDaysLength) {
					$cellValue = $this->currentDay;
					$this->currentDay ++;
				}
				$weekDays .= '<li>' . $cellValue . '</li>';
			}
		}
		return $weekDays;
	}
	function getWeekLengthByMonth() { 
	
		$weekLength =  intval ( $this->currentMonthDaysLength / 7 );	
		if($this->currentMonthDaysLength % 7 > 0) {
			$weekLength++;
		}
		$monthStartDay= date ( 'N', strtotime ( $this->currentMonthStart) );		
		$monthEndingDay= date ( 'N', strtotime ( $this->currentYear . '-' . $this->currentMonth . '-' . $this->currentMonthDaysLength) );
		if ($monthEndingDay < $monthStartDay) {			
			$weekLength++;
		}
		return $weekLength;
	}
}
?>