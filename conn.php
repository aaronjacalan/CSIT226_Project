<?php 
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	$connection = new mysqli('localhost', 'root', '', 'dbf2jacalan');
	
	if ($connection->connect_error) {
		die("Connection failed: " . $connection->connect_error);
	}
	
	// Verify if the required table exists
	$tableCheck = $connection->query("SHOW TABLES LIKE 'tblroom'");
	if ($tableCheck->num_rows == 0) {
		die("Error: Required table 'tblroom' does not exist in the database.");
	}
?>