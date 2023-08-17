<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $imageData = $_POST['image_data'];
    $password = $_POST['password'];

    // Decode the image data
    $decodedImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));

    // Establish a MySQL connection
    $mysqli = new mysqli('localhost', 'root', '', 'pacifique');

    // Prepare and execute the INSERT query
    $stmt = $mysqli->prepare("INSERT INTO employees (fullname, username, email, department, image_data, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $fullname, $username, $email, $department, $decodedImageData, $password);
    $stmt->execute();

    // Close the statement and the database connection
    $stmt->close();
    $mysqli->close();


    $uploadPath = 'uploads/' . $username . '.jpg'; // Change the extension if needed
    file_put_contents($uploadPath, $decodedImageData);
    // Redirect the user or show a success message
    // header("Location: register.html");
	echo '<script>
	window.onload = function() {
		alert("Data inserted successfully!");
		window.location = "login.html";
	};
</script>';
    exit;
}
?>
