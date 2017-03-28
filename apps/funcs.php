<?php
/*****************************************************************
Created By : Joaquim Marques (Joaquim.A.Marques@sapo.pt)
*****************************************************************/

ini_set('default_charset', 'UTF-8' );

// functions
//
function get_str($start, $end, $str){
$str = substr($str,strpos($str, $start)+strlen($start),strlen($str));     
return trim(substr($str,0,strpos($str,$end)));}
//
function strp_tags($str){return trim(strip_tags($str));}
//
function get_str1($start, $end, $str){
$str = substr($str,strpos($str, $start)+strlen($start)+1,strlen($str));     
return trim(substr($str,0,strpos($str,$end)-1));}
//
function str_clean($str){
$str = preg_replace('/[^(\x20-\x7F)]*/','', $str);
return trim($str);}
//
function str_quote($str){
$str = '"'.$str.'"';
return trim($str);}
//
function str_ed($str){
return trim(html_entity_decode($str));}

// some vars
$cs = ";"; $strout1 = ''; $eol = PHP_EOL; $col = 25;
$agent = 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.81 Safari/537.36';
for($v=1;$v<$col;$v++){$str[$v] = '';}

?>