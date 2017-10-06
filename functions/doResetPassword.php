<?php

// start session
session_start();

// include database connection details
include 'db-connection.php';

echo $_SESSION['SESS_ACC_ID']

/*if (isset($_POST['resetPassword'])) {
    $getUserId = $_POST['user_id'];
    $newPassword = sha1($_POST['inputNew']);
    $confirmPassword = sha1($_POST['inputConfirm']);

    $queryData = "SELECT * 
                FROM user 
                WHERE user_id = $getUserId";
    $getResult = mysqli_query($link, $queryData);

    if (mysqli_num_rows($getResult) == 1) {
        if ($newPassword == $confirmPassword) {
            $queryUpdate = "UPDATE user 
                            SET password = '$confirmPassword' 
                            WHERE user_id = $getUserId";
            $updateDB = mysqli_query($link, $queryUpdate);

            header("Location: ../index.php");
            $_SESSION['ok'] = "Password reset!";
        } else { // mismatched
            header("Location: ../confirmPasswordResetPage.php?id=$getUserId");
            $_SESSION['err'] = "Password not the same!";
        }
    }
}*/
?>