<?php

// start session
session_start();

// include database connection details
include '../db-connection.php';

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
    header("location: ../fileManager.php");
} else {
    header("location: ../index.php");
    $_SESSION['ERRMSG'] = "Wrong username/password!";
}
?>