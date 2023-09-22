<?php
if (isset($_POST['id'])) {
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'pacifique';

    $connection = mysqli_connect($host, $user, $password, $database);

    if (!$connection) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    $idToDelete = $_POST['id'];

    // Perform the DELETE query
    $query = "DELETE FROM attendance WHERE id = $idToDelete";

    if (mysqli_query($connection, $query)) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . mysqli_error($connection);
    }

    mysqli_close($connection);
}
?>
