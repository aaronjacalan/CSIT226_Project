<?php
require_once 'conn.php';

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    
    // Start transaction
    $connection->begin_transaction();
    
    try {
        // First, check if the user exists and get their type
        $checkUser = "SELECT usertype FROM tbluser WHERE userid = ?";
        $stmt = $connection->prepare($checkUser);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("User not found");
        }
        
        $userData = $result->fetch_assoc();
        $userType = $userData['usertype'];
        
        // If user is a customer (type 1), delete from customer table first
        if ($userType == 1) {
            // Get customer ID first
            $getCustomerId = "SELECT customerid FROM tblcustomer WHERE userid = ?";
            $stmt = $connection->prepare($getCustomerId);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $customerResult = $stmt->get_result();
            
            if ($customerResult->num_rows > 0) {
                $customerData = $customerResult->fetch_assoc();
                $customerId = $customerData['customerid'];
                
                // Update any rooms associated with this customer
                $updateRooms = "UPDATE tblroom SET customerid = NULL, isAvailable = 1, dateFrom = NULL, dateTo = NULL WHERE customerid = ?";
                $stmt = $connection->prepare($updateRooms);
                $stmt->bind_param("i", $customerId);
                $stmt->execute();
                
                // Delete from customer table
                $deleteCustomer = "DELETE FROM tblcustomer WHERE userid = ?";
                $stmt = $connection->prepare($deleteCustomer);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
            }
        } 
        // If user is a staff member (type 2)
        else if ($userType == 2) {
            // Delete from staff table if it exists
            $deleteStaff = "DELETE FROM tblstaff WHERE userid = ?";
            $stmt = $connection->prepare($deleteStaff);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
        }
        
        // Delete from user table
        $deleteUser = "DELETE FROM tbluser WHERE userid = ?";
        $stmt = $connection->prepare($deleteUser);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        
        if ($stmt->affected_rows === 0) {
            throw new Exception("Failed to delete user");
        }
        
        // If everything went well, commit the transaction
        $connection->commit();
        
        // Redirect back to dashboard with success message
        header("Location: staff_dashboard.php?message=User deleted successfully");
        exit();
        
    } catch (Exception $e) {
        // If there was an error, rollback the transaction
        $connection->rollback();
        
        // Log the error for debugging
        error_log("Delete user error: " . $e->getMessage());
        
        // Redirect back to dashboard with error message
        header("Location: staff_dashboard.php?error=" . urlencode("Failed to delete user: " . $e->getMessage()));
        exit();
    }
} else {
    // If no ID provided, redirect back to dashboard
    header("Location: staff_dashboard.php?error=No user ID provided");
    exit();
}
?>