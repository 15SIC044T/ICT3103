<?php

// start session
session_start();

// include database connection details
require_once('../dbConnection.php');

// sanitize the POST values
$resetPassToken = $_SESSION['SESS_TOKEN'];
$password = $_POST['inputPass'];
$confirmPassword = $_POST['inputConfirmPass'];

// password validation
$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number = preg_match('@[0-9]@', $password);

$query = "SELECT name FROM account WHERE resetPasswordToken = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $resetPassToken);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
     
    // do not pass the password validation
    if (!$uppercase || !$lowercase || !$number) {
        header("Location: ../confirmPasswordReset.php?t=$resetPassToken");
        $_SESSION['error_msg'] = "Password should consists of at least one uppercase, lowercase and number!";
    } elseif ($password != $confirmPassword) { // check password not same
        header("Location: ../confirmPasswordReset.php?t=$resetPassToken");
        $_SESSION['error_msg'] = "Password not the same!";
    } else {
        // password hashing
        $confirmPassHash = password_hash($confirmPassword, PASSWORD_BCRYPT);

        $nullValue = 'NULL';

        $queryUpdate = "UPDATE account 
                        SET password = ?, 
                            resetPasswordToken = $nullValue 
                        WHERE resetPasswordToken = ?";
        $stmt = $conn->prepare($queryUpdate);
        $stmt->bind_param("ss", $confirmPassHash, $resetPassToken);
        $stmt->execute();
        $stmt->close();

        header("Location: ../index.php");
        $_SESSION['success_msg'] = "Password changed!";
    }
} else
 {
    header("Location: ../confirmPasswordReset.php?t=$resetPassToken");
    $_SESSION['error_msg'] = "Token has been expired! Please request for a new token!";
}
?>