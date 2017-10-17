<?php

// include database connection details
include "../db-connection.php";
include "doEmailConnection.php";

// sanitize the POST values
$email = $_POST['email'];

// connect database
$connection = new Mysql_Driver();
$connection->connect();

// create query
$queryEmail = "SELECT * 
                FROM account 
                WHERE email = '$email'";
$resultEmail = $connection->query($queryEmail);

if ($connection->num_rows($resultEmail) == 1) {
    $user = mysqli_fetch_assoc($resultEmail);
    $dbAccountId = $user['accountID'];
    $dbName = $user['name'];

    // token to reset password
    $resetPassToken = md5(uniqid(rand(), true));

    $queryUpdate = "UPDATE account 
                    SET resetPasswordToken = '$resetPassToken' 
                    WHERE accountID = " . $dbAccountId . "";
    $updateDB = $connection->query($queryUpdate);

    // email content
    $subject = "Password Recovery";
    $message = "
    Hello " . $dbName . ",
    <p>We received a request to reset your password for DropIT Sharing: <strong>" . $email . "</strong></p>
    <p><a href = 'http://localhost/ICT3103/confirmPasswordReset.php?t=" . $resetPassToken . "'>Click the button to reset your password</a></p>
    <p>Please ignore this message if you didn't ask to change your password.</p>";

    send_mail($email, $subject, $message);

    // redirect 
    header("Location: ../index.php");
    $_SESSION['success_msg'] = "Reset password message has been sent!";
} else {
    $_SESSION['error_msg'] = "Email address not valid!";
}
?>