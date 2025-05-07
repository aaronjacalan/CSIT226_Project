<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Student Data</title>
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
      echo "<div class='container'><h2>Error: No student ID provided</h2>";
      echo "<p>Please go back to the <a href='dashboard.php'>dashboard</a> and select a student to edit.</p></div>";
      exit();
    }
    
    $userId = $_GET['id'];
    
    // Fetch user data from database
    $sql = "SELECT u.*, s.program, s.yearlevel 
            FROM tbluser u 
            LEFT JOIN tblstudent s ON u.id = s.uid 
            WHERE u.id = $userId";
    
    $result = mysqli_query($connection, $sql);
    
    if(mysqli_num_rows($result) == 0) {
      echo "<div class='container'><h2>Error: Student not found</h2>";
      echo "<p>Please go back to the <a href='dashboard.php'>dashboard</a> and select a valid student.</p></div>";
      exit();
    }
    
    $user = mysqli_fetch_assoc($result);
  ?>

  <div class="container">
    <h2>Edit Student Information</h2>
    <form id="editForm" method="post" onsubmit="return validateForm()">
      <input type="hidden" name="userId" value="<?php echo $userId; ?>">
      <div class="form-group">
        <label for="txtfirstname">Firstname:</label>
        <input type="text" id="txtfirstname" name="txtfirstname" value="<?php echo htmlspecialchars($user['firstname']); ?>">
      </div>
      <div class="form-group">
        <label for="txtlastname">Lastname:</label>
        <input type="text" id="txtlastname" name="txtlastname" value="<?php echo htmlspecialchars($user['lastname']); ?>">
      </div>
      <div class="form-group">
        <label for="txtgender">Gender:</label>
        <select id="txtgender" name="txtgender">
          <option value="">----</option>
          <option value="Male" <?php echo ($user['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
          <option value="Female" <?php echo ($user['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
        </select>
      </div>
      <div class="form-group">
        <label for="txtusertype">User Type:</label>
        <select id="txtusertype" name="txtusertype">
          <option value="">----</option>
          <option value="student" <?php echo ($user['usertype'] == 'student') ? 'selected' : ''; ?>>Student</option>
          <option value="employee" <?php echo ($user['usertype'] == 'employee') ? 'selected' : ''; ?>>Employee</option>
        </select>
      </div>
      <div class="form-group">
        <label for="txtusername">Username:</label>
        <input type="text" id="txtusername" name="txtusername" value="<?php echo htmlspecialchars($user['username']); ?>">
      </div>
      <div class="form-group">
        <label for="txtpassword">Password (leave blank to keep current):</label>
        <input type="password" id="txtpassword" name="txtpassword">
      </div>
      <div class="form-group">
        <label for="txtprogram">Program:</label>
        <select id="txtprogram" name="txtprogram">
          <option value="">----</option>
          <option value="bsit" <?php echo (isset($user['program']) && $user['program'] == 'bsit') ? 'selected' : ''; ?>>BSIT</option>
          <option value="bscs" <?php echo (isset($user['program']) && $user['program'] == 'bscs') ? 'selected' : ''; ?>>BSCS</option>
        </select>
      </div>
      <div class="form-group">
        <label for="txtyearlevel">Year Level:</label>
        <select id="txtyearlevel" name="txtyearlevel">
          <option value="">----</option>
          <option value="1" <?php echo (isset($user['yearlevel']) && $user['yearlevel'] == '1') ? 'selected' : ''; ?>>1</option>
          <option value="2" <?php echo (isset($user['yearlevel']) && $user['yearlevel'] == '2') ? 'selected' : ''; ?>>2</option>
          <option value="3" <?php echo (isset($user['yearlevel']) && $user['yearlevel'] == '3') ? 'selected' : ''; ?>>3</option>
          <option value="4" <?php echo (isset($user['yearlevel']) && $user['yearlevel'] == '4') ? 'selected' : ''; ?>>4</option>
        </select>
      </div>
      <div class="form-submit">
        <button type="submit" name="btnUpdate">Update Student</button>
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
      $gender = $_POST['txtgender'];
      $utype = $_POST['txtusertype'];
      $uname = $_POST['txtusername'];        
      $pword = $_POST['txtpassword'];
      $prog = $_POST['txtprogram'];        
      $yearlevel = $_POST['txtyearlevel'];
      
      // Update user information
      if(!empty($pword)) {
        // If password is provided, update it after validation
        if (preg_match('/^[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/', $pword) && !preg_match('/\s/', $pword)) {
          $hashedpw = password_hash($pword, PASSWORD_DEFAULT);
          $sql1 = "UPDATE tbluser SET firstname='$fname', lastname='$lname', gender='$gender', 
                   usertype='$utype', username='$uname', password='$hashedpw' WHERE id=$userId";
        } else {
          echo "<script>alert('Password must be at least 8 characters long, include special characters and have no spaces.');</script>";
          exit();
        }
      } else {
        // If password is not provided, update other fields only
        $sql1 = "UPDATE tbluser SET firstname='$fname', lastname='$lname', gender='$gender', 
                 usertype='$utype', username='$uname' WHERE id=$userId";
      }
      
      $result1 = mysqli_query($connection, $sql1);
      
      // Check if student record exists
      $checkSql = "SELECT * FROM tblstudent WHERE uid=$userId";
      $checkResult = mysqli_query($connection, $checkSql);
      
      if(mysqli_num_rows($checkResult) > 0) {
        // Update existing student record
        $sql2 = "UPDATE tblstudent SET program='$prog', yearlevel='$yearlevel' WHERE uid=$userId";
      } else {
        // Create new student record if it doesn't exist
        $sql2 = "INSERT INTO tblstudent (program, yearlevel, uid) VALUES ('$prog', '$yearlevel', '$userId')";
      }
      
      $result2 = mysqli_query($connection, $sql2);
      
      if($result1 && $result2) {
        echo "<script>alert('Student record updated successfully.'); window.location.href='dashboard.php';</script>";
      } else {
        echo "<script>alert('Error updating record: " . mysqli_error($connection) . "');</script>";
      }
    }
  ?>

</body>
</html>