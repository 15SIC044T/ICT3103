<?php

session_start();

$_SESSION['OKMSG'] = "You have successfully logged out!";

session_destroy();

header("Location: ../index.php");
exit;
?>