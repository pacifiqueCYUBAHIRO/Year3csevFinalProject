
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
  <!-- <link rel="stylesheet" href="styles.css"> -->
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
        <li><a href="setting.html">
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
<div style="display: flex; justify-content: center; align-items: center;">
    <form method="POST">
      <h2>Edit Employee</h2>
      <select name="selected_employee" id="selected_employee">
        <option value="">Select an employee</option>
        <?php
        $select_query = "SELECT username, fullname FROM employees";
        $result = mysqli_query($conn, $select_query);
        
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<option value='{$row['username']}'>{$row['fullname']}</option>";
        }
        ?>
      <input type="text" id="edit_username" name="username" placeholder="Your Username">
      <input type="text" id="edit_fullname" name="fullname" placeholder="New Full Name">
      <input type="email" id="edit_email" name="email" placeholder="New Email">
      <input type="text" id="edit_department" name="department" placeholder="Change Department"> <br>
      <button type="submit" name="edit_employee">Edit</button>
      <button type="submit" name="delete_employee">Delete</button>
  </form>

  <?php 

  // Using prepared statement for editing an employee
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_employee"])) {
    $username = $_POST["username"];
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $department = $_POST["department"];
    
    $update_query = "UPDATE employees SET fullname=?, email=?, department=? WHERE username=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssss", $fullname, $email, $department, $username);
    
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

<?php
if (isset($_GET["username"])) {
  $selectedUsername = $_GET["username"];
  
  $select_query = "SELECT * FROM employees WHERE username=?";
  $stmt = $conn->prepare($select_query);
  $stmt->bind_param("s", $selectedUsername);
  $stmt->execute();
  
  $result = $stmt->get_result();
  $employeeData = $result->fetch_assoc();
  
  echo json_encode($employeeData);
} else {
  echo json_encode(array());
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
            document.getElementById("edit_fullname").value = data.fullname;
            document.getElementById("edit_email").value = data.email;
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