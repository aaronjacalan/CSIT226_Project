<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Registration</title>
  <link rel="stylesheet" type="text/css" href="css/registerStyle.css">
  <link rel="stylesheet" type="text/css" href="css/indexStyle.css">
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

  <div class="container">
    <h2>User Registration Page</h2>
    <form id="registrationForm" method="post" onsubmit="return validateForm()">
      <div class="form-group">
        <label for="txtfirstname">Firstname:</label>
        <input type="text" id="txtfirstname" name="txtfirstname" value="<?php echo isset($_POST['txtfirstname']) ? htmlspecialchars($_POST['txtfirstname']) : ''; ?>">
      </div>
      <div class="form-group">
        <label for="txtlastname">Lastname:</label>
        <input type="text" id="txtlastname" name="txtlastname" value="<?php echo isset($_POST['txtlastname']) ? htmlspecialchars($_POST['txtlastname']) : ''; ?>">
      </div>
      <div class="form-group">
        <label for="txtgender">Gender:</label>
        <select id="txtgender" name="txtgender">
          <option value="">----</option>
          <option value="Male" <?php echo (isset($_POST['txtgender']) && $_POST['txtgender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
          <option value="Female" <?php echo (isset($_POST['txtgender']) && $_POST['txtgender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
        </select>
      </div>
      <div class="form-group">
        <label for="txtusertype">User Type:</label>
        <select id="txtusertype" name="txtusertype">
          <option value="">----</option>
          <option value="student" <?php echo (isset($_POST['txtusertype']) && $_POST['txtusertype'] == 'student') ? 'selected' : ''; ?>>Student</option>
          <option value="employee" <?php echo (isset($_POST['txtusertype']) && $_POST['txtusertype'] == 'employee') ? 'selected' : ''; ?>>Employee</option>
        </select>
      </div>
      <div class="form-group">
        <label for="txtusername">Username:</label>
        <input type="text" id="txtusername" name="txtusername" value="<?php echo isset($_POST['txtusername']) ? htmlspecialchars($_POST['txtusername']) : ''; ?>">
      </div>
      <div class="form-group">
        <label for="txtpassword">Password:</label>
        <input type="password" id="txtpassword" name="txtpassword">
      </div>
      <div class="form-group">
        <label for="txtprogram">Program:</label>
        <select id="txtprogram" name="txtprogram">
          <option value="">----</option>
          <option value="bsit" <?php echo (isset($_POST['txtprogram']) && $_POST['txtprogram'] == 'bsit') ? 'selected' : ''; ?>>BSIT</option>
          <option value="bscs" <?php echo (isset($_POST['txtprogram']) && $_POST['txtprogram'] == 'bscs') ? 'selected' : ''; ?>>BSCS</option>
        </select>
      </div>
      <div class="form-group">
        <label for="txtyearlevel">Year Level:</label>
        <select id="txtyearlevel" name="txtyearlevel">
          <option value="">----</option>
          <option value="1" <?php echo (isset($_POST['txtyearlevel']) && $_POST['txtyearlevel'] == '1') ? 'selected' : ''; ?>>1</option>
          <option value="2" <?php echo (isset($_POST['txtyearlevel']) && $_POST['txtyearlevel'] == '2') ? 'selected' : ''; ?>>2</option>
          <option value="3" <?php echo (isset($_POST['txtyearlevel']) && $_POST['txtyearlevel'] == '3') ? 'selected' : ''; ?>>3</option>
          <option value="4" <?php echo (isset($_POST['txtyearlevel']) && $_POST['txtyearlevel'] == '4') ? 'selected' : ''; ?>>4</option>
        </select>
      </div>
      <div class="form-submit">
        <button type="submit" name="btnRegister">Register</button>
        <button type="button" name="btnClear" onclick="clearForm()">Clear</button>
      </div>
    </form>
  </div>

  <script type="text/javascript">
    function validateForm() {
      const password = document.getElementById('txtpassword').value;
      const passwordPattern = /^[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/;

      if (!passwordPattern.test(password)) {
        alert('Password must be at least 8 characters long and have no spaces.');
        return false;
      }

      if (/\s/.test(password)) {
        alert('Password must not contain spaces.');
        return false;
      }

      return true;
    }

    function clearForm() {
      document.getElementById('registrationForm').reset();
    }
  </script>

  <?php    
    include 'conn.php';    

    if(isset($_POST['btnRegister'])){        
      $fname = $_POST['txtfirstname'];        
      $lname = $_POST['txtlastname'];
      $gender = $_POST['txtgender'];
      $utype = $_POST['txtusertype'];
      $uname = $_POST['txtusername'];        
      $pword = $_POST['txtpassword'];    

      if (preg_match('/^[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/', $pword) && !preg_match('/\s/', $pword)) {
          $hashedpw = password_hash($pword, PASSWORD_DEFAULT);

          $prog = $_POST['txtprogram'];        
          $yearlevel = $_POST['txtyearlevel'];        

          $sql1 = "INSERT INTO tbluser (firstname, lastname, gender, usertype, username, password) VALUES ('$fname', '$lname', '$gender', '$utype', '$uname', '$hashedpw')";
          mysqli_query($connection, $sql1);

          $last_id = mysqli_insert_id($connection);

          $sql2 = "INSERT INTO tblstudent (program, yearlevel, uid) VALUES ('$prog', '$yearlevel', '$last_id')";
          mysqli_query($connection, $sql2);

          echo "<script>alert('New record saved.');</script>";
          header("location: dashboard.php");
      } else {
          echo "<script>alert('Password must be at least 8 characters long, include special characters and have no spaces.');</script>";
      }
    }
  ?>

</body>
</html>