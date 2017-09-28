<?php

// start session
session_start();

// include database connection details
include 'db-connection.php';

// array to store validation errors
$errmsg = array();

// validation error flag
$errflag = false;

// function to sanitize values received from the form. Prevents SQL injection
function clean($str) {
    $str = @trim($str);
    if (get_magic_quotes_gpc()) {
        $str = stripslashes($str);
    }
    return mysqli_real_escape_string($str);
}

// sanitize the POST values
$name = ($_POST['user']);
$password = ($_POST['password']);

$connection = new Mysql_Driver();
$connection->connect();

// create query
$qry = "SELECT * FROM account WHERE name='$name' AND password='$password'";
$result = $connection->query($qry);

// check whether the query was successful or not
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        // login successful
        session_regenerate_id();
        $member = mysqli_fetch_assoc($result);
        $_SESSION['SESS_ACC_ID'] = $member['accountID'];
        $_SESSION['SESS_USERNAME'] = $member['name'];
        $_SESSION['SESS_PASSWORD'] = $member['password'];
        session_write_close();
        header("location: fileManager.php");
        exit();
    } else {
        // login failed
        $errmsg[] = 'user name and password not found';
        $errflag = true;
        if ($errflag) {
            $_SESSION['ERRMSG'] = $errmsg;
            session_write_close();
            header("location: index.php");
            exit();
        }
    }
} else {
    die("Query failed");
}
?>