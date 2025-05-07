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
        .content h2 {
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
    </style>
</head>
<body>

    <?php    
        include 'conn.php';
        include 'readrecords.php';   
        //require_once 'includes/header.php'; 
    ?>

    <nav class="navbar">
        <div class="nav-container">

            <div class="logo">
                <img src="https://www.adaptivewfs.com/wp-content/uploads/2020/07/logo-placeholder-image.png" alt="Hotel Mangement System">
                <h1>RAMS</h1>
            </div>

            <div class="nav-menu">
                <a href="index.php#home">Home</a>
                <a href="index.php#login">Login</a>
                <a href="dashboard.php">Dashboard</a>
                <a href="index.php#contact">Contact</a>
                <a href="index.php#about">About</a>
            </div>

        </div>
    </nav>

    <div class="charts">
        <div class="chart-container">
            <canvas id="roomsChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="monthly_tracker"></canvas>
        </div>
    </div>
    
    <div class="content">
        <h2>List of Customers</h2>     

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
                        <button><a href="editData.php?id=<?php echo $id; ?>">UPDATE</a></button>
                        <button><a href="delete.php?id=<?php echo $id; ?>">DELETE</a></button>
                    </td>
                </tr>
                <?php endwhile;?>
            </tbody>         
        </table>
    </div>

    <div class="content">
        <h2>List of Rooms</h2>     

        <div class="buttons">
            <button style="margin-right: 20px;"><a href="addroom.php">Add New Room</a></button>
        </div> 

        <table>
            <thead>
                <tr> 
                    <th>Room ID</th> 
                    <th>Customer ID</th> 
                    <th>Availability</th> 
                    <th>Date From</th>                     
                    <th>Date To</th>
                    <th>Room Type</th>
                    <th>Action</th>
                </tr> 
            </thead>  
            <tbody>
                <?php
                    while($room = $roomResultset->fetch_assoc()):
                        $roomId = $room['roomid'];
                ?>
                <tr>
                    <td><?php echo $roomId ?></td>
                    <td><?php echo $room['customerid'] ?></td>
                    <td><?php echo $room['isAvailable'] ? 'Available' : 'Occupied' ?></td>
                    <td><?php echo $room['dateFrom'] ?></td>
                    <td><?php echo $room['dateTo'] ?></td>
                    <td><?php echo $room['roomType'] ?></td>
                    <td>
                        <button><a href="editRoom.php?id=<?php echo $roomId; ?>">UPDATE</a></button>
                        <button><a href="deleteRoom.php?id=<?php echo $roomId; ?>">DELETE</a></button>
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
</script>

</body>
</html>