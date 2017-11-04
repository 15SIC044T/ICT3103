<?php

// start session
session_start();

// include database connection details
require_once('../dbConnection.php');

// sanitize the POST values
$email = $_POST['inputEmail'];
$password = $_POST['inputPass']; 

// look through database based on name
$queryUser = "SELECT * 
            FROM account 
            WHERE email = ?";
$stmt = $connection->prepare($queryUser);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultUser = $stmt->get_result();

// check whether the query is successful or not
if ($connection->num_rows($resultUser) == 1) {
    $user = $resultUser->fetch_array();
    $dbUserId = $user['accountID'];
    $dbPassHash = $user['password'];
    $dbAccountStatus = $user['accountStatus'];
    $dbToken = $user['verificationToken'];
    $dbLoginCount = $user['failLoginCount'];
    $dbLoginTime = $user['failLoginTime'];

    // check old password with database
    $verifyPassword = password_verify($password, $dbPassHash);

    // if database has failLoginTime
    if (!is_null($dbLoginTime)) {
        date_default_timezone_set('Asia/Singapore');

        $timeBlock = new DateTime($dbLoginTime); // datetime that account is locked
        $timeBlock->add(new DateInterval('PT15M')); // datetime after 15min
        $timeUntil = $timeBlock->format('Y-m-d H:i:s'); // convert datetime to string
        $timeNow = date("Y-m-d H:i:s"); // current datetime
        // if over 15min
        if ($timeUntil <= $timeNow) {
            $timeNull = 'NULL';

            // set time period to NULL
            $queryTimeUpdate = "UPDATE account 
                                SET failLoginTime = $timeNull 
                                WHERE accountID = ?";
            $stmt = $connection->prepare($queryTimeUpdate);
            $stmt->bind_param("i", $dbUserId);
            $stmt->execute();

            // verify the password again
            if ($verifyPassword == 1) {
                if ($dbAccountStatus == "Unverified") {
                    $_SESSION['SESS_ACC_ID'] = $user['accountID'];

                    header("Location: ../verifyAccount.php");
                } else {
                    $_SESSION['SESS_ACC_ID'] = $user['accountID'];
                    $_SESSION['SESS_USERNAME'] = $user['name'];

                    header("Location: ../fileManager.php");
                }
            } else { // start the count again
                $count = $dbLoginCount + 1;

                $queryCountUpdate = "UPDATE account 
                                    SET failLoginCount = ? 
                                    WHERE accountID = ?";
                $stmt = $connection->prepare($queryCountUpdate);
                $stmt->bind_param("ii", $count, $dbUserId);
                $stmt->execute();

                header("Location: ../index.php");
                $_SESSION['error_msg'] = "Wrong username/password!";
            }
        } else { // not over 15min yet
            header("Location: ../index.php");
            $_SESSION['error_msg'] = "You have exceed the number of tries! Try again later!";
        }
    } else { // no failLoginTime
        // check number of login attempt
        if ($dbLoginCount == 2) { // already failed two attempts
            // correct for the third time
            if ($verifyPassword == 1) {
                if ($dbAccountStatus == "Unverified") {
                    $_SESSION['SESS_ACC_ID'] = $user['accountID'];

                    header("Location: ../verifyAccount.php");
                } else {
                    $_SESSION['SESS_ACC_ID'] = $user['accountID'];
                    $_SESSION['SESS_USERNAME'] = $user['name'];

                    header("Location: ../fileManager.php");
                }
            } else { // if user enter wrong password for the third time
                // set time
                $queryTimeUpdate = "UPDATE account 
                                    SET failLoginTime = now() 
                                    WHERE accountID = ?";
                $stmt = $connection->prepare($queryTimeUpdate);
                $stmt->bind_param("i", $dbUserId);
                $stmt->execute();

                header("Location: ../index.php");
                $_SESSION['error_msg'] = "You have exceed the number of tries! Try again later!";
            }

            // reset the count to 0 regardless
            $queryCountUpdate = "UPDATE account 
                                SET failLoginCount = 0 
                                WHERE accountID = ?";
            $stmt = $connection->prepare($queryCountUpdate);
            $stmt->bind_param("i", $dbUserId);
            $stmt->execute();
        } else { // within two attempts to login
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

                // reset the count to 0 regardless
                $queryCountUpdate = "UPDATE account 
                                    SET failLoginCount = 0 
                                    WHERE accountID = ?";
                $stmt = $connection->prepare($queryCountUpdate);
                $stmt->bind_param("i", $dbUserId);
                $stmt->execute();
            } else { // wrong password entered, start the count
                $count = $dbLoginCount + 1;

                $queryCountUpdate = "UPDATE account 
                                    SET failLoginCount = ? 
                                    WHERE accountID = ?";
                $stmt = $connection->prepare($queryCountUpdate);
                $stmt->bind_param("ii", $count, $dbUserId);
                $stmt->execute();

                header("Location: ../index.php");
                $_SESSION['error_msg'] = "Wrong username/password!";
            }
        }
    }
} else {
    header("Location: ../index.php");
    $_SESSION['error_msg'] = "Wrong username/password!";
}
$stmt->close();
?>