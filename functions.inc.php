<?php
/**
 * Translating function. 
 */
function __($string)
{
	echo $string;
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

