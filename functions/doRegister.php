<?php

// start session
session_start();

// include database connection details
include "../db-connection.php";
include "doEmailConnection.php";

// sanitize the POST values
$name = $_POST['inputName'];
$password = $_POST['inputPass'];
$confirmPassword = $_POST['inputConfirmPass'];
$email = $_POST['inputEmail'];
$mobile = $_POST['inputMobile'];

// connect database
$connection = new Mysql_Driver();
$connection->connect();

// look through database based on name
$queryName = "SELECT * 
            FROM account 
            WHERE name='$name'";
$resultName = $connection->query($queryName);

// look through database based on email
$queryEmail = "SELECT * 
                FROM account 
                WHERE email='$email'";
$resultEmail = $connection->query($queryEmail);

// check for name duplication
if ($connection->num_rows($resultName) == 1) {
    header("Location: ../registerAcc.php");
    $_SESSION['error_msg'] = "Name taken!";
} elseif ($connection->num_rows($resultEmail) == 1) { // check for email duplication
    header("Location: ../registerAcc.php");
    $_SESSION['error_msg'] = "Email address used!";
} elseif (!$uppercase || !$lowercase || !$number) { // do not pass the password validation
    header("Location: ../registerAcc.php");
    $_SESSION['error_msg'] = "Password should consists of at least one uppercase, lowercase and number!";
} elseif ($password != $confirmPassword) { // check if password same
    header("Location: ../registerAcc.php");
    $_SESSION['error_msg'] = "Password not the same!";
} else {
    $confirmPassHash = password_hash($confirmPassword, PASSWORD_BCRYPT); // password hashing
    $accountToken = md5(uniqid(rand(), true)); // token to verify account

    $queryAdd = "INSERT INTO account(name, email, password, phone, accountStatus, verificationToken) 
                VALUES('$name', '$email', '$confirmPassHash', '$mobile', 'Unverified', '$accountToken')";
    $addUser = $connection->query($queryAdd);

    // send verification email
    $queryEmailAgain = "SELECT * 
                        FROM account 
                        WHERE email = '$email'";
    $resultEmailAgain = $connection->query($queryEmailAgain);

    if ($connection->num_rows($resultEmailAgain) == 1) {
        $user = mysqli_fetch_assoc($resultEmailAgain);
        $_SESSION['SESS_ACC_ID'] = $user['accountID'];
        $_SESSION['SESS_USERNAME'] = $user['name'];
        $_SESSION['SESS_TOKEN'] = $user['verificationToken'];

        // email content
        $subject = "Verify DropIT Sharing Account";
        $message = "
                Hello " . $_SESSION['SESS_USERNAME'] . ",
                <p>You have successfully created a DropIT Sharing account.</p>
                <p>Please verify your account with this verification code: <strong>" . $_SESSION['SESS_TOKEN'] . " </strong></p>";

        send_mail($email, $subject, $message);
    }

    header("Location: ../index.php");
    $_SESSION['success_msg'] = "Register Done! Check email to verify account!";
}
?>