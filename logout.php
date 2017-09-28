<?php

session_start();

unset($_SESSION["name"]);
unset($_SESSION["accountID"]);

session_destroy();

header("Location: index.php");
exit;
?>