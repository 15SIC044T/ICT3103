<?php

// start session
session_start();

// include database connection details
include "../db-connection.php";

// function to sanitize values received from the form. Prevents SQL injection
function clean($str) {
    $str = @trim($str);
    if (get_magic_quotes_gpc()) {
        $str = stripslashes($str);
    }
    return mysqli_real_escape_string($str);
}

// sanitize the POST values
$name = $_POST['inputName'];
$password = $_POST['inputPass'];

// connect database
$connection = new Mysql_Driver();
$connection->connect();

// look through database based on name
$queryUser = "SELECT * 
            FROM account 
            WHERE name = '$name'";
$resultUser = $connection->query($queryUser);

// check whether the query is successful or not
if ($connection->num_rows($resultUser) == 1) {
    $user = $connection->fetch_array($resultUser);
    $dbPassHash = $user['password'];
    $dbAccountStatus = $user['accountStatus'];
    $dbToken = $user['verificationToken'];

    // check old password with database
    $verifyPassword = password_verify($password, $dbPassHash);

    // if password valid in database
    if ($verifyPassword == 1) {
        if ($dbAccountStatus == "Unverified") {
            $_SESSION['SESS_ACC_ID'] = $user['accountID'];

            header("Location: ../verifyAccount.php");
        } else {
            $_SESSION['SESS_ACC_ID'] = $user['accountID'];
            $_SESSION['SESS_USERNAME'] = $user['name'];

            header("Location: ../fileManager.php");
        }
    } else {
        header("Location: ../index.php");
        $_SESSION['error_msg'] = "Wrong username/password!";
    }
} else {
    header("Location: ../index.php");
    $_SESSION['error_msg'] = "Wrong username/password!";
}
?>