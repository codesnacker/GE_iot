<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

	include("connect.php"); 	
	
	$link=Connection();
    $sql  = 'SELECT * FROM `izilla_opens` ORDER BY `timestamp` DESC';
	$result = mysqli_query($link, $sql);
	
?>

<html>
   <head>
      <title>ShipmentOpeningsData</title>
	  	  <link rel="stylesheet" type="text/css" href="style.css">
   </head>
<body>
<h1 align=center>Cetral Shipment Tracking</h1>
   <hw align=center>Openings of Shipments</h4>

   <table border="1" cellspacing="1" cellpadding="1">
		<tr>
			<td>&nbsp;Time of opening&nbsp;</td>
			<td>&nbsp;Device_ID&nbsp;</td>
		</tr>

      <?php 
            
		  if($result!==FALSE){
		      
		     while($row = mysqli_fetch_assoc($result)) {
		          
					printf("<tr><td> &nbsp;%s </td><td> &nbsp;%s </td></tr>", 
		           $row["timestamp"],$row["device_id"]);
				}
		     mysqli_free_result($result);
		     mysqli_close($link);
		  
			}
      ?>

   </table>
  
</body>
</html>