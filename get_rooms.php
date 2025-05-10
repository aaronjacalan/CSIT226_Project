<?php
class RoomManager {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Get all available rooms
    public function getAvailableRooms() {
        $sql = "SELECT roomid, roomType, isAvailable, price FROM tblroom WHERE isAvailable = 1";
        $result = $this->connection->query($sql);
        
        if (!$result) {
            throw new Exception("Error fetching available rooms: " . $this->connection->error);
        }

        $rooms = array();
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
        return $rooms;
    }

    // Get room by ID
    public function getRoomById($roomId) {
        $stmt = $this->connection->prepare("SELECT roomid, roomType, isAvailable, price FROM tblroom WHERE roomid = ?");
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $this->connection->error);
        }

        $stmt->bind_param("i", $roomId);
        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Get rooms by type
    public function getRoomsByType($roomType) {
        $stmt = $this->connection->prepare("SELECT roomid, roomType, isAvailable, price FROM tblroom WHERE roomType = ? AND isAvailable = 1");
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $this->connection->error);
        }

        $stmt->bind_param("s", $roomType);
        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $rooms = array();
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
        return $rooms;
    }

    // Check room availability for specific dates
    public function checkRoomAvailability($roomId, $dateFrom, $dateTo) {
        $stmt = $this->connection->prepare("
            SELECT * FROM tblroom 
            WHERE roomid = ? 
            AND isAvailable = 1 
            AND NOT EXISTS (
                SELECT 1 FROM tblroom 
                WHERE roomid = ? 
                AND (
                    (dateFrom <= ? AND dateTo >= ?) 
                    OR (dateFrom <= ? AND dateTo >= ?)
                    OR (dateFrom >= ? AND dateTo <= ?)
                )
            )
        ");

        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $this->connection->error);
        }

        $stmt->bind_param("iissssss", $roomId, $roomId, $dateTo, $dateFrom, $dateTo, $dateFrom, $dateFrom, $dateTo);
        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }

        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Get all room types
    public function getRoomTypes() {
        $sql = "SELECT DISTINCT roomType FROM tblroom";
        $result = $this->connection->query($sql);
        
        if (!$result) {
            throw new Exception("Error fetching room types: " . $this->connection->error);
        }

        $types = array();
        while ($row = $result->fetch_assoc()) {
            $types[] = $row['roomType'];
        }
        return $types;
    }

    // Get rooms with customer information
    public function getRoomsWithCustomerInfo() {
        $sql = "SELECT r.*, u.fname, u.lname 
                FROM tblroom r 
                LEFT JOIN tblcustomer c ON r.customerid = c.customerid 
                LEFT JOIN tbluser u ON c.userid = u.userid";
        
        $result = $this->connection->query($sql);
        
        if (!$result) {
            throw new Exception("Error fetching rooms with customer info: " . $this->connection->error);
        }

        $rooms = array();
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
        return $rooms;
    }
}

// Example usage:
/*
try {
    $roomManager = new RoomManager($connection);
    
    // Get all available rooms
    $availableRooms = $roomManager->getAvailableRooms();
    
    // Get specific room
    $room = $roomManager->getRoomById(1);
    
    // Check availability
    $isAvailable = $roomManager->checkRoomAvailability(1, '2024-05-01', '2024-05-05');
    
    // Get rooms by type
    $typeRooms = $roomManager->getRoomsByType('Type1');
    
    // Get all room types
    $roomTypes = $roomManager->getRoomTypes();
    
    // Get rooms with customer info
    $roomsWithCustomers = $roomManager->getRoomsWithCustomerInfo();
    
} catch (Exception $e) {
    error_log($e->getMessage());
    // Handle error appropriately
}
*/
?> 