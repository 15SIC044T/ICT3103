<?php

// start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();  
    
    if (isset($_SESSION['canary'])) {
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
        if ($_SESSION['canary']['birth'] < time() - 180) {
            session_regenerate_id(true);
            $_SESSION['canary']['birth'] = time();
        }
    } 

    // include database connection details 
    require_once('dbConnection.php');  
} 

?>