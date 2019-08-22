<?php
error_reporting(0);

$database = "doortask";
$dbuser	  = "root";
$dbpass	  = "";
$dbhost	  =	"localhost";


$con	=	mysqli_connect($dbhost,$dbuser,$dbpass,$database) or die("error on connection");

//mysqli_select_db($database, $con);

$baseurl = "http://localhost/20-06-2019 Doortask Website/";																																				
//$remote	= $_SERVER['DOCUMENT_ROOT']."/doortask/";
$pagetitle = "doortask";

//$baseurl = "http://www.doortask.com/";
$remote	= $_SERVER['DOCUMENT_ROOT']."/";
?>