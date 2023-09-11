<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pacifique";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function send_password_reset($email, $reset_token) {
    $mail = new PHPMailer(true);

    // SMTP Configuration
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = "smtp.gmail.com";
    $mail->Username = 'paccymaker@gmail.com'; // Replace with your Gmail email
    $mail->Password = 'hzzdfvncboktlfgq'; // Replace with your Gmail password or app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Sender and Recipient
    $mail->setFrom('paccymaker@gmail.com', 'Reset password'); // Replace with your info
    $mail->addAddress($email); // Recipient's email

    // Email Content
    $mail->isHTML(true);
    $mail->Subject = 'Password Reset Link';
    $mail->Body = "Click the following link to reset your password:<br><a href=\"http://localhost/year3csev/reset-password.php?token=$reset_token&email=$email\">Reset Link</a>";

    // Send the email
    $mail->send();
}

// Get email from form
$email = $_POST['email'];

// Query the database for the user
$sql = "SELECT * FROM employees WHERE email='$email'";
$result = mysqli_query($conn, $sql);

// Check if the user exists
if (mysqli_num_rows($result) > 0) {
    // User exists, generate a reset token and store it in the database
    $resetToken = bin2hex(random_bytes(32)); // Generate a random token
    $timestamp = time();

    $updateTokenQuery = "UPDATE employees SET reset_token='$resetToken', reset_token_timestamp='$timestamp' WHERE email='$email' LIMIT 1";
    if (mysqli_query($conn, $updateTokenQuery)) {
        // Send reset email with link to reset-password.php
        try {
            send_password_reset($email, $resetToken);
            echo '<script>
            window.onload = function() {
                alert("Password reset link sent to your email.");
                window.location = "login.html";
            };
        </script>'; 
            
        } catch (Exception $e) {
            echo "Error sending email: " . $e->getMessage();
        }
    } else {
        echo "Error updating reset token: " . mysqli_error($conn);
    }
} else {
    // User doesn't exist, show error message
    echo '<script>
    window.onload = function() {
        alert("No account found with this email.");
        window.location = "forgot-password.php";
    };
</script>';
}

mysqli_close($conn);
?>
