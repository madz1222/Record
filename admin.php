<!DOCTYPE html>
<html lang="en">

<?php
session_start();
include('./db_connect.php');
include ('footer.php'); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './assets/plugins/phpmailer/src/PHPMailer.php';
require './assets/plugins/phpmailer/src/SMTP.php';
require './assets/plugins/phpmailer/src/Exception.php';


$token = md5(uniqid(mt_rand(), true));

// Assuming you have a form submission to initiate the password reset
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the email address and token from the form submission
    $email = $_POST['email'];
    $token = $_POST['token'];

    // Calculate the expiration time (1 hour from now in UTC)
    $expirationTime = strtotime('+1 hour');

    // Format the expiration time for database storage (MySQL)
    $expirationTimeFormatted = date('Y-m-d H:i:s', $expirationTime);

    // Update the token and expiration time in the users table
    $sql = "UPDATE users SET token = '$token', token_expiration = '$expirationTimeFormatted' WHERE email = '$email'";


    // Execute the query
    if (mysqli_query($conn, $sql)) {
        // Compose the password reset email
        $subject = 'Password Reset';
        $resetLink = 'localhost/record/new_password.php?token=' . $token;
        $message = 'Please click the following link to reset your password: <a href="' . $resetLink . '">' . $resetLink . '</a> Have a good day!';
    
        // Send the password reset email
        $mail = new PHPMailer(true);
        try {
            // Configure PHPMailer with your SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.mail.yahoo.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'robellnomorosa@yahoo.com';
            $mail->Password = 'yexhgjommdydlrte';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
    
            // Set the sender and recipient
            $mail->setFrom('robellnomorosa@yahoo.com', 'Robell Nomorosa');
            $mail->addAddress($email);
    
            // Set email content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
    
            // Send the email
            $mail->send();
            echo '<script>
            Swal.fire(
                "Good job!",
                "Email sent successfully!",
                "success"
            ).then(function() {
                window.location.href = "index.php?page=login.php";
            });
          </script>';
    
        } catch (Exception $e) {
            // You can perform any custom error handling here if needed
            // For example, log the error to a file or database
        }
    } else {
        echo "Error updating row: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Forgot Password | Record System</title>

    <?php include('./header.php'); ?>
    <?php
    if (isset($_SESSION['login_id']))
        header("location:index.php?page=home");
    ?>

</head>
<style>
    body {
        width: 100%;
        height: calc(100%);
        position: fixed;
        top: 0;
        left: 0
        /*background: #007bff;*/
    }

    main#main {
        width: 100%;
        height: 100vh;
        display: flex;
    }

</style>

<body class="bg-dark">


    <main id="main">
        <div class="align-self-center w-100">
            <h4 class="text-white text-center"><b>Record System</b></h4>
            <div id="login-center" class="bg-dark row justify-content-center">
                <div class="card col-md-4">
                    <div class="card-body">
                        <form id="reset_password" method="POST" action="">
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                            <div class="form-group">
                                <label for="email" class="control-label text-dark">Email</label>
                                <input type="email" id="email" name="email" class="form-control form-control-sm" required value="">
                            </div>
                            <center><button id="reset_password_button" class="btn-sm btn-block btn-wave col-md-4 btn-success">Reset Password</button></center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>
<script>
const resetPasswordButton = document.getElementById('reset_password_button');

// Attach a click event listener
resetPasswordButton.addEventListener('click', function() {
  // Display SweetAlert2 popup with loading icon
  Swal.fire({
    title: 'Loading',
    html: '<div class="swal-loading"><i class="fas fa-circle-notch fa-spin fa-3x" style="color: #2196F3;"></i></div><div class="swal-text">Please wait while the page refreshes and a reset link is being sent to your email.</div>', // Customized content with blue loading icon and message
    showConfirmButton: false, // Disable the "OK" button
    allowOutsideClick: false, // Prevent closing the popup by clicking outside
    didOpen: () => {
      Swal.showLoading();
    }
  });
});


</script>
</html>
