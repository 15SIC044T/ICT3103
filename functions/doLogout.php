<?php

// start session
session_start();

$_SESSION['success_msg'] = "You have successfully logged out!";

header("Location: ../index.php");
exit;
?>