<?php

include 'db-connection.php';
include 'emailFunction.php';

$email = $_POST['email'];

$connection = new Mysql_Driver();
$connection->connect();

echo "$email";

// create query
$qry = "SELECT * FROM account WHERE email ='$email'";
$result = $connection->query($qry);

if ($connection->num_rows($result) == 1) {
    $member = mysqli_fetch_assoc($result);
    $_SESSION['SESS_ACC_ID'] = $member['accountID'];
    $_SESSION['SESS_USERNAME'] = $member['name'];

    // email content
    $subject = "Password Recovery";
    $message = "
       Hello," . $_SESSION['SESS_USERNAME'] . ",
       <p>We received a request to reset your password for DropIT Sharing:</p>" . $email . "
       <a href='http://localhost/ICT3103/confirmPasswordResetPage.php?id='" . $_SESSION['SESS_ACC_ID'] . "'>Click the button to reset your password</a></p>
       <p>Please ignore this message if you didn't ask to change your password.</p>";

    send_mail($subject, $email, $message);
    
    // redirect 
    header("Location: index.php");
} else {
    
}
?>