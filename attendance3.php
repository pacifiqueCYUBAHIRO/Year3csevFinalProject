<?php 
session_start();
if (!isset($_SESSION['logged-in'])) {
	header('Location: login.html');
// echo $_SESSION['logged-in'];
}
?>


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pacifique";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Retrieve the current date and time
$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');
$username = $_SESSION['username'];

// Function to perform face recognition
function performFaceRecognition($imagePath) {
    // Include AWS SDK for Rekognition
    require 'vendor/autoload.php';

    $accessKey = 'AKIAW3WOL3WYHHNZ7DPR'; // Replace with your AWS Access Key
    $secretKey = 'HOxP/B33yFa9PJCK/3Afek/ioLZBjNW7kwQfMHNC'; // Replace with your AWS Secret Access Key
    $region = 'us-east-1'; // Replace with your AWS region
    $collectionId = 'facerekognition'; // Replace with your Rekognition collection ID

    // Initialize the Rekognition client
    $rekognition = new Aws\Rekognition\RekognitionClient([
        'version' => 'latest',
        'region' => $region,
        'credentials' => [
            'key' => $accessKey,
            'secret' => $secretKey,
        ],
    ]);

    // Send the image to Amazon Rekognition for face recognition
    try {
        $result = $rekognition->searchFacesByImage([
            'CollectionId' => $collectionId,
            'Image' => [
                'Bytes' => file_get_contents($imagePath),
            ],
        ]);

        if (count($result['FaceMatches']) > 0) {
            $recognizedFaceId = $result['FaceMatches'][0]['Face']['FaceId'];
            return $recognizedFaceId;
        } else {
            return null;
        }
    } catch (Aws\Exception\AwsException $e) {
        echo 'Error: ' . $e->getMessage();
        return null;
    }
}

$imageCaptured = false; // Initialize a flag to check if the image has been captured

// Check if an image has been captured and set the session variable
if (isset($_POST['image_captured']) && $_POST['image_captured'] === "true") {
    $_SESSION['image_captured'] = true;
    $imageCaptured = true;
}

if (isset($_POST['submit'])) {
    // Check if an image has been captured before allowing submission
    if (isset($_SESSION['image_captured']) && $_SESSION['image_captured'] === true) {
        // Use the stored image path for face recognition
        $capturedImagePath = 'attend/' . $username . '.jpg';

        // Perform face recognition
        $recognizedFaceId = performFaceRecognition($capturedImagePath);

        if ($recognizedFaceId !== null) {
            // Face recognized, proceed to record attendance
            $sql = "INSERT INTO attendance (date, join_time, username) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sss", $currentDate, $currentTime, $username);

                if (mysqli_stmt_execute($stmt)) {
                    echo '<script>
                        window.onload = function() {
                            alert("Face recognized. Attendance recorded successfully!");
                        };
                    </script>';
                } else {
                    echo "Error inserting data: " . mysqli_error($conn);
                }

                mysqli_stmt_close($stmt);
            } else {
                echo "Error preparing statement: " . mysqli_error($conn);
            }
        } else {
            echo '<script>
                    window.onload = function() {
                        alert("Face not recognized.");
                    };
                </script>';
        }
    } else {
        echo '<script>
                window.onload = function() {
                    alert("Please capture a picture before submitting attendance.");
                };
            </script>';
    }
} elseif (isset($_POST['leave'])) {
    // User wants to log out
    // Check if the user has already logged in today
    $sql = "SELECT * FROM attendance WHERE username = ? AND date = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $username, $currentDate);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // User has already logged in today, update the existing record with the logout time
            $row = mysqli_fetch_assoc($result);
            $attendanceId = $row['id'];

            $sql = "UPDATE attendance SET logout_time = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                $logoutTime = $currentTime;
                mysqli_stmt_bind_param($stmt, "si", $logoutTime, $attendanceId);

                if (mysqli_stmt_execute($stmt)) {
                    echo '<script>
                        window.onload = function() {
                            alert("Logout recorded successfully!");
                            window.location.href = "logout.php"; // Redirect to logout page
                        };
                    </script>';
                } else {
                    echo "Error updating data: " . mysqli_error($conn);
                }

                mysqli_stmt_close($stmt);
            } else {
                echo "Error preparing statement: " . mysqli_error($conn);
            }
        } else {
            echo '<script>
                window.onload = function() {
                    alert("No attendance record found for today.");
                };
            </script>';
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}

// Close the database connection when done
mysqli_close($conn);
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
            height: 290px;
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
<canvas class="square" id="canvas"></canvas>
</div>


<div class="butt"> 

    <button id="capture">CAPTURE</button>

 <form method="post">
        <button type="submit" name="submit">Make Attendance</button>
    </form>
    <form method="post">
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
async function getWebCam() {
    try {
        const videoSrc = await navigator.mediaDevices.getUserMedia({ video: true });
        var video = document.getElementById("video");
        video.srcObject = videoSrc;
    } catch (e) {
        console.log(e);
    }
}
getWebCam();

var capture = document.getElementById("capture");
var canvas = document.getElementById("canvas");
var context = canvas.getContext('2d');

capture.addEventListener("click", function () {
    context.drawImage(video, 0, 20, 330, 115);

    // Send an AJAX request to set the image capture flag
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "set_image_captured.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Image capture flag set successfully
            console.log(xhr.responseText); // You can handle the response as needed
        }
    };
    xhr.send("image_captured=true");
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