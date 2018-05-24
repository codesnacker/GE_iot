<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

	include("connect.php"); 	
	
	$link=Connection();
    $sql  = 'SELECT * FROM `izilla_tlogs` ORDER BY `device_id` DESC, `timestamp` DESC';
	$result = mysqli_query($link, $sql);
	
?>

<html>
   <div id="header" class="header">
   <head>
      <title>sensorData</title>
	  <link rel="stylesheet" type="text/css" href="style.css">
	    
   </head>
 </div>
<body>
<h1 align="center">Central Shipment Monitor </h1>
   <h2 align="center">Shipment No 01</h2>
   <h2 align="center"><a href="https://maps.app.goo.gl/xoIMjiIA0Qjy4F3m2">Track Shipment</a></h2>
    <h2 align="center"><a href="http://izilla.sangeethraaj.com/indexstat.php">Package Details</a></h2>

   <table border="1" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th>&nbsp;Time&nbsp;</th>
			<th>&nbsp;Device_ID&nbsp;</th>
			<th>&nbsp;Temperature(*C)&nbsp;</th>
			<th>&nbsp;WaterVapourContent&nbsp;</th>
		</tr>
		

      <?php 
            
		  if($result!==FALSE){
		      
		     while($row = mysqli_fetch_assoc($result)) {
		          
					printf("<tr><td> &nbsp;%s </td><td> &nbsp;%s </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td></tr>", 
		           $row["timestamp"],$row["device_id"], $row["temp"], $row["hum"]);
				}
		     mysqli_free_result($result);
		     mysqli_close($link);
		  
			}
      ?>
      
      

   </table>


</body>
</html>

