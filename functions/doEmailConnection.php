<?php

function send_mail($email, $subject, $message) {
    require '../PhpMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl";
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465;
    $mail->AddAddress($email);
    $mail->Username = "3103ict@gmail.com"; // gmail email
    $mail->Password = "haha1234"; // gmail password
    $mail->SetFrom('you@yourdomain.com', 'ICT3103');
    $mail->AddReplyTo("you@yourdomain.com", "ICT3103");
    $mail->Subject = $subject;
    $mail->MsgHTML($message);
    $mail->Send();
}
?>