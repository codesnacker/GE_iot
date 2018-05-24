<?php

    error_reporting(E_ALL);
ini_set('display_errors', 1);
   	include("connect.php");
   	
   	$link=Connection();

	$devid=$_POST["devid"];
	$sid=$_POST["sid"];
	
    $ts = strtotime("now -2 hours");
    $ts1 = date("Y-m-d h:m:s ",$ts);
	
	$q1 = "SELECT `status` FROM `izilla_shipments` WHERE `shipment_id` LIKE '".$sid."'";
	$r1 = mysqli_query($link,$q1);
	
	
	       $s1 = mysqli_fetch_row ( $r1 );
	
        	if(strcmp($s1[0],'despatch')==0){
        	   $s2 = 'transit'; 
        	}elseif(strcmp($s1[0],'transit')==0){
        	    $s2 = 'delivered';
        	}elseif(strcmp($s1[0],'delivered')==0){
        	    $s2 = 'despatch';
        	}else{
        	    $s2 = 'unc';
        	}
        	
        	$query2 ="UPDATE `izilla_shipments` SET `status` = '".$s2."',`timestamp` = '".$ts1."' WHERE `izilla_shipments`.`shipment_id` = '".$sid."'";
        	
        // 	$query = "INSERT INTO `izilla_tlogs` (`temp`, `hum`, `device_id`) 
        // 		VALUES ('".$temp1."','".$hum1."','".$devid."')"; 
           	
           	mysqli_query($link,$query2);
	   
	mysqli_close($link);
	echo 'OK'

   	// header("Location: index.php");
?>
