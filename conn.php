<?php 
	$connection = new mysqli('localhost', 'root','','dbf2jacalan');
	
	if (!$connection){
		die (mysqli_error($mysqli));
	}
		
?>