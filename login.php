<?php
session_start();
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        header("Location: index.php?login_error=1");
        exit();
    }

    // Prepare statement to prevent SQL injection
    $stmt = $connection->prepare('SELECT userid, fname, lname, usertype, password FROM tbluser WHERE username = ?');
    if (!$stmt) {
        error_log("Prepare failed: " . $connection->error);
        header("Location: index.php?login_error=1");
        exit();
    }

    $stmt->bind_param('s', $username);
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        header("Location: index.php?login_error=1");
        exit();
    }

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $password === $user['password']) {
        // Set session variables
        $_SESSION['userid'] = $user['userid'];
        $_SESSION['fname'] = $user['fname'];
        $_SESSION['lname'] = $user['lname'];
        $_SESSION['usertype'] = $user['usertype'];
        
        // Log successful login
        error_log("User {$username} logged in successfully");
        
        // Redirect based on user type
        if ($user['usertype'] == 1) { // Customer
            header("Location: customer_dashboard.php");
        } else if ($user['usertype'] == 2) { // Staff
            header("Location: staff_dashboard.php");
        } else {
            // Invalid user type
            session_destroy();
            header("Location: index.php?login_error=1");
        }
        exit();
    } else {
        // Log failed login attempt
        error_log("Failed login attempt for username: {$username}");
        header("Location: index.php?login_error=1");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>