<?php
session_start();
require_once 'conn.php';

// Check if user is logged in and is staff
if (!isset($_SESSION['userid']) || $_SESSION['usertype'] != 2) {
    header("Location: index.php#login");
    exit();
}

if (isset($_GET['id'])) {
    try {
        $roomId = $_GET['id'];

        // Check if room exists
        $checkSql = "SELECT roomid FROM tblroom WHERE roomid = ?";
        $checkStmt = $connection->prepare($checkSql);
        if (!$checkStmt) {
            throw new Exception("Failed to prepare check statement: " . $connection->error);
        }

        $checkStmt->bind_param("i", $roomId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Room not found");
        }

        // Delete the room
        $deleteSql = "DELETE FROM tblroom WHERE roomid = ?";
        $deleteStmt = $connection->prepare($deleteSql);
        if (!$deleteStmt) {
            throw new Exception("Failed to prepare delete statement: " . $connection->error);
        }

        $deleteStmt->bind_param("i", $roomId);
        
        if ($deleteStmt->execute()) {
            $_SESSION['success'] = "Room deleted successfully!";
        } else {
            throw new Exception("Error deleting room: " . $deleteStmt->error);
        }

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    } finally {
        if (isset($checkStmt)) {
            $checkStmt->close();
        }
        if (isset($deleteStmt)) {
            $deleteStmt->close();
        }
        if ($connection) {
            $connection->close();
        }
    }
}

header("Location: staff_dashboard.php");
exit();
?> 