<?php 
session_start();
if (!isset($_SESSION['logged-in'])) {
	header('Location: adminlog.html');
// echo $_SESSION['logged-in'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Attendance</title>
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
  color: black;
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
.main .users{
  display: flex;
  flex-wrap: wrap; 
}
.users .card{
  width: 23%;
  margin: 10px;
  background: #fff;
  text-align: center;
  border-radius: 5px;
  padding: 10px;
  box-shadow: 0 20px 35px rgba(0, 0, 0, 0.1);
}
.users .card img{
  width: 70px;
  height: 70px;
  border-radius: 50%;
}
.users .card h4{
  text-transform: uppercase;
}
.users .card p{
  font-size: 12px;
  margin-bottom: 15px;
  text-transform: uppercase;
}
.users table{
  margin:  auto;
}
.users .per span{
  padding: 5px;
  border-radius: 10px;
  background: rgb(223, 223, 223);
}
.users td{
  font-size: 14px;
  padding-right: 15px;
}
.users .card button{
  width: 100%;
  margin-top: 8px;
  padding: 7px;
  cursor: pointer;
  border-radius: 10px;
  background: transparent;
  border: 1px solid black;
}
.users .card button:hover{
  background: #f3f3f3;
  color: #000;
  box-shadow: 3px 3px 3px rgba(0,12,12);
  transition: 0.5s;
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
  border-radius: 5px;
  box-shadow: 0 20px 35px rgba(0, 0, 0, 0.1);
}
.table{
  border-collapse: collapse;
  margin: 25px 0;
  font-size: 15px;
  min-width: 100%;
  overflow: hidden;
  border-radius: 5px 5px 0 0;
}
table thead tr{
  color: bla;
  background: rgb(223, 223, 223);
  box-shadow: 5px 5px 5px rgba(0,12,12);
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
.table tbody tr:hover{
  
  color: black;
}
.table tbody tr:last-of-type{
  border-bottom: 2px solid black;
}
.table button{
  padding: 6px 20px;
  border-radius: 10px;
  cursor: pointer;
  background: transparent;
  border: 1px solid black;
}
.table button:hover{
  background: #f3f3f3;
  color: #fff;
  transition: 0.5rem;
}
.footer{
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: black;
  color: white;
  padding: 20px;
}
.popup{
  width: 50%;
  box-shadow: 0px 4px 40px rgba(0, 0, 0, 0.25);
  border-radius: 5px;
  background: #f3f3f3;
  position: fixed;
  padding: 10px;
  left: 50%;
  cursor: pointer;
  z-index: 9999;
  display: none;


}
#backgroundOverlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: black;
  opacity: 0.7;
  z-index: 9998;
  display: none;
}

.open-popup{
  visibility: visible;
  top: 40%;
  opacity: 1;
  transform: translate(-50%, -50%) scale(1);
}
.container-pop{
  display: flex;
  flex-direction: row;
  width: 100%;
}
.picture{
  width: 50%;
  margin: 30px;
}
.info{
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  width: 50%;
  margin-top: -100px;
}
.info p{
  font-weight: bold;
}
.percentage td{
  font-weight: bold;
}
.percentage table{
  margin-top: -150px;
}
.percentage td span{
  font-weight: 200;
}


@media screen and (max-width:858px){
   
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
        <li><a href="admin.php">
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
        <h1>Attendance</h1>
        <i class="fas fa-user-cog"></i>
      </div>
      
      <div class="users">
        <?php
      $host = 'localhost';
$user = 'root';
$password = '';
$database = 'pacifique';

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}

$query = "SELECT username, fullname, email, department FROM employees";

$result = mysqli_query($connection, $query);

if (!$result) {
    die('Query failed: ' . mysqli_error($connection));
}      
        while ($row = mysqli_fetch_assoc($result)) {
          echo'<div class="card">';
          echo'<img src="./images/admin.jpeg">';
           echo '<h4>' . $row['username'] . '</h4>';
           echo '<p>' . $row['department'] . '</p>';
          echo'<div class="per">';
            echo'<table>';
              echo'<tr>';
                echo'<td><span>85%</span></td>';
                echo'<td><span>87%</span></td>';
              echo'</tr>';
              echo'<tr>';
                echo'<td>Month</td>';
                echo'<td>Year</td>';
              echo'</tr>';
            echo'</table>';
          echo'</div>';
          echo'<button id="profile" onclick="openPopup();">Profile</button>';
        echo'</div>';
      }
      
      
      // Close the database connection
      mysqli_close($connection);
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

$query = "SELECT attendance.username, employees.fullname, employees.email, employees.department, attendance.join_time, attendance.logout_time
          FROM employees
          INNER JOIN attendance ON employees.username = attendance.username";

$result = mysqli_query($connection, $query);

if (!$result) {
    die('Query failed: ' . mysqli_error($connection));
}    

        while ($row = mysqli_fetch_assoc($result)) {
          echo'<div class="popup" id="popup">';
          echo'<h1 style="text-align: center; padding: 10px;">PROFILE</h1>';

          echo'<div class="container-pop">';
            echo'<div class="picture"><img src="./images/photo.png" width="200px" height="250px" alt=""></div>';
            echo'<div class="info">';
            echo'<table>';
                  echo'<tr>';
                  echo'<td>';echo' <p>Fullname:</p>';echo'</td>';echo'<td>';echo'<span>'. $row['fullname']. '</span>';echo'</td>';echo'</tr>';
              echo'<tr>';
              echo'<td>';echo'<p>Username:</p>';echo'</td>';echo'<td>';echo'<span>'. $row['username']. '</span>';echo'</td>';echo'</tr>';
             echo'<tr>';
             echo'<td>';echo'<p>Email:</p>';echo'</td>';echo'<td>';echo'<span>'. $row['email']. '</span>';echo'</td>';echo'</tr>';
             echo'</tr>';
              echo'<td>';echo'<p>Department:</p>';echo'</td>';echo'<td>'; echo'<span>'. $row['department']. '</span>';echo'</td>';
             
             echo'</tr>';
             echo'</table>';
      
              echo'<div class="percentage">';
                echo'<table>';
                  echo'<tr>';
                  echo'<td>Month</td>';
                    echo'<td>Year</td>';
                    
                  echo'</tr>';
                  echo'<tr>';
                  echo'<td><span>85%</span></td>';
                    echo'<td><span>87%</span></td>';
                  echo'</tr>';
                echo'</table>';
                echo'</div>';
              
    

              echo'</div>';
              echo'</div>';
              echo'</div>';
            }
 mysqli_close($connection);
      ?>
       
      <div id="backgroundOverlay" onclick="closePopup();"></div>
      <section class="attendance">
        <div class="attendance-list">
          <h1>Attendance List</h1>
         
          <?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'pacifique';

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}

