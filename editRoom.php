<?php
session_start();
require_once 'conn.php';

// Check if user is logged in and is staff
if (!isset($_SESSION['userid']) || $_SESSION['usertype'] != 2) {
    header("Location: index.php#login");
    exit();
}

// Check if room ID is provided
if (!isset($_GET['id'])) {
    header("Location: staff_dashboard.php");
    exit();
}

$roomId = $_GET['id'];

// Fetch room details with customer information
$sql = "SELECT r.*, u.userid, u.fname, u.lname 
        FROM tblroom r 
        LEFT JOIN tblcustomer c ON r.customerid = c.customerid 
        LEFT JOIN tbluser u ON c.userid = u.userid 
        WHERE r.roomid = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $roomId);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();

if (!$room) {
    header("Location: staff_dashboard.php");
    exit();
}

// Fetch all customers for dropdown
$customersSql = "SELECT c.customerid, u.userid, u.fname, u.lname 
                 FROM tblcustomer c 
                 JOIN tbluser u ON c.userid = u.userid 
                 ORDER BY u.fname, u.lname";
$customersResult = $connection->query($customersSql);
$customers = [];
while ($row = $customersResult->fetch_assoc()) {
    $customers[] = $row;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (!isset($_POST['roomType']) || !isset($_POST['price'])) {
            throw new Exception("Required fields are missing");
        }

        $roomType = "Type" . $_POST['roomType'];
        $price = $_POST['price'];
        $isAvailable = isset($_POST['isAvailable']) ? 1 : 0;
        $dateFrom = $_POST['dateFrom'] ?: '0000-00-00';
        $dateTo = $_POST['dateTo'] ?: '0000-00-00';
        $customerId = $_POST['customerId'] ?: null;

        $updateSql = "UPDATE tblroom SET 
                     roomType = ?,
                     price = ?,
                     isAvailable = ?,
                     dateFrom = ?,
                     dateTo = ?,
                     customerid = ?
                     WHERE roomid = ?";
        
        $updateStmt = $connection->prepare($updateSql);
        if (!$updateStmt) {
            throw new Exception("Failed to prepare update statement: " . $connection->error);
        }

        $updateStmt->bind_param("sdisssi", $roomType, $price, $isAvailable, $dateFrom, $dateTo, $customerId, $roomId);
        
        if ($updateStmt->execute()) {
            $_SESSION['success'] = "Room updated successfully!";
            header("Location: staff_dashboard.php");
            exit();
        } else {
            throw new Exception("Error updating room: " . $updateStmt->error);
        }

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Room</title>
    <link rel="stylesheet" href="css/indexStyle.css">
    <style>
        .content {
            max-width: 800px;
            margin: 120px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .content h1 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .buttons button[type="submit"] {
            background-color: var(--primary-color);
            color: white;
        }
        .buttons button[type="button"] {
            background-color: #ddd;
        }
        .buttons button a {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <img src="https://www.adaptivewfs.com/wp-content/uploads/2020/07/logo-placeholder-image.png" alt="Hotel Management System">
                <h1>RAMS</h1>
            </div>
            <div class="nav-menu">
                <a href="staff_dashboard.php">Back to Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="content">
        <h1>Edit Room</h1>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="roomType">Room Type:</label>
                <select name="roomType" id="roomType" required>
                    <option value="1" <?php echo (substr($room['roomType'], 4) == '1') ? 'selected' : ''; ?>>Standard</option>
                    <option value="2" <?php echo (substr($room['roomType'], 4) == '2') ? 'selected' : ''; ?>>Luxury</option>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Price per Night (₱):</label>
                <input type="number" name="price" id="price" step="0.01" min="0" value="<?php echo $room['price']; ?>" required>
            </div>

            <div class="form-group">
                <label for="isAvailable">Availability:</label>
                <input type="checkbox" name="isAvailable" id="isAvailable" <?php echo $room['isAvailable'] ? 'checked' : ''; ?>>
                <label for="isAvailable">Available</label>
            </div>

            <div class="form-group">
                <label for="dateFrom">Date From:</label>
                <input type="date" name="dateFrom" id="dateFrom" value="<?php echo $room['dateFrom']; ?>">
            </div>

            <div class="form-group">
                <label for="dateTo">Date To:</label>
                <input type="date" name="dateTo" id="dateTo" value="<?php echo $room['dateTo']; ?>">
            </div>

            <div class="form-group">
                <label for="customerId">Customer:</label>
                <select name="customerId" id="customerId">
                    <option value="">No Customer</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?php echo $customer['customerid']; ?>" 
                                <?php echo ($room['customerid'] == $customer['customerid']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($customer['fname'] . ' ' . $customer['lname']); ?> 
                            (ID: <?php echo $customer['customerid']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if ($room['customerid']): ?>
            <div class="form-group">
                <label>Current Booking:</label>
                <p>
                    Customer: <?php echo htmlspecialchars($room['fname'] . ' ' . $room['lname']); ?><br>
                    User ID: <?php echo $room['userid']; ?><br>
                    From: <?php echo $room['dateFrom']; ?><br>
                    To: <?php echo $room['dateTo']; ?>
                </p>
            </div>
            <?php endif; ?>

            <div class="buttons">
                <button type="submit">Update Room</button>
                <button type="button"><a href="staff_dashboard.php">Cancel</a></button>
            </div>
        </form>
    </div>
</body>
</html> 