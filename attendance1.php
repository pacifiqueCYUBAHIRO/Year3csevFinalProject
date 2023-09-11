<?php 
session_start();
if (!isset($_SESSION['logged-in'])) {
	header('Location: login.html');
// echo $_SESSION['logged-in'];
}
?>

<?php

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the image data from the POST request
    $imageData = $_POST['image_data'];

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
}
?>




<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pacifique";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);


if (isset($_POST['update'])) {
   
    $password = $_POST['password'];

    // Update the employee's information in the database
    $username = $_SESSION['username'];
    $updateQuery = "UPDATE employees SET password = '$password' WHERE username = '$username'";
    
    if (mysqli_query($conn, $updateQuery)) {
        echo '<script>
            window.onload = function() {
                alert("Profile updated successfully!");
            };
        </script>';
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Employee Face Recognation Attendance system</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        body{
            background: linear-gradient(to bottom right, #ffffff, #777676);
        }
        nav{
            display: flex;
            padding: 25px;
            background: rgb(1, 13, 44);
         }

         nav h2{
          color: white;
          font-size: 30px;
         }
         
         nav li{
          display: flex;
          position: absolute;
          right: 20px;
          top: 0;
          list-style: none;
    
         }

         nav a{
            font-size: 20px;
            font-weight: bold;
            color: white;
            text-decoration: none;
            padding: 10px;
           }
      
              nav a:hover {
               
                  color: yellowgreen; 
                }        
        h1{
            padding: 10px;
        }
        label{
            padding: 10px;
            margin-top: 20px;
        }
        input[type="password"] {
            height: 15px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-top: 20px;
    }

        .footer{
            color: #FFFFFF;
            text-align: center;
            background: rgb(1, 13, 44);
            
            padding: 20px;
        }
        a{
            margin: 20px;
        }
        button{
            display: block;
            width: 150px;
            background-color: #0b5885;
            color: #fff;
            text-align: center;
            padding: 10px;
            border: none;
            margin: 20px auto;
            text-decoration: none;
            border-radius: 5px;
            text-transform: uppercase;
        }

        button:hover{
            display: block;
            width: 150px;
            background-color: #3a0101;
            text-align: center;
            padding: 10px;
            margin: 20px auto;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.4s ease;
            cursor: pointer;
            
        }
        .square{
            width: 300px;
            height: 280px;
            border: 2px solid black;
        }
        form{
            display: flex;
            justify-content: center;
            margin-right: 200px;
        }
        .butt{
           display: flex;
           justify-content: center;
           padding: 9px;
           }


          
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}

/* Style for the modal content */
.modal-content {
    background-color: white;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 60%;
    position: relative;
}

/* Style for the close button */
.close {
    position: absolute;
    right: 10px;
    top: 5px;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}
.bbtt{
    gap: 30px;
    margin-left: 200px;
}

       
        @media screen and (max-width: 850px) {
           .box{
            display: flex;
            flex-direction: column;
            margin: 20px;
            align-items: center;
            justify-content: center;
            gap: 20px;
            
           }
           form{
            margin: 0;
           }
           .butt{
            display: flex;
            flex-direction: column;
           } 
           h1{
            display: block;
           }
        }
    </style>
</head>
<body bgcolor="#FFFFFF">
    <nav>
        <h2>O E F R <span style="color: yellowgreen;">A </span>S</h2>
        <ul>
          <li>
            <a href="index.html">Home</a>
          </li>
        </ul>
      </nav> 
   <center>
        
            <br><h1>LOOK ON WEBCAM  FOR ATTENDANCE</h1>
            <hr>
            <br>
            
            <div class="box">
  

<video class="square" id="video" autoplay></video>  
<canvas class="square" id="canvas" name="image"></canvas>
</div>


<div class="butt"> 

    <!-- <button id="capture">CAPTURE</button> -->
 <form method="post" action="" class="bbtt">
 <button type="button" id="capture" value="Capture"></button>
 <input type="hidden" accept="" name="image_data" id="image-data" required>
        <button type="submit" name="submit">Make Attendance</button>
         <button name="leave">Logout</button>
    </form>


   

</div>

 <button id="open-profile-modal">Edit Profile</button>

<div id="profile-modal" class="modal">
    <div class="modal-content">
        <span class="close" id="close-profile-modal">&times;</span>
        <h2>Change Your Password</h2>
        <form method="post">
           
            <label for="fullname">Create New password:</label>
            <input type="password" id="password" name="password" value="" required><br>
            <button type="submit" name="update">Update Profile</button>
        </form>
    </div>
</div>


<div class="footer"> &COPY; 2023</div>


<script>
      const video = document.getElementById('video');
      const canvas = document.getElementById('canvas');
      const captureButton = document.getElementById('capture');
      const imageDataInput = document.getElementById('image-data');
      const imageInput = document.getElementById('image');
      
      navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
          video.srcObject = stream;
        })
        .catch(error => {
          console.error('Error accessing webcam:', error);
        });
      
      captureButton.addEventListener('click', () => {
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageDataURL = canvas.toDataURL('image/png');
        imageDataInput.value = imageDataURL;
      });
      
      imageInput.addEventListener('change', () => {
        const file = imageInput.files[0];
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => {
          imageDataInput.value = reader.result;
        };
        reader.onerror = (error) => {
          console.error('Error reading image:', error);
        };
      });
    </script>
<script>
    (function(d, w, c) {
        w.ChatraID = 'njhpenbXDBbbEaETE';
        var s = d.createElement('script');
        w[c] = w[c] || function() {
            (w[c].q = w[c].q || []).push(arguments);
        };
        s.async = true;
        s.src = 'https://call.chatra.io/chatra.js';
        if (d.head) d.head.appendChild(s);
    })(document, window, 'Chatra');
</script>


<script>
// Open the profile update modal when the button is clicked
const openProfileModalButton = document.getElementById('open-profile-modal');
const profileModal = document.getElementById('profile-modal');
const closeProfileModalButton = document.getElementById('close-profile-modal');

openProfileModalButton.addEventListener('click', function() {
    profileModal.style.display = 'block';
});

// Close the profile update modal when the close button is clicked
closeProfileModalButton.addEventListener('click', function() {
    profileModal.style.display = 'none';
});

// Close the profile update modal if the user clicks outside of it
window.addEventListener('click', function(event) {
    if (event.target === profileModal) {
        profileModal.style.display = 'none';
    }
});
</script>

</body>
</html>