$query = "SELECT attendance.username, employees.fullname, employees.email, employees.department, attendance.date, attendance.join_time, attendance.logout_time
          FROM employees
          INNER JOIN attendance ON employees.username = attendance.username";

$result = mysqli_query($connection, $query);

if (!$result) {
    die('Query failed: ' . mysqli_error($connection));
}

// Start the attendance table body
echo '<table class="table" id="excell">';
echo '<thead>';
echo '<tr>';
echo '<th id="user">Username</th>';
echo '<th>Fullname</th>';
echo '<th>Email</th>';
echo '<th>Department</th>';
echo '<th>Date</th>';
echo '<th>Join Time</th>';
echo '<th>Logout Time</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

// Fetch data from the result set
while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td>' . $row['username'] . '</td>';
    echo '<td>' . $row['fullname'] . '</td>';
    echo '<td>' . $row['email'] . '</td>';
    echo '<td>' . $row['department'] . '</td>';
    echo '<td>' . $row['date'] . '</td>';
    echo '<td>' . $row['join_time'] . '</td>';
    echo '<td>' . $row['logout_time'] . '</td>';
    echo '</tr>';
}

// End the attendance table body
echo '</tbody>';
echo '</table>';


// Close the database connection
mysqli_close($connection);
?>
 <button id="view-btn" style="padding: 10px 20px; border-radius:5px; margin-left: 500px; cursor:pointer; border: 1px solid black;">Save</button>
        </div>
      </section>
    </section>
  </div>
<!-- <div class="footer">&copy; 2023</div> -->

<script>
  
  const backgroundOverlay = document.getElementById("backgroundOverlay");
  let popup = document.getElementById('popup');
function openPopup() {
  popup.classList.add("open-popup");
  popup.style.display = "block";
  backgroundOverlay.style.display = "block";
}
function closePopup() {
  popup.classList.remove("open-popup");
  popup.style.display = "none";
  backgroundOverlay.style.display = "none";
}
</script>


<!-- <script>
  // Assuming you have a <div> element with the id "myDiv" that contains the data
var divData = document.getElementById('.excell').innerHTML;

// Format the data as CSV (comma-separated values)
var csvContent = "fullname, username, join_time\n"; // Header row
csvContent += divData; // Add the div data to the CSV content

// Create a Blob with the CSV data
var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });

// Create a temporary anchor element to trigger the download
var link = document.createElement('a');
link.href = URL.createObjectURL(blob);
link.download = 'data.csv';

// Append the anchor element to the document body and click it to trigger the download
document.body.appendChild(link);
link.click();
document.body.removeChild(link);

</script> -->
</body>
</html>
