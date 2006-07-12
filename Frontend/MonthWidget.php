<?php

/**
 * This class defines a 30 day widget containing information for a given month.
 * 
 * 
 * @package UNL_UCBCN_Frontend
 * @author bbieber
 */

require_once 'UNL/UCBCN.php';
require_once 'Calendar/Calendar.php';
require_once 'Calendar/Month/Weekdays.php';
require_once 'Calendar/Util/Textual.php';

class UNL_UCBCN_Frontend_MonthWidget extends UNL_UCBCN
{	

	/** Calendar UNL_UCBCN_Calendar Object **/
	var $calendar;
	/** Year for this month widget */
	var $year;
	/** Month for this month widget. */
	var $month;
	/** Caption for the month widget. */
	var $caption;
	
	var $thead;
	var $tfoot;
	var $tbody;
	
	/**
	 * This function constructs the month widget and populates the heading,
	 * caption, footer and body for the MonthWidget.
	 * 
	 * @param int $y Year
	 * @param int $m Month
	 */
	function __construct($y,$m)
	{
		$this->year = $y;
		$this->month = $m;
		$Month = new Calendar_Month_Weekdays($y, $m);
		$PMonth = $Month->prevMonth('object'); // Get previous month as object
		$prev = $_SERVER['PHP_SELF'].'?y='.$PMonth->thisYear().'&amp;m='.$PMonth->thisMonth().'&amp;d='.$PMonth->thisDay();
		$NMonth = $Month->nextMonth('object');
		$next = $_SERVER['PHP_SELF'].'?y='.$NMonth->thisYear().'&amp;m='.$NMonth->thisMonth().'&amp;d='.$NMonth->thisDay();
		
		$this->tfoot = 
			'<tr>
				<td abbr="'.Calendar_Util_Textual::thisMonthName($PMonth).'" colspan="3" id="prev">
						<a href="'.$prev.'" title="View events for '.Calendar_Util_Textual::thisMonthName($PMonth).' '.$PMonth->thisYear().'"><< </a></td>
				<td class="pad"> </td>
				<td abbr="'.Calendar_Util_Textual::thisMonthName($NMonth).'" colspan="3" id="next" class="pad">
						<a href="'.$next.'" title="View events for '.Calendar_Util_Textual::thisMonthName($NMonth).' '.$NMonth->thisYear().'"> >></a>
				</td>
			</tr>';
		
		$this->caption = '<a href="?y='.$Month->thisYear().'&amp;m='.$Month->thisMonth().'">'.Calendar_Util_Textual::thisMonthName($Month).'</a>&nbsp;<a href="?y='.$Month->thisYear().'">'.$Month->thisYear().'</a>';
		
		//Determine selected days
		$selectedDays = array();
		$Month->build($selectedDays);
		
		while ( $Day = $Month->fetch() ) {
	
	    	// Build a link string for each day
			$link = $_SERVER['PHP_SELF'].
			'?y='.$Day->thisYear().
			'&amp;m='.$Day->thisMonth().
			'&amp;d='.$Day->thisDay();
			
			// isFirst() to find start of week
			if ( $Day->isFirst() )
				$this->tbody .= "<tr>\n";
			if ( $this->dayHasEvents($Day) ) {
				$this->tbody .= "<td class='selected'><a href='$link'>".$Day->thisDay()."</a></td>\n";
			} else if ( $Day->isEmpty() ) {
				$this->tbody .= "<td class='empty'>".$Day->thisDay()."</td>\n";
			} else {
				$this->tbody .= "<td>".$Day->thisDay()."</td>\n";
			}
			
			// isLast() to find end of week
			if ( $Day->isLast() )
				$this->tbody .= "</tr>\n";
		}
	}
	
	/**
	 * This function checks if a calendar has events on the day requested.
	 * @param object Calendar_Day object
	 * @param calendar UNL_UCBCN_Calendar object
	 * @return bool true or false
	 */
	function dayHasEvents($day,$calendar = NULL)
	{
		$eventdatetime = $this->factory('eventdatetime');
		$eventdatetime->whereAdd('starttime LIKE \''.date('Y-m-d',$day->getTimestamp()).'%\'');
		if (isset($calendar)) {
			$eventdatetime->joinAdd($calendar);
		}
		return $eventdatetime->find();
	}
	
}

?>