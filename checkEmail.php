<?php
// This is a sample code in case you wish to check the username from a mysql db table
if(isset($_POST['email'])) { //change isSet to isset (it will not make any difference)
    $email = mysql_real_escape_string($_POST['email']); //escape the string
  
    require_once('dbConnection.php');
    $stmt = $conn->prepare("SELECT email FROM account WHERE email= ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
     
        if($result->num_rows > 0) { //check rows greater then zero (although it will also not make any difference)
            echo 'OK';
        } else {
            echo '<font color="red">The email <strong>'.$email.'</strong>'. ' is invalid.</font>';
        }
}
?>