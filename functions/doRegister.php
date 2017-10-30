<?php

// start session
session_start();

// include database connection details
include "../db-connection.php";
include "doEmailConnection.php";

// sanitize the POST values
$name = $_POST['inputName'];
$password = $_POST['inputPass'];
$confirmPassword = $_POST['inputConfirmPass'];
$email = $_POST['inputEmail'];
$mobile = $_POST['inputMobile'];

$timestamp = date("Y-m-d_H-i-s", time());
$pubPath = '../keys/rsa/' . $name . '_' . $timestamp . '_public.key';

$config = array(
    'private_key_bits' => 4096,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
);

// Create the private key
$privateKey = openssl_pkey_new($config);

// Save the private key
openssl_pkey_export($privateKey, $pkey);

// Generate the public key for the private key
$a_key = openssl_pkey_get_details($privateKey);
// Save the public key
file_put_contents($pubPath, $a_key['key']);

// Free the private Key.
openssl_free_key($privateKey);

// connect database
$connection = new Mysql_Driver();
$connection->connect();

// look through database based on name
$queryName = "SELECT * 
            FROM account 
            WHERE name = ?";
$stmt = $connection->prepare($queryName);
$stmt->bind_param("s", $name);
$stmt->execute();
$resultName = $stmt->get_result();

// look through database based on email
$queryEmail = "SELECT * 
            FROM account 
            WHERE email = ?";
$stmt1 = $connection->prepare($queryEmail);
$stmt1->bind_param("s", $email);
$stmt1->execute();
$resultEmail = $stmt1->get_result();

// password validation
$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number = preg_match('@[0-9]@', $password);

// check for name duplication
if ($connection->num_rows($resultName) == 1) {
    header("Location: ../registerAcc.php");
    $_SESSION['error_msg'] = "Name taken!";
} elseif ($connection->num_rows($resultEmail) == 1) { // check for email duplication
    header("Location: ../registerAcc.php");
    $_SESSION['error_msg'] = "Email address used!";
} elseif (!$uppercase || !$lowercase || !$number) { // do not pass the password validation
    header("Location: ../registerAcc.php");
    $_SESSION['error_msg'] = "Password should consists of at least one uppercase, lowercase and number!";
} elseif (preg_match('#($name)#', $password)) { // password should not contain name
    header("Location: ../registerAcc.php");
    $_SESSION['error_msg'] = "Password should not contain username!";
} elseif ($password != $confirmPassword) { // check if password same
    header("Location: ../registerAcc.php");
    $_SESSION['error_msg'] = "Password not the same!";
} else {
    $confirmPassHash = password_hash($confirmPassword, PASSWORD_BCRYPT); // password hashing
    $accountToken = md5(uniqid(rand(), true)); // token to verify account

    $queryAdd = "INSERT INTO account(name, email, password, phone, accountStatus, verificationToken, publicKey) 
                VALUES(?, ?, ?, ?, 'Unverified', ?, ?)";
    $stmt = $connection->prepare($queryAdd);
    $stmt->bind_param("ssssss", $name, $email, $confirmPassHash, $mobile, $accountToken, $pubPath);
    $stmt->execute();

    // send verification email
    $queryEmailAgain = "SELECT * 
                        FROM account 
                        WHERE email = ?";
    $stmt1 = $connection->prepare($queryEmailAgain);
    $stmt1->bind_param("s", $email);
    $stmt1->execute();
    $resultEmailAgain = $stmt1->get_result();

    if ($connection->num_rows($resultEmailAgain) == 1) {
        $user = mysqli_fetch_assoc($resultEmailAgain);
        $_SESSION['SESS_ACC_ID'] = $user['accountID'];
        $_SESSION['SESS_USERNAME'] = $user['name'];
        $_SESSION['SESS_TOKEN'] = $user['verificationToken'];

        // email content
        $subject = "Verify DropIT Sharing Account";
        $message = "
                Hello " . $_SESSION['SESS_USERNAME'] . ",
                <p>You have successfully created a DropIT Sharing account.</p>
                <p>Please verify your account with this verification code: <strong>" . $_SESSION['SESS_TOKEN'] . " </strong></p>";

        send_mail($email, $subject, $message);
    }

    header("Location: ../index.php");
    $_SESSION['success_msg'] = "Register Done! Check email to verify account!";

    // to display private key to the user
    $_SESSION['REGISTER_OK'] = "success";
    $_SESSION['KEY'] = $pkey;
}
?>