<?php

// start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();  
    
    if ($_SESSION['canary']['IP'] !== $_SERVER['REMOTE_ADDR']) {
        session_regenerate_id(true);
        // Delete everything:
        foreach (array_keys($_SESSION) as $key) {
            unset($_SESSION[$key]);
        }
        $_SESSION['canary'] = [
            'birth' => time(),
            'IP' => $_SERVER['REMOTE_ADDR']
        ];
    }
    // Regenerate session ID every five minutes:
    if ($_SESSION['canary']['birth'] < time() - 300) {
        session_regenerate_id(true);
        $_SESSION['canary']['birth'] = time();
    }
    
    // Check if user logged in 
    if (! isset($_SESSION["SESS_ACC_ID"])) 
    {
            // redirect to login page if the session variable shopperid is not set
            header ("Location: index.php");
            exit;
    }

    // include database connection details 
    require_once('dbConnection.php'); 
} 

?>