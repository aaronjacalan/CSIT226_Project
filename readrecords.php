<?php
	
	include 'conn.php';
	
	if (!$connection) {
	    die('Could not connect: ' . mysqli_connect_error());
	}
	
	$queryCustomers = "SELECT c.customerid, u.userid, u.usertype, u.fname, u.lname, u.birthdate FROM tblcustomer c INNER JOIN tbluser u ON u.userid = c.userid";
    $resultset = $connection->query($queryCustomers);
    
    if (!$resultset) die("Database query failed: " . $connection->error);
    
    $queryRooms = "SELECT roomid, customerid, isAvailable, dateFrom, dateTo, roomType, price FROM tblroom";
    $roomResultset = $connection->query($queryRooms);
    
    if (!$roomResultset) die("Database query for rooms failed: " . $connection->error);

    $queryStaff = "SELECT userid, usertype, fname, lname, birthdate FROM tbluser WHERE usertype = 2";
    $staffResultset = $connection->query($queryStaff);
    
    if (!$staffResultset) die("Database query for staff failed: " . $connection->error);		
	
?>