<?php

// include database connection details
require_once('../dbConnection.php');
include "doEmailConnection.php";

// sanitize the POST values
$email = $_POST['email']; 

// create query
$queryEmail = "SELECT * 
                FROM account 
                WHERE email = ?";
$stmt = $conn->prepare($queryEmail);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultEmail = $stmt->get_result();

if ($resultEmail->num_rows == 1) {
    $user = mysqli_fetch_assoc($resultEmail);
    $dbAccountId = $user['accountID'];
    $dbName = $user['name'];

    // token to reset password
    $resetPassToken = md5(uniqid(rand(), true));

    $queryUpdate = "UPDATE account 
                    SET resetPasswordToken = ? 
                    WHERE accountID = ?";
    $stmt = $conn->prepare($queryUpdate);
    $stmt->bind_param("si", $resetPassToken, $dbAccountId);
    $stmt->execute();

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
$stmt->close();
?>