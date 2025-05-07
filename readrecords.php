<?php
	
	include 'conn.php';
	
	if (!$connection) {
	    die('Could not connect: ' . mysqli_connect_error());
	}
	
	$queryCustomers = "SELECT c.customerid, u.usertype, u.fname, u.lname, u.birthdate FROM tblcustomer c INNER JOIN tbluser u ON u.userid = c.userid";
    	$resultset = $connection->query($queryCustomers);
    
    	if (!$resultset) {
        	die("Database query failed: " . $connection->error);
    	}
    
    	$queryRooms = "SELECT roomid, customerid, isAvailable, dateFrom, dateTo, roomType FROM tblroom";
    	$roomResultset = $connection->query($queryRooms);
    
    	if (!$roomResultset) {
        	die("Database query for rooms failed: " . $connection->error);
    	}
	
	//$querybsit = 'SELECT count(*) as total from  tblstudent where program = "BSIT"';
	//$resultset1 = mysqli_query($connection, $querybsit);
	//$count = mysqli_fetch_assoc($resultset1);	
		
	
?>