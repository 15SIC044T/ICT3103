<?php

/* this file handles the email function */

function send_mail($subject, $email, $message) {
    require 'PhpMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl";
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465;
    $mail->AddAddress($email);
    $mail->Username = "";
    $mail->Password = "";
    $mail->Subject = $subject;
    $mail->MsgHTML($message);
    $mail->Send();
}

?>