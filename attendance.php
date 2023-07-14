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

// Check if the user has already logged in today
$sql = "SELECT * FROM attendance WHERE username = '$username' AND date = '$currentDate'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // User has already logged in today, update the existing record
    $row = mysqli_fetch_assoc($result);
    $attendanceId = $row['id'];

    if (isset($_POST['submit'])) {
        // Prepare the SQL statement to update the existing record with the latest join time
        $sql = "UPDATE attendance SET join_time = '$currentTime' WHERE id = '$attendanceId'";

        // Execute the SQL statement
        if (mysqli_query($conn, $sql)) {
            echo '<script>
                window.onload = function() {
                    alert("Attendance updated successfully!");
                };
            </script>';
        } else {
            echo "Error updating data: " . mysqli_error($conn);
        }
    } elseif (isset($_POST['leave'])) {
        // Prepare the SQL statement to update the existing record with the logout time
        $sql = "UPDATE attendance SET logout_time = '$currentTime' WHERE id = '$attendanceId'";

        // Execute the SQL statement
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
    }
} else {
    // User has not logged in today, create a new attendance record
    if (isset($_POST['submit'])) {
        // Prepare the SQL statement to insert the initial record
        $sql = "INSERT INTO attendance (date, join_time, username) VALUES ('$currentDate', '$currentTime', '$username')";

        // Execute the SQL statement
        if (mysqli_query($conn, $sql)) {
            echo '<script>
                window.onload = function() {
                    alert("Attendance recorded successfully!");
                };
            </script>';
        } else {
            echo "Error inserting data: " . mysqli_error($conn);
        }
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
            height: 300px;
            border: 2px solid black;
        }
       
        @media screen and (max-width: 850px) {
           .box{
            display: flex;
            flex-direction: column;
            margin: 20px;
            align-items: center;
            justify-content: center;
            
           } 
           .butt{
           display: flex;
           flex-direction: row;
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
            
            <div class="box">
  

<pre><video class="square" id="video" autoplay></video>  <canvas class="square" id="canvas"></canvas></pre>
</div>

<div class="butt"> <button id="capture">CAPTURE</button>
 <form method="post">
        <button type="submit" name="submit">Make Attendance</button>
    </form>
    <form method="post">
        <button name="leave">Logout</button>
    </form>
    
</div>


    
</div>


<div class="footer"> &COPY; 2023</div>
<script>
async function getWebCam(){
    try{
        const videoSrc=await navigator.mediaDevices.getUserMedia({video:true});
        var video=document.getElementById("video");
        video.srcObject=videoSrc;
    }catch(e){
        console.log(e);
    }
}
getWebCam();
var capture=document.getElementById("capture");
var canvas=document.getElementById("canvas");
var context=canvas.getContext('2d');
capture.addEventListener("click",function(){
    context.drawImage(video,0,20,330,115);
});
</script>
<script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=17cdf8cd-def1-4157-a6da-f19c801ef92e"> </script>
</body>
</html>