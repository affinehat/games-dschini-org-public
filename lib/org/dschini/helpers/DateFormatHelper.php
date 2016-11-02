<?php
/*
 * DateFormatHelper
 * @author	John Weber
 * @date	30/11/08
 * @see		http://dschini.googlecode.com/
 */
class DateFormatHelper {
	
	/*
	 * RFC822
	 */
	public static function RFC822($datetime)
	{
		$datetime = new DateTime($datetime);
		return $datetime->format(DATE_RFC822);
	}

	/*
	 * niceShort
	 */
	function niceShort ($datetime=null,$return = false)
	{
		//$datetime = date($datetime);
	    list ( $date, $time ) = explode ( ' ', $datetime );
	    list ( $year, $month, $day ) = explode ( '-', $date );
	    $datetime = new DateTime($datetime);
	    if( $year=date('y') && $month==date('m') && $day==date('d') ){
	    	return 'Today at '.$time;
	    }
	    if( $year=date('y') && $month==date('m') && $day==date('d')-1 ){
	    	return 'Yesterday at '.$time;
	    }
	    return $datetime->format('d.m.y').' at '.$time;
	}
	
	/*
	 * standard
	 */
	function standard($datetime=null)
	{
	    $datetime = new DateTime($datetime);
	    return $datetime->format('jS, F Y - G:i:s');
	}

    /*
     * month
     */
    function month ($month)
    {
        $datetime = new DateTime('2000-'.$month.'-01');
        return $datetime->format('F');
    }

    /*
     * year
     */
    function year ($year)
    {
        $datetime = new DateTime($year.'-01-01');
        return $datetime->format('y');
    }
	
	/*
	 * currentMonth
	 */
	function currentMonth()
	{
	    $datetime = new DateTime();
	    return $datetime->format('F Y');
	}
	
	
}
