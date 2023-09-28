<?php
session_start();
if (!isset($_SESSION['logged-in'])) {
	header('Location: adminlog.html');
// echo $_SESSION['logged-in'];
}
?>

<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'pacifique';

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Default to displaying all attendance records
$query = "SELECT attendance.username,attendance.id, employees.fullname, employees.email, employees.department, attendance.date, attendance.image_data, attendance.join_time, attendance.logout_time
            FROM employees
            INNER JOIN attendance ON employees.username = attendance.username";

$result = mysqli_query($connection, $query);

if (!$result) {
    die('Query failed: ' . mysqli_error($connection));
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Attendance Dashboard</title>
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
  bottom: 0;
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

.main img{
  width: 50px;
  height: 50px;
}

/*Attendance List serction  */
.attendance{
  margin-top: 20px;
  text-transform: capitalize;
}
.attendance-list{
  width: 100%;
  padding: 10px;
  margin-top: 10px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 20px 35px rgba(0, 0, 0, 0.1);
}
.table{
  border-collapse: collapse;
  margin: 25px 0;
  font-size: 15px;
  min-width: 100%;
  overflow: hidden;
  border-radius: 5px 5px 0 0;
  /* border: 1px solid #34AF6D; */
}
table thead tr{
  color: #fff;
  background: #0b5885;
  border: 2px solid #0b5885;
  text-align: left;
  font-weight: bold;
}
.table th,
.table td{
  padding: 12px 15px;
}
.table tbody tr{
  border-bottom: 1px solid #ddd;
}
.table tbody tr:nth-of-type(odd){
  background: #f3f3f3;
}
.table tbody tr:last-of-type{
  border-bottom: 2px solid #0b5885;

}
.table tbody tr{
  border-left: 1px solid #0b5885;
  border-right: 1px solid #0b5885;

}
.table button{
  padding: 6px 20px;
  border-radius: 10px;
  cursor: pointer;
  background: transparent;
  border: 1px solid #0b5885;
}
.table button:hover{
  background: #0b5885;
  color: #fff;
  transition: 0.5rem;
}

.picture{
  width: 50%;
  margin: 30px;
}

.btn{
  padding: 10px 20px;
  border-radius:5px;
  margin-left: 500px;
  cursor:pointer;
  border: 1px solid #0b5885;
}

.btn:hover{
background: #0b5885;
color: white;
}





.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.7); /* Black background with opacity */
  }

  /* Modal Content */
  .modal-content {
    text-align: center; /* Center the image horizontally */
    margin: 10% auto; /* Center the modal vertically */
    display: block;
    max-width: 100%;
    max-height: 100%;

  }

  /* Close Button */
  .close {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 28px;
    font-weight: bold;
    color: white;
    cursor: pointer;
  }



@media print {
    body {
      font-size: 12pt; /* Adjust font size for printing */
    }

    .print-content {
      display: block; /* Show this element when printing */
      /* Add more styling specific for printing */
    }

    /* Hide elements that you don't want to print */
    .no-print {
      display: none;
    }
  }

  </style>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
</head>
<body>


<div id="imageModal" class="modal">
  <span class="close" id="closeModal">&times;</span>
  <img width="600px" height="400px" class="modal-content" id="modalImage">
