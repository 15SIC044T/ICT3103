<?php

// start session
session_start();

// include database connection details
include "../db-connection.php";

// sanitize the POST values
$userId = $_SESSION['SESS_ACC_ID'];
$name = $_POST['inputName'];
$email = $_POST['inputEmail'];
$mobile = $_POST['inputMobile'];

// connect database
$connection = new Mysql_Driver();
$connection->connect();

// look through database based on accountid
$queryUser = "SELECT * 
            FROM account 
            WHERE accountID = '$userId'";
$resultUser = $connection->query($queryUser);

// look through name on database that is not user
$queryName = "SELECT name 
            FROM account 
            WHERE accountID != '$userId'";
$resultName = $connection->query($queryName);
$otherNames = mysqli_fetch_all($resultName, MYSQLI_ASSOC);

// loop other users' name for duplicate
foreach ($otherNames as $on):
    $oName = $on['name'];
endforeach;

// look through email on database that is not user
$queryEmail = "SELECT email 
                FROM account 
                WHERE accountID != '$userId'";
$resultEmail = $connection->query($queryEmail);
$otherEmails = mysqli_fetch_all($resultEmail, MYSQLI_ASSOC);

// loop other users' email for duplicate
foreach ($otherEmails as $oe):
    $oEmail = $oe['email'];
endforeach;

if ($connection->num_rows($resultUser) == 1) {
    $user = $connection->fetch_array($resultUser);
    $dbName = $user['name'];
    $dbEmail = $user['email'];
    $dbPhone = $user['phone'];

    // check mobile not changed
    if ($dbPhone == $mobile) {
        // name and email not changed
        if ($dbName == $name && $dbEmail == $email) {
            header("Location: ../profile.php");
            $_SESSION['neutral_msg'] = "No changes made!";
        } else { // other info changed
            if ($dbName != $name && $dbEmail == $email) { // name changed, email not
                // check name is duplicate
                if ($oName == $name) {
                    header("Location: ../profile.php");
                    $_SESSION['error_msg'] = "Name taken!";
                } else { // name not duplicate
                    $queryUpdate = "UPDATE account 
                                    SET name = '$name' 
                                    WHERE accountID = $userId";
                    $updateDB = $connection->query($queryUpdate);

                    header("Location: ../profile.php");
                    $_SESSION['success_msg'] = "Changes made!";
                }
            } elseif ($dbName == $name && $dbEmail != $email) { // name not, email changed
                // check email is duplicate
                if ($oEmail == $email) {
                    header("Location: ../profile.php");
                    $_SESSION['error_msg'] = "Email address taken!";
                } else { // email not duplicate
                    $queryUpdate = "UPDATE account 
                                    SET email = '$email' 
                                    WHERE accountID = $userId";
                    $updateDB = $connection->query($queryUpdate);

                    header("Location: ../profile.php");
                    $_SESSION['success_msg'] = "Changes made!";
                }
            }
        }
    } else { // mobile changed
        // name and email not changed
        if ($dbName == $name && $dbEmail == $email) {
            $queryUpdate = "UPDATE account 
                            SET phone = '$mobile' 
                            WHERE accountID = $userId";
            $updateDB = $connection->query($queryUpdate);

            header("Location: ../profile.php");
            $_SESSION['success_msg'] = "Changes made!";
        } else { // other info changed
            if ($dbName != $name && $dbEmail == $email) { // name changed, email not
                // check name is duplicate
                if ($oName == $name) {
                    header("Location: ../profile.php");
                    $_SESSION['error_msg'] = "Name taken!";
                } else { // name not duplicate
                    $queryUpdate = "UPDATE account 
                                    SET name = '$name', 
                                        phone = '$mobile' 
                                    WHERE accountID = $userId";
                    $updateDB = $connection->query($queryUpdate);

                    header("Location: ../profile.php");
                    $_SESSION['success_msg'] = "Changes made!";
                }
            } elseif ($dbName == $name && $dbEmail != $email) { // name not, email changed
                // check email is duplicate
                if ($oEmail == $email) {
                    header("Location: ../profile.php");
                    $_SESSION['error_msg'] = "Email address taken!";
                } else { // email not duplicate
                    $queryUpdate = "UPDATE account 
                                    SET email = '$email', 
                                        phone = '$mobile' 
                                    WHERE accountID = $userId";
                    $updateDB = $connection->query($queryUpdate);

                    header("Location: ../profile.php");
                    $_SESSION['success_msg'] = "Changes made!";
                }
            } else {
                $queryUpdate = "UPDATE account 
                                SET name = '$name', 
                                    email = '$email', 
                                    phone = '$mobile' 
                                WHERE accountID = $userId";
                    $updateDB = $connection->query($queryUpdate);

                    header("Location: ../profile.php");
                    $_SESSION['success_msg'] = "Changes made!";
            }
        }
    }
}
?>