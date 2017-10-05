<?php

// start session
session_start();

// include database connection details
include '../db-connection.php';

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
$password = sha1($_POST['inputPass']);

// connect database
$connection = new Mysql_Driver();
$connection->connect();

// create query
$queryUser = "SELECT * 
            FROM account 
            WHERE name='$name' AND password='$password'";
$resultUser = $connection->query($queryUser);

// check whether the query was successful or not
if ($connection->num_rows($resultUser) == 1) {
    $user = $connection->fetch_array($resultUser);

    $_SESSION['SESS_ACC_ID'] = $user['accountID'];
    $_SESSION['SESS_USERNAME'] = $user['name'];
    $_SESSION['SESS_PASSWORD'] = $user['password'];

    header("Location: ../fileManager.php");
} else {
    header("Location: ../index.php");
    $_SESSION['error_msg'] = "Wrong username/password!";
}
?>