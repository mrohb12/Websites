<?php 
mysql_connect("localhost","root","");
define('SITE_URL','http://localhost/sms/securemts/');
define('BASE_URL','http://localhost/sms/securemts/');
define(DEBUG,0);
define('APP_NAME','Sanskar Billing Software');

$page = str_replace(".php","",$page);
$page =	str_replace("_"," ",$page);
$page =	str_replace("-"," ",$page);
$page =	str_replace("index","Welcome",$page);
$page =	ucwords($page);
include('function.php');
?>

 