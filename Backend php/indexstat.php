<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

	include("connect.php"); 	
	
	$link=Connection();
    $sql  = 'SELECT * FROM `izilla_shipments` ORDER BY `timeStamp` DESC';
	$result = mysqli_query($link, $sql);
	
?>

<html>
   <head>
      <title>ShipmentData</title>
	  	  <link rel="stylesheet" type="text/css" href="style.css">
   </head>
<body>
<h1 align=center>Central Shipment Monitor</h1>
   <h2 align=center>Package Status</h2>

   <table border="1" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th>&nbsp;Times&nbsp;</th>
			<th>&nbsp;PackageID&nbsp;</th>
			<th>&nbsp;Status&nbsp;</th>
		</tr>

      <?php 
            
		  if($result!==FALSE){
		      
		     while($row = mysqli_fetch_assoc($result)) {
		          
					printf("<tr><td> &nbsp;%s </td><td> &nbsp;%s </td><td> &nbsp;%s&nbsp; </td></tr>", 
		           $row["timestamp"],$row["shipment_id"], $row["status"]);
				}
		     mysqli_free_result($result);
		     mysqli_close($link);
		  
			}
      ?>

   </table>
  
</body>
</html>

