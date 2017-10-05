<?php

session_start();

$_SESSION['success_msg'] = "You have successfully logged out!";

session_destroy();

header("Location: ../index.php");
exit;
?>