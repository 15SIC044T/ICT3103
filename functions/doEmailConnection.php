<?php

/* this file handles the email function */

function send_mail($subject, $email, $message) {
    require '../PhpMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 2;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "SSL";
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465;
    $mail->AddAddress($email);
    $mail->Username = "";
    $mail->Password = "";
    $mail->SetFrom("donotreply@gmail.com");
    $mail->Subject = $subject;
    $mail->MsgHTML($message);
    $mail->Send();

    if (!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        $_SESSION['success_msg'] = "Reset password message has been sent!";
    }
}

?>