</div>



  <div class="container">


    <nav class="no-print">
      <ul>
        <li><a href="#" class="logo">
          <img src="./images/photo.png">
          <span class="nav-item">Admin</span>
        </a></li>
        <li><a href="admin.php">
          <i class="fas fa-menorah"></i>
          <span class="nav-item">Dashboard</span>
        </a></li>
        <li><a href="https://app.chatra.io/conversations/mychat">
          <i class="fas fa-comment"></i>
          <span class="nav-item">Message</span>
        </a></li>
        <li><a href="report.php">
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

    <section class="main" >

      <div class="main-top">
        <h1>Attendance List</h1>
        <i class="fas fa-user-cog"></i>
      </div>

      <form method="post" class="no-print">
    <label for="search_date">Search by Date:</label>
    <input type="date" id="search_date" name="search_date">
    <button style="margin-left: 860px; padding: 5px 30px;" class="btn" id="search_button" name="search">Search</button>
  </form>

  <?php
  if (isset($_POST['search'])) {
    $search_date = $_POST['search_date'];

    if ($search_date === '') {
      echo '<p>Please select a date to filter by.</p>';
    } else {
      echo '<table class="table">';
      echo '<thead>';
      echo '<tr>';

      echo '<th>Username</th>';
      echo '<th>Email</th>';
      echo '<th>Department</th>';
      echo '<th>Date</th>';
      echo '<th>Join Time</th>';
      echo '<th>Logout Time</th>';
      echo '<th>Picture</th>';
      echo '<th>Action</th>';

      echo '</tr>';
      echo '</thead>';
      echo '<tbody>';

      while ($row = mysqli_fetch_assoc($result)) {
        if ($row['date'] === $search_date) {
          echo '<tr>';

          echo '<td>' . $row['username'] .'</td>';
          echo '<td>' . $row['email'] . '</td>';
          echo '<td>' . $row['department'] . '</td>';
          echo '<td>' . $row['date'] . '</td>';
          echo '<td>' . $row['join_time'] . '</td>';
          echo '<td>' . $row['logout_time'] . '</td>';
          echo '<td>';
          echo '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image_data']) . '" onclick="openModal(\'data:image/jpg;charset=utf8;base64,' . base64_encode($row['image_data']) . '\')" style="cursor: pointer;" />';
          echo '</td>';
          echo '<td>';
          echo '<button class="delete-button" data-id="' . $row['id'] . '">Delete</button>';

          echo '</td>';

          echo '</tr>';
        }
      }

      echo '</tbody>';
      echo '</table>';
    }
  } else {
    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';

    echo '<th>Username</th>';
    echo '<th>Email</th>';
    echo '<th>Department</th>';
    echo '<th>Date</th>';
    echo '<th>Join Time</th>';
    echo '<th>Logout Time</th>';
    echo '<th>Picture</th>';
    echo '<th>Action</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
      echo '<tr>';

      echo '<td>' . $row['username'] .'</td>';
      echo '<td>' . $row['email'] . '</td>';
      echo '<td>' . $row['department'] . '</td>';
      echo '<td>' . $row['date'] . '</td>';
      echo '<td>' . $row['join_time'] . '</td>';
      echo '<td>' . $row['logout_time'] . '</td>';
      echo '<td>';
      echo '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['image_data']) . '" onclick="openModal(\'data:image/png;charset=utf8;base64,' . base64_encode($row['image_data']) . '\')" style="cursor: pointer;" />';
      echo '</td>';
      echo '<td>';
                  echo '<button class="delete-button" data-id="' . $row['id'] . '">Delete</button>';

                echo '</td>';
      echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
  }


  ?>


<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'pacifique';

if (isset($_POST['save'])) {
  $connection = mysqli_connect($host, $user, $password, $database);

  if (!$connection) {
      die('Connection failed: ' . mysqli_connect_error());
  }

  $query = "INSERT INTO report (username, email, department, date, join_time, logout_time)
            SELECT attendance.username, employees.email, employees.department, attendance.date, attendance.join_time, attendance.logout_time
            FROM employees
            INNER JOIN attendance ON employees.username = attendance.username";

  $result = mysqli_query($connection, $query);

  if ($result) {
      echo "Attendance data saved successfully!";
  } else {
      echo "Error: " . mysqli_error($connection);
  }

  mysqli_close($connection);
}


$connection = mysqli_connect($host, $user, $password, $database);
echo '<form method="post">';
echo '<button class="btn no-print" id="save_attendance" name="save" print()>Save Daily Attendance</button>';
echo '</form>';

mysqli_close($connection);
?>
  <button style="margin-top: 20px; padding: 5px 30px;" class="no-print btn" id="printButton">Print Page</button>
        </div>

      </section>
  </div>

<script>
  // JavaScript to handle printing when the button is clicked
  document.getElementById("printButton").addEventListener("click", function() {
    window.print();
  });
</script>




<script>
  // JavaScript to handle the delete button click
  document.querySelectorAll(".delete-button").forEach(function(button) {
    button.addEventListener("click", function() {
      // Get the ID of the row to delete
      var idToDelete = this.getAttribute("data-id");

      // Confirm with the user if they want to delete
      var confirmation = confirm("Are you sure you want to delete this record?");

      if (confirmation) {
        // If confirmed, send an AJAX request to delete the record
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete.php", true); // Replace "delete.php" with your PHP delete script
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
            // Reload the page or update the table data after successful deletion
            location.reload();
          }
        };
        xhr.send("id=" + idToDelete);
      }
    });
  });
</script>


<script>
  // Get the modal
  var modal = document.getElementById("imageModal");

  // Get the image and close button that opens the modal
  var modalImage = document.getElementById("modalImage");
  var closeModal = document.getElementById("closeModal");

  // Function to open the modal with the specified image
  function openModal(imageUrl) {
    modal.style.display = "block";
    modalImage.src = imageUrl;
  }

  // Close the modal when the close button is clicked
  closeModal.onclick = function() {
    modal.style.display = "none";
  };

  // Close the modal when the user clicks anywhere outside of it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  };
</script>
</body>
</html>