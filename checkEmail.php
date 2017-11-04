<?php
// This is a sample code in case you wish to check the username from a mysql db table
if(isset($_POST['email'])) { //change isSet to isset (it will not make any difference)
    $email = mysql_real_escape_string($_POST['email']); //escape the string
  
    $sql_check = mysql_query("SELECT email FROM account WHERE email='$email'") or die(mysql_error());
        if(mysql_num_rows($sql_check) > 0) { //check rows greater then zero (although it will also not make any difference)
            echo 'OK';
        } else {
            echo '<font color="red">The email <strong>'.$email.'</strong>'. ' is invalid.</font>';
        }
}
?>