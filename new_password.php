<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('./db_connect.php');

// You can perform the necessary actions to update the user's password here
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the new password and token from the request
    $new_password = $_POST['password'];
    $token = $_GET['token'];

    // Check if the new password is empty
    if (empty($new_password)) {
        exit;
    }

    // Update the password in the users table based on the token
    $sql = "";

    if (!empty($new_password)) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Build the SQL query
        $sql = "UPDATE users SET password = '$hashed_password' WHERE token = '$token'";
    }

    if (!empty($sql) && $conn->query($sql) === TRUE) {
        // Password updated successfully, perform any desired actions
        header('Location: ./login.php');
    } else {
        // Error updating password, handle the error accordingly
    }
}

// Retrieve the token from the URL parameter or form input
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Query the database to retrieve the token and expiration time
    $sql = "SELECT token, token_expiration FROM users WHERE token = '$token'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $storedToken = $row['token'];
        $expirationTime = strtotime($row['token_expiration']);

        // Compare the current time with the expiration time
        if ($storedToken === $token && time() <= $expirationTime) {
            // Token is valid and not expired
            // Proceed with the necessary actions or grant access
        } else {
            // Token is invalid or expired
            // Redirect the user to admin.php or display an error message
            header('Location: ./admin.php');
            exit;
        }
    } else {
        // Token is invalid or not found in the database
        // Redirect the user to admin.php or display an error message
        header('Location: ./admin.php');
        exit;
    }
} else {
    // Token is missing
    // Redirect the user to admin.php or display an error message
    header('Location: ./admin.php');
    exit;
}
?>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>New Password | Record System</title>

    <?php include('./header.php'); ?>

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
        height: calc(100%);
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
                        <form id="new-password-form" method="POST" action="">
                            <div class="form-group">
                                <label for="password" class="control-label text-dark">New Password</label>
                                <input type="password" id="password" name="password" class="form-control form-control-sm" required>
                            </div>
                            <center><button id="new_password_button" class="btn-sm btn-block btn-wave col-md-4 btn-success">Update Password</button></center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>
<script>
const new_password_button = document.getElementById('reset_password_button');

new_password_button.addEventListener('click', function() {
    // Display SweetAlert2 popup with success message
    Swal.fire({
        icon: 'success',
        title: 'Password Changed',
        text: 'Your password has been successfully changed.',
        showConfirmButton: false, // Disable the "OK" button
        timer: 4000, // Automatically close after 2 seconds
        didOpen: () => {
        setTimeout(() => {
            window.location.href = './login.php'; // Redirect to "./login.php"
        }, 4000); // Same duration as the timer
        }
    });
    });

</script>
</html>
