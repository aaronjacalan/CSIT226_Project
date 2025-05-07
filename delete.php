<?php
	include 'conn.php';
	
	if (!$connection) {
	    die('Could not connect: ' . mysqli_connect_error());
	}

	if (isset($_GET['id']) && is_numeric($_GET['id'])) {
	    $id = $_GET['id'];

	    // Start a transaction
	    mysqli_begin_transaction($connection);

	    try {
	        $query1 = "DELETE FROM tblstudent WHERE uid = $id";
	        $result1 = mysqli_query($connection, $query1);

	        if (!$result1) { throw new Exception('Error deleting record from tblstudent: ' . mysqli_error($connection)); }

	        $query2 = "DELETE FROM tbluser WHERE id = $id";
	        $result2 = mysqli_query($connection, $query2);

	        if (!$result2) { throw new Exception('Error deleting record from tbluser: ' . mysqli_error($connection)); }

	        mysqli_commit($connection);

	        echo "Record deleted successfully.";
	    } catch (Exception $e) {
	        mysqli_rollback($connection);
	        echo $e->getMessage();
	    }
	} else {
	    echo "Invalid ID.";
	}

	mysqli_close($connection);
?>