<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/indexStyle.css">
    <style>
        .content {
            max-width: 1200px;
            margin: 50px auto 30px auto;
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
        .content .buttons {
            display: flex;
            margin: 15px 0;
        }
        .content .buttons button a {
            text-decoration: none;
            color: var(--dark-color);
        }
        button {
            padding: 15px 30px;
        }
        .content table {
            width: 100%;
            border-collapse: collapse;
        }
        .content table, th, td {
            border: 1px solid #ddd;
        }
        .content table th, .content table td {
            padding: 10px;
            text-align: center;
        }
        .content table tbody td button a {
            text-decoration: none;
        }
        .content table th {
            background-color: var(--primary-color);
            color: var(--accent-color);
        }
        .charts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            grid-gap: 50px;
            margin: 120px auto 30px auto;
            max-width: 1200px;
        }
        .chart-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        canvas {
            max-width: 100%;
            height: auto;
        }
        /* Add new styles for messages */
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

    <?php    
        require_once 'conn.php';
        require_once 'readrecords.php';   
        //require_once 'includes/header.php'; 
    ?>

    <nav class="navbar">
        <div class="nav-container">

            <div class="logo">
                <img src="https://www.adaptivewfs.com/wp-content/uploads/2020/07/logo-placeholder-image.png" alt="Hotel Mangement System">
                <h1>RAMS</h1>
            </div>

            <div class="nav-menu">
                <a href="index.php#home">Logout</a>
            </div>

        </div>
    </nav>

    <?php
    // Display messages if they exist
    if (isset($_GET['message'])) {
        echo '<div class="message success">' . htmlspecialchars($_GET['message']) . '</div>';
    }
    if (isset($_GET['error'])) {
        echo '<div class="message error">' . htmlspecialchars($_GET['error']) . '</div>';
    }
    ?>

    <div class="charts">
        <div class="chart-container">
            <canvas id="roomsChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="monthly_tracker"></canvas>
        </div>
    </div>
    
    <div class="content">
        <h1>List of Customers</h1>     

        <div class="buttons">
            <button style="margin-right: 20px;"><a href="register.php">Add New Customer</a></button>
            <button><a href="logout.php">Logout</a></button>
        </div> 

        <table>
            <thead>
                <tr> 
                    <th>Customer ID</th> 
                    <th>User Type</th> 
                    <th>First Name</th> 
                    <th>Last Name</th>                     
                    <th>Birth Date</th>
                    <th>Action</th>
                </tr> 
            </thead>  
            <tbody>
                <?php
                    while($row = $resultset->fetch_assoc()):
                        $id = $row['customerid'];
                ?>
                <tr>
                    <td><?php echo $id ?></td>
                    <td><?php echo $row['usertype'] ?></td>
                    <td><?php echo $row['fname'] ?></td>
                    <td><?php echo $row['lname'] ?></td>
                    <td><?php echo $row['birthdate'] ?></td>
                    <td>
                        <button><a href="editData.php?id=<?php echo $row['userid']; ?>">UPDATE</a></button>
                        <button onclick="return confirmDelete('customer')"><a href="delete.php?id=<?php echo $row['userid']; ?>">DELETE</a></button>
                    </td>
                </tr>
                <?php endwhile;?>
            </tbody>         
        </table>
    </div>

    <div class="content">
        <h1>List of Rooms</h1>     

        <div class="buttons">
            <button style="margin-right: 20px;"><a href="create_room.php">Add New Room</a></button>
        </div> 

        <table>
            <thead>
                <tr> 
                    <th>Room ID</th> 
                    <th>Customer ID</th> 
                    <th>Customer Name</th>
                    <th>Availability</th> 
                    <th>Date From</th>                     
                    <th>Date To</th>
                    <th>Room Type</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr> 
            </thead>  
            <tbody>
                <?php
                    $queryRooms = "SELECT r.*, u.userid, u.fname, u.lname 
                                   FROM tblroom r 
                                   LEFT JOIN tblcustomer c ON r.customerid = c.customerid 
                                   LEFT JOIN tbluser u ON c.userid = u.userid";
                    $roomResultset = $connection->query($queryRooms);
                    while($room = $roomResultset->fetch_assoc()):
                        $roomId = $room['roomid'];
                ?>
                <tr>
                    <td><?php echo $roomId ?></td>
                    <td><?php echo $room['customerid'] ?: 'None' ?></td>
                    <td><?php echo $room['customerid'] ? htmlspecialchars($room['fname'] . ' ' . $room['lname']) : 'None' ?></td>
                    <td><?php echo $room['isAvailable'] ? 'Available' : 'Occupied' ?></td>
                    <td><?php echo $room['dateFrom'] ?></td>
                    <td><?php echo $room['dateTo'] ?></td>
                    <td><?php echo $room['roomType'] ?></td>
                    <td>₱<?php echo number_format($room['price'], 2) ?></td>
                    <td>
                        <button><a href="editRoom.php?id=<?php echo $roomId; ?>">UPDATE</a></button>
                        <button onclick="return confirmDelete('room')"><a href="deleteRoom.php?id=<?php echo $roomId; ?>">DELETE</a></button>
                    </td>
                </tr>
                <?php endwhile;?>
            </tbody>         
        </table>
    </div>

    <div class="content">
        <h1>List of Staff Members</h1>     

        <div class="buttons">
            <button style="margin-right: 20px;"><a href="register.php?type=2">Add New Staff</a></button>
        </div> 

        <table>
            <thead>
                <tr> 
                    <th>Staff ID</th> 
                    <th>User Type</th>
                    <th>First Name</th> 
                    <th>Last Name</th>                     
                    <th>Birth Date</th>
                    <th>Action</th>
                </tr> 
            </thead>  
            <tbody>
                <?php
                    while($staff = $staffResultset->fetch_assoc()):
                        $staffId = $staff['userid'];
                ?>
                <tr>
                    <td><?php echo $staffId ?></td>
                    <td><?php echo $staff['usertype'] ?></td>
                    <td><?php echo $staff['fname'] ?></td>
                    <td><?php echo $staff['lname'] ?></td>
                    <td><?php echo $staff['birthdate'] ?></td>
                    <td>
                        <button><a href="editData.php?id=<?php echo $staffId; ?>">UPDATE</a></button>
                        <button onclick="return confirmDelete('staff')"><a href="delete.php?id=<?php echo $staffId; ?>">DELETE</a></button>
                    </td>
                </tr>
                <?php endwhile;?>
            </tbody>         
        </table>
    </div>

    <?php //require_once 'includes/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

