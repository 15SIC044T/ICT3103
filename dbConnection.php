<?php
$servername = "localhost"; //localhost
$username = "root";   //root access (Enable this when on Localhost)
$password = "";    //root password (Enable this when on Localhost)
$dbname = "ict3103"; //database
// Create connection
$conn = new mysqli($servername, $username, $password);

// Select Database
$db_selected = mysqli_select_db($conn, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    //echo "Connection failed!!";
}
?>
