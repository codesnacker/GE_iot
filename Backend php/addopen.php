<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
   	
   	
   	include("connect.php");
   	
   	$link=Connection();
   	$ts = strtotime("now -2 hours");
    $ts1 = date("Y-m-d h:m:s ",$ts);
    echo $ts1;

	$devid=$_POST["devid"];

	$query = "INSERT INTO `izilla_opens` (`device_id`,`timestamp`) 
		VALUES ('".$devid."', '".$ts1."')"; 
   	
   	mysqli_query($link,$query);
	mysqli_close($link);
	
	echo 'OK'

   	// header("Location: opens.php");
?>
