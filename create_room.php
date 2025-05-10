<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Room</title>
    <link rel="stylesheet" href="css/indexStyle.css">
    <style>
        .content {
            max-width: 800px;
            margin: 50px auto;
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
    <?php
    // Database connection and error handling
    $connection = null;

    try {
        require_once 'conn.php';
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!isset($_POST['roomType']) || !isset($_POST['price'])) {
                throw new Exception("Required fields are missing");
            }

            $roomType = "Type" . $_POST['roomType'];
            $price = $_POST['price'];
            $isAvailable = 1; // Using 1 for true since it's tinyint(1)
            $dateFrom = '0000-00-00'; // Default date since it's NOT NULL
            $dateTo = '0000-00-00'; // Default date since it's NOT NULL
            $customerId = null; // This can be NULL

            if (!$connection) {
                throw new Exception("Database connection failed");
            }

            $sql = "INSERT INTO tblroom (roomType, price, isAvailable, dateFrom, dateTo, customerId) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $connection->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $connection->error);
            }

            $stmt->bind_param("sdisss", $roomType, $price, $isAvailable, $dateFrom, $dateTo, $customerId);
            
            if ($stmt->execute()) {
                echo "<script>alert('Room added successfully!'); window.location.href='staff_dashboard.php';</script>";
            } else {
                throw new Exception("Error adding room: " . $stmt->error);
            }
            
            $stmt->close();
        }
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    } finally {
        if ($connection) {
            $connection->close();
        }
    }
    ?>

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
        <h1>Add New Room</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="roomType">Room Type:</label>
                <select name="roomType" id="roomType" required>
                    <option value="">Select Room Type</option>
                    <option value="1">Standard</option>
                    <option value="2">Luxury</option>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Price per Night (₱):</label>
                <input type="number" name="price" id="price" step="0.01" min="0" required>
            </div>

            <div class="buttons">
                <button type="submit">Add Room</button>
                <button type="button"><a href="staff_dashboard.php">Cancel</a></button>
            </div>
        </form>
    </div>
</body>
</html> 