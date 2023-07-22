<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'pacifique';

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}

if (isset($_GET['employeeid'])) {
  $empId = $_GET['employeeid'];

  $query = "SELECT employeeid, username, fullname, email, department FROM employees WHERE employeeid = $empId";

  $result = mysqli_query($connection, $query);

  if (!$result) {
    die('Query failed: ' . mysqli_error($connection));
  }

  $employeeData = mysqli_fetch_assoc($result);
  echo json_encode($employeeData);
}

mysqli_close($connection);
?>