<?php

// List of allowed IP addresses (replace with your company's network IPs)
$allowedIPs = array("192.168.1.100", "192.168.1.101"); // Add more as needed

// Function to check if the user's IP is in the allowed list
function isAllowedIP($userIP, $allowedIPs) {
    return in_array($userIP, $allowedIPs);
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user's IP address
    $userIP = $_SERVER['REMOTE_ADDR'];

    // Check if the user's IP is in the allowed list
    if (!isAllowedIP($userIP, $allowedIPs)) {
        echo '<script>
            window.onload = function() {
                alert("Access denied. Your IP is not authorized to make attendance.");
                window.location.href = "logout.php"; // Redirect to a logout page or wherever needed
            };
        </script>';
        exit; // Exit the script
    }
}

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

    // Retrieve the current date and time
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i:s');

    // Check if the user is logged in (you should add more robust authentication)
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

        // Check if the logout button is pressed
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
        } elseif (!empty($_POST['image_data'])) {
            // Decode the base64-encoded image data
            $imageData = $_POST['image_data'];
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
                            $uploadPath = 'attend/' . $username . '.jpg'; // Change the extension if needed
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

    // Close the database connection
    mysqli_close($conn);
?>
