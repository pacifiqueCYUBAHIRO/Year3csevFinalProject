<?php
session_start();
if (!isset($_SESSION['logged-in'])) {
	header('Location: adminlog.html');
}
?>

<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pacifique";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin messages</title>

  <style>
    /*  import google fonts */
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap");
*{
  margin: 0;
  padding: 0;
  outline: none;
  border: none;
  text-decoration: none;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}
body{
  background: rgb(226, 226, 226);
}
nav{
  position: sticky;
  top: 0;
  bottom: 0;
  height: 100vh;
  left: 0;
  width: 90px;
  /* width: 280px; */
  background: #fff;
  overflow: hidden;
  transition: 1s;
}
nav:hover{
  width: 280px;
  transition: 1s;
}
.logo{
  text-align: center;
  display: flex;
  margin: 10px 0 0 10px;
  padding-bottom: 3rem;
}

.logo img{
  width: 45px;
  height: 45px;
  border-radius: 50%;
}
.logo span{
  font-weight: bold;
  padding-left: 15px;
  font-size: 18px;
  text-transform: uppercase;
}
a{
  position: relative;
  width: 280px;
  font-size: 14px;
  color: rgb(85, 83, 83);
  display: table;
  padding: 10px;
}
nav .fas{
  position: relative;
  width: 70px;
  height: 40px;
  top: 20px;
  font-size: 20px;
  text-align: center;
}
.nav-item{
  position: relative;
  top: 12px;
  margin-left: 10px;
}
a:hover{
  background: #eee;
}
a:hover i{
  color: #0b5885;
  transition: 0.5s;
}
.logout{
  position: absolute;
  bottom: 10px;
}

.container{
  display: flex;
}

/* MAin Section */
.main{
  position: relative;
  padding: 20px;
  width: 100%;
}
.main-top{
  display: flex;
  width: 100%;
}
.main-top i{
  position: absolute;
  right: 0;
  margin: 10px 30px;
  color: rgb(110, 109, 109);
  cursor: pointer;
}


    /* Container for the form */
  
    /* Input fields */
    input[type="text"],
    input[type="email"] {
      width: 50%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
    }

    /* Buttons */
    button {
      padding: 10px 20px;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }

    button[type="submit"] {
      background-color: #0b5885; /* Blue submit button */
      color: #fff;
    }

    button[type="delete_employee"] {
      background-color: #DC3545; /* Red delete button */
      color: #fff;
      margin-left: 10px;
    }

    select{
      width: 50%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
    }

  </style>
  <!-- Font Awesome Cdn Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
</head>
<body>
  <div class="container">
  <nav>
      <ul>
        <li><a href="admin.php" class="logo">
          <img src="./images/photo.png">
          <span class="nav-item">Admin</span>
        </a></li>
        <li><a href="admin.php">
          <i class="fas fa-menorah"></i>
          <span class="nav-item">Dashboard</span>
        </a></li>
        <li><a href="report.html">
          <i class="fas fa-database"></i>
          <span class="nav-item">Report</span>
        </a></li>
        <li><a href="attend.php">
          <i class="fas fa-chart-bar"></i>
          <span class="nav-item">Attendance</span>
        </a></li>
        <li><a href="setting.php">
          <i class="fas fa-cog"></i>
          <span class="nav-item">Setting</span>
        </a></li>

        <li><a href="logout.php" class="logout">
          <i class="fas fa-sign-out-alt"></i>
          <span class="nav-item">Log out</span>
        </a></li>
      </ul>
    </nav>



    <section class="main">
      <div class="main-top">
        <h1>Settings</h1>
        <i class="fas fa-user-cog"></i>
      </div>
      <br><br>
<div style="display: flex; justify-content: center; align-items: center; padding: 20px;">
    <form method="POST">
      <h2>Edit Employee</h2>
      
      <select id="edit_username" name="username">
  <option value="" disabled selected>Select username</option>
  <?php
  // Fetch usernames from the database
  $usernames_query = "SELECT username FROM employees";
  $result = mysqli_query($conn, $usernames_query);

  // Generate <option> elements
  while ($row = mysqli_fetch_assoc($result)) {
      $username = $row['username'];
      echo "<option value=\"$username\">$username</option>";
  }
  ?>
</select>

      <select id="edit_department" name="department">
        <option value="" disabled selected>Change department</option>
  <!-- Options will be populated dynamically by PHP -->
  <?php
// Fetch department options from the database
$departmentOptions = array("Cleaner", "Accountant", "Butcher man", " IT", "Finance");
// You can also fetch these options from a database query

// Generate <option> elements
foreach ($departmentOptions as $option) {
    echo "<option value=\"$option\">$option</option>";
}
?>

</select> <br>
      <button type="submit" name="edit_employee">Update</button>
      <button type="submit" name="delete_employee">Delete</button>
  </form>


  
  <?php 

  // Using prepared statement for editing an employee
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_employee"])) {
    $username = $_POST["username"];
   
    $department = $_POST["department"];
    
    $update_query = "UPDATE employees SET department=? WHERE username=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ss", $department, $username);
    
    if ($stmt->execute()) {
        echo "Employee updated successfully!";
    } else {
        echo "Error updating employee: " . $stmt->error;
    }
    
    $stmt->close();
  }
  
  
  
       ?>
  
  
       <?php
 
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_employee"])) {
    $username = $_POST["username"];
    
    $delete_query = "DELETE FROM employees WHERE username='$username'";
    
    if (mysqli_query($conn, $delete_query)) {
        echo "Employee deleted successfully!";
    } else {
        echo "Error deleting employee: " . mysqli_error($conn);
    }
  }
  
       ?>



</div>
    </section>

    <script>
  // Add an event listener for the selection change
  document.getElementById("selected_employee").addEventListener("change", function() {
    // Fetch employee data using AJAX
    let selectedUsername = this.value;
    if (selectedUsername !== "") {
      let xhr = new XMLHttpRequest();
      xhr.open("GET", "fetch_employee_data.php?username=" + selectedUsername, true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            let data = JSON.parse(xhr.responseText);
            // Populate input fields with fetched data
            document.getElementById("edit_username").value = data.username;
            document.getElementById("edit_department").value = data.department;
          } else {
            console.error("Error fetching employee data.");
          }
        }
      };
      xhr.send();
    }
  });
</script>
      
<!-- <div class="footer">&copy; 2023</div> -->

</body>
</html>
