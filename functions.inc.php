<?php
/**
 * Translating function. 
 */
function __($string)
{
	return $string;
}
/**
 * Classic PHP htmlspecialchars calling recursively on the array.
 */
function htmlspecialchars_array($array=array()) {
if($array)
{

    foreach($array as $k => $v)
    {
       if(is_array($v)) {
           $array[$k] = htmlspecialchars_array($v);
       } else {
           $array[$k] = htmlspecialchars($v);
       }      
    }
}
return $array;
}
/**
 * Changing date from UNIX timesamp to the format choosen by user.
 */
function unix_to_formated($date)
{
	  return date(DATE_FORMAT, (int)$date);
}
/**
 * Changing date from UNIX timesamp to the format choosen by user.
 */
function formated_to_unix($date)
{
	  $new_date = date_create_from_format(DATE_FORMAT, $date);
	  if(false === $new_date)
	  {
		  return false;
	  }
	  return date_format($new_date, 'U');
}
function valid_time($time, &$sp_time=array()) {
	if(preg_match('/^([0-9]{1,2}):([0-9]{1,2})$/', $time, $time_splitted)) {
		$h = (int)$time_splitted[1];
		$m = (int)$time_splitted[2];

	    if($h < 0 || $m < 0) {
			return false;
	    } elseif($h > 24) {
			return false;
		} elseif($h == 24)
		{
			if($m != 0)
			  return false;
		} else
		{
		  	if($m > 60)
		  	  return false;
		}
	$sp_time = array($h, $m);
	return true;
	}
}
function format_time($time) {
	$time = (int)$time;
	$h = floor($time/3600);
	$m = floor(($time-($h*3600))/60);
	return (strlen((string)$h) == 1 ? '0'.$h : $h).':'.(strlen((string)$m) == 1 ? '0'.$m : $m);

}
/**
 * Displaying error scren.
*/
function error($e) {
	include 'templates/error.php';
	exit;
}
