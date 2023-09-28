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
        
        <li><a href="report.php">
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
        <h1>Report</h1>
        <i class="fas fa-user-cog"></i>
      </div>
      <a href="attendance_data.xlsx" download>Download Excel</a>
    </section>

    <?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'pacifique';

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
  die('Connection failed: ' . mysqli_connect_error());
}

$query = "SELECT * FROM report GROUP BY username, date";
 // Modify the query as needed
$result = mysqli_query($connection, $query);

if (!$result) {
  die('Query failed: ' . mysqli_error($connection));
}

require 'C:\xampp\htdocs\year3csev\phpspreadsheet\vendor\autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set headers
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Username');
$sheet->setCellValue('C1', 'Email');
$sheet->setCellValue('D1', 'Department');
$sheet->setCellValue('E1', 'Date');
$sheet->setCellValue('F1', 'Join-time');
$sheet->setCellValue('G1', 'Logout-time');

// ... Add more headers if needed

$row = 2; // Start from row 2 for data

// Populate data
while ($data = mysqli_fetch_assoc($result)) {
  $sheet->setCellValue('A' . $row, $data['id']);
  $sheet->setCellValue('B' . $row, $data['username']);
  $sheet->setCellValue('C' . $row, $data['email']);
  $sheet->setCellValue('D' . $row, $data['department']);
  $sheet->setCellValue('E' . $row, $data['date']);
  $sheet->setCellValue('F' . $row, $data['join_time']);
  $sheet->setCellValue('G' . $row, $data['logout_time']);
  // ... Add more data columns if needed

  $row++;
}

// Create Excel file
$writer = new Xlsx($spreadsheet);
$excelFileName = 'attendance_data.xlsx'; // File name

// Save Excel file
$excelFilePath = __DIR__ . '/' . $excelFileName;
$writer->save($excelFilePath);

mysqli_close($connection);
?>
      
<!-- <div class="footer">&copy; 2023</div> -->

</body>
</html>
