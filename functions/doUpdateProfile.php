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
            WHERE accountID = ?";
$stmt = $connection->prepare($queryUser);
$stmt->bind_param("i", $userId);
$stmt->execute();
$resultUser = $stmt->get_result();

// look through name on database that is not user
$queryName = "SELECT name 
            FROM account 
            WHERE accountID != ?";
$stmt1 = $connection->prepare($queryName);
$stmt1->bind_param("i", $userId);
$stmt1->execute();
$resultName = $stmt1->get_result();
$otherNames = mysqli_fetch_all($resultName, MYSQLI_ASSOC);

// look through email on database that is not user
$queryEmail = "SELECT email 
            FROM account 
            WHERE accountID != ?";
$stmt2 = $connection->prepare($queryEmail);
$stmt2->bind_param("i", $userId);
$stmt2->execute();
$resultEmail = $stmt2->get_result();
$otherEmails = mysqli_fetch_all($resultEmail, MYSQLI_ASSOC);

if ($connection->num_rows($resultUser) == 1) {
    $user = $resultUser->fetch_array();
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
                // loop other users' name for duplicate
                foreach ($otherNames as $on):
                    $oName = $on['name'];

                    // check name is duplicate
                    if ($oName == $name) {
                        header("Location: ../profile.php");
                        $_SESSION['error_msg'] = "Name taken!";
                        exit();
                    }
                endforeach;

                // name not duplicate
                $queryUpdate = "UPDATE account 
                                SET name = ? 
                                WHERE accountID = ?";
                $stmt = $connection->prepare($queryUpdate);
                $stmt->bind_param("si", $name, $userId);
                $stmt->execute();

                header("Location: ../profile.php");
                $_SESSION['success_msg'] = "Changes made!";
            } elseif ($dbName == $name && $dbEmail != $email) { // name not, email changed
                // loop other users' email for duplicate
                foreach ($otherEmails as $oe):
                    $oEmail = $oe['email'];

                    // check email is duplicate
                    if ($oEmail == $email) {
                        header("Location: ../profile.php");
                        $_SESSION['error_msg'] = "Email address taken!";
                        exit();
                    }
                endforeach;

                // email not duplicate
                $queryUpdate = "UPDATE account 
                                SET email = ? 
                                WHERE accountID = ?";
                $stmt = $connection->prepare($queryUpdate);
                $stmt->bind_param("si", $email, $userId);
                $stmt->execute();

                header("Location: ../profile.php");
                $_SESSION['success_msg'] = "Changes made!";
            } else {
                $queryUpdate = "UPDATE account 
                                SET name = ?, 
                                    email = ? 
                                WHERE accountID = ?";
                $stmt = $connection->prepare($queryUpdate);
                $stmt->bind_param("ssi", $name, $email, $userId);
                $stmt->execute();

                header("Location: ../profile.php");
                $_SESSION['success_msg'] = "Changes made!";
            }
        }
    } else { // mobile changed
        // name and email not changed
        if ($dbName == $name && $dbEmail == $email) {
            $queryUpdate = "UPDATE account 
                            SET phone = ? 
                            WHERE accountID = ?";
            $stmt = $connection->prepare($queryUpdate);
            $stmt->bind_param("si", $mobile, $userId);
            $stmt->execute();

            header("Location: ../profile.php");
            $_SESSION['success_msg'] = "Changes made!";
        } else { // other info changed
            if ($dbName != $name && $dbEmail == $email) { // name changed, email not
                // loop other users' name for duplicate
                foreach ($otherNames as $on):
                    $oName = $on['name'];

                    // check name is duplicate
                    if ($oName == $name) {
                        header("Location: ../profile.php");
                        $_SESSION['error_msg'] = "Name taken!";
                        exit();
                    }
                endforeach;

                // name not duplicate
                $queryUpdate = "UPDATE account 
                                SET name = ?, 
                                    phone = ? 
                                WHERE accountID = ?";
                $stmt = $connection->prepare($queryUpdate);
                $stmt->bind_param("ssi", $name, $mobile, $userId);
                $stmt->execute();

                header("Location: ../profile.php");
                $_SESSION['success_msg'] = "Changes made!";
            } elseif ($dbName == $name && $dbEmail != $email) { // name not, email changed
                // loop other users' email for duplicate
                foreach ($otherEmails as $oe):
                    $oEmail = $oe['email'];

                    // check email is duplicate
                    if ($oEmail == $email) {
                        header("Location: ../profile.php");
                        $_SESSION['error_msg'] = "Email address taken!";
                        exit();
                    }
                endforeach;

                // email not duplicate
                $queryUpdate = "UPDATE account 
                                SET email = ?, 
                                    phone = ? 
                                WHERE accountID = ?";
                $stmt = $connection->prepare($queryUpdate);
                $stmt->bind_param("ssi", $email, $mobile, $userId);
                $stmt->execute();

                header("Location: ../profile.php");
                $_SESSION['success_msg'] = "Changes made!";
            } else {
                $queryUpdate = "UPDATE account 
                                SET name = ?, 
                                    email = ?, 
                                    phone = ? 
                                WHERE accountID = ?";
                $stmt = $connection->prepare($queryUpdate);
                $stmt->bind_param("sssi", $name, $email, $mobile, $userId);
                $stmt->execute();

                header("Location: ../profile.php");
                $_SESSION['success_msg'] = "Changes made!";
            }
        }
    }
}
?>