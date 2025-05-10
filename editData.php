<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit User Data</title>
  <link rel="stylesheet" type="text/css" href="css/registerStyle.css">
  <link rel="stylesheet" type="text/css" href="css/indexStyle.css">
  <style>
    .container {
      max-width: 800px;
      margin: 100px auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .container h2 {
      color: var(--primary-color);
      text-align: center;
      margin-bottom: 20px;
    }
    .form-submit {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }
    .form-submit button {
      padding: 15px 30px;
      cursor: pointer;
    }
    .form-group {
      margin-bottom: 15px;
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
        <a href="index.php#home">Home</a>
        <a href="index.php#login">Login</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="index.php#contact">Contact</a>
        <a href="index.php#about">About</a>
      </div>
    </div>
  </nav>

  <?php
    include 'conn.php';
    
    // Check if user ID is provided
    if(!isset($_GET['id']) || empty($_GET['id'])) {
      echo "<div class='container'><h2>Error: No user ID provided</h2>";
      echo "<p>Please go back to the <a href='dashboard.php'>dashboard</a> and select a user to edit.</p></div>";
      exit();
    }
    
    $userId = $_GET['id'];
    
    // Fetch user data from database
    $sql = "SELECT * FROM tbluser WHERE userid = $userId";
    
    $result = mysqli_query($connection, $sql);
    
    if(mysqli_num_rows($result) == 0) {
      echo "<div class='container'><h2>Error: User not found</h2>";
      echo "<p>Please go back to the <a href='dashboard.php'>dashboard</a> and select a valid user.</p></div>";
      exit();
    }
    
    $user = mysqli_fetch_assoc($result);
  ?>

  <div class="container">
    <h2>Edit User Information</h2>
    <form id="editForm" method="post" onsubmit="return validateForm()">
      <input type="hidden" name="userId" value="<?php echo $userId; ?>">
      <div class="form-group">
        <label for="txtfirstname">Firstname:</label>
        <input type="text" id="txtfirstname" name="txtfirstname" value="<?php echo htmlspecialchars($user['fname']); ?>" required>
      </div>
      <div class="form-group">
        <label for="txtlastname">Lastname:</label>
        <input type="text" id="txtlastname" name="txtlastname" value="<?php echo htmlspecialchars($user['lname']); ?>" required>
      </div>
      <div class="form-group">
        <label for="txtbirthdate">Birth Date:</label>
        <input type="date" id="txtbirthdate" name="txtbirthdate" value="<?php echo htmlspecialchars($user['birthdate']); ?>" required>
      </div>
      <div class="form-group">
        <label for="txtusertype">User Type:</label>
        <select id="txtusertype" name="txtusertype" required>
          <option value="">----</option>
          <option value="1" <?php echo ($user['usertype'] == 1) ? 'selected' : ''; ?>>Customer</option>
          <option value="2" <?php echo ($user['usertype'] == 2) ? 'selected' : ''; ?>>Staff</option>
        </select>
      </div>
      <div class="form-group">
        <label for="txtusername">Username:</label>
        <input type="text" id="txtusername" name="txtusername" value="<?php echo htmlspecialchars($user['username']); ?>" required>
      </div>
      <div class="form-group">
        <label for="txtpassword">Password (leave blank to keep current):</label>
        <input type="password" id="txtpassword" name="txtpassword">
      </div>
      <div class="form-submit">
        <button type="submit" name="btnUpdate">Update User</button>
        <button type="button" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
      </div>
    </form>
  </div>

  <script type="text/javascript">
    function validateForm() {
      const password = document.getElementById('txtpassword').value;
      
      // If password field is not empty, validate it
      if (password.trim() !== '') {
        const passwordPattern = /^[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/;

        if (!passwordPattern.test(password)) {
          alert('Password must be at least 8 characters long and have no spaces.');
          return false;
        }

        if (/\s/.test(password)) {
          alert('Password must not contain spaces.');
          return false;
        }
      }

      return true;
    }
  </script>

  <?php    
    if(isset($_POST['btnUpdate'])){
      $userId = $_POST['userId'];        
      $fname = $_POST['txtfirstname'];        
      $lname = $_POST['txtlastname'];
      $birthdate = $_POST['txtbirthdate'];
      $utype = $_POST['txtusertype'];
      $uname = $_POST['txtusername'];        
      $pword = $_POST['txtpassword'];
      
      // Update user information
      if(!empty($pword)) {
        // If password is provided, update it after validation
        if (preg_match('/^[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/', $pword) && !preg_match('/\s/', $pword)) {
          $sql = "UPDATE tbluser SET fname='$fname', lname='$lname', birthdate='$birthdate', 
                   usertype='$utype', username='$uname', password='$pword' WHERE userid=$userId";
        } else {
          echo "<script>alert('Password must be at least 8 characters long, include special characters and have no spaces.');</script>";
          exit();
        }
      } else {
        // If password is not provided, update other fields only
        $sql = "UPDATE tbluser SET fname='$fname', lname='$lname', birthdate='$birthdate', 
                 usertype='$utype', username='$uname' WHERE userid=$userId";
      }
      
      $result = mysqli_query($connection, $sql);
      
      if($result) {
        // Check if we need to update the user type in customer/staff tables
        if($utype == 1) {
          // Check if user exists in customer table
          $checkCustomer = mysqli_query($connection, "SELECT * FROM tblcustomer WHERE userid=$userId");
          if(mysqli_num_rows($checkCustomer) == 0) {
            // Remove from staff if exists
            mysqli_query($connection, "DELETE FROM tblstaff WHERE userid=$userId");
            // Add to customer
            mysqli_query($connection, "INSERT INTO tblcustomer (userid) VALUES ($userId)");
          }
        } else if($utype == 2) {
          // Check if user exists in staff table
          $checkStaff = mysqli_query($connection, "SELECT * FROM tblstaff WHERE userid=$userId");
          if(mysqli_num_rows($checkStaff) == 0) {
            // Remove from customer if exists
            mysqli_query($connection, "DELETE FROM tblcustomer WHERE userid=$userId");
            // Add to staff
            mysqli_query($connection, "INSERT INTO tblstaff (userid) VALUES ($userId)");
          }
        }
        
        echo "<script>alert('User record updated successfully.'); window.location.href='dashboard.php';</script>";
      } else {
        echo "<script>alert('Error updating record: " . mysqli_error($connection) . "');</script>";
      }
    }
  ?>

</body>
</html>