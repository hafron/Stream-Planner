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
