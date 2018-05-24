<?php

	function Connection(){
		$server="localhost";
		$user="<username>";
		$pass="<password>";
		$db="<dbname>";
	   	
		$connection = mysqli_connect($server, $user, $pass, $db);

		if (!$connection) {
	    	die('MySQL ERROR1: ' . mysqli_error());
		}
		
		return $connection;
	}
?>
