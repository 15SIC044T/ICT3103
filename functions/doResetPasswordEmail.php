<?php

// include database connection details
include '../db-connection.php';
include 'doEmailConnection.php';

// sanitize the POST values
$email = $_POST['email'];

// connect database
$connection = new Mysql_Driver();
$connection->connect();

// create query
$queryEmail = "SELECT * 
                FROM account 
                WHERE email = '$email'";
$resultEmail = $connection->query($queryEmail);

if ($connection->num_rows($resultEmail) == 1) {
    $user = mysqli_fetch_assoc($resultEmail);
    $_SESSION['SESS_ACC_ID'] = $user['accountID'];
    $_SESSION['SESS_USERNAME'] = $user['name'];

    // email content
    $subject = "Password Recovery";
    $message = "
       Hello " . $_SESSION['SESS_USERNAME'] . ",
       <p>We received a request to reset your password for DropIT Sharing: <strong>" . $email . "</strong></p>
       <p><a href='http://localhost/ICT3103/confirmPasswordReset.php?id=" . $_SESSION['SESS_ACC_ID'] . "'>Click the button to reset your password</a></p>
       <p>Please ignore this message if you didn't ask to change your password.</p>";

    send_mail($subject, $email, $message);

    // redirect 
    header("Location: ../index.php");

    echo $_SESSION['SESS_ACC_ID'] . "<br>";
    echo $_SESSION['SESS_USERNAME'] . "<br>";
    echo $subject . "<br>";
    echo $message . "<br>";
    echo "<br><br><br>";
} else {
    
}
?>