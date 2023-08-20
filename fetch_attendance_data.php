<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'pacifique';

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}

$query = "SELECT attendance.username, COUNT(*) as total_attendance
          FROM attendance
          GROUP BY attendance.username";

$result = mysqli_query($connection, $query);

if (!$result) {
    die('Query failed: ' . mysqli_error($connection));
}

$attendanceData = array();
while ($row = mysqli_fetch_assoc($result)) {
    $attendanceData[] = array(
        'username' => $row['username'],
        'attendance_count' => $row['total_attendance']
    );
}

mysqli_close($connection);

// Return the attendance data as JSON
header('Content-Type: application/json');
echo json_encode($attendanceData);
?>
