<?php    
    session_start();
    require_once 'conn.php';
    
    // Check if user is logged in
    if (!isset($_SESSION['userid'])) {
        header("Location: index.php#login");
        exit();
    }

    try {
        // Verify database connection
        if (!$connection) {
            throw new Exception("Database connection failed");
        }

        // Get room type filter if set
        $selectedType = isset($_GET['type']) ? $_GET['type'] : '';
        
        // Base query
        $queryRooms = "SELECT roomid, customerid, isAvailable, dateFrom, dateTo, roomType, price FROM tblroom";
        
        // Add type filter if selected
        if (!empty($selectedType)) {
            $queryRooms .= " WHERE roomType = '" . $connection->real_escape_string($selectedType) . "'";
        }
        
        $roomResultset = $connection->query($queryRooms);
        
        if (!$roomResultset) {
            throw new Exception("Error fetching rooms: " . $connection->error);
        }
        
        // Get all room types for filter dropdown
        $typeQuery = "SELECT DISTINCT roomType FROM tblroom ORDER BY roomType";
        $typeResult = $connection->query($typeQuery);
        $roomTypes = [];
        if ($typeResult) {
            while ($row = $typeResult->fetch_assoc()) {
                $roomTypes[] = $row['roomType'];
            }
        }
        
    } catch (Exception $e) {
        error_log("Error in customer_dashboard.php: " . $e->getMessage());
        $error = "Error loading rooms: " . $e->getMessage();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Dashboard - Room Booking</title>
    <link rel="stylesheet" href="css/indexStyle.css">
    <style>
        .content {
            max-width: 1200px;
            margin: 120px auto 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        .content h1 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 20px;
        }
        .room-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .room-card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .room-card:hover {
            transform: translateY(-5px);
        }
        .room-card h3 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        .room-card p {
            margin: 5px 0;
            color: #666;
        }
        .room-card .price {
            font-size: 1.2em;
            color: var(--primary-color);
            font-weight: bold;
            margin: 10px 0;
        }
        .room-card .book-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }
        .room-card .book-btn:hover {
            opacity: 0.9;
        }
        .room-card .book-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .booking-form {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        .booking-form.active {
            display: block;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        .overlay.active {
            display: block;
        }
        .close-btn {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
            font-size: 20px;
        }
        .filter-section {
            margin-bottom: 20px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 8px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .filter-section select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        .filter-section button {
            padding: 8px 15px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .filter-section button:hover {
            opacity: 0.9;
        }
        .error-message {
            color: #dc3545;
            text-align: center;
            margin: 10px 0;
            padding: 10px;
            background: #f8d7da;
            border-radius: 4px;
        }
        .success-message {
            color: #28a745;
            text-align: center;
            margin: 10px 0;
            padding: 10px;
            background: #d4edda;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: var(--primary-color);
            color: white;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .book-btn {
            padding: 8px 15px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .book-btn:hover {
            opacity: 0.9;
        }
        .book-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
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
                <a href="index.php#home">Logout</a>
            </div>
        </div>
    </nav>

    <div class="content">
        <h1>Available Rooms</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-message">
                <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <div class="filter-section">
            <form method="GET" action="" id="filterForm">
                <select name="type" id="roomTypeFilter">
                    <option value="">All Room Types</option>
                    <?php foreach ($roomTypes as $type): ?>
                        <option value="<?php echo htmlspecialchars($type); ?>" 
                                <?php echo ($selectedType === $type) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($type); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Room ID</th>
                            <th>Room Type</th>
                            <th>Availability</th>
                            <th>Price per Night</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($room = $roomResultset->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($room['roomid']); ?></td>
                                <td><?php echo htmlspecialchars($room['roomType']); ?></td>
                                <td><?php echo $room['isAvailable'] ? 'Available' : 'Occupied'; ?></td>
                                <td>₱<?php echo number_format($room['price'] ?? 0, 2); ?></td>
                                <td>
                                    <button class="book-btn" onclick="openBookingForm(<?php echo $room['roomid']; ?>)" <?php echo !$room['isAvailable'] ? 'disabled' : ''; ?>>
                                        <?php echo $room['isAvailable'] ? 'Book Now' : 'Not Available'; ?>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="overlay" id="overlay"></div>
    <div class="booking-form" id="bookingForm">
        <span class="close-btn" onclick="closeBookingForm()">&times;</span>
        <h2>Book a Room</h2>
        <form id="roomBookingForm" action="process_booking.php" method="POST">
            <input type="hidden" id="roomId" name="roomId">
            <div class="form-group">
                <label for="dateFrom">Check-in Date:</label>
                <input type="date" id="dateFrom" name="dateFrom" required min="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-group">
                <label for="dateTo">Check-out Date:</label>
                <input type="date" id="dateTo" name="dateTo" required min="<?php echo date('Y-m-d'); ?>">
            </div>
            <button type="submit" class="book-btn">Confirm Booking</button>
        </form>
    </div>

    <script>
        function openBookingForm(roomId) {
            document.getElementById('overlay').classList.add('active');
            document.getElementById('bookingForm').classList.add('active');
            document.getElementById('roomId').value = roomId;
        }

        function closeBookingForm() {
            document.getElementById('overlay').classList.remove('active');
            document.getElementById('bookingForm').classList.remove('active');
        }

        // Close form when clicking overlay
        document.getElementById('overlay').addEventListener('click', closeBookingForm);

        // Validate dates
        document.getElementById('dateFrom').addEventListener('change', function() {
            document.getElementById('dateTo').min = this.value;
        });

        // Auto-submit form when filter changes
        document.getElementById('roomTypeFilter').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    </script>
</body>
</html> 