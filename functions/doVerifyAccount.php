<?php

// start session
session_start();

// include database connection details
include "../db-connection.php";

// sanitize the POST values
$userId = $_POST['userID'];
$verifyToken = $_POST['inputToken'];

// connect database
$connection = new Mysql_Driver();
$connection->connect();

// look through database based on name
$queryUser = "SELECT * 
                FROM account 
                WHERE accountID = $userId";
$resultUser = $connection->query($queryUser);

// check whether the query is successful or not
if ($connection->num_rows($resultUser) == 1) {
    $user = $connection->fetch_array($resultUser);
    $dbName = $user['name'];
    $dbToken = $user['verificationToken'];
    $nullValue = 'NULL';

    if ($dbToken == $verifyToken) {
        $queryUpdate = "UPDATE account 
                        SET accountStatus = 'Verified', 
                            verificationToken = $nullValue 
                        WHERE accountID = $userId";
        $updateDB = $connection->query($queryUpdate);
        
        $_SESSION['SESS_USERNAME'] = $dbName;

        header("Location: ../fileManager.php");
        $_SESSION['success_msg'] = "Account verified!";
    } else { // wrong token
        header("Location: ../verifyAccount.php");
        $_SESSION['error_msg'] = "Wrong verification code!";
    }
} else {
    header("Location: ../index.php");
    $_SESSION['error_msg'] = "Wrong username/password!";
}
?>