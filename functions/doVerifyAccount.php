<?php

// start session
session_start();

// include database connection details
require_once('../dbConnection.php');

// sanitize the POST values
$userId = $_POST['userID'];
$verifyToken = $_POST['inputToken']; 

// look through database based on name
$queryUser = "SELECT * 
            FROM account 
            WHERE accountID = ?";
$stmt = $conn->prepare($queryUser);
$stmt->bind_param("i", $userId);
$stmt->execute();
$resultUser = $stmt->get_result();

// check whether the query is successful or not
if ($resultUser->num_rows == 1) {
    $user = $resultUser->fetch_array();
    $dbName = $user['name'];
    $dbToken = $user['verificationToken'];
    $nullValue = 'NULL';

    if ($dbToken == $verifyToken) {
        $queryUpdate = "UPDATE account 
                        SET accountStatus = 'Verified', 
                            verificationToken = $nullValue 
                        WHERE accountID = ?";
        $stmt = $conn->prepare($queryUpdate);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

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
$stmt->close();
?>