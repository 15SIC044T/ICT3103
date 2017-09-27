<?php

session_start();

//unset($_SESSION["shoppername"]);
//unset($_SESSION["shopperid"]);

session_destroy();

header("Location: index.php");
exit;
?>