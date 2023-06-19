<!-- <?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'pacifique';

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}

$query1 = "SELECT * FROM employees";
$result1 = mysqli_query($connection, $query1);

$query2 = "SELECT * FROM attendance";
$result2 = mysqli_query($connection, $query2);

echo '<table class="table">';
echo '<thead>';
echo '<tr>';
echo '<th id="user">Username</th>';
echo '<th>Fullname</th>';
echo '<th>Department</th>';
echo '<th>Date</th>';
echo '<th>Join Time</th>';
echo '<th>Logout Time</th>';
echo '<th>Details</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

// Fetch data from result set 1
while ($row1 = mysqli_fetch_assoc($result1)) {
    // Access the data using column names
    echo '<tr>';
    echo '<td>' . $row1['username'] . '</td>';
    echo '<td>' . $row1['fullname'] . '</td>';
    echo '<td>' . $row1['department'] . '</td>';
    echo '<td></td>';
    echo '<td></td>';
    echo '<td></td>';
    echo '<td><button id="view-btn" onclick="display()">View</button></td>';
    echo '</tr>';
}

// Fetch data from result set 2
while ($row2 = mysqli_fetch_assoc($result2)) {
    // Access the data using column names
    echo '<tr>';
    echo '<td></td>';
    echo '<td></td>';
    echo '<td></td>';
    echo '<td>' . $row2['date'] . '</td>';
    echo '<td>' . $row2['join_time'] . '</td>';
    echo '<td>' . $row2['logout_time'] . '</td>';
    echo '<td><button id="view-btn">View</button></td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

// Close the database connection
mysqli_close($connection);
?>
 -->


<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'pacifique';

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}

$query1 = "SELECT * FROM employees";
$result1 = mysqli_query($connection, $query1);

$query2 = "SELECT * FROM attendance";
$result2 = mysqli_query($connection, $query2);


// Fetch data from result set 1
while ($row1 = mysqli_fetch_assoc($result1)) {
    // Access the data using column names
    echo $row1['username'];
    echo $row1['fullname'];
    echo $row1['department'];
    // ...
}

// Fetch data from result set 2
while ($row2 = mysqli_fetch_assoc($result2)) {
    // Access the data using column names
    echo $row2['date'];
    echo $row2['join_time'];
    echo $row2['logout_time'];
    // ...
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

$query = "SELECT employees.username, employees.fullname, employees.department, attendance.date, attendance.join_time, attendance.logout_time
          FROM employees
          INNER JOIN attendance ON employees.id = attendance.employee_id";

$result = mysqli_query($connection, $query);

if (!$result) {
    die('Query failed: ' . mysqli_error($connection));
}

// Fetch data from the result set
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['username'];
    echo $row['fullname'];
    echo $row['department'];
    echo $row['date'];
    echo $row['join_time'];
    echo $row['logout_time'];
}

// Close the database connection
mysqli_close($connection);
?>
