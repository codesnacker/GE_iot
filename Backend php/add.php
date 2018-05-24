<?php

    error_reporting(E_ALL);
ini_set('display_errors', 1);
   	include("connect.php");
   	
   	$link=Connection();
   	// $ts  = strtotime("now -2 hours");
    // $ts1 = date("Y-m-d h:m:s",$ts);

	$temp1=$_POST["temp1"];
	$hum1=$_POST["hum1"];
	$devid=$_POST["devid"];

// 	$query = "INSERT INTO `izilla_tlogs` (`temp`, `hum`, `device_id`,`timestamp`) 
// 		VALUES ('".$temp1."','".$hum1."','".$devid."', '".$ts1."')"; 
   	
   	$query = "INSERT INTO `izilla_tlogs` (`device_id`, `temp`, `hum`) 
		VALUES ('".$devid."','".$temp1."','".$hum1."')"; 
   	
   	$query1 = "INSERT INTO `izilla_tlogs` (`device_id`, `timestamp`, `temp`, `hum`) VALUES ('iZilla001', CURRENT_TIMESTAMP, '31', '68')";
   	
   	mysqli_query($link,$query);
   
	mysqli_close($link);
	
		echo 'OK';

   	header("Location: index.php");
?>
