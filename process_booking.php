<?php
session_start();
require_once 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Check if user is logged in
        if (!isset($_SESSION['userid'])) {
            throw new Exception("Please login to book a room");
        }

        // Validate required fields
        if (!isset($_POST['roomId']) || !isset($_POST['dateFrom']) || !isset($_POST['dateTo'])) {
            throw new Exception("Required fields are missing");
        }

        $roomId = $_POST['roomId'];
        $dateFrom = $_POST['dateFrom'];
        $dateTo = $_POST['dateTo'];
        $customerId = $_SESSION['userid'];

        // Validate dates
        if (strtotime($dateFrom) >= strtotime($dateTo)) {
            throw new Exception("Check-out date must be after check-in date");
        }

        if (!$connection) {
            throw new Exception("Database connection failed");
        }

        // Check if room is available
        $checkSql = "SELECT isAvailable FROM tblroom WHERE roomid = ? AND isAvailable = 1";
        $checkStmt = $connection->prepare($checkSql);
        if (!$checkStmt) {
            throw new Exception("Failed to prepare check statement: " . $connection->error);
        }

        $checkStmt->bind_param("i", $roomId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Room is not available for booking");
        }

        // Update room booking
        $updateSql = "UPDATE tblroom SET 
                     isAvailable = 0,
                     customerId = ?,
                     dateFrom = ?,
                     dateTo = ?
                     WHERE roomid = ?";
        
        $updateStmt = $connection->prepare($updateSql);
        if (!$updateStmt) {
            throw new Exception("Failed to prepare update statement: " . $connection->error);
        }

        $updateStmt->bind_param("issi", $customerId, $dateFrom, $dateTo, $roomId);
        
        if ($updateStmt->execute()) {
            $_SESSION['success'] = "Room booked successfully!";
            header("Location: customer_dashboard.php");
            exit();
        } else {
            throw new Exception("Error booking room: " . $updateStmt->error);
        }

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: customer_dashboard.php");
        exit();
    } finally {
        if (isset($checkStmt)) {
            $checkStmt->close();
        }
        if (isset($updateStmt)) {
            $updateStmt->close();
        }
        if ($connection) {
            $connection->close();
        }
    }
} else {
    header("Location: customer_dashboard.php");
    exit();
}
?> 