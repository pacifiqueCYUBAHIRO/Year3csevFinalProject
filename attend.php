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
.main .users{
  display: flex;
  flex-wrap: wrap;  
}
.users .card{
  width: 23%;
  margin: 10px;
  background: #fff;
  text-align: center;
  border-radius: 10px;
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
  background: black;
  color: #fff;
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
}
table thead tr{
  color: #fff;
  background: black;
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
  font-weight: 500;
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
  background: black;
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
  width: 500px;
 
  border-radius: 10px;
  background: #f3f3f3;
  position: absolute;
  padding: 10px;
  left: 30%;
  transform: translate(-50%, -50%) scale(0.1);
  visibility: hidden;
  transition: transform 0.4s, bottom 0.4s;
  cursor: pointer;
  color: #fff;

}
.popup img{
    right: 0;
}
.open-popup{
  visibility: visible;
  top: 40%;
  transform: translate(-50%, -50%) scale(1);
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
        <div class="card">
          <img src="./images/admin.jpeg">
          <h4>Elisse</h4>
          <p>Ui designer</p>
          <div class="per">
            <table>
              <tr>
                <td><span>85%</span></td>
                <td><span>87%</span></td>
              </tr>
              <tr>
                <td>Month</td>
                <td>Year</td>
              </tr>
            </table>
          </div>
          <button id="profile">Profile</button>
        </div>
        <div class="card">
          <img src="./images/admin.jpeg">
          <h4>Elisse</h4>
          <p>Ui designer</p>
          <div class="per">
            <table>
              <tr>
                <td><span>85%</span></td>
                <td><span>87%</span></td>
              </tr>
              <tr>
                <td>Month</td>
                <td>Year</td>
              </tr>
            </table>
          </div>
          <button id="profile">Profile</button>
        </div>
        <div class="card">
          <img src="./images/admin.jpeg">
          <h4>Elisse</h4>
          <p>Ui designer</p>
          <div class="per">
            <table>
              <tr>
                <td><span>85%</span></td>
                <td><span>87%</span></td>
              </tr>
              <tr>
                <td>Month</td>
                <td>Year</td>
              </tr>
            </table>
          </div>
          <button id="profile">Profile</button>
        </div>
        <div class="card">
          <img src="./images/admin.jpeg">
          <h4>Elisse</h4>
          <p>Ui designer</p>
          <div class="per">
            <table>
              <tr>
                <td><span>85%</span></td>
                <td><span>87%</span></td>
              </tr>
              <tr>
                <td>Month</td>
                <td>Year</td>
              </tr>
            </table>
          </div>
          <button id="profile">Profile</button>
        </div>
        <div class="card">
          <img src="./images/admin.jpeg">
          <h4>Kellen</h4>
          <p>Progammer</p>
          <div class="per">
            <table>
              <tr>
                <td><span>85%</span></td>
                <td><span>87%</span></td>
              </tr>
              <tr>
                <td>Month</td>
                <td>Year</td>
              </tr>
            </table>
          </div>
          <button onclick="openPopup();">Profile</button>
        </div>
        <div class="popup" title="click to close" id="popup">
          <img src="./images/xx.png" width="20px" height="20px" style="margin-right: -300px; background: red;" alt="" onclick="closePopup();">
        </div>
        <div class="card">
          <img src="./images/admin.jpeg">
          <h4>Alliance</h4>
          <p>tester</p>
          <div class="per">
            <table>
              <tr>
                <td><span>85%</span></td>
                <td><span>87%</span></td>
              </tr>
              <tr>
                <td>Month</td>
                <td>Year</td>
              </tr>
            </table>
          </div>
          <button>Profile</button>
        </div>
        <div class="card">
          <img src="./images/admin.jpeg">
          <h4>Faustin</h4>
          <p>Ui designer</p>
          <div class="per">
            <table>
              <tr>
                <td><span>85%</span></td>
                <td><span>87%</span></td>
              </tr>
              <tr>
                <td>Month</td>
                <td>Year</td>
              </tr>
            </table>
          </div>
          <button>Profile</button>
        </div>
      </div>

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

$query = "SELECT attendance.username, employees.fullname, employees.department, attendance.date, attendance.join_time, attendance.logout_time
          FROM employees
          INNER JOIN attendance ON employees.id = attendance.id";

$result = mysqli_query($connection, $query);

if (!$result) {
    die('Query failed: ' . mysqli_error($connection));
}

// Start the attendance table body
echo '<table class="table">';
echo '<thead>';
echo '<tr>';
echo '<th id="user">Username</th>';
echo '<th>Fullname</th>';
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
        </div>
      </section>
    </section>
  </div>
<!-- <div class="footer">&copy; 2023</div> -->

<script>
  
  
  let popup = document.getElementById('popup');
function openPopup() {
  popup.classList.add("open-popup");
}
function closePopup() {
  popup.classList.remove("open-popup");
}
</script>


<!-- <script>
function exportToExcel() {
  /* Get table element */
  var table = document.getElementById("myTable");

  /* Convert table to workbook */
  var workbook = XLSX.utils.table_to_book(table);

  /* Convert workbook to Excel file */
  var excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });

  /* Save Excel file */
  saveAsExcelFile(excelBuffer, 'data.xlsx');
}

function saveAsExcelFile(buffer, fileName) {
  /* Convert buffer to Blob */
  var data = new Blob([buffer], { type: 'application/octet-stream' });

  /* Create download link */
  var link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = fileName;

  /* Trigger the download */
  link.click();
}
</script> -->
</body>
</html>
