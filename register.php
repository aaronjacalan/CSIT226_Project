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
        <input type="text" id="txtfirstname" name="txtfirstname" required>
      </div>
      <div class="form-group">
        <label for="txtlastname">Lastname:</label>
        <input type="text" id="txtlastname" name="txtlastname" required>
      </div>
      <div class="form-group">
        <label for="txtbirthdate">Birth Date:</label>
        <input type="date" id="txtbirthdate" name="txtbirthdate" required>
      </div>
      <div class="form-group">
        <label for="txtusertype">User Type:</label>
        <select id="txtusertype" name="txtusertype" required>
          <option value="">----</option>
          <option value="1">Customer</option>
          <option value="2">Staff</option>
        </select>
      </div>
      <div class="form-group">
        <label for="txtusername">Username:</label>
        <input type="text" id="txtusername" name="txtusername" required>
      </div>
      <div class="form-group">
        <label for="txtpassword">Password:</label>
        <input type="password" id="txtpassword" name="txtpassword" required>
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
      $birthdate = $_POST['txtbirthdate'];
      $utype = $_POST['txtusertype'];
      $uname = $_POST['txtusername'];        
      $pword = $_POST['txtpassword'];    

      if (preg_match('/^[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/', $pword) && !preg_match('/\s/', $pword)) {
          $sql = "INSERT INTO tbluser (fname, lname, birthdate, usertype, username, password) 
                  VALUES ('$fname', '$lname', '$birthdate', '$utype', '$uname', '$pword')";
          
          if(mysqli_query($connection, $sql)) {
              $userid = mysqli_insert_id($connection);
              
              // If user is a customer, add to tblcustomer
              if($utype == 1) {
                  $sql2 = "INSERT INTO tblcustomer (userid) VALUES ($userid)";
                  mysqli_query($connection, $sql2);
              }
              // If user is staff, add to tblstaff
              else if($utype == 2) {
                  $sql2 = "INSERT INTO tblstaff (userid) VALUES ($userid)";
                  mysqli_query($connection, $sql2);
              }
              
              echo "<script>alert('Registration successful!'); window.location.href='index.php';</script>";
          } else {
              echo "<script>alert('Error: " . mysqli_error($connection) . "');</script>";
          }
      } else {
          echo "<script>alert('Password must be at least 8 characters long, include special characters and have no spaces.');</script>";
      }
    }
  ?>

</body>
</html>