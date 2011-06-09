<?php

$string = "Asdfasdffsadf
afd	Asdf	9-0	000
Ij	O	Oi	Op
I	Oij	Jugiu	Yfuyrf
Ds4g5	6sd5af4	W8er7	`89r7w8er

";
$string = trim($string);
$string = explode("\n",$string);
$newstring = array();
foreach($string as $s){
               $newstring[] = explode("\t",$s);
}

echo "<pre>";
var_dump($newstring);
echo "</pre>";









?>