<script>
  <?php
    $availableRooms = 0;
    $occupiedRooms = 0;
    
    $roomResultset->data_seek(0);
    
    while($room = $roomResultset->fetch_assoc()) {
      if($room['isAvailable']) {
        $availableRooms++;
      } else {
        $occupiedRooms++;
      }
    }
    
    $totalRooms = $availableRooms + $occupiedRooms;
    
    // Calculate monthly bookings based on dateFrom
    $monthlyBookings = array_fill(0, 12, 0); // Initialize array with 12 months set to 0
    
    $roomResultset->data_seek(0);
    
    while($room = $roomResultset->fetch_assoc()) {
      if(!empty($room['dateFrom']) && $room['customerid']) {
        $bookingMonth = date('n', strtotime($room['dateFrom'])) - 1; // Get month index (0-11)
        $monthlyBookings[$bookingMonth]++;
      }
    }
  ?>

  const roomData = {
    labels: ['Available', 'Occupied'],
    datasets: [{
      label: 'Room Status',
      data: [<?php echo $availableRooms; ?>, <?php echo $occupiedRooms; ?>],
      backgroundColor: [
        'rgb(54, 162, 235)',
        'rgb(255, 99, 132)'
      ],
      hoverOffset: 4
    }]
  };

  const ctx = document.getElementById('roomsChart').getContext('2d');
  const roomsChart = new Chart(ctx, {
    type: 'doughnut',
    data: roomData,
    options: {
      responsive: true,
      maintainAspectRatio: true,
      plugins: {
        title: {
          display: true,
          text: 'Room Availability (Total: <?php echo $totalRooms; ?> rooms)',
          font: {
            size: 16
          }
        },
        legend: {
          position: 'bottom'
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const value = context.parsed || 0;
              const total = context.dataset.data.reduce((acc, data) => acc + data, 0);
              const percentage = Math.round((value / total) * 100);
              return `${label}: ${value} (${percentage}%)`;
            }
          }
        }
      }
    }
  });

  const monthlyData = {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    datasets: [{
      label: 'Customer Bookings',
      data: [<?php echo implode(', ', $monthlyBookings); ?>],
      backgroundColor: 'rgba(75, 192, 192, 0.2)',
      borderColor: 'rgb(75, 192, 192)',
      borderWidth: 1
    }]
  };

  const monthlyCtx = document.getElementById('monthly_tracker').getContext('2d');
  const monthly_tracker = new Chart(monthlyCtx, {
    type: 'bar',
    data: monthlyData,
    options: {
      responsive: true,
      maintainAspectRatio: true,
      plugins: {
        title: {
          display: true,
          text: 'Monthly Customer Bookings',
          font: {
            size: 16
          }
        },
        legend: {
          position: 'bottom'
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return `Bookings: ${context.parsed.y}`;
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Number of Bookings'
          },
          ticks: {
            precision: 0 // No decimal places
          }
        },
        x: {
          title: {
            display: true,
            text: 'Month'
          }
        }
      }
    }
  });

  // Add confirmation dialog for delete buttons
  function confirmDelete(type) {
    let message = '';
    switch(type) {
        case 'customer':
            message = 'Are you sure you want to delete this customer? This will also cancel any active room bookings.';
            break;
        case 'staff':
            message = 'Are you sure you want to delete this staff member? This action cannot be undone.';
            break;
        case 'room':
            message = 'Are you sure you want to delete this room? This will remove all booking history for this room.';
            break;
        default:
            message = 'Are you sure you want to delete this item? This action cannot be undone.';
    }
    return confirm(message);
  }
</script>

</body>
</html>