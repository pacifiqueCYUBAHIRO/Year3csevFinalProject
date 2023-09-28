
<?php
$allowedIPRange = "192.168.1.0/24"; // Example: Allow all IP addresses in the range 192.168.1.1 to 192.168.1.254
$userIP = $_SERVER['REMOTE_ADDR'];
list($allowedIP, $subnetMask) = explode('/', $allowedIPRange);
$allowedIPNumeric = ip2long($allowedIP);
$subnetMaskNumeric = pow(2, (32 - $subnetMask)) - 1;
$userIPNumeric = ip2long($userIP);

if (($userIPNumeric & $subnetMaskNumeric) !== ($allowedIPNumeric & $subnetMaskNumeric)) {
    // User's IP is not allowed, deny access
    echo "Access denied. Your IP address is not authorized for attendance.";
    exit;
}

// Your database connection code (replace with your credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pacifique";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check the database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the image data from the POST request
    $imageData = $_POST['image_data'];

    // Retrieve the current date and time
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i:s');

    // Check if the user is logged in (you should add more robust authentication)
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

        // Check if the logout button is pressed (if needed)
        if (isset($_POST['leave'])) {
            // Prepare the SQL statement to update the existing record with the logout time
            $sql = "UPDATE attendance SET logout_time = '$currentTime' WHERE username = '$username' AND date = '$currentDate'";

            if (mysqli_query($conn, $sql)) {
                echo '<script>
                    window.onload = function() {
                        alert("Logout recorded successfully!");
                        window.location.href = "logout.php";
                    };
                </script>';
            } else {
                echo "Error updating data: " . mysqli_error($conn);
            }
        } elseif (!empty($imageData)) {
            // Decode the base64-encoded image data
            $decodedImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));

            // Check if the user has already logged in today
            $sql = "SELECT * FROM attendance WHERE username = '$username' AND date = '$currentDate'";
            $result = mysqli_query($conn, $sql);

            if (!$result) {
                echo "Error: " . mysqli_error($conn);
            } elseif (mysqli_num_rows($result) > 0) {
                // User has already logged in today, update the existing record
                $row = mysqli_fetch_assoc($result);
                $attendanceId = $row['id'];

                if (isset($_POST['submit'])) {
                    // Prepare the SQL statement to update the existing record with the latest join time and image data
                    $sql = "UPDATE attendance SET join_time = '$currentTime', image_data = ? WHERE id = ?";
                    $stmt = mysqli_prepare($conn, $sql);

                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "si", $decodedImageData, $attendanceId);

                        if (mysqli_stmt_execute($stmt)) {
                            // Image saving
                            $uploadPath = 'attend/' . $username . '.jpg'; // Change the extension if needed
                            if (file_put_contents($uploadPath, $decodedImageData) === false) {
                                echo "Error saving image.";
                            } else {
                                echo '<script>
                                    window.onload = function() {
                                        alert("Attendance updated successfully!");
                                    };
                                </script>';
                            }
                        } else {
                            echo "Error updating data: " . mysqli_error($conn);
                        }

                        mysqli_stmt_close($stmt);
                    } else {
                        echo "Error preparing statement: " . mysqli_error($conn);
                    }
                }
            } else {
                // User has not logged in today, create a new attendance record with image data
                if (isset($_POST['submit'])) {
                    // Prepare the SQL statement to insert the initial record
                    $sql = "INSERT INTO attendance (date, join_time, image_data, username) VALUES (?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $sql);

                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "ssss", $currentDate, $currentTime, $decodedImageData, $username);

                        if (mysqli_stmt_execute($stmt)) {
                            // Image saving
                            $uploadPath = 'attend/' . $username . '.png'; // Change the extension if needed
                            if (file_put_contents($uploadPath, $decodedImageData) === false) {
                                echo "Error saving image.";
                            } else {
                                echo '<script>
                                    window.onload = function() {
                                        alert("Attendance recorded successfully!");
                                    };
                                </script>';
                            }
                        } else {
                            echo "Error inserting data: " . mysqli_error($conn);
                        }

                        mysqli_stmt_close($stmt);
                    } else {
                        echo "Error preparing statement: " . mysqli_error($conn);
                    }
                }
            }
        } else {
            // Display an error message or prevent submission if no image is captured
            echo '<script>
                window.onload = function() {
                    alert("Please capture an image before making attendance.");
                };
            </script>';
        }
    } else {
        // Handle the case where the user is not logged in (you should add proper authentication)
        echo "User is not logged in.";
    }
}

// Close the database connection
mysqli_close($conn);
?>

